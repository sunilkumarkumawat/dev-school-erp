<?php
$getgenders = Helper::getgender();
$classType = Helper::classType();
$getState = Helper::getState();
$getCity = Helper::getCity();
$getCountry = Helper::getCountry();
$getSetting = Helper::getSetting();
$bloodGroupType = Helper::bloodGroupType();
$getAdmissionDatatableFields = Helper::getAdmissionDatatableFields();
$student_fields = DB::table('student_fields')->whereNull('deleted_at')->where('status',0)->pluck('field_name'); // Collection
$studentFields_new = DB::table('student_fields')->whereNull('deleted_at')->where('status',0)->where('type','new_input')->get();
$student_fields_required = DB::table('student_fields')->whereNull('deleted_at')->pluck('required', 'field_name')->toArray(); 
?>

<?php $__env->startSection('content'); ?>


						
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<div class="content-wrapper">
	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
							<h3 class="card-title"><i class="fa fa-address-book-o"></i> &nbsp;<?php echo e(__('Students Admission')); ?></h3>
							<div class="card-tools">
								<a href="<?php echo e(url('admissionView')); ?>" class="btn btn-primary  btn-sm <?php echo e(Helper::permissioncheck(3)->view ? '' : 'd-none'); ?>"><i class="fa fa-eye"></i> <span class="Display_none_mobile"> <?php echo e(__('common.View')); ?> </span></a>
								<a href="<?php echo e(url('studentsDashboard')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i> <span class="Display_none_mobile"> <?php echo e(__('common.Back')); ?> </span></a>
							</div>

						</div>

						
						<div class="student_list_show"></div>
                        <hr>
						<form id="form-submit" action="<?php echo e(url('admissionAdd')); ?>" method="post" enctype="multipart/form-data">
							<?php echo csrf_field(); ?>
							<div class="row m-2">
								<div class=" col-md-12 title mt-n3">
									<h5 class="text-danger"><?php echo e(__('student.Personal Details')); ?>:-</h5>
								</div>
                                    <div class="col-md-2 <?php echo e($student_fields->contains('admissionNo') ? '' : 'd-none'); ?>">
                                            <div class="form-group">
                                                <label><?php echo e(__('student.Admission No.')); ?>

                                                <?php if($student_fields_required['admissionNo'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                            </label>
                                                <input type="text" class="form-control"  id="admissionNo"name="admissionNo"placeholder="<?php echo e(__('student.Admission No.')); ?>" value="<?php echo e($BillCounter ?? ''); ?>" 
                                                       onkeypress="return isNumber(event)"
                                                      >
                                            
                                            </div>
                                        </div>
								<div class="col-md-2  <?php echo e($student_fields->contains('ledger_no') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Ledger No')); ?>

										 <?php if($student_fields_required['ledger_no'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control " name="ledger_no" placeholder="<?php echo e(__('Ledger No')); ?>"  >
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('student_pen') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Student Pen
										 <?php if($student_fields_required['student_pen'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                                </label>
										<input type="text" class="form-control" id="student_pen" name="student_pen" placeholder="Student Pen" onkeypress="javascript:return isNumber(event)" maxlength="11">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('apaar_id') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Apaar Id
										    <?php if($student_fields_required['apaar_id'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="apaar_id" name="apaar_id" placeholder="Apaar Id" onkeypress="javascript:return isNumber(event)" maxlength="12">
									</div>
								</div>
							
								<div class="col-md-2 <?php echo e($student_fields->contains('family_id') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Family ID')); ?>

										<?php if($student_fields_required['family_id'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="family_id" name="family_id" placeholder="<?php echo e(__('Family ID')); ?>" onkeypress="javascript:return isNumber(event)">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('first_name') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Student Name')); ?>

										<?php if($student_fields_required['first_name'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" name="first_name" id="first_name" class="form-control invalid " value="<?php echo e(old('first_name')); ?>" placeholder="<?php echo e(__('Student Name')); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)">
									
									</div>
								</div>
							
								<div class="col-md-2 <?php echo e($student_fields->contains('aadhaar') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('common.Aadhaar No.')); ?>

										<?php if($student_fields_required['aadhaar'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control " id="aadhaar" name="aadhaar" placeholder=" <?php echo e(__('common.Aadhaar No.')); ?>" value="<?php echo e(old('aadhaar')); ?>" maxlength="12" onkeypress="javascript:return isNumber(event)">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('jan_aadhaar') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Jan Aadhaar No.')); ?>

										<?php if($student_fields_required['jan_aadhaar'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control " id="jan_aadhaar" name="jan_aadhaar" placeholder=" <?php echo e(__('Jan Aadhaar No.')); ?>" value="<?php echo e(old('jan_aadhaar')); ?>" maxlength="10" onkeypress="javascript:return isNumber(event)">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('gender_id') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('common.Gender')); ?>  
										   <?php if($student_fields_required['gender_id'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                            </label>
										<select class="form-control invalid select2" id="gender_id" name="gender_id">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php if(!empty($getgenders)): ?>
											<?php $__currentLoopData = $getgenders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($value->id); ?>" <?php echo e(($value->id == old('gender_id')) ? 'selected' : ''); ?>><?php echo e($value->name ?? ''); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select>
									
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('dob') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('common.Date Of  Birth')); ?>

										<?php if($student_fields_required['dob'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="date" class="form-control invalid" id="dob" name="dob" placeholder=" Date Of  Birth" value="<?php echo e(old('dob')); ?>">
									
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('mobile') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('common.Mobile No.')); ?>

										<?php if($student_fields_required['mobile'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control " id="mobile" name="mobile" placeholder="<?php echo e(__('common.Mobile No.')); ?>" value="<?php echo e(old('mobile')); ?>" maxlength="10" onkeypress="javascript:return isNumber(event)">
				                        <div id="mobileValidationMessage" style="color: red; display: none; font-size:13px;">must be at least 10 characters</div>


									</div>
								</div>
								
								<div class="col-md-2 <?php echo e($student_fields->contains('email') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('common.E-Mail')); ?>

										<?php if($student_fields_required['email'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="email" class="form-control" id="email" name="email" placeholder="<?php echo e(__('common.E-Mail')); ?>" value="<?php echo e(old('email')); ?>">
							          
									</div>
								</div>
								
                                	<div class="col-md-2 <?php echo e($student_fields->contains('class_type_id') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('common.Class')); ?>

										<?php if($student_fields_required['class_type_id'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>

										<select class="form-control invalid select2" id="class_type_id" name="class_type_id">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php if(!empty($classType)): ?>
											<?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($type->id ?? ''); ?>" data-orderBy="<?php echo e($type->orderBy ?? ''); ?>" <?php echo e(($type->id == old('class_type_id')) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select>
									
									</div>
								</div>
								
								
								<div class="col-md-2 " id="stream_subject_div" style="display:none;">
									<div class="form-group">
										<label>Stream Subject
									</label>

										<select class="form-control select2" multiple id="stream_subject" name="stream_subject[]">
											<option value=""><?php echo e(__('common.Select')); ?></option>
										</select>
									</div>
								</div>
                               
                              <div class="col-md-2 <?php echo e($student_fields->contains('admission_type_id') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Admission Type(Non RTE)
										<?php if($student_fields_required['admission_type_id'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<select class="form-control" id="admission_type_id" name="admission_type_id">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<option value="1" <?php echo e((1 == old('admission_type_id')) ? 'selected' : 'selected'); ?>>Yes</option>
											<option value="2" <?php echo e((2 == old('admission_type_id')) ? 'selected' : ''); ?>>NO</option>
										</select>
									  
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('religion') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Religion
										<?php if($student_fields_required['religion'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<select class="form-control select2" id="religion" name="religion">
											<option value="Select" selected="">Select</option>
											<option value="Hindu" <?php echo e(('Hindu' == old('religion')) ? 'selected' : 'selected'); ?>>Hindu</option>
											<option value="Islam" <?php echo e(('Islam' == old('religion')) ? 'selected' : ''); ?>>Islam</option>
											<option value="Sikh" <?php echo e(('Sikh' == old('religion')) ? 'selected' : ''); ?>>Sikh</option>
											<option value="Buddhism" <?php echo e(('Buddhism' == old('religion')) ? 'selected' : ''); ?>>Buddhism</option>
											<option value="Adivasi" <?php echo e(('Adivasi' == old('religion')) ? 'selected' : ''); ?>>Adivasi</option>
											<option value="Jain" <?php echo e(('Jain' == old('religion')) ? 'selected' : ''); ?>>Jain</option>
											<option value="Christianity" <?php echo e(('Christianity' == old('religion')) ? 'selected' : ''); ?>>Christianity</option>
											<option value="Other" <?php echo e(('Other' == old('religion')) ? 'selected' : ''); ?>>Other</option>
										</select>
									
									</div>
								</div>
								
								<div class="col-md-2 <?php echo e($student_fields->contains('category') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Category
										<?php if($student_fields_required['category'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<select class="form-control select2" id="category" name="category">
											<option value="">Select</option>
											<option value="OBC" <?php echo e(('OBC' == old('category')) ? 'selected' : 'selected'); ?>>OBC</option>
											<option value="ST" <?php echo e(('ST' == old('category')) ? 'selected' : ''); ?>>ST</option>
											<option value="SC" <?php echo e(('SC' == old('category')) ? 'selected' : ''); ?>>SC</option>
											<option value="BC" <?php echo e(('BC' == old('category')) ? 'selected' : ''); ?>>BC</option>
											<option value="GEN" <?php echo e(('GEN' == old('category')) ? 'selected' : ''); ?>>GEN</option>
											<option value="SBC" <?php echo e(('SBC' == old('category')) ? 'selected' : ''); ?>>SBC</option>
											<option value="Other" <?php echo e(('Other' == old('category')) ? 'selected' : ''); ?>>Other</option>
								        </select>
									</div>
								</div>
								
								<div class="col-md-2 <?php echo e($student_fields->contains('caste_category') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Caste')); ?><?php if($student_fields_required['caste_category'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                                </label>
										<input type="text" class="form-control" id="caste_category" name="caste_category" placeholder="<?php echo e(__('Caste')); ?>" value="<?php echo e(old('caste_category')); ?>" >
									
									</div>
								</div>
								
								<div class="col-md-2 <?php echo e($student_fields->contains('blood_group') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Blood Group')); ?><?php if($student_fields_required['blood_group'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                                </label>
										<select class="form-control select2" id="blood_group" name="blood_group">
											<option value=""><?php echo e(__('common.Select')); ?></option>
        										<?php if(!empty($bloodGroupType)): ?>
        											<?php $__currentLoopData = $bloodGroupType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bloodtype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        											<option value="<?php echo e($bloodtype->name ?? ''); ?>" <?php echo e(($bloodtype->name == old('blood_group')) ? 'selected' : ''); ?>><?php echo e($bloodtype->name ?? ''); ?></option>
        											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        										<?php endif; ?>
										</select>
									
									</div>
								</div>
								
								<div class="col-md-2 <?php echo e($student_fields->contains('medium') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Medium
										<?php if($student_fields_required['medium'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<select class="form-control select2" id="medium" name="medium">
											<option value="">Select</option>
											<option value="Hindi">Hindi</option>
											<option value="English">English</option>
										</select>
									</div>
								</div>
								
                                <div class="col-md-2 <?php echo e($student_fields->contains('admission_date') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('student.Date Of Admission')); ?>

										<?php if($student_fields_required['admission_date'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="date" class="form-control" id="admission_date" name="admission_date" value="<?php echo e(date('Y-m-d')); ?>">
									
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('country') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('common.Country')); ?>

										<?php if($student_fields_required['country'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<select class="form-control select2" name="country" id="country_id">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php if(!empty($getCountry)): ?>
											<?php $__currentLoopData = $getCountry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($country->id ?? ''); ?>" <?php echo e(($country->id == $getSetting->country_id) ? 'selected' : ''); ?>><?php echo e($country->name ?? ''); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('state') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label for="State" class="required"><?php echo e(__('common.State')); ?>

										<?php if($student_fields_required['state'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<select class="form-control stateId select2" id="state_id" name="state">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php if(!empty($getState)): ?>
											<?php $__currentLoopData = $getState; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($state->id ?? ''); ?>" <?php echo e(($state->id == $getSetting->state_id) ? 'selected' : ''); ?>><?php echo e($state->name ?? ''); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select>

									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('city') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label for="City"><?php echo e(__('common.City')); ?>

										<?php if($student_fields_required['city'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<select class="form-control cityId select2" name="city" id="city_id">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php if(!empty($getCity)): ?>
											<?php $__currentLoopData = $getCity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cities): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($cities->id ?? ''); ?>" <?php echo e(($cities->id == $getSetting->city_id) ? 'selected' : ''); ?>><?php echo e($cities->name ?? ''); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
								
							
								<div class="col-md-2 <?php echo e($student_fields->contains('village_city') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('student.Village/City')); ?>

										<?php if($student_fields_required['village_city'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="village_city" name="village_city" placeholder="<?php echo e(__('student.Village/City')); ?>" value="<?php echo e(old('village_city')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('address') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('student.Students Address')); ?>

										<?php if($student_fields_required['address'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control " id="address" name="address" placeholder="<?php echo e(__('student.Students Address')); ?>" value="<?php echo e(old('address')); ?>">
										
									</div>
								</div>
									<div class="col-md-2 <?php echo e($student_fields->contains('family_annual_income') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Family Annual Income')); ?>

										<?php if($student_fields_required['family_annual_income'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" name="family_annual_income" id="family_annual_income" class="form-control" value="" placeholder="<?php echo e(__('Family Annual Income')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('pincode') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('common.Pin Code')); ?>

										<?php if($student_fields_required['pincode'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="pincode" name="pincode" placeholder="<?php echo e(__('common.Pin Code')); ?>" value="<?php echo e(old('pincode')); ?>" maxlength="6" onkeypress="javascript:return isNumber(event)">
										
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('relation_student') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Relation with the student')); ?>

										<?php if($student_fields_required['relation_student'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" name="relation_student" id="relation_student" class="form-control" value="" placeholder="<?php echo e(__('Relation with the student')); ?>">
										
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('school_namestudied_last_year') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('School Studied Last Year')); ?>

										<?php if($student_fields_required['school_namestudied_last_year'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" name="school_namestudied_last_year" id="school_namestudied_last_year" class="form-control" value="" placeholder="<?php echo e(__('School Studied Last Year')); ?>">
										
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('house') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('House')); ?>

										<?php if($student_fields_required['house'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" name="house" id="house" class="form-control" value="" placeholder="<?php echo e(__('House')); ?>">
										
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('height') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Height')); ?>

										<?php if($student_fields_required['height'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" name="height" id="height" class="form-control" value="" placeholder="<?php echo e(__('Height')); ?>">
										
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('weight') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Weight')); ?>

										<?php if($student_fields_required['weight'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" name="weight" id="weight" class="form-control" value="" placeholder="<?php echo e(__('Weight')); ?>">
										
									</div>
								</div>
                                
								
								<div class="col-md-2 <?php echo e($student_fields->contains('remark_1') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('student.Remark')); ?>

										<?php if($student_fields_required['remark_1'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="remark_1" name="remark_1" placeholder="<?php echo e(__('student.Remark')); ?> " value="<?php echo e(old('remark_1')); ?>">
									</div>
								</div>
									<div class="col-md-2 <?php echo e($student_fields->contains('transport') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Transport')); ?>

										<?php if($student_fields_required['transport'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<select class="form-control select2" id="transport" name="transport">
											<option value="Yes" <?php echo e(('Yes' == old('transport')) ? 'selected' : 'selected'); ?>><?php echo e(__('Yes')); ?></option>
											<option value="No" <?php echo e(('No' == old('transport')) ? 'selected' : ''); ?>><?php echo e(__('No')); ?></option>
										</select>
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('bus_number') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Bus Number')); ?>

										<?php if($student_fields_required['bus_number'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="bus_number" name="bus_number" placeholder="<?php echo e(__('Bus Number')); ?> " value="<?php echo e(old('bus_number')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('bus_route') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Bus Route')); ?>

										<?php if($student_fields_required['bus_route'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="bus_route" name="bus_route" placeholder="<?php echo e(__('Bus Route')); ?> " value="<?php echo e(old('bus_route')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('stoppage') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Stoppage')); ?>

										<?php if($student_fields_required['stoppage'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="stoppage" name="stoppage" placeholder="<?php echo e(__('Stoppage')); ?> " value="<?php echo e(old('stoppage')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('transpor_charges') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Transpor Charges')); ?>

										<?php if($student_fields_required['transpor_charges'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="transpor_charges" name="transpor_charges" placeholder="<?php echo e(__('Transpor Charges')); ?> " value="<?php echo e(old('transpor_charges')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('bank_name') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Bank Name')); ?>

										<?php if($student_fields_required['bank_name'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="<?php echo e(__('Bank Name')); ?> " value="<?php echo e(old('bank_name')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('bank_account') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Bank Account')); ?>

										<?php if($student_fields_required['bank_account'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="bank_account" name="bank_account" placeholder="<?php echo e(__('Bank Account')); ?> " value="<?php echo e(old('bank_account')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('branch_name') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Branch Name')); ?>

										<?php if($student_fields_required['branch_name'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="<?php echo e(__('Branch Name')); ?> " value="<?php echo e(old('branch_name')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('ifsc') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('IFSC')); ?>

										<?php if($student_fields_required['ifsc'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="ifsc" name="ifsc" placeholder="<?php echo e(__('IFSC')); ?> " value="<?php echo e(old('ifsc')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('micr_code') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__(' MICR Code')); ?>

										<?php if($student_fields_required['micr_code'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="micr_code" name="micr_code" placeholder="<?php echo e(__('MICR Code')); ?> " value="<?php echo e(old('micr_code')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('bank_account_holder') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Bank Account Holder
										<?php if($student_fields_required['bank_account_holder'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="bank_account_holder" name="bank_account_holder" placeholder="Bank Account Holder" value="<?php echo e(old('bank_account_holder')); ?>">
									</div>
								</div>
							
							
								<div class="col-md-2 <?php echo e($student_fields->contains('district') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>District
										<?php if($student_fields_required['district'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="district" name="district" placeholder="District" value="<?php echo e(old('district')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('tehsil') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Tehsil
										<?php if($student_fields_required['tehsil'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="tehsil" name="tehsil" placeholder="Tehsil" value="<?php echo e(old('tehsil')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('father_pancard') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Father's Pancard
										<?php if($student_fields_required['father_pancard'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="father_pancard" name="father_pancard" placeholder="Father's Pancard" value="<?php echo e(old('father_pancard')); ?>">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('mother_pancard') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Mother's Pancard
										<?php if($student_fields_required['mother_pancard'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="mother_pancard" name="mother_pancard" placeholder="Mother's Pancard" value="<?php echo e(old('mother_pancard')); ?>">
									</div>
								</div>
							
							
								<div class="col-md-2 <?php echo e($student_fields->contains('bpl') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>BPL
										<?php if($student_fields_required['bpl'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<select class="form-control select2" id="bpl" name="bpl">
										    <option value="">Select</option>
										    <option value="Yes">Yes</option>
										    <option value="No">No</option>
										</select>
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('bpl_certificate_no') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>BPL Cetificate No.
										<?php if($student_fields_required['bpl_certificate_no'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="bpl_certificate_no" name="bpl_certificate_no" placeholder="BPL Cetificate No." value="<?php echo e(old('bpl_certificate_no')); ?>">
									</div>
								</div>
							
						
								<div class="col-md-4 <?php echo e($student_fields->contains('previous_school') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Name  And  Address Of Previous School
										<?php if($student_fields_required['previous_school'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control" id="previous_school" name="previous_school" placeholder="Name  And  Address Of Previous School" value="<?php echo e(old('previous_school')); ?>">
									</div>
								</div>
                    			<?php $__currentLoopData = $studentFields_new; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-<?php echo e($field->grid_column); ?>">
                                        <div class="form-group">
                                            <label>
                                                <?php echo e($field->field_label); ?>

                                                <?php if($field->required == 0): ?> 
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                            </label>
                                
                                            
                                            <?php if($field->field_type == 'text'): ?>
                                                <input type="text" name="<?php echo e($field->field_name); ?>"  class="form-control" placeholder="<?php echo e($field->field_label); ?>"  value="<?php echo e(old($field->field_name, $field->default_value)); ?>">
                                
                                            
                                            <?php elseif($field->field_type == 'email'): ?>
                                                <input type="email" 
                                                       name="<?php echo e($field->field_name); ?>"  class="form-control"  placeholder="<?php echo e($field->field_label); ?>" value="<?php echo e(old($field->field_name, $field->default_value)); ?>">
                                            
                                            <?php elseif($field->field_type == 'number'): ?>
                                                <input type="number" 
                                                       name="<?php echo e($field->field_name); ?>"  class="form-control"  placeholder="<?php echo e($field->field_label); ?>" value="<?php echo e(old($field->field_name, $field->default_value)); ?>">
                                
                                            
                                            <?php elseif($field->field_type == 'date'): ?>
                                                <input type="date" name="<?php echo e($field->field_name); ?>" class="form-control" value="<?php echo e(old($field->field_name, $field->default_value)); ?>">
                                
                                            
                                            <?php elseif($field->field_type == 'dropdown'): ?>
                                                <select name="<?php echo e($field->field_name); ?>" class="form-control">
                                                    <option value="">Select</option>
                                                    <?php $__currentLoopData = explode(',', $field->default_value ?? ''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e(trim($option)); ?>" >
                                                            <?php echo e(trim($option)); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                
                                            
                                            <?php elseif($field->field_type == 'file'): ?>
                                                <input type="file" name="<?php echo e($field->field_name); ?>" class="form-control">
                                
                                            
                                            <?php elseif($field->field_type == 'checkbox'): ?>
                                                <?php $__currentLoopData = explode(',', $field->default_value ?? ''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checkbox): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                 <?php if(!empty($checkbox)): ?>
                                                    <div class="form-check">
                                                        <input type="checkbox" 
                                                               name="<?php echo e($field->field_name); ?>[]" 
                                                               value="<?php echo e($checkbox); ?>"
                                                               class="form-check-input" id="<?php echo e($checkbox); ?><?php echo e($field->id); ?>">
                                                        <label class="form-check-label" for="<?php echo e($checkbox); ?><?php echo e($field->id); ?>"><?php echo e($checkbox); ?></label>
                                                    </div>
                                                     <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                            
                                            <?php elseif($field->field_type == 'radio'): ?>
                                                <?php $__currentLoopData = explode(',', $field->default_value ?? ''); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $radio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                   <?php if(!empty($radio)): ?>
                                                    <div class="form-check">
                                                       
                                                        <input type="radio"   id="<?php echo e($radio); ?><?php echo e($field->id); ?>" name="<?php echo e($field->field_name); ?>" value="<?php echo e($radio ?? ''); ?>" class="form-check-input">
                                                        <label class="form-check-label" for="<?php echo e($radio); ?><?php echo e($field->id); ?>"><?php echo e($radio); ?></label>
                                                    </div>
                                                     <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



								</div>
								<hr>
							<div class="row m-2">
								<div class=" col-md-12 title">
									<h5 class="text-danger"><?php echo e(__('Guardian Details')); ?>:-</h5>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('father_name') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('common.Fathers Name')); ?>

										<?php if($student_fields_required['father_name'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control invalid" id="father_name" name="father_name" placeholder="<?php echo e(__('common.Fathers Name')); ?>" value="<?php echo e(old('father_name')); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)">
									
										</select>
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('father_mobile') ? '' : 'd-none'); ?>">  
									<div class="form-group">
										<label><?php echo e(__('common.Fathers Contact No')); ?>

										<?php if($student_fields_required['father_mobile'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control invalid" id="father_mobile" name="father_mobile" placeholder="<?php echo e(__('common.Fathers Contact No')); ?>" value="<?php echo e(old('father_mobile')); ?>" maxlength="10" onkeypress="javascript:return isNumber(event)">
										 <div id="fathermobileValidationMessage" style="color: red; display: none; font-size:13px;">must be at least 10 characters</div>

								
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('father_aadhaar') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Fathers Aadhaar')); ?>

										<?php if($student_fields_required['father_aadhaar'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                    </label>
										<input type="text" class="form-control" id="father_aadhaar" name="father_aadhaar" placeholder="<?php echo e(__('Fathers Aadhaar')); ?>" value="<?php echo e(old('father_aadhaar')); ?>" maxlength="12" onkeypress="javascript:return isNumber(event)">
									</div>
								</div>
								
								<div class="col-md-2 <?php echo e($student_fields->contains('father_occupation') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Father Occupation
										<?php if($student_fields_required['father_occupation'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                    </label>
										<input type="text" class="form-control" id="father_occupation" name="father_occupation" placeholder="Father Occupation" value="<?php echo e(old('father_occupation')); ?>">
									</div>
								</div>
								
								<div class="col-md-2 <?php echo e($student_fields->contains('mother_name') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('common.Mothers Name')); ?><?php if($student_fields_required['mother_name'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                        </label>
										<input type="text" class="form-control invalid" id="mother_name" name="mother_name" placeholder="<?php echo e(__('common.Mothers Name')); ?>" value="<?php echo e(old('mother_name')); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)">
									
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('mother_mob') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Mother Mobile No')); ?>

										<?php if($student_fields_required['mother_mob'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                                </label>
										<input type="text" class="form-control" id="mother_mob" name="mother_mob" placeholder="<?php echo e(__('Mother Mobile No')); ?>" value="<?php echo e(old('mother_mob')); ?>" maxlength="10" onkeypress="javascript:return isNumber(event)">
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('mother_aadhaar') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Mothers Aadhaar')); ?>

										<?php if($student_fields_required['mother_aadhaar'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                                </label>
										<input type="text" class="form-control" id="mother_aadhaar" name="mother_aadhaar" placeholder="<?php echo e(__('Mothers Aadhaar')); ?>" value="<?php echo e(old('mother_aadhaar')); ?>" maxlength="12" onkeypress="javascript:return isNumber(event)">
									</div>
								</div>
								
								<div class="col-md-2 <?php echo e($student_fields->contains('mother_occupation') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label>Mother Occupation
										<?php if($student_fields_required['mother_occupation'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                                </label>
										<input type="text" class="form-control" id="mother_occupation" name="mother_occupation" placeholder="Mother Occupation" value="<?php echo e(old('mother_occupation')); ?>">
									</div>
								</div>
								
								<div class="col-md-2 <?php echo e($student_fields->contains('guardian_name') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Guardian Name')); ?>

										<?php if($student_fields_required['guardian_name'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                                </label>
										<input type="text" class="form-control" id="guardian_name" name="guardian_name" placeholder="<?php echo e(__('Guardian Name')); ?>" value="<?php echo e(old('guardian_name')); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)">
									
									</div>
								</div>
								<div class="col-md-2 <?php echo e($student_fields->contains('guardian_mobile') ? '' : 'd-none'); ?>">
									<div class="form-group">
										<label><?php echo e(__('Guardian Mobile No')); ?>

										<?php if($student_fields_required['guardian_mobile'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                                </label>
										<input type="text" class="form-control " id="guardian_mobile" name="guardian_mobile" placeholder="<?php echo e(__('Guardian Mobile No')); ?>" value="<?php echo e(old('guardian_mobile')); ?>" maxlength="10" onkeypress="javascript:return isNumber(event)">
								
									</div>
								</div>
								
						    </div>		
							<hr>
							<div class="row m-2">
								<div class=" col-md-12 title">
									<h5 class="text-danger"><?php echo e(__('student.Document Upload')); ?>:-</h5>
								</div>
								<div class="col-md-3 <?php echo e($student_fields->contains('image') ? '' : 'd-none'); ?>">
									<lable><?php echo e(__('student.Student Photo')); ?>

									<?php if($student_fields_required['image'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                                </lable>
									<div class="input file form-control">
										<input type="file" class="" name="student_img" id="student_img" value="<?php echo e(old('student_img')); ?>">
									</div>
								</div>
								<div class="col-md-1 <?php echo e($student_fields->contains('student_img') ? '' : 'd-none'); ?>">
									<img src="<?php echo e(env('IMAGE_SHOW_PATH').'/student_image/profile_img.png'); ?>" width="60px" height="60px" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'/default/user_image.jpg'); ?>'">
								</div>
								<div class="col-md-3 <?php echo e($student_fields->contains('father_img') ? '' : 'd-none'); ?>">
									<lable><?php echo e(__('student.Father Photo')); ?>

									<?php if($student_fields_required['father_img'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                                </lable>
									<div class="input file form-control">
										<input type="file" name="father_img" id="father_img" value="<?php echo e(old('father_img')); ?>" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'/default/user_image.jpg'); ?>'">
								            <p class="text-danger" id="image_errors"></p>
									</div>
								</div>
								<div class="col-md-1 <?php echo e($student_fields->contains('father_img') ? '' : 'd-none'); ?>">
									<img src="<?php echo e(env('IMAGE_SHOW_PATH').'/student_image/profile_img.png'); ?>" width="60px" height="60px" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'/default/user_image.jpg'); ?>'">
								</div>
								<div class="col-md-3 <?php echo e($student_fields->contains('mother_img') ? '' : 'd-none'); ?>">
									<lable><?php echo e(__('student.Mother Photo')); ?>

									<?php if($student_fields_required['mother_img'] == 0): ?>
                                                    <span style="color:red;">*</span>
                                                <?php endif; ?>
                                                </lable>
									<div class="input file form-control">
										<input type="file" name="mother_img" id="mother_img" value="<?php echo e(old('mother_img')); ?>" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'/default/user_image.jpg'); ?>'">
								           <p class="text-danger" id="image_er"></p>
									</div>
								</div>
								<div class="col-md-1 <?php echo e($student_fields->contains('mother_img') ? '' : 'd-none'); ?>">
									<img src="<?php echo e(env('IMAGE_SHOW_PATH').'/student_image/profile_img.png'); ?>" width="60px" height="60px" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'/default/user_image.jpg'); ?>'">
								</div>
							</div>
							<hr>
							<div class="mesterClassAmt" class="row m-2"></div>
							<div class="col-md-12 text-center"> 
							
							<?php if(Session::get('student_count') >= Session::get('register_student')): ?>
								<button type="submit" class="btn btn-primary btn-submit"  id="submitButton"><?php echo e(__('common.submit')); ?></button><br><br>
							<?php endif; ?>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

</div>
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        var baseUrl = "<?php echo e(url('/')); ?>";
        
        $('#class_type_id').change(function(){
            var class_type_id = parseInt($(this).val());
            var orderBy = parseInt($(this).find('option:selected').attr('data-orderBy'));
            
            if(orderBy > 10){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: baseUrl + '/getStreamSubjects',
                    data: {
                        class_type_id: class_type_id
                    },
                    success: function(data) {
                        var options = "";
                        $('#stream_subject').html("");
                            for(var i = 0; i < data.length; i++){
                                options += '<option value="'+ data[i].id +'">'+ data[i].name +'</option>';
                            }
                        $('#stream_subject').html(options);
                        $('#stream_subject_div').show();
                    }
                });
            }else{
                $('#stream_subject').html("");
                $('#stream_subject_div').hide();
            }
        });
    });
</script>





<script>
$(document).ready(function() {
    // Handler for form submission
    $('#is-invalid').on('click', function(event) {
        var mobileValue = $('#mobile').val();
        var mobileMinLength = 10;

        if (mobileValue.length < mobileMinLength) {
            $('#mobileValidationMessage').show();
            event.preventDefault();  
            $('html, body').animate({
            scrollTop: 0
        }, 800);
        } else {
            $('#mobileValidationMessage').hide();
        }

        // Perform father's mobile input validation
        var father_mobileInputValue = $('#father_mobile').val();
        var fatherMobileMinLength = 10;

        if (father_mobileInputValue.length < fatherMobileMinLength) {
            $('#fathermobileValidationMessage').show();
            event.preventDefault(); 
            $('html, body').animate({
            scrollTop: 0
        }, 800);
        } else {
            $('#fathermobileValidationMessage').hide();
        }
    });
});
</script>


<style>
    #image_error{
        font-weight: bold;
        font-size: 14px;
    }
    #image_er{
        font-weight: bold;
        font-size: 14px;
    }
    #image_errors{
        font-weight: bold;
        font-size: 14px;
    }
    
    .blink_me {
        animation: blinker 1s linear infinite;
    }

    @keyframes  blinker {
      50% {
        opacity: 0;
      }
    }
</style>



<style>
	@media  only screen and (max-width: 600px) {
		.upload {
			margin-left: 27%;
			margin-top: 7%;
		}
	}
</style>
<script>
$(document).ready(function(){
    $('#class_type_id').val('');
    
    $('#class_type_id').change(function(){
        if($('#admission_type_id').val() == 1){
            $('.mesterClassAmt').removeClass('d-none');
            mesterData();
        }else{
            $('.mesterClassAmt').addClass('d-none');
        }
    });
    
    $('#admission_type_id').change(function(){
      //      $('#class_type_id').val('');
      
            if($('#admission_type_id').val() == 1){
            $('.mesterClassAmt').removeClass('d-none');
            mesterData();
        }else{
            $('.mesterClassAmt').addClass('d-none');
        }veClass('d-none');
            mesterData();
    });
    
})
	var basurl = "<?php echo e(url('/')); ?>";
	function mesterData() {
	
		var class_type_id = $('#class_type_id :selected').val();
		if (class_type_id > 0) {
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				},
				type: 'post',
				url: basurl + '/mesterClassAmt',
				data: {
					class_type_id: class_type_id
				},
				//dataType: 'json',
				success: function(data) {
                if(data != ""){
                     $('.mesterClassAmt').show();
                    	$('.mesterClassAmt').html(data);

                }else{
                   $('.mesterClassAmt').hide();
                   //$('#class_type_id').val("");
                    alert('Please assign master fees*!');
                    //  window.open(basurl+'/feesMasterAdd', 'blank');
                  
                    			
                }

				}
			});
		} else {
			toastr.error('Please put a value in one column !');
		}
	};
		function sum_amount(amot) {
		    var sum = 0;
    
            sum += amot;
       
            $("#net_amount").val(sum.toFixed(2));
		}
		
		
		
	function SearchValue() {
		var basurl = "<?php echo e(url('/')); ?>";
		var name = $('#searchName').val();
		var registration_no = $('#registration_no').val();
		var class_search_id = $('#class_search_id :selected').val();
		if (class_search_id > 0 || registration_no != '' || name != '') {
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				},
				type: 'post',
				url: basurl + '/admissionStudentSearch',
				data: {
					class_search_id: class_search_id,
					name: name,
					registration_no: registration_no
				},
				//dataType: 'json',
				success: function(data) {
                    $('.student_list_show').addClass('fadeinout');
					$('.student_list_show').html(data);
                setTimeout(function() {
                         $('.student_list_show').removeClass('fadeinout');
                     }, 9000);
				}
			});
		} else {
			toastr.error('Please put a value in one column !');
		}
	};
    
 

// ======= Utility: Today Date (YYYY-MM-DD) =======
function getToday() {
    let t = new Date();
    let yyyy = t.getFullYear();
    let mm = ("0" + (t.getMonth() + 1)).slice(-2);
    let dd = ("0" + t.getDate()).slice(-2);
    return `${yyyy}-${mm}-${dd}`;
}

// Block future date in DOB
$('#dob').attr("max", getToday());
</script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <script>
flatpickr("#dob", {
    maxDate: "today",
    disable: [
        function(date) {
            return date > new Date(); // future dates disable
        }
    ],
    onDayCreate: function(dObj, dStr, fp, dayElem){
        if(dayElem.dateObj > new Date()){
            dayElem.style.color = "red";       // future date red
            dayElem.style.background = "#ffe6e6";
        }
    }
});

</script>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/students/admission/add.blade.php ENDPATH**/ ?>