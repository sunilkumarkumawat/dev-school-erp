 
<?php $__env->startSection('content'); ?>
<?php

$classType = Helper::ClassType();
$setting = Helper::getSetting();
?>
<div class="content-wrapper">
<section class="content pt-3">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12 col-md-12">
            <div class="card card-outline card-orange">
               <div class="card-header bg-primary">
                  <h3 class="card-title"><i class="fa fa-leanpub"></i> &nbsp; <?php echo e(__('Stream Update')); ?> </h3>
                  <div class="card-tools d-flex align-item-center"> 
                     <a href="<?php echo e(url('studentsDashboard')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><?php echo e(__('messages.Back')); ?>  </a> 
                  </div>
               </div>
               <div class="card-body">
                     <div class="row">
                        <div class='col-md-10'>
                        <form id="quickForm_find" action="<?php echo e(url('stream_update')); ?>" method="post">
                             <?php echo csrf_field(); ?> 
                            <div class="row">
                            <div class="col-md-3">
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
                                     <?php if($type->orderBy > 10): ?>
                                     <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id == $search['class_type_id']) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
                                     <?php endif; ?>
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
                               </div>                            </div>
                           
                          
                            
                         <div class="col-md-1 col-6">
                               <label for="" class="text-white">Search</label>
                               <button type="submit" class="btn btn-primary"><?php echo e(__('messages.Search')); ?></button>
                            </div>
                            </div>
                        </form>
                        </div>
                     </div>
                     <?php if(count($data) > 0): ?>
                     <hr>
                     <div class="row">
                        <div class='col-md-12'>
                        <form id="form-submit" action="<?php echo e(url('stream_update_save')); ?>" method="post">
                             <?php echo csrf_field(); ?> 
                            <div class="row">
                            
                            <div class="col-md-3">
                               <div class="form-group">
                                  <label class="text-danger"><?php echo e(__('Subject Name')); ?>*</label>
                                  <table id="" class="table table-bordered table-striped dataTable dtr-inline ">
                                    
                                    <tbody >
                                  
                                      <?php if(!empty($list_subject) && count($list_subject) > 0): ?>
                                        <?php
                                           $i=1;
                                         
                                        ?>
                                            <?php $__currentLoopData = $list_subject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>                                  
                                                        <input type="checkbox" data-value="view" name="subject_id[]" class="viewcheck2" value="<?php echo e($type1->id ?? ''); ?>">
                                                    </td>
                                                    <td><?php echo e($type1->name ?? ''); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="2">Subject not found
                                                       ( <a href="<?php echo e(url('add_subject')); ?>" class="">  Assign Subject </a>)
</td>
                                            </tr>
                                        <?php endif; ?>

                                  </tbody>
                            </table>
                               
                               </div>
                            </div>
                            <div class="col-md-9" style="font-size: 13px;">
                               <label class="text-danger"><?php echo e(__('Student Name')); ?>*</label>
                                <table id="" class="table table-bordered table-striped dataTable dtr-inline ">
                                    <thead class="bg-primary">
                                        <tr role="row">
                                          <th> <input type="checkbox" id="view1"><?php echo e(__('student.Ad. No')); ?></th>
                                           <th><?php echo e(__('common.Name')); ?></th>
                                           <th><?php echo e(__('Subject')); ?></th>
                                        </tr>
                                       </thead>
                                    <tbody >
                                  
                                  <?php if(!empty($data)): ?>
                                            <?php
                                                $i = 1;
                                            ?>
                                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $subject_ids = explode(',', $item['stream_subject']);
                                        
                                                    $subject = DB::table('subject')->whereIn('id', $subject_ids)->get();
                                                ?>
                                        
                                                <tr>
                                                    <td>								    
                                                        <input type="checkbox" data-value="view" id="<?php echo e($item->id ?? ''); ?>" name="admission_id[]" class="viewcheck" value="<?php echo e($item->id ?? ''); ?>">
                                                      <label for="<?php echo e($item->id ?? ''); ?>">  <?php echo e($item['admissionNo']); ?></label>
 
                                                    </td>
                                                    <td><?php echo e($item['first_name'] ?? ''); ?> <?php echo e($item['last_name'] ?? ''); ?></td>
                                                    <td>
                                                        <?php if(!empty($subject)): ?>
                                                            <ul class="subject-list">
                                                                <?php $__currentLoopData = $subject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <li class="subject-item" id="admission-<?php echo e($item->id); ?>-<?php echo e($sub->id); ?>">
                                                                        <?php echo e($sub->name ?? ''); ?>

                                                                        <div class="delete-btn" onclick="deleteSubject('<?php echo e($sub->id); ?>','<?php echo e($item->id); ?>')">
                                                                            <i class="fa fa-trash-o"></i>
                                                                        </div>
                                                                    </li>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </ul>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>

                                  </tbody>
                            </table>
                
            </div>
                        <?php if(count($data) > 0): ?>    
                         <div class="col-md-12 col-6 text-center">
                               <label for="" class="text-white">Search</label>
                               <button type="submit" class="btn btn-primary btn-submit"><?php echo e(__('Save')); ?></button>
                            </div>
                            <?php endif; ?>
                            </div>
                        </form>
                        </div>
                     </div>
                     <?php endif; ?>
                
            </div>
</section>
</div>
<script>
    // ✅ View1 checkbox logic (unchanged)
    $("#view1").click(function () {
        if ($(this).is(':checked')) {
            $(".viewcheck").prop('checked', false);
            $(".viewcheck").prop('checked', true);
        } else {
            $(".viewcheck").prop('checked', false);
        }
    });

    $(document).ready(function () {
        $('#class_type_id').on('change', function (e) {
            var baseurl = "<?php echo e(url('/')); ?>";
            var class_type_id = $(this).val();

            // ✅ Condition check like first code
            if (class_type_id > 0) {
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content') },
                    type: 'get',
                    url: baseurl + '/subjectGetData/' + class_type_id,
                    success: function (data) {
                        $("#subject_id").html(data);
                    },
                    error: function () {
                        alert('Something went wrong, please try again.');
                    }
                });
            } else {
                alert('Please select a Class Type!');
                $("#subject_id").html('<option value="">Select Subject</option>'); // reset subject dropdown
            }
        });
    });
</script>

<!-- Add your custom CSS -->
<style>
    .subject-list {
        list-style-type: none;
        padding: 0;
    }

    .subject-item {
        position: relative;
        display: inline-block;
        margin-right: 10px;
    }

    .delete-btn {
          display: none;
          /*position: absolute;*/
          top: 0;
         /* right: -16px;*/
          background-color: white;
          color: red;
          border: none;
          padding: -2px;
          cursor: pointer;
          z-index: 1100;
        }
    /* Show delete button on hover */
    .subject-item:hover .delete-btn {
        display: inline-block;
    }
</style>

<script>
var baseurl = "<?php echo e(url('/')); ?>";

function deleteSubject(subjectId, admission_id) {
    if (confirm("Are you sure you want to delete this subject?")) {
        $.ajax({
            url: `${baseurl}/stream_remove/${admission_id}/${subjectId}`,
            headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
            success: function(response) {
                if (response.success) {
                  
                    $('#admission-' + admission_id + '-' + subjectId).remove();
                } else {
                    alert('Error deleting the subject.');
                }
            },
            error: function() {
                alert('An error occurred while deleting the subject.');
            }
        });
    }
}

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/students/admission/stream_update.blade.php ENDPATH**/ ?>