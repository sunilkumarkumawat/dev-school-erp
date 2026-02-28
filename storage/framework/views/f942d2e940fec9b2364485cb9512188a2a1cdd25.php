<?php
   $getUser = Helper::getUser();
   $getgenders = Helper::getgender();
   $classType = Helper::classType();
   $getCountry = Helper::getCountry();
   $getState = Helper::getState();
   $getCity = Helper::getCity();
?>

 
<?php $__env->startSection('content'); ?>

<div class="content-wrapper">

   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-orange">

                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fa fa-user-circle-o"></i> &nbsp; View Profile</h3>
                    <div class="card-tools">
                      <a href="<?php echo e(url('dashboard')); ?>" class="btn btn-primary  btn-xs"><i class="fa fa-arrow-left"></i> <span class=""><?php echo e(__('Back')); ?> </span></a>
                    </div>
                </div> 
              <div class="card-body box-profile">
                  <form id="quickForm" action="<?php echo e(url('profile/edit')); ?>/<?php echo e(Session::get('id') ?? ''); ?>" method="post" enctype="multipart/form-data">
                     <?php echo csrf_field(); ?>
                  <div class="row mb-3">
                        <div class="col-md-12 text-center">
                                <img class="profile-user-img img-fluid rounded-circle" src="<?php echo e(env('IMAGE_SHOW_PATH').'/profile/'.$getUser['image']); ?>" style="width:100px; height:100px;" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'/default/user_image.jpg'); ?>'">
                        </div>
                    </div>
                <div class="container rounded bg-white">
                    <div class="row">
                            <div class="col-md-3">
    	    	                <div class="form-group">
    				                <label>Profile Photo</label>
    				               
    				                    <input type="file"  class="form-control <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="photo" name="photo" value="<?php echo e($data['photo'] ?? ""); ?>">
    		                        <?php $__errorArgs = ['photo'];
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
		                
                   
                    
		                 <div class="col-md-3">
            				<div class="form-group"> 
            					<label style="color:red;"> User Name* </label>
            					<input type="text" class="form-control <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  name="userName" id="userName"  value="<?php echo e($data['userName'] ?? ""); ?>" placeholder="User Name">
            			        <?php $__errorArgs = ['userName'];
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
            			
                        <div class="form-group col-md-3">
                            <label style="color:red;"> Name*</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="first_name" id="first_name" value="<?php echo e($data['first_name'] ?? ""); ?>" placeholder="First name">
                            <?php $__errorArgs = ['first_name'];
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
			            
			       
		         	
		
	            		<div class="col-md-3">
            				<div class="form-group"> 
            					<label style="color:red;">Date Of Birth*</label>
            					<input type="date"class="form-control <?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="dob" name="dob" value="<?php echo e($data['dob'] ?? ""); ?>" placeholder="Date Of Birth" >
            					<?php $__errorArgs = ['dob'];
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
            			
		               <div class="form-group col-md-3">
                            <label style="color:red;">Mobile*</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> " name="mobile" id="mobile" value="<?php echo e($data['mobile'] ?? ""); ?>" placeholder="Mobile" maxlength="10" onkeypress="javascript:return isNumber(event)" >
                                <?php $__errorArgs = ['mobile'];
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
			            
			            <div class="form-group col-md-3">
                            <label>Email:</label>
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> " name="email" id="email" value="<?php echo e($data['email'] ?? ""); ?>" placeholder="Email" >
                                <?php $__errorArgs = ['email'];
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
			            
			            
			            <div class="form-group col-md-3">
                            <label style="color:red;">Father name*</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['father_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> " name="father_name" id="father_name" value="<?php echo e($data['father_name'] ?? ""); ?>" placeholder="Father Name" >
                            <?php $__errorArgs = ['father_name'];
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
			            
			           
            		    
            			<div class="col-md-3" >
                            <div class="form-group">
                                <label>Country</label>
                                <select class="form-control select2" name="country_id" id="country_id"  >
                                    <?php if(!empty($getCountry)): ?> 
                                      <?php $__currentLoopData = $getCountry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                         <option value="<?php echo e($country->id ?? ''); ?>" <?php echo e(( $country['id'] == $data['country_id']) ? 'selected' : ''); ?>><?php echo e($country->name ?? ''); ?></option>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    
                              
                                	<?php $__errorArgs = ['country_id'];
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
                                </select>
                            </div>
                        </div>
                        
            			<div class="col-md-3">
            				<div class="form-group"> 
            					<label for="State" class="required">State</label>
            					<select class="form-control" id="state_id" name="state_id" >
                                    <?php if(!empty($getState)): ?> 
                                        <?php $__currentLoopData = $getState; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($state->id ?? ''); ?>" <?php echo e(( $state['id'] == $data['state_id']) ? 'selected' : ''); ?>><?php echo e($state->name ?? ''); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    
                                  	<?php $__errorArgs = ['state_id'];
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
                                </select>
            				</div>
            			</div>
            			
            			<div class="col-md-3">
            			    <div class="form-group">
            			        <label for="City">City</label>
            			        <select class="form-control" name="city_id" id="city_id"  >
            			            <?php if(!empty($getCity)): ?> 
                                  <?php $__currentLoopData = $getCity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cities): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <option value="<?php echo e($cities->id ?? ''); ?>" <?php echo e(( $cities['id'] == $data['city_id']) ? 'selected' : ''); ?>><?php echo e($cities->name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
            					
            					<?php $__errorArgs = ['city_id'];
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
            					</select>
            			    </div>
            			</div>
            			
            			<div class="col-md-3">
            				<div class="form-group"> 
            					<label>Address :</label>
            					<input type="text"class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="address" name="address" value="<?php echo e($data['address'] ?? ""); ?>" placeholder="Address" >
            					<?php $__errorArgs = ['address'];
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
            			
            			<div class="col-md-3">
            				<div class="form-group"> 
            					<label>Pin Code :</label>
            					<input type="text"class="form-control <?php $__errorArgs = ['pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="pincode" name="pincode" value="<?php echo e($data['pincode'] ?? ""); ?>" placeholder="Pin Code" >
            					<?php $__errorArgs = ['pincode'];
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
                </div>
                            
               
                    <div class="col-md-12 text-center">
    		           <button type="submit" class="btn btn-primary btn-sm">Update</button>
    		        </div>
    		       
            </form>
        </div>
     </div>
</div>
</div>
</div>
</section>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/profile/profile.blade.php ENDPATH**/ ?>