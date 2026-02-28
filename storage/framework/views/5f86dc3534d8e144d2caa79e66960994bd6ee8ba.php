<?php
$getUser = Helper::getUser();
?>



<?php $__env->startSection('title', 'Fees'); ?>
<?php $__env->startSection('page_title', 'FEES'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>

<?php $__env->startSection('content'); ?>
<section class="fees-page">

  <!-- 💰 Fees Summary Boxes -->
  <div class="fees-summary">
    <div class="fee-box orange">
      <h6>Previous Session Due Fees</h6>
      <p>Rs. <strong><?php echo e($summary['previousSessionDue'] ?? 0); ?></strong></p>
    </div>
    <div class="fee-box yellow">
      <h6>Current Session Due Fees</h6>
      <p>Rs. <strong><?php echo e($summary['currentSessionDue'] ?? 0); ?></strong></p>
    </div>
    <div class="fee-box red">
      <h6>Late Fees</h6>
      <p>Rs. <strong><?php echo e($summary['lateFees'] ?? 0); ?></strong></p>
    </div>
    <div class="fee-box gold">
      <h6>Total Fees</h6>
      <p>Rs. <strong><?php echo e($summary['totalFees'] ?? 0); ?></strong></p>
    </div>
  </div>

<!-- ================= Improved Fees Cards ================= -->
<div class="fees-title mb-3">FEES PAYMENT HISTORY</div>

<div class="row g-3">
<?php if(!empty($getFees)): ?>
    <?php
        $grand_total = 0;
        $Paids = 0;
        $Discount  = 0;
        $Fine  = 0;
        $balances = 0;
    ?>

    <?php $__currentLoopData = $getFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $pad = \App\Models\FeesDetail::where('fees_type',0)
                ->where('status',0)
                ->where('admission_id',Session::get('id'))
                ->where('fees_group_id',$item->fees_group_id)
                ->sum('total_amount');

            $discount = \App\Models\FeesDetail::where('fees_type',0)
                ->where('admission_id',Session::get('id'))
                ->where('fees_group_id',$item->fees_group_id)
                ->sum('discount');

            $balance  = max(0, ($item->fees_group_amount ?? 0) - ($pad ?? 0));

            // fine calculation
            $fine_amt = 0;
            if(!empty($item->installment_due_date) && $balance > 0){
                if(strtotime($item->installment_due_date) < strtotime(date('Y-m-d'))){
                    $fine_amt = round(($balance * ($item->installment_fine ?? 0)) / 100, 2);
                }
            }

            $paid_percent = 0;
            if(($item->fees_group_amount ?? 0) > 0){
                $paid_percent = round((($pad ?? 0) / $item->fees_group_amount) * 100, 1);
                if($paid_percent > 100) $paid_percent = 100;
            }

            // accent color based on status
            $accent = $balance > 0 ? 'accent-unpaid' : 'accent-paid';
        ?>

        <div class="col-12  col-lg-12">
            <div class="card fee-card h-100">
                <div class="d-flex">
                    <div class="accent <?php echo e($accent); ?>"></div>
                    <div class="card-body p-3 w-100">

                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h5 class="mb-0 fw-bold"><?php echo e($item->group_name ?? 'Fees'); ?></h5>
                            <small>#<?php echo e($item->fees_group_id); ?></small>
                        </div>

                        <div class="d-flex gap-2 align-items-center mb-2">
                            <div class="me-2 small">
                                <?php if(!empty($item->installment_due_date)): ?>
                                    <i class="bi bi-calendar-check"></i>
                                    <?php echo e(date('d-M-Y', strtotime($item->installment_due_date))); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </div>

                            <?php if($balance > 0 && !empty($item->installment_due_date) && strtotime($item->installment_due_date) < strtotime(date('Y-m-d'))): ?>
                                <span class="badge bg-danger">Overdue</span>
                            <?php elseif($balance == 0): ?>
                                <span class="badge bg-success">Paid</span>
                            <?php else: ?>
                                <span class="badge bg-info text-dark">Partially Paid</span>
                            <?php endif; ?>
                        </div>

                        <div class="progress mb-2" style="height:8px;border-radius:6px;overflow:hidden;">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo e($paid_percent); ?>%;" aria-valuenow="<?php echo e($paid_percent); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <div class="row small  mb-2">
                            <div class="col-6">
                                <div><strong>Amount</strong></div>
                                <div>Rs. <?php echo e(number_format($item->fees_group_amount ?? 0, 2)); ?></div>
                            </div>
                            <div class="col-6">
                                <div><strong>Paid</strong></div>
                                <div>Rs. <?php echo e(number_format($pad ?? 0, 2)); ?></div>
                            </div>
                        </div>

                        <div class="row small mb-2">
                            <div class="col-6">
                                <div><strong>Discount</strong></div>
                                <div>Rs. <?php echo e(number_format($item->discount ?? $discount ?? 0, 2)); ?></div>
                            </div>
                            <div class="col-6 ">
                                <div><strong>Fine</strong></div>
                                <div>Rs. <?php echo e(number_format($fine_amt ?? 0, 2)); ?></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                <div class="small ">Balance</div>
                                <div class="fw-semibold fs-6">Rs. <?php echo e(number_format($balance ?? 0, 2)); ?></div>
                            </div>

                            <div class="text-end">
                                <!-- PAY NOW REMOVED COMPLETELY -->
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php
            $grand_total += ($item->fees_group_amount ?? 0);
            $Paids += ($pad ?? 0);
            $Discount += ($item->discount ?? $discount ?? 0);
            $Fine += ($fine_amt ?? 0);
            $balances += ($balance ?? 0);
        ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Summary -->
    <div class="col-12">
        <div class="card summary-card fees-summary-card">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div><strong>Grand Total:</strong> Rs. <?php echo e(number_format($grand_total, 2)); ?></div>
                <div><strong>Paid:</strong> Rs. <?php echo e(number_format($Paids, 2)); ?></div>
                <div><strong>Discount:</strong> Rs. <?php echo e(number_format($Discount, 2)); ?></div>
                <div><strong>Fine:</strong> Rs. <?php echo e(number_format($Fine, 2)); ?></div>
                <div class="fw-bold"><strong>Balance:</strong> Rs. <?php echo e(number_format($balances, 2)); ?></div>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="col-12 text-center py-4">
        <b>!! NO DATA FOUND !!</b>
    </div>
<?php endif; ?>
</div>


</section>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/fees_history.blade.php ENDPATH**/ ?>