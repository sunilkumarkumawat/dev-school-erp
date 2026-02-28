<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayrollDeduction;
use App\Models\User;
use Session;

class StaffDeductionController extends Controller
{
    public function index(Request $request)
    {
        $branchId = Session::get('branch_id');
        $sessionId = Session::get('session_id');

        $month = (int) ($request->month ?? date('n'));
        $year = (int) ($request->year ?? date('Y'));

        $staffList = User::select('id', 'first_name', 'last_name', 'role_id', 'attendance_unique_id')
            ->where('session_id', $sessionId)
            ->where('branch_id', $branchId)
            ->where('status', 1)
            ->whereNotIn('role_id', [1, 3])
            ->orderBy('first_name')
            ->get();

        if ($request->isMethod('post')) {
            $action = (string) $request->input('action', '');

            if ($action === 'add_deduction') {
                $request->validate([
                    'unique_id' => 'required|string',
                    'amount' => 'required|numeric|min:0.01|max:99999999',
                    'title' => 'nullable|string|max:100',
                    'remark' => 'nullable|string|max:1000',
                ]);

                $uid = (string) $request->unique_id;
                if (stripos($uid, 'USR-') !== 0) {
                    return redirect()->to('payroll/staff/deductions?month=' . $month . '&year=' . $year)
                        ->with('error', 'Invalid staff selection.');
                }

                $userId = (int) str_replace('USR-', '', $uid);
                $user = User::where('id', $userId)
                    ->where('session_id', $sessionId)
                    ->where('branch_id', $branchId)
                    ->first();

                if (!$user) {
                    return redirect()->to('payroll/staff/deductions?month=' . $month . '&year=' . $year)
                        ->with('error', 'Staff not found.');
                }

                PayrollDeduction::create([
                    'branch_id' => $branchId,
                    'session_id' => $sessionId,
                    'unique_id' => $uid,
                    'month' => $month,
                    'year' => $year,
                    'amount' => (float) $request->amount,
                    'original_amount' => (float) $request->amount,
                    'type' => 'manual',
                    'title' => $request->title,
                    'remark' => $request->remark,
                    'is_applied' => 1,
                    'created_by' => Session::get('id'),
                ]);

                return redirect()->to('payroll/staff/deductions?month=' . $month . '&year=' . $year)
                    ->with('message', 'Deduction added successfully.');
            }

            if ($action === 'update_deduction') {
                $request->validate([
                    'deduction_id' => 'required|integer',
                    'amount' => 'required|numeric|min:0.01|max:99999999',
                    'title' => 'nullable|string|max:100',
                    'remark' => 'nullable|string|max:1000',
                ]);

                $deduction = PayrollDeduction::where('id', (int) $request->deduction_id)
                    ->where('branch_id', $branchId)
                    ->where('session_id', $sessionId)
                    ->whereNull('loan_id')
                    ->where('type', 'manual')
                    ->first();

                if ($deduction) {
                    $update = [
                        'amount' => (float) $request->amount,
                        'title' => $request->title,
                        'remark' => $request->remark,
                    ];
                    $update['original_amount'] = (float) $request->amount;
                    $deduction->update($update);
                }

                return redirect()->to('payroll/staff/deductions?month=' . $month . '&year=' . $year)
                    ->with('message', 'Deduction updated successfully.');
            }

            if ($action === 'delete_deduction') {
                $request->validate([
                    'deduction_id' => 'required|integer',
                ]);

                PayrollDeduction::where('id', (int) $request->deduction_id)
                    ->where('branch_id', $branchId)
                    ->where('session_id', $sessionId)
                    ->whereNull('loan_id')
                    ->where('type', 'manual')
                    ->delete();

                return redirect()->to('payroll/staff/deductions?month=' . $month . '&year=' . $year)
                    ->with('message', 'Deduction deleted successfully.');
            }

            // Waive-off feature removed
        }

        $deductions = PayrollDeduction::where('branch_id', $branchId)
            ->where('session_id', $sessionId)
            ->whereNull('loan_id')
            ->where('type', 'manual')
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('id', 'desc')
            ->get();

        $userIds = $deductions
            ->map(function ($d) {
                $uid = (string) ($d->unique_id ?? '');
                return (stripos($uid, 'USR-') === 0) ? (int) str_replace('USR-', '', $uid) : null;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $usersById = empty($userIds)
            ? collect()
            : User::select('id', 'first_name', 'last_name', 'role_id', 'attendance_unique_id')
                ->whereIn('id', $userIds)
                ->get()
                ->keyBy('id');

        return view('payroll.staff_deductions', compact('deductions', 'staffList', 'usersById', 'month', 'year'));
    }
}
