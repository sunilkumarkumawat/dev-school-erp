<?php
$getUser = Helper::getUser();
?>

<?php $__env->startSection('title', 'My Teachers'); ?>
<?php $__env->startSection('page_title', 'MY TEACHERS'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
<section class="common-page">
 <div class="common-box m-2">
         <table class="common-table w-100">
                  <thead>
                  <tr role="row">
                      <th><?php echo e(__('common.S.NO')); ?></th>
                      <th class="text-center">Image</th>
                            <th><?php echo e(__('staff.Teacher Name')); ?></th>
                           
			              <th><?php echo e(__('common.Action')); ?></th>
                    </tr>
                             
                      
                  </thead>
                  <tbody>
                      
                      <?php if(!empty($data)): ?>
                        <?php
                    
                           $i=1;
                        ?>
                        
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $chat='';
                            $complaint_id= '';
                            $viewStatus= null;
                         $chatData = DB::table('complaint')
                          ->where('session_id', '=',Session::get('session_id'))
                           ->where('branch_id', '=',Session::get('branch_id'))
                         ->where('admission_id', '=',Session::get('id'))->where('teacher_id_to_complaint','=',$item->id ?? '')->whereNull('deleted_at')->first();
                        
                        if(!empty($chatData)){
                              $chat = $chatData->chat ?? '';
                              $complaint_id = $chatData->id;
                              $viewStatus  = json_decode($chatData->view_status,true)[Session::get('id')] ?? 2;
                        }
                        ?>
                  
                        <tr>
                                <td><?php echo e($i++); ?></td>
                                <td class="text-center">
                                    <img class="profileImg pointer" src="<?php echo e(env('IMAGE_SHOW_PATH').'profile/'.$item['photo']); ?>" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/user_image.jpg'); ?>'" data-img="<?php if(!empty($item->photo)): ?> <?php echo e(env('IMAGE_SHOW_PATH').'profile/'.$item['photo']); ?> <?php endif; ?>" >
                                </td>
                                
                                <td><?php echo e($item['first_name']); ?> <?php echo e($item['last_name']); ?>  <span class='badge badge-primary'><?php if(Session::get('role_id') != 1): ?><?php echo e($item->class_type_id == Session::get('class_type_id') ? 'Class Teacher' : ''); ?><?php endif; ?></span></td>
                            
                                <td>
                                    <a class="btn btn-<?php echo e($viewStatus == "" ? 'info' : ($viewStatus == 0 ? 'danger' : 'primary')); ?> btn-xs modal_complaint" id='complaint_id_<?php echo e($item->id); ?>' data-complaint_id="<?php echo e($complaint_id); ?>"data-teacher_name="<?php echo e($item['first_name']  ?? ''); ?>"data-teacher_id="<?php echo e($item->id ?? ''); ?>" data-chat="<?php echo e($chat); ?>"><i class="fa fa-exclamation-circle" aria-hidden="true" ></i> Start / View Conversation
                                    </a>
                                </td>
                                      
                      </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </tbody>
                  </table>
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
            <input type="text" class="form-control" id="message"placeholder="Type your message..." aria-label="Type your message" aria-describedby="send-button">
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
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
  
var teacher_id = '';
  var student_id = '';
  var complaint_id = '';

$('.modal_complaint').click(function(){
    var $messagesContainer = $('.messages-container');
    $messagesContainer.html('');
    teacher_id = $(this).attr('data-teacher_id');
    complaint_id = $(this).attr('data-complaint_id');
    var teacher_name = $(this).attr('data-teacher_name');
     student_id = "<?php echo e(Session::get('id')); ?>";
    var student_name = "<?php echo e(Session::get('first_name')); ?>";
    var jsonString = $(this).attr('data-chat');
   
   
   if(jsonString != '')
   {
var messages = JSON.parse(jsonString);


$.each(messages, function(index, messageObject) {
  var userId = Object.keys(messageObject)[0];
  var messageContent = messageObject[userId];
 var messageType = '';
var user_name = '';
    if(userId === student_id )
    {
        messageType ='sent';
        user_name = 'Me';
    }
    else
    {
           messageType ='received';
    
        if(userId === '1')
        {
            user_name='Admin';
        }
    else{
        
    user_name ='Teacher';
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
                user_id:student_id,
                teacher_id:teacher_id,
                admin_id:1,
                teacher_id:teacher_id,
                student_id:student_id,
                admin_status:0,
                teacher_status:0,
                student_status:1,
                
            },
            success: function(data) {
               
               $('#complaint_id_'+ teacher_id).attr('data-chat',data.data);
             
                $('#complaint_id_'+ teacher_id).removeAttr('class'); 
                $('#complaint_id_'+ teacher_id).attr('class', "btn btn-primary btn-xs modal_complaint"); 
                 var anchorElement = document.getElementById('complaint_id_'+teacher_id);
                  anchorElement.click();
                     $('#message').val(''); 
            },
            error: function(xhr, status, error) {
                console.error('Error sending message:', error);
                alert('Failed to send message');
            }
        });
    });
    
});
</script>
<?php $__env->stopSection(); ?> 

<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/my_teachers.blade.php ENDPATH**/ ?>