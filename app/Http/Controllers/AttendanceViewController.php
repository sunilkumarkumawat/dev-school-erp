<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\AttendanceMark;
use App\Models\AttendanceSetting;
use App\Models\User;
use App\Models\Master\Weekendcalendar;
use Session;

class AttendanceViewController extends Controller
{
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

    private function resolveStaffAliases($member)
    {
        $primary = $this->resolveStaffUniqueId($member);
        $legacy = 'USR-' . $member->id;

        return array_values(array_unique([$primary, $legacy]));
    }


    private function normalizeCalendarStatus(?string $rawName): string
    {
        $name = strtolower(trim((string) $rawName));
        if ($name === 'holiday') {
            return 'holiday';
        }
        if ($name === 'event') {
            return 'event';
        }
        return '';
    }

    private function calendarStatusMap(int $branchId, int $sessionId, string $fromDate, string $toDate): array
    {
        $rows = Weekendcalendar::select('weekendcalendar.date', 'attendance_status.name as status_name')
            ->leftJoin('attendance_status', 'attendance_status.id', '=', 'weekendcalendar.attendance_status')
            ->where('weekendcalendar.branch_id', $branchId)
            ->where('weekendcalendar.session_id', $sessionId)
            ->whereBetween('weekendcalendar.date', [$fromDate, $toDate])
            ->orderBy('weekendcalendar.date')
            ->orderBy('weekendcalendar.id')
            ->get();

        $map = [];
        foreach ($rows as $row) {
            $dateKey = (string) $row->date;
            $status = $this->normalizeCalendarStatus($row->status_name ?? '');
            if ($status === '') {
                continue;
            }

            if (!isset($map[$dateKey])) {
                $map[$dateKey] = $status;
                continue;
            }

            if ($map[$dateKey] !== 'holiday' && $status === 'holiday') {
                $map[$dateKey] = 'holiday';
            }
        }

        return $map;
    }


    private function statusLabel(?string $status): string
    {
        if (!$status) {
            return '';
        }

        return ucwords(str_replace('_', ' ', $status));
    }

    private function formatTime12(?string $time): string
    {
        if (!$time) {
            return '';
        }

        $ts = strtotime($time);
        return $ts ? date('h:i A', $ts) : '';
    }

    private function exportMonthlyCsv(string $activeTab, string $selectedUniqueId, string $selectedName, int $month, int $year, $marks)
    {
        $entityLabel = $activeTab === 'staff' ? 'Staff' : 'Student';
        $fileName = 'attendance-history-' . strtolower($entityLabel) . '-' . $selectedUniqueId . '-' . sprintf('%04d-%02d', $year, $month) . '.csv';

        $summary = [
            'Present' => $marks->where('status', 'present')->count(),
            'Absent' => $marks->where('status', 'absent')->count(),
            'Leave' => $marks->where('status', 'leave')->count(),
            'Late' => $marks->where('status', 'late')->count(),
            'Early Out' => $marks->where('status', 'early_out')->count(),
            'Halfday' => $marks->where('status', 'halfday')->count(),
            'Holiday' => $marks->where('status', 'holiday')->count(),
            'Total Marked Days' => $marks->count(),
        ];

        return response()->streamDownload(function () use ($entityLabel, $selectedUniqueId, $selectedName, $month, $year, $marks, $summary) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['Attendance History Export']);
            fputcsv($out, ['Entity Type', $entityLabel]);
            fputcsv($out, ['Unique ID', $selectedUniqueId]);
            fputcsv($out, ['Name', $selectedName]);
            fputcsv($out, ['Month', date('F', mktime(0, 0, 0, $month, 1)) . ' ' . $year]);
            fputcsv($out, []);
            fputcsv($out, ['Date', 'Day', 'Status', 'Check In', 'Check Out']);

            foreach ($marks as $mark) {
                fputcsv($out, [
                    $mark->date,
                    date('l', strtotime($mark->date)),
                    $this->statusLabel($mark->status),
                    $this->formatTime12($mark->in_time),
                    $this->formatTime12($mark->out_time),
                ]);
            }

            fputcsv($out, []);
            fputcsv($out, ['Summary', 'Count']);
            foreach ($summary as $label => $count) {
                fputcsv($out, [$label, $count]);
            }

            fclose($out);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function index(Request $request)
    {
        $branchId = Session::get('branch_id');
        $sessionId = Session::get('session_id');

        $students = Admission::select('id', 'attendance_unique_id', 'admissionNo', 'first_name', 'last_name')
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
                $member->attendance_aliases = $this->resolveStaffAliases($member);
                return $member;
            });

        $activeTab = $request->tab ?? 'students';

