<?php
$getMessageType = Helper::getMessageType();
?>
 
<?php $__env->startSection('content'); ?>
<style>
       
        .switch_check {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 25px;
            margin-top: 10px;
        }

      
        .switch_check .check_new {
            opacity: 0;
            width: 0;
            height: 0;
        }

      
        .slider_check {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            border-radius: 34px;
            transition: 0.4s;
        }

        
        .slider_check::before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 9px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: 0.4s;
        }

        
        .check_new:checked+.slider_check {
            background-color: #2196F3;
        }

        .check_new:checked+.slider_check::before {
            transform: translateX(26px);
        }
    </style>
<div class="content-wrapper">

   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-orange">
                     <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fa fa-edit"></i> &nbsp;<?php echo e(__('master.Edit Message Contant')); ?> </h3>
                    <div class="card-tools">
                    <!--<a href="<?php echo e(url('add_user')); ?>" class="btn btn-primary  btn-sm" title="Add User"><i class="fa fa-plus"></i> Add </a>-->
                    <a href="<?php echo e(url('messageTemplate')); ?>" class="btn btn-primary  btn-sm" title="View Users"><i class="fa fa-eye"></i> <?php echo e(__('View')); ?>  </a>
                   <a href="<?php echo e(url('messageDashboard')); ?>" class="btn btn-primary  btn-sm" ><i class="fa fa-arrow-left"></i> <?php echo e(__('common.Back')); ?></a>
                    </div>
                    
                    </div>                 
                <form id="quickForm" action="<?php echo e(url('messageTemplateEdit')); ?>/<?php echo e($data->id); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="row m-2">
                            <div class="col-md-3" >
                                <label class="text-danger"><?php echo e(__('master.Message Type')); ?>*</label>
                                <select class="form-control select2 <?php $__errorArgs = ['message_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="message_type_id" id="message_type_id">
                                    <option value=""><?php echo e(__('common.Select')); ?></option>
                                  <?php if(!empty($getMessageType)): ?> 
                                      <?php $__currentLoopData = $getMessageType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                         <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e($type->id == old('message_type_id', $data->message_type_id) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['message_type_id'];
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
                            <div class="col-md-6">
                    			<label class="text-danger"><?php echo e(__('master.Subject/ Title Name')); ?>*</label>
                    			<input class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" name="title" id="title" placeholder="<?php echo e(__('master.Message Title')); ?>" value="<?php echo e($data->title ?? old('title')); ?>">
                                <?php $__errorArgs = ['title'];
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
                        	<div class="col-md-12 pt-2">
                                <div class="form-group">
                                    <label class="text-danger"><b><?php echo e(__('master.E-mail Content')); ?>*</b></label>
                                    <textarea type="text" class="form-control <?php $__errorArgs = ['email_content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="editor1" name="email_content" placeholder="<?php echo e(__('master.E-mail Content')); ?>"><?php echo e($data->email_content ??  old('email_content')); ?></textarea>
                                    <?php $__errorArgs = ['email_content'];
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
            					    
            					    <div>
            					        <label><b><?php echo e(__('master.Email Status')); ?></b></label>
            					        <label class="switch_check">
                                            <input type="checkbox" class="check_new changeStatus" id="email_status" name="email_status" value="1" <?php echo e(($data->email_status == 1)? 'checked' : ''); ?>>
                                            <span class="slider_check"></span>
                                        </label>
            					    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b><?php echo e(__('master.SMS Content')); ?></b></label>
                                    <textarea type="text" class="form-control" id="whatsapp_content" name="sms_content" placeholder="Type Your Content" rows="5"><?php echo e($data->sms_content ??  old('sms_content')); ?></textarea>
                                    <div>
            					        <label><b><?php echo e(__('master.Sms Status')); ?></b></label>
            					        <label class="switch_check">
                                            <input type="checkbox" class="check_new changeStatus" id="sms_status" name="sms_status" value="1" <?php echo e(($data->sms_status == 1)? 'checked' : ''); ?>>
                                            <span class="slider_check"></span>
                                        </label>
            					    </div>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <label><b><?php echo e(__('master.SMS Template Id')); ?></b></label>
                                <input class="form-control " type="text" name="template_id" id="template_id" onkeypress="javascript:return isNumber(event)" placeholder=" <?php echo e(__('master.SMS Template Id')); ?>" value="<?php echo e($data->template_id ?? old('template_id')); ?>">
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b><?php echo e(__('master.Whatsapp Content')); ?></b></label>
                                    <textarea type="text" class="form-control" id="whatsapp_content" name="whatsapp_content" placeholder="<?php echo e(__('master.Whatsapp Content')); ?>" rows="5"><?php echo e($data->whatsapp_content ??  old('whatsapp_content')); ?></textarea>
                                    <div>
            					        <label><b><?php echo e(__('master.Whatsapp Status')); ?></b></label>
            					        <label class="switch_check">
                                            <input type="checkbox" class="check_new changeStatus" id="whatsapp_status" name="whatsapp_status" value="1" <?php echo e(($data->whatsapp_status == 1) ? 'checked' : ''); ?>>
                                            <span class="slider_check"></span>
                                        </label>
            					    </div>
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

<script>
$(document).ready(function(){
    var editor1 = new RichTextEditor("#editor1");
});

$(document).ready(function(){
    $('.changeStatus').click(function(){
            if ($(this).is(":checked")) {
                $(this).val("1");
              } else {
                $(this).val("0");
              }
        });
});
</script>
 <?php $__env->stopSection(); ?>    
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/message/template/edit.blade.php ENDPATH**/ ?>