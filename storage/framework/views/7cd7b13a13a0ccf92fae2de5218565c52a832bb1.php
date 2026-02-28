<?php
$noticeBoard = Helper::noticeBoard();
$getstudentbirthday = Helper::getstudentbirthday();
$chartAttendanceStudents = Helper::chartAttendanceStudents();
$chartAttendanceStudentsClassWise = Helper::chartAttendanceStudentsClassWise();
$getremark = Helper::getremark();
$roleName = DB::table('role')->whereNull('deleted_at')->find(Session::get('role_id'));
$data = Helper::getMonthWiseFeeCollection();
$data1 = Helper::getWeeklyWiseFeeCollection();
$data2 = Helper::getYearWiseFeeCollection();
?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('dashboard.setup-lock', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<style>
     @media (max-width: 768px) {
         #classWiseGroupedChart{
             height:300px !important;
             max-height:300px !important;
         }
     }
</style>
<div class="content-wrapper students_search">

    <section class="content pt-3">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-home"></i> &nbsp;<?php echo e($roleName->name ?? ''); ?> Dashboard</h3>
                            
                            <div class="card-tools">
                                <!--<a href="<?php echo e(url('add_user')); ?>" class="btn btn-primary  btn-sm" title="Add User"><i class="fa fa-plus"></i> Add User</a>-->
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php
                    $active = \App\Models\Admission::countActiveAdmission()->count();
                    $capacity = Session::get('student_count'); 
                    $occupancy = $capacity > 0 ? round(($active / $capacity) * 100, 2) : 0;
                ?>
            <div class="col-md-6 col-lg-4 mb-4">
                          <div class="card-custom">
                            <div class="row g-2 align-items-center mb-2">
                              <div class="col-auto">
                                <div class="icon-box" style="background-color:#4fa5e94d;">
                                      <img src="<?php echo e(env('IMAGE_SHOW_PATH') . '/icons/student2.png'); ?>" alt="bg_logo" width="100%">
                                  <!--<i class="fa fa-users" aria-hidden="true"></i>-->
                                </div>
                              </div>
                              <div class="col">
                                   <a href="<?php echo e(url('admissionView')); ?>" class="small-box-footer">
                                <div class="title">Total Students Enrolled</div>
                                   </a>
                                <div class="subtitle">कुल छात्र संख्या</div>
                              </div>
                            </div> 
                            <div class="total-count"><?php echo e(App\Models\Admission::countActiveAdmission()->count()); ?></div>
                            <div class="row label-row">
                              <div class="col">Male Students:</div>
                              <div class="col text-right fw-bold"><?php echo e(\App\Models\Admission::countActiveAdmission()->where('gender_id', 1)->count()); ?></div>
                            </div>
                            <div class="row label-row">
                              <div class="col">Female Students:</div>
                              <div class="col text-right fw-bold"><?php echo e(\App\Models\Admission::countActiveAdmission()->where('gender_id', 2)->count()); ?></div>
                            </div>
                            <div class="row label-row">
                              <div class="col">Occupancy Rate:</div>
                              <div class="col text-right occupancy"><?php echo e($occupancy); ?>%</div>
                            </div>
                          </div>
                        </div>
                    
                   <?php
                       
                        $baseQuery = \App\Models\StudentAttendance::countPresentStudents();
                    
                        // Counts
                        $present = (clone $baseQuery)->where('attendance_status_id', 1)->count();
                        $absent = (clone $baseQuery)->where('attendance_status_id', 2)->count();
                        $presentAbsent = $present+$absent;
                       $remaningstudent = App\Models\Admission::countActiveAdmission()->count()-$presentAbsent;
                    ?>
                    
                    <div class="col-md-6 col-lg-4 mb-4">
                      <div class="card-custom">
                        <div class="row g-2 align-items-center mb-2">
                          <div class="col-auto">
                            <div class="icon-box" style="background-color:#378b4e2e">
                              <img src="<?php echo e(env('IMAGE_SHOW_PATH') . '/icons/attendence2.png'); ?>" alt="bg_logo" width="100%">
                            </div>
                          </div>
                          <div class="col">
                            <a href="<?php echo e(url('studentsAttendanceView')); ?>" class="small-box-footer">
                              <div class="title">Today's Attendance</div>
                            </a>
                            <div class="subtitle">आज की उपस्थिति</div>
                          </div>
                        </div>
                    
                        <div class="total-count text-success"><?php echo e(\App\Models\StudentAttendance::countPresentStudents()->count()); ?></div>
                    
                        <div class="row label-row">
                          <div class="col">Present Students :</div>
                          <div class="col text-right fw-bold text-success"><?php echo e($present); ?></div>
                        </div>
                    
                        <div class="row label-row">
                          <div class="col">Absent Students:</div>
                          <div class="col text-right fw-bold text-warning"><?php echo e($absent); ?></div>
                        </div>
                    
                        <div class="row label-row">
                          <div class="col"> Remaining (No Entry):</div>
                          <div class="col text-right occupancy text-danger"><?php echo e($remaningstudent); ?></div>
                        </div>
                      </div>
                    </div>
                    
                    <?php
                        use App\Models\fees\FeesAssignDetail;
                        use App\Models\FeesDetail;
                    
                        // Total Fee Assigned (expected to be collected)
                        $totalAssigned = FeesAssignDetail::Collection(); // already built method
                    
                        // Total Fee Collected
                        $totalCollected = FeesDetail::totalCollection(); // already built method
                    
                        // Fee Pending
                        $totalPending = $totalAssigned - $totalCollected;
                    
                        // Fee Collection Percentage
                        $collectionPercent = $totalAssigned > 0 ? round(($totalCollected / $totalAssigned) * 100, 2) : 0;
                    ?>
                    
                    <div class="col-md-6 col-lg-4 mb-4">
                      <div class="card-custom">
                        <div class="row g-2 align-items-center mb-2">
                          <div class="col-auto">
                            <div class="icon-box" style="background-color:#4fa5e94d;">
                              <img src="<?php echo e(env('IMAGE_SHOW_PATH') . '/icons/fees2.png'); ?>" alt="bg_logo" width="100%">
                            </div>
                          </div>
                          <div class="col">
                            <a href="<?php echo e(url('studentsAttendanceView')); ?>" class="small-box-footer">
                              <div class="title">Fee Collection</div>
                            </a>
                            <div class="subtitle">शुल्क की क्राय</div>
                          </div>
                        </div>
                    
                        <div class="total-count">₹ <?php echo e(number_format($totalAssigned, 2)); ?></div>
                    
                        <div class="row label-row">
                          <div class="col">Paid:</div>
                          <div class="col text-right fw-bold text-success">₹ <?php echo e(number_format($totalCollected, 2)); ?></div>
                        </div>
                    
                        <div class="row label-row">
                          <div class="col">Pending:</div>
                          <div class="col text-right fw-bold text-warning">₹ <?php echo e(number_format($totalPending, 2)); ?></div>
                        </div>
                    
                        <div class="row label-row">
                          <div class="col">Status:</div>
                          <div class="col text-right occupancy">
                            <span class="status-badge <?php echo e($collectionPercent >= 80 ? 'bg-success' : ($collectionPercent >= 50 ? 'bg-warning' : 'bg-danger')); ?> text-white">
                              <?php echo e($collectionPercent); ?>% Collected
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php
                 
                
                    $todayCollection = FeesDetail::todayCollection();
                    $totalAssigned = FeesAssignDetail::Collection();
                    $collectionPercent = $totalAssigned > 0 ? round(($todayCollection / $totalAssigned) * 100, 2) : 0;
                ?>
                
                <div class="col-md-6 col-lg-4 mb-4">
                  <form id="todayCollectionForm" action="<?php echo e(url('fees/index')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="starting" value="<?php echo e(date('Y-m-d')); ?>">
                    <input type="hidden" name="ending" value="<?php echo e(date('Y-m-d')); ?>">
                
                    <div class="card-custom" onclick="document.getElementById('todayCollectionForm').submit();">
                      <div class="row g-2 align-items-center mb-2">
                        <div class="col-auto">
                          <div class="icon-box" style="background-color:#d1ecf1;">
                            <img src="<?php echo e(env('IMAGE_SHOW_PATH') . '/icons/fees2.png'); ?>" alt="bg_logo" width="100%">
                          </div>
                        </div>
                        <div class="col">
                          <div class="title">Today's Collection</div>
                          <div class="subtitle">आज की शुल्क प्राप्ति</div>
                        </div>
                      </div>
                
                      <div class="total-count text-primary">₹ <?php echo e(number_format($todayCollection, 2)); ?></div>
                
                      <div class="row label-row">
                        <div class="col">Percent of Total Fee:</div>
                        <div class="col text-right fw-bold text-info">
                          <?php echo e($collectionPercent); ?>%
                        </div>
                      </div>
                
                      <div class="row label-row">
                        <div class="col">Status:</div>
                        <div class="col text-right occupancy">
                          <span class="status-badge <?php echo e($collectionPercent >= 80 ? 'bg-success' : ($collectionPercent >= 50 ? 'bg-warning' : 'bg-danger')); ?> text-white">
                            <?php echo e($collectionPercent); ?>% Collected Today
                          </span>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                
                <?php
                            use App\Models\Expense;
                            use Carbon\Carbon;
                        
                            $todayExpense = Expense::todayExpense();
                            $monthExpense = Expense::thisMonthExpense();
                            $totalExpense = Expense::totalExpense();
                        ?>
                        
                        <div class="col-md-6 col-lg-4 mb-4">
                          <form id="expenseForm" action="<?php echo e(url('expenseView')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="starting" value="<?php echo e(Carbon::now()->startOfMonth()->format('Y-m-d')); ?>">
                            <input type="hidden" name="ending" value="<?php echo e(Carbon::now()->endOfMonth()->format('Y-m-d')); ?>">
                        
                            <div class="card-custom" onclick="document.getElementById('expenseForm').submit();">
                              <div class="row g-2 align-items-center mb-2">
                                <div class="col-auto">
                                  <div class="icon-box" style="background-color:#f8d7da;">
                                    <img src="<?php echo e(env('IMAGE_SHOW_PATH') . '/icons/expense.png'); ?>" alt="expense_icon" width="100%">
                                  </div>
                                </div>
                                <div class="col">
                                  <div class="title">Expense Summary</div>
                                  <div class="subtitle">खर्च का सारांश</div>
                                </div>
                              </div>
                        
                              <div class="total-count text-danger">₹ <?php echo e(number_format($totalExpense, 2)); ?></div>
                        
                              <div class="row label-row">
                                <div class="col">Today's Expense:</div>
                                <div class="col text-right fw-bold text-danger">₹ <?php echo e(number_format($todayExpense, 2)); ?></div>
                              </div>
                        
                              <div class="row label-row">
                                <div class="col">This Month:</div>
                                <div class="col text-right fw-bold text-warning">₹ <?php echo e(number_format($monthExpense, 2)); ?></div>
                              </div>
                        
                             
                            </div>
                          </form>
                        </div>
                            
                            
                        <div class="col-md-6 col-lg-4 mb-4">
                          <div class="card-custom p-3" style="max-height: 185px;">
                            <div class="row g-2 align-items-center mb-2">
                              <div class="col-auto">
                                <div class="icon-box" style="background-color:#ffdfdf;">
                                  <img src="<?php echo e(env('IMAGE_SHOW_PATH') . '/icons/birthday.png'); ?>" alt="birthday_icon" width="100%">
                                </div>
                              </div>
                              <div class="col">
                                <div class="title">🎂 Birthday Today</div>
                                <div class="subtitle">आज जन्मदिन है</div>
                              </div>
                            </div>
                      
                            <?php if($getstudentbirthday->count() > 0): ?>
                              <div style="max-height: 100px; overflow-y: auto; padding-right: 5px;">
                                      <marquee direction="up" scrollamount="4" id="test" onmouseover="this.stop();" onmouseout="this.start();">
                                <ul class="todo-list ui-sortable" data-widget="todo-list">
                                  <?php $__currentLoopData = $getstudentbirthday; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="list-group-item py-1 px-2 d-flex justify-content-between align-items-center">
                                      <span>🎉 <?php echo e($student->first_name); ?> <?php echo e($student->last_name ?? ''); ?>  •  <?php echo e($student->class_name ?? ''); ?></span>
                                      <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($student->dob)->age); ?> yrs</small>
                                    </li>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                                </marquee>
                              </div>
                            <?php else: ?>
                              <div class="d-flex justify-content-center align-items-center" style="height: 130px;">
                                <span class="text-muted">🎈 कोई जन्मदिन नहीं आज</span>
                              </div>
                            <?php endif; ?>
                          </div>
                        </div>
                       <?php
                        $userCount = \App\Models\User::where('branch_id',Session::get('branch_id'))->where('status', 1)->count();
                        ?>
                    
                    <div class="col-md-6 col-lg-4 mb-4">
                      <div class="card-custom">
                        <div class="row g-2 align-items-center " style="margin-bottom: 40px;">
                          <div class="col-auto">
                            <div class="icon-box" style="background-color:#d1ecf1;">
                              <img src="<?php echo e(env('IMAGE_SHOW_PATH') . '/icons/warden2.png'); ?>" alt="user_icon" width="100%">
                            </div>
                          </div>
                          <div class="col">
                            <a href="<?php echo e(url('viewUser')); ?>" class="small-box-footer">
                              <div class="title">Users On Duty</div>
                            </a>
                            <div class="subtitle">ड्यूटी पर मौजूद यूज़र्स</div>
                          </div>
                        </div>
                    
                        <div class="total-count text-info"><?php echo e($userCount); ?></div>
                    
                       
                    
                        <div class="row label-row">
                          <div class="col">On Duty Now:</div>
                          <div class="col text-right fw-bold text-success">0</div>
                        </div>
                    
                        <div class="row label-row">
                          <div class="col">Off Duty / Leave:</div>
                          <div class="col text-right fw-bold text-warning"><?php echo e(0 ?? ''); ?></div>
                        </div>
                      </div>
                    </div>

                         <?php
                            // Existing summary
                            $complaints = \App\Models\Master\Complaint::countComplaint();
                            $total = $complaints->count();
                            $resolved = $complaints->whereNotNull('admin_action')->count();
                            $pending = $complaints->whereNull('admin_action')->count();
                            $percent = $total > 0 ? round(($resolved / $total) * 100) : 0;
                            
                            // Status color logic
                            $statusColor = 'bg-secondary';
                            if ($percent >= 80) {
                                $statusColor = 'bg-success'; // Green
                            } elseif ($percent >= 50) {
                                $statusColor = 'bg-warning'; // Yellow
                            } else {
                                $statusColor = 'bg-danger'; // Red
                            }
                            ?>

                        
                        <div class="col-md-6 col-lg-4 mb-4">
                          <div class="card-custom">
                            <div class="row g-2 align-items-center mb-2">
                              <div class="col-auto">
                                <div class="icon-box" style="background-color:#f9ea0126;">
                                  <img src="<?php echo e(env('IMAGE_SHOW_PATH') . '/icons/complaint2.png'); ?>" alt="bg_logo" width="100%">
                                </div>
                              </div>
                              <div class="col">
                                <a href="<?php echo e(url('complaint_view')); ?>" class="small-box-footer">
                                  <div class="title"> Students Complaints</div>
                                </a>
                                <div class="subtitle">छात्र शिकायतें</div>
                              </div>
                            </div>
                        
                            <div class="total-count text-danger"><?php echo e($total); ?></div>
                        
                            <div class="row label-row">
                              <div class="col">Resolved:</div>
                              <div class="col text-right fw-bold text-success"><?php echo e($resolved); ?></div>
                            </div>
                        
                            <div class="row label-row">
                              <div class="col">Pending:</div>
                              <div class="col text-right fw-bold text-warning"><?php echo e($pending); ?></div>
                            </div>
                        
                            <div class="row label-row">
                              <div class="col">Status:</div>
                              <div class="col text-right occupancy">
                                <span class="status-badge text-white <?php echo e($statusColor); ?>"><?php echo e($percent); ?>% Resolved</span>
                             </div>
                          </div>
                        </div>
            </div>
          
                 </div>
       
 
    <div class="row">

                   
                          

                     <?php if(count($getremark) > 0): ?>  
                     
                  <div class="col-md-4">
                     <div class="card card-danger" >
                    <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-bell"> <?php echo e(__('Student Remark Notification')); ?></i> </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                     
                            <marquee direction="up" scrollamount="4" id="student" onMouseOver="document.all.student.stop()"
                                onMouseOut="document.all.student.start()">
                                <ul class="todo-list ui-sortable" data-widget="todo-list">
                                    <?php if(!empty($getremark)): ?>
                              
                                    <?php $__currentLoopData = $getremark; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 
                                    <li class="">
                                        <a href="<?php echo e(url('students/index')); ?>">
                                            <span class="text text-dark"><?php echo e($item->remark ?? ''); ?></span>
                                            <small class="badge badge-danger"><i class="fa fa-envelope-o"></i>
                                                New</small>
                                        </a>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </ul>
                            </marquee>
                        </div>

                    </div>
              
            </div>
                
                <?php endif; ?>
                
                
                 
                
                
                       <?php if(count($noticeBoard) > 0): ?> 

                   
                  
                        
                         <div class="col-md-4">
                     <div class="card card-warning">
                    <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-bell"> <?php echo e(__('Notifications ')); ?></i> </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                         <div class="card-body">
                            <marquee direction="up" scrollamount="4" id="newnotic" onMouseOver="document.all.newnotic.stop()"
                                onMouseOut="document.all.newnotic.start()">
                                <ul class="todo-list ui-sortable" data-widget="todo-list">
                                   <?php if(!empty($noticeBoard)): ?>
                                    <?php $__currentLoopData = $noticeBoard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 
                                    <li class="">
                                      <a href="<?php echo e(url('notice_board/viewid')); ?>/<?php echo e($item->id); ?>">
                                           <span class="text text-dark"> <?php echo html_entity_decode($item->message ?? '', ENT_QUOTES, 'UTF-8'); ?> <?php echo html_entity_decode($item->title ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                            <small class="badge badge-danger"><i class="fa fa-envelope-o"></i>
                                                New</small>
                                        </a>
                                    </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </ul>
                            </marquee>
                        </div>

                    </div>
              
            </div>
            <?php endif; ?>
           

                    
                    <div class="col-md-12">
                    <div class="card">
                        <div class="card-header ui-sortable-handle flex_items_mb">
                            <h3 class="card-title_mb">
                                <i class="ion ion-clipboard mr-1"></i>
                                <?php echo e(__('dashboard.To Do List')); ?>

                            </h3>
                            <div class="card-tools">
                                <div class="row">
                                    
                                    <div class="col-md-12 col-12 text-center">
                                        <a href='<?php echo e(url("to_do_assign")); ?>' style="margin-top:-10px"><button type="button" class="btn btn-primary"><i
                                                class="fa fa-plus"></i> <?php echo e(__('Add/View')); ?></button></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                         
                        <div class="card-body">
                           
                            <ul class="todo-list ui-sortable todoList" data-widget="todo-list">
                              
                            </ul>
                        </div>
                        
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="card">
                        <div class="card-header ui-sortable-handle flex_items_mb">
                            <h3 class="card-title_mb  collection">
                              <i class="fa fa-money mr-1" aria-hidden="true"></i>

                                <?php echo e(__('Monthly Fee Collection')); ?>

                            </h3>
                           
                            <button id="yearly" class='btn btn-primary btn-xs'>Yearly</button>
                            <button id="monthly" class='btn btn-primary btn-xs' >Monthly</button>
                            <button id="7_days" class='btn btn-primary btn-xs' ><?php echo e(date('d')); ?> Days</button>
                        </div>
                          
                        <div class="card-body " id="chart-container">
                           
                             <canvas class='bg-white'id="myChart"></canvas>
                              
                            </ul>
                        </div>
                        
                    </div>
                </div>
                
                            <?php

                    $feesData = App\Models\FeesDetail::selectRaw('MONTH(date) as month, SUM(total_amount) as total')
                        ->where('session_id', Session::get('session_id'))
                        ->where('branch_id', Session::get('branch_id'))
                        ->whereIn('status',[0,1])
                        ->whereNull('deleted_at')
                        ->groupBy('month')
                        ->orderBy('month')
                        ->pluck('total', 'month');
                    
                    $expenseData = App\Models\Expense::selectRaw('MONTH(date) as month, SUM(amount) as total')
                        ->where('branch_id', Session::get('branch_id'))
                        ->where('session_id', Session::get('session_id'))
                        ->groupBy('month')
                        ->orderBy('month')
                        ->pluck('total', 'month');
                    
                    // Define all months
                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    
                    $feesArray = array_fill(0, 12, 0);
                    $expenseArray = array_fill(0, 12, 0);
                    
                    foreach ($feesData as $month => $total) {
                        $feesArray[$month - 1] = $total;
                    }
                    
                    foreach ($expenseData as $month => $total) {
                        $expenseArray[$month - 1] = $total;
                    }
                    ?>
                    
                    <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Income Or Expense</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="IncomeExpense" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                    </div>


              
                <div class="col-md-6" id="calendarElement">

                </div>
                
          

       
                <div class="col-md-12">
                    <div class="card card-dark ">
                        <div class="card-header">
                            <h3 class="card-title"> <?php echo e(__('Class-wise Attendance (Grouped)')); ?> </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                        <div style="overflow-x:auto;">
                                <canvas id="classWiseGroupedChart" style="min-height: 250px; height: 400px; max-height: 400px; max-width: 100%; display: block; width: 487px;"></canvas>
                        </div>
                        </div>

                    </div>
                </div> 
                <div class="col-md-6">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title"> <?php echo e(__('dashboard.Student Attendance Chart')); ?> </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chartjs-size-monitor">
                                <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                </div>
                            </div>
                            <canvas id="donutChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 487px;"
                                class="chartjs-render-monitor" width="487" height="250"></canvas>
                        </div>
                    </div>
                   

                   


              


       </div>

            

            </div>

        </div>
    </section>

</div>

<style>
     .round-overlap {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            color: #d81b60;
        }
</style>
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    var labels = <?php echo json_encode(array_keys($chartAttendanceStudentsClassWise)); ?>;

    var datasets = [
        {
            label: 'Present',
            data: <?php echo json_encode(array_column($chartAttendanceStudentsClassWise, 'in')); ?>,
            backgroundColor: '#00a65a'
        },
        {
            label: 'Absent',
            data: <?php echo json_encode(array_column($chartAttendanceStudentsClassWise, 'Absent')); ?>,
            backgroundColor: '#f56954'
        },
        {
            label: 'Holiday',
            data: <?php echo json_encode(array_column($chartAttendanceStudentsClassWise, 'Holiday')); ?>,
            backgroundColor: '#f39c12'
        },
   
        {
            label: 'Event',
            data: <?php echo json_encode(array_column($chartAttendanceStudentsClassWise, 'Event')); ?>,
            backgroundColor: '#5a6268'
        },
        {
            label: 'Exam',
            data: <?php echo json_encode(array_column($chartAttendanceStudentsClassWise, 'Exam')); ?>,
            backgroundColor: '#23272b'
        }
    ];

   var ctx = document.getElementById('classWiseGroupedChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: { labels: labels, datasets: datasets },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Class-wise Attendance (Grouped)' },
                datalabels: {
                    anchor: 'end',
                    align: 'end',   
                    offset: 0,     
                    color: '#000',
                    display: function(context) {
                        return window.innerWidth >= 768;
                    },
                    formatter: function(value) {
                        return value === 0 ? '' : value; 
                    }
                }
            },
            scales: {
                x: { stacked: false },
                y: { stacked: false }
            }
        },
        plugins: [ChartDataLabels] 
    });
