<?php
  $classType = Helper::classType();
  //$getSection = Helper::getSection();
  $getCountry = Helper::getCountry();
  $getAllUsers = Helper::getAllUsers();
?>
 
<?php $__env->startSection('content'); ?>

<style>
    
    .padding_table thead tr{
    background: #002c54;
    color:white;
}
    
.padding_table th, .padding_table td{
     padding:5px;
     font-size:14px;
}
</style>

<div class="content-wrapper">

   <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">      
    <div class="card card-outline card-orange">
        <div class="card-header bg-primary">
        <h3 class="card-title"><i class="fa fa-bar-chart-o"></i> &nbsp;<?php echo e(__('  Daily Collection  ')); ?></h3>
        <div class="card-tools">
        <a href="<?php echo e(url('fee_dashboard')); ?>" class="btn btn-primary  btn-sm" title="Back"><i class="fa fa-arrow-left"></i><?php echo e(__('messages.Back')); ?></a>
        </div>
        
        </div>  
        
        
        <form id="quickForm" action="<?php echo e(url('fees/index')); ?>" method="post" >
                <?php echo csrf_field(); ?> 
                    <div class="row m-2">
                    <?php if(Session::get('role_id') == 1): ?>
                    <div class="col-md-1 ">
                        <div class="form-group">
                            <label><?php echo e(__('Users')); ?></label>
                            <select class="select2 form-control" id="user_id" name="user_id">
                            <option value='' >All Users </option>
                            <?php if(!empty($getAllUsers)): ?>
                            <?php $__currentLoopData = $getAllUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $getCoun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($getCoun->id ?? ''); ?>" <?php echo e(($getCoun->id == $serach['user_id']) ? 'selected' : ''); ?>><?php echo e($getCoun->first_name ?? ''); ?> <?php echo e($getCoun->last_name ?? ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            </select>
                        </div>
                    </div>   
                     <?php endif; ?>
                    	<div class="col-md-2">
                    		<div class="form-group">
                    			<label><?php echo e(__('fees.From Date')); ?></label>
                                    <input type="date" class="form-control " id="starting" name="starting" value="<?php echo e($serach['starting'] ?? ''); ?>">                 	    
                            </div>
                    	</div>
                    	<div class="col-md-2">
                            <div class="form-group ">
                                <label><?php echo e(__('fees.To Date')); ?></label>
                                    <input type="date" class="form-control " id="ending" name="ending" value="<?php echo e($serach['ending'] ?? ''); ?>">
                			</div> 
                        </div>
                    	
                                	
                        <div class="col-md-1 ">
                             <label for="" style="color: white;">Search</label>
                    	    <button type="submit" class="btn btn-primary" srtle=""><?php echo e(__('messages.Search')); ?></button>
                    	</div>
                    			
                    </div>
                </form>   
          

        
    	<div class="row m-2">
    	    <div class="col-md-12 head_table text-center"></div>
    	    </div>
    	<div class="row">
		     <div class="col-12" id="downloadLeaflet">	
		<div class="table-responsive">
        <table id="example11" class="table table-bordered table-striped dataTable dtr-inline padding_table">
          <thead>
          <tr role="row">
            <th><?php echo e(__('messages.Sr.No.')); ?></th>
            <th>Collect By</th>
            <th>Receipt No.</th>
            <th>Status</th>
            <th>Admission No.</th>
              <th>Class</th>
                <th><?php echo e(__('Student Name')); ?></th>
            <th><?php echo e(__('messages.Fathers Name')); ?></th>
            <th><?php echo e(__('Payment date')); ?></th>
            <th><?php echo e(__('Payment Modes')); ?></th>
            <th><?php echo e(__('Discount')); ?></th>
            <th><?php echo e(__('Fine')); ?></th>
            <th><?php echo e(__('Amount')); ?></th>
            <th><?php echo e(__('Total Amount')); ?></th>
         
          </thead>
         <tbody>
             
             <?php if(!empty($data)): ?>
             <?php
            $cash = 0;
            $cheque = 0;
            $net_banking = 0;
            $upi = 0;
           
             ?>
             <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
             
             
                <?php
             
                   if($receipt->payment_mode_id == 1){
                      $cash += $receipt->amount+$receipt->total_fine;
                   }elseif($receipt->payment_mode_id == 2){
                      $cheque += $receipt->amount+$receipt->total_fine;
                   }elseif($receipt->payment_mode_id == 3){
                     $net_banking += $receipt->amount+$receipt->total_fine;
                   }else{
                     $upi += $receipt->amount;
                   }
                   
                  // dd($receipt);
                   
                ?>

             
             <tr>
                 
                 <td><?php echo e($key+1); ?></td>
                 <td><?php echo e($receipt->users_first_name ?? ''); ?> <?php echo e($receipt->users_last_name ?? ''); ?></td>
                <td class='d-flex'>
                     <form action=<?php echo e(url('printFeesInvoice')); ?> method="post" target="_blank">
                         <?php echo csrf_field(); ?>
                         <button class="text-primary" type="submit" id="fees_details_invoice_id" style="border: none; background: transparent; border-bottom: 2px solid #1f2d3d;" name="fees_details_invoice_id" value="<?php echo e($receipt->id ?? ''); ?>"><?php echo e($receipt->invoice_no ?? ''); ?></button>
                     </form>
                 </td>
                 <td>
                    <?php if($receipt->status == 0): ?>
                        <span style="color: green;">Received</span>
                    <?php elseif($receipt->status == 1): ?>
                        <span style="color: red;">Pending</span>
                    <?php elseif($receipt->status == 2): ?>
                       <span style="color: red;">Cancelled</span>
                    <?php endif; ?>
                </td>
           
                 
                 <td><?php echo e($receipt->admissionNo ?? ''); ?></td>
                 <td><?php echo e($receipt->class_name ?? ''); ?></td>
                 <td><?php echo e($receipt->first_name ?? ''); ?> <?php echo e($receipt->last_name ?? ''); ?></td>
                
                 <td><?php echo e($receipt->father_name ?? ''); ?></td>
                 <td><?php echo e(date('d-m-Y', strtotime($receipt->payment_date ?? ''))); ?></td>
                 <td><?php echo e($receipt->payment_mode ?? ''); ?></td>
                 <td><?php echo e($receipt->discount ?? ''); ?></td>
                    <td><?php echo e($receipt->total_fine ?? ''); ?></td>
                 <td>
            
                    <?php if($receipt->status == 0  ): ?>
                        <span style="color: green;"><?php echo e($receipt->amount ?? 0); ?></span>
                    <?php elseif($receipt->status == 1): ?>
                        <span style="color: #fd7e14;"><?php echo e($receipt->amount ?? 0); ?></span>
                    <?php elseif($receipt->status == 2): ?>
                       <span style="color: red;"><?php echo e($receipt->amount ?? 0); ?></span>
                    <?php endif; ?>

                 </td>
                 <td>
            
                    <?php if($receipt->status == 0  ): ?>
                        <span style="color: green;"><?php echo e($receipt->amount+$receipt->total_fine ?? 0); ?></span>
                    <?php elseif($receipt->status == 1): ?>
                        <span style="color: #fd7e14;"><?php echo e($receipt->amount+$receipt->total_fine ?? 0); ?></span>
                    <?php elseif($receipt->status == 2): ?>
                       <span style="color: red;"><?php echo e($receipt->amount+$receipt->total_fine ?? 0); ?></span>
                    <?php endif; ?>

                 </td>
              
             </tr>
             
             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              
             <?php endif; ?>
         </tbody>
         <tfoot style="font-weight: bold;">
             <tr>
                 <td colspan="13" style="text-align: right;"> Modes</td>
                 <td>Total Amt </td>
             </tr>

             <tr>
                 
                 <td colspan="13" style="text-align: right;"> Cash </td>
               
                 <td> <?php echo e($cash ??  ''); ?> </td>
              
             </tr>
             <tr>
                 
                 <td colspan="13" style="text-align: right;"> Cheque</td>
               
                 <td> <?php echo e($cheque ??  ''); ?> </td>
              
             </tr>
             <tr>
                 
                 <td colspan="13" style="text-align: right;">Net Banking</td>
               
                 <td> <?php echo e($net_banking ??  ''); ?> </td>
              
             </tr>
             <tr>
                 
                 <td colspan="13" style="text-align: right;">UPI</td>
               
                 <td> <?php echo e($upi ??  ''); ?> </td>
              
             </tr>
             <tr>
                  
                 <td colspan="13" style="text-align: right;">Total Amount</td>
               
                 <td> <?php echo e($upi+$net_banking+$cheque+$cash ??  ''); ?> </td>
              
             </tr></tfoot>
        </table>
        </div>
        <div style="text-align:center;">
                 <button class="btn btn-sm btn-success my-2" id="printFile" style="width:170px;"><i class="fa-fa-print"aria-hidden="true"></i> Print</button>
            </div>
        </div>
        </div>
    </div>

    </div>
  </div>
