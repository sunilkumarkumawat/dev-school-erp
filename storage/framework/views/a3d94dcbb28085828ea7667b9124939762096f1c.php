<?php
  $classType = Helper::classTypeExam();
  $getsubject = Helper::getSubject();
   $examTerm = DB::table('exam_terms')->where('branch_id',Session::get('branch_id'))->where('session_id',Session::get('session_id'))->whereNull('deleted_at')->get();
?>

<?php $__env->startSection('content'); ?>

<div class="content-wrapper">

   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-orange">
                     <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="nav-icon fas fa fa-edit"></i> &nbsp;<?php echo e(__('examination.Edit Exam')); ?> </h3>
                    <div class="card-tools">
                    <a href="<?php echo e(url('view/exam')); ?>" class="btn btn-primary  btn-sm" title="View Users"><i class="fa fa-eye"></i> View </a>
                    <a href="<?php echo e(url('examination_dashboard')); ?>" class="btn btn-primary  btn-sm" title="View Users"><i class="fa fa-arrow-left"></i> <?php echo e(__('common.Back')); ?>  </a>
                    </div>
                    
                    </div>        
                <form id="quickForm" action="<?php echo e(url('edit/exam')); ?>/<?php echo e($data->id ?? ''); ?>" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row m-2">

                       <div class="col-md-3">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('examination.Exam Name')); ?>*</label>
            				<input type="text" id="name" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e(__('examination.Exam Name')); ?>" value="<?php echo e($data['name'] ?? ''); ?>">
                             <?php $__errorArgs = ['name'];
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
            		<div class="col-md-3">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('Exam Term')); ?>*</label>
            				<select class="select2 form-control exam_term_id" id="exam_term_id" name="exam_term_id" >
                                 <option value=""><?php echo e(__('messages.Select')); ?></option>
                                 <?php if(!empty($examTerm)): ?> 
                                
                                 <?php $__currentLoopData = $examTerm; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <option value="<?php echo e($type->id); ?> " <?php echo e($type->id == $data->exam_term_id ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?> </option>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                 <?php endif; ?>
                                 <?php $__errorArgs = ['exam_term_id'];
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
            		</div>
            		<div class="col-md-3">
						<div class="form-group">
							<label style="color:red;"><?php echo e(__('common.Class')); ?>*</label>
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
								<option value="<?php echo e($type->id ?? ''); ?>" <?php echo e($type->id == $data->class_type_id ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
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
                    <!--<div class="col-md-3">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('examination.Exam Date')); ?>* :</label>
                            <input type="date" id="exam_date" name="exam_date" class="form-control <?php $__errorArgs = ['exam_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e($data['exam_date'] ?? ''); ?>">
                             <?php $__errorArgs = ['exam_date'];
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
            		
                    <div class="col-md-3">
            			<div class="form-group">
            				<label style="color:red;"><?php echo e(__('examination.Exam Code')); ?>* :</label>
                            <input type="text" id="exam_code" name="exam_code" class="form-control <?php $__errorArgs = ['exam_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e($data['exam_code'] ?? ''); ?>">
                             <?php $__errorArgs = ['exam_code'];
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
           		
                    <div class="col-md-3">
            			<div class="form-group">
            				<label><?php echo e(__('examination.Time Duration In Minutes')); ?>:</label>
                            <input type="number" id="duration" name="duration" class="form-control <?php $__errorArgs = ['duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e($data['duration'] ?? ''); ?>">
                             <?php $__errorArgs = ['duration'];
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
            		</div> -->       
                    <div class="col-md-12">
            			<div class="form-group">
            				<label><?php echo e(__('examination.Description')); ?></label>
                            <textarea id="description" name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> "><?php echo e($data['description'] ?? ''); ?></textarea>
                             <?php $__errorArgs = ['description'];
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
        		
		        </div>

                <div class="row m-2 pb-2">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary "><?php echo e(__('common.Update')); ?></button>
                    </div>
                </div>
                
            
            </form>
</div>
</div>
</div>
</div>
</section>
</div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/examination/offline_exam/exam/edit.blade.php ENDPATH**/ ?>