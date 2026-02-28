<?php
$getSetting = Helper::getSetting();
$getUser = Helper::getUser();
$siblingsList = Helper::getSiblings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"> 
  <title><?php echo e($getSetting->name ?? ''); ?></title>
  <style>
 body {
    max-width: 450px !important;
    width: 100% !important;
    margin: 0 auto !important;
    overflow-x: hidden !important;
    position: relative !important;
}
.app-header,
.app-footer ,#switchBottomSheet,#photoBottomSheet,.modal{
    max-width: 450px !important;
    margin: 0 auto !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
    right: auto !important;
}
* {
    max-width: 100% !important;
}  
  </style>
 
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script>
    (function() {
      const savedTheme = localStorage.getItem("theme") || "dark";
      document.documentElement.setAttribute("data-theme", savedTheme);
    })();
  </script>
  <link rel="stylesheet" href="<?php echo e(asset('public/assets/student_login/css/style.css')); ?>">
</head>
<body>
   <div id="pageLoader" class="page-loader">
    <div class="loader-spinner"></div>
  </div>

   <div class="switch-bottom-sheet" id="switchBottomSheet">
    <div class="switch-sheet-content">
      <div class="sheet-handle"></div>
      <h5 class="sheet-title">Switch User</h5>
      <ul class="switch-option-list">

            <li data-value="<?php echo e(Session::get('id')); ?>" class="active" style="color: greenyellow;font-weight:bolder;">
                <?php echo e(Session::get('first_name')); ?> - <?php echo e($getUser['ClassTypes']['name']); ?>

            </li> 
            <?php $__currentLoopData = $siblingsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sib): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li data-value="<?php echo e($sib->id); ?>"
                    class="<?php echo e($sib->id == Session::get('id') ? 'active' : ''); ?>" onclick="window.location.href='<?php echo e(url('sibling/login/'.$sib->id)); ?>'">
                    <?php echo e($sib->first_name); ?> - <?php echo e($sib['ClassTypes']['name']); ?>

                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>

      <div class="cancel-btn" id="cancelSwitch">Cancel</div>
    </div>
  </div>
  
  
    <?php echo $__env->make('student_login.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('student_login.layout.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('student_login.layout.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <main class="main-content">
    <?php echo $__env->yieldContent('content'); ?>
  </main> 
   <?php echo $__env->make('student_login.layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->yieldContent('scripts'); ?>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <script src="<?php echo e(URL::asset('public/assets/student_login/js/main.js')); ?>"></script>
   <script src="<?php echo e(URL::asset('public/assets/student_login/js/sweetAlert.js')); ?>"></script>
</body>
</html>
<?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/layout/app.blade.php ENDPATH**/ ?>