        $month = (int) ($request->month ?? date('n'));
        $year = (int) ($request->year ?? date('Y'));
        $startDate = date('Y-m-01', strtotime($year . '-' . $month . '-01'));
        $endDate = date('Y-m-t', strtotime($startDate));
        $yearStart = $year . '-01-01';
        $yearEnd = $year . '-12-31';

        $calendarMonthMap = $this->calendarStatusMap((int) $branchId, (int) $sessionId, $startDate, $endDate);
        $calendarYearMap = $this->calendarStatusMap((int) $branchId, (int) $sessionId, $yearStart, $yearEnd);

        if ($activeTab === 'staff') {
            $requestedStaffId = (string) ($request->staff ?? '');

            if ($requestedStaffId === '') {
                $preferredUid = AttendanceMark::where('session_id', $sessionId)
                    ->where('branch_id', $branchId)
                    ->where('entity_type', 'staff')
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderByDesc('date')
                    ->value('unique_id');

                if (!$preferredUid) {
                    $preferredUid = AttendanceMark::where('branch_id', $branchId)
                        ->where('entity_type', 'staff')
                        ->whereBetween('date', [$startDate, $endDate])
                        ->orderByDesc('date')
                        ->value('unique_id');
                }

                $selectedUniqueId = $preferredUid ?: optional($staff->first())->attendance_unique_id;
            } else {
                $selectedUniqueId = $requestedStaffId;
            }
        } else {
            $selectedUniqueId = $request->student ?? optional($students->first())->attendance_unique_id;
        }

        $lookupIds = [(string) $selectedUniqueId];
        if ($activeTab === 'staff') {
            $selectedStaff = $staff->first(function ($member) use ($selectedUniqueId) {
                return in_array($selectedUniqueId, $member->attendance_aliases, true);
            });

            if ($selectedStaff) {
                $lookupIds = $selectedStaff->attendance_aliases;
                $selectedUniqueId = $selectedStaff->attendance_unique_id;
            }
        }

        $lookupIds = array_values(array_filter(array_unique($lookupIds)));

        $baseMonthQuery = AttendanceMark::whereIn('unique_id', $lookupIds)
            ->where('branch_id', $branchId)
            ->whereBetween('date', [$startDate, $endDate]);

        if ($activeTab === 'staff') {
            $baseMonthQuery->where('entity_type', 'staff');
        } else {
            $baseMonthQuery->where('entity_type', 'student');
        }

        $marks = (clone $baseMonthQuery)
            ->where('session_id', $sessionId)
            ->orderBy('date')
            ->get();

        if ($activeTab === 'staff' && $marks->isEmpty()) {
            $marks = (clone $baseMonthQuery)
                ->orderBy('date')
                ->get();
        }

        $marks = $marks->groupBy('date')
            ->map(function ($group) {
                return $group->sortByDesc('updated_at')->first();
            })
            ->values();

        $marksByDate = $marks->keyBy('date');

        $totalDays = $marks->count();
        $presentDays = $marks->where('status', 'present')->count();
        $absentDays = $marks->where('status', 'absent')->count();
        $lateDays = $marks->where('status', 'late')->count();
        $earlyOutDays = $marks->where('status', 'early_out')->count();
        $halfDayDays = $marks->where('status', 'halfday')->count();
        $holidayDays = $marks->where('status', 'holiday')->count();
        $leaveDays = $marks->where('status', 'leave')->count();
        $attendancePercent = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

        $monthName = date('F', strtotime($startDate));

        $baseYearQuery = AttendanceMark::whereIn('unique_id', $lookupIds)
            ->where('branch_id', $branchId)
            ->whereBetween('date', [$yearStart, $yearEnd]);

        if ($activeTab === 'staff') {
            $baseYearQuery->where('entity_type', 'staff');
        } else {
            $baseYearQuery->where('entity_type', 'student');
        }

        $yearlyMarks = (clone $baseYearQuery)
            ->where('session_id', $sessionId)
            ->orderBy('date')
            ->get();

        if ($activeTab === 'staff' && $yearlyMarks->isEmpty()) {
            $yearlyMarks = (clone $baseYearQuery)
                ->orderBy('date')
                ->get();
        }

        $yearlyMarks = $yearlyMarks->groupBy('date')
            ->map(function ($group) {
                return $group->sortByDesc('updated_at')->first();
            })
            ->values();

        if ($activeTab === 'staff' && !empty($selectedUniqueId)) {
            $staffExists = $staff->contains(function ($member) use ($selectedUniqueId) {
                return $member->attendance_unique_id === $selectedUniqueId;
            });

            if (!$staffExists) {
                $legacyUser = User::select('first_name', 'last_name')
                    ->where('attendance_unique_id', $selectedUniqueId)
                    ->first();

                $legacy = new \stdClass();
                $legacy->id = 0;
                $legacy->attendance_unique_id = $selectedUniqueId;
                $legacy->attendance_aliases = [$selectedUniqueId];
                $legacy->role_id = null;
                $legacy->first_name = $legacyUser ? trim((string) $legacyUser->first_name) : 'Legacy Staff';
                $legacy->last_name = $legacyUser ? trim((string) $legacyUser->last_name) : ('(' . $selectedUniqueId . ')');

                $staff->prepend($legacy);
            }
        }

