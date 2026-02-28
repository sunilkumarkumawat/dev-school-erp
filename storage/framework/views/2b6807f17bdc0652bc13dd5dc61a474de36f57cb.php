<?php

?>
 
<?php $__env->startSection('content'); ?>

<div class="content-wrapper">
	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
            <h3 class="card-title"><i class="fa fa-edit"></i> &nbsp;<?php echo e(__('fees.Edit Fees Group')); ?> </h3>
            <div class="card-tools">
      
            <a href="<?php echo e(url('feesGroup')); ?>" class="btn btn-primary  btn-sm" ><i class="fa fa-eye"></i> <?php echo e(__('messages.View')); ?> </a>
            <a href="<?php echo e(url('fee_dashboard')); ?>" class="btn btn-primary  btn-sm" ><i class="fa fa-arrow-left"></i> <?php echo e(__('Back')); ?> </a>
            </div>
            
            </div>                 
                <form id="quickForm" action="<?php echo e(url('feesGroupEdit')); ?>/<?php echo e($data['id']); ?>" method="post">
                <?php echo csrf_field(); ?>
                	<div class="row mb-2 m-2">
		    <div class="col-md-4">
				<div class="form-group">
                			<label style="color:red;"<?php echo e(__('fees.Add Fees Group')); ?>><?php echo e(__('messages.Name')); ?>*</label>
                			<input class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" name="name" id="name" placeholder="Name"value="<?php echo e($data['name'] ?? ''); ?>">
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
                        	
                    <div class="col-md-4" style="align-content: flex-end;">
                    <div class="form-group">
                    <label for="refund_fees">Refund Fees :</label>
                    <!-- Checkbox for Refund Fees -->
                    <input type="checkbox"    id="refund_fees_value" value="yes"  <?php if(old('fees_refund', $data->fees_refund) === 'yes'): ?> checked <?php endif; ?>  onchange="updateRefundFees(this)" >
                       </div>                    	
                        </div>
                        <input type="hidden" id="fees_refund" name="fees_refund" value="<?php echo e($data['fees_refund'] ?? ''); ?>">
                        </div>
 
                
                          <div class="col-md-12 text-center">
			<button type="submit" class="btn btn-primary"><?php echo e(__('messages.Update')); ?></button><br><br>
		</div>
    	</form>
             </div>
        </div>
    </div>
    </section>
</div>
<script>
    function updateRefundFees(checkbox) {
        if (checkbox.checked) {
            document.getElementById('fees_refund').value = 'yes';
        } else {
            document.getElementById('fees_refund').value = 'no';
        }
    }
</script>
<?php $__env->stopSection(); ?>      
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/fees/fees/feesGroupEdit.blade.php ENDPATH**/ ?>