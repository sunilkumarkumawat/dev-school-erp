<?php
 $notificationCount = DB::table('notifications')->where('admission_id', Session::get('id'))->where('show_status', 1)->count();
?>

<footer class="app-footer d-flex justify-content-around align-items-center" style="padding: 0px 5px 0px 10px;">
    
    <a href="<?php echo e(url('dashboard')); ?>" class="footer-link <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>">
   <img src="<?php echo e(asset('public/assets/student_login/img/icons/footer_home.gif')); ?>" alt="" width="30px">
    <span><b>Home</b></span>
  </a>
    
  <a href="<?php echo e(url('profileStudent')); ?>" class="footer-link <?php echo e(request()->is('profileStudent') ? 'active' : ''); ?>">
     <img src="<?php echo e(asset('public/assets/student_login/img/icons/profile_edit.gif')); ?>" alt="" width="30px" height="30px">
    <span><b>Profile</b></span>
  </a>
  <a href="#" id="hardRefreshBtn" class="footer-link">
   <img src="<?php echo e(asset('public/assets/student_login/img/icons/refresh-149.png')); ?>" alt="" width="30px" style="background-color: transparent;">
    <span><b>Refresh</b></span>
  </a>
  
  <a href="<?php echo e(url('AttendanceView_student')); ?>" class="footer-link <?php echo e(request()->is('AttendanceView_student') ? 'active' : ''); ?>" >
    <img src="<?php echo e(asset('public/assets/student_login/img/icons/footer_attadance.gif')); ?>" alt="" width="30px">
    <span><b>Attendance</b></span>
  </a>
  <a  href="<?php echo e(url('notificationFatchStudent')); ?>" class="footer-link <?php echo e(request()->is('notificationFatchStudent') ? 'active' : ''); ?>" role="button" >
                  <div class="centerd_text_icon">
                    <div class="ms-auto d-flex align-items-center text-white mr-2" style="font-size:20px; position: relative;">
                        <img src="<?php echo e(asset('public/assets/student_login/img/icons/footer_alert.gif')); ?>" alt="" width="30px">
                        <?php if($notificationCount > 0): ?>
                            <span style="position: absolute;top: -2px;right: 0px;background: red;color: white;border-radius: 50%;padding: 0px 5px;font-size: 12px;">
                                <?php echo e($notificationCount); ?>

                            </span>
                        <?php endif; ?>
                       
                    </div>
                    </div>
            <span><b>Alert</b></span>
    </a>
</footer>
<?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/layout/footer.blade.php ENDPATH**/ ?>