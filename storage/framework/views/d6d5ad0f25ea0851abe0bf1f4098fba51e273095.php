 
<?php $__env->startSection('content'); ?>



<div class="content-wrapper">
	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
							<h3 class="card-title"><i class="fa fa-list-alt"></i> &nbsp;<?php echo e(__('dashboard.View Complaints')); ?></h3>
							<div class="card-tools"> 
							
					        <?php if(Session::get('role_id') !== 1): ?>		
							<a href="<?php echo e(url('complaint_add')); ?>" class="btn btn-primary  btn-sm <?php echo e(Helper::permissioncheck(28)->add ? '' : 'd-none'); ?>"><i class="fa fa-plus"></i><?php echo e(__('common.Add')); ?> </a> 
						    <?php endif; ?>	
						    <?php if(Session::get('role_id') !== 3): ?>
							<a href="<?php echo e(url('reception_file')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><?php echo e(__('common.Back')); ?> </a> 
							<?php endif; ?>
							
                             <?php if(Session::get('role_id') == 3): ?>
                                  <a href="<?php echo e(url('dashboard')); ?>" class="btn btn-primary  btn-xs "><i class="fa fa-arrow-left"></i> <span class="" ><?php echo e(__('Back')); ?> </span></a>
                              <?php endif; ?>
                           
							</div>
						</div>
				
    	<div class="row mb-2 m-2">
		    <div class="col-md-12">	
        <table id="example1" class="table table-bordered table-striped dataTable dtr-inline ">
          <thead class="bg-primary">
          <tr role="row">
              
              <th><?php echo e(__('common.SR.NO')); ?> </th>
                <?php if(Session::get('role_id') != 3): ?> 
                    <th><?php echo e(__('common.Name')); ?> </th>
                    <th><?php echo e(__('common.Mobile No.')); ?> </th>
                 <?php endif; ?>
                    <th><?php echo e(__('common.Subject')); ?> </th>
                    <th><?php echo e(__('dashboard.Description')); ?> </th>
                    <th><?php echo e(__('common.Date')); ?> </th>
                    <th>Admin Action </th>
                     <?php if(Session::get('role_id') == 1): ?>	
                        <th><?php echo e(__('common.Action')); ?></th>
                    <?php endif; ?>
          </thead>
          <tbody>
              
              <?php if(!empty($data)): ?>
                <?php
                   $i=1;
                ?>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                        <td><?php echo e($i++); ?></td>
                         <?php if(Session::get('role_id') != 3): ?> 
                    <td><?php echo e($item['Admission']['first_name'] ?? ''); ?> <?php echo e($item['Admission']['last_name'] ?? ''); ?> (<?php echo e($item['ClassType']['name'] ?? ''); ?>)</td>
                        <td><?php echo e($item['Admission']['mobile'] ?? ''); ?></td>
                 <?php endif; ?>
                        
                        <td><?php echo e($item['subject'] ?? ''); ?></td>
                        <td style='word-wrap: break-word;'><?php echo e($item['description'] ?? ''); ?></td>
                         
                        <td><?php echo e(date('d-m-Y', strtotime($item->date)) ?? ''); ?></td>
                         <td>
                            <?php if($item->teacher_id_to_complaint != ''): ?>
                                <?php
                                    $viewStatus = json_decode($item->view_status,true)[Session::get('id')] ?? 0;
                                    $teacher_name = DB::table('teachers')->where('id',$item->teacher_id_to_complaint)->first();
                                    //dd($item->view_status);
                                ?>
                                <a class="btn btn-<?php echo e($viewStatus == 0 ? 'danger' : 'primary'); ?> btn-xs modal_complaint" id='complaint_id_<?php echo e($item->id); ?>' data-complaint_id="<?php echo e($item->id); ?>" data-teacher_id="<?php echo e($item->teacher_id_to_complaint ?? ''); ?>" data-teacher_name="<?php echo e($teacher_name->first_name ?? ''); ?> <?php echo e($teacher_name->last_name ?? ''); ?>"
                                    data-student_name="<?php echo e($item['Admission']['first_name'] ?? ''); ?><?php echo e(' '); ?><?php echo e($item['Admission']['last_name'] ?? ''); ?><?php echo e(' '); ?>(<?php echo e($item['ClassType']['name'] ?? ''); ?>)"data-student_id="<?php echo e($item->admission_id ?? ''); ?>"data-chat="<?php echo e($item->chat ?? ''); ?>"><i class="fa fa-exclamation-circle" aria-hidden="true" ></i> View Chat
                                </a>
                             <?php else: ?>
                                <?php echo e($item['admin_action'] ?? ''); ?>

                             <?php endif; ?>
                         </td>
                      
                                                 
                           
                            <?php if(Session::get('role_id') == 3 ): ?>
                            
                                    <?php if($item['admin_action'] == ''): ?>
                                     <td>
                                    <a href="<?php echo e(url('complaint_edit')); ?>/<?php echo e($item->id); ?>" class="btn btn-primary  btn-xs ml-3 <?php echo e(Helper::permissioncheck(28)->edit ? '' : 'd-none'); ?>" title="Edit Complaint"><i class="fa fa-edit"></i></a> 
                                  
									<a href="javascript:;" data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-danger  btn-xs ml-3 <?php echo e(Helper::permissioncheck(28)->delete ? '' : 'd-none'); ?>" title="Delete Book"><i class="fa fa-trash-o"></i></a> 
                                  </td>
                                    <?php endif; ?>
                                    <?php else: ?>
                                     <td>
                                       <?php if($item->teacher_id_to_complaint == ''): ?>
                                    <?php if(Session::get('role_id') != 3): ?>
                                    
                                   <button class="btn btn-warning btn-xs ml-3 review <?php echo e(Helper::permissioncheck(28)->edit ? '' : 'd-none'); ?>" data-description="<?php echo e($item['description']); ?>" data-id="<?php echo e($item['id']); ?>" title="Edit c"><i class="fa fa-envelope"></i></button> 
                                    <?php endif; ?>                           
                                    <?php endif; ?>   
                                    <?php if((Session::get('role_id')) == 1): ?>
									<a href="javascript:;" data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-danger  btn-xs ml-3 <?php echo e(Helper::permissioncheck(28)->delete ? '' : 'd-none'); ?>" title="Delete Book"><i class="fa fa-trash-o"></i></a> 
                                    	<?php endif; ?>
                                    	</td>
                            <?php endif; ?>
                            
						
					
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
        </div>

