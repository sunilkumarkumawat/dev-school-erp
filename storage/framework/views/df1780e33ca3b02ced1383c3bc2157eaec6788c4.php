<?php
$getUser = Helper::getUser();
?>


<?php $__env->startSection('title', 'School Desk'); ?>
<?php $__env->startSection('page_title', 'SCHOOL DESK'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>

<?php $__env->startSection('content'); ?>
<section class="download-page">

  <div class="download-box">
     
                        	<div id="log">
                                <?php echo e($data->description ?? ''); ?>

                            </div>
                        	<div  id="divMain"></div>          
          
  </div>

</section>

<script>
    var support = (function() {
        if (!window.DOMParser) return false;
        var parser = new DOMParser();
        try {
            parser.parseFromString('x', 'text/html');
        } catch (err) {
            return false;
        }
        return true;
    })();

    var textToHTML = function(str) {

        // check for DOMParser support
        if (support) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(str, 'text/html');
            return doc.body.innerHTML;
        }

        // Otherwise, create div and append HTML
        var dom = document.createElement('div');
        dom.innerHTML = str;
        return dom;

    };

    var myValue9 = document.getElementById("log").innerText;

    document.getElementById("divMain").innerHTML = textToHTML(myValue9);

    document.getElementById("log").innerText="";
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/school_desk.blade.php ENDPATH**/ ?>