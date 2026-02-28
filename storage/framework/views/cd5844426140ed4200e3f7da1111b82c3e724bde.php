<?php
  $classType = Helper::classType();
  $getsubject = Helper::getSubject();;
?>

<?php $__env->startSection('content'); ?>

<div class="content-wrapper">

   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-orange">
                     <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fa fa-check-square-o"></i> &nbsp; <?php echo e(__('examination.Add Questions')); ?></h3>
                    <div class="card-tools">
                    <a href="<?php echo e(url('view/question')); ?>" class="btn btn-primary  btn-sm" title="View Users"><i class="fa fa-eye"></i> <?php echo e(__('common.View')); ?> </a>
                    <a href="<?php echo e(url('examination_dashboard')); ?>" class="btn btn-primary  btn-sm" title="View Users"><i class="fa fa-arrow-left"></i> <?php echo e(__('common.Back')); ?> </a>
                    </div>
                    
                    </div>        
                <form id="quickForm" action="<?php echo e(url('add/question')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row m-2">

                       <!--<div class="col-md-4">
            			<div class="form-group">
            				<label style="color:red;">Class*:</label>
            				<select class="form-control <?php $__errorArgs = ['class_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="class_type_id" name="class_type_id" value="<?php echo e(old('class_type_id')); ?>">
                            <option value="" >Select</option>
                             <?php if(!empty($classType)): ?> 
                                  <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id == old('class_type_id')) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                            </select>
                             <?php $__errorArgs = ['class_type_id'];
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
            		 -->        
                        <div class="col-md-3">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('examination.Subject')); ?></label>
            				<select class="form-control select2 <?php $__errorArgs = ['subject_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="subject_id" name="subject_id" value="<?php echo e(old('subject_id')); ?>">
                             <?php if(!empty($getsubject)): ?> 
                                  <?php $__currentLoopData = $getsubject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id == old('subject_id')) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                            </select>
                             <?php $__errorArgs = ['subject_id'];
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
                                <label style="color:red;"> <?php echo e(__('examination.Question Type')); ?></label>
                                <select class="form-control select2 <?php $__errorArgs = ['question_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="question_type_id" name="question_type_id">
        					    <option value="1" >Objective</option>
        					    <option value="2" >Descriptive </option>
                               
                            </select>
                             <?php $__errorArgs = ['question_type_id'];
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
        
            		<div class="col-md-12">
            			<div class="form-group">
            				<label  style="color:red;"><?php echo e(__('examination.Question')); ?></label>
            				<textarea class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name"><?php echo e(old('name') ?? ''); ?></textarea>
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
            		   <div class="col-md-3 option_1">
        				       
        			<div class="form-group ">
        				<label style="color:red;">  <?php echo e(__('examination.Option')); ?>-1 </label>
        				 <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                            <input type="checkbox" name="correct_ans" value="0" <?php if(old('correct_ans')=="0"): ?> checked <?php endif; ?>>
                            </span>
                            </div>
                          	<input type="text"  name="ans1" id="ans1" class="form-control <?php $__errorArgs = ['ans1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> checkAns" value="<?php echo e(old('ans1')); ?>" placeholder="<?php echo e(__('examination.Answer')); ?> 1">
                            </div>
                          	<?php $__errorArgs = ['ans1'];
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
		         <div class="col-md-3 option_2">
        			<div class="form-group ">
        				<label style="color:red;">  <?php echo e(__('examination.Option')); ?>-2 </label>
        				 <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                            <input type="checkbox"name="correct_ans" value="1" <?php if(old('correct_ans')=="1"): ?> checked <?php endif; ?>>
                            </span>
                            </div>
                          	<input type="text" name="ans2" id="ans2" class="form-control <?php $__errorArgs = ['ans2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> checkAns" value="<?php echo e(old('ans2')); ?>" placeholder="<?php echo e(__('examination.Answer')); ?> 2">
                            </div>
                          	<?php $__errorArgs = ['ans2'];
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
		        
		       <div class="col-md-3 option_3">
        				<div class="form-group ">
        				<label style="color:red;">  <?php echo e(__('examination.Option')); ?>-3 </label>
        				 <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                            <input type="checkbox"name="correct_ans" value="2" <?php if(old('correct_ans')=="2"): ?> checked <?php endif; ?>>
                            </span>
                            </div>
                          	<input type="text"  name="ans3" id="ans3" class="form-control <?php $__errorArgs = ['ans3'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> checkAns" value="<?php echo e(old('ans3')); ?>" placeholder="<?php echo e(__('examination.Answer')); ?> 3">
                            </div>
                          	<?php $__errorArgs = ['ans3'];
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
		          <div class="col-md-3 option_4">
        		      	<div class="form-group ">
        				<label style="color:red;">  <?php echo e(__('examination.Option')); ?>-4 </label>
        				 <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                            <input type="checkbox"name="correct_ans" value="3" <?php if(old('correct_ans')=="3"): ?> checked <?php endif; ?>>
                            </span>
                            </div>
                            <input type="text"  name="ans4" id="ans4" class="form-control <?php $__errorArgs = ['ans4'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> checkAns" value="<?php echo e(old('ans4')); ?>" placeholder="<?php echo e(__('examination.Answer')); ?> 4">
                            </div>
                          	<?php $__errorArgs = ['ans4'];
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

