 
<?php $__env->startSection('content'); ?>

<div class="content-wrapper">
    
   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">    
            <div class="col-md-4 pr-0 ">
            <div class="card card-outline card-orange mr-1">
             <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fa fa-calendar-check-o"></i> &nbsp;<?php echo e(__('master.Add Sessions')); ?> </h3>
            <div class="card-tools">
              </div>
            
            </div>                 
                <form id="form-submit" action="<?php echo e(url('session_add')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="row m-2">
                        <div class="col-md-12">
        			<div class="form-group">
        				<label style="color:red;"><?php echo e(__('master.From Year')); ?>* </label>
        				<input type="text" class="form-control <?php $__errorArgs = ['from_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="year" name="from_year" placeholder="From Year" value="<?php echo e(old('from_year')); ?>" maxlength="4" onkeypress="javascript:return isNumber(event)">
            		     
                        </select>
        		    </div>
    		    </div>
                        <div class="col-md-12">
        			<div class="form-group">
        				<label style="color:red;"><?php echo e(__('master.To Year')); ?>* </label>
        				<input type="text" class="form-control <?php $__errorArgs = ['to_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="to_year" name="to_year" placeholder="To Year" value="<?php echo e(old('to_year')); ?>" maxlength="2" onkeypress="javascript:return isNumber(event)">
            		     
                        </select>
        		    </div>
    		    </div>
                                     	
                </div>
 
                <div class="row m-2">
                    <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-submit"><?php echo e(__('common.submit')); ?> </button>
                    </div>
                </div>
                </form>
            </div>          
        </div>
        
        <div class="col-md-8 pl-0">
            <div class="card card-outline card-orange ml-1">
             <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fa fa-calendar-check-o"></i> &nbsp;<?php echo e(__('master.View Sessions')); ?> </h3>
            <div class="card-tools">
        
            <a href="<?php echo e(url('master_dashboard')); ?>" class="btn btn-primary  btn-sm" ><i class="fa fa-arrow-left"></i><?php echo e(__('common.Back')); ?> </a>
            </div>
            
            </div>                 
                <div class="row m-2">
                    <div class="col-md-12">
                	</div>
                    <div class="col-md-12">
                       <table id="example1" class="table table-bordered table-striped dataTable dtr-inline ">
                          <thead class="bg-primary">
                          <tr role="row">
                              <th><?php echo e(__('common.SR.NO')); ?></th>
                              <th><?php echo e(__('master.From Date')); ?></th>
                              <th><?php echo e(__('master.To Date')); ?></th>
                              
                              <th><?php echo e(__('common.Action')); ?></th>
                              
                              
                              
                              
                          </thead>
                          <tbody id="">
                          
                          <?php if(!empty($sessions)): ?>
                                <?php
                                   $i=1
                                ?>
                                <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                        <td><?php echo e($i++); ?></td>
                                        <td><?php echo e($item['from_year'] ?? ''); ?></td>
                                        <td><?php echo e($item['to_year'] ?? ''); ?></td>
                                        
                                        <td>
                                            <a href="<?php echo e(url('sessions_edit',$item->id)); ?>" class="btn btn-primary  btn-xs tooltip1 <?php echo e(Helper::permissioncheck(9)->edit ? '' : 'd-none'); ?>" title1="Edit Sessions"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:;"  data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id"  class="deleteData btn btn-danger  btn-xs ml-3 tooltip1 <?php echo e(Helper::permissioncheck(9)->delete ? '' : 'd-none'); ?>" title1="Delete Sessions "><i class="fa fa-trash-o"></i></a>                                   
                                            
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
</div>
</section>
</div>

<script>
  $('.deleteData').click(function() {
  var delete_id = $(this).data('id'); 
  
  $('#delete_id').val(delete_id); 
  } );
</script>

<!-- The Modal -->
<div class="modal" id="Modal_id">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #555b5beb;">

      <div class="modal-header">
        <h4 class="modal-title text-white"><?php echo e(__('messages.Delete Confirmation')); ?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>

      <form action="<?php echo e(url('sessions_delete')); ?>" method="post">
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
<?php $__env->stopSection(); ?>      
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/Sessions/add.blade.php ENDPATH**/ ?>