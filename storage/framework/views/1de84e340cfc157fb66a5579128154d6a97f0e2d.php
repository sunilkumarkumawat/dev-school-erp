<?php if(count($data) != 0): ?>
 
    
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    
    <?php
    $feesAssignedDetails = DB::table('fees_assign_details')->where('admission_id',$item->admission_id)->whereNull('deleted_at')->get();
        $count = 0;
        $rowspan = count($feesAssignedDetails);
    ?>
    
     <?php if(!empty($feesAssignedDetails)): ?>
<?php $__currentLoopData = $feesAssignedDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
 
 
<?php

$feeGroupName = DB::table('fees_group')->where('id',$details->fees_group_id)->whereNull('deleted_at')->first();


$deletable = DB::table('fees_detail')->where('admission_id',$item->admission_id)->where('fees_group_id',$details->fees_group_id)->whereNull('deleted_at')->whereIn('status',[0,1])->sum('total_amount');
?>
 <tr style="background-color:<?php echo e($key%2 == 0 ? '#c8c8c8' : ''); ?>">
            <?php if($count < 1): ?>
     <td rowspan=<?php echo e($rowspan); ?> ><?php echo e($item->first_name ?? ''); ?> <?php echo e($item->last_name ?? ''); ?></td>
     <td rowspan=<?php echo e($rowspan); ?> ><?php echo e($item->admissionNo ?? ''); ?></td>
        
        <td rowspan=<?php echo e($rowspan); ?>><?php echo e($item->mobile); ?></td>
        <?php endif; ?>
       
        <td class='text-left'>
        <?php echo e($feeGroupName->name ?? ''); ?> = 
            
  <input type='text' name='fees_group_amount' data-pay_fees="<?php echo e($deletable ?? ''); ?>" class='fees_assign_detail w-50' data-old_value="<?php echo e($details->fees_group_amount ?? ''); ?>" data-detail_id='<?php echo e($details->id); ?>' value="<?php echo e($details->fees_group_amount ?? ''); ?>" />

        </td>
        <td class='text-left'>
          
            <?php if(!empty($deletable)): ?>
             
                <?php echo e($details->discount ?? ''); ?>

            <?php else: ?>
            
             <input type='text' name='discount' class='fees_assign_detail w-100' data-detail_id='<?php echo e($details->id); ?>' data-old_value='<?php echo e($details->discount ?? 0); ?>' value="<?php echo e($details->discount ?? ''); ?>" />
            <?php endif; ?>
         
        </td>
        <td class='text-left'>
          
            <?php if(!empty($deletable)): ?>
             
            <?php if(!empty($details->installment_due_date)): ?> <?php echo e(date('d-M-Y', strtotime($details->installment_due_date))); ?> <?php endif; ?>
            <?php else: ?>
            
             <input type='date' name='installment_due_date' class='fees_assign_detail' data-detail_id='<?php echo e($details->id); ?>' data-old_value='<?php echo e($details->installment_due_date ?? 0); ?>' value="<?php echo e($details->installment_due_date ?? ''); ?>" />
            <?php endif; ?>
         
        </td>
        <td class='text-left'>
          
            <?php if(!empty($deletable)): ?>
             
            <?php echo e($details->installment_fine ?? ''); ?>

            <?php else: ?>
            
             <input type='number' min="0" max="100" name='installment_fine' class='fees_assign_detail w-100' data-detail_id='<?php echo e($details->id); ?>' data-old_value='<?php echo e($details->installment_fine ?? 0); ?>' value="<?php echo e($details->installment_fine ?? 0); ?>" />
            <?php endif; ?>
         
        </td>
        <td class='text-left'>
        <input type="checkbox" name="fees_refund"  id="refund_fees_value_<?php echo e($details->id ?? ''); ?>" 
        <?php echo e($details->fees_refund == 'yes' ? 'checked' : ''); ?> onchange="updateRefundFees(this, <?php echo e($details->id ?? ''); ?>)"
         data-detail_id='<?php echo e($details->id); ?>' data-old_value="<?php echo e($details->fees_refund ?? ''); ?>"
          value="<?php echo e($details->fees_refund ?? 'no'); ?>">
        </td>
        <td>
           <i style='cursor:pointer' data-detail_id='<?php echo e($details->id); ?>' class="fa fa-times text-danger <?php echo e(!empty($deletable) ? 'd-none' : ''); ?> delete_assigned" aria-hidden="true"></i>
        </td>
      
    </tr>
    
    <?php
    $count++;
    ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php endif; ?>           

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
<tr class="text-center">
    <td colspan="12">
        <p class="note_text"><i class="fa fa-warning"></i><br> No Student Found </p>
    </td>
</tr>
<?php endif; ?>  <?php /**PATH /home/rusofterp/public_html/dev/resources/views/fees/modification/fees_modification.blade.php ENDPATH**/ ?>