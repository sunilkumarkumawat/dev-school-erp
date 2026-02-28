 
<?php $__env->startSection('content'); ?>
<?php
$question_count = Helper::getCount('questions','id','count');
$exam_count = Helper::getCount('exams','id','count');
?>
<div class="content-wrapper">

   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary">
                    <?php if(Session::get('role_id') == 3): ?>
                        <h3 class="card-title"><i class="fa fa-leanpub"></i> &nbsp;<?php echo e(__('examination.My Examination dgf')); ?> </h3>
                    <?php else: ?>
                        <h3 class="card-title"><i class="fa fa-leanpub"></i> &nbsp;<?php echo e(__('Online Examination Management')); ?></h3>
                    <?php endif; ?>
                    
                    <div class="card-tools">
                        <!--<a href="<?php echo e(url('add_user')); ?>" class="btn btn-primary  btn-sm" title="Add User"><i class="fa fa-plus"></i> Add User</a>-->
                    </div>
            
                </div>               
            </div>
            </div>
            
            <?php if(!empty(Helper::SidebarSubPerm(8))): ?>
                <?php $__currentLoopData = Helper::SidebarSubPerm(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub_sidebar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-3 col-6">
                    <a href="<?php echo e(url($sub_sidebar['url'] ?? '')); ?>" class="small-box-footer">
                        <div class="small-box bg-<?php echo e($sub_sidebar['bg_color'] ?? ''); ?>">
                            <div class="inner">
                                <h4 class="mobile_text_title"> <?php if(Session::get('locale') == 'hi'): ?><?php echo e($sub_sidebar['hindi_name'] ?? ''); ?> <?php else: ?> <?php echo e($sub_sidebar['name'] ?? ''); ?> <?php endif; ?> </h4>
                                <p><?php echo e(__('common.Enter')); ?></p>
                            </div>
                            <div class="icon">
                                <i class="fa <?php echo e($sub_sidebar['ican'] ?? ''); ?>"></i>
                            </div>
                            <div class="text-center small-box-footer"><?php echo e(__('common.More info')); ?><i class="fa fa-arrow-circle-right"></i></div>
                        </div>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
      </div>
    </div>
    </section>
    
    
<section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          
           
            
           
       
         
         
          <!--<div class="col-12 col-md-12">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary">
                    <?php if(Session::get('role_id') == 3): ?>
                    <?php else: ?>
                        <h3 class="card-title"><i class="fa fa-leanpub"></i> &nbsp;<?php echo e(__('Digital Examination Management')); ?></h3>
                    <?php endif; ?>
                    
                    <div class="card-tools">
                    </div>
            
                </div>               
            </div>
            </div>
            
                <div class="col-md-3 col-6">
                <a href="<?php echo e(url('digital/view/question')); ?>" class="small-box-footer">
                <div class="small-box bg-danger">
                    <div class="inner">
                    <h4 class="mobile_text_title"><?php echo e(__('Question Bank')); ?></h4>
                     <h4 class="mobile_text_title">&nbsp;</h4>
                    </div>
                <div class="icon">
                    <i class="fa fa-file-text"></i>
                </div>
                    <div class="text-center small-box-footer"><?php echo e(__('common.More info')); ?><i class="fa fa-arrow-circle-right"></i></div>
                </div></a>
            </div>
                <div class="col-md-3 col-6">
                <a href="<?php echo e(url('digital/view/exam')); ?>" class="small-box-footer">
                <div class="small-box bg-success">
                    <div class="inner">
                    <h4 class="mobile_text_title"><?php echo e(__('Exam')); ?></h4>
                     <h4 class="mobile_text_title">&nbsp;</h4>
                    </div>
                <div class="icon">
                    <i class="fa fa-file-text"></i>
                </div>
                    <div class="text-center small-box-footer"><?php echo e(__('common.More info')); ?><i class="fa fa-arrow-circle-right"></i></div>
                </div></a>
            </div>-->
      </div>
    </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
  

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/examination/examination_dashboard.blade.php ENDPATH**/ ?>