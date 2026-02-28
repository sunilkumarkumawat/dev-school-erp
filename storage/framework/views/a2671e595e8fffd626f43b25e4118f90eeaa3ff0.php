<?php
$getUser = Helper::getUser();
?>

<?php $__env->startSection('title', 'Uniform'); ?>
<?php $__env->startSection('page_title', 'UNIFORM'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
<section class="common-page">
 <div class="common-box m-2">
       <table  class="common-table w-100">
                             <thead class="bg-primary">
                                 
                                 <tr>
                                     <th>Sr.No.</th>
                                     <th>Image</th>
                                     <th>Description</th>
                                     <th>Action</th>
                                 </tr>
                             </thead>
            
                              <tbody>
                                 
                                  <?php if(!empty($data)): ?>
                                  
                                  <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <tr>
                                      <td width='5%'><?php echo e($key+1 ?? ''); ?></td>
                                        <td width='30%'> <img width="100%" src="<?php echo e(env('IMAGE_SHOW_PATH')); ?><?php echo e('uniform_image/'); ?><?php echo e($item->uniform_image ?? ''); ?> " ></td>
                                        <td width='60%'> <?php echo html_entity_decode($item->description ?? ''); ?></td>
                                      <td width='5%'>
                                          <button type="button"  data-data="<?php echo e(env('IMAGE_SHOW_PATH')); ?><?php echo e('uniform_image/'); ?><?php echo e($item->uniform_image ?? ''); ?>"
                                          class="btn btn-primary button1" data-bs-toggle="modal" data-bs-target="#descriptionModal">
                                              <i class="fa fa-eye" aria-hidden="true"></i>

                                                        </button>
                                          
                                        </td></tr>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  <?php endif; ?>       
                                    
                              </tbody>
                              </table>
    </div>
<!-- Modal -->
<div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">School Uniform</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
         
        </button>
      </div>
      <div class="modal-body  description_modal">
      <img id="modal_id"  width="100%"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(".button1").click(function(){
 var value = $(this).data('data');

 $("#modal_id").attr("src",value);

});
</script>
  
<?php $__env->stopSection(); ?> 

<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/uniform.blade.php ENDPATH**/ ?>