
<?php $__env->startSection('content'); ?>

<?php echo $__env->make('attendance.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style>
    .att-summary .small-box {
        border-radius: 14px;
        overflow: hidden;
    }
    .att-legend span {
        margin-right: 12px;
        font-size: 12px;
        color: #6c757d;
    }
    .att-legend .dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .att-calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
    }
    .att-day {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        min-height: 66px;
        padding: 8px;
        background: #fff;
        position: relative;
        cursor: pointer;
    }
    .att-day.empty {
        background: #f8f9fa;
        border-style: dashed;
        cursor: default;
    }
    .att-day .date {
        font-size: 13px;
        font-weight: 600;
    }
    .att-day .time {
        font-size: 11px;
        color: #6c757d;
    }
    .att-day .status-dot {
        position: absolute;
        right: 10px;
        top: 10px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .att-day.active {
        border-color: #0b1b2b;
        box-shadow: 0 0 0 2px rgba(11, 27, 43, 0.15);
        background: #eef5ff;
    }
    .att-side-card {
        border-radius: 14px;
        background: #fff;
        border: 1px solid var(--att-border);
        padding: 16px;
        box-shadow: var(--att-shadow-soft);
    }
    .yearly-card {
        border-radius: 14px;
        background: #fff;
        border: 1px solid var(--att-border);
        padding: 16px;
        color: #111827;
        box-shadow: var(--att-shadow-soft);
    }
    .yearly-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
    }
    .month-tile {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px;
        min-height: 120px;
        position: relative;
    }
    .month-title {
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 8px;
        color: #111827;
    }
    .dot-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
        opacity: 1;
        margin-bottom: 8px;
    }
    .dot-grid span {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #d1d5db;
        display: inline-block;
    }
    .dot-grid span.empty {
        background: transparent;
    }
    .dot-grid .status-present { background: #22c55e; }
    .dot-grid .status-absent { background: #ef4444; }
    .dot-grid .status-leave { background: #f59e0b; }
    .dot-grid .status-late { background: #f97316; }
    .dot-grid .status-early_out { background: #3b82f6; }
    .dot-grid .status-halfday { background: #8b5cf6; }
    .dot-grid .status-holiday { background: #94a3b8; }
    .dot-grid .status-event { background: #06b6d4; }
    .mini-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        font-size: 11px;
        color: #6b7280;
    }
    .mini-legend .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 4px;
    }
    @media (max-width: 1200px) {
        .yearly-grid { grid-template-columns: repeat(4, 1fr); }
    }
    @media (max-width: 768px) {
        .yearly-grid { grid-template-columns: repeat(2, 1fr); }
    }
    .status-present { background: #22c55e; }
    .status-absent { background: #ef4444; }
    .status-leave { background: #f59e0b; }
    .status-late { background: #f97316; }
    .status-early_out { background: #3b82f6; }
    .status-halfday { background: #8b5cf6; }
    .status-holiday { background: #94a3b8; }
    .status-event { background: #06b6d4; }
</style>


<div class="content-wrapper attendance-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h3 class="card-title mb-0"><i class="fa fa-history"></i> &nbsp;Attendance History</h3>
                        <div class="text-white-50">Academic Year <?php echo e(date('Y')); ?>-<?php echo e(date('Y') + 1); ?></div>
                    </div>
                    <div class="d-flex" style="gap:8px;">
                        <?php
                            $exportUrl = url('attendance/view?tab=' . ($activeTab ?? 'students') . '&month=' . $month . '&year=' . $year . '&export=1');
                            if (($activeTab ?? 'students') === 'staff') {
                                $exportUrl .= '&staff=' . urlencode((string) $selectedUniqueId);
                            } else {
                                $exportUrl .= '&student=' . urlencode((string) $selectedUniqueId);
                            }
                        ?>
                        <a class="btn btn-sm btn-outline-light" href="<?php echo e($exportUrl); ?>"><i class="fa fa-download"></i> Export Report</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row att-summary">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <p>Total Working Days</p>
                                    <h3><?php echo e($totalDays); ?></h3>
                                </div>
                                <div class="icon"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <p>Days Present</p>
                                    <h3><?php echo e($presentDays); ?></h3>
                                </div>
                                <div class="icon"><i class="fa fa-check"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <p>Days Absent</p>
                                    <h3><?php echo e($absentDays); ?></h3>
                                </div>
                                <div class="icon"><i class="fa fa-times"></i></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <p>Attendance %</p>
                                    <h3><?php echo e(number_format($attendancePercent, 1)); ?>%</h3>
                                </div>
                                <div class="icon"><i class="fa fa-percent"></i></div>
                            </div>
                        </div>
                    </div>

                    <ul class="nav nav-pills att-tabs mt-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(($activeTab ?? 'students') === 'students' ? 'active' : ''); ?>" href="<?php echo e(url('attendance/view?tab=students&month='.$month.'&year='.$year)); ?>"><i class="fa fa-graduation-cap"></i> &nbsp;Students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(($activeTab ?? 'students') === 'staff' ? 'active' : ''); ?>" href="<?php echo e(url('attendance/view?tab=staff&month='.$month.'&year='.$year)); ?>"><i class="fa fa-users"></i> &nbsp;Staff</a>
                        </li>
                    </ul>

                    <form method="get" action="<?php echo e(url('attendance/view')); ?>" class="mt-2">
                        <input type="hidden" name="tab" value="<?php echo e($activeTab ?? 'students'); ?>">
                        <div class="att-filter-row">
                            <div class="row g-2 align-items-center">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <?php if(($activeTab ?? 'students') === 'staff'): ?>
                                        <select name="staff" class="form-control form-control-sm select2">
                                            <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $uid = $member->attendance_unique_id ?? ('USR-' . $member->id); ?>
                                                <option value="<?php echo e($uid); ?>" <?php echo e($selectedUniqueId === $uid ? 'selected' : ''); ?>>
                                                    <?php echo e(trim($member->first_name . ' ' . $member->last_name)); ?> (<?php echo e($uid); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    <?php else: ?>
                                        <select name="student" class="form-control form-control-sm select2">
                                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $uid = $stu->attendance_unique_id ?? ('STU-' . $stu->id);
                                                ?>
                                                <option value="<?php echo e($uid); ?>" <?php echo e($selectedUniqueId === $uid ? 'selected' : ''); ?>>
                                                    <?php echo e(trim($stu->first_name . ' ' . $stu->last_name)); ?> (<?php echo e($uid); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    <?php endif; ?>
                                </div>
                                <div class="col-6 col-md-3 col-lg-2">
                                    <select name="month" class="form-control form-control-sm att-chip">
                                        <?php for($m = 1; $m <= 12; $m++): ?>
                                            <option value="<?php echo e($m); ?>" <?php echo e($month == $m ? 'selected' : ''); ?>><?php echo e(date('M', mktime(0,0,0,$m,1))); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-6 col-md-3 col-lg-2">
                                    <select name="year" class="form-control form-control-sm att-chip">
                                        <?php for($y = date('Y')-3; $y <= date('Y')+1; $y++): ?>
                                            <option value="<?php echo e($y); ?>" <?php echo e($year == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-6 col-md-3 col-lg-2">
                                    <button class="btn btn-sm btn-primary w-100">Load</button>
                                </div>
                                <div class="col-6 col-md-3 col-lg-2">
                                    <?php
                                        $attendanceType = $setting->attendance_type ?? 2;
                                        $typeLabel = $attendanceType == 1 ? 'Biometric' : ($attendanceType == 3 ? 'Qr' : 'Normal');
                                    ?>
                                    <div class="att-chip w-100 text-center text-uppercase">Attendance Type: <?php echo e($typeLabel); ?></div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row mt-4">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0"><?php echo e($monthName); ?> <?php echo e($year); ?></h4>
                                    <div class="att-legend">
                                        <span><span class="dot status-present"></span>Present (<?php echo e($presentDays); ?>)</span>
                                        <span><span class="dot status-late"></span>Late (<?php echo e($lateDays); ?>)</span>
                                        <span><span class="dot status-early_out"></span>Early Out (<?php echo e($earlyOutDays); ?>)</span>
                                        <span><span class="dot status-halfday"></span>Half Day (<?php echo e($halfDayDays); ?>)</span>
                                        <span><span class="dot status-absent"></span>Absent (<?php echo e($absentDays); ?>)</span>
                                        <span><span class="dot status-holiday"></span>Holiday (<?php echo e($holidayDays); ?>)</span>
                                        <span><span class="dot status-leave"></span>Leave (<?php echo e($leaveDays); ?>)</span>
                                        <span><span class="dot status-event"></span>Event</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex text-muted mb-2" style="justify-content:space-between;">
                                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                                    </div>
                                    <div class="att-calendar">
                                        <?php $__currentLoopData = $calendar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $week): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $__currentLoopData = $week; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(!$day): ?>
                                                    <div class="att-day empty"></div>
                                                <?php else: ?>
                                                    <?php
                                                        $mark = $marksByDate[$day] ?? null;
                                                        $status = $mark->status ?? ($calendarMonthMap[$day] ?? '');
                                                        $dotClass = $status ? 'status-' . $status : '';
                                                        $inTimeRaw = $mark->in_time ?? '';
                                                        $outTimeRaw = $mark->out_time ?? '';
                                                        $inTimeDisplay = $inTimeRaw ? date('h:i A', strtotime($inTimeRaw)) : '';
                                                        $outTimeDisplay = $outTimeRaw ? date('h:i A', strtotime($outTimeRaw)) : '';
                                                    ?>
                                                    <div class="att-day" data-date="<?php echo e($day); ?>" data-status="<?php echo e($status); ?>" data-in="<?php echo e($inTimeRaw); ?>" data-out="<?php echo e($outTimeRaw); ?>">
                                                        <div class="date"><?php echo e(date('d', strtotime($day))); ?></div>
                                                        <?php if(($setting->attendance_type ?? 2) == 1): ?>
                                                            <div class="time"><?php echo e($inTimeDisplay); ?> <?php echo e($outTimeDisplay ? ' - '.$outTimeDisplay : ''); ?></div>
                                                        <?php else: ?>
                                                            <div class="time"></div>
                                                        <?php endif; ?>
                                                        <?php if($dotClass): ?>
                                                            <span class="status-dot <?php echo e($dotClass); ?>"></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="att-side-card" id="selectedDayCard">
                                <h5>Selected Day</h5>
                                <div class="badge badge-secondary" id="selectedStatus">No Data</div>
                                <div class="mt-2 text-muted">Reason</div>
                                <div id="selectedReason">Select a date to view details.</div>
                                <?php if(($setting->attendance_type ?? 2) == 1): ?>
                                    <div class="mt-3 text-muted">Check In / Out</div>
                                    <div id="selectedTime">-</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Yearly Overview (Jan - Dec)</h5>
                            <div class="mini-legend">
                                <span><span class="dot status-present"></span>Present</span>
                                <span><span class="dot status-late"></span>Late</span>
                                <span><span class="dot status-early_out"></span>Early Out</span>
                                <span><span class="dot status-halfday"></span>Half Day</span>
                                <span><span class="dot status-absent"></span>Absent</span>
                                <span><span class="dot status-holiday"></span>Holiday</span>
                                <span><span class="dot status-leave"></span>Leave</span>
                                <span><span class="dot status-event"></span>Event</span>
                            </div>
                        </div>
                        <div class="card-body yearly-card">
                            <div class="yearly-grid">
                                <?php $__currentLoopData = $yearlyOverview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="month-tile">
                                        <div class="month-title"><?php echo e($item['label']); ?> <?php echo e($item['year']); ?></div>
                                        <div class="dot-grid">
                                            <?php $__currentLoopData = $item['grid']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($cell === null): ?>
                                                    <span class="empty"></span>
                                                <?php elseif($cell === ''): ?>
                                                    <span></span>
                                                <?php else: ?>
                                                    <span class="status-<?php echo e($cell); ?>"></span>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <div class="mini-legend">
                                            <span><span class="dot status-present"></span><?php echo e($item['counts']['present']); ?></span>
                                            <span><span class="dot status-late"></span><?php echo e($item['counts']['late']); ?></span>
                                            <span><span class="dot status-early_out"></span><?php echo e($item['counts']['early_out']); ?></span>
                                            <span><span class="dot status-halfday"></span><?php echo e($item['counts']['halfday']); ?></span>
                                            <span><span class="dot status-absent"></span><?php echo e($item['counts']['absent']); ?></span>
                                            <span><span class="dot status-holiday"></span><?php echo e($item['counts']['holiday']); ?></span>
                                            <span><span class="dot status-leave"></span><?php echo e($item['counts']['leave']); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
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

        function formatDateIndian(dateStr) {
            if (!dateStr) return '';
            var parts = dateStr.split('-');
            if (parts.length !== 3) return dateStr;
            return parts[2] + '/' + parts[1] + '/' + parts[0];
        }

        function formatTime12(timeStr) {
            if (!timeStr) return '';
            var parts = timeStr.split(':');
            if (parts.length < 2) return timeStr;
            var h = parseInt(parts[0], 10);
            var m = parts[1];
            var ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12;
            h = h ? h : 12;
            return h + ':' + m + ' ' + ampm;
        }

        $('.att-day').on('click', function(){
            $('.att-day').removeClass('active');
            $(this).addClass('active');
            var date = $(this).data('date');
            var rawStatus = $(this).data('status') || 'No Data';
            var status = rawStatus;
            if (rawStatus && rawStatus !== 'No Data') {
                status = rawStatus.replace(/_/g, ' ');
                status = status.replace(/\b\w/g, function (c) { return c.toUpperCase(); });
            }
            var inTime = $(this).data('in') || '';
            var outTime = $(this).data('out') || '';

            $('#selectedStatus').text(status);
            if (status === 'No Data' || status === '') {
                $('#selectedStatus').removeClass().addClass('badge badge-secondary');
            } else {
                $('#selectedStatus').removeClass().addClass('badge badge-info');
            }
            $('#selectedReason').text(date ? ('Date: ' + formatDateIndian(date)) : 'Select a date to view details.');
            <?php if(($setting->attendance_type ?? 2) == 1): ?>
                var inDisplay = formatTime12(inTime);
                var outDisplay = formatTime12(outTime);
                $('#selectedTime').text((inDisplay || outDisplay) ? (inDisplay + (outDisplay ? ' - ' + outDisplay : '')) : '-');
            <?php endif; ?>
        });
    });
</script>
<?php $__env->stopSection(); ?>








<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/attendance/view.blade.php ENDPATH**/ ?>