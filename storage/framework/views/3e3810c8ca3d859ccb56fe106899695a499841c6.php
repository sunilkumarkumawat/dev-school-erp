<?php
$getTypeclass = Helper::classType();
$getCountry = Helper::getCountry();
$getState = Helper::getState();
$getCity = Helper::getCity();
$getgenders = Helper::getgender();
?>

<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content pt-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card card-outline card-orange">
            <div class="card-header bg-primary">
              <h3 class="card-title"><i class="fa fa-address-book-o"></i> &nbsp;<?php echo e(__('student.Students Enquiry')); ?> </h3>
              <div class="card-tools">
                <a href="<?php echo e(url('enquiryView')); ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> <?php echo e(__('common.View')); ?></a>
                <a href="<?php echo e(url('reception_file')); ?>" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> <?php echo e(__('common.Back')); ?> </a>
              </div>
            </div>

            <form id="form-submit" action="<?php echo e(url('enquiryAdd')); ?>" method="post" enctype="multipart/form-data">
              <?php echo csrf_field(); ?>

              <div class="row m-2">
                <div class="col-md-3">
                  <div class="form-group">
                    <label style="color:red;"><?php echo e(__('Full Name')); ?>*</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           onkeydown="return /[a-zA-Z ]/i.test(event.key)" id="first_name" name="first_name"
                           placeholder="<?php echo e(__('common.First Name')); ?>" value="<?php echo e(old('first_name')); ?>">
                    <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
                </div>

                <!-- Student Mobile (added) -->
                <div class="col-md-3">
                  <div class="form-group">
                    <label style="color:red;"><?php echo e(__('common.Mobile')); ?>*</label>
                    <input type="tel" class="form-control <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="mobile"
                           name="mobile" placeholder="<?php echo e(__('common.Mobile')); ?>" value="<?php echo e(old('mobile')); ?>"
                           maxlength="10" minlength="10" onkeypress="return isNumber(event)">
                    <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label style="color:red;"><?php echo e(__('common.Gender')); ?>*</label>
                    <select class="form-control <?php $__errorArgs = ['gender_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> select2" id="gender_id" name="gender_id">
                      <option value=""><?php echo e(__('common.Select')); ?></option>
                      <?php if(!empty($getgenders)): ?>
                        <?php $__currentLoopData = $getgenders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($value->id); ?>" <?php echo e(($value->id == old('gender_id')) ? 'selected' : ''); ?>>
                            <?php echo e($value->name ?? ''); ?>

                          </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['gender_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>DOB</label>
                    <input type="date" class="form-control" id="dob" name="dob" placeholder=" Date Of Birth" value="<?php echo e(old('dob')); ?>">
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label style="color:red;"><?php echo e(__('common.Fathers Name')); ?>*</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['father_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           onkeydown="return /[a-zA-Z ]/i.test(event.key)" id="father_name" name="father_name"
                           placeholder="<?php echo e(__('common.Fathers Name')); ?>" value="<?php echo e(old('father_name')); ?>">
                    <?php $__errorArgs = ['father_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label><?php echo e(__('common.Mothers Name')); ?></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['mother_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           onkeydown="return /[a-zA-Z ]/i.test(event.key)" id="mother_name" name="mother_name"
                           placeholder="<?php echo e(__('common.Mothers Name')); ?>" value="<?php echo e(old('mother_name')); ?>">
                    <?php $__errorArgs = ['mother_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  </div>
                </div>


                <!-- class -->
                <div class="col-md-3">
                  <div class="form-group">
                    <label><?php echo e(__('common.Class')); ?></label>
                    <select class="select2 form-control" id="class_type_id" name="class_type_id">
                      <option value=""><?php echo e(__('common.Select')); ?></option>
                      <?php if(!empty($getTypeclass)): ?>
                        <?php $__currentLoopData = $getTypeclass; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id == old('class_type_id')) ? 'selected' : ''); ?>>
                            <?php echo e($type->name ?? ''); ?>

                          </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </select>
                  </div>
                </div>

                <!-- email -->
                <div class="col-md-3">
                  <div class="form-group">
                    <label><?php echo e(__('common.E-Mail')); ?></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo e(__('common.E-Mail')); ?>" value="<?php echo e(old('email')); ?>">
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label><?php echo e(__('No Of Child ')); ?></label>
                    <input type="text" class="form-control" id="no_of_child" name="no_of_child" placeholder="<?php echo e(__('No Of Child ')); ?>" value="<?php echo e(old('no_of_child')); ?>">
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="assigned">Assigned By</label>
                    <select class="form-control select2" id="assigned_by" name="assigned_by" autocomplete="off">
                      <option value="">Select</option>
                      <?php if(!empty($users)): ?>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($u->id); ?>" <?php echo e((old('assigned_by')==$u->id)?'selected':''); ?>><?php echo e($u->first_name ?? $u->userName); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="reference">Reference</label>
                    <select class="form-control select2" id="reference_id" name="reference_id" autocomplete="off">
                      <option value="">Select</option>
                      <?php if(!empty($references)): ?>
                        <?php $__currentLoopData = $references; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ref): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($ref->id); ?>" <?php echo e((old('reference_id')==$ref->id)?'selected':''); ?>><?php echo e($ref->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="response_select">Response</label>
                    <select class="form-control select2" id="response_id" name="response_id" autocomplete="off">
                      <option value="">Select</option>
                      <?php if(!empty($responses)): ?>
                        <?php $__currentLoopData = $responses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($resp->id); ?>" <?php echo e((old('response_id')==$resp->id)?'selected':''); ?>><?php echo e($resp->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </select>
                  </div>
                </div>

                <!-- previous school textarea -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label><?php echo e(__('Previous School')); ?></label>
                    <textarea class="form-control" id="previous_school" name="previous_school" placeholder="<?php echo e(__('Previous School Name')); ?>" rows="2"><?php echo e(old('previous_school')); ?></textarea>
                  </div>
                </div>

                <!-- response textarea (renamed) -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label><?php echo e(__('Response')); ?></label>
                    <textarea class="form-control" id="response" name="response" placeholder="<?php echo e(__('Response')); ?>" rows="2"><?php echo e(old('response')); ?></textarea>
                  </div>
                </div>

                <!-- note (fixed name) -->
                <div class="col-md-12">
                  <div class="form-group">
                    <label><?php echo e(__('Note')); ?></label>
                    <textarea class="form-control" id="note" name="note" placeholder="<?php echo e(__('Note')); ?>" rows="2"><?php echo e(old('note')); ?></textarea>
                  </div>
                </div>

              </div>

              <div class="row m-2">
                <div class="col-md-12 text-center pb-2">
                  <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                </div>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php $__env->startSection('scripts'); ?>
<script>
  $(function () {
    $('.select2').select2();
    $('.select2bs4').select2({ theme: 'bootstrap4' });
  });
  function isNumber(evt){ var ch = String.fromCharCode(evt.which); if(!(/[0-9]/).test(ch)) evt.preventDefault(); }
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/students/enquiry/add.blade.php ENDPATH**/ ?>