</script>

<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fullcalendar/main.css">
<script src="https://adminlte.io/themes/v3/plugins/fullcalendar/main.js"></script>

<script>
//------------
//Income Vs Expense 
//-------
    $(document).ready(function () {
        var ChartData = {
            labels: <?php echo json_encode($months); ?>,
            datasets: [
                {
                    label: 'Fees Collected',
                    backgroundColor: 'rgba(0, 128, 0, 0.7)', // Semi-transparent green
                    borderColor: 'rgba(0, 128, 0, 1)', // Solid green border
                    data: <?php echo json_encode($feesArray); ?>
                },
                {
                    label: 'Expenses',
                    backgroundColor: 'rgba(255, 0, 0, 0.7)', // Semi-transparent red
                    borderColor: 'rgba(255, 0, 0, 1)', // Solid red border
                    data: <?php echo json_encode($expenseArray); ?>
                },
            ]
        };

        // Ensure canvas exists before initializing chart
        var barChartCanvas = document.getElementById("IncomeExpense").getContext("2d");

        var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        new Chart(barChartCanvas, {
            type: 'bar',
            data: ChartData,
            options: barChartOptions
        });
    });
    
 //---------
 //Income Vs Expense end
 //---------

  $('.fees-collection-info').click(function(){
         $('#fees_info_modal').modal('toggle');   
      });

   $(window).on("load", function(){
        tableviewajax()
			
			  });
      function tableviewajax() {
     $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
		            $.ajax({
                     	url:'/task_list',
					type:'post',
				  data: {
                status: "1",
               
            },
               
                success: function(result) {
                   // var result = JSON.parse(result);
                
                    if (result) {

                      //  toastr.success(result.msg);
                        	$('.todoList').html(result)
                        //	alert("done");
                      
                    } else {
                       $('.todoList').html("<p class='text-center'><img style='width:184px' src='<?php echo e(env('IMAGE_SHOW_PATH') ?? ''); ?>/default/Dashboard/task-task-icon-155379995.webp'></p>");

                    }
                }
            })
      }
      
    $(document).on('click', ".add_task", function () {
        var task = $('#task').val();
        var data = { 'task': task }
        if(task=="")
        {
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
          $("#task").css("margin-left","0px")
          }, 20);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid black")
           $("#task").css("margin-left","3px")
          }, 40);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
           $("#task").css("margin-left","0px")
          }, 60);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid black")
           $("#task").css("margin-left","3px")
          }, 80);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
           $("#task").css("margin-left","0px")
          }, 100);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid black")
           $("#task").css("margin-left","3px")
          }, 120);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
           $("#task").css("margin-left","0px")
          }, 140);
             
        }
        else{
        
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $.ajax({
            type: "POST",
            url: "/add/task",
            data: data,
            dataType: "html",
            success: function (response) {
                toastr.success('Task Added Successfully.');
                  tableviewajax()
                  $("#task").val("");
                     $("#task").css("border-bottom","1px solid black");
            },
        });
        }
    });

    $(document).on('click', ".task_status", function () {
        var id = $(this).data('id');
        var status = $(this).data('status');
        $.ajax({
            url: '/status/task',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                status: status,
                id: id
            },
            success: function () {
                toastr.success('Record Saved Successfully.');
               
            },
        });
    });

    $(document).on('click', ".task_delete", function () {
        var task_id = $(this).data('id');
        var data = { 'task_id': task_id }
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $.ajax({
            type: "POST",
            url: "/delete/task",
            data: data,
            dataType: "html",
            success: function (response) {
                $("#task_li").remove();
                toastr.error('Task Deleted Successfully.');
              tableviewajax()
            },
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(function () {
        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

      

        var areaChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false,
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display: false,
                    }
                }]
            }
        }

        // This will get the first returned node in the jQuery collection.
        new Chart(areaChartCanvas, {
            type: 'line',
            data: areaChartData,
            options: areaChartOptions
        })

        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
        var lineChartOptions = $.extend(true, {}, areaChartOptions)
        var lineChartData = $.extend(true, {}, areaChartData)
        lineChartData.datasets[0].fill = false;
        lineChartData.datasets[1].fill = false;
        lineChartOptions.datasetFill = false

        var lineChart = new Chart(lineChartCanvas, {
            type: 'line',
            data: lineChartData,
            options: lineChartOptions
        })

        //-------------
        //- DONUT CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var donutChartCanvas = $('#donutChart').get(0).getContext('2d');
        var donutData = {
            labels: [
                'in',
                'Absent',
                'Holiday',
                'Event',
                'Exam',
               
            ],
            datasets: [
                {
                    data: [ 
                        <?php echo e($chartAttendanceStudents['in']); ?>,
                        <?php echo e($chartAttendanceStudents['Absent']); ?>,
                        <?php echo e($chartAttendanceStudents['Holiday']); ?>,
                        <?php echo e($chartAttendanceStudents['Event']); ?>,
                        <?php echo e($chartAttendanceStudents['Exam']); ?>

                    ],

 backgroundColor: ['#00a65a', '#f56954', '#f39c12', '#002c54', '#5a6268', '#23272b'], // Correct placement
 
                   
                }
            ]
        }
        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(donutChartCanvas, {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        })


       
       
  

       

        
    })
