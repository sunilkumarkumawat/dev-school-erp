<?php
    $getCountry = Helper::getCountry();
    $getState = Helper::getState();
    $getCity = Helper::getCity();
    $getaccounts = Helper::getaccount();
    $getSession=Helper::getSession();

?>

 
<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
   
   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-orange">

                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fa fa-cogs"></i> &nbsp;<?php echo e(__('setting.Edit Setting')); ?> </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(url('viewSetting')); ?>" class="btn btn-primary  btn-sm" title="Back"><i class="fa fa-arrow-left"></i> <?php echo e(__('common.Back')); ?> </a>
                    </div>
                </div> 

        <div class="card-body">
         <form id="form-submit-edit" action="<?php echo e(url('editSetting')); ?>/<?php echo e($data->id); ?>" method="post"  enctype="multipart/form-data">   
         <?php echo csrf_field(); ?>            
            <div class="row">
                 <?php if(Session::get('role_id') == 1): ?>
                <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="branch" style="color:red;"><?php echo e(__('setting.Branch')); ?>*</label>
				    	 <select class="form-control <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="branch_id" name="branch_id" 
				    	 <?php echo e($data->branch_id == null ? '' :'disabled'); ?>

				    	 >
				    	     <option value="">Select</option>
				    	     <?php if(!empty($branch)): ?> 
                                      <?php $__currentLoopData = $branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                         <option value="<?php echo e($Branch->id ?? ''); ?>"<?php echo e(( $Branch['id'] == $data['branch_id']) ? 'selected' : ''); ?>><?php echo e($Branch->branch_name ?? ''); ?></option>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  <?php endif; ?>
					       </select>	
			       
				    </div>
			    </div>
			    <?php endif; ?>
			    <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="Username" style="color:red;"><?php echo e(__('common.Name')); ?>*</label>
				    	<input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)" id="name" name="name" placeholder="<?php echo e(__('common.Name')); ?>" value="<?php echo e($data->name); ?>">
				
				    </div>
			    </div>                
                
			    <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="Username" style="color:red;"><?php echo e(__('common.Mobile No.')); ?>*</label>
				    	<input type="numbre" class="form-control <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="mobile" name="mobile" placeholder="<?php echo e(__('common.Mobile No.')); ?>" value="<?php echo e($data->mobile); ?>" maxlength="10" onkeypress="javascript:return isNumber(event)">
				
				    </div>
			    </div>                
                
			    <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="Username" style="color:red;"><?php echo e(__('common.E-Mail')); ?>*</label>
				    	<input type="text" class="form-control <?php $__errorArgs = ['gmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="gmail" name="gmail" placeholder="<?php echo e(__('common.E-Mail')); ?>" value="<?php echo e($data->gmail); ?>">
				
				    </div>
			    </div>  
			 <div class="col-md-3" >
                <div class="form-group">
                 <label><?php echo e(__('common.Country')); ?></label>
                  <select class="select2 form-control select2" name="country_id" id="country_id" >
                  	<option value=""><?php echo e(__('common.Select')); ?></option>
                      <?php if(!empty($getCountry)): ?> 
                          <?php $__currentLoopData = $getCountry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <option value="<?php echo e($country->id ?? ''); ?>" <?php echo e(( $country['id'] == $data['country_id']) ? 'selected' : ''); ?>><?php echo e($country->name ?? ''); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    
              </select>
              </div>
            </div>
			<div class="col-md-3">
				<div class="form-group"> 
					<label for="State" class="required"><?php echo e(__('common.State')); ?></label>
					<select class="select2 form-control" id="state_id" name="state_id">
						<option value=""><?php echo e(__('common.Select')); ?></option>
                <?php if(!empty($getState)): ?> 
                      <?php $__currentLoopData = $getState; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                         <option value="<?php echo e($state->id ?? ''); ?>" <?php echo e(( $state['id'] == $data['state_id']) ? 'selected' : ''); ?>><?php echo e($state->name ?? ''); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                  
                    </select>
				
				</div>
			</div>
			<div class="col-md-3">
			    <div class="form-group">
			        <label for="City"><?php echo e(__('common.City')); ?></label>
			        <select class="select2 form-control" name="city_id" id="city_id" >
			        	<option value=""><?php echo e(__('common.Select')); ?></option>
			            <?php if(!empty($getcitys)): ?> 
                      <?php $__currentLoopData = $getcitys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cities): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                         <option value="<?php echo e($cities->id ?? ''); ?>" <?php echo e(( $cities['id'] == $data['city_id']) ? 'selected' : ''); ?>><?php echo e($cities->name ?? ''); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php endif; ?>
				
					</select>
			    </div>
			</div>	    
                
			    <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="Username" style="color:red;"><?php echo e(__('common.Pin Code')); ?>*</label>
				    	<input type="numbre" class="form-control <?php $__errorArgs = ['pincode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onkeypress="javascript:return isNumber(event)" maxlength="6" id="pincode" name="pincode" placeholder="<?php echo e(__('common.Pin Code')); ?>" value="<?php echo e($data->pincode); ?>">
				
				    </div>
			    </div>   
			    
			     <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="Username" style="color:red;"><?php echo e(__('common.Address')); ?>*</label>
				    	<input type="text" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="address" name="address" placeholder="<?php echo e(__('common.Address')); ?>" value="<?php echo e($data->address); ?>">
				
				    </div>
			    </div>			    
               
                <div class="col-md-2">
				    <div class="form-group"> 
					    <label for="watermark_image" class="required"><?php echo e(__('Water Mark Image')); ?></label>
				    	 <input type="file" class="form-control  <?php $__errorArgs = ['watermark_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="watermark_image" name="watermark_image" value="<?php echo e($data->watermark_image); ?>" accept="image/png, image/jpg, image/jpeg">
										 <p class="text-danger" id="image_erro"></p>
					    
				    </div>
			    </div>
			    <div class="col-md-1 mt-4">
                    <td><img src="<?php echo e(env('IMAGE_SHOW_PATH').'/setting/watermark_image/'.$data['watermark_image']); ?>" width="40px" height="40px" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'/default/no_image.png'); ?>'"></td>
                </div>
              
               <!-- <div class="col-md-2">
				    <div class="form-group"> 
					    <label for="right_logo" class="required"><?php echo e(__('setting.Right Logo')); ?></label>
				    	 <input type="file" class="form-control  <?php $__errorArgs = ['right_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="right_logo" name="right_logo" value="<?php echo e($data->right_logo); ?>" accept="image/png, image/jpg, image/jpeg">
										 <p class="text-danger" id="image_erro"></p>
					    	<?php $__errorArgs = ['right_logo'];
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
			     <div class="col-md-1 mt-4">
                    <td><img src="<?php echo e(env('IMAGE_SHOW_PATH').'/setting/right_logo/'.$data['right_logo']); ?>" width="40px" height="40px" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/no_image.png'); ?>'"></td>
                </div>-->
                <div class="col-md-2">
				    <div class="form-group"> 
					    <label for="left_logo" class="required"><?php echo e(__('setting.Left Logo')); ?></label>
				    	<input type="file" class="form-control  <?php $__errorArgs = ['left_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="left_logo" name="left_logo" value="<?php echo e($data->left_logo); ?>" accept="image/png, image/jpg, image/jpeg">
										 <p class="text-danger" id="image_errors"></p>
					
				    </div>
			    </div>
			     <div class="col-md-1 mt-4">
                    <td><img src="<?php echo e(env('IMAGE_SHOW_PATH').'/setting/left_logo/'.$data['left_logo']); ?>" width="40px" height="40px" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/no_image.png'); ?>'"></td>
                </div>
                <div class="col-md-2">
				    <div class="form-group"> 
					    <label for="seal_sign" class="required"><?php echo e(__('setting.Seal & Sign.')); ?></label>
				    	<input type="file" class="form-control  <?php $__errorArgs = ['seal_sign'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="seal_sign" name="seal_sign" value="<?php echo e($data->seal_sign); ?>" accept="image/png, image/jpg, image/jpeg">
										 <p class="text-danger" id="image_er"></p>
					
				    </div>
			    </div>
			     <div class="col-md-1 mt-4">
                    <td><img src="<?php echo e(env('IMAGE_SHOW_PATH').'/setting/seal_sign/'.$data['seal_sign']); ?>" width="40px" height="40px" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/no_image.png'); ?>'"></td>
                </div>
      <!--          <div class="col-md-2">-->
				  <!--  <div class="form-group"> -->
					 <!--   <label for="principal_sign" class="required"><?php echo e(__('Principal & Sign.')); ?></label>-->
				  <!--  	<input type="file" class="form-control  <?php $__errorArgs = ['principal_sign'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="principal_sign" name="principal_sign" value="<?php echo e($data->principal_sign); ?>" accept="image/png, image/jpg, image/jpeg">-->
						<!--				 <p class="text-danger" id="image_er"></p>-->
						<!--<?php $__errorArgs = ['principal_sign'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>-->
						<!--<span class="invalid-feedback" role="alert">-->
						<!--	<strong><?php echo e($message); ?></strong>-->
						<!--</span>-->
				  <!--  	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>-->
				  <!--  </div>-->
			   <!-- </div>-->
			   <!--  <div class="col-md-1 mt-4">-->
      <!--              <td><img src="<?php echo e(env('IMAGE_SHOW_PATH').'/setting/principal_sign/'.$data['principal_sign']); ?>" width="40px" height="40px" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/no_image.png'); ?>'"></td>-->
      <!--          </div>-->
      <!--          <div class="col-md-2">-->
				  <!--  <div class="form-group"> -->
					 <!--   <label for="exam_sign" class="required"><?php echo e(__('Exam & Sign.')); ?></label>-->
				  <!--  	<input type="file" class="form-control  <?php $__errorArgs = ['exam_sign'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="exam_sign" name="exam_sign" value="<?php echo e($data->exam_sign); ?>" accept="image/png, image/jpg, image/jpeg">-->
						<!--				 <p class="text-danger" id="image_er"></p>-->
						<!--<?php $__errorArgs = ['exam_sign'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>-->
						<!--<span class="invalid-feedback" role="alert">-->
						<!--	<strong><?php echo e($message); ?></strong>-->
						<!--</span>-->
				  <!--  	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>-->
				  <!--  </div>-->
			   <!-- </div>-->
			   <!--  <div class="col-md-1 mt-4">-->
      <!--              <td><img src="<?php echo e(env('IMAGE_SHOW_PATH').'/setting/exam_sign/'.$data['exam_sign']); ?>" width="40px" height="40px" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/no_image.png'); ?>'"></td>-->
      <!--          </div>-->
     <!--           <div class="col-md-2">-->
				 <!--   <div class="form-group"> -->
					<!--    <label for="treasurer_sign" class="required"><?php echo e(__('Treasurer & Sign.')); ?></label>-->
				 <!--   	<input type="file" class="form-control  <?php $__errorArgs = ['treasurer_sign'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="treasurer_sign" name="treasurer_sign" value="<?php echo e($data->treasurer_sign); ?>" accept="image/png, image/jpg, image/jpeg">-->
					<!--					 <p class="text-danger" id="image_er"></p>-->
					<!--	<?php $__errorArgs = ['treasurer_sign'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>-->
					<!--	<span class="invalid-feedback" role="alert">-->
					<!--		<strong><?php echo e($message); ?></strong>-->
					<!--	</span>-->
				 <!--   	<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>-->
				 <!--   </div>-->
			  <!--  </div>-->
			  <!--   <div class="col-md-1 mt-4">-->
     <!--               <td><img src="<?php echo e(env('IMAGE_SHOW_PATH').'/setting/treasurer_sign/'.$data['treasurer_sign']); ?>" width="40px" height="40px" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/no_image.png'); ?>'"></td>-->
     <!--           </div>-->
			  <!--  <div class="col-md-3">-->
				 <!--   <div class="form-group"> -->
					<!--    <label for="Username"><?php echo e(__('setting.Tin No.')); ?></label>-->
				 <!--   	<input type="text" class="form-control <?php $__errorArgs = ['tin_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"  id="tin_no" name="tin_no" placeholder="<?php echo e(__('setting.Tin No.')); ?>" value="<?php echo e($data->tin_no); ?>">-->
					<!--	<?php $__errorArgs = ['tin_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>-->
					<!--	<span class="invalid-feedback" role="alert">-->
					<!--		<strong><?php echo e($message); ?></strong>-->
					<!--	</span>-->
					<!--<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>-->
				 <!--   </div>-->
			  <!--  </div>-->
              <div class="col-md-3">
                         <div class="form-group">
                         <label><?php echo e(__('Current Active Session')); ?></label>
                      <select class="form-control " id="current_active_session_id" name="current_active_session_id">
                             <?php if(!empty($getSession)): ?> 
                                  <?php $__currentLoopData = $getSession; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <option value="<?php echo e($type->id ?? ''); ?> " <?php echo e(( $type->id == $data['current_active_session_id']) ? 'selected' : ''); ?>><?php echo e($type->from_year ?? ''); ?> - <?php echo e($type->to_year ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                             </select>
                        
                    </div>
                </div>
                
                  <div class="col-md-3">
				    <div class="form-group"> 
					   <label for="apk">Upload APK</label>
                        
                        <input type="file" class="form-control  <?php $__errorArgs = ['apk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="apk" name="apk" value="<?php echo e($data->apk); ?>" accept=".apk">
					
				    </div>
			    </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="firebaseNotification">Notifications</label><br>
                
                        <label class="switch">
                            <input type="checkbox" name="firebase_notification" id="firebaseNotification"
                                   value="1" <?php echo e($data->firebase_notification ? 'checked' : ''); ?>>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
              <!--<div class="col-md-3">-->
              <!--           <div class="form-group">-->
              <!--           <label><?php echo e(__('Login With Otp')); ?></label>-->
              <!--        <select class="form-control " name="loginWithOtp">-->
              <!--              <option value='No' <?php echo e($data->loginWithOtp == 'No' ? 'selected' : ''); ?>>No</option>-->
              <!--              <option value='Yes' <?php echo e($data->loginWithOtp == 'Yes' ? 'selected' : ''); ?>>Yes</option>-->
              <!--               </select>-->
                        
              <!--      </div>-->
              <!--  </div>-->
                
             <div class="col-md-12 text-center">
    			<button type="submit" class="btn btn-primary btn-submit"><?php echo e(__('common.Update')); ?></button>
    		</div>



            
            </div>
            </form>
        </div>

		
    </div>
</div>        

<!--<form class="col-md-3" method='post' action='<?php echo e(url("addVillageList")); ?>'>-->
<!--    <?php echo csrf_field(); ?>-->
<!--  <div >-->
<!--               <label><?php echo e(__('Add Village/City')); ?></label>-->
<!--                <input type="text"  class="form-control mb-2" name='village_name' placeholder="Enter village name" required>-->
<!--                <button id="addVillageBtn" class="btn btn-primary">Add Village</button>-->
<!--            </div>-->
            
<!--            </form>-->
<!--            <div class="col-md-9">-->
<!--                  <label><?php echo e(__('Village/City List')); ?></label>-->
<!--                  <div  class="d-flex flex-wrap m-1">-->
<!--                  
<!--                    <?php if(!empty($list)): ?>-->
                    
<!--                    <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>-->
                    
<!--                     <div class="village-item pl-1  pr-1 d-flex align-items-center bg-white mr-2 " style='border:1px solid #d8d8d8'>-->
<!--                            <span class='mr-1'><?php echo e($item->name ?? ''); ?></span>-->
<!--                    <form class="col-md-3" method='post' action='<?php echo e(url("deleteVillageList")); ?>'>-->
<!--    <?php echo csrf_field(); ?>-->
<!--    <input type='hidden' name='delete_id' value='<?php echo e($item->id); ?>' />-->
<!--                            <button class="btn text-danger btn-sm deleteBtn">&times;</button>-->
<!--         </form-->
<!--                        </div>-->
<!--                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>-->
<!--                    <?php endif; ?>-->
<!--                </div>-->
<!--            </div>-->
<!--</div>-->

 


</div>


</div>
</section>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('#right_logo').change(function(e){
            $('#image_erro').html("");
            var fileName = $(this).val();
        var extension = fileName.split(".").pop();
        if (
          extension.toLowerCase() === "png" ||
          extension.toLowerCase() === "jpg" ||
          extension.toLowerCase() === "jpeg"
        ) {
            if (e.target.files[0].size > Img_Size) {
                $('#image_erro').html("please select Image Size under 2MB");
                $(this).val('');
            }else{
                $('#image_erro').html("");
            }
        }else{
            $('#image_erro').html("Image Size File");
            $(this).val('');
        }
        });
    });
    $(document).ready(function(){
        $('#left_logo').change(function(e){
            $('#image_errors').html("");
            var fileName = $(this).val();
        var extension = fileName.split(".").pop();
        if (
          extension.toLowerCase() === "png" ||
          extension.toLowerCase() === "jpg" ||
          extension.toLowerCase() === "jpeg"
        ) {
            if (e.target.files[0].size > Img_Size) {
                $('#image_errors').html("please select Image Size under 2MB");
                $(this).val('');
            }else{
                $('#image_errors').html("");
            }
        }else{
            $('#image_errors').html("Image Size File");
            $(this).val('');
        }
        });
    });
    $(document).ready(function(){
        $('#seal_sign').change(function(e){
            $('#image_er').html("");
            var fileName = $(this).val();
        var extension = fileName.split(".").pop();
        if (
          extension.toLowerCase() === "png" ||
          extension.toLowerCase() === "jpg" ||
          extension.toLowerCase() === "jpeg"
        ) {
            if (e.target.files[0].size > Img_Size) {
                $('#image_er').html("please select Image Size under 2MB");
                $(this).val('');
            }else{
                $('#image_er').html("");
            }
        }else{
            $('#image_er').html("Image Size File");
            $(this).val('');
        }
        });
    });
    </script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("firebaseNotification");

    toggle.addEventListener("change", function () {
        // Checkbox value change (so form gets correct value)
        this.value = this.checked ? 1 : 0;
        console.log("Firebase notification:", this.value);
    });
});
</script>
    <style>
    #image_erro{
        font-weight: bold;
    font-size: 14px;
    }
    #image_errors{
        font-weight: bold;
    font-size: 14px;
    }
    #image_er{
        font-weight: bold;
    font-size: 14px;
    }
    .switch {
  position: relative;
  display: inline-block;
  width: 46px;
  height: 24px;
}
.switch input { display: none; }
.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 34px;
}
.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}
input:checked + .slider {
  background-color: #28a745;
}
input:checked + .slider:before {
  transform: translateX(22px);
}
    </style>
<?php $__env->stopSection(); ?>        
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/settings/setting/editSetting.blade.php ENDPATH**/ ?>