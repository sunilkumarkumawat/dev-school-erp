<?php
$getSubject = Helper::getSubject();
$getBranch = Helper::getPermisnByBranch();
$classType = Helper::classType();
$periods = Helper::getTimePeriod();
$getAllTeachers = Helper::getAllTeachers();

?>




<?php $__env->startSection('content'); ?>

<div class="content-wrapper">

   <section class="content pt-3">
      <div class="container-fluid">
    <h3 class="mb-3">Time Table Management </h3>
<div class="row">
<div class="col-sm-12">
    <div class="card card-outline card-orange">

                <div class="card-body">
    <form action="<?php echo e(url('teacher_subject_add')); ?>" method="POST" >
        <?php echo csrf_field(); ?>
                <div class="table-responsive" style="max-height: 600px; overflow: auto;">
    <table id="scheduleTable" class="table table-bordered table-striped text-center align-middle">
        <thead class="bg-primary text-white">
            <tr>
                <th style="min-width:120px; position: sticky; left:0; background:#002c54 ; z-index:10;">Period</th>
                <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th style="min-width:160px; position: sticky; top:0; background:#002c54 ; z-index:9;">
                        <?php echo e($class->name); ?>

                    </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                   
                    <td style="position: sticky; left:0; background:#f8f9fa; z-index:8;">
                        <b><?php echo e($period->period_name ?? ''); ?></b><br>
                        <div class="small">
                                  <?php echo e(\Carbon\Carbon::parse($period->from_time)->format('h:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($period->to_time)->format('h:i A')); ?>

                                            </div>
                    </td>
                     <?php if($period->period_name == "🍴 Lunch Break"): ?> 
        
                                        <td colspan="<?php echo e(count($classType)+1); ?>" class="text-center fw-bold bg-warning">
                                            🍴 LUNCH BREAK
                                        </td>
                    <?php else: ?>  
                    <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $saved = $data
                                ->where('class_type_id', $class->id)
                                ->where('time_period_id', $period->id)
                                ->first();
                        ?>
                        
                    
                        <td>
                            <div class="d-flex gap-1">
                                
                                <select 
                                    name="schedule[<?php echo e($class->id); ?>][<?php echo e($period->id); ?>][teacher_id]" 
                                    class="form-select form-select-sm">
                                    <option value="">-- Teacher --</option>
                                    <?php $__currentLoopData = $getAllTeachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($teacher->id); ?>" 
                                            <?php if($saved && $saved->user_id == $teacher->id): ?> selected <?php endif; ?>>
                                            <?php echo e($teacher->first_name ?? ''); ?> <?php echo e($teacher->last_name ?? ''); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                                
                                <select 
                                    name="schedule[<?php echo e($class->id); ?>][<?php echo e($period->id); ?>][subject_id]" 
                                    class="form-select form-select-sm">
                                    <option value="">-- Subject --</option>
                                    <?php $__currentLoopData = $getSubject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                         <?php if($subject->class_type_id == $class->id): ?>
                                        <option value="<?php echo e($subject->id); ?>" 
                                            <?php if($saved && $saved->subject_id == $subject->id): ?> selected <?php endif; ?>>
                                            <?php echo e($subject->name); ?>

                                        </option>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </td>
                    
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>


         <div class="col-sm-12 text-center mt-2" >  
             <button type="submit" class="btn btn-primary" >Save Time Table</button> 
         </div>
    </form>
    </div>
     </div>
     </div>
            <div class="col-md-12">
    <div class="container-fluid">

        <div class="text-end mb-3 no-print">
            <button class="btn btn-primary" onclick="printDiv()">🖨️ Print</button>
        </div>

        <div id="printBox" class="print-box">
            <h3 class="text-center fw-bold mb-4">
                <?php echo e($getBranch->branch_name ?? ''); ?>

            </h3>

            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:120px;">Period</th>
                            <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th><?php echo e($class->name); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <?php
                              
                                ?>
                                <td class="fw-bold"><?php echo e($period->period_name ?? ''); ?> <br>
                                 <div class="small">
                                  <?php echo e(\Carbon\Carbon::parse($period->from_time)->format('h:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($period->to_time)->format('h:i A')); ?>

                                            </div>
                              
                    </td>
                            <?php if($period->period_name == "🍴 Lunch Break"): ?> 
        
                                        <td colspan="<?php echo e(count($classType)+1); ?>" class="text-center fw-bold bg-warning">
                                            🍴 LUNCH BREAK
                                        </td>
                                <?php else: ?>    
    
                                <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $record = $data->where('class_type_id', $class->id)
                                                       ->where('time_period_id', $period->id)
                                                       ->first();
                                    ?>
                                    <td>
                                        <?php if($record): ?>
                                            <div class="fw-bold text-uppercase">
                                                <?php echo e($record->first_name ?? ''); ?> <?php echo e($record->last_name ?? ''); ?>

                                            </div>
                                            <div class="small">
                                                <?php echo e($record->subject_name ?? ''); ?>

                                            </div>
                                        <?php else: ?>
                                            --
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Print Styling -->
<style>
.print-box {
    border: 2px solid black;
    padding: 15px;
    background: #fff;
}
@media  print {
     @page  {
    size: A4 landscape !important;
    margin: 10mm;
  }
    body {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .no-print { display: none !important; }
    .print-box {
        border: 2px solid black;
        padding: 20px;
        margin: auto;
        width: 95%;
        page-break-inside: avoid;
    }
    table {
       
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid #080809 !important;
        padding: 4px;
    }
    h3 {
        font-size: 16px;
        margin-bottom: 15px;
    }
    
}

.table-bordered td, .table-bordered th {
  border: 1px solid #080809;
}
</style>

<!-- JS for print -->
<script>
function printDiv() {
    var printContents = document.getElementById("printBox").innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
  //  location.reload(); // reload back to normal after print
}
</script>

    </div>
</div>
</section>
</div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/TeacherSubject/add.blade.php ENDPATH**/ ?>