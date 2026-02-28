<?php
$getgenders = Helper::getgender();
$classType = Helper::classType();
$getCountry = Helper::getCountry();
$bloodGroupType = Helper::bloodGroupType();
$list = DB::table('custom_villages_list')->orderBy('name','ASC')->whereNull('deleted_at')->get();
?>

	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row"> 
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						
						<form id="form-admission-edit" action="<?php echo e(url('admissionEdit')); ?>/<?php echo e($data['id']); ?>" method="post" enctype="multipart/form-data">
							<?php echo csrf_field(); ?>
							<div class="row m-4">
								<div class=" col-md-12 title mt-n3">
									<h5 class="text-danger"><?php echo e(__('messages.Personal Details')); ?>:-</h5>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('student.Admission No.')); ?></label>
										<input type="text" class="form-control" id="admissionNo" name="admissionNo" placeholder="Admission No" value="<?php echo e($data['admissionNo']); ?>" onkeypress="javascript:return isNumber(event)">
									   
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Ledger No')); ?><span style=""></span></label>
										<input type="text" class="form-control " name="ledger_no" placeholder="<?php echo e(__('Ledger No')); ?>"  value="<?php echo e($data['ledger_no']); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Student Pen</label>
										<input type="text" class="form-control" id="student_pen" name="student_pen" placeholder="Student Pen" value="<?php echo e($data['student_pen']); ?>" onkeypress="javascript:return isNumber(event)" >
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Apaar Id</label>
										<input type="text" class="form-control" id="apaar_id" name="apaar_id" placeholder="Apaar Id" value="<?php echo e($data['apaar_id']); ?>" onkeypress="javascript:return isNumber(event)">
									</div>
								</div>
							
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Family ID')); ?></label>
										<input type="text" class="form-control" id="family_id" name="family_id" value="<?php echo e($data['family_id'] ?? ''); ?>" placeholder="<?php echo e(__('Family ID')); ?>" onkeypress="javascript:return isNumber(event)">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Student Name')); ?><span style="color:red;">*</span></label>
										<input type="text" name="first_name" id="first_name" class="form-control invalid" value="<?php echo e($data['first_name'] ?? ''); ?>" placeholder="<?php echo e(__('Student Name')); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)">
									
									</div>
								</div>
								<!--<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('common.Last Name')); ?></label>
										<input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo e($data['last_name'] ?? ''); ?>" placeholder="<?php echo e(__('common.Last Name')); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)">
									</div>
								</div>-->
							
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('common.Aadhaar No.')); ?></label>
										<input type="text" class="form-control" id="aadhaar" name="aadhaar" placeholder=" <?php echo e(__('common.Aadhaar No.')); ?>" value="<?php echo e($data['aadhaar'] ?? ''); ?>" maxlength="12" onkeypress="javascript:return isNumber(event)">
									
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Jan Aadhaar No.')); ?></label>
										<input type="text" class="form-control" id="jan_aadhaar" name="jan_aadhaar" placeholder=" <?php echo e(__('Jan Aadhaar No.')); ?>" value="<?php echo e($data['jan_aadhaar'] ?? ''); ?>" maxlength="10" onkeypress="javascript:return isNumber(event)">
									
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('common.Gender')); ?><span style="color:red;">*</span></label>
										<select class="form-control invalid" id="gender_id" name="gender_id">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php if(!empty($getgenders)): ?>
											<?php $__currentLoopData = $getgenders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($value->id); ?>" <?php echo e(( $value->id == $data['gender_id'] ? 'selected' : '' )); ?>><?php echo e($value->name ?? ''); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select>
									
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('common.Date Of  Birth')); ?><span style="color:red;">*</span></label>
										<input type="date" class="form-control invalid" id="dob" name="dob" placeholder=" Date Of  Birth" value="<?php echo e($data['dob'] ?? ''); ?>">
									
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('common.Mobile No.')); ?></label>
										<!--<input type="text" class="form-control " id="mobile" name="mobile" placeholder="<?php echo e(__('common.Mobile No.')); ?>" value="<?php echo e($data['mobile'] ?? ''); ?>"minlength="10" maxlength="10" onkeypress="javascript:return isNumber(event)">-->
						                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="<?php echo e(__('common.Mobile No.')); ?>" value="<?php echo e($data['mobile'] ?? ''); ?>" maxlength="10">
                                            <div id="mobileValidationMessage" style="color: red; display: none; font-size:13px;">must be at least 10 characters</div>

									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('common.E-Mail')); ?></label>
										<input type="email" class="form-control " id="email" name="email" placeholder="<?php echo e(__('common.E-Mail')); ?>" value="<?php echo e($data['email'] ?? ''); ?>">
							          
									</div>
								</div>
								


								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('common.Class')); ?><span style="color:red;">*</span></label>

										<select class="form-control invalid" id="class_type_id" name="class_type_id">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php if(!empty($classType)): ?>
											<?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($type->id ?? ''); ?>" data-orderBy="<?php echo e($type->orderBy ?? ''); ?>"  <?php echo e(( $type->id == $data['class_type_id'] ? 'selected' : '' )); ?> ><?php echo e($type->name ?? ''); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select>
										
									</div>
								</div>
								
								<?php
								    $streamSubjects = Helper::getStreamSubjects($data->class_type_id ?? '');
								?>
								
								<div class="col-md-2" id="stream_subject_div" style="display:<?php echo e($data['stream_subject'] != "" ? 'block' : 'none'); ?>">
									<div class="form-group">
										<label>Stream Subject<span style="color:red;">*</span></label>

										<select class="form-control select2" multiple id="stream_subject" name="stream_subject[]">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php if(!empty($streamSubjects)): ?>
                                                <?php $__currentLoopData = $streamSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($subject->id ?? ''); ?>" <?php echo e(in_array($subject->id, explode(',', $data->stream_subject)) ? 'selected' : ''); ?>><?php echo e($subject->name ?? ''); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
										</select>
									</div>
								</div>
								
								
								<div class="col-md-2">
									<div class="form-group">
										<label>Admission Type(Non RTE)</label>
										<select class="form-control" id="admission_type_id" name="admission_type_id">
											<option value="1" <?php echo e((1 == $data['admission_type_id'] ? 'selected' : '' )); ?>>Yes</option>
											<option value="2" <?php echo e((2 == $data['admission_type_id'] ? 'selected' : '' )); ?>>No</option>
										</select>
										
									</div>
								</div>
									<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Religion')); ?></label>
										<select class="form-control" id="religion" name="religion">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<option value="Hindu" <?php echo e(('Hindu' == $data['religion'] ? 'selected' : '' )); ?>>Hindu</option>
											<option value="Islam" <?php echo e(('Islam' == $data['religion'] ? 'selected' : '' )); ?>>Islam</option>
											<option value="Sikh" <?php echo e(('Sikh' == $data['religion'] ? 'selected' : '' )); ?>>Sikh</option>
											<option value="Buddhism" <?php echo e(('Buddhism' == $data['religion'] ? 'selected' : '' )); ?>>Buddhism</option>
											<option value="Adivasi" <?php echo e(('Adivasi' == $data['religion'] ? 'selected' : '' )); ?>>Adivasi</option>
											<option value="Jain" <?php echo e(('Jain' == $data['religion'] ? 'selected' : '' )); ?>>Jain</option>
											<option value="Christianity" <?php echo e(('Christianity' == $data['religion'] ? 'selected' : '' )); ?>>Christianity</option>
											<option value="Other" <?php echo e(('Other' == $data['religion'] ? 'selected' : '' )); ?>>Other</option>
										</select>
										
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Category')); ?></label>
										<select class="form-control" id="category" name="category">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<option value="OBC" <?php echo e(('OBC' == $data['category'] ? 'selected' : '' )); ?>>OBC</option>
											<option value="ST" <?php echo e(('ST' == $data['category'] ? 'selected' : '' )); ?>>ST</option>
											<option value="SC" <?php echo e(('SC' == $data['category'] ? 'selected' : '' )); ?>>SC</option>
											<option value="BC" <?php echo e(('BC' == $data['category'] ? 'selected' : '' )); ?>>BC</option>
											<option value="GEN" <?php echo e(('GEN' == $data['category'] ? 'selected' : '' )); ?>>GEN</option>
											<option value="SBC" <?php echo e(('SBC' == $data['category'] ? 'selected' : '' )); ?>>SBC</option>
											<option value="Other" <?php echo e(('Other' == $data['category'] ? 'selected' : '' )); ?>>Other</option>
										</select>
										
									</div>
								</div>
								
									<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Caste')); ?></label>
										<input type="text" class="form-control" id="caste_category" name="caste_category" placeholder="<?php echo e(__('Caste')); ?>" value="<?php echo e($data['caste_category'] ?? ''); ?>" >
									
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Blood Group')); ?></label>
										<select class="form-control" id="blood_group" name="blood_group">
											<option value=""><?php echo e(__('common.Select')); ?></option>
        										<?php if(!empty($bloodGroupType)): ?>
        											<?php $__currentLoopData = $bloodGroupType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bloodtype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        											<option value="<?php echo e($bloodtype->name ?? ''); ?>" <?php echo e(( $bloodtype->name == $data['blood_group'] ? 'selected' : '' )); ?>><?php echo e($bloodtype->name ?? ''); ?></option>
        											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        										<?php endif; ?>
										</select>
									
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Medium</label>
										<select class="form-control" id="medium" name="medium">
											<option value="">Select</option>
											<option value="Hindi" <?php echo e(('Hindi' == $data['medium'] ? 'selected' : '' )); ?>>Hindi</option>
											<option value="English" <?php echo e(('English' == $data['medium'] ? 'selected' : '' )); ?>>English</option>
										</select>
									</div>
								</div>
                                <div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('student.Date Of Admission')); ?></label>
										<input type="date" class="form-control" id="admission_date" name="admission_date" value="<?php echo e($data['admission_date'] == '1970-01-01' ? '' : $data['admission_date'] ?? ''); ?>">
									
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('common.Country')); ?></label>
										<select class="form-control" name="country" id="country_id">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php if(!empty($getCountry)): ?>
											<?php $__currentLoopData = $getCountry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($country->id ?? ''); ?>" <?php echo e(( $country->id == $data['country_id'] ? 'selected' : '' )); ?> ><?php echo e($country->name ?? ''); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="State" class="required"><?php echo e(__('common.State')); ?></label>
										<select class="form-control stateId " id="state_id" name="state">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php if(!empty($getState)): ?>
											<?php $__currentLoopData = $getState; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($state->id ?? ''); ?>" <?php echo e(( $state->id == $data['state_id'] ? 'selected' : '' )); ?> ><?php echo e($state->name ?? ''); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select>

									</div> 
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="City"><?php echo e(__('common.City')); ?></label>
										<select class="form-control cityId " name="city" id="city_id">
											<option value=""><?php echo e(__('common.Select')); ?></option>
											<?php if(!empty($getCity)): ?>
											<?php $__currentLoopData = $getCity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cities): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($cities->id ?? ''); ?>" <?php echo e(( $cities->id == $data['city_id'] ? 'selected' : '' )); ?> ><?php echo e($cities->name ?? ''); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
								<!--	<div class="col-md-2">-->
								<!--	<div class="form-group">-->
								<!--		<label><?php echo e(__('student.Village/City')); ?></label>-->
								<!--		<select class="form-control select2 " id="village_city" name="village_city">-->
								<!--			<option value=""><?php echo e(__('common.Select')); ?></option>-->
								<!--			<?php if(!empty($list)): ?>-->
								<!--			<?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>-->
								<!--			<option value="<?php echo e($type->name ?? ''); ?>" <?php echo e(( $type->name == $data['village_city'] ? 'selected' : '' )); ?>><?php echo e($type->name ?? ''); ?></option>-->
								<!--			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>-->
								<!--			<?php endif; ?>-->
								<!--		</select>-->
								<!--	</div>-->
								<!--</div>-->
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('messages.Village/City')); ?></label>
										<input type="text" class="form-control" id="village_city" name="village_city" placeholder="<?php echo e(__('messages.Village/City')); ?>" value="<?php echo e($data['village_city'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('student.Students Address')); ?></label>
										<input type="text" class="form-control " id="address" name="address" placeholder="<?php echo e(__('student.Students Address')); ?>" value="<?php echo e($data['address'] ?? ''); ?>">
										
									</div>
								</div>
									<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Family Annual Income')); ?></label>
										<input type="text" name="family_annual_income" id="family_annual_income" class="form-control" value="<?php echo e($data['family_annual_income'] ?? ''); ?>" placeholder="<?php echo e(__('Family Annual Income')); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Relation With The Student')); ?></label>
										<input type="text" name="relation_student" id="relation_student" class="form-control" value="<?php echo e($data['relation_student'] ?? ''); ?>" placeholder="<?php echo e(__('Relation With The Student')); ?>">
										
									</div>
								</div>
								
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('School Studied Last Year')); ?></label>
										<input type="text" name="school_namestudied_last_year" id="school_namestudied_last_year" class="form-control" value="<?php echo e($data['school_namestudied_last_year'] ?? ''); ?>" placeholder="<?php echo e(__('School Studied Last Year')); ?>">
										
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('House')); ?></label>
										<input type="text" name="house" id="house" class="form-control" value="<?php echo e($data['house'] ?? ''); ?>" placeholder="<?php echo e(__('House')); ?>">
										
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Height')); ?></label>
										<input type="text" name="height" id="height" class="form-control" value="<?php echo e($data['height'] ?? ''); ?>" placeholder="<?php echo e(__('Height')); ?>">
										
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Weight')); ?></label>
										<input type="txt" name="weight" id="weight" class="form-control" value="<?php echo e($data['weight'] ?? ''); ?>" placeholder="<?php echo e(__('Weight')); ?>">
										
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('messages.Pin Code')); ?></label>
										<input type="text" class="form-control" id="pincode" name="pincode" placeholder="<?php echo e(__('messages.Pin Code')); ?>" value="<?php echo e($data['pincode'] ?? ''); ?>" maxlength="6" onkeypress="javascript:return isNumber(event)">
										
									</div>
								</div>
                                
								
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('student.Remark')); ?> </label>
										<input type="text" class="form-control" id="remark_1" name="remark_1" placeholder="<?php echo e(__('student.Remark')); ?> " value="<?php echo e($data['remark_1'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Bus Number')); ?> </label>
										<input type="text" class="form-control" id="bus_number" name="bus_number" placeholder="<?php echo e(__('Bus Number')); ?> " value="<?php echo e($data['bus_number'] ?? ''); ?> ">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Bus Route')); ?> </label>
										<input type="text" class="form-control" id="bus_route" name="bus_route" placeholder="<?php echo e(__('Bus Route')); ?> " value="<?php echo e($data['bus_route'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Stoppage')); ?> </label>
										<input type="text" class="form-control" id="stoppage" name="stoppage" placeholder="<?php echo e(__('Stoppage')); ?> " value="<?php echo e($data['stoppage'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Transpor Charges')); ?> </label>
										<input type="text" class="form-control" id="transpor_charges" name="transpor_charges" placeholder="<?php echo e(__('Transpor Charges')); ?> " value="<?php echo e($data['transpor_charges'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Bank Name')); ?> </label>
										<input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="<?php echo e(__('Bank Name')); ?> " value="<?php echo e($data['bank_name'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Bank Account')); ?> </label>
										<input type="text" class="form-control" id="bank_account" name="bank_account" placeholder="<?php echo e(__('Bank Account')); ?> " value="<?php echo e($data['bank_account'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Branch Name')); ?> </label>
										<input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="<?php echo e(__('Branch Name')); ?> " value="<?php echo e($data['branch_name'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('IFSC')); ?> </label>
										<input type="text" class="form-control" id="ifsc" name="ifsc" placeholder="<?php echo e(__('IFSC')); ?> " value="<?php echo e($data['ifsc'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__(' MICR Code')); ?> </label>
										<input type="text" class="form-control" id="micr_code" name="micr_code" placeholder="<?php echo e(__('MICR Code')); ?> " value="<?php echo e($data['micr_code'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Bank Account Holder</label>
										<input type="text" class="form-control" id="bank_account_holder" name="bank_account_holder" placeholder="Bank Account Holder" value="<?php echo e($data['bank_account_holder'] ?? ''); ?>">
									</div>
								</div>
							
								
								<div class="col-md-2">
									<div class="form-group">
										<label>District</label>
										<input type="text" class="form-control" id="district" name="district" placeholder="District" value="<?php echo e($data['district'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Tehsil</label>
										<input type="text" class="form-control" id="tehsil" name="tehsil" placeholder="Tehsil" value="<?php echo e($data['tehsil'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Father's Pancard</label>
										<input type="text" class="form-control" id="father_pancard" name="father_pancard" placeholder="Father's Pancard" value="<?php echo e($data['father_pancard'] ?? ''); ?>">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Mother's Pancard</label>
										<input type="text" class="form-control" id="mother_pancard" name="mother_pancard" placeholder="Mother's Pancard" value="<?php echo e($data['mother_pancard'] ?? ''); ?>">
									</div>
								</div>
							
								<div class="col-md-2">
									<div class="form-group">
										<label>BPL</label>
										<select class="form-control" id="bpl" name="bpl">
										    <option value="">Select</option>
										    <option value="Yes" <?php echo e("Yes" == $data['bpl'] ? 'selected' : ''); ?>>Yes</option>
										    <option value="No" <?php echo e("No" == $data['bpl'] ? 'selected' : ''); ?>>No</option>
										</select>
									</div> 
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>BPL Cetificate No.</label>
										<input type="text" class="form-control" id="bpl_certificate_no" name="bpl_certificate_no" placeholder="BPL Cetificate No." value="<?php echo e($data['bpl_certificate_no'] ?? ''); ?>">
									</div>
								</div>
							<div class="col-md-4">
									<div class="form-group">
										<label>Name  And  Address Of Previous School</label>
										<input type="text" class="form-control" id="previous_school" name="previous_school" placeholder="Name  And  Address Of Previous School" value="<?php echo e($data['previous_school'] ?? ''); ?>">
									</div>
								</div>
								
							</div>
							
							<div class="row m-2 ">
							    
							    </div>
							<div class="row m-2">
								<div class=" col-md-12 title">
									<h5 class="text-danger"><?php echo e(__('Guardian Ditels')); ?>:-</h5>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('common.Fathers Name')); ?><span style="color:red;">*</span></label>
										<input type="text" class="form-control invalid" id="father_name" name="father_name" placeholder="<?php echo e(__('common.Fathers Name')); ?>" value="<?php echo e($data['father_name'] ?? ''); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)">
									
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('common.Fathers Contact No')); ?><span style="color:red;">*</span></label>
										<input type="text" class="form-control invalid" id="father_mobile" name="father_mobile" placeholder="<?php echo e(__('common.Fathers Contact No')); ?>" value="<?php echo e($data['father_mobile'] ?? ''); ?>" maxlength="10" onkeypress="javascript:return isNumber(event)">
						                 <div id="fathermobileValidationMessage" style="color: red; display: none; font-size:13px;">must be at least 10 characters</div>

								
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Fathers Aadhaar')); ?></label>
										<input type="text" class="form-control" id="father_aadhaar" name="father_aadhaar" placeholder="<?php echo e(__('Fathers Aadhaar')); ?>" value="<?php echo e($data['father_aadhaar'] ?? ''); ?>" maxlength="12" onkeypress="javascript:return isNumber(event)">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Father Occupation</label>
										<input type="text" class="form-control" id="father_occupation" name="father_occupation" placeholder="Father Occupation" value="<?php echo e(old('father_occupation')); ?>">
									</div>
								</div>
                                
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('common.Mothers Name')); ?><span style="color:red;">*</span></label>
										<input type="text" class="form-control invalid" id="mother_name" name="mother_name" placeholder="<?php echo e(__('common.Mothers Name')); ?>" value="<?php echo e($data['mother_name'] ?? ''); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)">
									
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Mother Mobile No')); ?></label>
										<input type="text" class="form-control" id="mother_mob" name="mother_mob" placeholder="<?php echo e(__('Mother Mobile No')); ?>" value="<?php echo e($data['mother_mob'] ?? ''); ?>" maxlength="10" onkeypress="javascript:return isNumber(event)">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Mothers Aadhaar')); ?></label>
										<input type="text" class="form-control" id="mother_aadhaar" name="mother_aadhaar" placeholder="<?php echo e(__('Mothers Aadhaar')); ?>" value="<?php echo e($data['mother_aadhaar'] ?? ''); ?>" maxlength="12" onkeypress="javascript:return isNumber(event)">
									</div>
								</div>
								
                                <div class="col-md-2">
									<div class="form-group">
										<label>Mother Occupation</label>
										<input type="text" class="form-control" id="mother_occupation" name="mother_occupation" placeholder="Mother Occupation" value="<?php echo e($data['mother_occupation'] ?? ''); ?>">
									</div>
								</div>
								
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Guardian Name')); ?></label>
										<input type="text" class="form-control" id="guardian_name" name="guardian_name" placeholder="<?php echo e(__('Guardian Name')); ?>" value="<?php echo e($data['guardian_name'] ?? ''); ?>" onkeydown="return /[a-zA-Z ]/i.test(event.key)">
									
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label><?php echo e(__('Guardian Mobile No')); ?></label>
										<input type="text" class="form-control " id="guardian_mobile" name="guardian_mobile" placeholder="<?php echo e(__('Guardian Mobile No')); ?>" value="<?php echo e($data['guardian_mobile'] ?? ''); ?>" maxlength="10" onkeypress="javascript:return isNumber(event)">
								
									</div>
								</div>
							
								
							</div>
							<hr>
							<div class="row m-2">
								<div class=" col-md-12 title">
									<h5 class="text-danger"><?php echo e(__('messages.Document Upload')); ?>:-</h5>
								</div>
								<div class="col-md-3">
									<lable><?php echo e(__('student.Student Photo')); ?></lable>
									<div class="input file form-control">
										<input type="file" class="" name="student_img" id="student_img" value="<?php echo e($data['image'] ?? ''); ?>">
                                   
								    </div>
								</div>

								<div class="col-md-1">
									<img src="<?php echo e(env('IMAGE_SHOW_PATH').'profile/'.$data['image']); ?>" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/user_image.jpg'); ?>'" width="60px" height="60px">
								</div>
								<div class="col-md-3">
									<lable><b><?php echo e(__('messages.Father Photo')); ?></b></lable>
									<div class="input file form-control <?php $__errorArgs = ['father_img'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
										<input type="file" name="father_img" id="father_img" value="<?php echo e($data['father_img'] ?? ''); ?>">
										
									</div>
								</div>
								<div class="col-md-1">
									<img src="<?php echo e(env('IMAGE_SHOW_PATH').'father_image/'.$data['father_img']); ?>" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/user_image.jpg'); ?>'" width="60px" height="60px">
								</div>
								<div class="col-md-3">
									<lable><b><?php echo e(__('messages.Mother Photo')); ?></b></lable>
									<div class="input file form-control <?php $__errorArgs = ['mother_img'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
										<input type="file" name="mother_img" id="mother_img" value="<?php echo e($data['mother_img'] ?? ''); ?>">
										 
									
									</div>
								</div>
								<div class="col-md-1">
									<img src="<?php echo e(env('IMAGE_SHOW_PATH').'mother_image/'.$data['mother_img']); ?>" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/user_image.jpg'); ?>'" width="60px" height="60px">
								</div>
							</div>
							<hr>
							
							<div class="mesterClassAmt" class="row m-2"></div>
							<div class="col-md-12 text-center ">
							    <div >
								<button type="submit" class="btn btn-primary btn-submit"   id="is-invalid" ><?php echo e(__('messages.Update')); ?></button><br><br>
							</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>






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
</style>


