<?php

$classType = Helper::classType();
$getPaymentMode = Helper::getPaymentMode();

  $array = [];
?>

<?php $__env->startSection('content'); ?>

<style>
    .padding_table thead tr{
        background: #002c54;
        position: sticky;
        top: 0;
        color: white;
        /*box-shadow: 0px 4px 6px #a8a8a8;*/
    }
    
    .padding_table thead tr th{
        padding:5px !important;
    }
    
    .padding_table tr th, .padding_table tr td{
        font-size:14px;
    }
</style>
<div class="content-wrapper">
    
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-outline card-orange mb-0">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-money"></i> &nbsp;<?php echo e(__('fees.Collect Student Fees')); ?></h3>
                            <div class="card-tools">
                                <a href="<?php echo e(url('fees/index')); ?>" class="btn btn-primary  btn-sm <?php echo e(Helper::permissioncheck(11)->view ? '' : 'd-none'); ?>" title="View Fees"><i class="fa fa-eye"></i><?php echo e(__('common.View')); ?> </a>
                                <a href="<?php echo e(url('fee_dashboard')); ?>" class="btn btn-primary  btn-sm" title="Back"><i class="fa fa-arrow-left"></i><?php echo e(__('common.Back')); ?> </a>
                            </div>

                        </div>
                        <div class="card-body">
                            <form id="quickForm" method="post" action="<?php echo e(url('Fees/add')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="row m-2">
                                            <div class="col-md-2">
									<div class="form-group">
										<label>Admission Type(Non RTE)</label>
										<select class="form-control invalid" id="admission_type_id" name="admission_type_id">
											<option value=""><?php echo e(__('common.Select')); ?></option>
										    <option value="1" <?php echo e(old('admission_type_id', $search['admission_type_id'] ?? '') == 1 ? 'selected' : ''); ?>>Yes</option>
                                            <option value="2" <?php echo e(old('admission_type_id', $search['admission_type_id'] ?? '') == 2 ? 'selected' : ''); ?>>No</option>

										</select>
									  
									</div>
								</div>
                            
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo e(__('common.Class')); ?></label>
                                            <select class="form-control select2 <?php $__errorArgs = ['class_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="class_type_id" name="class_type_id">
                                                <option value=""><?php echo e(__('common.Select')); ?></option>
                                                <?php if(!empty($classType)): ?>
                                                <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(old('class_type_id', $type->id == $search['class_type_id'] ?? '' ) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                <?php $__errorArgs = ['class_type_id'];
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
                                            <label>Search Type</label>
                                            <select class="form-control" id="search_type" name="search_type">
                                                <option value=""><?php echo e(__('common.Select')); ?></option>
                                                <option value="first_name" <?php echo e(old('search_type', $search['search_type'] ?? '') == 'first_name' ? 'selected' : ''); ?>>Name</option>
                                                <option value="admissionNo" <?php echo e(old('search_type', $search['search_type'] ?? '') == 'admissionNo' ? 'selected' : ''); ?>>Admission No</option>
                                                <option value="father_name" <?php echo e(old('search_type', $search['search_type'] ?? '') == 'father_name' ? 'selected' : ''); ?>>Father Name</option>
                                                <option value="mother_name" <?php echo e(old('search_type', $search['search_type'] ?? '') == 'mother_name' ? 'selected' : ''); ?>>Mother Name</option>
                                                <option value="mobile" <?php echo e(old('search_type', $search['search_type'] ?? '') == 'mobile' ? 'selected' : ''); ?>>Mobile</option>
                                                <option value="aadhaar" <?php echo e(old('search_type', $search['search_type'] ?? '') == 'aadhaar' ? 'selected' : ''); ?>>Aadhaar</option>
                                                <option value="jan_aadhaar" <?php echo e(old('search_type', $search['search_type'] ?? '') == 'jan_aadhaar' ? 'selected' : ''); ?>>Jan Aadhaar</option>
                                                <option value="address" <?php echo e(old('search_type', $search['search_type'] ?? '') == 'address' ? 'selected' : ''); ?>>Address</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo e(__('common.Search By Keywords')); ?></label>
                                            <input type="text" class="form-control" value="<?php echo e(old('name',$search['name']) ?? ''); ?>" id="name" name="name" placeholder="<?php echo e(__('common.Search By Keywords')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-1 ">
                                        <div class="form-group">
                                            <label class="text-white"><?php echo e(__('common.Search')); ?></label>
                                            <button type="submit" class="btn btn-primary"><?php echo e(__('common.Search')); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php if(!empty($data)): ?>
                            
                            <div class="row m-2" >
                                
                                <div class='col-12 col-md-7' style="max-height: 225px;overflow-y: scroll;">
                                <table class="table table-bordered small_td padding_table" id="trColor">
                                    <thead>
                                        <tr>
<!--                                            <th>RTE/Non RTE</th>
-->                                            <th>Ledger No.</th>
                                            <!--<th>Image</th>-->
                                            <th class="text-center"><?php echo e(__('student.Admission No.')); ?> </th>
                                            <th><?php echo e(__('common.Name')); ?></th>
                                            <th><?php echo e(__('common.Class')); ?> </th>
                                            <!--<th><?php echo e(__('common.Fathers Name')); ?></th>-->
                                            <!--<th><?php echo e(__('common.Mothers Name')); ?></th>-->
                                            <?php if(Session::get('role_id') == 1): ?>
                                            <!--<th><?php echo e(__('common.Mobile')); ?></th>-->
                                        <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i=1;
                                      
                                        ?>
                                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $array[$item->id] =$item;
                                ?>
                                            <tr  class="quickCollect" data-id='<?php echo e($item->id ?? ''); ?>'style="cursor:pointer; " onclick="showData('<?php echo e($item['unique_system_id']); ?>','<?php echo e(Session::get('session_id')); ?>')">
<!--                                            <td><?php echo e($item->admission_type_id == 2 ? 'RTE' : 'Non RTE'); ?></td>
-->                                            <td><?php echo e($item->ledger_no ?? 'NA'); ?></td>
                                            <!--<td class="text-center">-->
                                            <!--    <img src="<?php echo e(env('IMAGE_SHOW_PATH').'profile/'.$item['image']); ?>" -->
                                            <!--        class="photo_img" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'/default/user_image.jpg'); ?>'">-->
                                            <!--</td>-->
                                            <td class="text-center"><?php echo e($item['admissionNo'] ?? ''); ?></td>
                                            <td><?php echo e($item['first_name'] ?? ''); ?> <?php echo e($item['last_name'] ?? ''); ?></td>
                                            <td><?php echo e($item['ClassTypes']['name'] ?? ''); ?></td>
                                            <!--<td><?php echo e($item['father_name'] ?? ''); ?></td>-->
                                            <!--<td><?php echo e($item['mother_name'] ?? ''); ?></td>-->
                                             <?php if(Session::get('role_id') == 1): ?>
                                         <!--<td><?php echo e($item['mobile'] ?? ''); ?></td>-->
                                        <?php endif; ?>
                                            
                                        </tr>                                            
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                </div>
                                   <div class='col-12 col-md-5' id='show_details' style='display:none;position:relative'> 
                                       
                                   <table class='table table-bordered' style="font-size: 14px;">
                                            <tr>
                                                <th rowspan='6' style='text-center;padding:10px'>
                                                    <img src='' width='150px' height='150px' id="student-image" />
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Name</th>
                                                <th id="student-name"></th>
                                            </tr>
                                            <tr>
                                                <th>Mobile</th>
                                                <th id="student-mobile"></th>
                                            </tr>
                                            <tr>
                                                <th>Father</th>
                                                <th id="father-name"></th>
                                            </tr>
                                            <tr>
                                                <th>Mother</th>
                                                <th id="mother-name"></th>
                                            </tr>
                                            <tr>
                                                <th>Father Mobile</th>
                                                <th id="father-mobile"></th>
                                            </tr>
                                        </table>
                                       </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="student_fees_detail"></div>
        </div>
    </section>