</script>

<script>
$(window).on("load", function () {

    // Function to load task list
    function tableviewajax() {
        // Set up CSRF token for Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Optional: Show loading indicator
        $('.todoList').html('<p>Loading...</p>');

        // Make AJAX request
        $.ajax({
            url: '/task_list',
            type: 'POST',
            data: {
                status: "1"
            },
            success: function (result) {
                if (result) {
                    $('.todoList').html(result); // Inject response HTML
                } else {
                    $('.todoList').html('<p>No tasks found.</p>');
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                $('.todoList').html('<p style="color:red;">Failed to load tasks. Please try again.</p>');
            }
        });
    }

    // Call function on load
    tableviewajax();

});
</script>





<script>
$(document).ready(function() {
    // Load the monthly fee collection data on page load
    chartData(<?php echo json_encode($data, 15, 512) ?>);

    // When "7 days" button is clicked, switch to the weekly fee collection
    $('#7_days').click(function() {
        $('#myChart').remove(); // Remove the existing canvas
        $('#chart-container').append('<canvas id="myChart"></canvas>'); // Append a new canvas
        chartData(<?php echo json_encode($data1, 15, 512) ?>); // Load weekly data
        $('.collection').html(`
            <i class="fa fa-money mr-1" aria-hidden="true"></i>
            Weekly Fee Collection
        `);
    });

    // When "Monthly" button is clicked, switch back to the monthly fee collection
    $('#monthly').on('click', function() {
        $('#myChart').remove(); // Remove the existing canvas
        $('#chart-container').append('<canvas id="myChart"></canvas>'); // Append a new canvas
        chartData(<?php echo json_encode($data, 15, 512) ?>); // Load monthly data
        $('.collection').html(`
            <i class="fa fa-money mr-1" aria-hidden="true"></i>
            Monthly Fee Collection
        `);
    });

    // When "Yearly" button is clicked, switch to the yearly fee collection
    $('#yearly').on('click', function() {
        $('#myChart').remove(); // Remove the existing canvas
        $('#chart-container').append('<canvas id="myChart"></canvas>'); // Append a new canvas
        chartData(<?php echo json_encode($data2, 15, 512) ?>); // Load yearly data
        $('.collection').html(`
            <i class="fa fa-money mr-1" aria-hidden="true"></i>
            Yearly Fee Collection
        `);
    });

    // Function to load data into the chart
    function chartData(val) {
        var val1 = val['val1']; // Labels (Months, Days, or Years)
        var val2 = val['val2']; // Data (Fee Collection Amounts)

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar', // Chart type can be changed to 'line', 'pie', etc.
            data: {
                labels: val1, // X-axis labels
                datasets: [{
                    label: '',
                    data: val2, // Y-axis data
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // Start Y-axis from 0
                    }
                }
            }
        });
    }
});
</script>
  

