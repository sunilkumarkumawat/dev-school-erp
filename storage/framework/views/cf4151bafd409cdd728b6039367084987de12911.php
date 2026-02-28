

<?php $__env->startSection('content'); ?>

<div class="content-wrapper">

    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-4 pr-0 ">
                    <div class="card card-outline card-orange mr-1">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="	fa fa-street-view"></i> &nbsp;<?php echo e(__('master.Add Class')); ?>

                            </h3>
                            <div class="card-tools">
                                <!-- <a href="<?php echo e(url('master_dashboard')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i> Back</a>-->
                            </div>
                        </div>

                        <form id="form-submit" action="<?php echo e(url('add_class')); ?>" method="post">

                            <?php echo csrf_field(); ?>
                            <div class="row m-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-danger"><?php echo e(__('master.Class')); ?> *</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> "
                                            id="name" name="name" placeholder="<?php echo e(__('master.Class')); ?>"
                                            value="<?php echo e(old('name')); ?>">

                                    </div>
                                </div>
                            </div>


                            <div class="row m-2">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-submit" id="submitButton"><?php echo e(__('common.submit')); ?> </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="col-md-8 pl-0">
                    <div class="card card-outline card-orange ml-1">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="	fa fa-street-view"></i> &nbsp;<?php echo e(__('master.View Class')); ?> </h3>
                            <div class="card-tools">
                                <!--<a href="<?php echo e(url('students/add')); ?>" class="btn btn-primary  btn-sm" ><i class="fa fa-plus"></i> Add</a>-->
                                <a href="<?php echo e(url('master_dashboard')); ?>" class="btn btn-primary  btn-sm"><i
                                        class="fa fa-arrow-left"></i><?php echo e(__('common.Back')); ?></a>
                            </div>

                        </div>
                        <div class="row m-2">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12">
                                <table id="example1" class="table table-bordered table-striped dataTable dtr-inline ">
                                    <thead class="bg-primary">
                                        <tr role="row">
                                            <th><?php echo e(__('common.SR.NO')); ?></th>
                                            <th><?php echo e(__('master.Class')); ?></th>

                                            <th><?php echo e(__('common.Action')); ?></th>

                                    </thead>
                                    <tbody>
                                        <?php if(!empty($data) && count($data) > 0): ?>
                                        <?php $i = 1; ?>
                                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $admissions = DB::table('admissions')
                                        ->where('class_type_id', $item['id'])
                                        ->where('branch_id',Session('branch_id'))
                                        ->where('session_id',Session('session_id'))
                                        ->whereNull('deleted_at')
                                        ->get();
                                        $fees_master = DB::table('fees_master')
                                        ->where('class_type_id', $item['id'])
                                        ->where('branch_id',Session('branch_id'))
                                        ->where('session_id',Session('session_id'))
                                        ->whereNull('deleted_at')
                                        ->get();
                                        ?>
                                        <tr>
                                            <td><?php echo e($i++); ?></td>
                                            <td><?php echo e($item['name']); ?></td>
                                            <td>
                                                <?php if(count($admissions) == 0 && count($fees_master) == 0): ?>

                                                <a href="<?php echo e(url('edit_class')); ?>/<?php echo e($item['id'] ?? ''); ?>"
                                                    class="btn btn-primary btn-xs tooltip1 <?php echo e(Helper::permissioncheck(9)->edit ? '' : 'd-none'); ?>"
                                                    title1="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <a href="javascript:;" data-id="<?php echo e($item['id']); ?>"
                                                    data-bs-toggle="modal" data-bs-target="#Modal_id"
                                                    class="deleteData btn btn-danger btn-xs ml-3 tooltip1 <?php echo e(Helper::permissioncheck(9)->delete ? '' : 'd-none'); ?>"
                                                    title1="delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>


                                                <?php else: ?>
                                                <a href=""
                                                    class="btn btn-primary  btn-xs  tooltip_disable <?php echo e(Helper::permissioncheck(9)->edit ? '' : 'd-none'); ?>"
                                                    title_disable="Can't edit class after assign any student or fees ."><i
                                                        class="fa fa-edit"></i></a>
                                                <a href=""
                                                    class="deleteData btn btn-danger btn-xs ml-3  tooltip_disable <?php echo e(Helper::permissioncheck(9)->delete ? '' : 'd-none'); ?>"
                                                    title_disable="Can't delete class after assign any student or fees ."><i
                                                        class="fa fa-trash-o"></i></a>

                                                <?php endif; ?>
                                            </td>

                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" style="text-align: center;">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal">
                                                    Add Class
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                    <?php endif; ?>
                                    </tbody>
                                    <?php
                                    $class_type = DB::table('class_types')
                                    ->where('branch_id', session('branch_id'))
                                    ->orderBy('orderBy', 'ASC')
                                    ->groupBy('name')
                                    ->whereNull('deleted_at')
                                    ->get();

                                    ?>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(url('save-selected-classes')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">Select Classes</h5>
                    <button type="button" class="fa fa-times" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white">
                            <tr role="row">
                                <th><?php echo e(__('common.SR.NO')); ?></th>
                                <th><?php echo e(__('master.Class')); ?></th>
                                <th> <input type="checkbox" id="select_all" checked /> Select All
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php if(!empty($class_type) && count($class_type) > 0): ?>
                            <?php $__currentLoopData = $class_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($i++); ?></td>
                                <td><input class="form-control" type="text" name="class[<?php echo e($item1->id ?? ''); ?>]"
                                        id="amount" placeholder="Class Name" value="<?php echo e($item1->name ?? ''); ?>">

                                </td>
                                <td>
                                    <input type="checkbox" class="group_checkbox" name="class_id[]"
                                        value="<?php echo e($item1->id); ?>" checked>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No class found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Class</button>
                </div>
            </div>
        </form>
    </div>
</div>




<script src="<?php echo e(URL::asset('public/assets/school/js/jquery.min.js')); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select_all');
        const checkboxes = document.querySelectorAll('.group_checkbox');

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
    });
</script>

<script>
    $('.deleteData').click(function () {
        var delete_id = $(this).data('id');

        $('#delete_id').val(delete_id);
    });
</script>

<!-- The Modal -->
<div class="modal" id="Modal_id">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #555b5beb;">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-white"><?php echo e(__('common.Delete Confirmation')); ?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times"
                        aria-hidden="true"></i></button>
            </div>

            <!-- Modal body -->
            <form action="<?php echo e(url('class_delete')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="modal-body">



                    <input type=hidden id="delete_id" name=delete_id>
                    <h5 class="text-white"><?php echo e(__('common.Are you sure you want to delete')); ?> ?</h5>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form"
                        data-dismiss="modal"><?php echo e(__('common.Close')); ?></button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light"><?php echo e(__('common.Delete')); ?></button>
                </div>
            </form>

        </div>
    </div>
</div>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/class/add.blade.php ENDPATH**/ ?>