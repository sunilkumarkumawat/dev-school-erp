<?php
  $all_class = Helper::classType();
?>

 
<?php $__env->startSection('content'); ?>
         
         
         
          <div class="content-wrapper">
	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-edit"></i> &nbsp;<?php echo e(__('master.Edit Class')); ?> </h3>
                            <div class="card-tools">
                                        <a href="<?php echo e(url('add_class')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-eye"></i><?php echo e(__('common.View')); ?> </a>

                        </div>
                           </div>                      

    <form id="form-submit-edit" action="<?php echo e(url('edit_class')); ?>/<?php echo e($data['id']); ?>" method="post" >
        <?php echo csrf_field(); ?>
		<div class="row mb-2 m-2">
		     <div class="col-md-4">
				<div class="form-group">
				<label class="text-danger"><?php echo e(__('master.Class')); ?>* </label>
					<input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> " id="name" name="name" placeholder="<?php echo e(__('master.Class')); ?>" value="<?php echo e($data['name'] ?? ''); ?>">
				</div>
			</div>

		</div>
	
	
	
        <div class="col-md-12 text-center">
			<button type="submit" class="btn btn-primary btn-submit"><?php echo e(__('common.Update')); ?></button><br><br>
		</div>
    	</form>
    </div>
        </div>
    </div>

</div>    
</section>
</div>                                                           
    
        
        
        
        
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/class/edit.blade.php ENDPATH**/ ?>