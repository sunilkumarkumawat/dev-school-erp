<?php
  $classType = Helper::classType();
  $getsubject = Helper::getSubject();
  $date = date('Y-m-d');
?>
 
<?php $__env->startSection('content'); ?>

<div class="content-wrapper">

	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
                       						    
							<h3 class="card-title"><i class="nav-icon fas fa fa-leanpub"></i> &nbsp;<?php echo e(__(' View Exam Term')); ?></h3>
					
							<div class="card-tools"> 
							    <a href="<?php echo e(url('add/exam_term')); ?>" class="btn btn-primary  btn-sm <?php echo e(Helper::permissioncheck(8)->add ? '' : 'd-none'); ?>"><i class="fa fa-plus"></i><?php echo e(__('common.Add')); ?> </a> 
							    <a href="<?php echo e(url('examination_dashboard')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><?php echo e(__('common.Back')); ?>  </a> 
                            </div>
						</div>
						<div class="card-body">

                    <form id="quickForm" action="<?php echo e(url('view/exam')); ?>" method="post" >
                        <?php echo csrf_field(); ?> 
                    <div class="row">

            		<div class="col-md-5">
            			<div class="form-group">
            				<label><?php echo e(__('common.Search By Keywords')); ?></label>
            				<input type="text" class="form-control" id="name" name="name" placeholder="<?php echo e(__('common.Search By Keywords')); ?>" value="<?php echo e($search['name'] ?? ''); ?>">
            		    </div>
            		</div>                     	
                        <div class="col-md-1 ">
                             <label for="" class="text-white"><?php echo e(__('common.Search')); ?></label>
                    	    <button type="submit" class="btn btn-primary"><?php echo e(__('common.Search')); ?></button>
                    	</div>
                    			
                    </div>
                </form> 
                
							<table id="example1" class="table table-bordered table-striped  dataTable">
								<thead>
									<tr role="row">
										<th><?php echo e(__('common.SR.NO')); ?></th>
										<th><?php echo e(__('Exam Term')); ?> </th>
										<th><?php echo e(__('common.Action')); ?></th>
								</thead>
								<tbody> 
								<?php if(!empty($data)): ?> 
    								<?php 
    								    $i=1 
    								?> 
								<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								  
									<tr>
									
										<td><?php echo e($i++); ?></td>
										<td><?php echo e($item['name'] ?? ''); ?></td>

							           <td>
                                            <a href="<?php echo e(url('edit/exam_term')); ?>/<?php echo e($item->id); ?>" class="btn btn-primary  btn-xs ml-3 tooltip1 <?php echo e(Helper::permissioncheck(8)->edit ? '' : 'd-none'); ?>" title1="Edit Exam Term"><i class="fa fa-edit"></i></a> 
											<a href="javascript:;" data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-danger  btn-xs ml-3 tooltip1 <?php echo e(Helper::permissioncheck(8)->delete ? '' : 'd-none'); ?>" title1="Delete Exam Term"><i class="fa fa-trash-o"></i></a> 
										</td>
                                   </tr>
										
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
								<?php endif; ?> 
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>




<!-- The Modal -->
<div class="modal" id="Modal_id">
	<div class="modal-dialog">
		<div class="modal-content" style="background: #555b5beb;">
			<div class="modal-header">
				<h4 class="modal-title text-white"><?php echo e(__('common.Delete Confirmation')); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
			<input type="hidden" id="exam_id1" >
			<form action="<?php echo e(url('delete/exam_term')); ?>" method="post"> 
			    <?php echo csrf_field(); ?>
				<div class="modal-body">
					<input type=text id="delete_id" name=delete_id>
					<h5 class="text-white"><?php echo e(__('common.Are you sure you want to delete')); ?> ?</h5> </div>
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











</script>
<script>
$(document).on('click', ".startExam", function () {
        toastr.error('You Cannot Attempt This Exam Now !');        
});
$(document).on('click', ".oldData", function () {
        toastr.error('You Already Attempted This Exam !');        
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/examination/offline_exam/exam_term/view.blade.php ENDPATH**/ ?>