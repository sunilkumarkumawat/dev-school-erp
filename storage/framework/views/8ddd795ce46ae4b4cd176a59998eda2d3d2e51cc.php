<?php
$classType = Helper::classType();
$homeworkReview = Helper::homeworkReview();

?>
 
<?php $__env->startSection('content'); ?>

<input type="hidden" id="session_id" value="<?php echo e(Session::get('role_id') ?? ''); ?>">
 <div class="content-wrapper">
    
     <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-md-12">   
    <div class="card card-outline card-orange">
         <div class="card-header bg-primary">
        <?php if(Session::get('role_id') == 3): ?>    
        <h3 class="card-title"><i class="fa fa-flask"></i> &nbsp;<?php echo e(__('homework.My Homework')); ?> </h3>
        <?php else: ?>
        <h3 class="card-title"><i class="fa fa-flask"></i> &nbsp;<?php echo e(__('homework.View Homework')); ?> </h3>
        <?php endif; ?>
            
            <div class="card-tools">
          <a href="<?php echo e(url('homework/add')); ?>" class="btn btn-primary  btn-sm  <?php echo e(Helper::permissioncheck(10)->add ? '' : 'd-none'); ?>"><i class="fa fa-plus"></i> <?php echo e(__('messages.Add')); ?> </a>
        
          </div>
            
            </div>        
            
        
         <form id="quickForm" action="<?php echo e(url('homework/index')); ?>" method="post" >
                        <?php echo csrf_field(); ?> 
                    <div class="row m-2">
                    
                    	           
                        <div class="col-md-2">
                    		<div class="form-group">
                    			<label><?php echo e(__('messages.Class')); ?></label>
                    			<select class="form-control select2" id="class_type_id" name="class_type_id" >
                    			<option value=""><?php echo e(__('messages.Select')); ?></option>
                                 <?php if(!empty($classType)): ?> 
                                      <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                         <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id == $search['class_type_id']) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  <?php endif; ?>
                                </select>
                    	    </div>
                    	</div>
                    	
                    

                    	<div class="col-md-2">
                    		<div class="form-group">
                    			<label><?php echo e(__('homework.Admission No')); ?></label>
                    				<input type="text" class="form-control" id="admissionNo" name="admissionNo" placeholder="<?php echo e(__('homework.Admission No')); ?>" value="<?php echo e($search['admissionNo'] ?? ''); ?>">
  
        		        </div>
        		    </div>    

            		<div class="col-md-4">
            			<div class="form-group">
            				<label><?php echo e(__('messages.Search By Keywords')); ?></label>
            				<input type="text" class="form-control" id="name" name="name" placeholder="<?php echo e(__('messages.Ex. Name, Mobile, Email, Aadhaar, Father/ Mother Name etc.')); ?>" value="<?php echo e($search['name'] ?? ''); ?>">
            		    </div>
            		</div>                     	
                        <div class="col-md-1 ">
                             <label class="text-white d-md-block d-none">Search</label>
                    	    <button type="submit" class="btn btn-primary" ><?php echo e(__('messages.Search')); ?></button>
                    	</div>
                   			
                    </div>
                </form>
        
             
    	<div class="row mb-2 m-2">
		    <div class="col-md-12">	

        <table id="example1" class="table table-bordered table-striped dataTable dtr-inline ">
          <thead>
          <tr role="row">
              <th><?php echo e(__('messages.Sr.No.')); ?></th>
                    <th><?php echo e(__('master.Title')); ?></th>
                    <th>Assigned by</th>
                    <th><?php echo e(__('messages.Class')); ?></th>
                    <th><?php echo e(__('messages.Subject')); ?></th>
                    <th><?php echo e(__('Homework Issue Date')); ?></th>
                    <th><?php echo e(__('homework.Submission Date')); ?></th>
                    <th><?php echo e(__('messages.Action')); ?></th>
          </thead>
              <?php if(!empty($data)): ?>
                <?php
                   $i=1
                ?>
               
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
                <?php
                
               
                    $userName = DB::table('users')->whereNull('deleted_at')->where('id',$item->user_id)->first();
                    
                    $roleName = DB::table('role')->whereNull('deleted_at')->where('id',$userName->role_id)->first();
                ?>
                <tr class=" <?php echo e(( 1 == $item['view_status'])   ?  : ''); ?> "> 
                    <td><?php echo e($i++); ?></td>
                    <td><?php echo e($item['title'] ??''); ?> </td>
                    <td><?php echo e($userName->first_name ??''); ?> <?php echo e($userName->last_name ??''); ?> (<?php echo e($roleName->name ?? ''); ?>)</td>
                    <td><?php echo e($item['ClassType']['name'] ??''); ?></td>
                    <td><?php echo e($item['Subject']['name'] ?? ''); ?></td>
                    <td><?php echo e(date('d-m-Y', strtotime($item['homework_issue_date'])) ?? ''); ?></td>
                    <td><?php echo e(date('d-m-Y', strtotime($item['submission_date'])) ?? ''); ?></td>
                    <td>
                        <button data-id='<?php echo e($item->id); ?>' data-homework_issue_date="<?php echo e(date('d-m-Y', strtotime($item['homework_issue_date'])) ?? ''); ?>" data-submission_date="<?php echo e(date('d-m-Y', strtotime($item['submission_date'])) ?? ''); ?>" 
                        data-title='<?php echo e($item->title); ?>' data-description='<?php echo e($item->description); ?>' data-content_file='<?php echo e(env('IMAGE_SHOW_PATH').'homework/'.$item['content_file']); ?>'
                        data-class='<?php echo e($item['ClassType']['name'] ??''); ?>' data-subject='<?php echo e($item['Subject']['name'] ?? ''); ?>'
                        data-create_teacher='<?php echo e($item['Teacher']['first_name'] ?? ''); ?> <?php echo e($item['Teacher']['last_name'] ?? ''); ?>'
                        class="btn btn-success viewHomework btn-xs tooltip1 <?php echo e(Helper::permissioncheck(10)->view ? '' : 'd-none'); ?>" title1="View Homework" ><i class="fa fa-eye"></i></button>
                        <?php if(Session::get('role_id') !== 3): ?>
                       <?php if($item->teacher_id == Session::get('teacher_id') || Session::get('role_id') == 1): ?>

                        <a href="<?php echo e(url('homework/details')); ?>/<?php echo e($item->id); ?>" class="btn btn-success btn-xs ml-3 tooltip1" title1=" Assignments" ><i class="fa fa-reorder"></i></a>  
                            <a href="<?php echo e(url('homework/edit',$item->id)); ?>" class="btn btn-success  btn-xs ml-3 tooltip1 <?php echo e(Helper::permissioncheck(10)->edit ? '' : 'd-none'); ?>" title1="Edit Homework" ><i class="fa fa-edit"></i></a>
                            <a href="javascript:;" data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-danger  btn-xs ml-3 tooltip1 <?php echo e(Helper::permissioncheck(10)->delete ? '' : 'd-none'); ?>" title1="Delete Homework"><i class="fa fa-trash-o"></i></a>
						    <?php endif; ?>	
						    
						     
                        <?php endif; ?>
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

            <!-- The Modal -->
            <div class="modal" id="uploadModal">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
            
                  <div class="modal-header">
                    <h4 class="modal-title"><?php echo e(__('homework.Homework Assignments')); ?></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
                  </div>
                <form action="<?php echo e(url('upload/homework')); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                  <div class="modal-body">
                      <input type="hidden" id="homework_id" name="homework_id" value="">
                    	<div class="col-md-12">
								<div class="form-group">
									<label style="color: red;"><?php echo e(__('messages.Message')); ?>*</label>
									<textarea class="form-control" id="message" name="message" placeholder="Type Message"></textarea>
							    </div>
						</div>
                    	<div class="col-md-12">
								<div class="form-group">
									<label style="color: red;"><?php echo e(__('messages.Attach Document')); ?>*</label>
									<input class="form-control" type="file" id="content_file" name="content_file[]" multiple> 
							    </div>
						</div>                       
                  </div>
                    <div class="modal-footer">
                            <button type="submit" class="btn btn-primary waves-effect waves-light uploadHomework"><?php echo e(__('messages.submit')); ?></button>
                    </div>
                    </form>
                </div>
              </div>
            </div>

            <!-- The Modal -->
            <div class="modal" id="Modal_id">
              <div class="modal-dialog">
                <div class="modal-content" style="background: #555b5beb;">
            
                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title text-white"><?php echo e(__('messages.Delete Confirmation')); ?></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
                  </div>
            
                  <!-- Modal body -->
                  <form action="<?php echo e(url('homework/delete')); ?>" method="post">
                          	 <?php echo csrf_field(); ?>
                  <div class="modal-body">
                          <input type=hidden id="delete_id" name=delete_id>
                          <h5 class="text-white"><?php echo e(__('messages.Are you sure you want to delete')); ?>  ?</h5>
                  </div>
                  <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal"><?php echo e(__('messages.Close')); ?></button>
                                <button type="submit" class="btn btn-danger waves-effect waves-light"><?php echo e(__('messages.Delete')); ?></button>
                     </div>
                   </form>
            
                </div>
              </div>
            </div>


          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" >
              <div class="modal-content">
                <div class="modal-header">
                        <h5><?php echo e(__('homework.Homework Details')); ?></h5>
                  <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">x</button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-7">
                                <p><b><?php echo e(__('messages.Title')); ?> :</b> <span id="title"></span></p>
                                <p><b>Description :</b> <span id="description"></span></p> 
                             
                        </div>
                        <div class="col-md-5 pl-3" style="background-color: whitesmoke;margin-top: -2%; margin-bottom: -2%;">
                           <h4><?php echo e(__('homework.Summary')); ?></h4>
                           <hr>
                            <p><i class="fa fa-calendar-plus-o"></i> <b><?php echo e(__('homework.Homework Date')); ?>:</b> <span id="homework_issue_date"></span></p>
                            <p><i class="fa fa-calendar-plus-o"></i> <b><?php echo e(__('homework.Submission Date')); ?>:</b> <span id="submission_date"></span></p>
                            <!--<p><i class="fa fa-calendar-plus-o"></i> <b><?php echo e(__('homework.Evaluation Date')); ?>:</b> 12/05/2021</p>-->
                            <p><b><?php echo e(__('homework.Created By')); ?>:</b> <span id="create_teacher"></span></p>
                            <!--<p><b><?php echo e(__('homework.Evaluated By')); ?>:</b> Shyam Sir</p>-->
                            <p><b><?php echo e(__('messages.Class')); ?>:</b> <span id="classes"></span></p>
                            <p><b><?php echo e(__('messages.Subject')); ?>:</b> <span id="subject"></span></p>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo e(__('messages.Close')); ?></button>
                </div>
              </div>
            </div>
          </div>
          
          
