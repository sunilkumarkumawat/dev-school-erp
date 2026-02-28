 
<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page_title', 'Dashboard'); ?>
<?php $__env->startSection('page_sub', 'Welcome'); ?>
<?php $__env->startSection('content'); ?>

<?php
  $getSetting = Helper::getSetting();
?>
  
<div class="card text-center school-info-card" >
    <div class="d-flex justify-content-center  p-2">
        <img src="<?php echo e(asset('public/assets/student_login/img/logo.png')); ?>" alt="Logo" width="55px" class="me-2" style="border-radius:50%;">
        <div class="ml-2">
            <h6 class="m-0 fw-bold p-0"> <?php echo e(\Illuminate\Support\Str::limit($getSetting->name ?? 'Rukmani Software', 27, '...')); ?></h6>
            <p class="m-0 " style="text-align: center;"> <b>Email :</b> <?php echo e(\Illuminate\Support\Str::limit($getSetting->gmail ?? '', 30, '...')); ?></p>
            <p class="m-0 " style="text-align: center;"><b>Address :</b>  <?php echo e(\Illuminate\Support\Str::limit($getSetting->address ?? '', 30, '...')); ?></p>
        </div>
        
    </div>
    <div class="ml-2 quoto">
          “Learning Today, Leading Tomorrow”
         </div>
</div>
<div class="dashboard-grid mt-3">
  <?php
    $modules = [
     
      ['icon' => 'attendence.png', 'label' => 'ATTENDANCE', 'url' => 'AttendanceView_student'],
      ['icon' => 'homework.png', 'label' => 'HOMEWORK', 'url' => 'student_homework'],
      ['icon' => 'fees.png', 'label' => 'FEES', 'url' => 'fees_history'],
      ['icon' => 'exam_timetable.jpeg', 'label' => 'EXAM TIME TABLE', 'url' => 'student/result-card'],
      ['icon' => 'result.png', 'label' => 'EXAM RESULT', 'url' => 'student/result-card'],
      ['icon' => 'leave.png', 'label' => 'APPLY LEAVE', 'url' => 'applyLeaveStudent'],
      ['icon' => 'feedback.png', 'label' => 'COMPLAINT', 'url' => 'complaintAddStudent'],
      ['icon' => 'timetable.jpg', 'label' => 'DAILY CLASS TIMETABLE', 'url' => 'timetable'],
      ['icon' => 'circular.jpeg', 'label' => 'NOTICE  BOARD', 'url' => 'notice_board_student/0'],
      ['icon' => 'notes.png', 'label' => 'ACADEMIC NOTES', 'url' => 'student_study_material'],
      ['icon' => 'syllabus.png', 'label' => 'SYLLABUS', 'url' => 'student_syllabus'],
      ['icon' => 'download.jpeg', 'label' => 'DOWNLOAD CENTER', 'url' => 'download_center_student'],
    ];
  ?>

  <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(url($item['url'])); ?>" class="dash-card text-center animate">
      <img src="<?php echo e(asset('public/assets/student_login/img/icons/'.$item['icon'])); ?>" alt="<?php echo e($item['label']); ?>">
      <p><?php echo e($item['label']); ?></p>
    </a>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/dashboard.blade.php ENDPATH**/ ?>