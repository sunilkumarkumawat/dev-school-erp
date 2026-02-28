

 
<?php $__env->startSection('content'); ?>

<div class="content-wrapper">
   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-orange">

                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fa fa-cogs"></i> &nbsp;<?php echo e(('System Student Field')); ?> </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(url('AddStudentField')); ?>" class="btn btn-primary  btn-sm <?php echo e(Helper::permissioncheck(17)->add ? '' : 'd-none'); ?>" title="Add User"><i class="fa fa-plus"></i> <?php echo e(('Custom Field Add')); ?> </a>
                        <a href="<?php echo e(url('settings_dashboard')); ?>" class="btn btn-primary  btn-sm" title="Back"><i class="fa fa-arrow-left"></i><?php echo e(__('messages.Back')); ?> </a> 
                    </div>
                </div> 
                <div class="card-body">
                <table id="example11" class="table table-bordered table-striped dataTable dtr-inline ">
                  <thead class="bg-primary">
                  <tr role="row">
                            <th><?php echo e(__('Fields Name')); ?></th>
                            <th><?php echo e(__('Active')); ?></th>
                            <th><?php echo e(__('Required')); ?></th>
                            <th><?php echo e(__('Student Login Edit Permission')); ?></th>
                            <th><?php echo e(__('Order')); ?></th>
                     </tr> 
                  </thead>
                  <tbody>
                      <?php if(!empty($data)): ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><i class="fa fa-arrow-circle-right"></i> <?php echo e($item['field_label'] ?? ''); ?></td>

                            
                            <td>  
                                <label class="switch1">
                                    <input type="checkbox" class="toggle-status" 
                                           data-id="<?php echo e($item->id); ?>"  
                                           data-inputname="status"
                                           data-label="<?php echo e($item->field_label); ?> Status"
                                           <?php echo e($item->status == 0 ? 'checked' : ''); ?>>
                                    <span class="slider1">
                                        <span class="on">✔ </span>
                                        <span class="off">✖ </span>
                                    </span>
                                </label>
                           </td>

                           
                           <td>  
                                <label class="switch1">
                                    <input type="checkbox" class="toggle-status" 
                                           data-id="<?php echo e($item->id); ?>"  
                                           data-inputname="required"
                                           data-label="<?php echo e($item->field_label); ?> Required"
                                           <?php echo e($item->required == 0 ? 'checked' : ''); ?>>
                                    <span class="slider1">
                                        <span class="on">✔ </span>
                                        <span class="off">✖ </span>
                                    </span>
                                </label>
                            </td>

                            
                            <td>  
                                <label class="switch1">
                                    <input type="checkbox" class="toggle-status" 
                                           data-id="<?php echo e($item->id); ?>"  
                                           data-inputname="stu_edit_perm"
                                           data-label="<?php echo e($item->field_label); ?> Permission"
                                           <?php echo e($item->stu_edit_perm == 0 ? 'checked' : ''); ?>>
                                    <span class="slider1">
                                        <span class="on">✔ </span>
                                        <span class="off">✖ </span>
                                    </span>
                                </label>
                            </td>

                            
                            <td>
                                <input type="number" class="form-control field-order-input w-50" 
                                       data-id="<?php echo e($item->id); ?>" 
                                       value="<?php echo e($item['field_order'] ?? ''); ?>" />
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
    </section>
</div>

<script>
 $(document).on('change', '.toggle-status', function(){
    let checkbox = $(this);
    let student_field_id = checkbox.data('id');
    let inputName = checkbox.data('inputname'); 
    let fieldLabel = checkbox.data('label');
    let status = checkbox.is(':checked') ? '0' : '1';

    $.ajax({
        url: "<?php echo e(url('SystemStudentFieldStatusUpdate')); ?>",
        type: "POST",
        data: {
            _token: "<?php echo e(csrf_token()); ?>",
            student_field_id,
            inputName,
            status
        },
        success: function(res){
            if(res.success){
                if(status === '0'){
                    toastr.success(fieldLabel + ' Activated Successfully!');
                } else {
                    toastr.warning(fieldLabel + ' Deactivated Successfully!');
                }
            } else {
                checkbox.prop('checked', !checkbox.prop('checked'));
                toastr.error('Something went wrong!');
            }
        }
    });
});


 $(document).on('change', '.field-order-input', function(){
    let input = $(this);
    let student_field_id = input.data('id');
    let field_order = input.val();

    $.ajax({
        url: "<?php echo e(url('SystemStudentFieldOrderUpdate')); ?>",
        type: "POST",
        data: {
            _token: "<?php echo e(csrf_token()); ?>",
            student_field_id,
            field_order
        },
        success: function(res){
            if(res.success){
                toastr.success('Field order updated successfully!');
               // setTimeout(() => location.reload(), 800);
            } else {
                toastr.error(res.message || 'Failed to update order');
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/settings/SystemStudentField/index.blade.php ENDPATH**/ ?>