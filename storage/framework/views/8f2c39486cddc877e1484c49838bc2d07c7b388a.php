<?php
  $classType = Helper::classType();
  $getState = Helper::getState();
  $getCity = Helper::getCity();
  $getCountry = Helper::getCountry();
?>
 
<?php $__env->startSection('content'); ?>


<div class="content-wrapper">
	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
           
       <div class="col-md-12 pl-0">
            <div class="card card-outline card-orange ml-1">
             <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fa fa-user"></i> &nbsp; <?php echo e(__('student.View Students Id')); ?></h3>
            <div class="card-tools">
            <a href="<?php echo e(url('studentsDashboard')); ?>" class="btn btn-primary  btn-sm" ><i class="fa fa-arrow-left"></i><span class="Display_none_mobile"><?php echo e(__('common.Back')); ?></span></a>
            </div>
            <div class="card-tools">
        </div>
            
            </div>
            <form id="searchForm" action="<?php echo e(url('student/id/index')); ?>" method="post" >
            <?php echo csrf_field(); ?> 
            <div class="row m-2">
				<div class="col-md-2">
					<div class="form-group">
						<label><?php echo e(__('student.Admission No')); ?></label>
						<input type="text" class="form-control"  id="admission_id"name="admission_id" placeholder="<?php echo e(__('Admission No')); ?>" value="<?php echo e($search['admission_id'] ?? ''); ?>">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label><?php echo e(__('common.Class')); ?></label>
						<select class="form-control select2 " id="class_type_id" name="class_search_id">
							<option value=""><?php echo e(__('common.Select')); ?></option>
							<?php if(!empty($classType)): ?>
							<?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id == $search['class_search_id']) ? 'selected' : ''); ?> ><?php echo e($type->name ?? ''); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							<?php endif; ?>
						</select>
					</div>
				</div>

				<div class="col-md-2">
				    <lable>&nbsp;</lable><br>
				    <button class="btn btn-primary btn-xl"><?php echo e(__('common.Search')); ?></button>
				</div>
            </div>
            </form>
            <form id="quickForm" action="<?php echo e(url('student_id_print_multiple')); ?>" target="_blank" method="post" >
            <?php echo csrf_field(); ?> 
                <div class="row ">
                    <div class="col-md-12">
                	</div>
                    <div class="col-md-12">
                       <table id="" class="table table-bordered table-striped dataTable dtr-inline ">
                          <thead class="bg-primary">
                          <tr role="row">
                      <th> <input type="checkbox" id="view1"><?php echo e(__('common.SR.NO')); ?></th>
                       <th><?php echo e(__('common.Name')); ?></th>
                       <th><?php echo e(__('Class')); ?></th>
                      <th><?php echo e(__('common.DOB')); ?></th>
                      <th><?php echo e(__('student.Ad. No')); ?></th>
                    </tr>
                  </thead>
                  <tbody id="product_list_show">
                  
                  <?php if(!empty($data)): ?>
                        <?php
                           $i=1;
                         
                        ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                       
                        <tr>
                            
                                <td>								    
                                <input type="checkbox"  data-value="view" name="checkbox[]" class="viewcheck <?php $__errorArgs = ['checkbox[]'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e($item->id ?? ''); ?>">
                                <?php echo e($i++); ?></td>
                                <td><?php echo e($item['first_name']  ?? ''); ?> <?php echo e($item['last_name'] ?? ''); ?></td>
                                <td><?php echo e($item['ClassTypes']['name'] ?? ''); ?></td>
                                <td><?php echo e(date('d-m-Y', strtotime($item['dob'])) ?? ''); ?></td>
                                <td><?php echo e($item['admissionNo']); ?></td>
                                
                        </tr>
                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                  </tbody>
            </table>
                	</div>
                </div>
                 <?php $__errorArgs = ['checkbox'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                   <span class="text-danger">
                       <?php echo e($message); ?>

                   </span>
                 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                 <?php if(!empty($data)): ?>
                <div class="col-12 text-center mb-3 mt-2">
                    <button class="btn btn-primary" target="_blank"><?php echo e(__('student.Generate Ids')); ?></button>
                </div>
                <?php endif; ?>
             </form>
            </div>  
            
        </div>
        
       
    </div>
</div>
</section>
</div>



     <!--<script src="<?php echo e(URL::asset('public/assets/school/js/jquery.min.js')); ?>"></script>-->

<script>

    
        $("#view1").click(function(){
            if ($(this).is(':checked')) {
                $(".viewcheck").attr('checked', false);
                $(".viewcheck").attr('checked', true);
            }else{
                $(".viewcheck").attr('checked', false);
            }
        });
        function SearchValue() {
            var admission_no = $('#admission_no').val();
            var class_type_id = $('#class_type_id :selected').val();
            var country_id = $('#country_id :selected').val();
            var state_id = $('#state_id :selected').val();
            var city_id = $('#city_id :selected').val();
            if(class_type_id > 0 || country_id > 0 || state_id > 0 || city_id > 0 || admission_no != ''){
            $.ajax({
                     headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
                type:'post',
                url: '/students_id_data',
                data: {class_type_id:class_type_id,country_id:country_id,state_id:state_id,city_id:city_id,admission_no:admission_no},
                 //dataType: 'json',
                success: function (data) {
                   
                  
                    $('#product_list_show').html(data);
                   
                }
              });
            }else{
                alert('Please put a value in minimum one column !');
            }              
        };

</script>

 <?php $__env->stopSection(); ?>      
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/students/student_id/index.blade.php ENDPATH**/ ?>