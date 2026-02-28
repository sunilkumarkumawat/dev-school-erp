<?php
  $classType = Helper::classType();
  $getsubject = Helper::getSubject();
  $getallStudent = Helper::getallStudent();

?>

 
<?php $__env->startSection('content'); ?>
   
    
<div class="content-wrapper">
   
    <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-md-12">   
        <div class="card card-outline card-orange">
               <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fa fa-flask"></i> &nbsp; <?php echo e(__('messages.Add Hourly Homework')); ?></h3>
            <input type="hidden" id="role_id" value="<?php echo e(Session::get('role_id') ?? ''); ?>"> 
            <div class="card-tools">
                         <a href="<?php echo e(url('hourly/hw/view')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-eye"></i> <?php echo e(__('messages.Hourly Homework')); ?></a>
<!--        <a href="<?php echo e(url('master_dashboard')); ?>" class="btn btn-primary  btn-xs"><i class="fa fa-arrow-left"></i> Back</a>
-->                
   
           
            </div>
            
            </div>   
             <div class="card-body">
                 <form id="form-submit" action="<?php echo e(url('hourly/hw/add')); ?>" method="post" enctype="multipart/form-data">
                      <?php echo csrf_field(); ?>
                <div class="row"> 
                     <div class="col-md-2">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('messages.Class')); ?>*</label>
            				<select class="form-control select2 <?php $__errorArgs = ['class_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="class_type_id" name="class_type_id">
                            <option value="" ><?php echo e(__('messages.Select')); ?></option>
                             <?php if(!empty($classType)): ?> 
                                  <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id == Session::get('class_type_id')) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                            </select>
                            
            		    </div>
            		</div>

                    <div class="col-md-2">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('messages.Subject')); ?>*</label>
            				<select class="form-control select2 <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="subject" name="subject">
            				    <option value="">Select</option>
                             <?php if(!empty($getsubject)): ?> 
                                  <?php $__currentLoopData = $getsubject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <option value="<?php echo e($type->id ?? ''); ?>" ><?php echo e($type->name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                            </select>
                            
            		    </div>
            		</div>            		 
            		<div class="col-md-2">
        			    <div class="form-group">
        				<label style="color:red;"></label><?php echo e(__('messages.Homework Date')); ?>

        				
        					<input type="date" class="form-control <?php $__errorArgs = ['homework_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="homework_date" name="homework_date"value="<?php echo e(date('Y-m-d')); ?>">
        				 
        		        </div>
        		    </div>
                <div class="col-md-2">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('Select Student')); ?>*</label>
            			<select name="admission_id" id="admission_id" class="form-control select2">
            			    <option value="">Select</option>
                                         <?php if(!empty($getallStudent)): ?> 
                                          <?php $__currentLoopData = $getallStudent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                             <option value="<?php echo e($type->id ?? ''); ?>"><?php echo e($type->first_name ?? ''); ?> <?php echo e($type->last_name ?? ''); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                         <?php endif; ?>

                                            </select>  
                            
            		    </div>
            		</div>
            		
                <div class="col-md-2">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('Time')); ?>*</label>
                            <input type="time" name="times" id="times" class="form-control <?php $__errorArgs = ['times'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                           
            		    </div>
            	</div>
                
                                    
                <div class="col-md-2">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('Content File')); ?>*</label>
                             <input class="form-control <?php $__errorArgs = ['content_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="file" name="content_file" id="content_file" value="<?php echo e(old('content_file') ?? ''); ?>" accept="image/png, image/jpg, image/jpeg">
										 <p class="text-danger" id="image_error"></p>
                           
            		    </div>
            		</div>
                       <div class="col-md-2">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('Title')); ?>*</label>
            		            <textarea type="title" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="If have any note write here..."></textarea>
                           
            		    </div>
            		</div>
             
                             
              </div>
              <div class="row m-2">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-submit"><?php echo e(__('messages.Submit')); ?></button>
                    </div>
                </div>
                </form>
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
    </style>
    
<script>
$("#class_type_id").change(function(){
    
    var class_type_id = $(this).val();
        $.ajax({
             headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
        type:'post',
        url: '/find/student',
        data: {class_type_id:class_type_id},
	    success: function(data){
	     if(data != ''){
	         	$("#admission_id").html(data);
	     }else{
	         	$("#admission_id").html(data);
	            toastr.error('User Not Found !');
	     }
	    }
        }); 
 
});  

$(document).ready(function() {
  
    count=0;
      $( ".removeprodtxtbx" ).eq( 0 ).css( "display", "none" );
    $(document).on("click", "#clonebtn", function() {
       count++;
        //we select the box clone it and insert it after the box
        $('#box2').addClass('rowTr')
        $('#box2').clone().appendTo('#table_body')
        $('.rowTr').last().addClass('rowTr1')

         $( ".removeprodtxtbx" ).eq( count ).css( "display", "block" );
         $( ".addmoreprodtxtbx" ).eq( count ).css( "display", "none" );
         $( ".pay_amt" ).eq( count ).val("");
          
    });
    
    $(document).on("click", "#removerow", function() {
        $(this).parents("#box2").remove();
        $('#removerow').focus();
        count--;
    });
});
</script>        
<style>
._table {
    width: 100%;
    border-collapse: collapse;
}

._table :is(th, td) {
    padding: 0px 10px;
}
.success {
    background-color: #24b96f !important;
}
.danger {
    background-color: #ff5722 !important;
}
.action_container>* {
    border: none;
    outline: none;
    color: #fff;
    text-decoration: none;
    display: inline-block;
    padding: 8px 14px;
    cursor: pointer;
    transition: 0.3s ease-in-out;
}
textarea {
    height: calc(2.25rem) !important;
}

 .addmoreprodtxtbx {
  background-color: #FFFFFF;
  background-image: url(<?php echo e(url('https://saleanalysics.rukmanisoftware.com/public/images/list_add.png')); ?>);
  background-repeat: no-repeat;
  border: medium none;
  cursor: pointer;
  height: 16px;
  margin-top:4px;
  width: 16px;
}

.removeprodtxtbx {
  background-color: #FFFFFF;
  background-image: url(<?php echo e(url('https://saleanalysics.rukmanisoftware.com/public/images/delete2.png')); ?>);
  background-repeat: no-repeat;
  border: medium none;
  cursor: pointer;
  height: 15px;
 
   margin:4px 0 0 0 !important;
  width: 16px;
 
}
</style>    
<script>
/*$( document ).ready(function() {
    var role_id = $('#role_id').val();
   
   if( role_id == 2 ) { 
        $("#class_type_id").attr('disabled', 'disabled');
        $("#section").attr('disabled', 'disabled');
   }else{
   }     
});*/
</script>
<?php $__env->stopSection(); ?>                
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/home_work/hourly/add.blade.php ENDPATH**/ ?>