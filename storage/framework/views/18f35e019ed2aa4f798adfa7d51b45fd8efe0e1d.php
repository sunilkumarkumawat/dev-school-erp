                 
                 
                    <div class="card-body table-responsive p-0" style="height: 420px; overflow-y: scroll;">
                        <table class="table table-head-fixed table-bordered table-striped dataTable" style="margin-top:0px !important;">
                            <thead class="bg-primary">
                                <tr>
        							<th>Sr. No.</th>
        							<th>Date</th>
        							<th width="10%">Message</th>
        							<th>Assignments</th>
        							<?php if(Session::get('role_id') !== 3): ?>
        							<th>Action</th>
        							<?php endif; ?>
                                </tr>
                        </thead>
        					<tbody>
            					<?php if(!empty($data)): ?> 
            						<?php 
            						    $i=1 
            						?> 
            					<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1=>$type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $hwDocument = Helper::getHwDocument($type['id']);
                                 
                                    ?>    
                                  
            						<tr>
            						    <td id="stuName" data-first_name="<?php echo e($type['Admission']['first_name'] ?? ''); ?> <?php echo e($type['Admission']['last_name'] ?? ''); ?>"><?php echo e($i++); ?></td>
            							<td><?php echo e(date('d-m-Y', strtotime($type['submission_date'])) ?? ''); ?>

            							<?php if(Session::get('role_id') == 3): ?>
                                            <!--<?php if($type['email_status'] == 1): ?>-->
                                            <!--<small class="badge badge-success"><i class="fa fa-check"></i> Email Sent</small>-->
                                            <!--<?php else: ?>-->
                                            <!--<button type="button" id="resendEmail" class="btn btn-primary btn-xs">Resend-Email</button>-->
                                            <!--<?php endif; ?>            							-->
            							<?php endif; ?>
            							</td>
            							<td><?php echo e($type['message'] ?? ''); ?></td>
                                        <td class="row">
                                            <?php if(!empty($hwDocument)): ?> 
                                                <?php $__currentLoopData = $hwDocument; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-md-3 p-1">
                                                    <img src="<?php echo e(env('IMAGE_SHOW_PATH').'uploadHomework/'.$info->content_file); ?>" width="60px" height="60px" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'/default/user_image.jpg'); ?>'">
                                                    <span class="">
                                                    <a href="<?php echo e(url('download_assignment')); ?>/<?php echo e($info->content_file); ?>" class="btn btn-primary  btn-xs ml-3" title="Download Assignments" ><i class="fa fa-download"></i></a>
                                                    <a data-href="<?php echo e(env('IMAGE_SHOW_PATH').'uploadHomework/'.$info->content_file); ?>" data-upload_id="<?php echo e($info['upload_hw_id']); ?>"  class="viewModal_<?php echo e($info['upload_hw_id']); ?>  viewModal2 btn btn-warning btn-xs" title="View Assignment" ><i class="fa fa-eye"></i></a>     
                                                    </span>
                    									<!--<input type="text" class="form-control submit_<?php echo e($key1); ?>" id="message_<?php echo e($key); ?>" name="message" placeholder="Type Review" data-id="<?php echo e($info->id); ?>" value="<?php echo e($info->hw_review ?? ''); ?>">-->
                    							    
                    							    <?php if(Session::get('role_id') == 3): ?>
                    							    <textarea class="form-control" placeholder="Your Review By Teacher" readonly><?php echo e($info->hw_review ?? ''); ?></textarea>
                    							    <?php else: ?>
                    							    <textarea class="form-control submit_<?php echo e($key1); ?>" id="message_<?php echo e($key); ?>" name="message" placeholder="Type Review" data-id="<?php echo e($info->id); ?>" value=""><?php echo e($info->hw_review ?? ''); ?></textarea>
                    							    <?php endif; ?>
                    							    </div>                                                
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </td>
                                        <?php if(Session::get('role_id') !== 3): ?>
                                        <td class="text-right pl-3"> <button type="submit" class="btn btn-primary btn-xs submitReview " data-submit="<?php echo e($key1); ?>">Submit</button></td>
                                        <?php endif; ?>
                                   </tr>
                                
            					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
            					<?php endif; ?> 
        				    </tbody>
                        </table>
                    </div>        				
  <?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/home_work/home_work/data_homework.blade.php ENDPATH**/ ?>