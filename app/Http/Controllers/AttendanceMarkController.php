<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSetting;
use App\Models\Admission;
use App\Models\User;
use App\Models\Role;
use App\Models\AttendanceStatus;
use App\Models\AttendanceMark;
use App\Models\Master\Weekendcalendar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Session;
use Helper;

class AttendanceMarkController extends Controller
{
    private function normalizedMessagingServices($raw): array
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

    private function resolveAttendanceMessageEntity(string $uniqueId, string $entityType, int $branchId, int $sessionId): array
    {
        $entityType = strtolower(trim($entityType));

        if ($entityType === 'student') {
            $student = Admission::select('id', 'first_name', 'last_name', 'mobile')
                ->where('branch_id', $branchId)
                ->where('session_id', $sessionId)
                ->where(function ($q) use ($uniqueId) {
                    $q->where('attendance_unique_id', $uniqueId)
                        ->orWhere('admissionNo', $uniqueId);
                })
                ->first();

            return [
                'entity_type' => 'student',
                'mobile' => (string) ($student->mobile ?? ''),
                'display_name' => $student
                    ? trim((string) (($student->first_name ?? '') . ' ' . ($student->last_name ?? '')))
                    : $uniqueId,
            ];
        }

        $staff = User::select('id', 'first_name', 'last_name', 'mobile')
            ->where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->where(function ($q) use ($uniqueId) {
                $q->where('attendance_unique_id', $uniqueId)
                    ->orWhere('id', (stripos($uniqueId, 'USR-') === 0) ? (int) str_replace('USR-', '', $uniqueId) : 0);
            })
            ->first();

        return [
            'entity_type' => 'staff',
            'mobile' => (string) ($staff->mobile ?? ''),
            'display_name' => $staff
                ? trim((string) (($staff->first_name ?? '') . ' ' . ($staff->last_name ?? '')))
                : $uniqueId,
        ];
    }

    private function resolveFirebaseTokenForAttendance(string $attendanceUniqueId): string
    {
        if (!Schema::hasTable('notification_tokens')) {
            return '';
        }

        return trim((string) (DB::table('notification_tokens')
            ->where('attendance_unique_id', $attendanceUniqueId)
            ->orderByDesc('id')
            ->value('device_token') ?? ''));
    }

    private function buildBulkAttendanceMessage(AttendanceMark $mark, array $entity): string
    {
        $name = trim((string) ($entity['display_name'] ?? '')) ?: (string) $mark->unique_id;
        $dateStr = Carbon::parse((string) $mark->date)->format('d/m/Y');
        $hasIn = !empty($mark->in_time);
        $hasOut = !empty($mark->out_time);
        $inStr = $hasIn ? Carbon::parse((string) $mark->in_time)->format('h:i A') : '';
        $rawStatus = (string) ($mark->status ?? '');
        $status = ucwords(str_replace('_', ' ', $rawStatus));

        if (!$hasIn && !$hasOut) {
            return "Attendance update for {$name}: Date {$dateStr}, Status {$status}.";
        }

        if ($hasIn && !$hasOut) {
            return "Attendance update for {$name}: Check-in {$inStr} on {$dateStr}, Status {$status}.";
        }

        $outStr = Carbon::parse((string) $mark->out_time)->format('h:i A');
        if (!$hasIn && $hasOut) {
            return "Attendance update for {$name}: Check-out {$outStr} on {$dateStr}, Status {$status}.";
        }
        return "Attendance update for {$name}: Check-in {$inStr} on {$dateStr}, Check-out {$outStr}, Status {$status}.";
    }

