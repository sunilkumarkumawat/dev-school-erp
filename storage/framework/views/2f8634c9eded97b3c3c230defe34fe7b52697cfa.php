<?php
$getUser = Helper::getUser();
?>

<?php $__env->startSection('title', 'Subject'); ?>
<?php $__env->startSection('page_title', 'SUBJEST'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
<section class="common-page">
 <div class="common-box m-2">
         <table  class="common-table w-100">
                                    <thead>
                                        <tr role="row">
                                            <th><?php echo e(__('messages.Sr.No.')); ?></th>
                                            <th><?php echo e(__('messages.Subject')); ?></th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if(!empty($data)): ?> 
                                        <?php $i=1 
                                        ?> 
                                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($i++); ?></td>
                                            <td><?php echo e($item['name'] ?? ''); ?></td>
                                            <td><?php if($item->other_subject == 0): ?> Main <?php else: ?> Other <?php endif; ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
                                    </tbody>
                                </table>
    </div>

  
<?php $__env->stopSection(); ?> 

<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/subject.blade.php ENDPATH**/ ?>