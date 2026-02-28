 
 <script type="text/javascript" src="<?php echo e(URL::asset('public/assets/toastr_message/js/jquery_3.5.1_jquery.min.js')); ?>"></script>
  
 <link rel="stylesheet" href="<?php echo e(asset('public/assets/toastr_message/css/toastr.min.css')); ?>" />
  <script type="text/javascript" src="<?php echo e(URL::asset('public/assets/toastr_message/js/toastr.min.js')); ?>"></script>
 
 <script>
   <?php if(Session::has('message')): ?>
   toastr.options =
   {
       "closeButton" : true,
       "progressBar" : true
   }
           toastr.success("<?php echo e(session('message')); ?>");
   <?php endif; ?>
 
   <?php if(Session::has('error')): ?>
   toastr.options =
   {
       "closeButton" : true,
       "progressBar" : true
   }
           toastr.error("<?php echo e(session('error')); ?>");
   <?php endif; ?>
 
   <?php if(Session::has('info')): ?>
   toastr.options =
   {
       "closeButton" : true,
       "progressBar" : true
   }
           toastr.info("<?php echo e(session('info')); ?>");
   <?php endif; ?>
 
   <?php if(Session::has('warning')): ?>
   toastr.options =
   {
       "closeButton" : true,
       "progressBar" : true
   }
           toastr.warning("<?php echo e(session('warning')); ?>");
   <?php endif; ?>
 </script><?php /**PATH /home/rusofterp/public_html/dev/resources/views/layout/message.blade.php ENDPATH**/ ?>