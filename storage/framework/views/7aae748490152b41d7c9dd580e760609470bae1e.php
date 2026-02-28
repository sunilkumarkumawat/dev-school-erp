<?php
$getUser = Helper::getUser();
?>

<?php $__env->startSection('title', 'Study Material'); ?>
<?php $__env->startSection('page_title', 'STUDY  MATERIAL'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
<section class="common-page">
 <div class="common-box m-2">
       <table class="common-table w-100">
                                <thead>
                                    <tr role="row">
                                        <th>Sr. No.</th>
                                        <th>Content Title</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Action</th>

                                </thead>
                                <tbody>
                      
                                    <?php if(!empty($data)): ?>
                                    <?php
                                       $i=1
                                    ?>
                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($item->content_type =="Study Material"): ?>
                                    <tr>
                                        <td><?php echo e($i++); ?></td>
                                        <td><?php echo e($item['content_title']); ?></td>
                                        <td><?php echo e($item['content_type']); ?></td>
                                        <td><?php echo e($item['upload_date']); ?></td>
                                        <td>
                                            <a href="<?php echo e(url('download')); ?>/<?php echo e($item['id'] ?? ''); ?>" class="ml-2"><i class="fa fa-download text-success"></i></a>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
    </div>

  
<?php $__env->stopSection(); ?> 

<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/download_center/student_study_material.blade.php ENDPATH**/ ?>