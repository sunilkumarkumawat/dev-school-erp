<?php
  $classType = Helper::ClassType();
  $getsubject = Helper::getSubject();
?>

<?php $__env->startSection('content'); ?>

<div class="content-wrapper">

   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-orange">
                     <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="nav-icon fas fa fa-leanpub"></i> &nbsp;<?php echo e(__('Add Exam Term')); ?></h3>
                    <div class="card-tools">
                    <a href="<?php echo e(url('view/exam_term')); ?>" class="btn btn-primary  btn-sm <?php echo e(Helper::permissioncheck(8)->view ? '' : 'd-none'); ?>" title="View Users"><i class="fa fa-eye"></i> <?php echo e(__('common.View')); ?> </a>
                    <a href="<?php echo e(url('view/exam_term')); ?>" class="btn btn-primary  btn-sm" title="View Users"><i class="fa fa-arrow-left"></i> <?php echo e(__('common.Back')); ?> </a>
                    </div>
                    
                    </div>        
                <form id="quickForm" action="<?php echo e(url('add/exam_term')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row m-2">
                       <div class="col-md-3">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('Exam Term Name')); ?>*</label>
            				<input type="text" id="name" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(__('Exam Term Name')); ?>" value="<?php echo e(old('name')); ?>">
                             <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            					<span class="invalid-feedback" role="alert">
            						<strong><?php echo e($message); ?></strong>
            					</span>
            				<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            		    </div>
            		</div>
                    
        		
		        </div>

                <div class="row m-2 pb-2">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary "><?php echo e(__('common.Submit')); ?></button>
                    </div>
                </div>
                
            
            </form>
</div>
</div>
</div>
</div>
</section>
</div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/examination/offline_exam/exam_term/add.blade.php ENDPATH**/ ?>