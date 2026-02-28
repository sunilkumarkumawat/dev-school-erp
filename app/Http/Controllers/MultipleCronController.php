<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AttendanceMark;
use App\Models\AttendanceSetting;
use App\Models\FirebaseToken;
use App\Models\Master\Weekendcalendar;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use App\Services\FcmDirectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Session;

class MultipleCronController extends Controller
{
    public function testBiometricAttendance(Request $request)
    {
        $request->validate([
            'attendance_unique_id' => 'required|string|max:100',
            'date' => 'required|date_format:Y-m-d',
            'time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
        ]);

        $attendanceUniqueId = trim((string) $request->input('attendance_unique_id'));
        $date = (string) $request->input('date');
        $timeInput = trim((string) $request->input('time'));
        $time = strlen($timeInput) === 5 ? ($timeInput . ':00') : $timeInput;
        $now = now();

        DB::table('biomatric_attendance')->insert([
            'attendance_unique_id' => $attendanceUniqueId,
            'date' => $date,
            'time' => $time,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Biometric attendance test row saved.',
            'data' => [
                'attendance_unique_id' => $attendanceUniqueId,
                'date' => $date,
                'time' => $time,
            ],
        ]);
    }

    private function isHolidayDate(int $branchId, int $sessionId, string $date): bool
    {
        return Weekendcalendar::leftJoin('attendance_status', 'attendance_status.id', '=', 'weekendcalendar.attendance_status')
            ->where('weekendcalendar.branch_id', $branchId)
            ->where('weekendcalendar.session_id', $sessionId)
            ->whereDate('weekendcalendar.date', $date)
            ->whereRaw("LOWER(COALESCE(attendance_status.name, '')) = ?", ['holiday'])
            ->exists();
    }

    private function normalizeAttendanceStatusName(?string $name, string $fallback = 'holiday'): string
    {
        $value = strtolower(trim((string) $name));
        if ($value === '') {
            return $fallback;
        }

        $value = preg_replace('/[^a-z0-9]+/', '_', $value);
        $value = trim((string) $value, '_');

        return $value !== '' ? $value : $fallback;
    }

    private function checkCalendarOrSundayAttendanceForToday(int $branchId, int $sessionId): array
    {
        $today = Carbon::today();
        $date = $today->toDateString();

        if ($branchId <= 0 || $sessionId <= 0) {
            return [
                'triggered' => false,
                'date' => $date,
                'reason' => 'Invalid branch/session context',
            ];
        }

        $event = Weekendcalendar::query()
            ->leftJoin('attendance_status', 'attendance_status.id', '=', 'weekendcalendar.attendance_status')
            ->where('weekendcalendar.branch_id', $branchId)
            ->where('weekendcalendar.session_id', $sessionId)
            ->whereDate('weekendcalendar.date', $date)
            ->orderBy('weekendcalendar.id', 'asc')
            ->select([
                'weekendcalendar.id',
                'weekendcalendar.event_title',
                'weekendcalendar.event_description',
                'weekendcalendar.auto_message_enabled',
                'weekendcalendar.message_services',
                'weekendcalendar.auto_message_text',
                'attendance_status.name as attendance_status_name',
            ])
            ->first();

        if ($event) {
            return [
                'triggered' => true,
                'source' => 'academic_calendar',
                'date' => $date,
                // Any academic calendar event/date should mark holiday before absent logic runs.
                'status' => 'holiday',
                'event' => [
                    'id' => (int) ($event->id ?? 0),
                    'title' => (string) ($event->event_title ?? ''),
                    'description' => (string) ($event->event_description ?? ''),
                    'attendance_status_name' => (string) ($event->attendance_status_name ?? ''),
                    'auto_message_enabled' => (int) ($event->auto_message_enabled ?? 0),
                    'message_services' => (string) ($event->message_services ?? 'whatsapp,firebase,sms'),
                    'auto_message_text' => (string) ($event->auto_message_text ?? ''),
                ],
            ];
        }

        if ($today->isSunday()) {
            return [
                'triggered' => true,
                'source' => 'sunday',
                'date' => $date,
                'status' => 'holiday',
                'event' => null,
            ];
        }

        return [
            'triggered' => false,
            'date' => $date,
            'reason' => 'No academic calendar event and not Sunday',
        ];
    }

    private function buildCalendarAutoMessageForRecipient(string $messageTemplate, string $name, string $date): string
    {
        $dateStr = Carbon::parse($date)->format('d/m/Y');
        $name = trim($name) !== '' ? trim($name) : 'User';

        $custom = trim((string) $messageTemplate);
        if ($custom === '') {
            return "Holiday/Event update for {$name} on {$dateStr}.";
        }

        return str_replace(
            ['{name}', '{date}'],
            [$name, $dateStr],
            $custom
        );
    }

