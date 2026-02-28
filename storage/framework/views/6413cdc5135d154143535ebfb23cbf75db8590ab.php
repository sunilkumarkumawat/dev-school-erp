
<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content pt-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card card-outline card-orange">
            <div class="card-header bg-primary">
              <h3 class="card-title"><i class="fa fa-address-book-o"></i> &nbsp; <?php echo e(__('Add Call Log')); ?></h3>
              <div class="card-tools">
                <a href="<?php echo e(url('reception_file')); ?>" class="btn btn-primary btn-sm">
                  <i class="fa fa-arrow-left"></i> <?php echo e(__('common.Back')); ?>

                </a>
              </div>
            </div>

            <div class="card-body">
             
              <form action="<?php echo e(url('callLog/add')); ?>" method="post" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="row m-2">

                  <div class="col-md-3">
                    <div class="form-group">
                      <label style="color:red;"><?php echo e(__('Call Type')); ?>*</label>
                      <select class="form-control <?php $__errorArgs = ['call_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="call_type" name="call_type">
                        <option value="">Select</option>
                        <option value="Outgoing" <?php echo e(old('call_type')=='Outgoing'?'selected':''); ?>>Outgoing</option>
                        <option value="Incoming" <?php echo e(old('call_type')=='Incoming'?'selected':''); ?>>Incoming</option>
                      </select>
                      <?php $__errorArgs = ['call_type'];
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

                  <div class="col-md-3">
                    <div class="form-group">
                      <label style="color:red;"><?php echo e(__('Calling Purpose')); ?>*</label>
                      <select class="form-control <?php $__errorArgs = ['calling_purpose_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> select2"
                              id="calling_purpose_id"
                              name="calling_purpose_id">
                        <option value="">Select</option>
                        <?php $__currentLoopData = $callingPurposes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purpose): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($purpose->id); ?>" <?php echo e(old('calling_purpose_id') == $purpose->id ? 'selected' : ''); ?>>
                              <?php echo e($purpose->name); ?>

                          </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>
                      <?php $__errorArgs = ['calling_purpose_id'];
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

                  <div class="col-md-3">
                    <div class="form-group">
                      <label style="color:red;"><?php echo e(__('Name')); ?>*</label>
                      <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                             id="name" name="name" placeholder="<?php echo e(__('Enter Name')); ?>" value="<?php echo e(old('name')); ?>">
                      <?php $__errorArgs = ['name'];
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

                  <div class="col-md-3">
                    <div class="form-group">
                      <label style="color:red;"><?php echo e(__('Mobile No')); ?>*</label>
                      <input type="text" class="form-control <?php $__errorArgs = ['mobile_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                             id="mobile_no" name="mobile_no" maxlength="10" onkeypress="return isNumber(event)"
                             placeholder="<?php echo e(__('Enter Mobile Number')); ?>" value="<?php echo e(old('mobile_no')); ?>">
                      <?php $__errorArgs = ['mobile_no'];
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

                  <div class="col-md-3">
                    <div class="form-group">
                      <label style="color:red;"><?php echo e(__('Date')); ?>*</label>
                      <input type="date" class="form-control <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                             id="date" name="date" value="<?php echo e(old('date')); ?>">
                      <?php $__errorArgs = ['date'];
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

                  <div class="col-md-2">
                    <div class="form-group">
                      <label style="color:red;"><?php echo e(__('Start Time')); ?>*</label>
                      <input type="time" class="form-control <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                             id="start_time" name="start_time" value="<?php echo e(old('start_time')); ?>">
                      <?php $__errorArgs = ['start_time'];
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

                  <div class="col-md-2">
                    <div class="form-group">
                      <label style="color:red;"><?php echo e(__('End Time')); ?>*</label>
                      <input type="time" class="form-control <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                             id="end_time" name="end_time" value="<?php echo e(old('end_time')); ?>">
                      <?php $__errorArgs = ['end_time'];
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

                  <div class="col-md-3">
                    <div class="form-group">
                      <label><?php echo e(__('Follow Up Date')); ?></label>
                      <input type="date" class="form-control <?php $__errorArgs = ['follow_up_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                             id="follow_up_date" name="follow_up_date" value="<?php echo e(old('follow_up_date')); ?>">
                      <?php $__errorArgs = ['follow_up_date'];
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

                  <div class="col-md-12">
                    <div class="form-group">
                      <label><?php echo e(__('Note')); ?></label>
                      <textarea class="form-control <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="note" name="note" placeholder="<?php echo e(__('Note')); ?>" rows="2"><?php echo e(old('note')); ?></textarea>
                      <?php $__errorArgs = ['note'];
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
                </div>

                <div class="row m-2">
                  <div class="col-md-12 text-center pb-2">
                    <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          
