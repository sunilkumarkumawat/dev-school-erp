
<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">

          <!-- Left Side (Enquiry Details) -->
          <div class="col-12 col-md-4">
              <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary">
                  <h3 class="card-title mb-0">
                  <i class="fa fa-address-book-o"></i> &nbsp; <?php echo e(__('Enquiry Details')); ?>

                </h3>
              </div>
                <div class="card-body p-4">
                  <?php if(!empty($data)): ?>
                  <ul class="list-group list-group-flush">
                    <?php
                        $statusColor = match($data->status) {
                            'Active' => 'badge bg-success',
                            'Partially Closed' => 'badge bg-warning text-dark',
                            'Missed' => 'badge bg-danger',
                            'Closed' => 'badge bg-secondary',
                            default => 'badge bg-light text-dark'
                        };
                    ?>
                    <li class="list-group-item">
                      <strong>Status:</strong>
                      <?php
                          $statusColor = match($data->latest_status) {
                              'Active' => 'badge bg-success',
                              'Partially Closed' => 'badge bg-warning text-dark',
                              'Missed' => 'badge bg-danger',
                              'Closed' => 'badge bg-secondary',
                              default => 'badge bg-light text-dark'
                          };
                      ?>
                      <span class="<?php echo e($statusColor); ?>"><?php echo e($data->latest_status ?? ''); ?></span>
                    </li>

                    <li class="list-group-item"><strong>Name:</strong> <?php echo e($data['first_name'] ?? ''); ?></li>
                    <li class="list-group-item"><strong>Class:</strong> <?php echo e($data['ClassTypes']['name'] ?? ''); ?></li>
                    <li class="list-group-item"><strong>Mobile:</strong> <?php echo e($data['mobile'] ?? ''); ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?php echo e($data['email'] ?? ''); ?></li>
                    <li class="list-group-item"><strong>Father's Name:</strong> <?php echo e($data['father_name'] ?? ''); ?></li>
                    <li class="list-group-item"><strong>Mother's Name:</strong> <?php echo e($data['mother_name'] ?? ''); ?></li>
                    <li class="list-group-item"><strong>DOB:</strong> <?php echo e(date('d-m-Y', strtotime($data['dob'])) ?? ''); ?></li>
                    <li class="list-group-item"><strong>Registration Date:</strong> <?php echo e(date('d-m-Y', strtotime($data['registration_date'])) ?? ''); ?></li>
                    <li class="list-group-item"><strong>Note:</strong> <?php echo e($data['note'] ?? ''); ?></li>
                  </ul>
                  <?php endif; ?>
                </div>
              </div>
          </div>

          <!-- Right Side -->
          <div class="col-12 col-md-8">

            <!-- Add Follow Up -->
            <div class="card card-outline card-orange mb-3">
              <!--<div class="card-header bg-primary">-->
              <!--  <h3 class="card-title mb-0"><i class="fa fa-address-book-o"></i> &nbsp;<?php echo e(__('Add Follow Up')); ?></h3>-->
              <!--   <a href="<?php echo e(url('enquiryView')); ?>" class="btn btn-primary  btn-sm leftbutons" ><i class="fa fa-arrow-left"></i><span class="Display_none_mobile"><?php echo e(__('common.View')); ?></span></a>-->
              <!--   <a href="<?php echo e(url('reception_file')); ?>" class="btn btn-primary  btn-sm" ><i class="fa fa-arrow-left"></i><span class="Display_none_mobile"><?php echo e(__('common.Back')); ?></span></a>-->
              <!--</div>-->
              <div class="card-header bg-primary d-flex justify-content-between align-items-center flex-wrap">
                  <h3 class="card-title mb-0 text-white d-flex align-items-center">
                    <i class="fa fa-address-book-o"></i>&nbsp;<?php echo e(__('Add Follow Up')); ?>

                  </h3>
                
                  <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                    <a href="<?php echo e(url('enquiryView')); ?>" class="btn text-light btn-sm fgfgfg">
                      <i class="fa fa-eye"></i>
                      <span class="d-none d-md-inline"><?php echo e(__('common.View')); ?></span>
                    </a>
                    <a href="<?php echo e(url('reception_file')); ?>" class="btn text-light btn-sm">
                      <i class="fa fa-arrow-left"></i>
                      <span class="d-none d-md-inline"><?php echo e(__('common.Back')); ?></span>
                    </a>
                  </div>
                </div>

              <form action="<?php echo e(url('enquiryFollowUpAdd/'.$data->id)); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="row m-2">
                    <div class="col-md-6">
                        <label><?php echo e(__('Follow Up Date')); ?></label>
                        <input type="date" class="form-control" name="follow_up_date" value="<?php echo e(old('follow_up_date', $data->follow_up_date ? $data->follow_up_date->format('Y-m-d') : date('Y-m-d'))); ?>">
                    </div>
                    <div class="col-md-6">
                        <label style="color:red;"><?php echo e(__('Next Follow Up Date')); ?>*</label>
                        <input type="date" class="form-control" name="next_follow_up_date" value="<?php echo e(old('next_follow_up_date', $data->next_follow_up_date ? $data->next_follow_up_date->format('Y-m-d') : '')); ?>">
                    </div>
                    <div class="col-md-12">
                        <label><?php echo e(__('Response')); ?></label>
                        <textarea class="form-control" name="response"><?php echo e(old('response', $data->response)); ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label style="color:red;"><?php echo e(__('Status')); ?>*</label>
                        <select class="form-control" name="status">
                            <option value=""><?php echo e(__('Select Status')); ?></option>
                            <option value="Active" <?php echo e(old('status', $data->status) == 'Active' ? 'selected' : ''); ?>>Active</option>
                            <option value="Partially Closed" <?php echo e(old('status', $data->status) == 'Partially Closed' ? 'selected' : ''); ?>>Partially Closed</option>
                            <option value="Missed" <?php echo e(old('status', $data->status) == 'Missed' ? 'selected' : ''); ?>>Missed</option>
                            <option value="Closed" <?php echo e(old('status', $data->status) == 'Closed' ? 'selected' : ''); ?>>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label><?php echo e(__('Note')); ?></label>
                        <textarea class="form-control" name="note"><?php echo e(old('note', $data->note)); ?></textarea>
                    </div>
                </div>
                <div class="row m-2">
                    <div class="col-md-12 text-center pb-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
              </form>
            </div>

            <!-- Follow Up List -->
            <div class="card card-outline card-orange">
              <div class="card-header bg-primary">
                <h3 class="card-title mb-0"><i class="fa fa-list"></i> &nbsp; <?php echo e(__('Follow Up List')); ?></h3>
              </div>
              <div class="row m-2">
                <div class="col-12">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>SR.NO</th>
                        <th>Follow Up Date</th>
                        <th>Next Follow Up</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(!empty($remark)): ?>
                        <?php $i=1; ?>
                        <?php $__currentLoopData = $remark; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php
                            $lines = explode("\n", $item->remark);
                            $response = $lines[0] ?? '';
                            $status = isset($lines[1]) ? str_replace('Status: ', '', $lines[1]) : '';
                            $nextFollowUp = isset($lines[2]) ? str_replace('Next Follow Up: ', '', $lines[2]) : '';
                          ?>
                          <tr>
                            <td><?php echo e($i++); ?></td>
                            <td><?php echo e($item->date ? date('d-m-Y', strtotime($item->date)) : ''); ?></td>
                            <td><?php echo e($nextFollowUp ? date('d-m-Y', strtotime($nextFollowUp)) : ''); ?></td>
                            <td>
                              <span class="badge 
                                <?php if($status=='Active'): ?> bg-success 
                                <?php elseif($status=='Partially Closed'): ?> bg-warning text-dark 
                                <?php elseif($status=='Missed'): ?> bg-danger 
                                <?php elseif($status=='Closed'): ?> bg-secondary 
                                <?php else: ?> bg-light text-dark <?php endif; ?>">
                                <?php echo e($status); ?>

                              </span>
                            </td>
                            <td>
                                <a href="javascript:;"  
                                   data-id='<?php echo e($item->id); ?>' 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#FollowUpDeleteModal"  
                                   class="deleteFollowup btn btn-danger btn-xs" 
                                   title="Delete Follow Up">
                                   <i class="fa fa-trash"></i>
                                </a>
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
      
      <!-- Follow Up Delete Modal -->
<div class="modal" id="FollowUpDeleteModal">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #555b5beb;">
      <div class="modal-header">
        <h4 class="modal-title text-white">Delete Confirmation</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
      </div>

      <form action="<?php echo e(url('followupDelete')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <input type="hidden" id="followup_delete_id" name="delete_id">
          <h5 class="text-white">Are you sure you want to delete this Follow Up?</h5>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>

    </section>
</div>

<style>
    .fgfgfg{
        margin-left:60vh;
    }
    
}

</style>

<script>
    $(document).on('click', '.deleteFollowup', function(){
        var delete_id = $(this).data('id');
        $('#followup_delete_id').val(delete_id);
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/students/enquiry/studentRegistrationDetail.blade.php ENDPATH**/ ?>