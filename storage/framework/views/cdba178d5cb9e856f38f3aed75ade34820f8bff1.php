<?php if(!empty($data)): ?>
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $fees_assign_details = DB::table('fees_assign_details')->whereNull('deleted_at')->where('admission_id',$item->id)->get();
            $fees_group_ids = [];
            $fees_master_ids = [];
            if(!empty($fees_assign_details)){
                foreach($fees_assign_details as $fees){
                    $fees_group_ids[] = $fees->fees_group_id;
                }
            }
            
            $feesGroupData = [];
            if(count($fees_group_ids) != 0){
                $feesGroupData = DB::table('fees_group')->whereNull('deleted_at')->whereIn('id',$fees_group_ids)->get();
            }
            
            
        ?>
        <tr style="background-color:<?php echo e($key%2 == 0 ? '#c8c8c8' : ''); ?>">
               <td><input type='checkbox' name="admissionIds[]" class="student_select_checkbox" value="<?php echo e($item->id ?? ''); ?>" /></td>
                 <td style="line-height: 1.2; padding: 2px 4px;">
                    <?php echo e($item->first_name ?? ''); ?> <?php echo e($item->last_name ?? ''); ?><br>
                    <small style="font-size: 12px;">Address: <?php echo e($item->address ?? ''); ?></small>
                </td>               <td><?php echo e($item->admissionNo ?? ''); ?></td>
               <td><?php echo e($item->mobile ?? ''); ?></td>
               <td><?php echo e($item->father_name ?? ''); ?></td>
               <td>
                   <?php if(count($feesGroupData) != 0): ?>
                      <?php $__currentLoopData = $feesGroupData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fees_group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($fees_group->name ?? ''); ?> <br>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                   <?php endif; ?>
               </td>
        </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
<tr class="text-center">
    <td colspan="12">
        <p class="note_text"><i class="fa fa-warning"></i><br> Either the fees have been paid or there is no data</p>
    </td>
</tr>
<?php endif; ?>        
    
   
    
   
    <?php /**PATH /home/rusofterp/public_html/dev/resources/views/fees/modification/admissionList.blade.php ENDPATH**/ ?>