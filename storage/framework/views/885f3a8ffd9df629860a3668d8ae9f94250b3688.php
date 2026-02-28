 <?php
  $getSetting = Helper::getSetting();
  $sidebarData = DB::table('students_sidebar')->whereNull('deleted_at')->orderBy('id', 'ASC')->get();
 ?>
 <style>
     #sidebar ul {
    padding-bottom: 70px;
}
 </style>
<nav id="sidebar" class="app-sidebar">
  <div class="sidebar-header px-3 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <img src="<?php echo e(env('IMAGE_SHOW_PATH').'/setting/left_logo/'.$getSetting['left_logo'] ?? ''); ?>" alt="Logo" width="40px" class="me-2">
      <h6 class="m-0 text-white fw-bold"><?php echo e($getSetting->name); ?></h6>
    </div>
    <button id="closeSidebar" class="btn btn-link text-white p-0">
      <i class="bi bi-x-lg fs-5"></i>
    </button>
  </div>

  <ul class="list-unstyled mt-3">
    <?php if(!empty($sidebarData)): ?>
        <?php $__currentLoopData = $sidebarData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <a href="<?php echo e(url($item->url)); ?>" class="<?php echo e(url($item->url)  == URL::current() ? 'active' : ""); ?>">
                    <i class="fas <?php echo e($item->ican ?? ''); ?> me-2"></i>
                        <?php echo e($item->name ?? ''); ?>

                </a>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <li><a href="javascript:void(0)" id="sidebarThemeToggle" class="theme-toggle"> <i class="bi bi-moon me-2"></i><span>Dark Mode</span></a></li>
    <li><a href="<?php echo e(url('profileStudent')); ?>" class="<?php echo e(url('profileStudent')  == URL::current() ? 'active' : ""); ?>"> <i class="fa fa-user me-2" aria-hidden="true"></i><span>Profile</span></a></li>
   <li>
    <a href="javascript:void(0);" id="logoutBtn" style="color:red;">
        <i class="fa fa-sign-out me-2"></i> Logout
    </a>
</li>
  </ul>
</nav>

<script>
document.getElementById("logoutBtn").addEventListener("click", function () {

   
    if(document.getElementById("closeSidebar")){
        document.getElementById("closeSidebar").click();
    }

    
    setTimeout(() => {

       
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to logout?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?php echo e(url('logout')); ?>";
            }
        });

    }, 300); 
});
</script>

<?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/layout/sidebar.blade.php ENDPATH**/ ?>