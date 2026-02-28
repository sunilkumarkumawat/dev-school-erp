<?php
$getUser = Helper::getUser();
?>

<?php $__env->startSection('title', 'Time Table'); ?>
<?php $__env->startSection('page_title', 'TIME TABLE'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
<section class="common-page">
 <div class="common-box m-2">
         <table id="" class="common-table">
                             
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <!--<th>Class Name</th>-->
                                        <th>Subject Name</th>
                                        <th>Teacher Name</th>
                                        <th>Time Periods</th>
                                    </tr>
                                </thead>
                              <tbody>
                                <?php if(!empty($data)): ?>
                                <?php
                                  $i = 1;
                                ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                    <td><?php echo e($i++); ?></td>
                                    <!--<td><?php echo e($item->className ?? ''); ?> <?php if($item->stream != ""): ?>[<?php echo e($item->stream ?? ''); ?>] <?php endif; ?></td>-->
                                    <td><?php echo e($item->subjectName ?? ''); ?> <?php if($item->sub_name != ""): ?><?php echo e($item->sub_name ?? ''); ?><?php endif; ?></td>
                                    <td style="text-transform: capitalize;"><?php echo e($item->first_name ?? ''); ?> <?php echo e($item->last_name ?? ''); ?></td>
                                    <td><?php echo e(date('h:i A', strtotime($item->from_time)) ?? ''); ?> <?php echo e("To"); ?> <?php echo e(date('h:i A', strtotime($item->to_time)) ?? ''); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>       
                              </tbody>
                              </table>
    </div>

  


<?php $__env->stopSection(); ?> 

<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/timetable.blade.php ENDPATH**/ ?>