</div>
</section>
</div>

<style>
    /* Table header style */
.padding_table thead tr {
    background: #002c54;
    color: white;
}

/* Table padding */
.padding_table th, 
.padding_table td {
    padding: 5px;
    font-size: 14px;
    white-space: nowrap; /* prevent text breaking */
}

/* Wrapper to enable responsive scroll */
.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 6px;
}

/* Search form spacing */
#quickForm .form-group label {
    font-size: 13px;
    font-weight: 600;
}

/* Button fix */
#quickForm button {
    margin-top: 28px;
}

/* Print button alignment */
#printFile {
    width: 170px;
}

/* --- Responsive Breakpoints --- */
@media (max-width: 992px) {
    .padding_table th, .padding_table td {
        font-size: 13px;
    }
    #quickForm .col-md-1, 
    #quickForm .col-md-2 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (max-width: 768px) {
    .padding_table th, .padding_table td {
        font-size: 12px;
        padding: 4px;
    }
    #quickForm .col-md-1, 
    #quickForm .col-md-2 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    #quickForm button {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .padding_table th, .padding_table td {
        font-size: 11px;
        padding: 3px;
    }
    .card-header h3 {
        font-size: 16px;
    }
    .card-header .btn {
        font-size: 12px;
        padding: 4px 8px;
    }
}

</style>


<script>
$(document).ready(function() {
    $("#printFile").click(function() {
        printContent();
    });
});

function printContent() {
    var styles = 'Daily Collection  ';

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
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/fees/fees_collect/index.blade.php ENDPATH**/ ?>