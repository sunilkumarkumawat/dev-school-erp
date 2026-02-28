<?php
$role = Helper::roleType();
$branch = Helper::getAllBranch();
?>
 
<?php $__env->startSection('content'); ?>
<div class="content-wrapper">

	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
							<h3 class="card-title"><i class="fa fa-desktop"></i> &nbsp; <?php echo e(__('View Users')); ?></h3>
							<div class="card-tools">
							     <a href="<?php echo e(url('addUser')); ?>" class="btn btn-primary  btn-sm <?php echo e(Helper::permissioncheck(6)->add ? '' : 'd-none'); ?>" title="Add User"><i class="fa fa-plus"></i> <?php echo e(__('common.Add')); ?> </a>
							     
							     <a href="<?php echo e(url('user_dashboard')); ?>" class="btn btn-primary  btn-sm" title="Back User"><i class="fa fa-arrow-left"></i><?php echo e(__('common.Back')); ?></a>
							     
							     </div>
						</div>
						<div class="card-body">
						    
                            <form id="quickForm"  method="post" >
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo e(__('Branch')); ?> </label>
                                            <select class="form-control" name="branch_id" name="branch_id">
                                                <option value=""><?php echo e(__('common.Select')); ?></option>
                                            <?php if(!empty($branch)): ?> 
                                                      <?php $__currentLoopData = $branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $br): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                         <option value="<?php echo e($br->id ?? ''); ?>"  <?php echo e(($br->id == $search['branch_id']) ? 'selected' : ''); ?>><?php echo e($br->branch_name ?? ''); ?></option>
                                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>                                    
                                            </select>
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                		<div class="form-group">
                                			<label><?php echo e(__(' Role')); ?></label>
                                			<select class="select2 form-control" id="role_id" name="role_id" >
                                			<option value=""><?php echo e(__('common.Select')); ?></option>
                                             <?php if(!empty($role)): ?> 
                                                  <?php $__currentLoopData = $role; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                     <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(( $type['id'] == 3 ?? '' ) ? 'hidden' : ''); ?> <?php echo e(($type->id == $search['role_id']) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                              <?php endif; ?>
                                            </select>
                                	    </div>
                                	</div>
                                	
                                	 <div class="col-md-2">
                    					<div class="form-group">
                    						<label>Status</label>
                    						<select class="form-control" id="status" name="status">
                    						    	<option value=""><?php echo e(__('common.Select')); ?></option>
                    								<option value="1" <?php echo e((1 == $search['status']) ? 'selected' : ''); ?>><?php echo e(__('Active')); ?></option>
                    			           			<option value="0" <?php echo e((0 == $search['status']) ? 'selected' : ''); ?>><?php echo e(__('Inactive')); ?></option>
                    			           			<option value="2" <?php echo e((2 == $search['status']) ? 'selected' : ''); ?>><?php echo e(__('Dropped')); ?></option>
                    				        </select>
                    				    </div>
                    				</div>
                                	
                                   	<div class="col-md-4">
                            			<div class="form-group">
                            				<label><?php echo e(__('common.Search By Keywords')); ?></label>
                            				<input type="text" class="form-control" id="name" name="name" placeholder="<?php echo e(__('common.Ex. Name, Mobile, Email, Aadhaar etc.')); ?>" value="<?php echo e($search['name'] ?? ''); ?>">
                            		    </div>
                		            </div>                     	
                                    <div class="col-md-1 mb-md-0 mb-2">
                                         <div class="Display_none_mobile">
                                            <label class="text-white">Search</label>
                                         </div>
                                	    <button type="submit" class="btn btn-primary" ><?php echo e(__('common.Search')); ?></button>
                                	</div>	
                                </div>
                            </form> 						    
						    
							<div class="table-responsive">
							    <table id="example1" class="table table-bordered table-striped  dataTable">
								<thead class="bg-primary">
									<tr role="row">
										<th>#</th>
										<th class="text-center">Image</th>
										<th><?php echo e(__('user.Role')); ?></th>
										<th><?php echo e(__('common.Name')); ?> </th>
										<th><?php echo e(__('common.Mobile')); ?></th>
										<th><?php echo e(__('common.E-Mail')); ?></th>
										<th><?php echo e(__('user.User Name')); ?></th>
										<th><?php echo e(__('common.Password')); ?></th>
										<th><?php echo e(__('common.Status')); ?></th>
										<th>Time Table</th>
										<th><?php echo e(__('common.Action')); ?></th>
									</tr>
								</thead>
								<tbody id="user_list_show"> 
								<?php if(!empty($data)): ?> 
    								<?php 
    								    $i=1; 
    								?> 
								<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<tr>
										<td><?php echo e($i++); ?></td>
										<td class="text-center">
                                            <img class="profileImg pointer" src="<?php echo e(env('IMAGE_SHOW_PATH').'profile/'.$item['image']); ?>" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/user_image.jpg'); ?>'" data-img="<?php if(!empty($item->image)): ?> <?php echo e(env('IMAGE_SHOW_PATH').'profile/'.$item['image']); ?> <?php endif; ?>" >
                                        </td>
                                        <?php
                                            $branchIds = explode(',', $item->access_branch_id ?? '');
                                            $branches = DB::table('branch')->whereIn('id', $branchIds)->get();
                                            ?>
                                            
                                            
										<td><?php echo e($item['roleName']['name'] ?? ''); ?> <sub><?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge badge-info"><?php echo e($b->branch_name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></sub></td>
								        <td><?php echo e($item['first_name'] ?? ''); ?> <?php echo e($item['last_name'] ?? ''); ?> </td>
										<td><?php echo e($item['mobile'] ?? ''); ?></td>
										<td><?php echo e($item['email'] ?? ''); ?></td>
										<td><?php echo e($item['userName'] ?? ''); ?></td>
										<td><?php echo e($item['confirm_password'] ?? ''); ?></td>
										<td> 
										
										    <?php if($item->role_id != 2): ?>
        										<?php if($item->status==1): ?>
                                                    <button data-toggle="modal" data-target="#statusModal" data-id="<?php echo e($item['id'] ?? ''); ?>" class="btn btn-primary  w-100 btn btn-primary btn-sm userStatus" data-status="0">Active</button>
                                                <?php else: ?>
                                                    <button data-toggle="modal" data-target="#statusModal" data-id="<?php echo e($item['id'] ?? ''); ?>" class="btn btn-primary  w-75 btn btn-primary btn-sm userStatus" data-status="1">Inactive</button>
                                                <?php endif; ?> 
                                                <?php else: ?>
                                                    <select name="status" data-id="<?php echo e($item['id'] ?? ''); ?>" class="btn btn-primary form-control statusDrop w-75 <?php echo e($item->status == 0 ? 'btn btn-primary' : ''); ?> <?php echo e($item->status == 1 ?  : ''); ?> <?php echo e($item->status == 2 ? 'bg-info' : ''); ?>">
                                                        <option value="0" <?php echo e($item->status == 0 ? 'selected' : ''); ?>>Inactive</option>
                                                        <option value="1" <?php echo e($item->status == 1 ? 'selected' : ''); ?>>Active</option>
                                                        <option value="2" <?php echo e($item->status == 2 ? 'selected' : ''); ?>>Dropped Teacher</option>
                                                    </select>
                                                <?php endif; ?>    
										   
										</td>
										
										
										 <td>
										     <?php if($item->role_id == 2): ?>
										    <button class="btn btn-xs btn-primary timeTable"
                                                    data-user_id="<?php echo e($item->id); ?>"
                                                    data-userName="<?php echo e($item['first_name'] ?? ''); ?> <?php echo e($item['last_name'] ?? ''); ?>">
                                                <i class="fa fa-clock-o"></i> Time Table
                                            </button>
                                            <?php endif; ?>
										 </td>
                                        <td>
                                          <?php if(Session::get('role_id') != 3): ?> 
                                            <a class="btn btn-success btn-xs tooltip1" data-toggle="dropdown" title1="Show Option"><i class="fa fa-bars"></i></a>
                                         
                                            <ul class="dropdown-menu" style="">
                                             <a href="<?php echo e(url('relieving_letter_print_user/'.$item->id)); ?>" target="_blank" class=""><li class="dropdown-item text-success" title="Relieving letter"><i class="fa fa-print text-success"></i><?php echo e(__('Relieving letter print')); ?></li></a>
                                             <a href="<?php echo e(url('joining_letter_print_user')); ?>/<?php echo e($item->id); ?>" target="blank" class="<?php echo e(Helper::permissioncheck(6)->print ? '' : 'd-none'); ?>"><li class="dropdown-item text-success" title="Joining print"><i class="fa fa-print text-success"></i><?php echo e(__('staff.Joining print')); ?></li></a>
                                                <a href="<?php echo e(url('users_idCard')); ?>/<?php echo e($item->id); ?>" target="blank" class="<?php echo e(Helper::permissioncheck(6)->print ? '' : 'd-none'); ?>"><li class="dropdown-item text-success" title="Id Card"><i class="fa fa-print text-success"></i><?php echo e(__('Id Print')); ?></li></a>
                                                   <a href="<?php echo e(url('editUser',$item['id'])); ?>" class="<?php echo e(Helper::permissioncheck(6)->edit ? '' : 'd-none'); ?>"><li class="dropdown-item text-primary" title="Edit"><i class="fa fa-edit text-primary"></i> <?php echo e(__('common.Edit')); ?></li></a>
                                               
                                               
                                                    <a href="javascript:;"  data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#Modal_id"  class="deleteData"><li class="dropdown-item text-danger" title="Delete"><i class="fa fa-trash-o text-danger"></i> <?php echo e(__('common.Delete')); ?></li></a>     
                                            </ul>

                                             <button class="btn btn-xs btn-primary view-user-permissions <?php echo e(Helper::permissioncheck(6)->add ? '' : 'd-none'); ?> tooltip1"  title1="User Permissions" data-role="<?php echo e($item->role_id); ?>" data-user="<?php echo e($item->id); ?>">
                                                    <i class="fa fa-check-circle"></i>
                                                </button>
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
			</div>
		</div>
	</section>
</div>





<div class="modal fade" id="timeTableModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="userName"> Time Table</h5>
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>
                    <input type="hidden" id="modal_user_id">

      <div class="modal-body" id="timeTableContent">

          <div class="text-center">
              <i class="fa fa-spinner fa-spin"></i> Loading...
          </div>
      </div>
    </div>
  </div>
</div>



 
 

<div class="modal fade" id="userPermissionModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Permissions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="userPermissionContent">
        <div class="text-center">
            <i class="fa fa-spinner fa-spin"></i> Loading...
        </div>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener("click", function(e){

    let button = e.target.closest(".timeTable");

    if(button){

        // Correct way
        let userId = button.getAttribute("data-user_id");
        let userName = button.getAttribute("data-userName");

      

        $('#modal_user_id').val(userId);
        $('#userName').html(userName);
    }

});
</script>
<script>
$(document).on('click', '.timeTable', function(){

    var userId = $(this).data('user_id');

    $('#timeTableModal').modal('show');
    $('#timeTableContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');

    $.get("<?php echo e(url('user/timetable')); ?>/" + userId, function(response){
        $('#timeTableContent').html(response);
    });

});






    $(document).ready(function(){
        
        $('.statusDrop').change(function(){
           var status = $(this).val(); 
            $('#status_id').val(status);
            $('#id').val($(this).data('id'));
            $('#statusModal').modal('show');
        });
        
        $('.deleteData').click(function() {
        	var delete_id = $(this).data('id');
        	$('#delete_id').val(delete_id);
        });
        
        $('.userStatus').click(function(){
            var status = $(this).data('status');
            $('#status_id').val(status);
            $('#id').val($(this).data('id'));
        });
    });
    
    
    
   $(document).on('click', '.view-user-permissions', function() {
    var userId = $(this).data('user');
    var roleId = $(this).data('role');

    $('#userPermissionContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
    $('#userPermissionModal').modal('show');

    $.get("<?php echo e(url('user/permissions')); ?>/" + userId, function(data) {
       
        $('#userPermissionContent').html(data);
        
        $('.row-select-all').on('change', function() {
            let moduleId = $(this).data('module-id');
            let checked = $(this).is(':checked');
            $('input.permission-checkbox[data-module-id="'+moduleId+'"]').prop('checked', checked);
        });

        $('.check-type').on('change', function() {
            let type = $(this).data('type');
            $('input.permission-checkbox.' + type).prop('checked', $(this).is(':checked'));
        });
    });
});

</script>

<div class="modal fade" id="statusModal">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #002c54;">
      <div class="modal-header">
        <h4 class="modal-title text-white">Change Status Conformation</h4>
        <button type="button" class="btn-close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>

      <form action="<?php echo e(url('userStatus')); ?>" method="post">
            <?php echo csrf_field(); ?>
      <div class="modal-body">
          <input type="hidden" id="status_id" name="status_id">
          <input type="hidden" id="id" name="id">
          <h5 class="text-white">Are you sure you want to Change Status ?</h5>
           
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" >Close</button>
            <button type="submit" class="btn btn-danger waves-effect waves-light">Submit</button>
         </div>
       </form>
    </div>
  </div>
</div>

<!-- The Modal -->
<div class="modal" id="Modal_id">
	<div class="modal-dialog">
		<div class="modal-content" style="background: #002c54;">
			<div class="modal-header">
				<h4 class="modal-title text-white"><?php echo e(__('common.Delete Confirmation')); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
			<form action="<?php echo e(url('deleteUser')); ?>" method="post">
			     <?php echo csrf_field(); ?>
				<div class="modal-body">
					<input type=hidden id="delete_id" name=delete_id>
					<h5 class="text-white"><?php echo e(__('common.Are you sure you want to delete')); ?> ?</h5> </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal"><?php echo e(__('common.Close')); ?></button>
					<button type="submit" class="btn btn-danger waves-effect waves-light"><?php echo e(__('common.Delete')); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="profileImgModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <img id="profileImg" src="" width="100%" height="100%">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>  
<style>
    .profileImg {
        width:50px;
        height:50px;
        border-radius:50%;
    }
    
    .statusDrop option{
        background-color: white !important;
        color:black !important;
    }
</style>

<script>
    $('.profileImg').click(function(){
        var profileImgUrl = $(this).data('img');
        if(profileImgUrl != ''){
            $('#profileImgModal').modal('toggle');
            $('#profileImg').attr('src',profileImgUrl);
        }
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/user/users/view.blade.php ENDPATH**/ ?>