</div>


<style>
    .photo_img{
        border-radius: 10px;
        padding: 2px;
        width: 50px;
        height: 50px;
    }
    .blink2 {
 
 animation: blink-animation 0.5s infinite step-start;
}

@keyframes    blink-animation {
 0% {
   opacity: 1;
 }
 5% {
   opacity: 0.1;
 }

 100% {
   opacity: 1;
 }
}   
</style>

<script>
$(document).ready(function() {
    $('#trColor tr').click(function() {
        $(this).css('backgroundColor', '#002c54');
        $(this).css('color', '#fff');
        $( this ).siblings().css( "background-color", "white" );
        $( this ).siblings().css( "color", "black" );
    });
});




$(".quickCollect").on("click", function(){
 var array = <?php echo json_encode($array, 15, 512) ?>;
 var id = $(this).data('id');
 
 var student = array[id];

const IMAGE_SHOW_PATH = "<?php echo e(env('IMAGE_SHOW_PATH')); ?>";

const path = student.image 
    ? `${IMAGE_SHOW_PATH}profile/${student.image}` 
    : `${IMAGE_SHOW_PATH}default/user_image.jpg`;

        $("#student-image").attr("src",path );
        $("#student-name").text(student.first_name + ' ' + (student.last_name ? student.last_name : ''));
        $("#student-mobile").text(student.mobile);
        $("#father-name").text(student.father_name);
        $("#mother-name").text(student.mother_name);
        $("#father-mobile").text(student.father_mobile);


$('#show_details').show();
}); 
    function showData(unique_system_id,session_id) {
         var basurl = "<?php echo e(url('/')); ?>";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }, 
            type: 'post',
            url: basurl+'/student_fees_onclick',
            data: {
                unique_system_id: unique_system_id,
                session_id: session_id,
            },
            // dataType: 'json',
            success: function(data) {
                // alert(JSON.stringify(data));
                if (data == 0) {
                    alert('Please Assign the Fees for this Student !');
                    //window.location.href = "<?php echo e(url('feesMasterAdd')); ?>";
                    var url = "<?php echo e(url('admissionEdit')); ?>/" + admission_id;
                    var width = 1000; 
                    var height = 500; 
                    var leftPosition = (window.screen.width - width) / 2; 
                    var topPosition = (window.screen.height - height) / 2; 
                    var features = 'width=' + width + ',height=' + height + ',left=' + leftPosition + ',top=' + topPosition; 
                    
                } else {
                    $('#student_fees_detail').html(data);
                }
            }
        });
    };

/*    function SearchValue() {
         var basurl = "<?php echo e(url('/')); ?>";
        var class_type_id = $('#class_type_id :selected').val();
        var name = $('#name').val();
        if (class_type_id > 0 || name != '') {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: basurl+'/SearchValueStd',
                data: {
                    class_type_id: class_type_id,
                    name: name
                },
                //dataType: 'json',
                success: function(data) {
                    $('.student_list_show').html(data);
                }
            });
        } else {
            alert('Please put a value in minimum one column !');
        }

    };*/
   
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/fees/fees_collect/add.blade.php ENDPATH**/ ?>