</div>


<script>
    $('.deleteData').click(function() {
	var delete_id = $(this).data('id');
	$('#delete_id').val(delete_id);
});

$(document).on('click', ".review", function() {
  $('#viewModal').modal('toggle');
  var complaint_id = $(this).data('id');
  $('#complaint_id').val(complaint_id);
  	var description = $(this).data('description');
		$('#admin_action').val(description);
});
</script>

<div class="modal" id="viewModal">
	<div class="modal-dialog">
		<div class="modal-content" >
			<div class="modal-header">
				<h4 class="modal-title "><?php echo e(__('dashboard.Submit Review')); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
			<form action="<?php echo e(url('complaint_action')); ?>" method="post"> 
			    <?php echo csrf_field(); ?>
				<div class="modal-body">
					<input type="hidden" id="complaint_id" name="complaint_id">
					<label style="color: red;"><?php echo e(__('dashboard.Admin Action')); ?>*</label>
					<textarea class="form-control  <?php $__errorArgs = ['admin_action'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" id="admin_action" name="admin_action" placeholder="<?php echo e(__('dashboard.Admin Action')); ?>"></textarea> 
					
					 </div>
				<div class="modal-footer">
				<button type="submit" class="btn btn-danger waves-effect waves-light"><?php echo e(__('common.submit')); ?></button>				    
				<button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal"><?php echo e(__('common.Close')); ?></button>
	
				</div>
			</form>
		</div>
	</div>
		</div>

<div class="modal" id="Modal_id">
	<div class="modal-dialog">
		<div class="modal-content" style="background: #555b5beb;">
			<div class="modal-header">
				<h4 class="modal-title text-white"><?php echo e(__('common.Delete Confirmation')); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
			<form action="<?php echo e(url('delete_complaint')); ?>" method="post"> 
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
	</div>
		</div>
	</div>
	</section>
</div>

