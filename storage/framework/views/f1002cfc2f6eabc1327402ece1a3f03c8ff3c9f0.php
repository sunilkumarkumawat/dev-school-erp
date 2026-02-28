 
<?php $__env->startSection('content'); ?>

<div class="content-wrapper" >

    <section class="content pt-3">
        <div class="container-fluid">
          
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fa fa-whatsapp"></i> &nbsp;<?php echo e(__('master.Message Template Dashboard')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(url('master_dashboard')); ?>" class="btn btn-primary  btn-sm" title="Back"><i class="fa fa-arrow-left"></i><?php echo e(__('common.Back')); ?></a>
                    </div>
            
                </div>               
            </div>
            </div> 
        </div> 
            
        <div class="row">
           <div class="col-md-3">
               <a href="<?php echo e(url('messageType')); ?>" class="small-box-footer">
                <div class="small-box bg-primary">
                <div class="inner">
                    <h4><?php echo e(__('master.Message Type')); ?></h4>

                    <h4><?php echo e(\App\Models\Master\MessageType::countMessageType() ?? '0'); ?></h4>
                </div>
                <div class="icon">
                    <i class="fa fa-whatsapp"></i>
                </div>
                <div class="text-center small-box-footer"><?php echo e(__('common.More info')); ?> <i class="fa fa-arrow-circle-right"></i></div>
                </div></a>
            </div>
       
            <div class="col-md-3">
                <a href="<?php echo e(url('messageTemplate')); ?>" class="small-box-footer">
                    <div class="small-box bg-danger">
                        <div class="inner">
                        <h4><?php echo e(__('master.Message Template')); ?> </h4>
                        <h4><?php echo e(\App\Models\Master\MessageTemplate::countMessageTemplate() ?? '0'); ?></h4>
                        </div>
                <div class="icon">
                    <i class="fa fa-whatsapp"></i>
                </div>
                   <div class="text-center small-box-footer"><?php echo e(__('common.More info')); ?> <i class="fa fa-arrow-circle-right"></i></div>
                </div></a>
                </div>

              
        </div>
            
            
        </div>
    </section>
    
</div>
       

<?php $__env->stopSection(); ?>
  

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/message/dashboard.blade.php ENDPATH**/ ?>