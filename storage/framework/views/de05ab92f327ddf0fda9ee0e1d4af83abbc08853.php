<?php
$classType = Helper::classType();
$getSetting=Helper::getSetting();
         $session=DB::table('sessions')->where('id',Session::get('session_id'))->whereNull('deleted_at')->first();

?>

<?php $__env->startSection('content'); ?>



<div class="content-wrapper">
  <section class="content pt-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card card-outline card-orange">
            <div class="card-header bg-primary flex_items_toggel">
              <h3 class="card-title"><i class="fa fa-address-book-o"></i> &nbsp;<?php echo e(__('Students Category Wise Report ')); ?></h3>
              <div class="card-tools">
                <a href="<?php echo e(url('studentsDashboard')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><span class="Display_none_mobile"><?php echo e(__('common.Back')); ?> </span></a>
              </div>

            </div>
        <hr class="m-0 ml-2 mr-2">
            <div class="row m-2" >
              <div class="col-12" id="downloadLeaflet">
             <table class="header" style="width:100%;"> 
                <tr>
                              
                     <?php if(Session::get('branch_id') == 1): ?>
                        <td colspan="12" class="logo" style="text-align: center;"> 
                            <table style="width:100%;">
                                  <tr>
                                       <td style="width: 30%;text-align: start;">
                                         &nbsp &nbsp <img  src="<?php echo e(env('IMAGE_SHOW_PATH').'/setting/left_logo/'.$getSetting['left_logo']); ?>" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'/default/rukmani_logo.png'); ?>'" style="width:100px;height: 70px;"/>
                                       </td>
                                         <td style="text-align:start;">
                                                <span style="font-size:40px;font-weight:bold;"><?php echo e($getSetting['name'] ?? ''); ?></span>
                                         </td>
                                  </tr>
                            </table>
                        </td>
        
                              <?php else: ?>
                              <td colspan="12" class="logo" style="text-align: center;width: 70%;"> 
                                        <img src="<?php echo e(env('IMAGE_SHOW_PATH').'/default/'); ?>Senior.jpg" style="width: 88%;height: 76px;margin-top: 4px;">
                              </td>
                  <?php endif; ?> 
            </tr>
        </table>
                            <h3 style="text-align: center;margin: 14px;">Category Wise  Report Session :-<?php echo e($session->from_year ?? ''); ?><?php echo e("-"); ?><?php echo e($session->to_year ?? ''); ?></h3>
                            <div class="table-responsive">
                <table id="example11" class="table table-bordered  dataTable  nowrap"  border="1" cellspacing="0" cellpadding="5" style="border: 1px solid black;margin-left: -10px;" >
  <thead>
    <tr>
      <th class="center">S.No.</th>
      <th>Class Name</th>
      <?php
      $categories = array("GENERAL", "OBC", "ST", "SC", "BC", "SBC");
      ?>
	     <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
           <th class="center" colspan="3"><?php echo e($cat ?? ''); ?> Enrollment</th>
      	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
      <th class="center" colspan="3">Total Enrollment</th> 
    </tr>
    <tr>
      <th></th>
      <th></th>
       <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <th> Boys</th>
              <th>Girls</th>
              <th> Total</th>
      	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
     
   
      <th class="center" ><b>Total Boys</b></th>
      <th class="center" ><b>Total Girls</b></th>
      <th class="center" ><b>G Total </b></th>
    </tr>
  </thead>
  <tbody>
      <?php if(!empty($classType)): ?>
       <?php
                               $TotalGirls = 0;
      $TotalBoys = 0;
                       
      $i=1;
     
      ?>
                      <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                              <td class="center"><?php echo e($i++); ?></td>
                              <td><?php echo e($type->name ?? ''); ?></td>
                             <?php
                                                      $TotalGirls = 0;
      $TotalBoys = 0;
                             ?>
                             
                             
                               <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                               <?php
                               

                               $Boys = DB::table('admissions')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('class_type_id',$type->id)->where('gender_id',1)->where('category',$cat)->whereNull('deleted_at')->where('status',1)->count();
                               $Girls = DB::table('admissions')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('class_type_id',$type->id)->where('gender_id',2)->where('category',$cat)->whereNull('deleted_at')->where('status',1)->count();
                              $TotalBoys += $Boys;
                              $TotalGirls += $Girls;
                               ?>
                                      <td><?php echo e($Boys ?? ''); ?></td>
                                      <td><?php echo e($Girls ?? ''); ?></td>
                                      <td><?php echo e($Boys+$Girls); ?></td>
                              	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                              <td class="center" ><b><?php echo e($TotalBoys ?? ''); ?></b></td>
                              <td class="center" ><b><?php echo e($TotalGirls ?? ''); ?></b></td>
                              <td class="center" ><b><?php echo e($TotalBoys+$TotalGirls); ?></b></td>
                            </tr>

  
 
    
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     <?php endif; ?>
                        </tr>
                        </tbody>
                        <tfoot>
                       <tr>
                              <td></td>
                              <td class="center"><b>Total</b></td>
                              <?php
                              $Boys_Total1 = 0;
                              $Girls_Total2 = 0;
                              ?>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                               <?php
                               

                               $Boys_Total = DB::table('admissions')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('gender_id',1)->where('status',1)->where('category',$cat1)->whereNull('deleted_at')->count();
                               $Girls_Total = DB::table('admissions')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('gender_id',2)->where('status',1)->where('category',$cat1)->whereNull('deleted_at')->count();
                             
                               ?>
                                      <td><b><?php echo e($Boys_Total ?? ''); ?></b></td>
                                      <td><b><?php echo e($Girls_Total ?? ''); ?></b></td>
                                      <td><b><?php echo e($Boys_Total+$Girls_Total); ?></b></td>
                                      <?php
                                      $Boys_Total1 +=$Boys_Total;
                                      $Girls_Total2 +=$Girls_Total;
                                      ?>
                              	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                             <td class="center"><b><?php echo e($Boys_Total1 ?? ''); ?></b></td>
                              <td class="center"><b><?php echo e($Girls_Total2 ?? ''); ?></b></td>
                              <td class="center"><b><?php echo e($Boys_Total1+$Girls_Total2); ?></b></td>
                            </tr>
                            
                    </tfoot>
                        
                </table>
                </div>
              </div>
            </div>
            <div style="text-align:center;">
                 <button class="btn btn-sm btn-primary my-2" id="printFile" style="width:170px;"><i class="fa-fa-print"aria-hidden="true"></i> Print</button>
            </div>
             
            </div>
            </div>
            </div>
            </div>
            </section>
