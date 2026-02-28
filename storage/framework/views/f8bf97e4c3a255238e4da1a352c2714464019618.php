<?php
$getCountry = Helper::getCountry();
$getState = Helper::getState();
$getCity = Helper::getCity();
$roleType = Helper::roleType();
$getSetting = Helper::getSetting();

?>

<?php $__env->startSection('content'); ?>
<div class="content-wrapper">

    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-desktop"></i> &nbsp;<?php echo e(__('user.Add User')); ?> </h3>
                            <div class="card-tools">
                                <a href="<?php echo e(url('viewUser')); ?>"
                                    class="btn btn-primary  btn-sm <?php echo e(Helper::permissioncheck(6)->view ? '' : 'd-none'); ?>"
                                    title="View Users"><i class="fa fa-eye"></i> <?php echo e(__('common.View')); ?> </a>
                                <a href="<?php echo e(url('user_dashboard')); ?>" class="btn btn-primary  btn-sm"
                                    title="View Users"><i class="fa fa-arrow-left"></i> <?php echo e(__('common.Back')); ?> </a>
                            </div>

                        </div>
                        <form id="form-submit" action="<?php echo e(url('addUser')); ?>" method="post"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row m-2">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;"><?php echo e(__('Branch Access')); ?> *</label>
                                        <select
                                            class="form-control <?php $__errorArgs = ['access_branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> select2 "
                                            multiple="" name="access_branch_id[]">
                                            <option value=""><?php echo e(__('common.Select')); ?></option>
                                            <?php if(!empty($branch)): ?>
                                            <?php $__currentLoopData = $branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($Branch->id ?? ''); ?>" <?php echo e($Branch->id ==
                                                Session::get('access_branch_id') ? 'selected' : ''); ?>><?php echo e($Branch->branch_name ?? ''); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;"><?php echo e(__(' Name')); ?> *</label>
                                        <input type="text"
                                            class="form-control <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            onkeydown="return /[a-zA-Z ]/i.test(event.key)" id="first_name"
                                            name="first_name" value="<?php echo e(old('first_name')); ?>"
                                            placeholder="<?php echo e(__('common.First Name')); ?>">

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;"><?php echo e(__('common.Mobile No.')); ?> *</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="mobile" name="mobile" value="<?php echo e(old('mobile')); ?>"
                                            placeholder="<?php echo e(__('common.Mobile No.')); ?> " maxlength="10"
                                            onkeypress="javascript:return isNumber(event)">


                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;"><?php echo e(__('common.Email')); ?> *</label>
                                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="exampleInputEmail1" name="email" value="<?php echo e(old('email')); ?>"
                                            placeholder="<?php echo e(__('common.Email')); ?>">


                                    </div>
                                </div>
                                <!--<div class="col-md-2" >
                                    <div class="form-group">
                                     <label>Country</label>
                                      <select class="form-control select2" name="country" id="country_id">
                                          <?php if(!empty($getCountry)): ?> 
                                              <?php $__currentLoopData = $getCountry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                 <option value="<?php echo e($country->id ?? ''); ?>" <?php echo e(($country->id == Session::get('countries_id')) ? 'selected' : ''); ?>><?php echo e($country->name ?? ''); ?></option>
                                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                          <?php endif; ?>
                                      
                                      
                                        	<?php $__errorArgs = ['country'];
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
                                      </select>
                                      </div>
                                    </div>-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="State" class="required" style="color:red;"><?php echo e(__('common.State')); ?>*</label>
                                        <select class="select2 form-control <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="state_id" name="state">
                                            <option value=""><?php echo e(__('common.Select')); ?></option>
                                            <?php if(!empty($getState)): ?>
                                            <?php $__currentLoopData = $getState; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($state->id ?? ''); ?>" <?php echo e(($state->id ==
                                                $getSetting->state_id) ? 'selected' : ''); ?>><?php echo e($state->name ?? ''); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="City" style="color:red;"><?php echo e(__('common.City')); ?>*</label>
                                        <select class="select2 form-control <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="city" id="city_id">
                                            <option value=""><?php echo e(__('common.Select')); ?></option>
                                            <?php if(!empty($getCity)): ?>
                                            <?php $__currentLoopData = $getCity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cities): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($cities->id ?? ''); ?>" <?php echo e(($cities->id ==
                                                $getSetting->city_id) ? 'selected' : ''); ?>><?php echo e($cities->name ?? ''); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;"><?php echo e(__('common.Address')); ?> *</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="address" name="address" value="<?php echo e(old('address')); ?>"
                                            placeholder="<?php echo e(__('common.Address')); ?>">

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;"><?php echo e(__('user.User Name')); ?>*</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['userName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="userName" name="userName" value="<?php echo e(old('userName')); ?>"
                                            placeholder="<?php echo e(__('user.User Name')); ?>">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:red;"><?php echo e(__('common.Password')); ?>*</label>
                                    <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="password" name="password" value="<?php echo e(old('password')); ?>"
                                        placeholder="<?php echo e(__('common.Password')); ?>">


                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;"><?php echo e(__('user.Role')); ?>*</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control <?php $__errorArgs = ['role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                name="role_id" id="role_id">
                                                <option value=""><?php echo e(__('common.Select')); ?></option>
                                                <?php if(!empty($roleType)): ?>
                                                <?php
                                                $array = [1,3];
                                                ?>
                                                <?php $__currentLoopData = $roleType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(in_array($item->id, $array)): ?> {
                                                <?php else: ?>
                                                <option value="<?php echo e($item->id ?? ''); ?>"><?php echo e($item->name ?? ''); ?></option>
                                                <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                            <div class="input-group-append">
                                                <a href="<?php echo e(url('role_add')); ?>" target="_blank">
                                                    <button class="btn btn-primary" type="button" style="height: 100%;">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Class Name</label>
                                        <div class="custom-multi" id="classMulti">
                                            <button type="button" class="custom-btn" onclick="toggleDropdown()">
                                                <span id="selectedText">None selected Class</span>
                                                <span>▼</span>
                                            </button>
                                            <div class="custom-dropdown" id="ClassDropdown">
                                
                                                <!-- ✅ Select All Fixed -->
                                                <label class="dropdown-item">
                                                    <input type="checkbox" id="selectAll" onchange="selectAllFees(this)">
                                                    Select All
                                                </label>
                                
                                                <div id="feesOptions">
                                
                                                    <?php
                                                        $selectedClasses = explode(',', $data->class_type_id ?? '');
                                                    ?>
                                
                                                    <?php if(!empty(Helper::classType())): ?>
                                                        <?php $__currentLoopData = Helper::classType(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <label class="dropdown-item">
                                                                <input type="checkbox"
                                                                       class="class-checkbox"
                                                                       value="<?php echo e($type->id); ?>"
                                                                       data-name="<?php echo e($type->name); ?>"
                                                                       <?php echo e(in_array($type->id, $selectedClasses) ? 'checked' : ''); ?>

                                                                       onchange="updateSelectedText()">
                                                                <?php echo e($type->name); ?>

                                                            </label>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                
                                                </div>
                                            </div>
                                        </div>
                                
                                        <!-- Hidden Field -->
                                        <input type="hidden" name="class_type_id" id="class_type_id"
                                               value="<?php echo e($data->class_type_id ?? ''); ?>">
                                    </div>
                                </div>




                            </div>
                            <div class=" col-md-12 title">
                                <h5 style="color:red">Document Upload:-</h5>
                            </div>
                            <hr>
                            <div class="row m-2">

                                <!--camera img capture-->

                                <div class="row col md-12">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Photo</label>
                                            <input type="file" class="form-control " id="photo" name="photo" value=""
                                                accept="image/png, image/jpg, image/jpeg">
                                            <p class="text-danger" id="photo_error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Id Proof</label>
                                            <input type="file" class="form-control " id="id_proof" name="id_proof"
                                                value="<?php echo e(old('id_proof')); ?>" accept="image/png, image/jpg, image/jpeg">
                                            <p class="text-danger" id="proof_error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Qualification Proof</label>
                                            <input type="file" class="form-control " id="qualification_proof"
                                                name="qualification_proof" value="<?php echo e(old('qualification_proof')); ?>"
                                                accept="image/png, image/jpg, image/jpeg">
                                            <p class="text-danger" id="qualification_errors"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Experience Letter</label>
                                            <input type="file" class="form-control " id="experience_letter"
                                                name="experience_letter" value="<?php echo e(old('experience_letter')); ?>"
                                                accept="image/png, image/jpg, image/jpeg">
                                            <p class="text-danger" id="letter_errors"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Pan Card No.</label>
                                            <input type="text" class="form-control " id="pan_card" name="pan_card"
                                                placeholder="Pan Card No." value="<?php echo e(old('pan_card')); ?>" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <input type="text" class="form-control " id="bank" name="bank"
                                                placeholder="Bank Name" value="<?php echo e(old('bank')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Bank Account No.</label>
                                            <input type="text" class="form-control " id="account_no" name="account_no"
                                                placeholder="Bank Account No." value="<?php echo e(old('account_no')); ?>"
                                                maxlength="18">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Bank IFSC Code</label>
                                            <input type="text" class="form-control " id="ifsc_code" name="ifsc_code"
                                                placeholder="Bank IFSC Code" value="<?php echo e(old('ifsc_code')); ?>"
                                                maxlength="11">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Salary</label>
                                            <input type="text" class="form-control " id="salary" name="salary"
                                                placeholder="Salary" value="<?php echo e(old('salary')); ?>"
                                                onkeypress="javascript:return isNumber(event)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-2 pb-2">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-submit"><?php echo e(__('common.submit')); ?></button>
                                </div>
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
    $(document).ready(function () {
        $('#photo').change(function (e) {
            $('#image_error').html("");
            var fileName = $(this).val();
            var extension = fileName.split(".").pop();
            if (
                extension.toLowerCase() === "png" ||
                extension.toLowerCase() === "jpg" ||
                extension.toLowerCase() === "jpeg"
            ) {
                if (e.target.files[0].size > Img_Size) {
                    $('#image_error').html("please select Image Size under 2MB");
                    $(this).val('');
                } else {
                    $('#image_error').html("");
                }
            } else {
                $('#image_error').html("Image Size File");
                $(this).val('');
            }
        });
    });
</script>

<style>
    #image_error {
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


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/user/users/add.blade.php ENDPATH**/ ?>