    private function queueAcademicCalendarAutoMessages(int $branchId, int $sessionId, string $date, array $calendarCheck, array $serviceFilter = []): array
    {
        if (!Schema::hasTable('attendance_messages')) {
            return [
                'enabled' => false,
                'queued' => 0,
                'services' => [],
                'attendance_unique_ids' => [],
                'skipped_reason' => 'attendance_messages table not found',
            ];
        }

        $event = (array) ($calendarCheck['event'] ?? []);
        $enabled = (int) ($event['auto_message_enabled'] ?? 0) === 1;
        $messageText = trim((string) ($event['auto_message_text'] ?? ''));

        if (!$enabled || $messageText === '') {
            return [
                'enabled' => false,
                'queued' => 0,
                'services' => [],
                'attendance_unique_ids' => [],
                'skipped_reason' => 'Academic calendar auto message is disabled or empty for today',
            ];
        }

        $services = $this->normalizedServices($event['message_services'] ?? ['whatsapp', 'firebase', 'sms']);
        if (!empty($serviceFilter)) {
            $services = array_values(array_intersect($services, $this->normalizedServices($serviceFilter)));
        }
        if (empty($services)) {
            return [
                'enabled' => true,
                'queued' => 0,
                'services' => [],
                'attendance_unique_ids' => [],
                'skipped_reason' => 'No matching messaging services after filter',
            ];
        }

        $marks = AttendanceMark::query()
            ->where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->whereDate('date', $date)
            ->where('status', 'holiday')
            ->get();

        $queued = 0;
        $queuedUniqueIds = [];
        $serviceCounts = array_fill_keys($services, 0);

        foreach ($marks as $mark) {
            $uid = trim((string) ($mark->unique_id ?? ''));
            if ($uid === '') {
                continue;
            }

            $entity = $this->resolveEntity($uid, $branchId, $sessionId);
            if (!$entity) {
                continue;
            }

            $mobile = trim((string) ($entity['mobile'] ?? ''));
            $firebaseToken = $this->resolveFirebaseToken($entity);
            $recipientName = trim((string) ($entity['display_name'] ?? $uid));
            $finalMessage = $this->buildCalendarAutoMessageForRecipient($messageText, $recipientName, $date);

            foreach ($services as $service) {
                if (($service === 'whatsapp' || $service === 'sms') && $mobile === '') {
                    continue;
                }
                if ($service === 'firebase' && $firebaseToken === '') {
                    continue;
                }

                $existingSentOrPending = DB::table('attendance_messages')
                    ->where('branch_id', $branchId)
                    ->where('session_id', $sessionId)
                    ->where('attendance_unique_id', $uid)
                    ->where('service', $service)
                    ->whereDate('attendance_date', $date)
                    ->whereIn('status', [0, 1])
                    ->where('payload', 'like', '%"calendar_auto_message":true%')
                    ->where('payload', 'like', '%"calendar_date":"' . $date . '"%')
                    ->exists();

                if ($existingSentOrPending) {
                    continue;
                }

                $payload = [
                    'calendar_auto_message' => true,
                    'calendar_date' => $date,
                    'calendar_source' => (string) ($calendarCheck['source'] ?? 'academic_calendar'),
                    'calendar_event_id' => (int) ($event['id'] ?? 0),
                    'calendar_event_title' => (string) ($event['title'] ?? ''),
                    'calendar_event_description' => (string) ($event['description'] ?? ''),
                    'entity_type' => (string) ($entity['entity_type'] ?? ''),
                    'service' => $service,
                    'name' => $recipientName,
                    'date' => $date,
                    'status' => 'holiday',
                    'message_template' => $finalMessage,
                ];

                DB::table('attendance_messages')->insert([
                    'attendance_mark_id' => (int) ($mark->id ?? 0),
                    'branch_id' => $branchId,
                    'session_id' => $sessionId,
                    'attendance_unique_id' => $uid,
                    'service' => $service,
                    'mobile' => ($service === 'whatsapp' || $service === 'sms') ? $mobile : null,
                    'firebase_token' => $service === 'firebase' ? $firebaseToken : null,
                    'attendance_date' => $date,
                    'in_time' => null,
                    'out_time' => null,
                    'attendance_status' => 'holiday',
                    'status' => 0,
                    'payload' => json_encode($payload),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $queued++;
                $serviceCounts[$service] = (int) ($serviceCounts[$service] ?? 0) + 1;
                $queuedUniqueIds[] = $uid;
            }
        }

        return [
            'enabled' => true,
            'queued' => $queued,
            'services' => $services,
            'service_counts' => $serviceCounts,
            'attendance_unique_ids' => array_values(array_unique($queuedUniqueIds)),
            'message_preview' => $messageText,
        ];
    }

    private function markAllAttendanceForStatusWithoutMessaging(int $branchId, int $sessionId, string $date, string $status): array
    {
        $status = $this->normalizeAttendanceStatusName($status, 'holiday');

        $students = Admission::query()
            ->select('attendance_unique_id')
            ->where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->where('status', 1)
            ->whereNotNull('attendance_unique_id')
            ->whereRaw("TRIM(attendance_unique_id) != ''")
            ->get()
            ->map(fn ($row) => [
                'unique_id' => trim((string) $row->attendance_unique_id),
                'entity_type' => 'student',
            ]);

        $staff = User::query()
            ->select('attendance_unique_id', 'role_id')
            ->where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->where('status', 1)
            ->whereNotNull('attendance_unique_id')
            ->whereRaw("TRIM(attendance_unique_id) != ''")
            ->get()
            ->map(fn ($row) => [
                'unique_id' => trim((string) $row->attendance_unique_id),
                'entity_type' => ((int) ($row->role_id ?? 0) === 3) ? 'student' : 'staff',
            ]);

        $all = [];
        foreach ($students->concat($staff) as $row) {
            $uid = trim((string) ($row['unique_id'] ?? ''));
            if ($uid === '') {
                continue;
            }
            if (!isset($all[$uid])) {
                $all[$uid] = $row;
            }
        }

        $createdOrUpdated = 0;
        $unchanged = 0;

        if (!empty($all)) {
            $alreadyProcessedCount = AttendanceMark::query()
                ->where('branch_id', $branchId)
                ->where('session_id', $sessionId)
                ->whereDate('date', $date)
                ->whereIn('unique_id', array_keys($all))
                ->where('status', $status)
                ->whereNull('in_time')
                ->whereNull('out_time')
                ->count();

            if ($alreadyProcessedCount === count($all)) {
                return [
                    'date' => $date,
                    'status' => $status,
                    'total_candidates' => count($all),
                    'created_or_updated' => 0,
                    'unchanged' => count($all),
                    'already_processed' => true,
                    'messages_created' => 0,
                    'messages_dispatched' => 0,
                ];
            }
        }

        foreach ($all as $uid => $row) {
            $mark = AttendanceMark::where('unique_id', $uid)
                ->where('date', $date)
                ->where('branch_id', $branchId)
                ->where('session_id', $sessionId)
                ->first();

            $newValues = [
                'entity_type' => (string) ($row['entity_type'] ?? 'staff'),
                'status' => $status,
                'in_time' => null,
                'out_time' => null,
            ];

            $isChanged = true;
            if ($mark) {
                $isChanged =
                    (string) ($mark->entity_type ?? '') !== (string) $newValues['entity_type'] ||
                    (string) ($mark->status ?? '') !== (string) $newValues['status'] ||
                    !empty($mark->in_time) ||
                    !empty($mark->out_time);
            }

            if (!$isChanged) {
                $unchanged++;
                continue;
            }

            AttendanceMark::updateOrCreate(
                [
                    'unique_id' => $uid,
                    'date' => $date,
                    'branch_id' => $branchId,
                    'session_id' => $sessionId,
                ],
                [
                    'entity_type' => $newValues['entity_type'],
                    'status' => $newValues['status'],
                    'in_time' => null,
                    'out_time' => null,
                    'created_by' => Session::get('id') ?: 0,
                ]
            );

            $createdOrUpdated++;
        }

        return [
            'date' => $date,
            'status' => $status,
            'total_candidates' => count($all),
            'created_or_updated' => $createdOrUpdated,
            'unchanged' => $unchanged,
            'already_processed' => $createdOrUpdated === 0 && $unchanged === count($all),
            'messages_created' => 0,
            'messages_dispatched' => 0,
        ];
    }

    private function resolveEntity(string $attendanceUniqueId, int $branchId, int $sessionId): ?array
    {
        $student = Admission::select('id', 'branch_id', 'session_id', 'mobile', 'first_name', 'last_name')
            ->where('attendance_unique_id', $attendanceUniqueId)
            ->where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->first();

        if (!$student) {
            $student = Admission::select('id', 'branch_id', 'session_id', 'mobile', 'first_name', 'last_name')
                ->where('attendance_unique_id', $attendanceUniqueId)
                ->first();
        }

        if ($student) {
            return [
                'entity_type' => 'student',
                'entity_id' => (int) $student->id,
                'attendance_unique_id' => $attendanceUniqueId,
                'branch_id' => (int) ($student->branch_id ?? $branchId),
                'session_id' => (int) ($student->session_id ?? $sessionId),
                'mobile' => (string) ($student->mobile ?? ''),
                'firebase_token' => '',
                'display_name' => trim((string) (($student->first_name ?? '') . ' ' . ($student->last_name ?? ''))) ?: $attendanceUniqueId,
            ];
        }

        $staff = User::select('id', 'branch_id', 'session_id', 'role_id', 'mobile', 'first_name', 'last_name')
            ->where('attendance_unique_id', $attendanceUniqueId)
            ->where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->first();

        if (!$staff) {
            $staff = User::select('id', 'branch_id', 'session_id', 'role_id', 'mobile', 'first_name', 'last_name')
                ->where('attendance_unique_id', $attendanceUniqueId)
                ->first();
        }

        if ($staff) {
            $entityType = ((int) $staff->role_id === 3) ? 'student' : 'staff';

            return [
                'entity_type' => $entityType,
                'entity_id' => (int) $staff->id,
                'attendance_unique_id' => $attendanceUniqueId,
                'branch_id' => (int) ($staff->branch_id ?? $branchId),
                'session_id' => (int) ($staff->session_id ?? $sessionId),
                'mobile' => (string) ($staff->mobile ?? ''),
                'firebase_token' => '',
                'display_name' => trim((string) (($staff->first_name ?? '') . ' ' . ($staff->last_name ?? ''))) ?: $attendanceUniqueId,
            ];
        }

        return null;
    }

    private function resolveScheduleTimes(AttendanceSetting $setting, Carbon $date): array
    {
        $month = (int) $date->format('n');
        $isSummer = $month >= 4 && $month <= 9;

        $startRaw = $isSummer ? $setting->summer_start_time : $setting->winter_start_time;
        $endRaw = $isSummer ? $setting->summer_end_time : $setting->winter_end_time;

        if (!$startRaw) {
            $startRaw = $setting->winter_start_time ?: $setting->summer_start_time ?: '09:00:00';
        }
        if (!$endRaw) {
            $endRaw = $setting->winter_end_time ?: $setting->summer_end_time ?: '15:00:00';
        }

        $start = Carbon::parse($date->format('Y-m-d') . ' ' . substr((string) $startRaw, 0, 8));
        $end = Carbon::parse($date->format('Y-m-d') . ' ' . substr((string) $endRaw, 0, 8));

        return [$start, $end];
    }

    private function resolveLunchTimes(AttendanceSetting $setting, Carbon $date): array
    {
        $month = (int) $date->format('n');
        $isSummer = $month >= 4 && $month <= 9;

        $fromRaw = $isSummer ? $setting->summer_lunch_from_time : $setting->winter_lunch_from_time;
        $toRaw = $isSummer ? $setting->summer_lunch_to_time : $setting->winter_lunch_to_time;

        if (!$fromRaw) {
            $fromRaw = $setting->winter_lunch_from_time ?: $setting->summer_lunch_from_time;
        }
        if (!$toRaw) {
            $toRaw = $setting->winter_lunch_to_time ?: $setting->summer_lunch_to_time;
        }

        $from = $fromRaw ? Carbon::parse($date->format('Y-m-d') . ' ' . substr((string) $fromRaw, 0, 8)) : null;
        $to = $toRaw ? Carbon::parse($date->format('Y-m-d') . ' ' . substr((string) $toRaw, 0, 8)) : null;

        return [$from, $to];
    }

    private function isAfterLunchCutoff(AttendanceSetting $setting, Carbon $date, Carbon $punch): bool
    {
        [$lunchFrom, $lunchTo] = $this->resolveLunchTimes($setting, $date);
        if ($lunchTo) {
            return $punch->gte($lunchTo);
        }
        if ($lunchFrom) {
            return $punch->gte($lunchFrom);
        }
        return false;
    }

    private function determineBiometricStatus(AttendanceSetting $setting, Carbon $date, Carbon $inTime, ?Carbon $outTime): string
    {
        [$startTime, $endTime] = $this->resolveScheduleTimes($setting, $date);

        $lateGraceMinutes = max(0, (int) ($setting->late_grace_minutes ?? 0));
        $earlyOutGraceMinutes = max(0, (int) ($setting->early_out_grace_minutes ?? 0));
        $halfDayMinMinutes = max(0, (int) ($setting->half_day_min_minutes ?? 0));

        if ($outTime && $halfDayMinMinutes > 0) {
            $workedMinutes = max(0, $inTime->diffInMinutes($outTime, false));
            if ($workedMinutes > 0 && $workedMinutes < $halfDayMinMinutes) {
                return 'halfday';
            }
        }

        if ($inTime->gt((clone $startTime)->addMinutes($lateGraceMinutes))) {
            return 'late';
        }

        if ($outTime && $outTime->lt((clone $endTime)->subMinutes($earlyOutGraceMinutes))) {
            return 'early_out';
        }

        return 'present';
    }

    private function determineCheckoutOnlyStatus(AttendanceSetting $setting, Carbon $date, Carbon $outTime): string
    {
        [, $endTime] = $this->resolveScheduleTimes($setting, $date);
        $earlyOutGraceMinutes = max(0, (int) ($setting->early_out_grace_minutes ?? 0));

        if ($outTime->lt((clone $endTime)->subMinutes($earlyOutGraceMinutes))) {
            return 'early_out';
        }

        return 'present';
    }


    private function normalizedServices($raw): array
    {
        $default = ['whatsapp', 'firebase', 'sms'];
        if (is_string($raw) && trim($raw) !== '') {
            $raw = explode(',', $raw);
        }
        if (!is_array($raw) || empty($raw)) {
            return $default;
        }

        $allowed = ['whatsapp', 'firebase', 'sms'];
        $services = [];
        foreach ($raw as $item) {
            $service = strtolower(trim((string) $item));
            if (in_array($service, $allowed, true)) {
                $services[] = $service;
            }
        }

        $services = array_values(array_unique($services));
        return !empty($services) ? $services : $default;
    }

    private function resolveFirebaseToken(array $entity): string
    {
        $direct = trim((string) ($entity['firebase_token'] ?? ''));
        if ($direct !== '') {
            return $direct;
        }

        if (!Schema::hasTable('notification_tokens')) {
            return '';
        }

        $attendanceUniqueId = trim((string) ($entity['attendance_unique_id'] ?? ''));
        if ($attendanceUniqueId === '') {
            return '';
        }

        $query = DB::table('notification_tokens')
            ->where('attendance_unique_id', $attendanceUniqueId)
            ->orderByDesc('id');

        return trim((string) ($query->value('device_token') ?? ''));
    }


    private function buildAttendanceMessage(AttendanceMark $mark, array $entity): string
    {
        $name = trim((string) ($entity['display_name'] ?? ''));
        if ($name === '') {
            $name = (string) $mark->unique_id;
        }

        $dateStr = Carbon::parse((string) $mark->date)->format('d/m/Y');
        $hasIn = !empty($mark->in_time);
        $hasOut = !empty($mark->out_time);
        $inStr = $hasIn ? Carbon::parse((string) $mark->in_time)->format('h:i A') : '';
        $outStr = $hasOut ? Carbon::parse((string) $mark->out_time)->format('h:i A') : '';
        $rawStatus = (string) ($mark->status ?? '');
        $status = ucwords(str_replace('_', ' ', $rawStatus));

        if (!$hasIn && !$hasOut) {
            return "Attendance update for {$name}: Date {$dateStr}, Status {$status}.";
        }

        if (!$hasIn && $hasOut) {
            return "Attendance update for {$name}: Check-out {$outStr} on {$dateStr}, Status {$status}.";
        }

        if ($hasIn && !$hasOut) {
            return "Attendance update for {$name}: Check-in {$inStr} on {$dateStr}, Status {$status}.";
        }

        return "Attendance update for {$name}: Check-in {$inStr} on {$dateStr}, Check-out {$outStr}, Status {$status}.";
    }

    private function queueAttendanceMessages(array $services, AttendanceMark $mark, array $entity): int
    {

        $mobile = trim((string) ($entity['mobile'] ?? ''));
        $firebaseToken = $this->resolveFirebaseToken($entity);
        $inserted = 0;

        foreach ($services as $service) {
            if (($service === 'whatsapp' || $service === 'sms') && $mobile === '') {
                continue;
            }

            $payload = [
                'entity_type' => $entity['entity_type'] ?? '',
                'service' => $service,
                'name' => $entity['display_name'] ?? (string) $mark->unique_id,
                'date' => (string) $mark->date,
                'status' => (string) ($mark->status ?? ''),
                'message_template' => $this->buildAttendanceMessage($mark, $entity),
            ];

            if (!empty($mark->in_time)) {
                $payload['check_in'] = (string) $mark->in_time;
            }

            if (!empty($mark->out_time)) {
                $payload['check_out'] = (string) $mark->out_time;
            }

            DB::table('attendance_messages')->insert([
                'attendance_mark_id' => (int) $mark->id,
                'branch_id' => (int) $mark->branch_id,
                'session_id' => (int) $mark->session_id,
                'attendance_unique_id' => (string) $mark->unique_id,
                'service' => $service,
                'mobile' => ($service === 'whatsapp' || $service === 'sms') ? $mobile : null,
                'firebase_token' => $service === 'firebase' ? $firebaseToken : null,
                'attendance_date' => $mark->date,
                'in_time' => $mark->in_time,
                'out_time' => $mark->out_time,
                'attendance_status' => $mark->status,
                'status' => 0,
                'payload' => json_encode($payload),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $inserted++;
        }

        return $inserted;
    }

    private function syncBiometricRows(AttendanceSetting $setting, int $branchId, int $sessionId, array $services): array
    {

        $rows = DB::table('biomatric_attendance')
            ->select('attendance_unique_id', 'date', 'time')
            ->whereNotNull('attendance_unique_id')
            ->whereNotNull('date')
            ->whereNotNull('time')
            ->orderBy('date')
            ->orderBy('attendance_unique_id')
            ->orderBy('time')
            ->get();

        $grouped = [];
        foreach ($rows as $row) {
            $uid = trim((string) $row->attendance_unique_id);
            $date = (string) $row->date;
            $time = substr((string) $row->time, 0, 8);

            if ($uid === '' || $date === '' || $time === '') {
                continue;
            }

            $key = $uid . '|' . $date;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'attendance_unique_id' => $uid,
                    'date' => $date,
                    'times' => [],
                ];
            }

            $grouped[$key]['times'][] = $time;
        }

        $processed = 0;
        $skipped = 0;
        $deleted = 0;
        $messageRows = 0;

        foreach ($grouped as $row) {
            $uid = $row['attendance_unique_id'];
            $date = $row['date'];
            $times = $row['times'];
            sort($times);

            if (empty($times)) {
                $skipped++;
                continue;
            }

            $entity = $this->resolveEntity($uid, $branchId, $sessionId);
            if (!$entity) {
                $skipped++;
                continue;
            }

            $entryBranchId = (int) $entity['branch_id'];
            $entrySessionId = (int) $entity['session_id'];

            if ($entryBranchId <= 0) {
                $entryBranchId = $branchId;
            }
            if ($entrySessionId <= 0) {
                $entrySessionId = $sessionId;
            }

            if ($entryBranchId <= 0 || $entrySessionId <= 0) {
                $skipped++;
                continue;
            }

            if ($this->isHolidayDate($entryBranchId, $entrySessionId, $date)) {
                $skipped++;
                continue;
            }

            $existingMark = AttendanceMark::where('unique_id', $uid)
                ->where('date', $date)
                ->where('branch_id', $entryBranchId)
                ->where('session_id', $entrySessionId)
                ->first();

            // Once check-in and check-out are both marked, keep data immutable.
            if ($existingMark && !empty($existingMark->in_time) && !empty($existingMark->out_time)) {
                $deleted += DB::table('biomatric_attendance')
                    ->where('attendance_unique_id', $uid)
                    ->whereDate('date', $date)
                    ->delete();
                $skipped++;
                continue;
            }

            $checkoutOnly = false;
            $inTimeRaw = $existingMark && !empty($existingMark->in_time)
                ? substr((string) $existingMark->in_time, 0, 8)
                : $times[0];
            $outTimeRaw = null;

            // Ignore duplicate punches within 1 minute. Checkout is first punch at least 60s after check-in.
            if (!$existingMark || empty($existingMark->in_time)) {
                $dateObjForLunch = Carbon::parse($date);
                $effectiveSettingForLunch = AttendanceSetting::where('branch_id', $entryBranchId)
                    ->where('session_id', $entrySessionId)
                    ->orderByDesc('id')
                    ->first() ?: $setting;
                $firstPunch = Carbon::parse($date . ' ' . $times[0]);
                if ($this->isAfterLunchCutoff($effectiveSettingForLunch, $dateObjForLunch, $firstPunch)) {
                    $checkoutOnly = true;
                    $inTimeRaw = null;
                    $outTimeRaw = $times[count($times) - 1];
                }
            }

            if (!$checkoutOnly) {
                $inTime = Carbon::parse($date . ' ' . $inTimeRaw);
                foreach ($times as $timeRaw) {
                    $punch = Carbon::parse($date . ' ' . $timeRaw);
                    if ($inTime->diffInSeconds($punch, false) >= 60) {
                        $outTimeRaw = $timeRaw;
                        break;
                    }
                }
            }

            // If in-time exists but checkout is still not valid, do not delete raw rows.
            // This allows next punches to complete checkout later.
            if (!$checkoutOnly && $existingMark && !empty($existingMark->in_time) && empty($outTimeRaw)) {
                $skipped++;
                continue;
            }

            $dateObj = Carbon::parse($date);
            $inTime = !$checkoutOnly && $inTimeRaw ? Carbon::parse($date . ' ' . $inTimeRaw) : null;
            $outTime = $outTimeRaw ? Carbon::parse($date . ' ' . $outTimeRaw) : null;

            $effectiveSetting = AttendanceSetting::where('branch_id', $entryBranchId)
                ->where('session_id', $entrySessionId)
                ->orderByDesc('id')
                ->first() ?: $setting;

            if ($checkoutOnly && $outTime) {
                $status = $this->determineCheckoutOnlyStatus($effectiveSetting, $dateObj, $outTime);
            } else {
                $status = $this->determineBiometricStatus($effectiveSetting, $dateObj, $inTime, $outTime);
            }

            $mark = AttendanceMark::updateOrCreate(
                [
                    'unique_id' => $uid,
                    'date' => $date,
                    'branch_id' => $entryBranchId,
                    'session_id' => $entrySessionId,
                ],
                [
                    'entity_type' => (string) $entity['entity_type'],
                    'in_time' => $inTimeRaw,
                    'out_time' => $outTimeRaw,
                    'status' => $status,
                    'created_by' => Session::get('id') ?: 0,
                ]
            );

            $messageRows += $this->queueAttendanceMessages($services, $mark, $entity);

            $deleted += DB::table('biomatric_attendance')
                ->where('attendance_unique_id', $uid)
                ->whereDate('date', $date)
                ->delete();

            $processed++;
        }

        return [
            'total_groups' => count($grouped),
            'processed' => $processed,
            'skipped' => $skipped,
            'deleted' => $deleted,
            'messages_created' => $messageRows,
        ];
    }

    private function autoMarkAbsentForToday(AttendanceSetting $setting, int $branchId, int $sessionId, array $services): array
    {
        $enabled = (int) ($setting->auto_absent_mark_enabled ?? 0) === 1;
        $timeRaw = trim((string) ($setting->auto_absent_mark_time ?? ''));
        $today = Carbon::today();

        if (!$enabled) {
            return [
                'enabled' => false,
                'triggered' => false,
                'date' => $today->toDateString(),
                'reason' => 'Auto absent mark disabled',
                'marked' => 0,
                'messages_created' => 0,
            ];
        }

        if ($timeRaw === '') {
            return [
                'enabled' => true,
                'triggered' => false,
                'date' => $today->toDateString(),
                'reason' => 'Auto absent mark time not set',
                'marked' => 0,
                'messages_created' => 0,
            ];
        }

        $triggerAt = Carbon::parse($today->toDateString() . ' ' . substr($timeRaw, 0, 8));
        if (Carbon::now()->lt($triggerAt)) {
            return [
                'enabled' => true,
                'triggered' => false,
                'date' => $today->toDateString(),
                'trigger_at' => $triggerAt->format('H:i:s'),
                'reason' => 'Current time is before auto absent mark time',
                'marked' => 0,
                'messages_created' => 0,
            ];
        }

        if ($branchId <= 0 || $sessionId <= 0) {
            return [
                'enabled' => true,
                'triggered' => false,
                'date' => $today->toDateString(),
                'reason' => 'Invalid branch/session context',
                'marked' => 0,
                'messages_created' => 0,
            ];
        }

        if ($this->isHolidayDate($branchId, $sessionId, $today->toDateString())) {
            return [
                'enabled' => true,
                'triggered' => false,
                'date' => $today->toDateString(),
                'trigger_at' => $triggerAt->format('H:i:s'),
                'reason' => 'Holiday date',
                'marked' => 0,
                'messages_created' => 0,
            ];
        }

        $studentIds = Admission::query()
            ->where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->where('status', 1)
            ->whereNotNull('attendance_unique_id')
            ->whereRaw("TRIM(attendance_unique_id) != ''")
            ->pluck('attendance_unique_id')
            ->map(fn ($v) => trim((string) $v))
            ->all();

        $staffIds = User::query()
            ->where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->where('status', 1)
            ->whereNotNull('attendance_unique_id')
            ->whereRaw("TRIM(attendance_unique_id) != ''")
            ->pluck('attendance_unique_id')
            ->map(fn ($v) => trim((string) $v))
            ->all();

        $allUniqueIds = array_values(array_unique(array_filter(array_merge($studentIds, $staffIds))));
        if (empty($allUniqueIds)) {
            return [
                'enabled' => true,
                'triggered' => true,
                'date' => $today->toDateString(),
                'trigger_at' => $triggerAt->format('H:i:s'),
                'total_candidates' => 0,
                'marked' => 0,
                'messages_created' => 0,
            ];
        }

        $alreadyMarked = AttendanceMark::query()
            ->where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->whereDate('date', $today->toDateString())
            ->whereIn('unique_id', $allUniqueIds)
            ->pluck('unique_id')
            ->map(fn ($v) => trim((string) $v))
            ->all();

        $alreadyMarkedMap = array_fill_keys($alreadyMarked, true);
        $marked = 0;
        $skippedExisting = 0;
        $skippedUnresolved = 0;
        $messageRows = 0;

        foreach ($allUniqueIds as $uid) {
            if (isset($alreadyMarkedMap[$uid])) {
                $skippedExisting++;
                continue;
            }

            $entity = $this->resolveEntity($uid, $branchId, $sessionId);
            if (!$entity) {
                $skippedUnresolved++;
                continue;
            }

            $mark = AttendanceMark::updateOrCreate(
                [
                    'unique_id' => $uid,
                    'date' => $today->toDateString(),
                    'branch_id' => $branchId,
                    'session_id' => $sessionId,
                ],
                [
                    'entity_type' => (string) ($entity['entity_type'] ?? 'staff'),
                    'in_time' => null,
                    'out_time' => null,
                    'status' => 'absent',
                    'created_by' => Session::get('id') ?: 0,
                ]
            );

            $messageRows += $this->queueAttendanceMessages($services, $mark, $entity);
            $marked++;
        }

        return [
            'enabled' => true,
            'triggered' => true,
            'date' => $today->toDateString(),
            'trigger_at' => $triggerAt->format('H:i:s'),
            'total_candidates' => count($allUniqueIds),
            'already_marked' => $skippedExisting,
            'unresolved' => $skippedUnresolved,
            'marked' => $marked,
            'messages_created' => $messageRows,
        ];
    }

    private function sendWhatsappAttendanceMessage(array $payload, $messageRow)
    {
        return null;
    }

    private function sendSmsAttendanceMessage(array $payload, $messageRow)
    {
        return null;
    }

    private function resolveNotificationBranding(int $branchId): array
    {
        $setting = Setting::query()
            ->when($branchId > 0, function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderByDesc('id')
            ->first();

        if (!$setting) {
            $setting = Setting::query()->orderByDesc('id')->first();
        }

        $schoolName = trim((string) ($setting->name ?? 'School ERP'));
        $leftLogo = trim((string) ($setting->left_logo ?? ''));
        $baseImagePath = rtrim((string) env('IMAGE_SHOW_PATH', ''), '/');

        $logoUrl = '';
        // Temporarily disable logo/icon in Firebase attendance notifications.
        // if ($baseImagePath !== '' && $leftLogo !== '') {
        //     $logoUrl = $baseImagePath . '/setting/left_logo/' . $leftLogo;
        // }

        return [
            'school_name' => $schoolName,
            'logo_url' => $logoUrl,
        ];
    }

    private function buildFirebaseNotificationContent(array $payload, $messageRow, array $branding): array
    {
        if (!empty($payload['calendar_auto_message'])) {
            $schoolName = trim((string) ($branding['school_name'] ?? 'School ERP'));
            $customTitle = trim((string) ($payload['calendar_event_title'] ?? 'Academic Calendar Update'));
            $customBody = trim((string) ($payload['message_template'] ?? ''));
            if ($customBody === '') {
                $customBody = 'Academic calendar update.';
            }

            return [
                'title' => $customTitle,
                'body' => $customBody . "\n" . $schoolName,
                'layout' => [
                    'layout_type' => 'academic_calendar_update',
                    'title' => $customTitle,
                    'subtitle' => $schoolName,
                    'show_logo' => !empty($branding['logo_url']),
                    'show_checkout' => false,
                    'show_checkin' => false,
                ],
                'is_checkout' => false,
            ];
        }

        $name = trim((string) ($payload['name'] ?? $messageRow->attendance_unique_id));
        $name = ucwords(strtolower($name));
        $status = ucwords(str_replace('_', ' ', (string) ($payload['status'] ?? $messageRow->attendance_status ?? 'Present')));
        $date = trim((string) ($payload['date'] ?? $messageRow->attendance_date ?? ''));
        $checkIn = trim((string) ($payload['check_in'] ?? $messageRow->in_time ?? ''));
        $checkOut = trim((string) ($payload['check_out'] ?? $messageRow->out_time ?? ''));

        $isCheckout = $checkOut !== '';
        $schoolName = trim((string) ($branding['school_name'] ?? 'School ERP'));
        $prettyDate = $date !== '' ? Carbon::parse($date)->format('d M Y') : date('d M Y');
        $checkIn12 = $checkIn !== '' ? Carbon::parse($checkIn)->format('h:i A') : '';
        $checkOut12 = $checkOut !== '' ? Carbon::parse($checkOut)->format('h:i A') : '';
        $isCheckoutOnly = $checkIn === '' && $checkOut !== '';
        $hasNoTimes = ($checkIn === '' && $checkOut === '');

        $title = $name . ' | Attendance Update';
        $body = '';
        if (!$hasNoTimes && !$isCheckoutOnly && $checkIn12 !== '') {
            $body .= "Check-in: {$checkIn12}\n";
        }
        if ($checkOut12 !== '') {
            $body .= "Check-out: {$checkOut12}\n";
        }
        $body .= "Date: {$prettyDate}\nStatus: {$status}\n{$schoolName}\nSchool Administrater";

        $layout = [
            'layout_type' => $hasNoTimes ? 'attendance_status_only' : ($isCheckoutOnly ? 'attendance_checkout_only' : ($isCheckout ? 'attendance_checkout' : 'attendance_checkin')),
            'title' => $title,
            'subtitle' => $schoolName,
            'show_logo' => !empty($branding['logo_url']),
            'show_checkout' => $checkOut12 !== '',
            'show_checkin' => !$isCheckoutOnly && $checkIn12 !== '',
        ];

        return [
            'title' => $title,
            'body' => $body,
            'layout' => $layout,
            'is_checkout' => $isCheckout,
        ];
    }

    private function sendFirebaseAttendanceMessage(array $payload, $messageRow)
    {
        $tokenQuery = FirebaseToken::query()
            ->where('attendance_unique_id', (string) $messageRow->attendance_unique_id)
            ->whereNull('deleted_at')
            ->whereNotNull('device_token')
            ->where('device_token', '!=', '')
            ->orderByDesc('id');

        $tokens = $tokenQuery->pluck('device_token')->unique()->values();

        if ($tokens->isEmpty()) {
            return [
                'ok' => false,
                'error' => 'No firebase token found',
                'meta' => null,
            ];
        }

        $branding = $this->resolveNotificationBranding((int) ($messageRow->branch_id ?? 0));
        $content = $this->buildFirebaseNotificationContent($payload, $messageRow, $branding);

        $title = (string) $content['title'];
        $body = (string) $content['body'];
        $image = (string) ($branding['logo_url'] ?? '');
        $data = [
            'attendance_unique_id' => (string) $messageRow->attendance_unique_id,
            'date' => (string) ($messageRow->attendance_date ?? ''),
            'status' => (string) ($messageRow->attendance_status ?? ''),
            'school_name' => (string) ($branding['school_name'] ?? ''),
            'logo_url' => $image,
            'layout' => json_encode($content['layout']),
            'is_checkout' => !empty($content['is_checkout']) ? '1' : '0',
            'check_in' => (string) ($payload['check_in'] ?? ''),
            'check_out' => (string) ($payload['check_out'] ?? ''),
        ];

        $fcmService = new FcmDirectService();
        $lastError = null;

        foreach ($tokens as $token) {
            $result = $fcmService->send((string) $token, $data, 'high', $title, $body, $image !== '' ? $image : null);
            if (!empty($result['success'])) {
                return [
                    'ok' => true,
                    'error' => null,
                    'meta' => [
                        'firebase_message_id' => $result['message_id'] ?? null,
                        'token' => (string) $token,
                    ],
                ];
            }
            $lastError = $result['error'] ?? 'Unknown firebase error';
        }

        return [
            'ok' => false,
            'error' => $lastError,
            'meta' => null,
        ];
    }

    private function dispatchAttendanceMessageByService($messageRow, array $payload)
    {
        $service = strtolower(trim((string) ($messageRow->service ?? '')));

        if ($service === 'firebase') {
            return $this->sendFirebaseAttendanceMessage($payload, $messageRow);
        }

        if ($service === 'whatsapp') {
            return [
                'ok' => true,
                'error' => null,
                'meta' => $this->sendWhatsappAttendanceMessage($payload, $messageRow),
            ];
        }

        if ($service === 'sms') {
            return [
                'ok' => true,
                'error' => null,
                'meta' => $this->sendSmsAttendanceMessage($payload, $messageRow),
            ];
        }

        return [
            'ok' => false,
            'error' => 'Unsupported service',
            'meta' => null,
        ];
    }

    public function sendAttendanceMessages(Request $request)
    {
        try {
            $todayDate = Carbon::today()->toDateString();
            $limit = (int) $request->input('limit', 50);
            if ($limit < 1) {
                $limit = 50;
            }
            if ($limit > 500) {
                $limit = 500;
            }

            $query = DB::table('attendance_messages')
                ->where('status', 0)
                ->whereDate('attendance_date', $todayDate)
                ->orderBy('id')
                ->limit($limit);

            if ($request->filled('service')) {
                $query->where('service', strtolower(trim((string) $request->input('service'))));
            }

            if ($request->filled('attendance_unique_id')) {
                $query->where('attendance_unique_id', trim((string) $request->input('attendance_unique_id')));
            }

            if ((int) $request->input('calendar_only', 0) === 1) {
                $query->where('payload', 'like', '%"calendar_auto_message":true%');
            }

            $messages = $query->get();

            if ($messages->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'No pending attendance messages.',
                    'dispatch_date' => $todayDate,
                    'processed' => 0,
                    'sent' => 0,
                    'failed' => 0,
                ]);
            }

            $processed = 0;
            $sent = 0;
            $failed = 0;

            foreach ($messages as $msg) {
                $processed++;

                $payload = [];
                if (!empty($msg->payload)) {
                    $decoded = json_decode((string) $msg->payload, true);
                    if (is_array($decoded)) {
                        $payload = $decoded;
                    }
                }

                $dispatch = $this->dispatchAttendanceMessageByService($msg, $payload);

                if (!empty($dispatch['ok'])) {
                    $payload['send_status'] = 'sent';
                    $payload['service_response'] = $dispatch['meta'] ?? null;

                    DB::table('attendance_messages')->where('id', $msg->id)->update([
                        'status' => 1,
                        'payload' => json_encode($payload),
                        'updated_at' => now(),
                    ]);
                    $sent++;
                } else {
                    $payload['send_status'] = 'failed';
                    $payload['send_error'] = $dispatch['error'] ?? 'Unknown error';

                    DB::table('attendance_messages')->where('id', $msg->id)->update([
                        'status' => 2,
                        'payload' => json_encode($payload),
                        'updated_at' => now(),
                    ]);
                    $failed++;
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Attendance message dispatch completed.',
                'dispatch_date' => $todayDate,
                'processed' => $processed,
                'sent' => $sent,
                'failed' => $failed,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function dispatchAttendanceMessagesByType(Request $request)
    {
        $type = strtolower(trim((string) $request->input('type', 'all')));
        $allowed = ['all', 'firebase', 'whatsapp', 'sms'];

        if (!in_array($type, $allowed, true)) {
            return response()->json([
                'status' => false,
                'error' => 'Invalid type. Allowed: all, firebase, whatsapp, sms'
            ], 422);
        }

        if ($type !== 'all') {
            $request->merge(['service' => $type]);
        }

        return $this->sendAttendanceMessages($request);
    }

    public function firebaseNotiication(Request $request)
    {
        $request->merge(['type' => 'firebase']);
        return $this->dispatchAttendanceMessagesByType($request);
    }

    public function detectAttendanceType(Request $request)
    {
        $branchId = (int) ($request->input('branch_id') ?: Session::get('branch_id') ?: 0);
        $sessionId = (int) ($request->input('session_id') ?: Session::get('session_id') ?: 0);
        $services = [];

        $query = AttendanceSetting::query();

        if ($branchId > 0) {
            $query->where('branch_id', $branchId);
        }

        if ($sessionId > 0) {
            $query->where('session_id', $sessionId);
        }

        $setting = $query->orderByDesc('id')->first();

        // Cron may hit this route without login session; use the selected setting context.
        if ($setting) {
            if ($branchId <= 0) {
                $branchId = (int) ($setting->branch_id ?? 0);
            }
            if ($sessionId <= 0) {
                $sessionId = (int) ($setting->session_id ?? 0);
            }
        }

        $type = (int) ($setting->attendance_type ?? 2);

        $labels = [
            1 => 'biometric',
            2 => 'normal',
            3 => 'qr',
        ];

        $response = [
            'ok' => true,
            'branch_id' => $branchId,
            'session_id' => $sessionId,
            'attendance_type' => $type,
            'attendance_type_label' => $labels[$type] ?? 'normal',
        ];

        if ($setting) {
            $servicesSource = $request->input('services');
            if ($servicesSource === null || $servicesSource === '') {
                $servicesSource = $setting->messaging_services ?? ['whatsapp', 'firebase', 'sms'];
            }
            $services = $this->normalizedServices($servicesSource);
            $response['requested_services'] = $services;
        }

        // Must run before biometric sync / auto absent.
        $calendarCheck = $this->checkCalendarOrSundayAttendanceForToday($branchId, $sessionId);
        $response['calendar_or_sunday'] = $calendarCheck;
        if (!empty($calendarCheck['triggered'])) {
            $response['calendar_marking'] = $this->markAllAttendanceForStatusWithoutMessaging(
                $branchId,
                $sessionId,
                (string) ($calendarCheck['date'] ?? Carbon::today()->toDateString()),
                'holiday'
            );

            $calendarDate = (string) ($calendarCheck['date'] ?? Carbon::today()->toDateString());
            $response['calendar_auto_message'] = $this->queueAcademicCalendarAutoMessages(
                $branchId,
                $sessionId,
                $calendarDate,
                $calendarCheck,
                $services
            );

            if (!empty($response['calendar_auto_message']['services']) && !empty($response['calendar_auto_message']['attendance_unique_ids'])) {
                $dispatchRuns = [];
                $summary = ['processed' => 0, 'sent' => 0, 'failed' => 0];

                foreach ($response['calendar_auto_message']['attendance_unique_ids'] as $attendanceUniqueId) {
                    foreach ($response['calendar_auto_message']['services'] as $service) {
                        $dispatchRequest = new Request([
                            'type' => $service,
                            'attendance_unique_id' => $attendanceUniqueId,
                            'calendar_only' => 1,
                            'limit' => 200,
                        ]);
                        $dispatchResponse = $this->dispatchAttendanceMessagesByType($dispatchRequest);
                        $result = method_exists($dispatchResponse, 'getData')
                            ? $dispatchResponse->getData(true)
                            : [];

                        $dispatchRuns[] = [
                            'attendance_unique_id' => $attendanceUniqueId,
                            'service' => $service,
                            'result' => $result,
                        ];

                        $summary['processed'] += (int) ($result['processed'] ?? 0);
                        $summary['sent'] += (int) ($result['sent'] ?? 0);
                        $summary['failed'] += (int) ($result['failed'] ?? 0);
                    }
                }

                $response['calendar_auto_message']['dispatch'] = [
                    'summary' => $summary,
                    'runs' => $dispatchRuns,
                ];
            }

            $response['pipeline_stopped'] = true;
            $response['pipeline_stop_reason'] = 'Academic calendar event/Sunday holiday applied before auto absent.';

            return response()->json($response);
        }

        if ($type === 1 && $setting) {
            $response['sync'] = $this->syncBiometricRows($setting, $branchId, $sessionId, $services);
        }

        // Auto absent marking is common for all attendance types (after configured time, not exact-only).
        if ($setting) {
            $response['auto_absent'] = $this->autoMarkAbsentForToday($setting, $branchId, $sessionId, $services);

            if (!empty($services)) {
                // Auto-trigger dispatch after sync/auto-absent using configured messaging services only.
                $dispatchLimit = (int) $request->input('dispatch_limit', 200);
                if ($dispatchLimit < 1) {
                    $dispatchLimit = 200;
                }
                if ($dispatchLimit > 500) {
                    $dispatchLimit = 500;
                }

                $dispatchRuns = [];
                $summary = [
                    'processed' => 0,
                    'sent' => 0,
                    'failed' => 0,
                ];

                foreach ($services as $service) {
                    $dispatchRequest = new Request([
                        'type' => $service,
                        'limit' => $dispatchLimit,
                    ]);
                    $dispatchResponse = $this->dispatchAttendanceMessagesByType($dispatchRequest);
                    $result = method_exists($dispatchResponse, 'getData')
                        ? $dispatchResponse->getData(true)
                        : [];

                    $dispatchRuns[] = [
                        'service' => $service,
                        'result' => $result,
                    ];

                    $summary['processed'] += (int) ($result['processed'] ?? 0);
                    $summary['sent'] += (int) ($result['sent'] ?? 0);
                    $summary['failed'] += (int) ($result['failed'] ?? 0);
                }

                $response['dispatch'] = [
                    'services' => $services,
                    'summary' => $summary,
                    'runs' => $dispatchRuns,
                ];
            } else {
                $response['dispatch'] = [
                    'services' => [],
                    'summary' => [
                        'processed' => 0,
                        'sent' => 0,
                        'failed' => 0,
                    ],
                    'runs' => [],
                    'skipped' => true,
                    'reason' => 'No messaging services configured',
                ];
            }
        }

        return response()->json($response);
    }
}