</div>



<style>
    .table-responsive {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

</style>


<style>
    table.dataTable > thead > tr > th:not(.sorting_disabled), table.dataTable > thead > tr > td:not(.sorting_disabled) {
  padding-right: 0px;
 
  padding-top: 10px;
  padding-bottom: 10px;
}
.table-bordered td, .table-bordered th {
  border: 1px solid black;
    border-bottom-width: 1px;
    border-left-width: 1px;
     padding-top: 10px;
  padding-bottom: 10px;
}
@page  {
    margin: 2cm;
    
}

@media  screen {
   .table-bordered td, .table-bordered th {
  border: 2px solid #000;
  
}
}
.center {
  text-align: center;
}
</style>
<script>
$(document).ready(function() {
    $("#printFile").click(function() {
        printContent();
    });
});

function printContent() {
    var styles = '';

    $(document).ready(function() {
        $('style, link[rel="stylesheet"]').each(function() {
            styles += $(this).prop('outerHTML');
        });
        var content = $("#downloadLeaflet").html();
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Student Data</title>' + styles + '</head><body style="margin-left:10px;">');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
            
        setTimeout(function() {
            printWindow.print();
            printWindow.close();
        }, 500);
    });
}

</script>

            <?php $__env->stopSection(); ?>
            

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/students/report/category_wise_report.blade.php ENDPATH**/ ?>