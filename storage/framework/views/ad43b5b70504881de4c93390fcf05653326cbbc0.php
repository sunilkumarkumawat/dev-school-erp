<?php

//$task = Helper::task();


?>

<style>
    .listing_flex marquee{
        width:60%;
    }
    .listing_flex form{
        width:100%;
    }
    .listing_flex{
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>


  <?php if(!empty($task)): ?>
  
  
               <?php $__currentLoopData = $task; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                 <?php
                $fdate = $item->created_at;
                $tdate = date('Y-m-d H:i:s');
                $datetime1 = new DateTime($fdate);
                $datetime2 = new DateTime($tdate);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');
                
                ?>
                                <li class="listing_flex" id="_<?php echo e($item->id ?? ''); ?>" type="submit">
                                <form id="myForm" action="<?php echo e(url('to_do_assign_view')); ?>" method="post">
                    <?php echo csrf_field(); ?>     
                    <input type="hidden" name="to_do_list_id" value="<?php echo e($item->id ?? ''); ?>">
                                    <span class="text"><?php echo e($item->name ?? ''); ?></span>
                                    <small class="badge badge-secondary"><?php echo e($item->first_name ?? ''); ?></small>
                                    <small class="badge badge-<?php echo e($days<=2 ? 'success' : ''); ?><?php echo e($days>=3 && $days<=6  ? 'primary' : ''); ?><?php echo e($days>=7  ? 'danger' : ''); ?>"><i class="fa fa-clock"></i> <?php echo e($days); ?>d</small>
                                    <small class="badge badge-<?php echo e($item->priority == 'low' ? 'success' : ''); ?><?php echo e($item->priority == 'medium'  ? 'primary' : ''); ?><?php echo e($item->priority == 'high' ? 'danger' : ''); ?>"><i class="fa fa-clock"></i>
                                    <?php echo e($item->priority == 'low' ? 'Low' : ''); ?><?php echo e($item->priority == 'medium'  ? 'Medium' : ''); ?><?php echo e($item->priority == 'high' ? 'High' : ''); ?>

                                    </small>
                                   
                                    <small class="badge badge-<?php echo e($item->status == 0 ? 'danger' : ''); ?><?php echo e($item->status == 1  ? 'warning' : ''); ?><?php echo e($item->status == 2 ? 'info' : ''); ?><?php echo e($item->status == 3 ? 'success' : ''); ?>"><i class="fa fa-clock"></i>
                                    <?php echo e($item->status == 0 ? 'Pending' : ''); ?><?php echo e($item->status == 1  ? 'Working' : ''); ?><?php echo e($item->status == 2 ? 'Completed' : ''); ?><?php echo e($item->status == 3 ? 'Verified' : ''); ?>

                                    </small>
                                    <marquee> <?php echo e($item->description ?? ''); ?></marquee>        
                                     <div class="tools mr-2">
                                     <button type="submit" class=" btn btn-success btn-xs ml-3"> <i class="fa fa-eye"></i></button>
                                    </form>
                                    <?php if(Session::get("role_id") == 1): ?>
                                        <i class="fa fa-trash-o task_delete" data-id="<?php echo e($item->id ?? ''); ?>"></i>
                                    </div>
                                    <?php endif; ?>
                                </li>
                                
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
<?php /**PATH /home/rusofterp/public_html/dev/resources/views/task_list.blade.php ENDPATH**/ ?>