        $selectedName = '-';
        if ($activeTab === 'staff') {
            $selectedMember = $staff->first(function ($member) use ($selectedUniqueId) {
                $aliases = $member->attendance_aliases ?? [$member->attendance_unique_id];
                return in_array($selectedUniqueId, $aliases, true) || (string) $member->attendance_unique_id === (string) $selectedUniqueId;
            });
            if ($selectedMember) {
                $selectedName = trim((string) ($selectedMember->first_name ?? '') . ' ' . (string) ($selectedMember->last_name ?? ''));
            }
        } else {
            $selectedStudent = $students->firstWhere('attendance_unique_id', $selectedUniqueId);
            if ($selectedStudent) {
                $selectedName = trim((string) ($selectedStudent->first_name ?? '') . ' ' . (string) ($selectedStudent->last_name ?? ''));
            }
        }
        if ($selectedName === '') {
            $selectedName = '-';
        }

        if ((int) $request->input('export', 0) === 1) {
            return $this->exportMonthlyCsv($activeTab, (string) $selectedUniqueId, $selectedName, $month, $year, $marks);
        }

        $monthBuckets = [];
        for ($m = 1; $m <= 12; $m++) {
            $key = $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
            $monthBuckets[$key] = [
                'label' => date('M', mktime(0, 0, 0, $m, 1)),
                'year' => $year,
                'counts' => [
                    'present' => 0,
                    'late' => 0,
                    'early_out' => 0,
                    'halfday' => 0,
                    'absent' => 0,
                    'holiday' => 0,
                    'leave' => 0,
                    'total' => 0,
                ],
                'grid' => [],
            ];
        }

        $statusByDate = [];
        foreach ($yearlyMarks as $mark) {
            $key = date('Y-m', strtotime($mark->date));
            if (!isset($monthBuckets[$key])) {
                continue;
            }

            $status = $mark->status ?? '';
            if (isset($monthBuckets[$key]['counts'][$status])) {
                $monthBuckets[$key]['counts'][$status]++;
            }
            $monthBuckets[$key]['counts']['total']++;
            $statusByDate[$mark->date] = $status;
        }

        foreach ($calendarYearMap as $calendarDate => $calendarStatus) {
            if (!isset($statusByDate[$calendarDate]) || $statusByDate[$calendarDate] === '') {
                $statusByDate[$calendarDate] = $calendarStatus;
            }
        }

        foreach ($monthBuckets as $key => &$bucket) {
            $firstDate = $key . '-01';
            $daysInMonth = (int) date('t', strtotime($firstDate));
            $firstDay = (int) date('w', strtotime($firstDate));

            $cells = [];
            for ($i = 0; $i < 42; $i++) {
                $day = $i - $firstDay + 1;
                if ($day < 1 || $day > $daysInMonth) {
                    $cells[] = null;
                } else {
                    $dateStr = $key . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    $cells[] = $statusByDate[$dateStr] ?? '';
                }
            }
            $bucket['grid'] = $cells;
        }
        unset($bucket);

        $yearlyOverview = array_values($monthBuckets);

        $calendar = [];
        $firstDayOfWeek = (int) date('w', strtotime($startDate));
        $daysInMonth = (int) date('t', strtotime($startDate));
        $dayCounter = 1;

        for ($week = 0; $week < 6; $week++) {
            $row = [];
            for ($d = 0; $d < 7; $d++) {
                if ($week === 0 && $d < $firstDayOfWeek) {
                    $row[] = null;
                } elseif ($dayCounter > $daysInMonth) {
                    $row[] = null;
                } else {
                    $row[] = sprintf('%04d-%02d-%02d', $year, $month, $dayCounter);
                    $dayCounter++;
                }
            }
            $calendar[] = $row;
        }

        $setting = AttendanceSetting::where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->orderBy('id', 'desc')
            ->first();

        return view('attendance.view', compact(
            'students',
            'staff',
            'activeTab',
            'selectedUniqueId',
            'month',
            'year',
            'monthName',
            'marksByDate',
            'totalDays',
            'presentDays',
            'absentDays',
            'lateDays',
            'earlyOutDays',
            'halfDayDays',
            'holidayDays',
            'leaveDays',
            'attendancePercent',
            'calendar',
            'setting',
            'yearlyOverview',
            'calendarMonthMap'
        ));
    }
}
