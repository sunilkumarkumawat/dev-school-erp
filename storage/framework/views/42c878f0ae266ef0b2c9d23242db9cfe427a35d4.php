  <?php
  $getSetting = Helper::getSetting();
  ?>

<style>
    /* New Css Start */

       .top_brand_section {
           display: flex;
           align-items: center;
           border-bottom: 2px solid white;
           margin-bottom: 20px;
           padding-bottom: 20px;
           padding-top: 20px;
           padding-left: 10px;
           padding-right: 10px;
           position: relative;
           height: 70px;
       }

       .brand_img {
           width: 40px;
           height: 40px;
       }

       .brand_title {
           margin-bottom: 0px;
           width: 200px;
           font-size: 14px;
           font-weight: 600;
           color: white;
           margin-left: 10px;
       }
       @media  screen and (max-width:600px) {
      .elevation-4{
       background-color:#0094ae !important;
       
      }
      .nav-sidebar .nav-item>.nav-link {
          color:black;
          font-weight:bold;
      }
     
      .brand_title{
          color: black;
          font-weight:bold;
          text-align:center;
      }

      /*ul li:hover{*/
      /*    background-color:black !important;*/
      /*    color:white !important;*/
      /*}*/
    }
   
</style>
  
<aside class="main-sidebar bg-light  elevation-4">
  
<a href="<?php echo e(url('/')); ?>">
  <div class="top_brand_section">
       <img src="<?php echo e(env('IMAGE_SHOW_PATH').'/setting/left_logo/'.$getSetting['left_logo'] ?? ''); ?>" alt="" class="brand_img">
       <p class="brand_title" style="display:none;"><?php echo e($getSetting->name); ?></p>
   </div>
</a>

    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">


<?php
$sidebarData = DB::table('students_sidebar')->whereNull('deleted_at')->orderBy('id', 'ASC')->get();
?>

<?php if(!empty($sidebarData)): ?>

<?php $__currentLoopData = $sidebarData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
 <li class="nav-item menu-open ">
                    <a href="<?php echo e(url($item->url)); ?><?php echo e($item->url == 'student_fees_details' ? '/'.Session::get('id') : ''); ?>" class="nav-link <?php echo e(url($item->url)  == URL::current() ? 'active' : ""); ?>">
                    <i class="nav-icon fas <?php echo e($item->ican ?? ''); ?>"></i>
                    <p><?php echo e($item->name ?? ''); ?></p>
                    </a>
                </li>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
                <li class="nav-item menu-open ">
                    <a href="#" class="nav-link " onclick="confirmLogout(event)">
                    <i class="nav-icon fa fa-sign-out"></i>
                    <p>Log Out</p>
                    </a>
                </li>
            
            </ul>
        </nav>
    </div>
</aside>
 <?php /**PATH /home/rusofterp/public_html/dev/resources/views/layout/student_sidebar.blade.php ENDPATH**/ ?>