<?php
  $classType = Helper::classType();
  $getsubject = Helper::getSubject();;
?>
 
<?php $__env->startSection('content'); ?>
<div class="content-wrapper">

	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
							<h3 class="card-title"><i class="fa fa-check-square-o"></i> &nbsp;<?php echo e(__('examination.View Questions')); ?></h3>
							<div class="card-tools"> 
							    <a href="<?php echo e(url('add/question')); ?>" class="btn btn-primary  btn-sm <?php echo e(Helper::permissioncheck(8)->add ? '' : 'd-none'); ?>"><i class="fa fa-plus"></i><?php echo e(__('common.Add')); ?>  </a> 
                                <a href="<?php echo e(url('examination_dashboard')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><?php echo e(__('common.Back')); ?>  </a> 
                            </div>
						</div>
						<div class="card-body">
						    
                    <form id="quickForm" action="<?php echo e(url('view/question')); ?>" method="post" >
                        <?php echo csrf_field(); ?> 
                    <div class="row">

                        <div class="col-md-2">
                    		<div class="form-group">
                    			<label><?php echo e(__('examination.Subject')); ?></label>
                    			<select class="select2 form-control" id="subject_id" name="subject_id">
                    			<option value=""><?php echo e(__('common.Select')); ?></option>
                                 <?php if(!empty($getsubject)): ?> 
                                      <?php $__currentLoopData = $getsubject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                         <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id == $search['subject_id']) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  <?php endif; ?>
                                </select>
                    	    </div>
                    	</div>
                    	<div class="col-md-2">
                    		<div class="form-group">
                    			<label><?php echo e(__('examination.Question Type')); ?></label>
                    				<select class="select2 form-control" id="question_type_id" name="question_type_id">
                    			   <option value=""><?php echo e(__('common.Select')); ?></option>
                    			   <option value="1" <?php echo e((1== $search['question_type_id']) ? 'selected' : ''); ?>>Objective</option>
                    			   <option value="2" <?php echo e((2 == $search['question_type_id']) ? 'selected' : ''); ?>>Descriptive</option>
                                </select>
                    	    </div>
                    	</div>

            		<div class="col-md-4">
            			<div class="form-group">
            				<label><?php echo e(__('common.Search By Keywords')); ?></label>
            				<input type="text" class="form-control" id="name" name="name" placeholder="<?php echo e(__('common.Ex. Name, Mobile, Email, Aadhaar etc.')); ?>" value="<?php echo e($search['name'] ?? ''); ?>">
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
										<th><?php echo e(__('examination.Q. Id')); ?></th>
										<th><?php echo e(__('common.Subject')); ?></th>
										<th><?php echo e(__('examination.Question Type')); ?> </th>
										<th><?php echo e(__('examination.Question')); ?></th>
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
										<td><?php echo e($item['subject_name'] ?? ''); ?></td>
										<td>
										    <?php if($item['question_type_id'] == 1): ?>
										        Objective
										    <?php else: ?>
										        Descriptive
										    <?php endif; ?>
										</td>
										<td><?php echo e($item['name'] ?? ''); ?></td>
							            <td class="d-flex align-items-center">
                                                <a data-question='<?php echo e($item->name ?? ''); ?>'  
                                                    data-subject_id='<?php echo e($item['Subject']->name ?? ''); ?>' data-class_type_id='<?php echo e($item['ClassType']->name ?? ''); ?>' 
                                                    data-opt1='<?php echo e($item->ans1 ?? ''); ?>'
                                                    data-opt2='<?php echo e($item->ans2 ?? ''); ?>' data-opt3='<?php echo e($item->ans3 ?? ''); ?>'
                                                    data-opt4='<?php echo e($item->ans4 ?? ''); ?>' data-correct_ans='<?php echo e($item->correct_ans ?? ''); ?>' 
                                                    class="btn btn-success questionDetail btn-xs <?php echo e(Helper::permissioncheck(8)->view ? '' : 'd-none'); ?>" title="View Question"><i class="fa fa-eye"></i></a> 
                                                <a href="<?php echo e(url('edit/question')); ?>/<?php echo e($item->id); ?>" class="btn btn-primary  btn-xs ml-3 <?php echo e(Helper::permissioncheck(8)->edit ? '' : 'd-none'); ?>" title="Edit Question"><i class="fa fa-edit"></i></a> 
											    <a href="javascript:;" data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-danger  btn-xs ml-3 <?php echo e(Helper::permissioncheck(8)->delete ? '' : 'd-none'); ?>" title="Delete Question"><i class="fa fa-trash-o"></i></a> 
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



