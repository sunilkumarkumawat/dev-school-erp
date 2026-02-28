<?php
$classType = Helper::classType();
$homeworkReview = Helper::homeworkReview();
$getUser = Helper::getUser();
?>


<?php $__env->startSection('title', 'Homework'); ?>
<?php $__env->startSection('page_title', 'HOMEWORK'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
<section class="homework-page">
<input type="hidden" id="session_id" value="<?php echo e(Session::get('role_id') ?? ''); ?>">
 <div class="homework-box m-2">
        <table id="" class="homework-table">
          <thead>
          <tr role="row">
              <th style="padding:0;"><?php echo e(__('messages.Sr.No.')); ?></th>
                  <th><?php echo e(__('master.Title')); ?></th>
                    <th><?php echo e(__('messages.Subject')); ?></th>
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
                   
                ?>
                <tr class=" <?php echo e(( 1 == $item['view_status'])   ?  : ''); ?> "> 
                    <td><?php echo e($i++); ?></td>
                   
                      <td><?php echo e($item['title'] ??''); ?> </td>
                  
                    <td><?php echo e($item['Subject']['name'] ?? ''); ?></td>
                    <td><?php echo e(date('d-m-Y', strtotime($item['submission_date'])) ?? ''); ?></td>
                    <td class="actionTd">
                        <button data-id='<?php echo e($item->id); ?>'  data-submission_date="<?php echo e(date('d-m-Y', strtotime($item['submission_date'])) ?? ''); ?>" 
                        data-title='<?php echo e($item->title); ?>' data-description='<?php echo e($item->description); ?>' data-content_file='<?php echo e(env('IMAGE_SHOW_PATH').'homework/'.$item['content_file']); ?>'
                        data-class='<?php echo e($item['ClassType']['name'] ??''); ?>' data-subject='<?php echo e($item['Subject']['name'] ?? ''); ?>'
                        data-create_teacher='<?php echo e($item['Teacher']['first_name'] ?? ''); ?> <?php echo e($item['Teacher']['last_name'] ?? ''); ?>'
                       
                        class="btn btn-secondary viewHomework btn-xs" title="View Homework" ><i class="fa fa-eye"></i></button>
                            <a href="javascript:;" class="btn btn-success btn-xs ml-3 homeworkId" id="homeworkId" data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#uploadModal" title="Upload Assignment" ><i class="fa fa-upload"></i></a>
                        <a href="<?php echo e(url('homework/details_student')); ?>/<?php echo e($item->id); ?>" class="btn btn-primary btn-xs ml-3" title=" Assignments" ><i class="fa fa-reorder"></i></a>    
                    </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

            <!-- The Modal -->
            <div class="modal" id="uploadModal">
              <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content">
            
                  <div class="modal-header">
                    <h4 class="modal-title"><?php echo e(__('homework.Homework Assignments')); ?></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                <form action="<?php echo e(url('uploadHomework')); ?>" method="post" enctype="multipart/form-data">
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
                          <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo e(__('messages.Close')); ?></button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light uploadHomework"><?php echo e(__('messages.submit')); ?></button>
                    </div>
                    </form>
                </div>
              </div>
            </div>

       <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered homework-modal" role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('homework.Homework Details')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="downloadLeaflet">
                    <div class="col-md-12 col-12">
                        <p><b><?php echo e(__('messages.Title')); ?> :</b> <span id="title"></span></p>
                        <p><b><?php echo e(__('messages.Subject')); ?>:</b> <span id="subject"></span></p>
                        <p><b><?php echo e(__('homework.Submission Date')); ?>:</b> <span id="submission_date"></span></p>
                        <p><b><?php echo e(__('homework.Created By')); ?>:</b> <span id="create_teacher"></span></p>
                    </div>
                    <br>
                    <div class="col-md-12 col-12">
                        <hr>
                        <p><b>Description :</b> <span id="description"></span></p>
                       
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              
                <button type="button" class="btn "  id="downloadBtnImage"><i class="fa fa-download"></i></button>
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo e(__('messages.Close')); ?></button>
            </div>
        </div>
    </div>
</div>

          
          
</div>
</div>
</div>
</section>

<script src="<?php echo e(URL::asset('public/assets/school/js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('public/assets/school/js/jquery-ui.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('public/assets/school/js/html2canvas.js')); ?>"></script>
<script src="<?php echo e(URL::asset('public/assets/school/js/jspdf.js')); ?>"></script>

  <script>
      function downloadImageLeaflet() {
        const leafletElement = document.getElementById('downloadLeaflet');
        html2canvas(leafletElement, {
            useCORS: true,
            scrollX: 0,
            scrollY: 0,
            dpi: window.devicePixelRatio * 1000, // Set higher DPI value for better image quality
            scale: 5
        }).then((canvas) => {
            const imgData = canvas.toDataURL('image/jpeg', 2.0); // Use JPEG format with highest quality
            // Create a temporary link element
            const link = document.createElement('a');
            link.href = imgData;
            link.download = 'Homework.png';

            // Trigger the download
            link.click();
        });
    }

 
    const downloadBtnImage = document.getElementById('downloadBtnImage');
    downloadBtnImage.addEventListener('click', downloadImageLeaflet);
</script>
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
    var submission_date = $(this).data('submission_date');
    var title = $(this).data('title');
    var description = $(this).data('description');
    var classes = $(this).data('class');
    var subject = $(this).data('subject');
 
    var content_file = $(this).data('content_file');
    var create_teacher = $(this).data('create_teacher');

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




<style>
    .actionTd a{
        margin-left: 0px !important;
    }
   @media (min-width: 576px) {
  .homework-modal {
    max-width: 80%;
    margin: 1.75rem auto;
  }
   
}
</style>


<?php $__env->stopSection(); ?> 
<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/homework/index.blade.php ENDPATH**/ ?>