<?php
$classType = Helper::classType();

?>


<?php $__env->startSection('content'); ?>


<div class="content-wrapper students_search">
<input type="hidden" id="value">
<input type="hidden" id="value2">
    <section class="content pt-3">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-home"></i> &nbsp;Students Academic Performance</h3>
                            
                            <div class="card-tools">
                                <!--<a href="<?php echo e(url('add_user')); ?>" class="btn btn-primary  btn-sm" title="Add User"><i class="fa fa-plus"></i> Add User</a>-->
                            </div>

                        </div>
                    </div>
                </div>
            </div> 
 
         
                <div class="row">
                <div class="col-12 col-md-9">
                    
                        <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fa fa-chart-bar"></i>
               Average Subjects Score
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
                      <div class="card-body">
               
          <form id="quickForm" action="<?php echo e(url('student_performance')); ?>" method="post">
              <?php echo csrf_field(); ?>
              <div class="row ">

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="State" class="required">Ad. No.</label>
                     <input type="text" class="form-control" id="admissionNo" name="admissionNo" placeholder="Ad. No." value="<?php echo e($search['admissionNo'] ?? ''); ?>">
                  </div>
                </div>

         
           
                <div class="col-md-2">
                  <div class="form-group">
                    <label><?php echo e(__('common.Class')); ?></label>
                    <select class="form-control" id="class_type_id" name="class_type_id">
                      <option value='' ><?php echo e(__('common.Select')); ?></option>
                      <?php if(!empty($classType)): ?>
                      <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id == $search['class_type_id']) ? 'selected' : ''); ?>><?php echo e($type->name ?? ''); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </select>
                  </div>
                </div>
                
                <div class="col-md-3">
                  <div class="form-group">
                    <label><?php echo e(__('common.Search By Keywords')); ?></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo e(__('common.Ex. Name, Mobile, Email, Aadhaar etc.')); ?>" value="<?php echo e($search['name'] ?? ''); ?>">
                  </div>
                </div>
                
                <div class="col-md-1 ">
                  <label class="text-white"><?php echo e(__('common.Search')); ?></label>
                  <button type="submit" class="btn btn-primary"><?php echo e(__('common.Search')); ?></button>
                </div>
              
              </div>
            </form>
           
               
              </div>
               
                    <div class="col-12 col-md-12" style="overflow-x:scroll;">
                  
                <table id="example1" class="table table-bordered table-striped dataTable dtr-inline nowrap">
                  <thead id='main_thead'>
                    <tr role="row">
                         
                         <th>Student's Name</th>
                         <th>Class</th>
                         <th>Grade</th>
                         <th>Average Marks</th>
                         <th>Attendance</th>
                        </tr>
                        </thead>
                        <tbody>
                          
                            <?php if(!empty($students)): ?>
                              <?php
                            $total_attendance = 0;
                            $gradeArray['grade_1'] = 0 ;
                            $gradeArray['grade_2'] = 0 ;
                            $gradeArray['grade_3'] = 0 ;
                            $gradeArray['grade_4'] = 0 ;
                            $gradeArray['grade_5'] = 0 ;
                         
                            ?>
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <tr>
                                <td>
                                    <a target='_blank'href='<?php echo e(url("studentParticularPerformance")); ?>/<?php echo e($student->id); ?>' >
                                    <?php echo e($student->first_name ?? ''); ?>   <?php echo e($student->last_name ?? ''); ?> 
                                    </a>
                                </td>
                                <td>
                                     <?php echo e($student->class_name ?? ''); ?>

                                </td>
                                
                                <td>
                            
                            <?php
                            $examList = Helper::getExamsForPerformance($student->class_type_id);
                            $exam_total_maximum ='';
                            $total_obtained ='';
                            $grade = 0;
                                if(!empty($examList))
                                {
                            $exam_total_maximum = Helper::getExamMaximumForPerformance($examList,$student->class_type_id);
                            $total_obtained = Helper::getExamObtainedForPerformance($examList,$student->class_type_id,$student->id);
                          if ($exam_total_maximum != 0) {
    $grade = ($total_obtained / $exam_total_maximum) * 100;
} else {
    $grade = 0; 
}
                                }
                                
                           
                            ?>
                                    
                                    
                                    <?php if( $grade >=91 && $grade <=100 ): ?>
                                   Grade 1
                                   
                                   <?php
                                   $gradeArray['grade_1']++ ;
                                   ?>
                                <?php elseif( $grade >=81 && $grade <=90.99): ?>
                              Grade 2
                               <?php
                                   $gradeArray['grade_2']++ ;
                                   ?>
                                <?php elseif( $grade >=71 && $grade <=80.99): ?>
                                 Grade 3
                                  <?php
                                   $gradeArray['grade_3']++ ;
                                   ?>
                                <?php elseif( $grade >=61 && $grade <=70.99): ?>
                                Grade 4
                                 <?php
                                   $gradeArray['grade_4']++ ;
                                   ?>
                               <?php else: ?>
                               Grade 5
                                <?php
                                   $gradeArray['grade_5']++ ;
                                   ?>
                                    <?php endif; ?>
                                    
                                </td>
                                <td><?php echo e(number_format($grade, 2)); ?>%</td>
                                <td>
                                    
                            <?php
     
                    $attendance = Helper::getAttendancePerformance($student->id,$student->class_type_id);
                    
                        $total_attendance +=  intval(rtrim($attendance, '%'));
                        
                       
                  
                            ?>
                            
                            <?php echo e($attendance); ?>

                                </td>
                            </tr>
                            
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                           
                        </tbody>
                        
                        </table>
                  
                  </div>
                </div>
                
                
                </div>
               
         <div class='col-md-3'>
             
            <div class='row'>
                
                         <div class="col-12 col-md-12">
                 
                        <div class="info-box mb-3 text-dark">
                            <span class="info-box-icon bg-info elevation-1"><i class="fa fa-graduation-cap"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">STUDENTS COUNT</span>
                                <span class="info-box-number"><?php echo e(count($students)); ?></span>
                            </div>
                        </div>
                    
                </div>
                         <div class="col-12 col-md-12">
                 
                        <div class="info-box mb-3 text-dark">
                            <span class="info-box-icon bg-danger elevation-1"><i class="ion ion-stats-bars"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">STUDENTS ATTENDANCE</span>
                                <span class="info-box-number">
                                    <?php if(count($students) > 0): ?>
                                   <?php echo e(number_format($total_attendance/(count($students)), 2)); ?>%
                                   <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    
                </div>
                         <div class="col-12 col-md-12">
                 <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fa fa-chart-bar"></i>
              Students Count by Grade
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
                <div class="card-body">
                    <canvas id="gradeChart"></canvas>
                </div>
            </div>
                   
                    
                </div>
            </div>
    
         </div>
                  
                  
                 

        
  </div>
  </div>
  </section>
  </div>
              
               <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
              
               <script>
        const ctx = document.getElementById('gradeChart').getContext('2d');
        
        
        var grade1 = "<?php echo e($gradeArray['grade_1']); ?>";
        var grade2 = "<?php echo e($gradeArray['grade_2']); ?>";
        var grade3 = "<?php echo e($gradeArray['grade_3']); ?>";
        var grade4 = "<?php echo e($gradeArray['grade_4']); ?>";
        var grade5 = "<?php echo e($gradeArray['grade_5']); ?>";
        const gradeChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Grade 1','Grade 2', 'Grade 3','Grade 4', 'Grade 5'],
                datasets: [{
                    label: 'Students',
                    data: [grade1,grade2,grade3,grade4,grade5], // Number of students in each grade
                    backgroundColor: ['FFA07A', '#FFD700','#87CEFA', '#32CD32','#FFB6C1'],
                }]
            },    
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            },
        });
    </script>
            
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/students/academic_performance/student_performance.blade.php ENDPATH**/ ?>