<script>
    // $(document).ready(function(){
    //   $('.checkAns').change(function(){
    //       var value = $(this).val();
    //       var length = $('.checkAns').length;
    //       var index = $('.checkAns').index(this);
    //       var array = [];
           
    //       $('.checkAns').each(function(){
    //           if($(this).val() != ""){
    //               array.push($(this).val());
    //           }else{
    //               array.push(null);
    //           }
    //       });
          
          
          
          
          
          
          
          
           
          
    //     //   if (array.indexOf($(this).val()) > -1) {
    //     //         alert("same value");   
    //     //     }else{
    //     //     }
    //   }); 
    // });
</script>

<script>
$(document).ready(function() { 
    $("#ans4").attr('required','true');
    $("#ans3").attr('required','true');
    $("#ans2").attr('required','true');
    $("#ans1").attr('required','true');
});
$('#question_type_id').on('change', function(e){
  
	var question_type_id = $(this).val();
    if(question_type_id == 1){
        $(".option_1").show();
        $(".option_2").show();
        $(".option_3").show();
        $(".option_4").show();
        $("#ans4").attr('required','true');
        $("#ans3").attr('required','true');
        $("#ans2").attr('required','true');
        $("#ans1").attr('required','true');
        
    }
    if(question_type_id == 2){
        $(".option_1").hide();
        $(".option_2").hide();
        $(".option_3").hide();
        $(".option_4").hide();
    $("#ans4").removeAttr('required');
    $("#ans3").removeAttr('required');
    $("#ans2").removeAttr('required');
    $("#ans1").removeAttr('required');        
    }    
    if(question_type_id == ''){
        $(".option_1").hide();
        $(".option_2").hide();
        $(".option_3").hide();
        $(".option_4").hide();
        
    } 	
}); 

//select all checkboxes
    $("#<?php echo e($data->id ?? ''); ?>").change(function () {  
        $(".checkbox").prop('checked', $(this).prop("checked")); 
    });

//".checkbox" change 
    $('.checkbox').change(function () {
        if (false == $(this).prop("checked")) { 
            $("#<?php echo e($data->id ?? ''); ?>").prop('checked', false);
        }
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $("#<?php echo e($data->id ?? ''); ?>").prop('checked', true);
        }
    });
</script>
<script>
    $("input:checkbox").on('click', function() {
  var $box = $(this);
  if ($box.is(":checked")) {
    var group = "input:checkbox[name='" + $box.attr("name") + "']";
    $(group).prop("checked", false);
    $box.prop("checked", true);
  } else {
    $box.prop("checked", false);
  }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/examination/offline_exam/question_bank/add.blade.php ENDPATH**/ ?>