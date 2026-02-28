<?php
  $feesType = Helper::feesType();

?>
 
<?php $__env->startSection('content'); ?>

<div class="content-wrapper">
	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
							<h3 class="card-title"><i class="fa fa-id-badge"></i> &nbsp;<?php echo e(__('master.Leave Applications')); ?></h3>
							<div class="card-tools"> <a href="<?php echo e(url('master_dashboard')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><?php echo e(__('messages.Back')); ?> </a> </div>
						</div>
    

    <div class="row">
        <div class="col-md-12 pl-2">
                <div class="row m-2">
                    <div class="col-md-12">
                            <h5><?php echo e(__('master.Applied leave list')); ?></h5><hr>
                	</div>
                    <div class="col-md-12">
                       <table id="example1" class="table table-bordered table-striped dataTable dtr-inline ">
                          <thead class="bg-primary">
                          <tr role="row">
                              <th><?php echo e(__('messages.Sr.No.')); ?></th>
                             
                                <th><?php echo e(__('master.Status')); ?></th>
                             
                              <th><?php echo e(__('Student Name')); ?></th>
                              <th><?php echo e(__('messages.Subject')); ?></th>
                              <th><?php echo e(__('Date')); ?></th>
                              <th><?php echo e(__('master.Reason')); ?></th>
                              <!--<th>Action</th>-->
                              
                              
                              
                          </thead>
                          <tbody id="">
                           <?php if(!empty($dataview)): ?>
                                <?php
                                        $i=1
                                    ?>
                                    <?php $__currentLoopData = $dataview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($item['status']=="2"): ?>
                                    <?php
                                        $width="w-50";
                                        $bg="bg-yellow";
                                    ?>
                                    <?php else: ?>
                                    <?php
                                     $bg="bg-white";
                                        $width="w-100";
                                    ?>
                                    <?php endif; ?>
                                 
                                <tr class="<?php echo e($bg); ?>">

                                        <td><?php echo e($i++); ?></td>
                                      
                                        <td >
                                            <a data-id="<?php echo e($item['id'] ?? ''); ?>" style="<?php echo e($item['status'] == 1 ? 'display:none'   : ''); ?>" data-status="1" class="btn btn-xs btn-success reminder_status <?php echo e($width); ?>" data-text="Activate"> &nbsp; <?php echo e(__('messages.Approve')); ?> &nbsp;</a>
                                            <a data-id="<?php echo e($item['id'] ?? ''); ?>" style="<?php echo e($item['status'] == 0 ? 'display:none'   : ''); ?>" data-status="0" class="btn btn-xs btn-danger reminder_status <?php echo e($width); ?>" data-text="Deactivate"><?php echo e(__('messages.Deny')); ?></a>                                                               
                                        </td>
                                        
                                        <td><?php echo e($item['first_name'] ?? ''); ?> <?php echo e($item['last_name'] ?? ''); ?></td>
                                        <td><?php echo e($item['subject'] ?? ''); ?></td>
                                        <td><?php echo e(date('d-m-Y', strtotime($item['from_date'])) ?? ''); ?> / <?php echo e(date('d-m-Y', strtotime($item['to_date'])) ?? ''); ?></td>
                                        
                                        <td><?php echo e($item['reason'] ?? ''); ?></td>
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
</div>
</div>
</section>
</div>

<script>
    $(document).on('click', ".reminder_status", function () {
var id = $(this).data("id");
 var basurl = "<?php echo e(url('/')); ?>";
   var status = $(this).data("status");
    if(confirm('Are you sure ?')){
        $.ajax({
            url: basurl + '/leaveStatus',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { status: status, id: id },
            success: function (response) {
                // alert(JSON.stringify(response));
                if (response == 0) {
                  location.reload(true);
                }else if (response == 1) {
                  location.reload(true);
                }
                else {
                    alert("Internal Servasaser Error");
                }
            },
        });
    }

});

</script>


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

      <form action="<?php echo e(url('leave_delete')); ?>" method="post">
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

<style>
    .w-50 {
  width: 47% !important;
}
</style>
<?php $__env->stopSection(); ?>      
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/leave/add.blade.php ENDPATH**/ ?>