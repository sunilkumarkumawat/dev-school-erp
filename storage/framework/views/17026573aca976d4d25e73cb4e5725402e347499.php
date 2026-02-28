<?php
$getFeesGroup = Helper::getFeesGroup();
$classType = Helper::classType();
//dd();
?>

<?php $__env->startSection('content'); ?>

<style>
    .note_text{
        color: #dc3545;
        font-size: 16px;
        text-transform: capitalize;
        font-weight: 500;
    }
    
    .pointer_block{
        pointer-events:none;
        cursor:not-allowed;
    }
</style>

<div class="content-wrapper">
  <section class="content pt-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card card-outline card-orange">
            <div class="card-header bg-primary">
              <h3 class="card-title"><i class="fa fa-edit"></i> &nbsp;<?php echo e(__('fees.Edit Fees Master')); ?></h3>
              <div class="card-tools">

                <a href="<?php echo e(url('feesMasterAdd')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-eye"></i><?php echo e(__('messages.View')); ?> </a>
                <a href="<?php echo e(url('fee_dashboard')); ?>" class="btn btn-primary  btn-sm" ><i class="fa fa-arrow-left"></i> <?php echo e(__('Back')); ?> </a>
              </div>

            </div>
            <form id="quickForm" action="<?php echo e(url('feesMasterEdit')); ?>/<?php echo e($data[0]['id'] ?? ''); ?>" method="post">
              <?php echo csrf_field(); ?>


              <div class="row m-2">
                <div class="col-md-4">
                  <div class="form-group">
                    <label style="color:red;"><?php echo e(__('messages.Class')); ?>*</label>
                    <select class="form-control pointer_block <?php $__errorArgs = ['class_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> " id="class_type_id" name="class_type_id" readonly>
                      <option value=""><?php echo e(__('messages.Select')); ?></option>
                      <?php if(!empty($classType)): ?>
                      <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(( $type->id == $data[0]['class_type_id'] ? 'selected' : '' )); ?>><?php echo e($type->name ?? ''); ?></option>
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

                <div class="col-md-8">

                  <table class="_table" id="tableId">
                    <thead>
                      <tr>
                        <th colspan="3" style="color:red;"><?php echo e(__('fees.Fees Group')); ?>*</th>
                        <th colspan="4" style="color:red;"><?php echo e(__('messages.Amount')); ?>*</th>
                        <th colspan="4" style="color:red;"><?php echo e(__('Editable')); ?>*</th>
                        <th width="50px"></th>

                      </tr>
                    </thead>
                    <tbody id="table_body">
                      <?php if(!empty($data)): ?>
                      <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php
                        $isDeleteAllowed1 = DB::table('fees_detail')->where('fees_group_id',$item->fees_group_id)->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->whereNull('deleted_at')->count();
                        $isDeleteAllowed2 = DB::table('fees_assign_details')->where('class_type_id',$item['ClassTypes']['id'])->where('fees_group_id',$item->fees_group_id)->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->whereNull('deleted_at')->count();
                     ?>
                      <tr id="appendRow_0">

                        <td colspan="3">
                            <input type="hidden" value="<?php echo e($item->id ?? ''); ?>" name="fees_master_id[]">
                          <select class="form-control pointer_block <?php $__errorArgs = ['fees_group_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" readonly id="fees_group_id" name="fees_group_id[]">
                            <option value=""><?php echo e(__('messages.Select')); ?></option>
                            <?php if(!empty($getFeesGroup)): ?>
                            <?php $__currentLoopData = $getFeesGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(( $type->id == $item['fees_group_id'] ? 'selected' : '' )); ?>><?php echo e($type->name ?? ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                          </select>
                        </td>
                        <td colspan="3">
                          <input class="form-control amount_<?php echo e($item->id ?? ''); ?>  <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"     type="<?php echo e($item->editable == 1 ? 'hidden' : 'text'); ?>" name="amount[]" id="amount" placeholder="Amount" onkeypress="javascript:return isNumber(event)" value="<?php echo e($item['amount'] ?? ''); ?>">
                        </td>
                        
                        <td colspan="3">
                             <?php if(($isDeleteAllowed1+$isDeleteAllowed2) == 0): ?>
                            <input class="form-control change_box" data-amount_id="<?php echo e($item->id ?? ''); ?>" data-old_amount="<?php echo e($item->amount ?? '0'); ?>" type="checkbox" name="editable[]" id="editable" <?php echo e($item->editable == 1 ? 'checked' : ''); ?>>
                            <?php endif; ?>
                            <input type="hidden" name="editable_value[]" class="close_edited_value" id="editable_value" value="<?php echo e($item->editable ?? '0'); ?>">
                        </td>

                       <!-- <td colspan="3">
                          <div class="action_container">
                            <button type="button" class="btn btn-primary btn-xs addmoreprodtxtbx" id="clonebtn"><i class="fa fa-plus"></i></button>
                          </div>
                        </td>-->

                      </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </tbody>



                  </table>

                </div>


              </div>


              <div class="row m-2">
                <div class="col-md-12 text-center">
                  <button type="submit" class="btn btn-primary"><?php echo e(__('messages.Update')); ?></button><br><br>
                </div>
                
                <div class="col-md-12">
                    <p class="note_text">
                        <b>Note :</b> You can't modify the fees group amount until it is no longer in use.
                    </p>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
  </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    count = 0;
    $(".removeprodtxtbx").eq(0).css("display", "none");
    $(document).on("click", "#clonebtn", function() {
      count++;
      var newRow = '<tr id="appendRow_' + count + '">'
        + '<td colspan="3">'
        + '<select class="form-control select2" id="fees_group_id" name="fees_group_id[]">'
        + '<option value=""><?php echo e(__("messages.Select")); ?></option>'
        + '<?php if(!empty($getFeesGroup)): ?>'
        + '<?php $__currentLoopData = $getFeesGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>'
        + '<option value="<?php echo e($type->id ?? ""); ?>"><?php echo e($type->name ?? ""); ?></option>'
        + '<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>'
        + '<?php endif; ?>'
        + '</select>'
        + '</td>'
        + '<td colspan="3">'
        + '<input class="form-control " type="text" name="amount[]" id="amount" placeholder="Amount" onkeypress="javascript:return isNumber(event)">'
        + '</td>'
        +'<td colspan="3">'
        +'<input class="form-control change_box" type="checkbox" name="editable[]">'
        +'<input type="hidden" name="editable_value[]" class="close_edited_value" value="0">'
        +'</td>'
        + '<td colspan="3">'
        + '<div class="action_container">'
        + '<button type="button" class="btn btn-primary btn-xs addmoreprodtxtbx" id="clonebtn"><i class="fa fa-plus"></i></button>'
        + '<button type="button" class="btn btn-danger btn-xs removeprodtxtbx" id="removerow"><i class="fa fa-trash"></i></button>'
        + '</div>'
        + '</td>'
        + '</tr>';

      $('#table_body').append(newRow);
      $(".removeprodtxtbx").eq(count).css("display", "block");
      $(".addmoreprodtxtbx").eq(count).css("display", "none");
    });

    $(document).on("click", "#removerow", function() {
      $(this).parents('tr').remove();
      count--;
      window.calculateSum(); // Assuming you have a function named "calculateSum" defined elsewhere.
    });
  });
</script>

<script>
$(document).ready(function(){
   $(document).on('click','.change_box',function(){
       var amount_id = $(this).data('amount_id');
       var old_amount = $(this).data('old_amount');
    //   alert(amount_id);
       if($(this).prop('checked')){
           $(this).siblings('input').val(1);
            $('.amount_' + amount_id).val(0);
            $('.amount_' + amount_id).attr('type','hidden');
       }else{
           $(this).siblings('input').val(0);
           $('.amount_' + amount_id).val(old_amount);
           $('.amount_' + amount_id).attr('type','text');
       }
   }); 
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/fees/fees_master/feesMasterEdit.blade.php ENDPATH**/ ?>