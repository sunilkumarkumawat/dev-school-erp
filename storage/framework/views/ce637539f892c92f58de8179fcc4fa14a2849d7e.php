<?php
    $classType = Helper::classTypeExam();
?>

 
<?php $__env->startSection('content'); ?>

<input type="hidden" id="session_id" value="<?php echo e(Session::get('role_id') ?? ''); ?>">

<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <!-- Card -->
                    <div class="card card-outline card-orange">
                        
                        <!-- Card Header -->
                        <div class="card-header bg-primary">
                            <h3 class="card-title">
                                <i class="fa fa-calendar-check-o"></i> &nbsp;
                                <?php echo e(__('Exam Result Update')); ?>

                            </h3>
                            <div class="card-tools">
                                <a href="<?php echo e(url('examination_dashboard')); ?>" class="btn btn-primary btn-sm">
                                    <i class="fa fa-arrow-left"></i>
                                    <span class="Display_none_mobile"><?php echo e(__('messages.Back')); ?></span>
                                </a>
                            </div>
                        </div>
                        <!-- End Card Header -->

                        <!-- Search Form -->
                        <form id="quickForm" action="<?php echo e(url('exam_result_update')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="row m-2">

                                <!-- Class Type -->
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
unset($__errorArgs, $__bag); ?>" 
                                                id="class_type_id" name="class_type_id">
                                            <option value=""><?php echo e(__('messages.Select')); ?></option>
                                            <?php if(!empty($classType)): ?>
                                                <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($type->id ?? ''); ?>"
                                                        <?php echo e(($type->id == $search['class_type_id']) ? 'selected' : ''); ?>>
                                                        <?php echo e($type->name ?? ''); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php $__errorArgs = ['class_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Exam Name -->
                                <div class="col-md-2 col-4">
                                    <div class="form-group">
                                        <label class="text-danger"><?php echo e(__('messages.Exam Name')); ?>*</label>
                                        <select class="select2 form-control exam_id_" id="exam_id" name="exam_id" required>
                                            <option value=""><?php echo e(__('messages.Select')); ?></option>
                                            <?php if(!empty($exam)): ?>
                                                <?php $__currentLoopData = $exam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($type->exam_id); ?>"
                                                        <?php echo e(($type->exam_id == $search['exam_id'] ? 'selected' : '' )); ?>>
                                                        <?php echo e($type->exam_name ?? ''); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                            <?php $__errorArgs = ['exam_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Search Button -->
                                <div class="col-md-1 col-12 text-center">
                                    <label class="Display_none_mobile text-white">Search</label>
                                    <button type="submit" class="btn btn-primary" onclick="SearchValue1()">
                                        <?php echo e(__('messages.Search')); ?>

                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- End Search Form -->

                        <!-- Student Table -->
                        <div class="col-md-12">
                            <form action="<?php echo e(url('exam_result_update_save')); ?>" method="post" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="table-responsive">                        
                                    <table class="bg-white table table-bordered table-striped dataTable dtr-inline">
                                        <thead>
                                            <tr role="row">
                                                <th><?php echo e(__('Admission No')); ?></th>
                                                <th><?php echo e(__('Name')); ?></th>
                                                <th><?php echo e(__('Roll No.')); ?></th>
                                                <th><?php echo e(__('Attendence ')); ?></th>
                                                <th><?php echo e(__('Permote to')); ?></th>
                                                <th><?php echo e(__('Rank')); ?></th>
                                                <th><?php echo e(__('Remark')); ?></th> 
                                            </tr>
                                        </thead>
                                        <tbody class="student_list_show">
                                            <?php if($data->count() > 0): ?>
                                                <?php $i = 1; ?>
                                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $exam_data = DB::table('exam_result_updates')
                                                            ->where('admission_id', $item->id)
                                                            ->where('exam_id', $search['exam_id'])
                                                            ->first();
                                                    ?>
                                                    <tr class="active_color">
                                                        <input type="hidden" name="admission_id[]" value="<?php echo e($item->id ?? ''); ?>">
                                                        <input type="hidden" name="exam_ids" value="<?php echo e($search['exam_id'] ?? ''); ?>">
                                                        <input type="hidden" name="class_type_id" value="<?php echo e($search['class_type_id'] ?? ''); ?>">
    
                                                        <td><?php echo e($item['admissionNo'] ?? ''); ?></td>
                                                        <td><?php echo e($item['first_name'] ?? ''); ?></td>
                                                        <td><input type="text" name="roll_no[]" value="<?php echo e($exam_data->roll_no ?? ''); ?>" style="width:150px;" placeholder="Roll No"></td>
                                                        <td><input type="text" name="attendence[]" value="<?php echo e($exam_data->attendence ?? ''); ?>" style="width:150px;" placeholder="Attendence"></td>
                                                        <td><input type="text" name="permote_to[]" value="<?php echo e($exam_data->permote_to ?? ''); ?>" style="width:150px;" placeholder="Permote to"></td>
                                                        <td><input type="text" name="rank[]" value="<?php echo e($exam_data->rank ?? ''); ?>" style="width:150px;" placeholder="Rank"></td>
                                                        <td><input type="text" name="remark[]" value="<?php echo e($exam_data->remark ?? ''); ?>" style="width:250px;" placeholder="Remark"></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="12" class="text-center">No Students Found !</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Submit Button -->
                                <div class="col-md-12 text-center p-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                        <!-- End Student Table -->

                    </div>
                    <!-- End Card -->

                </div>
            </div>
        </div>
    </section>
</div>

<style>
    /* Mobile view improvements */
@media (max-width: 768px) {
  .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  table {
    font-size: 13px;
    white-space: nowrap;
  }
  th, td {
    text-align: center;
    vertical-align: middle;
  }
  input.form-control-sm {
    min-width: 120px; /* so input fields don’t shrink too much */
  }
}

</style>

<!-- Scripts -->
<script src="<?php echo e(URL::asset('public/assets/school/js/jquery.min.js')); ?>"></script>

<script>
$(document).ready(function(){
    // Check/uncheck view
    $("#view1").click(function(){
        if ($(this).is(':checked')) {
            $(".viewcheck").prop('checked', true);
        } else {
            $(".viewcheck").prop('checked', false);
        }
    });

    // Load exams on class change
    $('#class_type_id').on('change', function(){
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/examination/offline_exam/exam_result_update/exam_result_update.blade.php ENDPATH**/ ?>