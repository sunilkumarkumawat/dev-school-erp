<?php
$getUser = Helper::getUser();
$examSchedule = DB::table('examination_schedules')
    ->where('branch_id', Session::get('branch_id'))
    ->where('session_id', Session::get('session_id'))
    ->where('class_type_id', Session::get('class_type_id'))
    ->whereNull('deleted_at')
    ->groupBy('exam_id')
    ->get();

$examResultRaw = DB::table('fill_marks')
    ->where('branch_id', Session::get('branch_id'))
    ->where('session_id', Session::get('session_id'))
    ->where('admission_id', Session::get('id'))
    ->whereNull('deleted_at')
    ->get();

$examResult = $examResultRaw->groupBy('exam_id');
?>

<?php $__env->startSection('title', 'Exam Time Table & Result '); ?>
<?php $__env->startSection('page_title', 'EXAM TIME TABLE & RESULT'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
 <link rel="stylesheet" href="<?php echo e(asset('public/assets/school/css/adminlte.min.css')); ?>">   
<section class="common-page">
 <div class="common-box border-0 m-2">
        <div class="container-fluid">
            <div class="row">
                <!-- Exam Schedule -->
                <div class="col-md-12">
                    <div class="col-md-12">
                        <p><i class="fa fa-calendar"></i> Exam Schedule</p>
                    </div>
                    <?php if(count($examSchedule) > 0): ?>		
                        <div class="col-12 col-md-12 p-0 m-0">
                            <?php $__currentLoopData = $examSchedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $examss = DB::table('exams')->find($schedule->exam_id);
                                ?>
                                <div class="col-md-12 p-0 m-0">
                                    <div class="card collapsed-card">
                                        <div class="card-header border-0">
                                            <h3 class="card-title">
                                                <i class="fa fa-calendar mr-1"></i>
                                                Exam : <?php echo e($examss->name ?? ''); ?>

                                            </h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                     <div class="card-body" style="display:none;overflow-x: scroll;">
                                            <?php
                                                $i = 1;
                                                $examSchedules = DB::table('examination_schedules')
                                                    ->where('exam_id', $examss->id)
                                                    ->where('class_type_id', Session::get('class_type_id'))
                                                    ->where('branch_id', Session::get('branch_id'))
                                                    ->where('session_id', Session::get('session_id'))
                                                    ->whereNull('deleted_at')
                                                    ->orderBy('date')
                                                    ->orderBy('from_time')
                                                    ->get();
                                            ?>
                                            <?php if(count($examSchedules) > 0): ?>
                                                <table class="common-table">
                                                    <thead>
                                                        <tr>
                                                            <th>S No</th>
                                                            <th>Subject Name</th>
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $examSchedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $subjectData = DB::table('subject')
                                                                    ->whereNull('deleted_at')
                                                                    ->where('class_type_id', Session::get('class_type_id'))
                                                                    ->where('id', $exam->subject_id)
                                                                    ->first();
                                                            ?>
                                                            <tr>
                                                                <td><?php echo e($i++); ?>.</td>
                                                                <td><?php echo e($subjectData->name ?? ''); ?></td>
                                                                <td><?php echo e($exam->date ? date('d-M-Y', strtotime($exam->date)) : ''); ?></td>
                                                                <td>
                                                                    <?php if(!empty($exam->from_time)): ?>
                                                                        <?php echo e(date('h:i A', strtotime($exam->from_time))); ?> - <?php echo e(date('h:i A', strtotime($exam->to_time))); ?>

                                                                    <?php else: ?>
                                                                        School Time
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
 <div class="col-md-12">
<div class="col-md-12">
    <p><i class="fa fa-calendar"></i> Exam Result</p>
</div>

<?php if(count($examResult) > 0): ?>
    <div class="col-12 col-md-12 p-0 m-0">
        <?php $allChartData = []; ?>

        <?php $__currentLoopData = $examResult; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam_id => $resultGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $examss = DB::table('exams')->find($exam_id);
                $i = 1;
                $total_obtained_marks = 0;
                $total_max_marks = 0;
                $chartData = [];
            ?>

            <div class="col-md-12 p-0 m-0">
                <div class="card collapsed-card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fa fa-calendar mr-1"></i>
                            Exam : <?php echo e($examss->name ?? ''); ?>

                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="overflow-x: scroll;">
                        <!-- Marks Table -->
                                            <table class="common-table">
                                                <thead>
                                                    <tr style="background: #007bff; color: white;">
                                                        <th>#</th>
                                                        <th>Subject</th>
                                                        <th>Max Marks</th>
                                                        <th>Obtained</th>
                                                    </tr>
                                                </thead>
                                            
                                                <tbody>
                                                    <?php
                                                        $i = 1;
                                                        $total_max_marks = 0;
                                                        $total_obtained_marks = 0;
                                                        $chartData = [];
                                                    ?>
                                            
                                                    <?php $__currentLoopData = $resultGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam_result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $subjectData = DB::table('subject')
                                                                ->whereNull('deleted_at')
                                                                ->where('class_type_id', Session::get('class_type_id'))
                                                                ->where('id', $exam_result->subject_id)
                                                                ->first();
                                            
                                                            $max = (int)($exam_result->exam_maximum_marks ?? 0);
                                                            $obt = (int)($exam_result->student_marks ?? 0);
                                            
                                                            $total_max_marks += $max;
                                                            $total_obtained_marks += $obt;
                                            
                                                            $percent = $max > 0 ? round(($obt / $max) * 100, 2) : 0;
                                            
                                                            $chartData[] = [
                                                                'percent' => $percent,
                                                                'subject_name' => $subjectData->name ?? 'Unknown'
                                                            ];
                                                        ?>
                                            
                                                        <tr>
                                                            <td><?php echo e($i++); ?>.</td>
                                                            <td><?php echo e($subjectData->name ?? ''); ?></td>
                                                            <td><?php echo e($exam_result->exam_maximum_marks ?? ''); ?></td>
                                                            <td><?php echo e($exam_result->student_marks ?? ''); ?></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            
                                                    <?php
                                                        $percentage = $total_max_marks > 0
                                                            ? round(($total_obtained_marks / $total_max_marks) * 100, 2)
                                                            : 0;
                                                    ?>
                                            
                                                    <tr style="background: #646464; font-weight: bold; color: white;">
                                                        <td colspan="4">
                                                            <div style="display: flex; justify-content: space-around;">
                                                                <span>Rank : <?php echo e($allRanks[$exam_id] ?? '-'); ?></span>
                                                                <span>Obtained : <?php echo e($total_obtained_marks); ?></span>
                                                                <span><?php echo e($percentage); ?>%</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                        <!-- Knob Charts -->
                        <div class="row mt-3">
                            <div class="col-6 col-md-3 text-center">
                                <input type="text" class="knob"
                                       value="<?php echo e($percentage); ?>"
                                       data-width="90"
                                       data-height="90"
                                       data-fgColor="#00a65a"
                                       data-thickness="0.3"
                                       readonly style="width:100%;">
                                <div class="knob-label" style="font-weight: bold;">Overall %</div>
                            </div>
                        
                            <?php $__currentLoopData = $chartData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $chart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-md-3 text-center">
                                    <input type="text"
                                           id="knob-<?php echo e($index); ?>"
                                           class="knob knob-subject"
                                           value="<?php echo e($chart['percent']); ?>"
                                           data-width="90"
                                           data-height="90"
                                           data-fgColor="#3c8dbc"
                                           data-thickness="0.3"
                                           readonly style="width:100%;">
                                    <div class="knob-label"><?php echo e($chart['subject_name']); ?></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        
                      

                       

                        
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>


</div>

            </div>
        </div>
        </div>
  </section>
  <!-- Include scripts only once (after jQuery loaded) -->
             
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
           <script src="https://adminlte.io/themes/v3/plugins/jquery-knob/jquery.knob.min.js"></script>
                        
                        <script>
                        $(document).ready(function () {
                            // Initialize all knob inputs after page load
                            $('.knob').each(function () {
                                var $this = $(this);
                                $this.knob({
                                    draw: function () {
                                        if (this.$.data('skin') === 'tron') {
                                            var a = this.angle(this.cv),
                                                sa = this.startAngle,
                                                sat = sa,
                                                eat = sat + a;
                                            this.g.lineWidth = this.lineWidth;
                                            this.o.cursor && (sat = eat - 0.3) && (eat = eat + 0.3);
                                            this.g.beginPath();
                                            this.g.strokeStyle = this.o.fgColor;
                                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                                            this.g.stroke();
                                            return false;
                                        }
                                    }
                                });
                            });
                        });
                        </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/exams/view.blade.php ENDPATH**/ ?>