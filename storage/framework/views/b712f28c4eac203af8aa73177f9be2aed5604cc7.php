 
<?php $__env->startSection('content'); ?>
<?php
$getMessageType = Helper::getMessageType();
?>
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
        <div class="col-md-12">
            <div class="card collapsed-card card-outline card-orange">
                <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fa fa-whatsapp"></i> &nbsp; <?php echo e(__('master.Add Message Template')); ?> </h3>
                <div class="card-tools">
                <button type="button" class="btn btn-tool <?php echo e(Helper::permissioncheck(9)->add ? '' : 'd-none'); ?>" data-card-widget="collapse"><i class="fa fa-plus text-white"></i>
                </button>
                <a href="<?php echo e(url('messageDashboard')); ?>" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i> <?php echo e(__('common.Back')); ?> </a>
                </div>
                
                </div>
                
                    <div class="card-body">
                        <form id="quickForm" action="<?php echo e(url('messageTemplate')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-3" >
                                <label class="text-danger"><?php echo e(__('master.Message Type')); ?> *</label>
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
                                         <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e($type->id == old('message_type_id') ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
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
unset($__errorArgs, $__bag); ?>" type="text" name="title" id="title" placeholder="<?php echo e(__('master.Message Title')); ?>" value="<?php echo e(old('title')); ?>">
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
                                    <div>
                                    <label class="text-danger"><b><?php echo e(__('master.E-mail Content')); ?>*</b></label>
                                    </div>
                                    <textarea type="text" class="form-control <?php $__errorArgs = ['email_content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="editor1" name="email_content" value="" placeholder="<?php echo e(__('master.E-mail Content')); ?>"></textarea>
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
                                            <input type="checkbox" class="check_new changeStatus" id="email_status" name="email_status" value="1" checked>
                                            <span class="slider_check"></span>
                                        </label>
            					    </div>
                                        
                                </div>
                            </div>
                          
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b><?php echo e(__('master.SMS Content')); ?></b></label>
                                    <textarea type="text" class="form-control" id="sms_content" name="sms_content" value="" placeholder="<?php echo e(__('master.SMS Content')); ?>" rows="5"></textarea>
                                    <div>
            					        <label><b><?php echo e(__('master.Sms Status')); ?></b></label>
            					        <label class="switch_check">
                                            <input type="checkbox" class="check_new changeStatus" id="sms_status" name="sms_status" value="1" checked>
                                            <span class="slider_check"></span>
                                        </label>
                					</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label><b><?php echo e(__('master.SMS Template Id')); ?></b></label>
                                <input class="form-control" type="text" name="template_id" id="template_id" onkeypress="javascript:return isNumber(event)" placeholder="<?php echo e(__('master.SMS Template Id')); ?>" value="">
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><b><?php echo e(__('master.Whatsapp Content')); ?></b></label>
                                    <textarea type="text" class="form-control" id="whatsapp_content" name="whatsapp_content" value="" placeholder="<?php echo e(__('master.Whatsapp Content')); ?>" rows="5"></textarea>
                                    <div>
            					        <label><b><?php echo e(__('master.Whatsapp Status')); ?></b></label>
            					        <label class="switch_check">
                                            <input type="checkbox" class="check_new changeStatus" id="whatsapp_status" name="whatsapp_status" value="1" checked>
                                            <span class="slider_check"></span>
                                        </label>
                					</div>
                                </div>
                            </div>                    	
                        </div>
         
                        <div class="row m-2">
                            <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary"><?php echo e(__('common.submit')); ?> </button>
                            </div>
                        </div>
                        </form>
                    </div>
                    </div>
                    </div>

        <div class="col-md-12">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fa fa-whatsapp"></i> &nbsp;<?php echo e(__('master.View Message Template')); ?> </h3>
                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus text-white"></i>
                </button>
                </div>
                
                </div>
                
                    <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped  dataTable">
                          <thead class="bg-primary">
                            <tr role="row">
                                    <th><?php echo e(__('common.SR.NO')); ?></th>
                                    <th>Type</th>
                                    <th><?php echo e(__('master.Title')); ?></th>
                                    <th><?php echo e(__('master.Content')); ?>  </th>
                                    
                                        <th><?php echo e(__('common.Action')); ?></th>
                                    
                             </tr>
                          </thead>
                          <tbody>
                              
                              <?php if(!empty($data)): ?>
                                <?php
                                   $i=1
                                ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $type = DB::table('message_types')->where('id',$item->message_type_id)->first()
                                    ?>
                                <tr>
                                    <td><?php echo e($i++); ?></td>
                                    <td><?php echo e($type->name ?? ''); ?></td>
                                    <td><?php echo e($item['title'] ?? ''); ?></td>
                                    <td><small><u class="text-danger">Email :</u> <span><?php echo e(Str::limit($item->email_content, 30)); ?></span> <br><u class="text-primary">SMS :</u> <?php echo e(Str::limit($item->sms_content, 30) ?? ''); ?><br><u class="text-success">Whatsapp :</u> <?php echo e(Str::limit($item->whatsapp_content, 30) ?? ''); ?></small></td>
                                    <td>
                                    
                                          <a href="<?php echo e(url('messageTemplateEdit',$item['id'])); ?>" class="btn btn-primary  btn-xs <?php echo e(Helper::permissioncheck(9)->edit ? '' : 'd-none'); ?>" title="Edit Content"><i class="fa fa-edit"></i></a>
                                   
                                        <a href="javascript:;"  data-id='<?php echo e($item->id); ?>' data-bs-toggle="modal" data-bs-target="#deleteModal"  class="deleteData btn btn-primary  btn-xs ml-3 <?php echo e(Helper::permissioncheck(9)->delete ? '' : 'd-none'); ?>" title="Delete Content"><i class="fa fa-trash-o"></i></a>
                                   
                                    </td>
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
    </section>
</div>
    
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
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


  $('.deleteData').click(function() {
  var delete_id = $(this).data('id'); 
  
  $('#delete_id').val(delete_id); 
  } );
  
</script>

<!-- The Modal -->
<div class="modal" id="deleteModal">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #555b5beb;">

      <div class="modal-header">
        <h4 class="modal-title text-white"><?php echo e(__('common.Delete Confirmation')); ?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>

      <form action="<?php echo e(url('messageTemplateDelete')); ?>" method="post">
              	 <?php echo csrf_field(); ?>
      <div class="modal-body">
              <input type=hidden id="delete_id" name=delete_id>
              <h5 class="text-white"><?php echo e(__('common.Are you sure you want to delete')); ?>  ?</h5>
      </div>
      <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal"><?php echo e(__('common.Close')); ?></button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light"><?php echo e(__('common.Delete')); ?></button>
         </div>
       </form>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>      
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/message/template/add.blade.php ENDPATH**/ ?>