<div class="modal" id="myModal">
    <div class="modal-dialog modal-xl" >
      <div class="modal-content card-outline card-orange">
        <div class="modal-header">
            <h4 class="text-center" style="width:100%;"><?php echo e(__('examination.Question Details')); ?></h4>   
                <button type="button" id="closeModal"class="close" data-bs-dismiss="modal" aria-hidden="true">x</button>
        </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><b>Class :</b> <span id="subject_id1"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><b><?php echo e(__('examination.Question Type')); ?> :</b> <?php echo e(__('examination.Objective')); ?> </p>
                    </div><br>
                    <div class="col-md-12 border-bottom" id="question1">
                        <p><b><?php echo e(__('examination.Question')); ?> :</b> <span id="question"></span></p>
                    </div><br>    
                    <div class="col-md-12" id="option1">
                        <p><b><?php echo e(__('examination.Option')); ?> A :</b> <span id="ans1"></span></p>
                    </div>       
                    <div class="col-md-12" id="option2">
                        <p><b><?php echo e(__('examination.Option')); ?> B :</b> <span id="ans2"></span></p>
                    </div>      
                    <div class="col-md-12" id="option3">
                        <p><b><?php echo e(__('examination.Option')); ?> C :</b> <span id="ans3"></span></p>
                    </div>       
                    <div class="col-md-12" id="option4">
                        <p><b><?php echo e(__('examination.Option')); ?> D :</b> <span id="ans4"></span></p>
                    </div>                         
                </div>

            </div>
        
            <div class="modal-footer">
              <button class="btn btn-danger" id="closeModal"class="close" data-bs-dismiss="modal">Close</button>
            </div>
      </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal" id="Modal_id">
	<div class="modal-dialog">
		<div class="modal-content" style="background: #555b5beb;">
			<div class="modal-header">
				<h4 class="modal-title text-white"><?php echo e(__('messages.Delete Confirmation')); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
			<form action="<?php echo e(url('delete/question')); ?>" method="post"> 
			    <?php echo csrf_field(); ?>
				<div class="modal-body">
					<input type=hidden id="delete_id" name=delete_id>
					<h5 class="text-white"><?php echo e(__('messages.Are you sure you want to delete')); ?> ?</h5> </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal"><?php echo e(__('messages.Close')); ?></button>
					<button type="submit" class="btn btn-danger waves-effect waves-light"><?php echo e(__('messages.Delete')); ?></button>
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

$('.questionDetail').click(function() {
    $('#myModal').modal('toggle');
    
var question = $(this).data('question');
var class_type_id = $(this).data('class_type_id');
var subject_id1 = $(this).data('subject_id');
var answer1 = $(this).data('opt1');
var answer2 = $(this).data('opt2');
var answer3 = $(this).data('opt3');
var answer4 = $(this).data('opt4');
var correct_ans = $(this).data('correct_ans');

$('#question').html(question);
$('#subject_id1').html(subject_id1);
$('#ans1').html(answer1);
$('#ans2').html(answer2);
$('#ans3').html(answer3);
$('#ans4').html(answer4);

/*toastr.error(answer1);
toastr.error(answer2);
toastr.error(answer3);
toastr.error(answer4);*/
/*if(correct_ans == "0"){
    $('#ans1').addClass("bg-success");
    $('#ans2').removeClass("bg-success");
    $('#ans3').removeClass("bg-success");
    $('#ans4').removeClass("bg-success");
}else if(correct_ans == "1"){
    $('#ans2').addClass("bg-success");
    $('#ans1').removeClass("bg-success");
    $('#ans3').removeClass("bg-success");
    $('#ans4').removeClass("bg-success");    
}else if(correct_ans == "2"){
    $('#ans3').addClass("bg-success");
    $('#ans1').removeClass("bg-success");
    $('#ans2').removeClass("bg-success");
    $('#ans4').removeClass("bg-success");    
}else{
    $('#ans4').addClass("bg-success");
    $('#ans1').removeClass("bg-success");
    $('#ans2').removeClass("bg-success");
    $('#ans3').removeClass("bg-success");    
}*/

});

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/examination/offline_exam/question_bank/view.blade.php ENDPATH**/ ?>