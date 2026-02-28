<?php $getCountry = Helper::getCountry();
$getState = Helper::getState();
$getCity = Helper::getCity();
?>

<?php $__env->startSection('content'); ?>

<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 pr-0">
                    <div class="card card-outline card-orange mr-1">
                        <div class="card-header bg-primary flex_items_toggel">
                            <h3 class="card-title"><i class="fa fa-leanpub"></i> &nbsp;<?php echo e(__('Assign Subject ')); ?></h3>
                            <div class="card-tools"><a href="<?php echo e(url('master_dashboard')); ?>"
                                    class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><span
                                        class="Display_none_mobile"> Back </span></a></div>
                        </div>

                        <form id="quickForm" action="<?php echo e(url('select_class')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="row m-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo e(__('messages.Class')); ?></label>
                                        <select class="form-control select2" id="class_type_id" name="class_type_id">
                                            <option value=""><?php echo e(__('messages.Select')); ?></option>
                                            <?php if(!empty(Helper::classType())): ?>
                                            <?php $__currentLoopData = Helper::classType(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id ==
                                                $search['class_type_id']) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-2 ">
                                    <button type="submit" class="btn btn-primary mt-4"><?php echo e(__('Search')); ?></button><br>
                                </div>
                            </div>
                        </form>


                        <?php if(!empty($search['class_type_id'])): ?>
                        <form id="form-submit" action="<?php echo e(url('add_subject')); ?>" method="post">
                            <?php echo csrf_field(); ?>

                            <div class="row m-2">
                                <input type="hidden" name="class_type_id" value="<?php echo e($search['class_type_id'] ?? ''); ?>" />
                                <div class="col-md-12">
                                    <label class=""><?php echo e(__('Select Subject')); ?></label>

                                </div>


                                <div class="col-md-12">



                                    <?php
                                    $checkbox =
                                    DB::table('all_subjects')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('deleted_at',null)->get();
                                    ?>

                                    <?php $__currentLoopData = $checkbox; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <?php
                                    $subject =
                                    DB::table('subject')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('name',$item->name)->
                                    where('class_type_id',$search['class_type_id'])->where('deleted_at',null)->first();
                                    ?>
                                    <div class="row">
                                        <div class="col-md-2 ">
                                            <label style="cursor:pointer;">
                                                <input type="checkbox"
                                                       name="add_subject[]"
                                                       value="<?php echo e($item->name ?? ''); ?>"
                                                       <?php echo e($subject !='' ? 'checked' : ''); ?> />
                                                &nbsp;&nbsp;  <span
                                                style="width:300px"><?php echo e($item->name ?? ''); ?> </span>
                                            &nbsp;&nbsp;
                                            </label>
                                        </div>
                                        <div class="col-md-2 ">
                                            <?php echo e($item->other_subject == 1 ? 'Other' : 'Main'); ?>

                                        </div>
                                    </div>



                                    <br>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                </div>
                            </div>
                            <div class="row m-2">
                                <div class="col-md-12 text-center"><button type="submit"
                                        class="btn btn-primary btn-submit"><?php echo e(__('Save')); ?></button><br></div>
                            </div>
                        </form>

                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if(!empty($section) && count($section) > 0): ?>
            <form id="form-submit-edit" action="<?php echo e(url('subjectOrderBy')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="col-md-12 pl-0">
                    <div class="card  ml-1">


                        <div class="col-md-12" style="overflow-x:scroll;">
                            <table id="example1" class="table table-bordered table-striped dataTable dtr-inline nowrap">
                                <thead class="bg-primary">
                                    <tr role="row">
                                        <th><?php echo e(__('messages.Sr.No.')); ?></th>
                                        <th><?php echo e(__('messages.Subject')); ?></th>
                                        <th><?php echo e(__('Sort')); ?></th>
                                        <th><?php echo e(__('Category')); ?></th>
                                        <th>Class</th>
                                        <th><?php echo e(__('Delete')); ?></th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php $i=1
                                    ?>
                                    <?php $__currentLoopData = $section; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <?php
                                        $resortData =
                                        DB::table('class_types')->where('id',$item['class_type_id'])->first()
                                        ?>
                                        <td><?php echo e($i++); ?>

                                            <input type="hidden" name="subject_id[]" value="<?php echo e($item['id'] ?? ''); ?>" />
                                        </td>
                                        <td><?php echo e($item['name'] ?? ''); ?></td>
                                        <td> <input type="text" name="sort_by[]" class="w-25"
                                                value="<?php echo e($item['sort_by'] ?? ''); ?>" /></td>
                                        <td><?php echo e($item['other_subject'] == 1 ? 'Other' : 'Main'); ?></td>
                                        <td><?php echo e($resortData->name ?? ''); ?></td>
                                        <td>
                                            <!--<a href="<?php echo e(url('edit_subject')); ?>/<?php echo e($item['id'] ?? ''); ?>" class="btn btn-primary  btn-xs" title="Edit" ><i class="fa fa-edit"></i></a> -->
                                            <a href="javascript:;" data-id='<?php echo e($item[' id']); ?>' data-bs-toggle="modal"
                                                data-bs-target="#Modal_id"
                                                class="deleteData btn btn-primary btn-xs ml-3" title="Delete"><i
                                                    class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td colspan="6" class=" p-2 text-center"><button
                                                class=" btn btn-primary btn-submit" type="submit">Submit</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </form>
            <?php endif; ?>
        </div>
</div>
</div>
</section>
</div>


<script src="<?php echo e(URL::asset('public/assets/school/js/jquery.min.js')); ?>"></script>

<script>
    $(".deleteData").click(function () {
        var delete_id = $(this).data("id");

        $("#delete_id").val(delete_id);
    });
</script>



<!-- The Modal -->
<div class="modal" id="Modal_id">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #555b5beb;">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-white"><?php echo e(__('messages.Delete Confirmation')); ?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times"
                        aria-hidden="true"></i></button>
            </div>

            <!-- Modal body -->
            <form action="<?php echo e(url('delete_subject')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" id="delete_id" name="delete_id" />
                    <h5 class="text-white"><?php echo e(__('messages.Are you sure you want to delete')); ?> ?</h5>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form"
                        data-bs-dismiss="modal"><?php echo e(__('messages.Close')); ?></button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo e(__('messages.Delete')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/subject/add_subject.blade.php ENDPATH**/ ?>