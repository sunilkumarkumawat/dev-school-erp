 
<?php $__env->startSection('content'); ?>
<?php
$home_count = Helper::getCount('homeworks','id','count');
?>
                                                                    
<div class="content-wrapper">
    <div class="panel panel-primary">
        <div class="container-fluid panel-heading">
            <div class="row">
                <div class="col-sm-8">
                <h3 class="m-2">Homework Management</h3>
                </div>
                <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right">
                <li class="pr-2"><a href="<?php echo e(url('dashboard')); ?>" class="btn btn-primary  btn-xs"><i class="fa fa-home"></i> Home</a></li>
                <li class="pl-2"><a href="<?php echo e(url('master_dashboard')); ?>" class="btn btn-primary  btn-xs"><i class="fa fa-arrow-left"></i> Back</a></li>
                </ol>
                </div>
            </div>
        <hr class="bg-primary" style="margin-top:-12px;">
        </div>
    </div>

   <div class="card-body"> 
        <div class="row">
            <div class="col-md-3">
                <a href="<?php echo e(url('homework/add')); ?>"  class="small-box-footer">
                <div class="small-box bg-success">
                <div class="inner">
                    <h4>Add H/W </h4>

                    <p>Enter</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <div class="text-center small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></div>
                </div></a>
            </div>    
            
            <div class="col-md-3">
                <a href="<?php echo e(url('homework/index')); ?>"  class="small-box-footer">
                <div class="small-box bg-warning">
                <div class="inner">
                    <h4>View H/W</h4>

                    <h4><?php echo e($home_count ?? '0'); ?></h4>
                </div>
                <div class="icon">     
                    <i class="ion ion-stats-bars"></i>
                </div>
               <div class="text-center small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></div>
                </div></a>
           </div>
        </div>
    </div>
</div>


  
       

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/home_work/dashboard.blade.php ENDPATH**/ ?>