<script>

   $(window).on("load", function(){
        tableviewajax()
			
			  });
      function tableviewajax() {
     $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
		            $.ajax({
                     	url:'/task_list',
					type:'post',
				  data: {
                status: "1",
               
            },
               
                success: function(result) {
                   // var result = JSON.parse(result);
                
                    if (result) {

                      //  toastr.success(result.msg);
                        	$('.todoList').html(result)
                        //	alert("done");
                      
                    } else {
                       $('.todoList').html("<p class='text-center'><img style='width:184px' src='<?php echo e(env('IMAGE_SHOW_PATH') ?? ''); ?>/default/Dashboard/task-task-icon-155379995.webp'></p>");

                    }
                }
            })
      }
      
    $(document).on('click', ".add_task", function () {
        var task = $('#task').val();
        var data = { 'task': task }
        if(task=="")
        {
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
          $("#task").css("margin-left","0px")
          }, 20);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid black")
           $("#task").css("margin-left","3px")
          }, 40);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
           $("#task").css("margin-left","0px")
          }, 60);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid black")
           $("#task").css("margin-left","3px")
          }, 80);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
           $("#task").css("margin-left","0px")
          }, 100);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid black")
           $("#task").css("margin-left","3px")
          }, 120);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
           $("#task").css("margin-left","0px")
          }, 140);
             
        }
        else{
        
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $.ajax({
            type: "POST",
            url: "/add/task",
            data: data,
            dataType: "html",
            success: function (response) {
                toastr.success('Task Added Successfully.');
                  tableviewajax()
                  $("#task").val("");
                     $("#task").css("border-bottom","1px solid black");
            },
        });
        }
    });

    $(document).on('click', ".task_status", function () {
        var id = $(this).data('id');
        var status = $(this).data('status');
        $.ajax({
            url: '/status/task',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                status: status,
                id: id
            },
            success: function () {
                toastr.success('Record Saved Successfully.');
               
            },
        });
    });

    $(document).on('click', ".task_delete", function () {
        var task_id = $(this).data('id');
        var data = { 'task_id': task_id }
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $.ajax({
            type: "POST",
            url: "/delete/task",
            data: data,
            dataType: "html",
            success: function (response) {
                $("#task_li").remove();
                toastr.error('Task Deleted Successfully.');
              tableviewajax()
            },
        });
    });
