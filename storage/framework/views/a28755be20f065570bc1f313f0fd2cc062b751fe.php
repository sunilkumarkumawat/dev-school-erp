<?php
   $classType = Helper::classType();
    $getAttendanceStatus= Helper::getAttendanceStatus();
  
?>
 
<?php $__env->startSection('content'); ?>

<style>
    .paddingTable thead tr{
        background:#002c54;
        color:white;
    }
    
    .paddingTable thead tr th{
        padding:5px;
    }
</style>


<input type="hidden" id="session_id" value="<?php echo e(Session::get('role_id') ?? ''); ?>">
 <div class="content-wrapper">

   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
         <div class="card card-outline card-orange">
         <div class="card-header bg-primary">
        <h3 class="card-title"><i class="fa fa-calendar-check-o"></i> &nbsp;<?php echo e(__('Create Fees Group for New Session')); ?></h3>
        <div class="card-tools">
        <a href="<?php echo e(url('studentsDashboard')); ?>" class="btn btn-primary  btn-sm" ><i class="fa fa-arrow-left"></i><span class="Display_none_mobile"><?php echo e(__('messages.Back')); ?></span></a>
        </div>
        
        </div>         
        <form id="quickForm" action="<?php echo e(url('student/promote_add')); ?>" method="post" >
            <?php echo csrf_field(); ?> 
            <div class="row m-2">
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="State" class="required">Admission No.</label>
                     <input type="text" class="form-control" id="admissionNo" name="admissionNo" placeholder="Admission No." value="<?php echo e($search['admissionNo'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-2">
            		<div class="form-group">
            			<label><?php echo e(__('messages.Class')); ?></label>
            			<select class="form-control select2" id="class_type_id1" name="class_type_id" required>
            			<option value=""><?php echo e(__('messages.Select')); ?></option>
                         <?php if(!empty($classType)): ?> 
                              <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e($search['class_type_id'] == $type->id ? 'selected' : ''); ?> ><?php echo e($type->name ?? ''); ?></option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          <?php endif; ?>
                        </select>
            	    </div>
            	</div>
                <div class="col-md-4">
            		<div class="form-group">
            			<label><?php echo e(__('messages.Search By Keywords')); ?></label>
            			<input type="text" class="form-control"  name="name" placeholder="<?php echo e(__('messages.Ex. Student Name, Father/ Mother Name, Mobile etc.')); ?>" value="<?php echo e($search['name'] ?? ''); ?>"> 
            	    </div>
            	</div> 
                <div class="col-md-1 ">
                   <div class="Display_none_mobile">
                        <label for="" class="text-white">Search</label>
                   </div>
            	    <button type="submit" class="btn btn-primary"  ><?php echo e(__('messages.Search')); ?></button>
            	</div>
            </div>
        </form>        

    </div>
     <?php if(!empty($data)): ?> 
    <div class="card card-default">
         <div class="card-header ">
        <h3 class="card-title"><?php echo e(__('The Next Session Was Transferred To The Students')); ?></h3>
      
        
        </div>         
            <div class="col-md-12">
            	<div class=" alert-subl mb-lg m-2">
            		<strong>Instructions :</strong><br>
            		1. The Roll field shows the previous roll and you can manually add new roll for promoted session.<br>
            		2. All fields from the previous session will be transferred to the new session.<br>
            		3. Students with unchecked checkboxes will not be promoted.<br>
            		4. Failed students will  pormote in same  class in next session.<br>
            		5. Before promoting students to a new class, the fee structure (Fees Group, Fees Master) must be created.<br>
            		5.<span style="color: red;">*</span>. The Carry Forward (Select All) checkbox will apply only to students who have pending fees.<br>
            	</div>
            </div>
        <form id="form-submit-promote" action="<?php echo e(url('studentsPromoteAdd')); ?>" method="post" >
                <?php echo csrf_field(); ?> 
                <div class="row m-2">
                	<div class="col-md-2">
                		<div class="form-group">
                			<label class="text-danger"><?php echo e(__('messages.Date')); ?>*</label>
                			<input class="form-control <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="date" id="date" name="date" value="<?php echo e(date('Y-m-d')); ?>">
                              	<?php $__errorArgs = ['date'];
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
                	</div> 
                	<div class="col-md-2">
                	    <div class="form-group">
                	        <label class="text-danger">In Session *</label>
                	       	<select class="form-control" id="new_session_id" name="session_id" required >
                                  <?php if(!empty($session)): ?> 
                                  <option value=""><?php echo e(__('messages.Select')); ?></option>
                                      <?php $__currentLoopData = $session; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                         <option value="<?php echo e($item->id ?? ''); ?>" 
                                         <?php if(Session::get('session_id')+1 > $item->id): ?>
                                       <?php echo e("disabled"); ?>

                                        
                                         <?php endif; ?>
                                         ><?php echo e($item->from_year ?? ''); ?><?php echo e("-"); ?><?php echo e($item->to_year ?? ''); ?></option>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  <?php endif; ?>  
                                </select>      
                	    </div>
                	</div>
                	<div class="col-md-2">
                	    <div class="form-group">
                	        <label class="text-danger">Promote To*</label>
                	        <select class="form-control select2" id="promote_class_type_id" name="promote_class_type_id"  >
                			<option value=""><?php echo e(__('messages.Select')); ?></option>
                            </select>
                	    </div>
                	</div>
                             <div id="no_class_box" class="alert alert-warning mt-2" style="display:none;">
                    <strong>
                        No class found for the 
                        <span id="selected_new_session_text"></span> session ❌
                    </strong><br>
                    Please change the session or add a class first.
                </div>


                   
                	</div> 
                
           
                	<div class="col-md-12">
             
            	<div class="table-responsive">
            	    <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select_all" checked>
                                        <label for="select_all">Select All</label>
                                    </th>
                                    <th>Admission No.</th>
                                    <th>Name
                                    <br>
                                    <spam>Father Name</spam></th>
                                    <th>Class</th>
                                    <th>
                                        Pending Fees
                                        <br>
                                        <input type="checkbox" id="select_allFees" checked>
                                        <label for="select_allFees">Carry Forward</label>
                                    </th>
                                    <th>Roll No.</th>
                                    <th>Promote Status</th>
                                </tr>
                            </thead>
                        
                            <tbody>
                            <?php $i=1; ?>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $pendingAmount = Helper::CarryForwardFees($item->id) ?? 0;
                                ?>
                        

                                <tr class=" <?php echo e($pendingAmount > 0 ? 'pending-row' : ''); ?>  <?php echo e($item->promote_date ? 'promoted-row' : ''); ?>">
                                    <td>
                                         <?php echo e($i++); ?>

                                        
                                        <?php if($item->promote_date): ?>
                                            <span class="promoted-label">PROMOTED STUDENT</span>
                                        <?php endif; ?>
                                    
                                        <?php if($item->promote_date == NULL): ?>
                                            <input
                                                type="checkbox"
                                                class="admission_checkbox"
                                                name="admission_ids[]"
                                                value="<?php echo e($item->id); ?>"
                                                checked
                                            >
                                        <?php endif; ?>
                                    </td>

                        
                                    <td><?php echo e($item->admissionNo); ?></td>
                                    <td><?php echo e($item->first_name); ?> <?php echo e($item->last_name); ?>

                                 <br>
                                    <spam><?php echo e($item->father_name); ?></spam></td>
                                        
                                    <td><?php echo e($item->ClassTypes->name ?? ''); ?>

                                    
                                    </td>
                        
                                    <td>
                                        <?php if($pendingAmount > 0): ?>
                                         <input
                                            type="checkbox"
                                            class="fees_checkbox"
                                            name="carry_forward_ids[]"
                                            value="<?php echo e($item->id); ?>"
                                            <?php echo e($pendingAmount > 0 ? 'checked' : 'disabled'); ?> >
                                        ₹ <?php echo e($pendingAmount); ?>

                                       <?php else: ?>
                                       <span class="text-success">
                                            ✔ No Pending Fees
                                        </span>

                                       <input type="hidden"  name="carry_forward_ids[]"    value="<?php echo e($item->id); ?>" >
                                       <?php endif; ?>
                                    </td>
                        
                                    <td>
                                        <input
                                            type="text"
                                            class="form-control roll_no"
                                            name="roll_no[<?php echo e($item->id); ?>]"
                                            value="<?php echo e($item->roll_no ?? ''); ?>"
                                        >
                                    </td>
                        
                                    <td>
                                        <label>
                                            <input type="radio" name="promote_status[<?php echo e($item->id); ?>]" value="1" checked>
                                            Promote
                                        </label>
                                        <label class="ms-2">
                                            <input type="radio" name="promote_status[<?php echo e($item->id); ?>]" value="2">
                                            Running
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>

            	</div>
                </div>
                 <?php if(!empty($data)): ?>
                <div class="row m-2">
                    <div class="col-md-12 text-center"><button type="submit"  class="btn btn-primary btn-submit" ><?php echo e(__('messages.Submit')); ?></button></div>
                </div>
                <?php endif; ?>
                </form>  
                </div>
                 <?php endif; ?>    
                            
    </div>