<div class="card mt-4 card-outline card-orange">
  <div class="card-header bg-primary">
    <h3 class="card-title text-white">
      <i class="fa fa-list"></i> &nbsp; <?php echo e(__('Call Log List')); ?>

    </h3>
  </div>
  <div class="card-body">
    <table id="example1" class="table table-bordered table-striped dataTable dtr-inline">
      <thead class="bg-primary text-white">
        <tr>
          <th><?php echo e(__('common.SR.NO')); ?></th>
          <th><?php echo e(__('Call Type')); ?></th>
          <th><?php echo e(__('Calling Purpose')); ?></th>
          <th><?php echo e(__('Name')); ?></th>
          <th><?php echo e(__('Mobile No')); ?></th>
          <th><?php echo e(__('Date')); ?></th>
          <th><?php echo e(__('Start Time')); ?></th>
          <th><?php echo e(__('End Time')); ?></th>
          <th><?php echo e(__('Follow Up Date')); ?></th>
          <th><?php echo e(__('Note')); ?></th>
          <th><?php echo e(__('Action')); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        <?php $__empty_1 = true; $__currentLoopData = $callLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td><?php echo e($i++); ?></td>
            <td>
              <?php if($log->call_type == 'Outgoing'): ?>
                <span class="badge badge-success"><?php echo e($log->call_type); ?></span>
              <?php else: ?>
                <span class="badge badge-info"><?php echo e($log->call_type); ?></span>
              <?php endif; ?>
            </td>
            <td><?php echo e($log->callingPurpose->name ?? '-'); ?></td>
            <td><?php echo e($log->name); ?></td>
            <td><?php echo e($log->mobile_no); ?></td>
            <td><?php echo e($log->date ? \Carbon\Carbon::parse($log->date)->format('d-M-Y') : '-'); ?></td>
            <td><?php echo e($log->start_time); ?></td>
            <td><?php echo e($log->end_time); ?></td>
            <td><?php echo e($log->follow_up_date ? \Carbon\Carbon::parse($log->follow_up_date)->format('d-M-Y') : '-'); ?></td>
            <td><?php echo e($log->note); ?></td>
            <td>
                 <a href="javascript:;" data-id='<?php echo e($log->id); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData <?php echo e(Helper::permissioncheck(28)->delete ? '' : 'd-none'); ?>">
                                <button class="btn btn-danger btn-xs tooltip1" title1="Delete"><i class="fa fa-trash-o"></i></button>
                          </a>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="10" class="text-center text-danger">
              <?php echo e(__('No call logs found.')); ?>

            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal" id="Modal_id">
              <div class="modal-dialog">
                <div class="modal-content" style="background: #555b5beb;">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title text-white">Delete Confirmation</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
                  </div>

                  <!-- Modal body -->
                  <form action="<?php echo e(url('callLogDelete')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">



                      <input type="hidden" id="delete_id" name="delete_id">
                      <h5 class="text-white">Are you sure you want to delete ?</h5>

                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-danger waves-effect waves-light">Delete</button>
                    </div>
                  </form>

                </div>
              </div>
            </div>
            
            
<script>
    $('.deleteData').click(function() {
        
  var delete_id = $(this).data('id'); 
  
  $('#delete_id').val(delete_id); 
  } );
  

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/reception/call_log/add.blade.php ENDPATH**/ ?>