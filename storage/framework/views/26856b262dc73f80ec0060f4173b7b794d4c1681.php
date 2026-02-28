<?php
$getCountry = Helper::getCountry();

?>

<?php $__env->startSection('content'); ?>


	<?php
	$branchCount = DB::table('branch')->where('deleted_at',null)->count();

	?>
<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-code-fork"></i> &nbsp; <?php echo e(__('master.Add Branch')); ?></h3>
                            <div class="card-tools">
                                <a href="<?php echo e(url('viewBranch')); ?>" class="btn btn-primary  btn-sm" title="View Users"><i class="fa fa-eye"></i> <?php echo e(__('common.View')); ?></a>
                                <a href="<?php echo e(url('master_dashboard')); ?>" class="btn btn-primary  btn-sm" title="View Users"><i class="fa fa-arrow-left"></i> <?php echo e(__('common.Back')); ?> </a>
                            </div>
                        </div>  
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <form id="form-submit" action="<?php echo e(url('addBranch')); ?>" method="post" >
                                    <?php echo csrf_field(); ?>
                    		        <div class="row mb-2 m-2">
                    		        <div class="col-md-2">
                					    <label for="branch_code" class="text-danger"><?php echo e(__('master.Branch Code')); ?>* :</label>
                    					<input type="text" class="form-control" id="branch_code" name="branch_code" placeholder="<?php echo e(__('master.Branch Code')); ?>" value="<?php echo e(old('branch_code')); ?>">
                    				  
                    			    </div>
                    			    <div class="col-md-2">
                    					<label for="branch_name" class="text-danger"><?php echo e(__('master.Branch Name')); ?>* :</label>
                    					<input type="text" class="form-control <?php $__errorArgs = ['branch_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="branch_name" name="branch_name" placeholder="<?php echo e(__('master.Branch Name')); ?>" value="<?php echo e(old('branch_name')); ?>">
                    					
                        			</div>
                        			<div class="col-md-2">
                    					<label for="contact_person"><?php echo e(__('master.Director/Administrator')); ?> :</label>
                    					<input type="text" class="form-control" id="contact_person" name="contact_person" placeholder="<?php echo e(__('master.Contact Person')); ?>" value="<?php echo e(old('contact_person')); ?>">
                        			</div>
                        			<div class="col-md-2">
                        		   	    <lable class="text-danger"><?php echo e(__('master.Mobile Number')); ?>* :</lable>
                        		   	        <!--<div style="display : inline-flex;">-->
                            				  
                        		   		        <div class="input text">
                            		   		        <input name="mobile" id="mobile" class="form-control <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(__('master.Mobile Number')); ?>" maxlength="10" minlength="10" type="tel" value="<?php echo e(old('mobile')); ?>" onkeypress="javascript:return isNumber(event)">
                            		   		       
                            		   		    </div>		   		
                            		     	<!--</div>-->
                            		   	</div>
                            		   	<div class="col-md-2">
                            					<label for="email" class="text-danger"><?php echo e(__('common.Email')); ?>*   :</label>
                            					<input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" placeholder="<?php echo e(__('common.Email')); ?>" value="<?php echo e(old('email')); ?>">
                            				    
                            			</div>
                            			<div class="col-md-2">
                        					<label for="address"><?php echo e(__('common.Address')); ?> :</label>
                        					<input type="text" class="form-control" id="address" name="address" placeholder="<?php echo e(__('common.Address')); ?>" value="<?php echo e(old('address')); ?>" />
                    			        </div>
                    		            <div class="col-md-2" >
                                            <label> <?php echo e(__('common.Country')); ?> :</label>
                                            <select class="form-control select2" name="country_id" id="country_id" value="<?php echo e(old('country_id')); ?>">
                                                <option value=""><?php echo e(__('common.Select')); ?></option>
                                              <?php if(!empty($getCountry)): ?> 
                                                  <?php $__currentLoopData = $getCountry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                     <option value="<?php echo e($country->id ?? ''); ?>" ><?php echo e($country->name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                              <?php endif; ?>
                                            </select>
                                        </div>
                    			        <div class="col-md-2">
                        					<label for="State"><?php echo e(__('common.State')); ?> :</label>
                        					<select class="form-control select2" id="state_id" name="state_id" value="<?php echo e(old('state_id')); ?>" >
                                                <option value=""><?php echo e(__('common.Select')); ?></option>
                                                    
                                            </select>
                    			        </div>
                    			        <div class="col-md-2">
                        			        <label for="City"><?php echo e(__('common.City')); ?> :</label>
                        			        <select class="form-control select2" name="city_id" id="city_id" value="<?php echo e(old('city_id')); ?>">
                                                <option value=""><?php echo e(__('common.Select')); ?></option>
                                                
                            				</select>
                    			        </div>
                            			<div class="col-md-2">
                        					<label for="Pin Code"><?php echo e(__('common.Pin Code')); ?> :</label>
                        					<input type="text" class="form-control" id="pin_code" name="pin_code" placeholder="<?php echo e(__('common.Pin Code')); ?>" value="<?php echo e(old('pin_code')); ?>"maxlength="6" onkeypress="javascript:return isNumber(event)">
                            			</div>
                            			
                            		
                            		   	<div class="col-md-2">
                        					<label for="expert_name"><?php echo e(__('master.Expert Name')); ?> :</label>
                        					<input type="text" class="form-control" id="expert_name" name="expert_name" placeholder="<?php echo e(__('master.Expert Name')); ?>" value="<?php echo e(old('expert_name')); ?>">
                            			</div>
                            			
                        			  
                            			
                            			     
                                   
                            			
                        		    </div>
                                    <div class="col-md-12 text-center mt-3">
                            			<button type="submit" class="btn btn-primary mb-3 btn-submit"><?php echo e(__('common.Submit')); ?></button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('#login_background').change(function(e){
            $('#image_error').html("");
            var fileName = $(this).val();
        var extension = fileName.split(".").pop();
        if (
          extension.toLowerCase() === "png" ||
          extension.toLowerCase() === "jpg" ||
          extension.toLowerCase() === "jpeg"
        ) {
            if (e.target.files[0].size > Img_Size) {
                $('#image_error').html("please select Image Size under 2MB");
                $(this).val('');
            }else{
                $('#image_error').html("");
            }
        }else{
            $('#image_error').html("Image Size File");
            $(this).val('');
        }
        });
    });
    </script>
    
    <style>
    #image_error{
        font-weight: bold;
    font-size: 14px;
    }
        .blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes  blinker {
  50% {
    opacity: 0;
  }
}
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/branch/addBranch.blade.php ENDPATH**/ ?>