</div>
</div>
</div>
</section>
        
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select the fees that should be assigned to all students.</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p></p><table class="table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Session</th>
                            <th>Class</th>
                            <th>Fees Group</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody id='fees_group_show'>
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id='save_changes'>Save Changes</button>
                <!-- Additional buttons can be added here -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="error_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content shadow">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    Fees Group Required
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"> ❌  </button>
            </div>

            <div class="modal-body text-center px-4">
                <p class="mb-2 fw-semibold">
                    No fees group is available for the selected class and session.
                </p>
                <p class="text-muted mb-0">
                    Please create a fees group for the new session to continue with the process.
                </p>
            </div>

            <div class="modal-footer justify-content-center">
                <a href="<?php echo e(url('fees_group_add')); ?>" class="btn btn-primary px-4">
                    Create Fees Group
                </a>
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                    skipped
                </button>
            </div>

        </div>
    </div>
</div>

<style>
    .alert-subl {
    color: #31708f;
    border-color: #ddd;
}
.pending-row {
        background-color: #fff3cd !important; /* light yellow */
    }


.promoted-row{
    background:#ffe6e6;
    border-left:5px solid red;
    position:relative;
}
.promoted-label{
    position: absolute;
  top: 0px;
  left: 0%;
  background: #ff00008c;
  color: #fff;
  font-size: 22px;
  padding: 2px 10px;
  border-radius: 3px;
  font-weight: bold;
  z-index: 10;
  width: 100%;
  height: 94%;
  text-align: center;
}
</style>
<script>
$( document ).ready(function() {
$('#promote_class_type_id').change(function(){
    var newClass = $(this).val(); 
    var newSession = $('#new_session_id').val(); 
    $('#group_ids').val(""); 	
    if(newClass != ""){
        var URL = "<?php echo e(url('/')); ?>"; 
        $.ajax({
            headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')}, 
            type: 'post',
            url: URL + '/getFeesGroup', 
            data: {
                class_type_id: newClass,
                session_id: newSession
            },
            success: function (data) {
            var data1 = data.data;
                if(data1.length != 0){
                    var container = $('#fees_group_show');
                        container.html('');
                       data1.forEach(function(item) {
                        var newData = $('<tr class="new-data">' +
                                        '<td><input type="checkbox" class="fees_group_checked" data-id="' + item.id + '"></td>' +
                                        '<td>' + (item.from_year ?? '') + '-' + (item.to_year ?? '') + '</td>' +
                                        '<td>' + (item.class_name ?? '') + '</td>' +
                                        '<td>' + (item.fees_group_name ?? '') + '</td>' +
                                        '<td>' + (item.amount ?? '') + '</td>' +
                                    '</tr>');
                        container.append(newData);
                       $("#myModal").modal('show');
                    });  
                }else{
                    $('#error_modal').modal('show');
                }
                 
               
               
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText); 
            }
     });
    }
});
 
$('#save_changes').click(function(){
    var fees_masters = [];

    $( ".fees_group_checked" ).each(function( index ) {

    if ($(this).is(':checked')) {
fees_masters.push($(this).attr('data-id'))

    }

});

    
$('#fees_master').val(fees_masters);    
$('#myModal').modal('hide');
});
});





    
$( document ).ready(function() {
    var session_id = $('#session_id').val();
    if(session_id != 1){
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("date")[0].setAttribute('min', today);        
    }
}); 

