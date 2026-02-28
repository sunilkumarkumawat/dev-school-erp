<?php $__env->startSection('content'); ?>

<?php echo $__env->make('payroll.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
    $monthLabel = date('F', mktime(0, 0, 0, $month, 1));
    $staffCount = is_countable($rows ?? null) ? count($rows) : 0;
    $totalSalaryNow = (float)($totals['salary_till_now'] ?? 0);
    $totalManual = (float)($totals['manual_deductions'] ?? 0);
    $totalLoan = (float)($totals['loan_deductions'] ?? 0);
    $totalDeduction = $totalManual + $totalLoan;
    $totalNet = (float)($totals['net_salary'] ?? ($totalSalaryNow - $totalDeduction));
    $leaveAllowedLabel = isset($payrollSetting) && $payrollSetting->paid_leave_limit !== null
        ? $payrollSetting->paid_leave_limit . ' days'
        : 'Not Set';
?>

<div class="content-wrapper payroll-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="payroll-hero">
                <div class="payroll-hero-inner">
                    <div>
                        <div class="payroll-hero-kicker">Payroll Center</div>
                        <div class="payroll-hero-title">Staff Payroll</div>
                        <div class="payroll-hero-subtitle">Salary calculation from attendance (<?php echo e($monthLabel); ?> <?php echo e($year); ?>)</div>
                        <div class="payroll-hero-chips">
                            <span class="payroll-chip"><i class="fa fa-calendar"></i> <?php echo e($monthLabel); ?> <?php echo e($year); ?></span>
                            <span class="payroll-chip"><i class="fa fa-clock-o"></i> Till: <?php echo e(\Carbon\Carbon::parse($rangeEnd)->format('d/m/Y')); ?></span>
                            <?php if(isset($payrollSetting)): ?>
                                <span class="payroll-chip"><i class="fa fa-leaf"></i> Leave Allowed: <?php echo e($leaveAllowedLabel); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="payroll-hero-actions">
                        <button type="button" class="btn btn-sm btn-payroll btn-payroll-light" data-toggle="modal" data-target="#payrollSettingsModal">
                            <i class="fa fa-sliders"></i> Payroll Settings
                        </button>
                        <a href="<?php echo e(url('payroll/staff/loans')); ?>" class="btn btn-sm btn-payroll btn-payroll-light">
                            <i class="fa fa-list"></i> Loan / Advance
                        </a>
                        <form method="post" action="<?php echo e(url('payroll/staff?month='.$month.'&year='.$year)); ?>" style="display:inline-block;">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="action" value="generate_salary_bulk">
                            <button type="submit" class="btn btn-sm btn-payroll btn-payroll-accent">
                                <i class="fa fa-check-circle"></i> Generate Salary
                            </button>
                        </form>
                        <button type="button" class="btn btn-sm btn-payroll btn-payroll-primary" data-toggle="modal" data-target="#salaryModal">
                            <i class="fa fa-cog"></i> Set Salary
                        </button>
                    </div>
                </div>
            </div>

            <div class="payroll-stats">
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Total Staff</div>
                    <div class="payroll-stat-value"><?php echo e($staffCount); ?></div>
                    <div class="payroll-stat-sub">Active this period</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Salary Till Now</div>
                    <div class="payroll-stat-value"><?php echo e(number_format($totalSalaryNow, 2)); ?></div>
                    <div class="payroll-stat-sub">Before deductions</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Deductions</div>
                    <div class="payroll-stat-value"><?php echo e(number_format($totalDeduction, 2)); ?></div>
                    <div class="payroll-stat-sub">Manual + loans</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Net Payable</div>
                    <div class="payroll-stat-value <?php echo e($totalNet < 0 ? 'text-danger' : ''); ?>"><?php echo e(number_format($totalNet, 2)); ?></div>
                    <div class="payroll-stat-sub">Calculated total</div>
                </div>
            </div>

            <div class="card card-outline card-orange payroll-card">
                <div class="card-header payroll-card-header">
                    <div>
                        <div class="payroll-card-title"><i class="fa fa-money"></i> Payroll Register</div>
                        <div class="text-muted small">Manage salaries, deductions, and slips for the selected period.</div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center" style="gap:8px;">
                        <span class="badge-soft">Period: <?php echo e($monthLabel); ?> <?php echo e($year); ?></span>
                        <span class="badge-soft">Till: <?php echo e(\Carbon\Carbon::parse($rangeEnd)->format('d/m/Y')); ?></span>
                    </div>
                </div>

                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <strong>Validation Error:</strong>
                            <ul class="mb-0 pl-3">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="get" action="<?php echo e(url('payroll/staff')); ?>">
                        <div class="d-flex flex-wrap align-items-center payroll-filter">
                            <select name="month" class="form-control form-control-sm" style="min-width:120px;">
                                <?php for($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo e($m); ?>" <?php echo e((int)$month === (int)$m ? 'selected' : ''); ?>><?php echo e(date('M', mktime(0,0,0,$m,1))); ?></option>
                                <?php endfor; ?>
                            </select>
                            <select name="year" class="form-control form-control-sm" style="min-width:120px;">
                                <?php for($y = date('Y')-3; $y <= date('Y')+1; $y++): ?>
                                    <option value="<?php echo e($y); ?>" <?php echo e((int)$year === (int)$y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                <?php endfor; ?>
                            </select>
                            <button class="btn btn-sm btn-payroll btn-payroll-primary">Load</button>

                            <div class="ml-auto payroll-inline-note">
                                Till: <span class="badge-soft"><?php echo e(\Carbon\Carbon::parse($rangeEnd)->format('d/m/Y')); ?></span>
                            </div>
                        </div>
                    </form>

                    <?php if(isset($payrollSetting)): ?>
                        <div class="d-flex flex-wrap align-items-center mt-2" style="gap:8px;">
                            <span class="badge-soft">Leave Allowed: <?php echo e($leaveAllowedLabel); ?></span>
                            <span class="badge-soft">Early Out Credit: <?php echo e(number_format((float)($payrollSetting->early_out_weight ?? 1), 2)); ?> day</span>
                            <span class="badge-soft">Half Day Credit: <?php echo e(number_format((float)($payrollSetting->halfday_weight ?? 0.5), 2)); ?> day</span>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered payroll-table" id="payrollTable">
                            <thead>
                                <tr>
                                    <th>Unique ID</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th class="text-right">Monthly Salary</th>
                                    <th class="text-center">Working Days</th>
                                    <th class="text-right">Per Day Salary</th>
                                    <th class="text-center">Paid Days</th>
                                    <th class="text-right">Salary Till Now</th>
                                    <th class="text-right">Manual Deduction</th>
                                    <th class="text-right">Loan Deduction</th>
                                    <th class="text-right">Net Salary</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($row['unique_id']); ?></td>
                                        <td><?php echo e($row['name']); ?></td>
                                        <td><?php echo e($row['role']); ?></td>
                                        <td class="text-right"><?php echo e(number_format($row['monthly_salary'], 2)); ?></td>
                                        <td class="text-center"><?php echo e($row['working_days']); ?></td>
                                        <td class="text-right"><?php echo e(number_format($row['per_day_salary'], 2)); ?></td>
                                        <td class="text-center"><?php echo e(rtrim(rtrim(number_format($row['paid_days'], 2), '0'), '.')); ?></td>
                                        <td class="text-right"><b><?php echo e(number_format($row['salary_till_now'], 2)); ?></b></td>
                                        <td class="text-right">
                                            <?php echo e(($row['manual_deductions'] ?? 0) > 0 ? '-' . number_format($row['manual_deductions'], 2) : '0.00'); ?>

                                        </td>
                                        <td class="text-right">
                                            <?php echo e(($row['loan_deductions'] ?? 0) > 0 ? '-' . number_format($row['loan_deductions'], 2) : '0.00'); ?>

                                        </td>
                                        <?php $netVal = (float) ($row['net_salary'] ?? 0); ?>
                                        <td class="text-right"><b class="<?php echo e($netVal < 0 ? 'text-danger' : 'text-success'); ?>"><?php echo e(number_format($netVal, 2)); ?></b></td>
                                        <td>
                                            <?php if(!empty($row['generated_at'])): ?>
                                                <span class="badge badge-payroll badge-payroll-success mr-1">Generated</span>
                                                <form method="post" action="<?php echo e(url('payroll/staff?month='.$month.'&year='.$year)); ?>" style="display:inline-block;" onsubmit="return confirm('Reset generated salary?');">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="action" value="reset_salary">
                                                    <input type="hidden" name="unique_id" value="<?php echo e($row['unique_id']); ?>">
                                                    <button class="btn btn-sm btn-payroll btn-payroll-danger mr-1">Reset</button>
                                                </form>
                                                <a class="btn btn-sm btn-payroll btn-payroll-outline" target="_blank" href="<?php echo e(url('payroll/staff/slip-pdf?staff='.$row['unique_id'].'&month='.$month.'&year='.$year)); ?>">
                                                    <i class="fa fa-file-pdf-o"></i> PDF
                                                </a>
                                            <?php else: ?>
                                                <form method="post" action="<?php echo e(url('payroll/staff?month='.$month.'&year='.$year)); ?>" style="display:inline-block;">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="action" value="generate_salary">
                                                    <input type="hidden" name="unique_id" value="<?php echo e($row['unique_id']); ?>">
                                                    <button class="btn btn-sm btn-payroll btn-payroll-success mr-1">Generate</button>
                                                </form>
                                                <a class="btn btn-sm btn-payroll btn-payroll-outline" href="<?php echo e(url('payroll/staff/edit?staff='.$row['unique_id'].'&month='.$month.'&year='.$year)); ?>" title="Edit Payroll">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="12" class="text-center text-muted">No staff found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <?php if(!empty($rows)): ?>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-right">Total</th>
                                        <th class="text-right"><?php echo e(number_format($totals['salary_till_now'] ?? 0, 2)); ?></th>
                                        <th class="text-right"><?php echo e(($totals['manual_deductions'] ?? 0) > 0 ? '-' . number_format($totals['manual_deductions'], 2) : '0.00'); ?></th>
                                        <th class="text-right"><?php echo e(($totals['loan_deductions'] ?? 0) > 0 ? '-' . number_format($totals['loan_deductions'], 2) : '0.00'); ?></th>
                                        <?php $totalNet = (float) ($totals['net_salary'] ?? 0); ?>
                                        <th class="text-right"><span class="<?php echo e($totalNet < 0 ? 'text-danger' : 'text-success'); ?>"><?php echo e(number_format($totalNet, 2)); ?></span></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            <?php endif; ?>
                        </table>
                    </div>

                    <div class="payroll-inline-note mt-2">
                        Calculation uses Payroll Settings (credits/limits). You can change rules from <b>Payroll Settings</b>.
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Payroll Settings Modal -->
<div class="modal fade payroll-modal" id="payrollSettingsModal" tabindex="-1" role="dialog" aria-labelledby="payrollSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="<?php echo e(url('payroll/staff?month='.$month.'&year='.$year)); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="settings">
                <div class="modal-header">
                    <h5 class="modal-title" id="payrollSettingsModalLabel"><i class="fa fa-sliders"></i> Payroll Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Paid Leave Allowed (Days / Month)</label>
                                <input type="number" min="0" max="31" name="paid_leave_limit" class="form-control form-control-sm"
                                       value="<?php echo e(old('paid_leave_limit', $payrollSetting->paid_leave_limit ?? '')); ?>"
                                       placeholder="Leave blank for Unlimited">
                                <small class="text-muted">If set, extra leave days will be treated as unpaid (0 credit).</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Early Out Credit (Paid Day)</label>
                                <?php $earlyOut = old('early_out_weight', $payrollSetting->early_out_weight ?? 1); $earlyOutVal = (float) $earlyOut; ?>
                                <select name="early_out_weight" class="form-control form-control-sm">
                                    <option value="1.00" <?php echo e($earlyOutVal == 1.0 ? 'selected' : ''); ?>>1.00 (No Deduction)</option>
                                    <option value="0.50" <?php echo e($earlyOutVal == 0.5 ? 'selected' : ''); ?>>0.50 (Half Day)</option>
                                    <option value="0.00" <?php echo e($earlyOutVal == 0.0 ? 'selected' : ''); ?>>0.00 (Full Deduction)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Late Frequency (Every N = Penalty)</label>
                                <input type="number" min="1" max="31" name="late_frequency" class="form-control form-control-sm"
                                       value="<?php echo e(old('late_frequency', $payrollSetting->late_frequency ?? '')); ?>"
                                       placeholder="e.g. 3">
                                <small class="text-muted">Below frequency, late counts as full day credit.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Early Out Frequency (Every N = Penalty)</label>
                                <input type="number" min="1" max="31" name="early_out_frequency" class="form-control form-control-sm"
                                       value="<?php echo e(old('early_out_frequency', $payrollSetting->early_out_frequency ?? '')); ?>"
                                       placeholder="e.g. 3">
                                <small class="text-muted">Below frequency, early-out counts as full day credit.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Leave Credit (Paid Day)</label>
                                <?php $leaveW = old('leave_weight', $payrollSetting->leave_weight ?? 1); $leaveVal = (float) $leaveW; ?>
                                <select name="leave_weight" class="form-control form-control-sm">
                                    <option value="1.00" <?php echo e($leaveVal == 1.0 ? 'selected' : ''); ?>>1.00 (Paid Leave)</option>
                                    <option value="0.00" <?php echo e($leaveVal == 0.0 ? 'selected' : ''); ?>>0.00 (Unpaid Leave)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Half Day Credit (Paid Day)</label>
                                <?php $halfW = old('halfday_weight', $payrollSetting->halfday_weight ?? 0.5); $halfVal = (float) $halfW; ?>
                                <select name="halfday_weight" class="form-control form-control-sm">
                                    <option value="0.50" <?php echo e($halfVal == 0.5 ? 'selected' : ''); ?>>0.50</option>
                                    <option value="1.00" <?php echo e($halfVal == 1.0 ? 'selected' : ''); ?>>1.00</option>
                                    <option value="0.00" <?php echo e($halfVal == 0.0 ? 'selected' : ''); ?>>0.00</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Salary Modal -->
<div class="modal fade payroll-modal" id="salaryModal" tabindex="-1" role="dialog" aria-labelledby="salaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form method="post" action="<?php echo e(url('payroll/staff?month='.$month.'&year='.$year)); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="salary">
                <div class="modal-header">
                    <h5 class="modal-title" id="salaryModalLabel"><i class="fa fa-cog"></i> Set Staff Salary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th>Staff</th>
                                    <th>Role</th>
                                    <th style="width:220px;" class="text-right">Set Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sr = 1; ?>
                                <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $roleName = $rolesById[$member->role_id]->name ?? '-';
                                        $old = $member->salary ?? '';
                                    ?>
                                    <tr>
                                        <td><?php echo e($sr++); ?></td>
                                        <td><?php echo e(trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? ''))); ?> (<?php echo e(trim((string)($member->attendance_unique_id ?? '')) !== '' ? $member->attendance_unique_id : ('USR-' . $member->id)); ?>)</td>
                                        <td><?php echo e($roleName); ?></td>
                                        <td class="text-right">
                                            <input type="number" step="0.01" min="0" name="salary[<?php echo e($member->id); ?>]" class="form-control form-control-sm text-right" value="<?php echo e($old); ?>">
                                            <div class="text-muted small mt-1 text-left">
                                                Saved: <?php echo e($old !== '' ? number_format((float)$old, 2) : '-'); ?>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Salary</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        try {
            $('#payrollTable').DataTable({
                pageLength: 25,
                ordering: true,
                searching: true
            });
        } catch (e) {}
    });
</script>

<?php if($errors->any() && old('action') === 'settings'): ?>
    <script>
        $(function(){ $('#payrollSettingsModal').modal('show'); });
    </script>
<?php endif; ?>

<?php if($errors->any() && old('action') === 'salary'): ?>
    <script>
        $(function(){ $('#salaryModal').modal('show'); });
    </script>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/payroll/staff.blade.php ENDPATH**/ ?>