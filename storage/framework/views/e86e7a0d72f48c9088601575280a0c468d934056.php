 
<?php $__env->startSection('content'); ?>

                                                                   
<div class="content-wrapper">
	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
							<h3 class="card-title"><i class="fa fa-edit"></i> &nbsp;<?php echo e(__('master.Edit Time Period')); ?></h3>
							
							<div class="card-tools">
                                        <a href="<?php echo e(url('time_periods')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i> <?php echo e(__('common.Back')); ?> </a>
                        </div>
                           </div>                      
                          <form id="form-submit-edit" action="<?php echo e(url('edit_periods')); ?>/<?php echo e(($data->id)); ?>" method="post" >
                                <?php echo csrf_field(); ?>
                        	<div class="row m-2">
                             
                                <div class="col-md-4">
                                    <label class="text-danger"><?php echo e(__('master.From Time')); ?>*</label>
                                    <input type="time" class="form-control <?php $__errorArgs = ['from_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)"
                                        id="from_time"
                                        name="from_time"
                                       
                                        value="<?php echo e(date("H:i", strtotime($data['from_time']))); ?>"
                                    />
                                    
                                </div>
                           
                             
                                <div class="col-md-4">
                                    <label class="text-danger"><?php echo e(__('master.To Time')); ?>*</label>
                                    <input type="time" class="form-control <?php $__errorArgs = ['to_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)"
                                        id="to_time"
                                        name="to_time"
                                       
                                         value="<?php echo e(date("H:i", strtotime($data['to_time']))); ?>"
                                    />
                                   
                                </div>
                                <div class="col-md-4">
                                    <label class="text-danger">Period Name*</label>
                                    <select name="period_name" class="form-control <?php $__errorArgs = ['period_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="" >Select Period Name</option>
                                        <option value="First" <?php echo e(($data['period_name'] == "First") ? 'selected' : ''); ?>>First</option>
                                        <option value="Second" <?php echo e(($data['period_name'] == "Second") ? 'selected' : ''); ?> >Second</option>
                                        <option value="Third" <?php echo e(($data['period_name'] == "Third") ? 'selected' : ''); ?> >Third</option>
                                        <option value="Fourth" <?php echo e(($data['period_name'] == "Fourth") ? 'selected' : ''); ?> >Fourth</option>
                                        <option value="Fifth" <?php echo e(($data['period_name'] == "Fifth") ? 'selected' : ''); ?> >Fifth</option>
                                        
                                        <option value=" 🍴 Lunch Break" <?php echo e(($data['period_name'] == "🍴 Lunch Break") ? 'selected' : ''); ?> >🍴 Lunch Break</option>
                                        
                                        <option value="Sixth" <?php echo e(($data['period_name'] == "Sixth") ? 'selected' : ''); ?> >Sixth</option>
                                        <option value="Seventh" <?php echo e(($data['period_name'] == "Seventh") ? 'selected' : ''); ?> >Seventh</option>
                                        <option value="Eighth" <?php echo e(($data['period_name'] == "Eighth") ? 'selected' : ''); ?> >Eighth</option>
                                        <option value="Ninth" <?php echo e(($data['period_name'] == "Ninth") ? 'selected' : ''); ?> >Ninth</option>
                                        <option value="Tenth" <?php echo e(($data['period_name'] == "Tenth") ? 'selected' : ''); ?> >Tenth</option>
                                        <option value="Eleventh" <?php echo e(($data['period_name'] == "Eleventh") ? 'selected' : ''); ?> >Eleventh</option>
                                        <option value="Twelfth" <?php echo e(($data['period_name'] == "Twelfth") ? 'selected' : ''); ?> >Twelfth</option>
                                        
                                    </select>
                                     
                                     
                                   
                                </div>
                            </div>
        <div class="col-md-12 text-center">
			<button type="submit" onclick="timeCheck()" class="btn btn-primary btn-submit"><?php echo e(__('messages.Update')); ?></button><br><br>
		</div>
    	</form>
            </div>
        </div>
    </div>
    </section>
</div>

<script>

$("#form-submit-edit").submit(function(e){
     
  

  var element = document.getElementById("from_time").value;
  var element1 = document.getElementById("to_time").value;
  
  if (element == "") {
  alert("Please Enter Time");
    return false;  
  }

  else {
  
 
 
  // get input time
  var time = element.split(":");
  var hour = time[0];
  if(hour == '00') {hour = 24}
  var min = time[1];
  
   var inputTime = hour+"."+min;
  
  var time1 = element1.split(":");
  var hour1 = time1[0];
  if(hour1 == '00') {hour1 = 24}
  var min1 = time1[1];
  
  var inputTime1 = hour1+"."+min1;
 
  
  var totalTime = inputTime1 - inputTime;
  
 
  if ((Math.abs(totalTime)) > 0.29000000000000004) {
  
  } 
  else {
   
      e.preventDefault();
    alert("Less Time");
  }
    }
  });
   
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/time_table/edit.blade.php ENDPATH**/ ?>