$('#new_session_id').change(function () {

    var new_session_id   = $(this).val();
    var session_text    = $('#new_session_id option:selected').text();

    $('#promote_class_type_id').html('<option value="">Loading...</option>');
    $('#no_class_box').hide();

    if (new_session_id !== '') {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "<?php echo e(url('get-class-by-session')); ?>",
            data: { session_id: new_session_id },
            success: function (response) {

                var options = '<option value="">Select Class</option>';

                if (response.data && response.data.length > 0) {

                    response.data.forEach(function (item) {
                        options += '<option value="' + item.id + '">' + item.name + '</option>';
                    });

                    $('#promote_class_type_id').html(options);

                } else {
                    // ❌ No class for selected session
                    $('#promote_class_type_id').html('<option value="">No Class Found</option>');

                    // 👉 set session text dynamically
                    $('#selected_new_session_text').text(session_text);

                    // 👉 show warning box
                    $('#no_class_box').show();
                }
            }
        });
    }
});


</script>  
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

  })

</script>

<script>
    $(document).ready(function() {
    // Select All Checkbox Functionality
    $("#select_all").on("change", function() {
        $(".admission_checkbox").prop("checked", $(this).prop("checked"));
    });

    // Individual Checkbox Functionality
    $(".admission_checkbox").on("change", function() {
        if ($(".admission_checkbox:checked").length === $(".admission_checkbox").length) {
            $("#select_all").prop("checked", true);
        } else {
            $("#select_all").prop("checked", false);
        }
    });
    
      $("#select_allFees").on("change", function() {
        $(".fees_checkbox").prop("checked", $(this).prop("checked"));
    });

    // Individual Checkbox Functionality
    $(".fees_checkbox").on("change", function() {
        if ($(".fees_checkbox:checked").length === $(".fees_checkbox").length) {
            $("#select_allFees").prop("checked", true);
        } else {
            $("#select_allFees").prop("checked", false);
        }
    });
});
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        let typingTimer;
        const doneTypingInterval = 500;

        function checkForDuplicates(input) {
            let currentValue = input.val().trim();
            let isDuplicate = false;

            if (currentValue === "") {
                input.removeClass("is-invalid");
                input.siblings(".error-message").hide();
                return;
            }

            $(".roll_no").each(function () {
                if (this !== input[0]) {
                    let val = $(this).val().trim();
                    if (val !== "" && val === currentValue) {
                        isDuplicate = true;
                        return false; // break
                    }
                }
            });

            let errorMessage = input.siblings(".error-message");

            if (isDuplicate) {
                input.val(""); // Clear
                input.addClass("is-invalid");
                errorMessage.show();
            } else {
                input.removeClass("is-invalid");
                errorMessage.hide();
            }
        }

        $(document).on("input", ".roll_no", function () {
            let input = $(this);
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function () {
                checkForDuplicates(input);
            }, doneTypingInterval);
        });

        // Also check when input loses focus
        $(document).on("blur", ".roll_no", function () {
            checkForDuplicates($(this));
        });
    });
</script>


<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/students/promote/promote.blade.php ENDPATH**/ ?>