<script>
$(document).ready(function(){
    var baseUrl = "<?php echo e(url('/')); ?>";

    // Initially hide/show based on selected
    toggleStream($('#class_type_id'));

    $('#class_type_id').change(function(){
        toggleStream($(this));
    });

    function toggleStream($el) {
        var orderBy = parseInt($el.find('option:selected').data('orderby')) || 0;
        var class_type_id = parseInt($el.val()) || 0;

        if(orderBy > 10){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: baseUrl + '/getStreamSubjects',
                data: { class_type_id: class_type_id },
                success: function(data) {
                    var options = "";
                    $.each(data, function(i, subject){
                        options += '<option value="'+ subject.id +'">'+ subject.name +'</option>';
                    });
                    $('#stream_subject').html(options);
                    $('#stream_subject_div').show();
                }
            });
        } else {
            $('#stream_subject').html("");
            $('#stream_subject_div').hide();
        }
    }
});
</script>



<script>
	$(function() {
		//Initialize Select2 Elements
		$('.select2').select2()

		//Initialize Select2 Elements
		$('.select2bs4').select2({
			theme: 'bootstrap4'
		})

	})
	
	$(document).on("submit", "#form-admission-edit", function (e) {
    e.preventDefault();
    var $form = $(this);
    var btn = $form.find(".btn-submit");
    var originalBtnText = btn.html(); // Save original button text
    var formData = new FormData(this);

    $.ajax({
        url: $form.attr("action"),
        type: "POST",
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            btn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            $(".error, .alert").remove(); // Clear old messages
            $(".is-invalid").removeClass("is-invalid");
        },
        success: function (response) {
            toastr.success(response.message);

            // Modal close karo
            $("#editStudentModal").modal("hide");
            $form[0].reset();
            btn.prop("disabled", false).html(originalBtnText);
        },
        error: function (xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                $.each(xhr.responseJSON.errors, function (index, value) {
                    var inputField = $form.find("[name='" + index + "']");
                    if (inputField.length) {
                        inputField.addClass("is-invalid");
                        if (inputField.closest(".form-group").find(".error").length === 0) {
                            inputField.closest(".form-group").append("<div class='error invalid-feedback'></div>");
                        }
                        inputField.closest(".form-group").find(".error").text(value[0]);
                    }
                });
            } else {
                var errorMessage = xhr.responseJSON?.message || "An unexpected error occurred.";
                toastr.error(errorMessage);
            }

            btn.prop("disabled", false).html(originalBtnText);
        },
    });
});

	
</script>




<?php /**PATH /home/rusofterp/public_html/dev/resources/views/students/admission/edit.blade.php ENDPATH**/ ?>