    private function queueBulkAttendanceMessages(array $services, AttendanceMark $mark, array $entity): int
    {
        $mobile = trim((string) ($entity['mobile'] ?? ''));
        $firebaseToken = $this->resolveFirebaseTokenForAttendance((string) $mark->unique_id);
        $inserted = 0;

        // Replace pending rows for same mark+service so re-save does not create duplicate pending messages.
        DB::table('attendance_messages')
            ->where('attendance_mark_id', (int) $mark->id)
            ->whereIn('service', $services)
            ->where('status', 0)
            ->delete();

        foreach ($services as $service) {
            if (($service === 'whatsapp' || $service === 'sms') && $mobile === '') {
                continue;
            }

            $payload = [
                'entity_type' => $entity['entity_type'] ?? (string) ($mark->entity_type ?? ''),
                'service' => $service,
                'name' => $entity['display_name'] ?? (string) $mark->unique_id,
                'date' => (string) $mark->date,
                'check_in' => (string) ($mark->in_time ?? ''),
                'status' => (string) ($mark->status ?? ''),
                'message_template' => $this->buildBulkAttendanceMessage($mark, $entity),
            ];

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

    private function dispatchBulkAttendanceMessages(array $services, array $attendanceUniqueIds): void
    {
        if (empty($services) || empty($attendanceUniqueIds)) {
            return;
        }

        $dispatcher = new MultipleCronController();
        $attendanceUniqueIds = array_values(array_unique(array_filter(array_map('strval', $attendanceUniqueIds))));

        foreach ($attendanceUniqueIds as $attendanceUniqueId) {
            foreach ($services as $service) {
                $req = new Request([
                    'type' => $service,
                    'attendance_unique_id' => $attendanceUniqueId,
                    'limit' => 200,
                ]);
                $dispatcher->dispatchAttendanceMessagesByType($req);
            }
        }
    }

    private function resolveStudentUniqueId($student)
    {
        $attendanceId = trim((string) ($student->attendance_unique_id ?? ''));
        if ($attendanceId !== '') {
            return $attendanceId;
        }

        $admissionNo = trim((string) ($student->admissionNo ?? ''));
        if ($admissionNo !== '') {
            return $admissionNo;
        }

        return 'STU-' . $student->id;
    }

    private function resolveStaffUniqueId($member)
    {
        $attendanceId = trim((string) ($member->attendance_unique_id ?? ''));

        return $attendanceId !== '' ? $attendanceId : ('USR-' . $member->id);
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

    private function normalizeTimeValue($value): ?string
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        $formats = ['H:i:s', 'H:i', 'h:i A', 'h:iA', 'g:i A', 'g:iA'];
        foreach ($formats as $format) {
            try {
                $dt = Carbon::createFromFormat($format, strtoupper($raw));
                if ($dt !== false) {
                    return $dt->format('H:i:s');
                }
            } catch (\Throwable $e) {
                // Try next format
            }
        }

        try {
            return Carbon::parse($raw)->format('H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function index(Request $request)
    {
        $branchId = Session::get('branch_id');
        $sessionId = Session::get('session_id');
        $roleId = (int) Session::get('role_id');

        $setting = AttendanceSetting::where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->orderBy('id', 'desc')
            ->first();

        $selectedDate = $request->date ?? date('Y-m-d');
        $activeTab = $request->tab ?? 'students';
        $allowBackDate = (int) ($setting->allow_back_date_attendance ?? 0) === 1;
        $allowBackDateForUser = $allowBackDate || $roleId === 1;

        if (!$request->isMethod('post') && !$allowBackDateForUser && $selectedDate < date('Y-m-d')) {
            $selectedDate = date('Y-m-d');
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'date' => 'required|date',
            ]);

            $selectedDate = $request->input('date', $selectedDate);

            if (!$allowBackDateForUser && $selectedDate < date('Y-m-d')) {
                return redirect()->back()->with('error', 'Back date attendance is not allowed.');
            }

            if ($this->isHolidayDate((int) $branchId, (int) $sessionId, (string) $selectedDate)) {
                return redirect()->back()->with('error', 'Attendance marking is not allowed on holiday dates.');
            }

            $rows = $request->input('rows', []);
            $resetKeys = [];
            $savedMarks = [];
            foreach ($rows as $row) {
                $uniqueId = $row['unique_id'] ?? null;
                $entityType = $row['entity_type'] ?? null;
                $status = $row['status'] ?? null;
                $inTime = $this->normalizeTimeValue($row['in_time'] ?? null);
                $outTime = $this->normalizeTimeValue($row['out_time'] ?? null);

                if (!$uniqueId || !$entityType) {
                    continue;
                }

                if (!$status && !$inTime && !$outTime) {
                    $resetKeys[] = $uniqueId;
                    continue;
                }

                $mark = AttendanceMark::where('unique_id', $uniqueId)
                    ->where('date', $selectedDate)
                    ->where('branch_id', $branchId)
                    ->where('session_id', $sessionId)
                    ->first();

                $newValues = [
                    'entity_type' => (string) $entityType,
                    'status' => $status ?: null,
                    'in_time' => $inTime ?: null,
                    'out_time' => $outTime ?: null,
                ];

                $isChanged = true;
                if ($mark) {
                    $isChanged =
                        (string) ($mark->entity_type ?? '') !== (string) ($newValues['entity_type'] ?? '') ||
                        (string) ($mark->status ?? '') !== (string) ($newValues['status'] ?? '') ||
                        (string) ($mark->in_time ?? '') !== (string) ($newValues['in_time'] ?? '') ||
                        (string) ($mark->out_time ?? '') !== (string) ($newValues['out_time'] ?? '');
                }

                if (!$mark) {
                    $mark = new AttendanceMark();
                    $mark->unique_id = $uniqueId;
                    $mark->date = $selectedDate;
                    $mark->branch_id = $branchId;
                    $mark->session_id = $sessionId;
                }

                if ($isChanged) {
                    $mark->entity_type = $newValues['entity_type'];
                    $mark->status = $newValues['status'];
                    $mark->in_time = $newValues['in_time'];
                    $mark->out_time = $newValues['out_time'];
                    $mark->created_by = Session::get('id');
                    $mark->save();
                }

                if ($isChanged) {
                    $savedMarks[] = [
                        'mark' => $mark,
                        'entity_type' => (string) $entityType,
                    ];
                }
            }

            if (!empty($resetKeys)) {
                AttendanceMark::where('date', $selectedDate)
                    ->where('branch_id', $branchId)
                    ->where('session_id', $sessionId)
                    ->whereIn('unique_id', $resetKeys)
                    ->delete();
            }

            $manualMessagingEnabled = (int) (optional($setting)->manual_attendance_messaging_enabled ?? 0) === 1;
            if ($manualMessagingEnabled && !empty($savedMarks) && Schema::hasTable('attendance_messages')) {
                $services = $this->normalizedMessagingServices(optional($setting)->messaging_services ?? ['whatsapp', 'firebase', 'sms']);
                $queuedUniqueIds = [];

                foreach ($savedMarks as $saved) {
                    /** @var AttendanceMark $mark */
                    $mark = $saved['mark'];
                    $entity = $this->resolveAttendanceMessageEntity(
                        (string) $mark->unique_id,
                        (string) ($saved['entity_type'] ?? $mark->entity_type ?? 'staff'),
                        (int) $branchId,
                        (int) $sessionId
                    );

                    $this->queueBulkAttendanceMessages($services, $mark, $entity);
                    $queuedUniqueIds[] = (string) $mark->unique_id;
                }

                $this->dispatchBulkAttendanceMessages($services, $queuedUniqueIds);
            }

            return redirect()->back()->with('message', 'Attendance saved successfully.');
        }

        $classes = Helper::classType();
        $students = Admission::select('id', 'admissionNo', 'attendance_unique_id', 'first_name', 'last_name', 'class_type_id')
            ->where('session_id', $sessionId)
            ->where('branch_id', $branchId)
            ->where('status', 1)
            ->orderBy('first_name')
            ->get()
            ->map(function ($student) {
                $student->attendance_unique_id = $this->resolveStudentUniqueId($student);
                return $student;
            });

        $staff = User::select('id', 'attendance_unique_id', 'first_name', 'last_name', 'role_id')
            ->where('session_id', $sessionId)
            ->where('branch_id', $branchId)
            ->where('status', 1)
            ->where('role_id', '!=', 3)
            ->orderBy('first_name')
            ->get()
            ->map(function ($member) {
                $member->attendance_unique_id = $this->resolveStaffUniqueId($member);
                return $member;
            });

        $staffRoleIds = $staff->pluck('role_id')->unique()->values();
        $staffRoles = Role::whereIn('id', $staffRoleIds)->orderBy('name')->get();

        $attendanceStatuses = AttendanceStatus::orderBy('id')->get();

        $attendanceMarks = AttendanceMark::where('date', $selectedDate)
            ->where('session_id', $sessionId)
            ->where('branch_id', $branchId)
            ->get()
            ->keyBy('unique_id');

        $attendanceType = $setting->attendance_type ?? 2;
        $isHolidayDate = $this->isHolidayDate((int) $branchId, (int) $sessionId, (string) $selectedDate);

        if ($attendanceType == 1) {
            return view('attendance.biometric', compact('setting', 'classes', 'students', 'staff', 'staffRoles', 'attendanceStatuses', 'attendanceMarks', 'selectedDate', 'activeTab', 'allowBackDateForUser', 'isHolidayDate'));
        }

        if ($attendanceType == 3) {
            return view('attendance.qr', compact('setting', 'selectedDate', 'activeTab', 'allowBackDateForUser', 'isHolidayDate'));
        }

        return view('attendance.normal', compact('setting', 'classes', 'students', 'staff', 'staffRoles', 'attendanceStatuses', 'attendanceMarks', 'selectedDate', 'activeTab', 'allowBackDateForUser', 'isHolidayDate'));
    }
}
