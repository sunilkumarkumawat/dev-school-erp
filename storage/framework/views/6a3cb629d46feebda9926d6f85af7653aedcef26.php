<?php
$classType = Helper::classTypeExam();
$getsubject = Helper::getSubject();
?>

<?php $__env->startSection('content'); ?>

<div class="content-wrapper">

    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary flex_items_toggel">
                            <h3 class="card-title"><i class="nav-icon fas fa fa-tag"></i> &nbsp;Assign Exam To Class :: <?php echo e($data->name ?? ''); ?> </h3>
                            <div class="card-tools">
                                <a href="<?php echo e(url('view/exam')); ?>" class="btn btn-primary  btn-sm"><i
                                        class="fa fa-arrow-left"></i> <span class="Display_none_mobile"><?php echo e(__('messages.Back')); ?></span> </a>
                            </div>

                        </div>
                        <form class="p-3" action="<?php echo e(url('assign/exam/'.$data->id)); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="edit_id" id="edit_id">
                            
                            <div id="examWrapper">
                            
                                <div class="row examRow">
                                    
                                    <div class="col-md-3">
                                        <label>Class *</label>
                                        <select name="class_type_id[]" class="form-control" required>
                                            <option value="">Select</option>
                                            <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                            
                                    <div class="col-md-2">
                                        <label>Total Marks *</label>
                                        <input type="text" name="total_marks[]" class="form-control" placeholder="Enter Total Marks" required>
                                    </div>
                            
                                    <div class="col-md-2">
                                        <label>Exam Date *</label>
                                        <input type="date" name="exam_date[]" class="form-control">
                                    </div>
                            
                                    <div class="col-md-2">
                                        <label>Result Date *</label>
                                        <input type="date" name="result_declaration_date[]" class="form-control">
                                    </div>
                            
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-success addRow">+</button>
                                    </div>
                            
                                </div>
                            
                            </div>
                            
                            <br>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>

                        <div class="row m-3 pb-2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Class</th>
                                        <th>Total Marks</th>
                                        <th>Exam Date</th>
                                        <th>Result Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            
                                <tbody>
                                    <?php $__currentLoopData = $AssignExam; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key+1); ?></td>
                                        <td><?php echo e($item->class_name); ?></td>
                                        <td><?php echo e($item->total_marks); ?></td>
                                        <td><?php echo e($item->exam_date); ?></td>
                                        <td><?php echo e($item->result_declaration_date); ?></td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-primary btn-xs editAssign tooltip1"
                                                title1="Edit Student"
                                                data-id="<?php echo e($item->id); ?>"
                                                data-class="<?php echo e($item->class_type_id); ?>"
                                                data-total_marks="<?php echo e($item->total_marks); ?>"
                                                data-exam_date="<?php echo e($item->exam_date); ?>"
                                                data-result_declaration_date="<?php echo e($item->result_declaration_date); ?>">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-xs deleteAssign tooltip1"
                                                title1="Delete"
                                                data-assign_id="<?php echo e($item->id); ?>"
                                                data-toggle="modal"
                                                data-target="#deleteModal">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="modal fade" id="deleteModal">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Delete Conformation</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form action="<?php echo e(url('assign/delete/exam')); ?>" method="post">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-body">
                                            <input type="hidden" id="exam_id" name="exam_id"
                                                value="<?php echo e($data->id ?? ''); ?>">
                                            <input type="hidden" id="assign_id" name="assign_id">
                                            Are You Sure ?
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Sumbit</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    $(document).ready(function () {
        $('.deleteAssign').click(function () {
            var assign_id = $(this).data('assign_id');
            $('#assign_id').val(assign_id);
        });
    })
</script>

<script>
$(document).ready(function(){

    $(document).on('click','.addRow',function(){

        let row = `
        <div class="row examRow mt-2">
            <div class="col-md-3">
                <select name="class_type_id[]" class="form-control" required>
                    <option value="">Select</option>
                    <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-2">
                <input type="number" name="total_marks[]" class="form-control" required>
            </div>

            <div class="col-md-2">
                <input type="date" name="exam_date[]" class="form-control" required>
            </div>

            <div class="col-md-2">
                <input type="date" name="result_declaration_date[]" class="form-control" required>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger removeRow">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
        `;

        $('#examWrapper').append(row);
    });

    $(document).on('click','.removeRow',function(){
        $(this).closest('.examRow').remove();
    });

});
</script>
<script>

$(document).on('click','.editAssign',function(){

    let id = $(this).data('id');
    let class_id = $(this).data('class');
    let total_marks = $(this).data('total_marks');
    let exam_date = $(this).data('exam_date');
    let result_declaration_date = $(this).data('result_declaration_date');

    $('#examWrapper').html('');

    let row = `
    <div class="row examRow">
        <div class="col-md-3">
            <label>Class *</label>
            <select name="class_type_id[]" class="form-control classSelect" required>
                <option value="">Select</option>
                <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-md-2">
            <label>Total Marks *</label>
            <input type="number" name="total_marks[]" class="form-control" value="${total_marks}" required>
        </div>

        <div class="col-md-2">
            <label>Exam Date *</label>
            <input type="date" name="exam_date[]" class="form-control" value="${exam_date}" required>
        </div>

        <div class="col-md-2">
            <label>Result Date *</label>
            <input type="date" name="result_declaration_date[]" class="form-control" value="${result_declaration_date}" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-success addRow">+</button>
        </div>
    </div>
    `;

    $('#examWrapper').append(row);

    $('#edit_id').val(id);

    // Set selected class
    $('.classSelect').val(class_id);

    $('html, body').animate({
        scrollTop: $("#examWrapper").offset().top - 100
    }, 500);

});

</script>





<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/examination/offline_exam/exam/assign.blade.php ENDPATH**/ ?>