</div>
</div>
</div>
</section>
  
<script>
  $('.homeworkId').click(function() {
  var homework_id = $(this).data('id'); 
  
  $('#homework_id').val(homework_id); 
  } );


$(document).on('click', ".uploadHomework", function () {
    if( !$('#message').val() ) { 
        $("#message").attr('required','true');
        toastr.error('The Message field is required!'); 
    }     
    if( !$('#content_file').val() ) { 
        $("#content_file").attr('required','true');
        toastr.error('The Document field is required!'); 
    } 
});

$(document).on('click', ".viewHomework", function() {

    var session_id = $('#session_id').val();
        $('#myModal').modal('toggle');      
        
    var homework_issue_date = $(this).data('homework_issue_date');
   //alert(homework_issue_date);
    var submission_date = $(this).data('submission_date');
    var title = $(this).data('title');
    var description = $(this).data('description');
    var classes = $(this).data('class');
    var subject = $(this).data('subject');
    var content_file = $(this).data('content_file');
    var create_teacher = $(this).data('create_teacher');

    $('#homework_issue_date').html(homework_issue_date);
    $('#submission_date').html(submission_date);
    $('#title').html(title);
    $('#description').html(description);
    $('#classes').html(classes);
    $('#subject').html(subject);
    $('#hw_file').attr('src',content_file);
        if(create_teacher !== "") { 
            $('#create_teacher').html(create_teacher); 
        }else{
            $('#create_teacher').html('Admin');
        }     

}); 

 
  $('.deleteData').click(function() {
  var delete_id = $(this).data('id'); 
  
  $('#delete_id').val(delete_id); 
  } );
</script>  

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/home_work/home_work/index.blade.php ENDPATH**/ ?>