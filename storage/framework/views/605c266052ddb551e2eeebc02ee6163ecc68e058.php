<?php
$classType = Helper::ClassType();
?>



 
<?php $__env->startSection('content'); ?>


<div class="content-wrapper" >

   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">    
         
        <div class="col-md-4 pr-0">
            <div class="card card-outline card-orange mr-1">
             <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fa fa-cloud-download"></i> &nbsp;<?php echo e(__('master.Add Content')); ?> </h3>
            <div class="card-tools">
           
            </div>
            
            </div>                 
           
                <form id="form-submit" action="<?php echo e(url('upload/content')); ?>" method="post" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row m-2">
                    	<div class="col-md-12">
									<div class="form-group">
										<label><?php echo e(__('common.Class')); ?></label>
										<select class="form-control select2 " id="class_search_id" name="class_search_id">
										<?php if(Session::get('role_id') != 2): ?>
										
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php endif; ?>
											<?php if(!empty($classType)): ?>
											<?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($type->id ?? ''); ?>"><?php echo e($type->name ?? ''); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
                        <div class="col-md-12">
                         
                			<label style="color:red;"><?php echo e(__('master.Content Title')); ?>*</label>
                			<input class="form-control <?php $__errorArgs = ['content_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" name="content_title" id="content_title" placeholder="<?php echo e(__('master.Content Title')); ?>" value="<?php echo e(old('content_title')); ?>">
                                            			
                    	</div>
                        <div class="col-md-12">
                			<label style="color:red;"><?php echo e(__('master.Content Type')); ?>*</label>
            				<select class="select2  form-control <?php $__errorArgs = ['content_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="content_type" name="content_type">
                                <option value="" >Select</option>
                                <option name="Assignments" value="Assignments">Assignments</option>
                                <option name="Study Material" value="Study Material">Study Material</option>
                                <option name="Syllabus" value="Syllabus">Syllabus</option>
                                <option name="Other Downloads" value="Other Downloads">Other Downloads</option>
                            </select>
                                             			
                    	</div>  
                        <div class="col-md-12">
                			<label style="color:red;"><?php echo e(__('master.Upload Date')); ?>*</label>
                			<input class="form-control <?php $__errorArgs = ['upload_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="date" name="upload_date" id="upload_date" value="<?php echo e(date('Y-m-d')); ?>">
                                              			
                    	</div>                     	
                        <div class="col-md-12">
                    			<label><?php echo e(__('Video Link')); ?></label>
                    			<input class="form-control" type="text" name="video_link" id="video_link" placeholder="<?php echo e(__('Video Link')); ?>" value="<?php echo e(old('video_link')); ?>">
                      	
                    	</div> 
                        <div class="col-md-12">
                    			<label><?php echo e(__('master.Description')); ?></label>
                    			<textarea class="form-control" type="text" name="description" id="description" placeholder="<?php echo e(__('master.Description')); ?>"><?php echo e(old('description')); ?></textarea>
                    	</div> 
                        <div class="col-md-12">
                			<label ><?php echo e(__('master.Content File')); ?></label>
                                <input type="file" class="input file form-control <?php $__errorArgs = ['content_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="content_file" id="content_file" value="<?php echo e(old('content_file')); ?>"  accept="image/png, image/jpg, image/jpeg">
										 <p class="text-danger" id="image_error"></p>
                               
                                                
                    	</div>                    	
                </div>
 
                <div class="row m-2">
                    <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-submit"><?php echo e(__('master.Submit')); ?> </button>
                    </div>
                </div>
                </form>
            </div>          
        </div>
        
    <div class="col-md-8 pl-0">
            <div class="card card-outline card-orange ml-1">
             <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fa fa-cloud-download"></i> &nbsp;<?php echo e(__('master.Content List')); ?> </h3>
            <div class="card-tools">
         
            <a href="<?php echo e(url('download_center')); ?>" class="btn btn-primary  btn-sm" ><i class="fa fa-arrow-left"></i><?php echo e(__('master.Back')); ?> </a>
            </div>
            
            </div>                 
                <div class="row m-2">
                    <div class="col-md-12">
                	</div>
                    <div class="col-md-12">
                       <table id="example1" class="table table-bordered table-striped dataTable dtr-inline ">
                          <thead class="bg-primary">
                          <tr role="row">
                              <th><?php echo e(__('master.Sr.No.')); ?></th>
                              <th><?php echo e(__('master.Content Title')); ?></th>
                              <th><?php echo e(__('master.Class')); ?></th>
                              <th><?php echo e(__('master.Content Type')); ?></th>
                              <th><?php echo e(__('master.Date')); ?></th>
                              <th><?php echo e(__('Link')); ?></th>
                              <th><?php echo e(__('Description')); ?></th>
                              
                              <th><?php echo e(__('master.Action')); ?></th>
                             
                              
                              
                          </thead>
                          <tbody id="">
                          
                          <?php if(!empty($dataview)): ?>
                                <?php
                                   $i=1
                                ?>
                                <?php $__currentLoopData = $dataview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                        <td><?php echo e($i++); ?></td>
                                        <td><?php echo e($item['content_title'] ?? ''); ?></td>
                                        <td><?php echo e($item['class_name'] ?? 'All'); ?></td>
                                        <td><?php echo e($item['content_type'] ?? ''); ?></td>
                                        <td><?php echo e(date('d-m-Y', strtotime($item['upload_date'])) ?? ''); ?></td>
                                        <td>  <?php if(($item['video_link'] ?? '') != ''): ?>
                                        <a class='text-primary' target='_blank'href="<?php echo e($item['video_link'] ?? ''); ?>" >Click to View</a>
                                        <?php endif; ?></td>
                                          <td><?php echo e($item['description'] ?? ''); ?></td>
                                        
                                        <td>
                                           
                                            <a href="<?php echo e(url('upload/content_edit')); ?>/<?php echo e($item['id'] ?? ''); ?>" class="ml-2 tooltip1 <?php echo e(Helper::permissioncheck(12)->edit ? '' : 'd-none'); ?>" title1="download"><i class="fa fa-edit text-primary"></i></a>

                                            <a href="<?php echo e(url('download')); ?>/<?php echo e($item['id'] ?? ''); ?>" class="ml-2 tooltip1 <?php echo e(Helper::permissioncheck(12)->print ? '' : 'd-none'); ?>" title1="download"><i class="fa fa-download text-success"></i></a>
                                            
                                            <a href="javascript:;"  data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id"  class="deleteData ml-2 tooltip1 <?php echo e(Helper::permissioncheck(12)->delete ? '' : 'd-none'); ?>" title1="Delete"><i class="fa fa-trash-o text-danger"></i></a>
                                           
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
                <h4 class="modal-title text-white">Delete Confirmation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>

            <form action="<?php echo e(url('upload_delete')); ?>" method="post">
                 <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type=hidden id="delete_id" name=delete_id>
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


    
    <style>
    #image_error{
        font-weight: bold;
    font-size: 14px;
    }
    </style>
<?php $__env->stopSection(); ?>      
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/download_center/upload.blade.php ENDPATH**/ ?>