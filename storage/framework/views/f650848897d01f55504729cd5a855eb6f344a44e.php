
<?php $__env->startSection('content'); ?>

<?php echo $__env->make('payroll.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
    $monthLabel = date('F', mktime(0, 0, 0, $month, 1));
    $deductionsCollection = $deductions ?? collect();
    $deductionCount = $deductionsCollection->count();
    $appliedCount = $deductionsCollection->where('is_applied', 1)->count();
    $skippedCount = $deductionsCollection->where('is_applied', 0)->count();
    $totalAmount = $deductionsCollection->sum('amount');
    $appliedAmount = $deductionsCollection->where('is_applied', 1)->sum('amount');
?>

<div class="content-wrapper payroll-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="payroll-hero">
                <div class="payroll-hero-inner">
                    <div>
                        <div class="payroll-hero-kicker">Payroll Center</div>
                        <div class="payroll-hero-title">Staff Deductions</div>
                        <div class="payroll-hero-subtitle">Manual deductions management - <?php echo e($monthLabel); ?> <?php echo e($year); ?></div>
                        <div class="payroll-hero-chips">
                            <span class="payroll-chip"><i class="fa fa-calendar"></i> <?php echo e($monthLabel); ?> <?php echo e($year); ?></span>
                            <span class="payroll-chip"><i class="fa fa-check-circle"></i> Applied: <?php echo e($appliedCount); ?></span>
                            <span class="payroll-chip"><i class="fa fa-ban"></i> Skipped: <?php echo e($skippedCount); ?></span>
                        </div>
                    </div>
                    <div class="payroll-hero-actions">
                        <button class="btn btn-sm btn-payroll btn-payroll-light" data-toggle="modal" data-target="#addDeductionModal">
                            <i class="fa fa-plus"></i> Add Deduction
                        </button>
                        <a href="<?php echo e(url('payroll/staff?month='.$month.'&year='.$year)); ?>" class="btn btn-sm btn-payroll btn-payroll-light">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="payroll-stats">
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Total Deductions</div>
                    <div class="payroll-stat-value"><?php echo e($deductionCount); ?></div>
                    <div class="payroll-stat-sub">For this period</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Applied Amount</div>
                    <div class="payroll-stat-value"><?php echo e(number_format((float)$appliedAmount, 2)); ?></div>
                    <div class="payroll-stat-sub">Active deductions</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Skipped Entries</div>
                    <div class="payroll-stat-value"><?php echo e($skippedCount); ?></div>
                    <div class="payroll-stat-sub">Not applied</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Total Amount</div>
                    <div class="payroll-stat-value"><?php echo e(number_format((float)$totalAmount, 2)); ?></div>
                    <div class="payroll-stat-sub">Manual deductions</div>
                </div>
            </div>

            <div class="card card-outline card-orange payroll-card">
                <div class="card-header payroll-card-header">
                    <div>
                        <div class="payroll-card-title"><i class="fa fa-minus-circle"></i> Deductions Register</div>
                        <div class="payroll-inline-note">Applied, skipped, or waived deductions for the period.</div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center" style="gap:8px;">
                        <span class="badge-soft">Period: <?php echo e($monthLabel); ?> <?php echo e($year); ?></span>
                        <span class="badge-soft">Total: <?php echo e(number_format((float)$totalAmount, 2)); ?></span>
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

                    <div class="d-flex flex-wrap align-items-center mb-2" style="gap:8px;">
                        <span class="payroll-pill"><i class="fa fa-calendar"></i> Month: <?php echo e($monthLabel); ?> <?php echo e($year); ?></span>
                        <span class="payroll-pill"><i class="fa fa-info-circle"></i> Status: Applied, Skipped, Waived</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered payroll-table">
                            <thead>
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th>Staff</th>
                                    <th>Title</th>
                                    <th>Remark</th>
                                    <th style="width:150px;" class="text-right">Amount</th>
                                    <th style="width:140px;">Status</th>
                                    <th style="width:220px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sr = 1; ?>
                                <?php $__empty_1 = true; $__currentLoopData = $deductions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $uid = (string) ($d->unique_id ?? '');
                                        $userId = (stripos($uid, 'USR-') === 0) ? (int) str_replace('USR-', '', $uid) : null;
                                        $user = $userId ? ($usersById[$userId] ?? null) : null;
                                        $name = $user ? trim(($user->first_name ?? '').' '.($user->last_name ?? '')) : $uid;
                                    ?>
                                    <tr>
                                        <td><?php echo e($sr++); ?></td>
                                        <td><?php echo e($name); ?> (<?php echo e($uid); ?>)</td>
                                        <td><?php echo e($d->title ?? '-'); ?></td>
                                        <td><?php echo e($d->remark ?? '-'); ?></td>
                                        <td class="text-right"><?php echo e(number_format((float)$d->amount, 2)); ?></td>
                                        <td>
                                            <?php if((int)($d->is_applied ?? 1) === 1): ?>
                                                <span class="badge badge-payroll badge-payroll-success">Applied</span>
                                            <?php else: ?>
                                                <span class="badge badge-payroll badge-payroll-warning">Skipped</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-payroll btn-payroll-outline" data-toggle="modal" data-target="#editDeductionModal<?php echo e($d->id); ?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <form method="post" action="<?php echo e(url('payroll/staff/deductions?month='.$month.'&year='.$year)); ?>" class="d-inline" onsubmit="return confirm('Delete this deduction?');">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="action" value="delete_deduction">
                                                <input type="hidden" name="deduction_id" value="<?php echo e($d->id); ?>">
                                                <button class="btn btn-sm btn-payroll btn-payroll-danger"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No manual deductions for this month.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Deduction Modal -->
<div class="modal fade payroll-modal" id="addDeductionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-plus-circle"></i> Add Manual Deduction</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" action="<?php echo e(url('payroll/staff/deductions?month='.$month.'&year='.$year)); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="action" value="add_deduction">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Staff</label>
                        <select name="unique_id" class="form-control" required>
                            <option value="">Select Staff</option>
                            <?php $__currentLoopData = $staffList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="USR-<?php echo e($s->id); ?>"><?php echo e(trim(($s->first_name ?? '').' '.($s->last_name ?? ''))); ?> (USR-<?php echo e($s->id); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" step="0.01" min="0" name="amount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Penalty">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Remark</label>
                                <input type="text" name="remark" class="form-control" placeholder="Optional note">
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">This deduction will apply for <?php echo e($monthLabel); ?> <?php echo e($year); ?>.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-payroll btn-payroll-outline" data-dismiss="modal">Close</button>
                    <button class="btn btn-payroll btn-payroll-primary"><i class="fa fa-save"></i> Save Deduction</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__currentLoopData = $deductions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade payroll-modal" id="editDeductionModal<?php echo e($d->id); ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-edit"></i> Edit Deduction</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="<?php echo e(url('payroll/staff/deductions?month='.$month.'&year='.$year)); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="update_deduction">
                    <input type="hidden" name="deduction_id" value="<?php echo e($d->id); ?>">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Amount</label>
                            <input type="number" step="0.01" min="0" name="amount" class="form-control" value="<?php echo e($d->amount); ?>" required>
                        </div>
                    </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo e($d->title); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Remark</label>
                                    <input type="text" name="remark" class="form-control" value="<?php echo e($d->remark); ?>">
                                </div>
                            </div>
                        </div>
                        <!-- Waive-off removed -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-payroll btn-payroll-outline" data-dismiss="modal">Close</button>
                        <button class="btn btn-payroll btn-payroll-primary"><i class="fa fa-save"></i> Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/payroll/staff_deductions.blade.php ENDPATH**/ ?>