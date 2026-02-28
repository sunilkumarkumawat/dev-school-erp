<?php
$getUser = Helper::getUser();
?>

<?php $__env->startSection('title', 'Download Center'); ?>
<?php $__env->startSection('page_title', 'DOWNLOAD CENTER'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
<section class="common-page">
 <div class="common-box m-2 border-0">

      
            <div class="dashboard-grid mt-3">
                                            <?php if(!empty($data)): ?>
                                    <?php
                                       $i=1;
                                       $assignment=0;
                                       $OtherDownloads=0;
                                       $StudyMaterial=0;
                                       $Syllbus=0;
                                    ?>
                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <?php if($item->content_type =="Assignments"): ?>
                                        <!--<?php echo e($assignment++); ?>-->
                                     <?php endif; ?>
                                       <?php if($item->content_type =="Other Downloads"): ?>
                                        <!--<?php echo e($OtherDownloads++); ?>-->
                                     <?php endif; ?>
                                      <?php if($item->content_type =="Study Material"): ?>
                                        <!--<?php echo e($StudyMaterial++); ?>-->
                                     <?php endif; ?>
                                      <?php if($item->content_type =="Syllabus"): ?>
                                        <!--<?php echo e($Syllbus++); ?>-->
                                     <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
           
                <a href="<?php echo e(url('studentAssignments')); ?>"  class="dash-card bg-primary p-2">
               
                    <div class="inner">
                        <h6>Assignments</h6>
                        <h6><?php echo e($assignment); ?></h6>
                    </div>
                    <div class="icon">     
                        <i class="fa fa-clipboard"></i>
                    </div>
                   
                </a>
          
           
          
                <a href="<?php echo e(url('student_study_material')); ?>"  class="dash-card bg-success p-2">
               
                    <div class="inner">
                        <h6>Study Materials</h6>
                        <h6><?php echo e($StudyMaterial++); ?></h6>
                    </div>
                    <div class="icon">     
                        <i class="fa fa-sitemap"></i>
                    </div>
                   
                </a>
         
           
            
                <a href="<?php echo e(url('student_syllabus')); ?>"  class="dash-card bg-danger p-2">
               
                    <div class="inner">
                        <h6>Syllabus</h6>
                        <h6><?php echo e($Syllbus++); ?></h6>
                    </div>
                    <div class="icon">     
                        <i class="fa fa-book"></i>
                    </div>
                   
               </a>
           
           
            
                <a href="<?php echo e(url('student_other_downloads')); ?>"  class="dash-card bg-warning p-2">
              
                    <div class="inner">
                        <h6>Other Downloads</h6>
                        <h6><?php echo e($OtherDownloads++); ?></h6>
                    </div>
                    <div class="icon">     
                        <i class="fa fa-cloud-download"></i>
                    </div>
                   
                </a>
           
        </div>
        </div>
   
</section>
  

  
       

<?php $__env->stopSection(); ?>


<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/download_center/download_center.blade.php ENDPATH**/ ?>