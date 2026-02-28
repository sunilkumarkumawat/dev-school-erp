<?php $__env->startSection('content'); ?>

<?php echo $__env->make('attendance.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="content-wrapper attendance-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h3 class="card-title mb-0"><i class="fa fa-list"></i> &nbsp;Attendance Report</h3>
                        <div class="text-white-50">Day wise ya month wise attendance report</div>
                    </div>
                    <?php
                        $exportUrl = url('attendance/report?export=1&tab=' . ($activeTab ?? 'students') . '&report_mode=' . ($reportMode ?? 'day_wise'));
                        if (($activeTab ?? 'students') === 'staff' && !empty($roleFilter)) {
                            $exportUrl .= '&role_id=' . urlencode((string) $roleFilter);
                        }
                        if (($activeTab ?? 'students') === 'students' && !empty($classFilter)) {
                            $exportUrl .= '&class_type_id=' . urlencode((string) $classFilter);
                        }
                        $exportUrl .= '&date=' . urlencode((string) $selectedDate);
                        $exportUrl .= '&month=' . urlencode((string) $month);
                        $exportUrl .= '&year=' . urlencode((string) $year);
                    ?>
                    <a href="<?php echo e($exportUrl); ?>" class="btn btn-sm btn-outline-light"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills att-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(($activeTab ?? 'students') === 'students' ? 'active' : ''); ?>"
                               href="<?php echo e(url('attendance/report?tab=students&report_mode='.$reportMode.'&date='.$selectedDate.'&month='.$month.'&year='.$year)); ?>"><i class="fa fa-graduation-cap"></i> &nbsp;Students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(($activeTab ?? 'students') === 'staff' ? 'active' : ''); ?>"
                               href="<?php echo e(url('attendance/report?tab=staff&report_mode='.$reportMode.'&date='.$selectedDate.'&month='.$month.'&year='.$year)); ?>"><i class="fa fa-users"></i> &nbsp;Staff</a>
                        </li>
                    </ul>

                    <form method="get" action="<?php echo e(url('attendance/report')); ?>" class="mt-2">
                        <input type="hidden" name="tab" value="<?php echo e($activeTab ?? 'students'); ?>">
                        <div class="d-flex flex-wrap align-items-center att-filter-row" style="gap:10px;">
                            <?php if(($activeTab ?? 'students') === 'staff'): ?>
                                <select name="role_id" class="form-control form-control-sm select2" style="min-width:220px;">
                                    <option value="">All Roles</option>
                                    <?php $__currentLoopData = $staffRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role->id); ?>" <?php echo e((string)$roleFilter === (string)$role->id ? 'selected' : ''); ?>><?php echo e($role->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            <?php else: ?>
                                <select name="class_type_id" class="form-control form-control-sm select2" style="min-width:220px;">
                                    <option value="">All Classes</option>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>" <?php echo e((string)$classFilter === (string)$class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            <?php endif; ?>

                            <select name="report_mode" id="report_mode" class="form-control form-control-sm" style="min-width:180px;">
                                <option value="day_wise" <?php echo e(($reportMode ?? 'day_wise') === 'day_wise' ? 'selected' : ''); ?>>Day Wise</option>
                                <option value="monthly" <?php echo e(($reportMode ?? 'day_wise') === 'monthly' ? 'selected' : ''); ?>>Month Wise</option>
                            </select>

                            <div id="dayFields" class="d-flex" style="gap:10px;">
                                <input type="date" name="date" class="form-control form-control-sm" value="<?php echo e($selectedDate ?? date('Y-m-d')); ?>" style="max-width:220px;">
                            </div>

                            <div id="monthFields" class="d-flex" style="gap:10px; display:none;">
                                <select name="month" class="form-control form-control-sm att-chip">
                                    <?php for($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?php echo e($m); ?>" <?php echo e($month == $m ? 'selected' : ''); ?>><?php echo e(date('M', mktime(0,0,0,$m,1))); ?></option>
                                    <?php endfor; ?>
                                </select>

                                <select name="year" class="form-control form-control-sm att-chip">
                                    <?php for($y = date('Y')-3; $y <= date('Y')+1; $y++): ?>
                                        <option value="<?php echo e($y); ?>" <?php echo e($year == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <button class="btn btn-sm btn-primary">Load</button>
                        </div>
                    </form>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered att-table" id="reportTable">
                            <thead>
                                <tr>
                                    <th>Unique ID</th>
                                    <th>Name</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>Leave</th>
                                    <th>Late</th>
                                    <th>Early Out</th>
                                    <th>Half Day</th>
                                    <th>Holiday</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($row['unique_id']); ?></td>
                                        <td><?php echo e($row['name']); ?></td>
                                        <td><?php echo e($row['counts']['present']); ?></td>
                                        <td><?php echo e($row['counts']['absent']); ?></td>
                                        <td><?php echo e($row['counts']['leave']); ?></td>
                                        <td><?php echo e($row['counts']['late']); ?></td>
                                        <td><?php echo e($row['counts']['early_out']); ?></td>
                                        <td><?php echo e($row['counts']['halfday']); ?></td>
                                        <td><?php echo e($row['counts']['holiday']); ?></td>
                                        <td><b><?php echo e($row['counts']['total']); ?></b></td>
                                        <td>
                                            <?php if(($activeTab ?? 'students') === 'staff'): ?>
                                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(url('attendance/view?tab=staff&staff='.$row['unique_id'].'&month='.$month.'&year='.$year)); ?>" title="View Attendance">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            <?php else: ?>
                                                <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(url('attendance/view?tab=students&student='.$row['unique_id'].'&month='.$month.'&year='.$year)); ?>" title="View Attendance">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">No data found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="2">Overall Total</td>
                                    <td><?php echo e($totals['present'] ?? 0); ?></td>
                                    <td><?php echo e($totals['absent'] ?? 0); ?></td>
                                    <td><?php echo e($totals['leave'] ?? 0); ?></td>
                                    <td><?php echo e($totals['late'] ?? 0); ?></td>
                                    <td><?php echo e($totals['early_out'] ?? 0); ?></td>
                                    <td><?php echo e($totals['halfday'] ?? 0); ?></td>
                                    <td><?php echo e($totals['holiday'] ?? 0); ?></td>
                                    <td><?php echo e($totals['total'] ?? 0); ?></td>
                                    <td>-</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h5 class="mb-2">Date-wise Summary (<?php echo e(date('d/m/Y', strtotime($dateFrom))); ?> - <?php echo e(date('d/m/Y', strtotime($dateTo))); ?>)</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Present</th>
                                        <th>Absent</th>
                                        <th>Leave</th>
                                        <th>Late</th>
                                        <th>Early Out</th>
                                        <th>Half Day</th>
                                        <th>Holiday</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $dateWiseSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $counts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e(date('d/m/Y', strtotime($date))); ?></td>
                                            <td><?php echo e($counts['present']); ?></td>
                                            <td><?php echo e($counts['absent']); ?></td>
                                            <td><?php echo e($counts['leave']); ?></td>
                                            <td><?php echo e($counts['late']); ?></td>
                                            <td><?php echo e($counts['early_out']); ?></td>
                                            <td><?php echo e($counts['halfday']); ?></td>
                                            <td><?php echo e($counts['holiday']); ?></td>
                                            <td><b><?php echo e($counts['total']); ?></b></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">No summary found.</td>
                                        </tr>
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

<script>
    $(document).ready(function(){
        $('.select2').select2({ theme: 'bootstrap4', width: 'resolve' });

        function toggleModeFields() {
            var mode = $('#report_mode').val();
            $('#dayFields').toggle(mode === 'day_wise');
            $('#monthFields').toggle(mode === 'monthly');
        }

        $('#report_mode').on('change', toggleModeFields);
        toggleModeFields();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/attendance/report.blade.php ENDPATH**/ ?>