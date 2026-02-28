<?php
   $classType = Helper::ClassType();
  $getsubject = Helper::getSubject();
?>

 
<?php $__env->startSection('content'); ?>
    
    
<div class="content-wrapper">
  
    <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-md-12">   
        <div class="card card-outline card-orange">
              <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fa fa-edit"></i> &nbsp;<?php echo e(__('homework.Edit Homework')); ?> </h3>
            <div class="card-tools">
            <a href="<?php echo e(url('hourly/hw/view')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><?php echo e(__('messages.Back')); ?> </a>
           <!-- <a href="<?php echo e(url('homework/index')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i> Back</a>-->
            </div>
            
            </div>        
            
             <div class="card-body">
                <form id="form-submit-edit" action="<?php echo e(url('homework/edit')); ?>/<?php echo e($data['id'] ?? ''); ?>" method="post" enctype="multipart/form-data">
                      <?php echo csrf_field(); ?>
                <div class="row"> 
                
                <div class="col-md-3">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('messages.Class')); ?>:*</label>
            				<select class="form-control select2 <?php $__errorArgs = ['class_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="class_type_id" name="class_type_id">
                             <?php if(!empty($classType)): ?> 
                                  <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(( $type->id == $data['class_type_id'] ? 'selected' : '' )); ?>><?php echo e($type->name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                            </select>
                            
            		    </div>
            		</div>
                    <div class="col-md-3">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('messages.Subject')); ?>:*</label>
            				<select class="form-control select2 <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="subject" name="subject">
                             <?php if(!empty($getsubject)): ?> 
                                  <?php $__currentLoopData = $getsubject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(( $type->id == $data['subject'] ? 'selected' : '' )); ?>><?php echo e($type->name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                            </select>
                             
            		    </div>
            		</div>            	
            		
            		
                <div class="col-md-3">
					<div class="form-group">
								<label style="color: red;"><?php echo e(__('homework.Homework Title')); ?>*</label>
								<input class="form-control  <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" id="title" name="title" placeholder="Homework Title" value="<?php echo e($data->title ??  ''); ?>"> 
                               								    
						    </div>
						</div>
                    	 
            		<div class="col-md-3">
        			    <div class="form-group">
        				<label style="color:red;"><?php echo e(__('Homework Issue Date')); ?>:*</label>
        				
        					<input type="date" class="form-control <?php $__errorArgs = ['homework_issue_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="homework_issue_date" name="homework_issue_date"value="<?php echo e($data['homework_issue_date'] ?? ''); ?>">
        				   
                        
        		        </div>
        		    </div>
        		    
            		<div class="col-md-3">
        			    <div class="form-group">
        				<label style="color:red;"><?php echo e(__('homework.Submission Date')); ?>:*</label>
        					<input type="date" class="form-control <?php $__errorArgs = ['submission_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="submission_date" name="submission_date"value="<?php echo e($data['submission_date'] ?? ''); ?>">
                        
        		        </div>
        		    </div> 
        		    
                             		    
        		    
        		    		   <div class="col-md-12">
        			   <div class="form-group">
        				<label style="color:red;"><?php echo e(__('messages.Description')); ?>:*</label>
        					<textarea type="text" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="compose-textarea" name="description" placeholder="Please submit before last date." ><?php echo e($data['description'] ?? ''); ?></textarea>
                         
        		        </div>
        		    </div>
        	<!--	   <div class="col-md-12">
        			   <div class="form-group">
        				<label style="color:red;">Description:*</label>
        					<textarea type="text" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description" placeholder="Please submit before last date."><?php echo e($data['description'] ?? ''); ?></textarea>
                         <?php $__errorArgs = ['description'];
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
        		    </div>-->
              </div>
                <div class="row m-2">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-submit"><?php echo e(__('messages.Update')); ?></button>
                    </div>
                </div>
                </form>
                </div>                 
            </div> 
            </div>
</div>
</div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('#content_file').change(function(e){
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
    
    .card-block{
                height:240px;
            }
    </style>
    
</section>
        </div>
        

          
<?php $__env->stopSection(); ?>                
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/home_work/home_work/edit.blade.php ENDPATH**/ ?>