</script>
<script>
    $(window).on("load", function(){
        
      function tableviewajax() {
     $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
		            $.ajax({
                     	url:'/task_list',
					type:'post',
				  data: {
                status: "1",
               
            },
               
                success: function(result) {
                   // var result = JSON.parse(result);
                
                    if (result) {

                      //  toastr.success(result.msg);
                        	$('.todoList').html(result)
                        //	alert("done");
                      
                    } else {
                       // toastr.error(result.msg);
                    }
                }
            })
      }
      tableviewajax()
			
			  });
			  
			  
			  
</script>

<script>
$( document ).ready(function() {
    
 
    
 $(window).on("load", function(){
     
                $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "/calender_view",
                dataType: "json",
         success:function(response){ 
              
              $.each(response.data,function (key, item){
                  var myArray = [
                      "bg-danger",
                      "bg-warning",
                      "bg-primary",
                      "bg-info",
                      "bg-success",
                        ];
                        
                        var randomItem = myArray[Math.floor(Math.random()*myArray.length)];
      var value = $.trim(""+item.date);
          $(".fc-daygrid-day").each(function() {
    
            var data=  $(this).data("date");
           
            if(data == value)
            {
                $(this).find(".fc-daygrid-day-events").append("<div class='mt-1 fc-event-title fc-sticky "+randomItem+"'>"+item.message+"</div>");
                
            }
            })
          
            })
    }
            });
 });

 
});
</script>




