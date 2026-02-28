<?php
$getUser = Helper::getUser();
?>

<?php $__env->startSection('title', 'Notifications'); ?>
<?php $__env->startSection('page_title', 'NOTIFICATIONS'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
<section class="common-page">
 <div class="common-box m-2 border-0">

                    <div class="card shadow-lg border-0 rounded-3" style="overflow:hidden;">
                        
                        <div class="card-header d-flex justify-content-between align-items-center" 
                             style="background:var(--primary-dark); color:var(--common-white);">
                            <h3 class="card-title mb-0" style="font-weight:600;" >
                                <i class="fa fa-bell"></i> &nbsp; Notifications
                            </h3>
                        </div>

                        
                        <div class="card-body" style=" min-height:300px;">
                            <?php if($notifications->isEmpty()): ?>
                                <p class="text-center text-muted mt-3">No notifications found</p>
                            <?php else: ?>
                                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                       $url = url('notification_detail_stu/' . $n->id);
                                        $time = \Carbon\Carbon::parse($n->created_at)->diffForHumans();
                                    ?>

                                    <div class="d-flex align-items-start border-bottom pb-2 mb-2 position-relative" style="transition:0.3s;">
                                        <a href="<?php echo e($url); ?>" class="text-decoration-none flex-grow-1" style="color:var(--theme-color);">
                                            <div class="d-flex align-items-start">
                                                <div class="position-relative me-2 pr-2 pl-2 mt-1">
                                                    
                                                    <?php if($n->message_seen == 0): ?>
                                                        <span class="position-absolute" style="width:8px; height:8px; background:red; border-radius:50%; top:4px; left:-8px;"></span>
                                                    <?php endif; ?>
                                                    <i class="fa fa-bell text-primary" style="font-size:18px;"></i>
                                                </div>
                                                <div>
                                                    <strong style="margin: 0px 40px 0px 0px;"><?php echo e($n->title); ?></strong><br>
                                                    <small class="" style="color: var(--text-secondary);"><i class="fa fa-clock"></i> <?php echo e($time); ?></small>
                                                </div>
                                            </div>
                                        </a>

                                        
                                        <form method="GET" style="position:absolute; right:0; top:0;">
                                            <input type="hidden" name="hide_id" value="<?php echo e($n->id); ?>">
                                            <button type="submit" class="btn btn-sm text-secondary" style="border:none; background:none;">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/notification/notification_list.blade.php ENDPATH**/ ?>