<?php
$role = Helper::roleType();
?>
 
<?php $__env->startSection('content'); ?>
<div class="content-wrapper">

	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
							<h3 class="card-title"><i class="fa fa-code-fork"></i> &nbsp;<?php echo e(__('master.View Branch')); ?></h3>
							<div class="card-tools">
							   
							     <a href="<?php echo e(url('addBranch')); ?>" class="btn btn-primary  btn-sm <?php echo e(Helper::permissioncheck(9)->add ? '' : 'd-none'); ?>" title="Add Branch"><i class="fa fa-plus"></i><?php echo e(__('common.Add')); ?> </a>
							    
							     
							     <a href="<?php echo e(url('master_dashboard')); ?>" class="btn btn-primary  btn-sm" title="Back User"><i class="fa fa-arrow-left"></i><?php echo e(__('common.Back')); ?></a>
							     
							     </div>
						</div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
           
                <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-responsive dataTable dtr-inline ">
                  <thead class="bg-primary">
                  <tr role="row">
                      <th><?php echo e(__('master.Sr. No.')); ?></th>
                            <th><?php echo e(__('master.Branch Code')); ?> </th>
                            <th><?php echo e(__('master.Branch Name')); ?> </th>
                            <th><?php echo e(__('master.Contact Person')); ?></th>
                            <th><?php echo e(__('common.Mobile')); ?></th>
                            <th><?php echo e(__('common.Email')); ?></th>
                            <!-- <th><?php echo e(__('master.Country')); ?></th> -->
                            <th><?php echo e(__('common.State')); ?> </th>
                            <th><?php echo e(__('common.City')); ?> </th>
                            <!-- <th><?php echo e(__('master.Pin Code')); ?></th> -->
                            <th><?php echo e(__('common.Address')); ?></th>
                          
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
                                <td><?php echo e($item['branch_code']); ?></td>
                                <td><?php echo e($item['branch_name']); ?></td>
                                <td><?php echo e($item['contact_person']); ?></td>
                                <td><?php echo e($item['mobile']); ?></td>
                                <td><?php echo e($item['email']); ?></td>
                                <!-- <td><?php echo e($item['country']); ?></td> -->
                                <td><?php echo e($item['state']); ?></td>
                                <td><?php echo e($item['city']); ?></td>
                                <!-- <td><?php echo e($item['pin_code']); ?></td> -->
                                <td><?php echo e($item['address']); ?></td>
                               
                                <td>
                                   
                                    <a class="btn btn-primary btn-xs tooltip1 <?php echo e(Helper::permissioncheck(9)->edit ? '' : 'd-none'); ?>" href="<?php echo e(url('editBranch',$item->id)); ?>" title1="Edit"><i class="fa fa-edit"></i></a>
                                  
                                <?php if($item->id !== 1): ?>
                                <a class="delete btn btn-danger  btn-xs ml-2 tooltip1 <?php echo e(Helper::permissioncheck(9)->delete ? '' : 'd-none'); ?>"data-id='<?php echo e($item->id); ?>'  href="javascript:"data-bs-toggle="modal" data-bs-target="#Modal_id" title1="Delete"><i class="fa fa-trash"></i></a>
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
      
    </section>
    
  	</div>
				</div>
			</div>
		</div>
	</div>
	</section>
</div>
        
        
    <script src="<?php echo e(URL::asset('public/assets/school/js/jquery.min.js')); ?>"></script>
    
<script>
  $('.delete').click(function(){
    $('#delete_id').val($(this).data('id'));
    $('#Modal_id').modal('show');
  });
 </script>

<!-- The Modal -->
<div class="modal" id="Modal_id">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #555b5beb;">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-white"><?php echo e(__('common.Delete Confirmation')); ?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>

      <!-- Modal body -->
      <form action="<?php echo e(url('deleteBranch')); ?>" method="post">
              	 <?php echo csrf_field(); ?>
      <div class="modal-body">
              
            
            
              <input type="hidden" id="delete_id" name="delete_id">
              <h5 class="text-white"><?php echo e(__('common.Are you sure you want to delete')); ?>  ?</h5>
           
      </div>

      <div class="modal-footer">
                    <button type="button" class="btn btn-close btn-default waves-effect remove-data-from-delete-form"aria-hidden="true" data-bs-dismiss="modal"><?php echo e(__('common.Close')); ?></button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light"><?php echo e(__('common.Delete')); ?></button>
         </div>
       </form>

    </div>
  </div>
</div>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/branch/viewBranch.blade.php ENDPATH**/ ?>