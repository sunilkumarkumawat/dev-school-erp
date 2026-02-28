
<?php $__env->startSection('content'); ?>

<div class="content-wrapper">
<section class="content pt-3">
<div class="container-fluid">
<div class="row">
<div class="col-12">

<div class="card card-outline card-orange mb-0">

<div class="card-header bg-primary">
    <h3 class="card-title">
        <i class="fa fa-money"></i> Sibling Fee Management
    </h3>
</div>

<div class="card-body">

<div class="collect-wrapper">

<form method="post"
      action="<?php echo e(isset($preview) ? route('sibling.confirm') : route('sibling.preview')); ?>">
<?php echo csrf_field(); ?>

<input type="hidden" name="ledger_no" value="<?php echo e($ledgerNo); ?>">

<!-- TOTAL + PERCENTAGE INPUT -->

<div style="margin-bottom:20px;">
    <label>Total Amount</label>
    <input type="number"
           name="total_amount"
           id="totalAmountInput"
           value="<?php echo e($totalAmount ?? ''); ?>"
           class="form-control"
           <?php echo e(isset($preview) ? 'readonly' : ''); ?>>
</div>

<div style="margin-bottom:20px;">
    <label>Percentage</label>
    <input type="number"
           name="percentage"
           id="percentageInput"
           value="<?php echo e($percentage ?? 50); ?>"
           class="form-control"
           <?php echo e(isset($preview) ? 'readonly' : ''); ?>>
</div>

<hr>

<!-- STUDENT LOOP -->

<?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="student-block">

    <div class="student-title">
        <h4>
            <i class="fa fa-user"></i>
            <?php echo e($student->first_name); ?>

            (<?php echo e($student->class_name); ?>)
        </h4>
    </div>

    <div class="fee-row header-row">
        <div>Fee Head</div>
        <div>Due Amount</div>
        <div>Discount</div>
        <div>Fine</div>
    </div>

    <?php $__currentLoopData = $student->groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <div class="fee-row">

        <div>
            <input type="checkbox"
                   class="head-checkbox"
                   data-student="<?php echo e($student->id); ?>"
                   data-group="<?php echo e($group->fees_group_id); ?>"
                   disabled>
            <?php echo e($group->group_name); ?>

        </div>

        <div>
            <input type="number"
                   class="amount-input"
                   value="<?php echo e($group->remaining); ?>"
                   data-student="<?php echo e($student->id); ?>"
                   data-group="<?php echo e($group->fees_group_id); ?>"
                   readonly>
        </div>

        <div>
            <input type="number" value="0" readonly>
        </div>

        <div>
            <input type="number" value="0" readonly>
        </div>

    </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>

<hr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<div class="text-center">
    <button type="submit" class="btn btn-success btn-lg">
        <?php echo e(isset($preview) ? 'Confirm & Pay' : 'Preview Distribution'); ?>

    </button>
</div>

</form>

</div>
</div>
</div>

</div>
</div>
</div>
</section>
</div>


<!-- =========================
     AUTO ALLOCATION SCRIPT
========================= -->

<script>
document.addEventListener("DOMContentLoaded", function () {

    const totalInput = document.getElementById("totalAmountInput");
    const percentageInput = document.getElementById("percentageInput");

    if (!totalInput || !percentageInput) return;

    totalInput.addEventListener("input", distribute);
    percentageInput.addEventListener("input", distribute);

    function distribute() {

        let total = parseFloat(totalInput.value) || 0;
        let percent = parseFloat(percentageInput.value) || 0;

        if (total <= 0 || percent <= 0) return;

        let share = (total * percent) / 100;

        const studentGroups = {};

        document.querySelectorAll(".amount-input").forEach(input => {

            let sid = input.dataset.student;
            let gid = input.dataset.group;
            let due = parseFloat(input.value) || 0;

            if (!studentGroups[sid]) studentGroups[sid] = [];

            studentGroups[sid].push({
                checkbox: document.querySelector(
                    `.head-checkbox[data-student='${sid}'][data-group='${gid}']`
                ),
                due: due
            });
        });

        for (let sid in studentGroups) {

            let remaining = share;

            studentGroups[sid].forEach(group => {

                group.checkbox.checked = false;
                group.checkbox.disabled = true;

                if (remaining <= 0) return;

                let payNow = Math.min(remaining, group.due);

                if (payNow > 0) {
                    group.checkbox.checked = true;
                    group.checkbox.disabled = false;
                }

                remaining -= payNow;
            });
        }
    }

});
</script>
<style>
    /* =========================
   BASE WRAPPER
========================= */

