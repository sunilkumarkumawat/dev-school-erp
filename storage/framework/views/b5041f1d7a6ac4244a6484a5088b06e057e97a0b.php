<?php
$getUser = Helper::getUser();
?>

<?php $__env->startSection('title', 'Add Leave'); ?>
<?php $__env->startSection('page_title', 'ADD LEAVE'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
<section class="common-page">
 <div class="common-box m-2 border-0">
     <div class="leave-form-box p-3">              
                <form id="quickForm" action="<?php echo e(url('applyLeaveStudent')); ?>" method="post">
  
                <?php echo csrf_field(); ?>
                
                        
                    <div class="form-group">
                			<label style="color:red;"><?php echo e(__('messages.Subject')); ?>*</label>
            			<input class="leave-input <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="input" id="subject" name="subject" placeholder="Subject">
                             <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            					<span class="invalid-feedback" role="alert">
            						<strong><?php echo e($message); ?></strong>
            					</span>
            				<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>                  			
                    	</div> 
                        <div class="form-group">
                			<label style="color:red;"><?php echo e(__('messages.From Date')); ?>*</label>
            				<input class="leave-input <?php $__errorArgs = ['from_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="date" id="from_date" name="from_date" value="<?php echo e(date('Y-m-d')); ?>">
                             <?php $__errorArgs = ['from_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            					<span class="invalid-feedback" role="alert">
            						<strong><?php echo e($message); ?></strong>
            					</span>
            				<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>                  			
                    	</div>                     	
                        <div class="form-group">
                			<label style="color:red;"><?php echo e(__('messages.To Date')); ?>*</label>
            				<input class="leave-input <?php $__errorArgs = ['to_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="date" id="to_date" name="to_date" value="<?php echo e(date('Y-m-d')); ?>">
                             <?php $__errorArgs = ['to_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            					<span class="invalid-feedback" role="alert">
            						<strong><?php echo e($message); ?></strong>
            					</span>
            				<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>                  			
                    	</div>                     	
                        <div class="form-group">
                    			<label style="color:red;"><?php echo e(__('messages.Reason')); ?>*</label>
                    			<textarea class="leave-input <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" name="reason" id="reason" placeholder="Reason"></textarea>
                             <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            					<span class="invalid-feedback" role="alert">
            						<strong><?php echo e($message); ?></strong>
            					</span>
            				<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> 
                    	</div>                      	
              
                <button type="submit" class="btn-leave-send">SUBMIT</button>
                </form>
            </div> 
            
            
             <h6 class="leave-title">LEAVE DETAILS</h6>
 <div class="common-box m-2 border-0">
                          <table  class="common-table w-100">
                          <thead>
                          <tr>
                              <th><?php echo e(__('messages.Sr.No.')); ?></th>
                              <th><?php echo e(__('messages.Status')); ?></th>
                              <th><?php echo e(__('messages.Subject')); ?></th>
                              <th><?php echo e(__('Date')); ?></th>
                              
                              <th><?php echo e(__('messages.Reason')); ?></th>
                              <th><?php echo e(__('messages.Action')); ?></th>
                              </tr>
                              
                              
                          </thead>
                          <tbody id="">
                          
                          <?php if(!empty($dataview)): ?>
                                <?php
                                   $i=1;
                                 
                                ?>
                                <?php $__currentLoopData = $dataview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             
                               <?php if(Session::get('id')==$item['admission_id']): ?>
                               
                                <tr>
                                    <td><?php echo e($i++); ?></td>
                                    <?php if($item['status']== "1"): ?>
                                        <td>
                                            
                                            <a style="user-select:none;font-size: 12px;"class="btn btn-xs btn-success reminder_status w-100" >Approved</a>
                                            <!--<a data-id="<?php echo e($item['admission_id'] ?? ''); ?>" style="<?php echo e($item['status'] == 1 ? 'display:none'   : ''); ?>" data-status="1" class="btn btn-xs btn-danger reminder_status" data-text="Deactivate">Deactive </a>                                                               -->
                                        </td>
                                        <?php endif; ?>
                                
                                    <?php if($item['status']== "0"): ?>
                                        <td>
                                        <a style="user-select:none;font-size: 12px;"class="btn btn-xs btn-danger reminder_status w-100" >Denied</a>                                                              
                                        </td>
                                        <?php endif; ?>
                                        
                                         <?php if($item['status']== "2"): ?>
                                        <td>
                                        <a style="user-select:none;font-size: 12px;"class="btn btn-xs btn-warning reminder_status w-100" >Pending</a>                                                              
                                        </td>
                                        <?php endif; ?>
                                        <td><?php echo e($item['subject'] ?? ''); ?></td>
                                        <td><?php echo e(date('d-m', strtotime($item['from_date'])) ?? ''); ?>/<?php echo e(date('d-m-Y', strtotime($item['to_date'])) ?? ''); ?></td>
                                        
                                        <td><?php echo e($item['reason'] ?? ''); ?></td>
                                        
                                        <td>
                                                 <?php if($item['status']== "2"): ?>
                                              <a href="<?php echo e(url('updateLeaveStudent')); ?>/<?php echo e($item['id'] ?? ''); ?>" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:;" 
                                                   data-id="<?php echo e($item->id); ?>"  
                                                   class="btn btn-danger btn-xs ml-1 deleteData"
                                                   data-bs-toggle="modal" data-bs-target="#Modal_id">
                                                   <i class="fa fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                          </tbody>
                          </table>
                        
					</div>
        </div>
        
    </div>  

</div>

<div class="modal" id="Modal_id">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #555b5beb;">

      <div class="modal-header">
        <h4 class="modal-title text-white">Delete Confirmation</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>

      <form action="<?php echo e(url('deleteLeaveStudent')); ?>" method="post">
              	 <?php echo csrf_field(); ?>
      <div class="modal-body">
              <input type=hidden id="delete_id" name=delete_id >
              <h5 class="text-white">Are you sure you want to delete  ?</h5>
      </div>
      <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">Delete</button>
         </div>
       </form>
    </div>
  </div>
</div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('.deleteData').click(function() {
  var delete_id = $(this).data('id'); 
  $('#delete_id').val(delete_id); 
  } );
</script>




<?php $__env->stopSection(); ?>      
<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/leave/student_leave.blade.php ENDPATH**/ ?>