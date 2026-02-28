<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayrollLoan;
use App\Models\PayrollDeduction;
use App\Models\PayrollLoanPayment;
use App\Models\User;
use Carbon\Carbon;
use Session;
use DB;

class StaffLoanController extends Controller
{
    public function index(Request $request)
    {
        $branchId = Session::get('branch_id');
        $sessionId = Session::get('session_id');

        $typeFilter = (string) ($request->type ?? '');
        $statusFilter = (string) ($request->status ?? 'active'); // active|closed|all

        if ($request->isMethod('post')) {
            $action = (string) $request->input('action', '');

            if ($action === 'add_loan') {
                $request->validate([
                    'unique_id' => 'required|string',
                    'loan_type' => 'required|in:loan,advance',
                    'principal_amount' => 'required|numeric|min:0.01|max:99999999',
                    'monthly_deduction' => 'required|numeric|min:0.01|max:99999999',
                    'start_month' => 'required|integer|min:1|max:12',
                    'start_year' => 'required|integer|min:2000|max:2100',
                    'title' => 'nullable|string|max:100',
                    'remark' => 'nullable|string|max:1000',
                ]);

                PayrollLoan::create([
                    'branch_id' => $branchId,
                    'session_id' => $sessionId,
                    'unique_id' => $request->unique_id,
                    'type' => $request->loan_type,
                    'principal_amount' => (float) $request->principal_amount,
                    'monthly_deduction' => (float) $request->monthly_deduction,
                    'start_month' => (int) $request->start_month,
                    'start_year' => (int) $request->start_year,
                    'is_active' => 1,
                    'title' => $request->title,
                    'remark' => $request->remark,
                    'created_by' => Session::get('id'),
                ]);

                return redirect()->to('payroll/staff/loans')
                    ->with('message', 'Loan/Advance saved successfully.');
            }

            if ($action === 'update_loan') {
                $request->validate([
                    'loan_id' => 'required|integer',
                    'unique_id' => 'required|string',
                    'loan_type' => 'required|in:loan,advance',
                    'principal_amount' => 'required|numeric|min:0.01|max:99999999',
                    'monthly_deduction' => 'required|numeric|min:0.01|max:99999999',
                    'start_month' => 'required|integer|min:1|max:12',
                    'start_year' => 'required|integer|min:2000|max:2100',
                    'is_active' => 'required|in:0,1',
                    'title' => 'nullable|string|max:100',
                    'remark' => 'nullable|string|max:1000',
                ]);

                PayrollLoan::where('id', (int) $request->loan_id)
                    ->where('branch_id', $branchId)
                    ->where('session_id', $sessionId)
                    ->update([
                        'unique_id' => $request->unique_id,
                        'type' => $request->loan_type,
                        'principal_amount' => (float) $request->principal_amount,
                        'monthly_deduction' => (float) $request->monthly_deduction,
                        'start_month' => (int) $request->start_month,
                        'start_year' => (int) $request->start_year,
                        'is_active' => (int) $request->is_active,
                        'title' => $request->title,
                        'remark' => $request->remark,
                    ]);

                return redirect()->to('payroll/staff/loans')
                    ->with('message', 'Loan/Advance updated successfully.');
            }

            if ($action === 'close_loan') {
                $request->validate([
                    'loan_id' => 'required|integer',
                ]);

                $loan = PayrollLoan::where('id', (int) $request->loan_id)
                    ->where('branch_id', $branchId)
                    ->where('session_id', $sessionId)
                    ->first();

                if (!$loan) {
                    return redirect()->to('payroll/staff/loans')->with('error', 'Loan/Advance not found.');
                }

                $deducted = (float) PayrollDeduction::join('payroll_salaries', function ($join) {
                        $join->on('payroll_salaries.unique_id', '=', 'payroll_deductions.unique_id')
                            ->on('payroll_salaries.month', '=', 'payroll_deductions.month')
                            ->on('payroll_salaries.year', '=', 'payroll_deductions.year');
                    })
                    ->where('payroll_deductions.loan_id', $loan->id)
                    ->where('payroll_deductions.is_applied', 1)
                    ->sum('payroll_deductions.amount');

                $payments = (float) PayrollLoanPayment::where('loan_id', $loan->id)->sum('amount');
                $remaining = (float) $loan->principal_amount - ($deducted + $payments);

                if ($remaining > 0) {
                    return redirect()->to('payroll/staff/loans')
                        ->with('error', 'Remaining amount exists. Loan cannot be closed.');
                }

                $loan->is_active = 0;
                $loan->save();

                return redirect()->to('payroll/staff/loans')
                    ->with('message', 'Loan/Advance closed successfully.');
            }

            if ($action === 'add_payment') {
                $request->validate([
                    'loan_id' => 'required|integer',
                    'payment_date' => 'required|date',
                    'amount' => 'required|numeric|min:0.01|max:99999999',
                    'remark' => 'nullable|string|max:200',
                ]);

                $loan = PayrollLoan::where('id', (int) $request->loan_id)
                    ->where('branch_id', $branchId)
                    ->where('session_id', $sessionId)
                    ->first();

                if (!$loan) {
                    return redirect()->to('payroll/staff/loans')->with('error', 'Loan/Advance not found.');
                }

                PayrollLoanPayment::create([
                    'branch_id' => $branchId,
                    'session_id' => $sessionId,
                    'loan_id' => $loan->id,
                    'payment_date' => $request->payment_date,
                    'amount' => (float) $request->amount,
                    'remark' => $request->remark,
                    'created_by' => Session::get('id'),
                ]);

                // Auto-close if remaining becomes 0 or less.
                $deducted = (float) PayrollDeduction::join('payroll_salaries', function ($join) {
                        $join->on('payroll_salaries.unique_id', '=', 'payroll_deductions.unique_id')
                            ->on('payroll_salaries.month', '=', 'payroll_deductions.month')
                            ->on('payroll_salaries.year', '=', 'payroll_deductions.year');
                    })
                    ->where('payroll_deductions.loan_id', $loan->id)
                    ->where('payroll_deductions.is_applied', 1)
                    ->sum('payroll_deductions.amount');
                $payments = (float) (PayrollLoanPayment::where('loan_id', $loan->id)->sum('amount'));
                $remaining = (float) $loan->principal_amount - ($deducted + $payments);
                if ($remaining <= 0) {
                    $loan->is_active = 0;
                    $loan->save();
                }

                return redirect()->to('payroll/staff/loans')
                    ->with('message', 'Payment saved successfully.');
            }
        }

        $loansQ = PayrollLoan::where('branch_id', $branchId)
            ->where('session_id', $sessionId);

        if (in_array($typeFilter, ['loan', 'advance'], true)) {
            $loansQ->where('type', $typeFilter);
        }

        if ($statusFilter === 'active') {
            $loansQ->where('is_active', 1);
        } elseif ($statusFilter === 'closed') {
            $loansQ->where('is_active', 0);
        }

        $loans = $loansQ->orderBy('id', 'desc')->get();

        $loanIds = $loans->pluck('id')->values()->all();
        $deductedTotals = empty($loanIds)
            ? collect()
            : PayrollDeduction::select('payroll_deductions.loan_id', DB::raw('SUM(payroll_deductions.amount) as total'))
                ->join('payroll_salaries', function ($join) {
                    $join->on('payroll_salaries.unique_id', '=', 'payroll_deductions.unique_id')
                        ->on('payroll_salaries.month', '=', 'payroll_deductions.month')
                        ->on('payroll_salaries.year', '=', 'payroll_deductions.year');
                })
                ->whereIn('payroll_deductions.loan_id', $loanIds)
                ->where('payroll_deductions.is_applied', 1)
                ->groupBy('payroll_deductions.loan_id')
                ->pluck('total', 'payroll_deductions.loan_id');

        $paymentTotals = empty($loanIds)
            ? collect()
            : PayrollLoanPayment::select('loan_id', DB::raw('SUM(amount) as total'))
                ->whereIn('loan_id', $loanIds)
                ->groupBy('loan_id')
                ->pluck('total', 'loan_id');

        $userIds = $loans
            ->map(function ($l) {
                $uid = (string) ($l->unique_id ?? '');
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

        $staffList = User::select('id', 'first_name', 'last_name', 'role_id', 'attendance_unique_id')
            ->where('session_id', $sessionId)
            ->where('branch_id', $branchId)
            ->where('status', 1)
            ->whereNotIn('role_id', [1, 3])
            ->orderBy('first_name')
            ->get();

        $rows = [];
        foreach ($loans as $loan) {
            $uid = (string) $loan->unique_id;
            $userId = (stripos($uid, 'USR-') === 0) ? (int) str_replace('USR-', '', $uid) : null;
            $user = $userId ? ($usersById[$userId] ?? null) : null;
            $name = $user ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) : $uid;
            $displayUniqueId = $user && trim((string) ($user->attendance_unique_id ?? '')) !== ''
                ? trim((string) $user->attendance_unique_id)
                : $uid;

            $deducted = (float) ($deductedTotals[$loan->id] ?? 0);
            $paid = (float) ($paymentTotals[$loan->id] ?? 0);
            $remaining = max(0, (float) $loan->principal_amount - ($deducted + $paid));

            if ($remaining <= 0 && (int) ($loan->is_active ?? 0) === 1) {
                $loan->is_active = 0;
                $loan->save();
            }
            if ($remaining > 0 && (int) ($loan->is_active ?? 0) === 0) {
                $loan->is_active = 1;
                $loan->save();
            }

            $rows[] = [
                'id' => $loan->id,
                'unique_id' => $uid,
                'display_unique_id' => $displayUniqueId,
                'name' => $name,
                'type' => $loan->type,
                'title' => $loan->title,
                'remark' => $loan->remark,
                'principal_amount' => (float) $loan->principal_amount,
                'monthly_deduction' => (float) $loan->monthly_deduction,
                'start' => sprintf('%02d/%04d', (int) $loan->start_month, (int) $loan->start_year),
                'deducted' => $deducted,
                'paid' => $paid,
                'remaining' => $remaining,
                'is_active' => (int) ($loan->is_active ?? 0) === 1,
            ];
        }

        return view('payroll.staff_loans', compact('rows', 'typeFilter', 'statusFilter', 'staffList'));
    }
}