.collect-wrapper {
    max-width: 1100px;
    margin: 0 auto;
    padding: 20px;
}

.collect-header {
        display: flex;
    /* flex-direction: column; */
    /* gap: 8px; */
    margin-bottom: 25px;
    justify-content: space-between;
}

.collect-header h2 {
    margin: 0;
    font-size: 28px;
}

.collect-header p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
}

.back-link {
    font-size: 14px;
    color: #2563eb;
    text-decoration: none;
    font-weight: bold;
}

/* =========================
   MAIN CARD
========================= */

.collect-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 25px;
    border: 1px solid #e2e8f0;
    margin-bottom: 30px;
}

/* =========================
   STUDENT BLOCK
========================= */

.student-block {
    margin-bottom: 30px;
    padding-left: 18px;
    border-left: 4px solid transparent;
}

.student-block.blue {
    border-color: #2563eb;
}

.student-block.pink {
    border-color: #ec4899;
}

.student-block.orange {
    border-color: #f97316;
}

.student-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.student-title h4 {
    margin: 0;
    font-weight:bold;
}

/* =========================
   FEE ROW GRID
========================= */

.fee-row {
    display: grid;
    grid-template-columns: 1fr 120px 120px 120px;
    gap: 15px;
    align-items: center;
    margin-bottom: 12px;
}

.header-row {
    font-weight: 600;
    color: #64748b;
    font-size: 13px;
}

/* =========================
   INPUTS
========================= */

input[type="number"],
input[type="text"],
input[type="date"],
select {
    width: 100%;
    padding: 8px 10px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
}

input:focus,
select:focus {
    outline: none;
    border-color: #2563eb;
}

/* =========================
   PAYMENT CONTROLS
========================= */

.payment-controls {
    margin-top: 20px;
}

.control-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-size: 13px;
    margin-bottom: 6px;
}

.form-group .required {
    color: red;
}

.full-width {
    grid-column: span 3;
}

/* =========================
   PAY BUTTON
========================= */

.pay-action {
    text-align: center;
}

.main-pay-btn {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: none;
    font-size: 16px;
    font-weight: 600;
    background: linear-gradient(to right, #2563eb, #1d4ed8);
    color: white;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(37,99,235,0.3);
}

.main-pay-btn:hover {
    opacity: 0.95;
}

.secure-text {
    margin-top: 10px;
    font-size: 12px;
    color: #64748b;
}

/* =========================
   RESPONSIVE
========================= */

/* Tablet */
@media (max-width: 992px) {

    .fee-row {
        grid-template-columns: 1fr 1fr;
    }

    .header-row {
        display: none;
    }

    .control-row {
        grid-template-columns: repeat(2, 1fr);
    }

    .full-width {
        grid-column: span 2;
    }

}

/* Mobile */
@media (max-width: 576px) {

    .collect-card {
        padding: 18px;
    }

    .fee-row {
        grid-template-columns: 1fr;
    }

    .control-row {
        grid-template-columns: 1fr;
    }

    .full-width {
        grid-column: span 1;
    }

    .collect-header {
        align-items: flex-start;
    }

}

</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/fees/fees_collect/sibling_pay.blade.php ENDPATH**/ ?>