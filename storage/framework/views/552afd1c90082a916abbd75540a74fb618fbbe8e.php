<?php
    $getHostel = Helper::getHostel();
    $getRole = Helper::getUsers();
?>
 
<?php $__env->startSection('content'); ?>
<style>
    .top{
        margin-top: -12px;
    }
</style>
<div class="content-wrapper">

	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
							<h3 class="card-title"><i class="fa fa-credit-card"></i> &nbsp;<?php echo e(__('expense.View Expense')); ?></h3>
							
							
							<div class="card-tools"> 
							    <a href="<?php echo e(url('expenseAdd')); ?>" class="btn btn-primary btn-sm <?php echo e(Helper::permissioncheck(16)->add ? '' : 'd-none'); ?>"><i class="fa fa-plus"></i><?php echo e(__('common.Add')); ?></a> 
							</div>
							
						</div>
						<div class="card-body">
                        <form id="quickForm" action="<?php echo e(url('expenseView')); ?>" method="post">
                            <?php echo csrf_field(); ?> 
                            <div class="row m-2">
             
                                <div class="col-md-2">
                                    <label><b><?php echo e(__('User Name')); ?></b></label>
                                    <select class="form-control" name="role">
                                        <option value="">Select</option>
                                        <?php $__currentLoopData = $getRole; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id); ?>" <?php echo e(($search['role'] ?? '') == $item->id ? 'selected' : ''); ?>>
                                                <?php echo e($item->first_name); ?> <?php echo e($item->last_name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                
                               <div class="col-6 col-md-2">
                                    <label><b><?php echo e(__('Category')); ?></b></label>
                                    <select class="form-control" name="category">
                                        <option value="">Select</option>
                                      <option value="Salary" <?php echo e(($search->category ?? '') == 'Salary' ? 'selected' : ''); ?>>Salary</option>

                                        <option value="Loan Payments" <?php echo e(($search->category ?? '') == 'Loan Payments' ? 'selected' : ''); ?>>Loan Payments</option>
                                        
                                        <option value="Mobile Bill & Recharge" <?php echo e(($search->category ?? '') == 'Mobile Bill & Recharge' ? 'selected' : ''); ?>>Mobile Bill &amp; Recharge</option>
                                        
                                        <option value="School Building Maintenance" <?php echo e(($search->category ?? '') == 'School Building Maintenance' ? 'selected' : ''); ?>>School Building Maintenance</option>
                                        
                                        <option value="Computer & Electronics" <?php echo e(($search->category ?? '') == 'Computer & Electronics' ? 'selected' : ''); ?>>Computer &amp; Electronics</option>
                                        
                                        <option value="Laboratory Expenses" <?php echo e(($search->category ?? '') == 'Laboratory Expenses' ? 'selected' : ''); ?>>Laboratory Expenses</option>
                                        
                                        <option value="Furniture Expense" <?php echo e(($search->category ?? '') == 'Furniture Expense' ? 'selected' : ''); ?>>Furniture Expense</option>
                                        
                                        <option value="Fuel & Gas" <?php echo e(($search->category ?? '') == 'Fuel & Gas' ? 'selected' : ''); ?>>Fuel &amp; Gas</option>
                                        
                                        <option value="Printing & Stationery Items" <?php echo e(($search->category ?? '') == 'Printing & Stationery Items' ? 'selected' : ''); ?>>Printing &amp; Stationery Items</option>
                                        
                                        <option value="Donations And Taxes" <?php echo e(($search->category ?? '') == 'Donations And Taxes' ? 'selected' : ''); ?>>Donations And Taxes</option>
                                        
                                        <option value="Electricity Bills" <?php echo e(($search->category ?? '') == 'Electricity Bills' ? 'selected' : ''); ?>>Electricity Bills</option>
                                        
                                        <option value="Internet Bills" <?php echo e(($search->category ?? '') == 'Internet Bills' ? 'selected' : ''); ?>>Internet Bills</option>
                                        
                                        <option value="Water Bills" <?php echo e(($search->category ?? '') == 'Water Bills' ? 'selected' : ''); ?>>Water Bills</option>
                                        
                                        <option value="Staff Welfare Expenses" <?php echo e(($search->category ?? '') == 'Staff Welfare Expenses' ? 'selected' : ''); ?>>Staff Welfare Expenses</option>
                                        
                                        <option value="Rent Expenses" <?php echo e(($search->category ?? '') == 'Rent Expenses' ? 'selected' : ''); ?>>Rent Expenses</option>
                                        
                                        <option value="Event Expenses" <?php echo e(($search->category ?? '') == 'Event Expenses' ? 'selected' : ''); ?>>Event Expenses</option>
                                        
                                        <option value="House Expenses" <?php echo e(($search->category ?? '') == 'House Expenses' ? 'selected' : ''); ?>>House Expenses</option>
                                        
                                        <option value="Maintenance" <?php echo e(($search->category ?? '') == 'Maintenance' ? 'selected' : ''); ?>>Maintenance</option>
                                        
                                        <option value="Insurance" <?php echo e(($search->category ?? '') == 'Insurance' ? 'selected' : ''); ?>>Insurance</option>
                                        
                                        <option value="Education & Tuition" <?php echo e(($search->category ?? '') == 'Education & Tuition' ? 'selected' : ''); ?>>Education &amp; Tuition</option>
                                        
                                        <option value="Sports Goods" <?php echo e(($search->category ?? '') == 'Sports Goods' ? 'selected' : ''); ?>>Sports Goods</option>
                                        
                                        <option value="Other Charges" <?php echo e(($search->category ?? '') == 'Other Charges' ? 'selected' : ''); ?>>Other Charges</option>

                                    </select>
                                </div>



                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label><?php echo e(__('expense.From Date')); ?></label>
                                        <input type="date" class="form-control" name="from_date" value="<?php echo e($search['from_date'] ?? ''); ?>">
                                    </div>
                                </div>
                        
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label><?php echo e(__('expense.To Date')); ?></label>
                                        <input type="date" class="form-control" name="to_date" value="<?php echo e($search['to_date'] ?? ''); ?>">
                                    </div>
                                </div>
                        
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo e(__('common.Search By Keywords')); ?></label>
                                        <input type="text" class="form-control" name="keyword" placeholder="Search by keyword"
                                               value="<?php echo e($search['keyword'] ?? ''); ?>">
                                    </div>
                                </div>
                        
                                <div class="col-md-1">
                                    <div class="Display_none_mobile">
                                        <label class="text-white">Search</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary"><?php echo e(__('common.Search')); ?></button>
                                </div>
                            </div>
                        </form>

                            <div class="col-md-12" id="">

                        <table id="example1" class="table table-bordered table-striped dataTable dtr-inline ">
                            <thead class="bg-primary">
                                <tr role="row">
                                    <th><?php echo e(__('common.SR.NO')); ?></th>
                                    <th>Invoice No</th>
                                    <th><?php echo e(__('Expense Head')); ?></th>
                                    <th><?php echo e(__('User Name')); ?></th>
                                    <th><?php echo e(__('common.Date')); ?></th>
                                    <th><?php echo e(__('expense.Quantity')); ?></th>
                                    <th><?php echo e(__('common.Amount')); ?></th>
                                    
                                    <th><?php echo e(__('common.Action')); ?></th>
                                   
                                </tr>
                            </thead>
                            <tbody>

                                <?php if(!empty($data)): ?>
                                <?php
                                    $i=1;
                                   $total = 0;
                                ?>

                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $expance = DB::table('expenses')->where('date',$item['date'])->get();
                                ?>
                               <tr>
                                    <td><?php echo e($i++); ?></td>
                                    <td><?php echo e($item['invoice_no'] ?? ''); ?></td>
                                    
                                    <?php
                                   
                                    $user_name = DB::table('users')->where('id',$item->user_id)->first();
                                    ?>
                                    <td><?php echo e($item['name']?? 'Not Mentioned'); ?> </td>
                                    <td><?php echo e($user_name->first_name ?? 'Not Mentioned'); ?> <?php echo e($user_name->last_name ?? ''); ?> </td>
                                    <td><?php echo e(date('d-m-Y', strtotime($item['date'])) ?? ''); ?></td>
                                    <td><?php echo e($item['quantity'] ?? ''); ?></td>
                                    <td id="<?php echo e($item['date'] ?? ''); ?>"><?php echo e($item['amount'] ?? ''); ?></td>
                                    
                                       <td>
                                            <a href="<?php echo e(url('expensePrint')); ?>/<?php echo e($item['invoice_no']); ?>" target="blank" class="btn btn-success btn-xs tooltip1 <?php echo e(Helper::permissioncheck(16)->print ? '' : 'd-none'); ?>" title1="Print Expense"><i class="fa fa-print"></i></a>
                                        
                                            <a href="<?php echo e(url('expenseEdit')); ?>/<?php echo e($item['invoice_no']); ?>" class="btn btn-primary btn-xs ml-1 tooltip1 <?php echo e(Helper::permissioncheck(16)->edit ? '' : 'd-none'); ?>" title1="Edit Expense"><i class="fa fa-edit"></i></a>
                                        
                                            <a href="javascript:;" data-id='<?php echo e($item['id'] ?? ''); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-danger btn-xs ml-1 tooltip1 <?php echo e(Helper::permissioncheck(16)->delete ? '' : 'd-none'); ?>" title1="Delete Expense"><i class="fa fa-trash-o"></i></a>
                                        </td>

                                   
                                </tr>
                                <?php
                                    $total += $item['amount'] ;
                                ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tfoot>
                                    <tr>
                                        <th class="text-white">Total</th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> <b><?php echo e(__('messages.Total Amount')); ?></b></th>
                                        <th> <b id="total_amt">₹ <?php echo e($total ?? ''); ?></b></th>
                                        <th></th>   
                                    </tr>    
                                </tfoot>
                              <?php endif; ?>
                            </tbody>
                        </table>
                            </div>
                        </div>                        
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!--<div class="modal" id="Modal_new">-->
<!--	<div class="modal-dialog">-->
<!--		<div class="modal-content" style="margin-left: -30%;width: 160%;">-->
<!--			<div class="modal-header">-->
<!--			    	<h3>Total Expances</h3>-->
<!--				<h4 class="modal-title text-white"></h4>-->
<!--				<button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>-->
<!--			</div>-->
		
<!--			    <div class="modal-body">-->
<!--			        <div class="row">-->
<!--			            <div class="col-md-12" id="appendTable"></div>-->
<!--			        </div>-->
<!--			    </div>-->

					  
<!--				<div class="modal-footer">-->
<!--					<button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal"><?php echo e(__('messages.Close')); ?></button>-->
<!--				</div>-->
		
<!--		</div>-->
<!--	</div>-->
<!--</div> -->
<!--</div> -->



















<!-- The Modal -->
<div class="modal" id="Modal_id">
	<div class="modal-dialog">
		<div class="modal-content" style="background: #555b5beb;">
			<div class="modal-header">
				<h4 class="modal-title text-white"><?php echo e(__('common.Delete Confirmation')); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
			<form action="<?php echo e(url('expenseDelete')); ?>" method="post"> 
			    <?php echo csrf_field(); ?>
				<div class="modal-body">
					<input type=hidden id="delete_id" name=delete_id>
					<h5 class="text-white"><?php echo e(__('common.Are you sure you want to delete')); ?>  ?</h5> </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal"><?php echo e(__('common.Close')); ?></button>
					<button type="submit" class="btn btn-danger waves-effect waves-light"><?php echo e(__('common.Delete')); ?></button>
				</div>
			</form>
		</div>
	</div>
</div> 


   
<script>
$('.deleteData').click(function() {
	var delete_id = $(this).data('id');
	$('#delete_id').val(delete_id);
});

// $(".expanseShow").click(function(){
//     var expanse = $(this).data('expanse');
//     var tablestart = "<table class='table table-bordered table-striped dataTable dtr-inline'><tr><th>Name</th><th>Name</th><th>Name</th><th>Name</th></tr>";
//     var tableend = "<tr><td></td><td></td><td><b>Total</b></td><td id='sumamt'></td></tr></table>";
//     var tr = "";
//     var sumamt = 0;
//     $.each( expanse, function( key, value ) {
//         tr = tr + "<tr><td>" + value.name + "</td><td>" + value.date + "</td><td>" + value.quantity + "</td><td>" + value.amount + "</td></tr>";
//         sumamt += parseInt(value.amount);
//     });
//     var table = tablestart + tr + tableend;
//     $("#appendTable").html(table);
//     $("#sumamt").html(sumamt);
// })
</script>


<?php $__env->stopSection(); ?>      
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/expense/view.blade.php ENDPATH**/ ?>