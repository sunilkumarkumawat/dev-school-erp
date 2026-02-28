<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Salary Slip</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #0b1b2b; }
        .page { border: 1px solid #e2e8f0; border-radius: 10px; }
        .header { padding: 12px 16px; background: #0f3d56; color: #ffffff; }
        .title { font-size: 18px; font-weight: 700; color: #ffffff; }
        .meta { font-size: 11px; color: rgba(255, 255, 255, 0.75); }
        .section { padding: 12px 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e2e8f0; padding: 6px; font-size: 11px; }
        th { background: #f1f5f9; text-align: left; }
        .no-border td { border: none; padding: 2px 0; }
        .stat { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em; }
        .value { font-size: 14px; font-weight: 700; }
        .right { text-align: right; }
        .green { color: #166534; }
        .muted { color: #64748b; }
    </style>
</head>
<body>
<?php
    $monthLabel = date('F', mktime(0,0,0,$month,1));
?>
<div class="page">
    <div class="header">
        <table class="no-border">
            <tr>
                <td class="title">Salary Slip</td>
                <td class="meta right">Period: <?php echo e(\Carbon\Carbon::parse($monthStart)->format('d/m/Y')); ?> to <?php echo e(\Carbon\Carbon::parse($rangeEnd)->format('d/m/Y')); ?></td>
            </tr>
            <tr>
                <td class="meta"><?php echo e(trim(($member->first_name ?? '').' '.($member->last_name ?? ''))); ?> (<?php echo e($displayUniqueId ?? $uniqueId); ?>) | <?php echo e($monthLabel); ?> <?php echo e($year); ?></td>
                <td class="meta right">Role: <?php echo e($roleName); ?></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table>
            <tr>
                <td>
                    <div class="stat">Monthly Salary</div>
                    <div class="value"><?php echo e(number_format((float)$monthlySalary, 2)); ?></div>
                </td>
                <td>
                    <div class="stat">Working Days</div>
                    <div class="value"><?php echo e($daysInMonth); ?></div>
                </td>
                <td>
                    <div class="stat">Per Day Salary</div>
                    <div class="value"><?php echo e(number_format((float)$perDay, 2)); ?></div>
                </td>
                <td>
                    <div class="stat">Paid Days</div>
                    <div class="value"><?php echo e(rtrim(rtrim(number_format((float)$paidDays, 2), '0'), '.')); ?></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="stat">Gross (Attendance)</div>
                    <div class="value"><?php echo e(number_format((float)$gross, 2)); ?></div>
                </td>
                <td>
                    <div class="stat">Manual Deductions</div>
                    <div class="value"><?php echo e(number_format((float)$manualTotal, 2)); ?></div>
                </td>
                <td>
                    <div class="stat">Loan/Advance</div>
                    <div class="value"><?php echo e(number_format((float)$loanTotal, 2)); ?></div>
                </td>
                <td>
                    <div class="stat green">Net Payable</div>
                    <div class="value green"><?php echo e(number_format((float)$net, 2)); ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="stat">Total Deductions</div>
                    <div class="value"><?php echo e(number_format((float)$deductionTotal, 2)); ?></div>
                </td>
                <td>
                    <div class="stat">Leave Allowed</div>
                    <div class="value"><?php echo e($payrollSetting->paid_leave_limit === null ? 'Unlimited' : $payrollSetting->paid_leave_limit.' days'); ?></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section" style="padding-top:0;">
        <div style="font-weight:700;margin-bottom:6px;">Attendance Summary</div>
        <table>
            <tr>
                <td>Present: <b><?php echo e($countsByStatus['present']); ?></b></td>
                <td>Late: <b><?php echo e($countsByStatus['late']); ?></b></td>
                <td>Early Out: <b><?php echo e($countsByStatus['early_out']); ?></b></td>
                <td>Half Day: <b><?php echo e($countsByStatus['halfday']); ?></b></td>
            </tr>
            <tr>
                <td>Absent: <b><?php echo e($countsByStatus['absent']); ?></b></td>
                <td>Holiday: <b><?php echo e($countsByStatus['holiday']); ?></b></td>
                <td>Leave: <b><?php echo e($countsByStatus['leave']); ?></b></td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>

    <div class="section" style="padding-top:0;">
        <table>
            <tr>
                <th colspan="3">Manual Deductions</th>
                <th colspan="3">Loan / Advance Deductions</th>
            </tr>
            <tr>
                <th>Title</th>
                <th>Remark</th>
                <th class="right">Amount</th>
                <th>Type</th>
                <th>Title</th>
                <th class="right">Amount</th>
            </tr>
            <?php
                $maxRows = max($manualDeductions->count(), $loanDeductions->count(), 1);
            ?>
            <?php for($i = 0; $i < $maxRows; $i++): ?>
                <?php
                    $m = $manualDeductions[$i] ?? null;
                    $l = $loanDeductions[$i] ?? null;
                ?>
                <tr>
                    <td><?php echo e($m->title ?? '-'); ?></td>
                    <td><?php echo e($m->remark ?? '-'); ?></td>
                    <td class="right"><?php echo e($m ? number_format((float)$m->amount, 2) : '-'); ?></td>
                    <td><?php echo e($l ? ucfirst($l->type ?? 'loan') : '-'); ?></td>
                    <td><?php echo e($l->title ?? '-'); ?></td>
                    <td class="right"><?php echo e($l ? number_format((float)$l->amount, 2) : '-'); ?></td>
                </tr>
            <?php endfor; ?>
        </table>
        <div class="muted" style="margin-top:6px;">This is a system generated salary slip.</div>
    </div>
</div>
</body>
</html>
<?php /**PATH /home/rusofterp/public_html/dev/resources/views/payroll/staff_slip_pdf.blade.php ENDPATH**/ ?>