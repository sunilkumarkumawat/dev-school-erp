 
<?php $__env->startSection('content'); ?>
<?php
$classType = Helper::classTypeExam();
$setting = Helper::getSetting();

?>
<div class="content-wrapper">
<section class="content pt-3">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12 col-md-12">
            <div class="card card-outline card-orange">
               <div class="card-header bg-primary">
                  <h3 class="card-title"><i class="fa fa-leanpub"></i> &nbsp; <?php echo e(__('Examination Schedule')); ?> </h3>
                  <div class="card-tools cl-6"> 
                     <a href="<?php echo e(url('examination_dashboard')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><?php echo e(__('messages.Back')); ?>  </a> 
                  </div>
               </div>
               <div class="card-body">
                  <form id="quickForm_find" action="<?php echo e(url('add/examination_schedule')); ?>" method="post" >
                     <?php echo csrf_field(); ?> 
                     <div class="row">
                        <div class="col-md-2 col-4">
                           <div class="form-group">
                              <label class="text-danger"><?php echo e(__('messages.Class')); ?>*</label>
                              <select class="select2 form-control <?php $__errorArgs = ['class_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> " id="class_type_id" name="class_type_id">
                                 <option value=""><?php echo e(__('messages.Select')); ?></option>
                                 <?php if(!empty($classType)): ?>
                                 <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id == $search['class_type_id']) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
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
                        <div class="col-md-2 col-4">
                           <div class="form-group">
                              <label class="text-danger"><?php echo e(__('messages.Exam Name')); ?>*</label>
                              <select class="select2 form-control exam_id_" id="exam_id" name="exam_id" >
                                 <option value=""><?php echo e(__('messages.Select')); ?></option>
                                 <?php if(!empty($exam)): ?> 
                                
                                 <?php $__currentLoopData = $exam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <option value="<?php echo e($type->exam_id); ?> " <?php echo e(( $type->exam_id == $search['exam_id'] ? 'selected' : '' )); ?>><?php echo e($type->exam_name ?? ''); ?> </option>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                 <?php endif; ?>
                                 <?php $__errorArgs = ['exam_id'];
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
                        <div class="col-md-1 col-2">
                           <label for="" class="text-white">Search</label>
                           <button type="submit" onClick="checkValidation(event)" class="btn btn-primary"><?php echo e(__('messages.Search')); ?></button>
                        </div>
                     </div>
                  </form>
                  
                  <div class="row m-2">
                     <div class="col-12">
                    <?php if(count($data) > 0): ?>   
                        <form id="form-submit" action=<?php echo e(url('SubmitSchedule')); ?> method="post">
                           <?php echo csrf_field(); ?>
                            <div class="card p-0">
                               <div class="card-body p-0" style="display: block;">
                                  <div class="table-responsive card">
                                     <table class="table table-bordered table-striped dataTable dtr-inline card-outline card-orange">
                            <thead class="bg-primary">
                                <tr>
                                    <th>S No</th>
                                    <th>Subject Name</th>
                                    <th>Date</th>
                                    <th>From Time</th>
                                    <th>To Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                 $i = 1;
                                ?>
                                 <input type="hidden" id="class_type_id" name="class_type_id" value="<?php echo e($search['class_type_id'] ?? ''); ?>">
                                <input type="hidden" id="exam_id" name="exam_id" value="<?php echo e($search['exam_id'] ?? ''); ?>">
                                
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <?php
                                      $examsc = DB::table('examination_schedules')
                                                        ->where(['exam_id' => $search['exam_id']])
                                                        ->where('class_type_id',$search['class_type_id'])
                                                        ->where('branch_id',Session::get('branch_id'))
                                                        ->where('session_id',Session::get('session_id'))
                                                        ->where('subject_id',$item->id)->whereNull('deleted_at')->first();
                                  ?>
                                  <?php if(!empty($examsc->id)): ?>
                               <input type="hidden" class="form-control" name="examination_schedule_id[]" id="examination_schedule_id"  value="<?php echo e($examsc->id ?? ''); ?>">
                               <?php else: ?>
                                <input type="hidden" class="form-control" name="examination_schedule_id[]" id="examination_schedule_id"  value="">
                               <?php endif; ?>
                                <tr>
                                    <td><?php echo e($i++); ?>.</td>
                                    <td><input type="hidden" class="form-control" name="subject_id[]" id="subject_id" readonly value="<?php echo e($item->id ?? ''); ?>"><?php echo e($item->name  ?? ''); ?></td>
                                    <td><input type="date" name="date[]" id="date" class="form-control" value="<?php echo e($examsc->date ?? ''); ?>" ></td> 
                                    <td><input type="time" name="from_time[]" id="from_time" class="form-control" value="<?php echo e($examsc->from_time ?? ''); ?>"></td>
                                    <td><input type="time" name="to_time[]" id="to_time" class="form-control" value="<?php echo e($examsc->to_time ?? ''); ?>"></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            </table>
                                  </div>
                               </div>
                               
                                    <div class="card-footer mt-2" style="display: block;">
                                  <div class="col-md-12 mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                           <div class="form-group">
                                                <label>Exam Center</label>
                                                <input type="text" class="form-control" name="exam_center" id="exam_center" value="<?php echo e($setting->address ?? ''); ?>" placeholder="Exam Center">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 mt-3 mb-3 text-center">
                                           <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                                        </div>
                                    </div>
                        </div>
                               </div>
                               
                        </form>
                    <?php else: ?>
                    <div class="row">
                        <div class="col-md-2" style="text-align: center;font-size: 22px;border: 1px solid;padding: 3px; background-color: #dd3545; color: white; border-radius: 8px;">
                             data not found 
                            </div>
                            
                            
                        </div>
                    <?php endif; ?>
                     </div>
                  </div>
                  
               </div>
            </div>
         </div>
      </div>
</section>
</div>


<?php $__env->stopSection(); ?>

<script>
    function checkValidation(event){
        event.preventDefault()
        var classval = $('#class_type_id').val();
        var examVal = $('#exam_id').val();
        if(classval == ""){
           toastr.error('Please select a class ');
        }else if(examVal == ""){
            toastr.error('Please select a exam ');
        }else{
            $('#quickForm_find').trigger('submit');
        }
    }
</script>
<script src="<?php echo e(URL::asset('public/assets/school/js/jquery.min.js')); ?>"></script>
<script>
$(document).ready(function(){
    
        $('#class_type_id').on('change', function(e){
            

                $(".div_subject_id").css("display","block");
                $('#subject_id').prop('required',true);

                var baseurl = "<?php echo e(url('/')); ?>";
            	var class_type_id = $(this).val();
            	  
                $.ajax({
                    headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
            	    url: baseurl + '/examData/' + class_type_id,
            	    success: function(data){
    	         	    $("#exam_id").html(data);
            	    }
            	});
        });
    
});
</script>


<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/examination/offline_exam/exam_schedule/add.blade.php ENDPATH**/ ?>