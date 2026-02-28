<?php $__env->startSection('content'); ?>

<div class="content-wrapper">

    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-4 pr-0 d-none">
                    <div class="card card-outline card-orange mr-1">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-road"></i> &nbsp; <?php echo e(__('bus.Add Route')); ?></h3>
                            <div class="card-tools">

                            </div>

                        </div>
                        <form id="form-submit" action="<?php echo e(url('busRouteAdd')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="row m-2">
                                <div class="col-md-12">
                                    <label style="color:red;"><?php echo e(__('messages.Name')); ?>*</label>
                                    <input class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" name="name" id="name" placeholder="<?php echo e(__('messages.Name')); ?>">
                                   
                                </div>
                                <div class="col-md-12">
                                    <label><?php echo e(__('messages.Description')); ?></label>
                                    <textarea class="form-control" type="text" name="description" id="description" placeholder="<?php echo e(__('messages.Description')); ?>"></textarea>
                                </div>
                            </div>

                            <div class="row m-2">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-submit"><?php echo e(__('messages.submit')); ?> </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-8 pl-0 col-md-12 pl-0">
                    <div class="card card-outline card-orange ml-1">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-road"></i> &nbsp;<?php echo e(__('bus.View Route')); ?> </h3>
                            <div class="card-tools">
                                <!--<a href="<?php echo e(url('students/add')); ?>" class="btn btn-primary  btn-sm" ><i class="fa fa-plus"></i> Add</a>-->
                                <a href="<?php echo e(url('busDashboard')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><?php echo e(__('messages.Back')); ?> </a>
                            </div>

                        </div>
                        <div class="row m-2">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12">
                                <table id="example1" class="table table-bordered table-striped dataTable dtr-inline ">
                                    <thead class="bg-primary">
                                        <tr role="row">
                                            <th><?php echo e(__('messages.Sr.No.')); ?></th>
                                            <th><?php echo e(__('messages.Name')); ?></th>
                                            
                                            <th><?php echo e(__('messages.Action')); ?></th>
                                           



                                    </thead>
                                    <tbody id="">

                                        <?php if(!empty($dataview)): ?>
                                        <?php
                                        $i=1
                                        ?>
                                        <?php $__currentLoopData = $dataview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($i++); ?></td>
                                            <td><?php echo e($item['name'] ?? ''); ?></td>
                                             
                                            <td>
                                                
                                                <a href="<?php echo e(url('busRouteEdit',$item->id)); ?>" class="btn btn-primary  btn-xs tooltip1 <?php echo e(Helper::permissioncheck(20)->edit ? '' : 'd-none'); ?>" title1='Edit Student Registration'><i class="fa fa-edit"></i></a>
                                               
                                                <a href="javascript:;" data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-primary  btn-xs ml-3 tooltip1 <?php echo e(Helper::permissioncheck(20)->delete ? '' : 'd-none'); ?>" title1="Delete Student Registration"><i class="fa fa-trash-o"></i></a>
                                               
                                            </td>
                                           
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $('.deleteData').click(function() {
        var delete_id = $(this).data('id');

        $('#delete_id').val(delete_id);
    });
</script>

<!-- The Modal -->
<div class="modal" id="Modal_id">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #555b5beb;">

            <div class="modal-header">
                <h4 class="modal-title text-white"><?php echo e(__('messages.Delete Confirmation')); ?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>

            <form action="<?php echo e(url('busRouteDelete')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type=hidden id="delete_id" name=delete_id>
                    <h5 class="text-white"><?php echo e(__('messages.Are you sure you want to delete')); ?> ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal"><?php echo e(__('messages.Close')); ?></button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light"><?php echo e(__('messages.Delete')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/bus/route/route_add.blade.php ENDPATH**/ ?>