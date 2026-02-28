 <?php $__env->startSection('content'); ?>

<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 pr-0">
                    <div class="card card-outline card-orange mr-1">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-leanpub"></i> &nbsp;<?php echo e(__('master.Add Subject')); ?></h3>
                            <div class="card-tools"><!--<a href="<?php echo e(url('master_dashboard')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i> Back</a>--></div>
                        </div>

                        <form id="form-submit-AddSubject" action="<?php echo e(url('create_subject')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="row m-2">
                             
                                <div class="col-md-12">
                                    <div class="form-group">
				
                                    <label class="text-danger"><?php echo e(__('master.Subject')); ?>*</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['add_subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="add_subject"
                                        name="add_subject"
                                        placeholder="<?php echo e(__('master.Subject')); ?>"
                                        value="<?php echo e(old('add_subject')); ?>"
                                    />
                                   
                                    </div>
                                     <input type="checkbox" class="" 
                                        id="other_subject"
                                        name="other_subject"
                                        placeholder="<?php echo e(__('Other Subject')); ?>"
                                        value="1"
                                    /> Other Subject
                                </div>
                            </div>

                            <div class="row m-2">
                                <div class="col-md-12 text-center"><button type="submit" class="btn btn-primary btn-submit"><?php echo e(__('messages.submit')); ?></button><br /></div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-8 pl-0">
                    <div class="card card-outline card-orange ml-1">
                        <div class="card-header bg-primary flex_items_toggel">
                            <h3 class="card-title"><i class="fa fa-leanpub"></i> &nbsp;<?php echo e(__('master.View Subject')); ?></h3>
                            <div class="card-tools">
                                <a href="<?php echo e(url('master_dashboard')); ?>" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i><span class="Display_none_mobile"><?php echo e(__('messages.Back')); ?></span></a>
                            </div>
                        </div>
                        <form id="form-submit-edit" action="<?php echo e(url('multi_edit_subject')); ?>" method="post" >
                            <?php echo csrf_field(); ?>
                           
                        <div class="row m-2">
                         

            	
                    	
                            <div class="col-md-12" style="overflow-x:scroll;">
                                <table id="example1" class="table table-bordered table-striped dataTable dtr-inline nowrap">
                                    <thead class="bg-primary">
                                        <tr role="row">
                                            <th><?php echo e(__('messages.Sr.No.')); ?></th>
                                            <th><?php echo e(__('messages.Subject')); ?></th>
                                       
                                            <th><?php echo e(__('Delete')); ?></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php if(!empty($data) && count($data) > 0): ?>
                                        <?php $i=1 ;
                                        
                                      
                                        ?> 
                                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        
                                        <tr>
                                            
                                            <td><?php echo e($i++); ?></td>
                                            <td>
                                                <input type="hidden" value="<?php echo e($item['id'] ?? ''); ?>" name="id[]"  /> 
                                                <input type="text" value="<?php echo e($item['name'] ?? ''); ?>" name="add_subject[]"  />  &nbsp; <input type="radio" value="0" name="other_subject_<?php echo e($item->id); ?>"  <?php echo e($item['other_subject'] == 1 ? '' : 'checked'); ?> /> Main &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" style='accent-color:red' value="1" name="other_subject_<?php echo e($item->id); ?>"  <?php echo e($item['other_subject'] == 1 ? 'checked' : ''); ?> /> Other
                                                
                                                </td>
                                          
                                            <td>
                                           <!--<a href="<?php echo e(url('edit_create_subject')); ?>/<?php echo e($item['id'] ?? ''); ?>" class="btn btn-primary  btn-xs" title="Edit" ><i class="fa fa-edit"></i></a> -->
                                              <a href="javascript:;" data-id='<?php echo e($item['id']); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-primary btn-xs ml-3 tooltip1" title1="Delete"><i class="fa fa-trash-o"></i></a>
                                              </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                         <?php else: ?>
                                         <tfoot>
                                       <tr>
                                                <td colspan="3" style="text-align: center;">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                      Add Subject 
                                    </button>
                                                </td>
                                            </tr>
                                      </tfoot>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php if(!empty($data) && count($data) > 0): ?>
                         <div class="row m-2">
                                <div class="col-md-12 text-center"><button type="submit" class="btn btn-submit btn-primary"><?php echo e(__('Update')); ?></button><br /></div>
                            </div>
                        	<?php endif; ?>	
                </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

  
       <script src="<?php echo e(URL::asset('public/assets/school/js/jquery.min.js')); ?>"></script>

<script>
    $(".deleteData").click(function () {
        var delete_id = $(this).data("id");

        $("#delete_id").val(delete_id);
    });
</script>



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
            <form action="<?php echo e(url('delete_create_subject')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" id="delete_id" name="delete_id" />
                    <h5 class="text-white"><?php echo e(__('messages.Are you sure you want to delete')); ?> ?</h5>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal"><?php echo e(__('messages.Close')); ?></button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo e(__('messages.Delete')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
  <script src="<?php echo e(URL::asset('public/assets/school/js/form/form_save.js')); ?>"></script> 
 <?php
                                    $all_subjects = DB::table('all_subjects')
                                        ->where('branch_id', session('branch_id'))
                                        ->orderBy('id', 'ASC')
                                        ->groupBy('name')
                                        ->whereNull('deleted_at')
                                        ->get();
                                
                            ?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?php echo e(url('save-selected-subjects')); ?>" method="POST">
      <?php echo csrf_field(); ?>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fs-5" id="exampleModalLabel">Select Subjects</h5>
          <button type="button" class="fa fa-times" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white">
              <tr role="row">
                <th><?php echo e(__('common.SR.NO')); ?></th>
                <th><?php echo e(__('Subjects')); ?></th>
                <th>      <input type="checkbox" id="select_all" checked/> Select All
</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              <?php if(!empty($all_subjects) && count($all_subjects) > 0): ?>
                <?php $__currentLoopData = $all_subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                    <td><?php echo e($i++); ?></td>
                    <td><input class="form-control" type="text" name="subjects[<?php echo e($item1->id ?? ''); ?>]"  id="subjects" 
                    placeholder="Subjects Name" value="<?php echo e($item1->name ?? ''); ?>" >

                        </td>
                    <td>
                      <input type="checkbox"
                             class="group_checkbox"
                             name="Allsubjects_id[]"
                             value="<?php echo e($item1->id); ?>"
                             checked>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php else: ?>
                <tr><td colspan="3" class="text-center">No class found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Subjects</button>
        </div>
      </div>
    </form>
  </div>
</div>

    
     <script>
      document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select_all');
        const checkboxes = document.querySelectorAll('.group_checkbox');
    
        selectAll.addEventListener('change', function () {
          checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });
      });
    </script> 
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/subject/create_subject.blade.php ENDPATH**/ ?>