<script>

 $(document).ready(function() {
     chartData(<?php echo json_encode($data, 15, 512) ?>);
 $('#7_days').click(function() {
        $('#myChart').remove(); // Remove the existing canvas
        $('#chart-container').append('<canvas id="myChart"></canvas>'); // Append a new canvas
        chartData(<?php echo json_encode($data1, 15, 512) ?>);
        $('.collection').html(`
            <i class="fa fa-money mr-1" aria-hidden="true"></i>
            Weekly Fee Collection
        `);
    });

    $('#monthly').on('click', function() {
        $('#myChart').remove(); // Remove the existing canvas
        $('#chart-container').append('<canvas id="myChart"></canvas>'); // Append a new canvas
        chartData(<?php echo json_encode($data, 15, 512) ?>);
        $('.collection').html(`
            <i class="fa fa-money mr-1" aria-hidden="true"></i>
            Monthly Fee Collection
        `);
    });
   


function chartData(val){
  var val1 = val['val1'];
  var val2 = val['val2'];
  
    // var total = monthlyData.reduce((sum, value) => sum + value, 0);
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // Example type, can be 'line', 'pie', 'doughnut', etc.
        data: {
           labels: val1,
            datasets: [{
                label: '',
                data: val2,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
 });

</script>


<style>
    .font_bottom{
        margin-bottom: 10px;
        font-family: inherit;
        font-size: inherit !important;
        line-height: inherit;
    }
    .font_count{
        font-size: 37px;
        font-weight: 600;
        font-family: sans-serif;
    }
    
    
    .card-title span {
      font-size: 0.8rem;
      color: #6c757d;
    }
    .status-badge {
      display: inline-block;
      padding: 0.2rem 0.6rem;
      border-radius: 0.5rem;
      font-size: 0.75rem;
    }
    .card-custom {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 15px;
      
    }
    .icon-box {
      background-color: #e0edff;
      border-radius: 12px;
      padding: 7px;
      width: 48px;
      height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
    }
    .title {
      font-weight: bold;
      font-size: 1.2rem;
      color:#212529;
      transition:1s;
      font-family: initial;
    }
    .dashboard-title{
        font-family: initial;
        font-size: 1.8rem;
    }
    .subtitle {
      font-size: 0.85rem;
      color: #666;
       transition:1s;
    }
    .total-count {
      font-size: 22px;
      color: #1a73e8;
     
    }
    .label-row {
      padding: 4px 0;
      border-top: 1px solid #eee;
    }
    .occupancy {
      color: green;
      font-weight: bold;
    }
    .icon-box img{
        transition:1s;
    }
   .card-custom:hover .icon-box img {
    transform: scale(1.2);
    transition: transform 1s;
}
 .card-custom:hover .title,.card-custom:hover .subtitle{
     transform: translateX(5px);
     transition: transform 1s;
 }

 @media  screen and (max-width: 600px){
     .dashboard-title{
         font-size: 1.4rem;
     }
     .dashboard-title img{
         width:25px;
     }
 }
 @media  screen and (max-width: 350px){
     .dashboard-title{
         font-size: 1.1rem;
     }
     .dashboard-title img{
         width:20px;
     }
 }
  .list-group-flush::-webkit-scrollbar {
    width: 5px;
  }
  .list-group-flush::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 5px;
  }

</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/dashboard/admin_dashboard.blade.php ENDPATH**/ ?>