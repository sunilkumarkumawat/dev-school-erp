<?php

namespace App\Http\Controllers;

use App\Models\AttendanceMark;
use App\Models\Admission;
use App\Models\Master\LeaveManagement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class AttendanceLeaveApprovalController extends Controller
{
    private function leaveStatusMeta($status)
    {
        $raw = (string) $status;
        if ($raw === '1') {
            return ['label' => 'Approved', 'class' => 'badge-success'];
        }

        if ($raw === '0') {
            return ['label' => 'Rejected', 'class' => 'badge-danger'];
        }

        if ($raw === '3') {
            return ['label' => 'Cancelled', 'class' => 'badge-secondary'];
        }

        return ['label' => 'Pending', 'class' => 'badge-warning'];
    }

    private function syncLeaveMarks($leave, $approvedBy)
    {
        $entityType = $leave->user_type === 'student' ? 'student' : 'staff';
        $fromDate = Carbon::parse($leave->from_date)->startOfDay();
        $toDate = Carbon::parse($leave->to_date)->startOfDay();

        $cursor = $fromDate->copy();
        while ($cursor->lte($toDate)) {
            AttendanceMark::updateOrCreate(
                [
                    'unique_id' => $leave->attendance_unique_id,
                    'date' => $cursor->toDateString(),
                    'branch_id' => $leave->branch_id,
                    'session_id' => $leave->session_id,
                ],
                [
                    'entity_type' => $entityType,
                    'status' => 'leave',
                    'in_time' => null,
                    'out_time' => null,
                    'created_by' => $approvedBy,
                ]
            );

            $cursor->addDay();
        }
    }

    private function clearLeaveMarks($leave)
    {
        AttendanceMark::where('unique_id', $leave->attendance_unique_id)
            ->where('branch_id', $leave->branch_id)
            ->where('session_id', $leave->session_id)
            ->whereBetween('date', [$leave->from_date, $leave->to_date])
            ->where('status', 'leave')
            ->delete();
    }

    public function index(Request $request)
    {
        if ((int) Session::get('role_id') !== 1) {
            return redirect()->to(url('access-denied'));
        }

        $branchId = Session::get('branch_id');
        $sessionId = Session::get('session_id');
        $statusFilter = (string) $request->input('status', '2');

        $query = LeaveManagement::where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->whereNotNull('attendance_unique_id')
            ->orderByDesc('id');

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $rowsRaw = $query->get();

        $studentIds = $rowsRaw->filter(function ($r) {
            return (string) ($r->user_type ?? '') === 'student' && !empty($r->user_id);
        })->pluck('user_id')->unique()->values();

        $staffIds = $rowsRaw->filter(function ($r) {
            return (string) ($r->user_type ?? '') !== 'student' && !empty($r->user_id);
        })->pluck('user_id')->unique()->values();

        $studentsById = Admission::select('id', 'first_name', 'last_name')
            ->whereIn('id', $studentIds)
            ->get()
            ->keyBy('id');

        $staffById = User::select('id', 'first_name', 'last_name')
            ->whereIn('id', $staffIds)
            ->get()
            ->keyBy('id');

        $rows = $rowsRaw->map(function ($row) use ($studentsById, $staffById) {
            $meta = $this->leaveStatusMeta($row->status);
            $row->status_label = $meta['label'];
            $row->status_class = $meta['class'];

            $name = '';
            if ((string) ($row->user_type ?? '') === 'student') {
                $student = $studentsById->get($row->user_id);
                if ($student) {
                    $name = trim((string) ($student->first_name ?? '') . ' ' . (string) ($student->last_name ?? ''));
                }
            } else {
                $staff = $staffById->get($row->user_id);
                if ($staff) {
                    $name = trim((string) ($staff->first_name ?? '') . ' ' . (string) ($staff->last_name ?? ''));
                }
            }

            $row->person_name = $name !== '' ? $name : '-';
            return $row;
        });

        return view('attendance.leave_approvals', compact('rows', 'statusFilter'));
    }

    public function action(Request $request)
    {
        if ((int) Session::get('role_id') !== 1) {
            return redirect()->to(url('access-denied'));
        }

        $request->validate([
            'leave_id' => 'required|integer',
            'action' => 'required|in:approve,reject,cancel,delete',
        ]);

        $branchId = Session::get('branch_id');
        $sessionId = Session::get('session_id');

        $leave = LeaveManagement::where('id', (int) $request->leave_id)
            ->where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->first();

        if (!$leave) {
            return redirect()->back()->with('error', 'Leave request not found.');
        }

        if ($request->action === 'approve') {
            $leave->status = '1';
            $leave->save();
            $this->syncLeaveMarks($leave, (int) Session::get('id'));
            return redirect()->back()->with('message', 'Leave approved successfully.');
        }

        if ($request->action === 'reject') {
            $leave->status = '0';
            $leave->save();
            $this->clearLeaveMarks($leave);
            return redirect()->back()->with('message', 'Leave rejected successfully.');
        }

        if ($request->action === 'cancel') {
            $leave->status = '3';
            $leave->save();
            $this->clearLeaveMarks($leave);
            return redirect()->back()->with('message', 'Leave cancelled successfully.');
        }

        $this->clearLeaveMarks($leave);
        $leave->delete();

        return redirect()->back()->with('message', 'Leave deleted successfully.');
    }
}
