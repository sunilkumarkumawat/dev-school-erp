<?php
$getUser = Helper::getUser();
?>

<?php $__env->startSection('title', 'Gate Pass'); ?>
<?php $__env->startSection('page_title', 'GATE PASS'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
<section class="common-page">
 <div class="common-box m-2">
         <table class="common-table w-100">
                                    <thead class="bg-primary">
                                        <tr role="row">
                                            <th><?php echo e(__('messages.Sr.No.')); ?></th>
                                            <th><?php echo e(__('Student Name')); ?></th>
                                            <th><?php echo e(__('Father Name')); ?></th>
                                            <th><?php echo e(__('Father Mobile')); ?></th>
                                            <th><?php echo e(__('Reciver  Name')); ?></th>
                                            <th><?php echo e(__('Reciver Mobile')); ?></th>
                                            <th><?php echo e(__('Relation')); ?></th>
                                            <th><?php echo e(__('Date')); ?></th>

                                            <!--<th><?php echo e(__('messages.Action')); ?></th>-->
                                    </thead>
                                    <tbody>

                                        <?php if(!empty($data)): ?>
                                        <?php
                                        $i=1
                                        ?>
                                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($i++); ?></td>
                                            <td><?php echo e($item['student_name'] ?? ''); ?></td>
                                            <td><?php echo e($item['father_name'] ?? ''); ?></td>
                                            <td><?php echo e($item['father_mobile'] ?? ''); ?></td>
                                            <td><?php echo e($item['reciver_name'] ?? ''); ?></td>
                                            <td><?php echo e($item['reciver_mobile'] ?? ''); ?></td>
                                            <td><?php echo e($item['relation'] ?? ''); ?></td>
                                            <td><?php echo e(date('d-m-Y', strtotime($item['iessu_date'] ?? ''))); ?> <?php echo e(date('h:i A', strtotime($item['iessu_date'] ?? ''))); ?></td>

                                            <!--<td>
                                                <a href="<?php echo e(url('gate_pass_print')); ?>/<?php echo e($item->admissionNo); ?>" class="btn btn-success  btn-xs ml-3" title="Gate Pass Print" target="_blank"><i class="fa fa-print"></i></a>
                                                <a href="<?php echo e(url('gate_pass_edit')); ?>/<?php echo e($item->id); ?>" class="btn btn-primary  btn-xs ml-3" title="Edit Complaint"><i class="fa fa-edit"></i></a>
                                                <a href="javascript:;" data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-danger  btn-xs ml-3" title="Delete Book"><i class="fa fa-trash-o"></i></a>
                                            </td>-->
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
    </div>

  
<?php $__env->stopSection(); ?> 

<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/gate_pass.blade.php ENDPATH**/ ?>