<div class="modal fade" id="modal_complaint_modal">
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content">
    <div class="modal-body">
        <div class="col-md-12">
            <div class="centered_flex">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                <p>Chat Panel</p>
            </div>
        </div>
        
          <div class="modal-body">
        <div class="chat-container">
   <div class="messages-container">
       
        </div>
          <div class="input-group mb-3 mt-2">
            <input type="text" class="form-control" id='message' placeholder="Type your message..." aria-label="Type your message" aria-describedby="send-button">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button" id="send-button">Send</button>
            </div>
          </div>
        </div>
      </div>
        
        <!--<div class="col-md-12 text-right">-->
        <!--    <button class="modal_btn bg-white change_status" data-action="Discard" data-bs-dismiss="modal">Discard</button>-->
        <!--    <button class="modal_btn bg-warning change_status" data-action="Confirm" data-bs-dismiss="modal">Send</button>-->
        <!--</div>-->
   
    </div>
  </div>
</div>
</div>

<style>
  /* Style for Messages Container */
  .messages-container {
    height: 300px;
    overflow-y: scroll;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  /* Style for Received Messages */
  .message.received {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 10px;
  }

  .received-message {
    background-color: #f0f0f0;
    padding: 8px 12px;
    border-radius: 10px;
    max-width: 70%;
    word-wrap: break-word;
  }

  .received-label {
    font-size: 12px;
    color: #888;
    margin-bottom: 4px;
  }

  /* Style for Sent Messages */
  .message.sent {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    margin-bottom: 10px;
  }

  .sent-message {
    background-color: #007bff;
    color: #fff;
    padding: 8px 12px;
    border-radius: 10px;
    max-width: 70%;
    word-wrap: break-word;
  }

  .sent-label {
    font-size: 12px;
    color: #888;
    margin-bottom: 4px;
  }

  /* Style for New Message Form */
  #messageForm {
    margin-top: 10px;
  }
  
  </style>
<script>

var teacher_id = '';
  var student_id = '';
  var complaint_id = '';
    $('.modal_complaint').click(function(){
    var $messagesContainer = $('.messages-container');
    
    $messagesContainer.html('');
    teacher_id = $(this).attr('data-teacher_id');
    var teacher_name = $(this).attr('data-teacher_name');
    student_id = $(this).attr('data-student_id');
    complaint_id = $(this).attr('data-complaint_id');
    var student_name = $(this).attr('data-student_name');
    var jsonString = $(this).attr('data-chat');
   
   
   if(jsonString != '')
   {
var messages = JSON.parse(jsonString);


$.each(messages, function(index, messageObject) {
  var userId = Object.keys(messageObject)[0];
  var messageContent = messageObject[userId];
 var messageType = '';
var user_name = '';
    if(userId === '1' )
    {
        messageType ='sent';
        user_name = 'Admin';
    }
    else
    {
           messageType ='received';
    
        if(userId === teacher_id)
        {
            user_name= teacher_name + " (Teacher)";
        }
    else{
        
    user_name =student_name;
    }
        
    }
 

  var messageHTML = `
    <div class="message ${messageType}">
      <div class="${messageType}-label">${user_name}</div>
      <div class="${messageType}-message">${messageContent}</div>
    </div>
  `;

  $messagesContainer.append(messageHTML);
});
 
}
 $('#modal_complaint_modal').modal('show');
 setTimeout(function() {        
    var scrollHeight = $messagesContainer.innerHeight();
    $messagesContainer.animate({scrollTop: $messagesContainer[0].scrollHeight}, 'slow');
}, 800);
  
});


$('#send-button').click(function() {
        var message = $('#message').val();
        

        var baseUrl = "<?php echo e(url('/')); ?>";

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: baseUrl + '/sendConversation',
            data: {
                complaint_id: complaint_id,
                message: message,
                user_id:1,
                admin_id:1,
                teacher_id:teacher_id,
                student_id:student_id,
                admin_status:1,
                teacher_status:0,
                student_status:0,
                
            },
            success: function(data) {
               
               $('#complaint_id_'+complaint_id).attr('data-chat',data.data);
               $('#complaint_id_'+ complaint_id).removeAttr('class'); 
                $('#complaint_id_'+ complaint_id).attr('class', "btn btn-primary btn-xs modal_complaint");
                $('#message').val(''); 
                
                
                
                var anchorElement = document.getElementById('complaint_id_'+complaint_id);
                anchorElement.click();
            },
            error: function(xhr, status, error) {
                console.error('Error sending message:', error);
                alert('Failed to send message');
            }
        });
    });

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/dashboard/student/complaint/view.blade.php ENDPATH**/ ?>