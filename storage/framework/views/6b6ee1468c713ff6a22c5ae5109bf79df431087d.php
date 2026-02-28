<?php
$i=1
?>
<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr class="quickCollect" style="cursor:pointer; " data-admission_id ="<?php echo e($item['id']); ?>" data-ledger_no ="<?php echo e($item['ledger_no']); ?>">
    <td><?php echo e($item->ledger_no ?? 'NA'); ?></td>
    <td class="text-center"><?php echo e($item['admissionNo'] ?? ''); ?></td>
    <td><?php echo e($item['first_name'] ?? ''); ?> <?php echo e($item['last_name'] ?? ''); ?></td>
    <td><?php echo e($item['ClassTypes']['name'] ?? ''); ?></td>
    <td><?php echo e($item['father_name'] ?? ''); ?></td>
    <!--<td><?php echo e($item['mother_name'] ?? ''); ?></td>-->
    <td><?php echo e($item['mobile'] ?? ''); ?></td>
</tr>                                            
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php /**PATH /home/rusofterp/public_html/dev/resources/views/fees/fees_collect/studentSearchList.blade.php ENDPATH**/ ?>