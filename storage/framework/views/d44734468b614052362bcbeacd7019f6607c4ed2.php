<?php $__env->startSection('content'); ?>

<?php echo $__env->make('attendance.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
    $typeText = $attendanceType === 1 ? 'Biometric' : ($attendanceType === 3 ? 'QR' : 'Normal');
    $today = date('Y-m-d');

    $presentCount = $marks->where('status', 'present')->count();
    $absentCount = $marks->where('status', 'absent')->count();
    $leaveCount = $marks->where('status', 'leave')->count();
    $lateCount = $marks->where('status', 'late')->count();
    $earlyOutCount = $marks->where('status', 'early_out')->count();
    $halfdayCount = $marks->where('status', 'halfday')->count();
    $holidayCount = $marks->where('status', 'holiday')->count();
    $totalMarked = $marks->count();
    $attendancePercent = $totalMarked > 0 ? round(($presentCount / $totalMarked) * 100, 1) : 0;

    $marksPayload = [];
    $calendarPayload = $calendarStatusByDate ?? [];
    $leaveCalendarPayload = $leaveCalendarByDate ?? [];
    foreach ($marks as $date => $mark) {
        $marksPayload[$date] = [
            'status' => $mark->status,
            'in_time' => $mark->in_time,
            'out_time' => $mark->out_time,
        ];
    }
?>

<div class="content-wrapper attendance-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="card card-outline card-orange self-card">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h3 class="card-title mb-0"><i class="fa fa-user"></i> &nbsp;Self Attendance (<?php echo e($typeText); ?>)</h3>
                        <div class="text-white-50">Click date to mark attendance. Leave requires admin approval.</div>
                    </div>
                    <div class="self-chip"><?php echo e($displayName); ?> (<?php echo e($uniqueId); ?>)</div>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:10px;">
                        <div class="d-flex" style="gap:8px;">
                            <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(url('attendance/self?month='.$prevMonth.'&year='.$prevYear)); ?>"><i class="fa fa-chevron-left"></i></a>
                            <div class="att-chip"><?php echo e($monthName); ?> <?php echo e($year); ?></div>
                            <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(url('attendance/self?month='.$nextMonth.'&year='.$nextYear)); ?>"><i class="fa fa-chevron-right"></i></a>
                            <button type="button" class="btn btn-sm btn-warning" id="openLeaveModal"><i class="fa fa-calendar-plus-o"></i> Apply Leave</button>
                        </div>
                    </div>

                    <div class="self-stats-grid mt-2 mb-2">
                        <div class="self-stat-card"><div class="label">Marked Days</div><div class="value" id="stat_total"><?php echo e($totalMarked); ?></div></div>
                        <div class="self-stat-card"><div class="label">Present</div><div class="value text-success" id="stat_present"><?php echo e($presentCount); ?></div></div>
                        <div class="self-stat-card"><div class="label">Absent</div><div class="value text-danger" id="stat_absent"><?php echo e($absentCount); ?></div></div>
                        <div class="self-stat-card"><div class="label">Leave</div><div class="value text-warning" id="stat_leave"><?php echo e($leaveCount); ?></div></div>
                        <div class="self-stat-card"><div class="label">Late</div><div class="value" id="stat_late"><?php echo e($lateCount); ?></div></div>
                        <div class="self-stat-card"><div class="label">Early Out</div><div class="value" id="stat_early_out"><?php echo e($earlyOutCount); ?></div></div>
                        <div class="self-stat-card"><div class="label">Halfday</div><div class="value" id="stat_halfday"><?php echo e($halfdayCount); ?></div></div>
                        <div class="self-stat-card"><div class="label">Holiday</div><div class="value" id="stat_holiday"><?php echo e($holidayCount); ?></div></div>
                        <div class="self-stat-card"><div class="label">Attendance %</div><div class="value" id="stat_percent"><?php echo e(number_format($attendancePercent, 1)); ?>%</div></div>
                    </div>

                    <div class="card mt-2 mb-2">
                        <div class="card-header py-2"><strong>My Leave Requests</strong></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $leaveRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leaveRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e(!empty($leaveRow->from_date) ? date('d/m/Y', strtotime($leaveRow->from_date)) : '-'); ?></td>
                                                <td><?php echo e(!empty($leaveRow->to_date) ? date('d/m/Y', strtotime($leaveRow->to_date)) : '-'); ?></td>
                                                <td><?php echo e($leaveRow->reason ?: '-'); ?></td>
                                                <td><span class="badge <?php echo e($leaveRow->status_class); ?>"><?php echo e($leaveRow->status_label); ?></span></td>
                                                <td>
                                                    <?php if((string) $leaveRow->status === '2'): ?>
                                                        <button type="button" class="btn btn-xs btn-outline-warning leave-action-btn" data-leave-id="<?php echo e($leaveRow->id); ?>" data-action="cancel">Cancel</button>
                                                        <button type="button" class="btn btn-xs btn-outline-danger leave-action-btn" data-leave-id="<?php echo e($leaveRow->id); ?>" data-action="delete">Delete</button>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No leave request found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="self-weekdays mt-2">
                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                    </div>

                    <div class="self-calendar-grid">
                        <?php $__currentLoopData = $calendar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $week): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $__currentLoopData = $week; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!$day): ?>
                                    <div class="self-day empty"></div>
                                <?php else: ?>
                                    <?php
                                        $mark = $marks[$day] ?? null;
                                        $calendarStatus = $calendarStatusByDate[$day] ?? '';
                                        $leaveMeta = $leaveCalendarByDate[$day] ?? null;
                                        $leaveStatus = is_array($leaveMeta) ? ($leaveMeta['status'] ?? '') : '';
                                        $isApprovedLeaveLocked = is_array($leaveMeta) ? !empty($leaveMeta['lock']) : false;
                                        $status = $mark->status ?? ($leaveStatus ?: $calendarStatus);
                                        $isFuture = $day > $today;
                                        $isBackLocked = !($canBackDateMarking ?? true) && $day < $today;
                                        $isHolidayLocked = $calendarStatus === 'holiday';
                                    ?>
                                    <div class="self-day <?php echo e($status ? 'status-'.$status : 'status-none'); ?> <?php echo e(($isFuture || $isBackLocked || $isHolidayLocked || $isApprovedLeaveLocked) ? 'disabled' : ''); ?>"
                                         data-date="<?php echo e($day); ?>"
                                         data-status="<?php echo e($status); ?>"
                                         data-in="<?php echo e($mark->in_time ?? ''); ?>"
                                         data-out="<?php echo e($mark->out_time ?? ''); ?>"
                                         data-leave-lock="<?php echo e($isApprovedLeaveLocked ? '1' : '0'); ?>"
                                         title="<?php echo e($isApprovedLeaveLocked ? 'Approved leave - attendance locked' : ($isHolidayLocked ? 'Holiday - attendance locked' : '')); ?>">
                                        <div class="self-day-head">
                                            <span class="self-day-num"><?php echo e(date('d', strtotime($day))); ?></span>
                                            <?php if($day === $today): ?>
                                                <span class="self-day-today">Today</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="self-day-status"><?php echo e($status ? ucwords(str_replace('_', ' ', $status)) : 'Not marked'); ?></div>
                                        <?php if(!empty($mark->in_time) || !empty($mark->out_time)): ?>
                                            <div class="self-day-time"><?php echo e(!empty($mark->in_time) ? date('h:i A', strtotime($mark->in_time)) : ''); ?><?php echo e(!empty($mark->out_time) ? ' - '.date('h:i A', strtotime($mark->out_time)) : ''); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="selfAttendanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="selfAttendanceForm">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="hidden" name="date" id="modalDate">
                        <input type="text" id="modalDateDisplay" class="form-control" readonly>
                    </div>

                        <div class="form-row" id="modalTimeFields" <?php if($attendanceType != 1): ?> style="display:none;" <?php endif; ?>>
                            <div class="form-group col-md-6">
                                <label>Check In</label>
                                <input type="time" name="in_time" id="modalInTime" class="form-control" step="900">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Check Out</label>
                                <input type="time" name="out_time" id="modalOutTime" class="form-control" step="900">
                            </div>
                        </div>

                    <div class="form-group mb-0">
                        <label>Status</label>
                        <select name="status" id="modalStatus" class="form-control">
                            <option value="">Select</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="early_out">Early out</option>
                            <option value="halfday">Halfday</option>
                            <option value="holiday">Holiday</option>
                        </select>
                    </div>
                </form>
                <div id="selfAttendanceMsg" class="small text-muted mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="clearSelfAttendance">Clear</button>
                <button type="button" class="btn btn-primary" id="saveSelfAttendance">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="selfLeaveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apply Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="selfLeaveForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="leave_mode" value="1">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>From Date</label>
                            <input type="date" name="from_date" id="leaveFromDate" class="form-control" <?php if(!($canBackDateMarking ?? true)): ?> min="<?php echo e(date('Y-m-d')); ?>" <?php endif; ?> required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>To Date</label>
                            <input type="date" name="to_date" id="leaveToDate" class="form-control" <?php if(!($canBackDateMarking ?? true)): ?> min="<?php echo e(date('Y-m-d')); ?>" <?php endif; ?> required>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label>Reason</label>
                        <textarea name="reason" id="leaveReason" class="form-control" rows="2" placeholder="Reason"></textarea>
                    </div>
                </form>
                <div id="selfLeaveMsg" class="small text-muted mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="saveSelfLeave">Apply Leave</button>
            </div>
        </div>
    </div>
</div>




<style>
    .self-weekdays { display:grid; grid-template-columns:repeat(7,1fr); font-size:11px; color:#64748b; margin-bottom:6px; font-weight:700; }
    .self-weekdays span { text-align:center; }
    .self-stats-grid { display:grid; grid-template-columns:repeat(9,1fr); gap:8px; }
    .self-stat-card { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:8px; }
    .self-stat-card .label { font-size:11px; color:#64748b; line-height:1.1; }
    .self-stat-card .value { font-size:16px; font-weight:700; color:#0f172a; margin-top:2px; line-height:1.1; }
    .self-calendar-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:8px; }
    .self-day { min-height:74px; border:1px solid #dbe2ea; border-radius:10px; background:#fff; padding:6px; cursor:pointer; transition:all .15s ease-in-out; }
    .self-day:hover { box-shadow:0 0 0 2px rgba(15,61,86,.15); }
    .self-day.empty { background:#f8fafc; border-style:dashed; cursor:default; }
    .self-day.disabled { opacity:.55; cursor:not-allowed; background:#f8fafc; }
    .self-day-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:4px; }
    .self-day-num { font-weight:700; font-size:12px; }
    .self-day-today { font-size:9px; border-radius:8px; padding:1px 5px; background:#0b1b2b; color:#fff; }
    .self-day-status { font-size:10px; font-weight:700; line-height:1.1; }
    .self-day-time { font-size:9px; color:#64748b; margin-top:3px; line-height:1.1; }
    .self-day.status-present { background:#dcfce7; border-color:#86efac; color:#166534; }
    .self-day.status-absent { background:#fee2e2; border-color:#fca5a5; color:#991b1b; }
    .self-day.status-leave { background:#fef3c7; border-color:#fcd34d; color:#92400e; }
    .self-day.status-leave_pending { background:#fff7ed; border-color:#fdba74; color:#9a3412; }
    .self-day.status-late { background:#ffedd5; border-color:#fdba74; color:#9a3412; }
    .self-day.status-early_out { background:#dbeafe; border-color:#93c5fd; color:#1e3a8a; }
    .self-day.status-halfday { background:#ede9fe; border-color:#c4b5fd; color:#5b21b6; }
    .self-day.status-holiday { background:#e2e8f0; border-color:#cbd5e1; color:#334155; }
    .self-day.status-event { background:#ccfbf1; border-color:#67e8f9; color:#155e75; }
    .self-day.status-none { background:#ffffff; color:#334155; }
    @media (max-width: 1200px) { .self-stats-grid { grid-template-columns:repeat(6,1fr); } }
    @media (max-width: 992px) { .self-stats-grid { grid-template-columns:repeat(3,1fr); } }
    @media (max-width: 768px) {
        .self-stats-grid { grid-template-columns:repeat(2,1fr); }
        .self-calendar-grid { grid-template-columns:repeat(2,1fr); }
        .self-weekdays { display:none; }
    }
</style>

<script>
$(function () {
    var marks = <?php echo json_encode($marksPayload, 15, 512) ?>;
    var calendarStatusMap = <?php echo json_encode($calendarPayload, 15, 512) ?>;
    var leaveStatusMap = <?php echo json_encode($leaveCalendarPayload, 15, 512) ?>;
    var attendanceType = <?php echo e((int) $attendanceType); ?>;
    var selectedCell = null;

    function statusLabel(status) {
        if (!status) return 'Not marked';
        return status.replace(/_/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });
    }

    function formatDateIndian(dateStr) {
        if (!dateStr) return '';
        var parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }

    function formatTime12(timeStr) {
        if (!timeStr) return '';
        var t = String(timeStr).trim().replace(/\s*:\s*/g, ':').replace(/\s+/g, ' ');
        var m24 = t.match(/^(\d{1,2}):(\d{2})(?::\d{2})?$/);
        if (m24) {
            var h = parseInt(m24[1], 10);
            var m = m24[2];
            var ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12;
            h = h ? h : 12;
            return String(h).padStart(2, '0') + ':' + m + ' ' + ampm;
        }
        return '';
    }

    function to24Hour(time12) {
        var value = (time12 || '').trim().toUpperCase().replace(/\s*:\s*/g, ':').replace(/\s+/g, ' ');
        if (!value) return '';

        var m24 = value.match(/^(\d{1,2}):(\d{2})(?::\d{2})?$/);
        if (m24) {
            var h24 = parseInt(m24[1], 10);
            if (h24 < 0 || h24 > 23) return null;
            return String(h24).padStart(2, '0') + ':' + m24[2];
        }

        var m12 = value.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/);
        if (!m12) return null;
        var h = parseInt(m12[1], 10);
        var min = m12[2];
        var meridian = m12[3];
        if (h < 1 || h > 12) return null;
        if (meridian === 'AM') h = h === 12 ? 0 : h;
        else h = h === 12 ? 12 : h + 12;
        return String(h).padStart(2, '0') + ':' + min;
    }

    function normalizeTimeInput($input) {
        var val = ($input.val() || '').trim().replace(/\s*:\s*/g, ':').replace(/\s+/g, ' ');
        if (!val) {
            $input.removeClass('is-invalid');
            return true;
        }
        if (($input.attr('type') || '').toLowerCase() === 'time') {
            var ok = to24Hour(val) !== null;
            $input.toggleClass('is-invalid', !ok);
            if (ok && /^\d{1,2}:\d{2}$/.test(val)) {
                var parts = val.split(':');
                $input.val(String(parseInt(parts[0],10)).padStart(2,'0') + ':' + parts[1]);
            }
            return ok;
        }
        var t = formatTime12(val);
        if (!t) {
            $input.addClass('is-invalid');
            return false;
        }
        $input.removeClass('is-invalid').val(t);
        return true;
    }

    function paintCell($cell, mark) {
        var statusClasses = 'status-present status-absent status-leave status-late status-early_out status-halfday status-holiday status-event status-none';
        var dateKey = $cell.data('date');
        var leaveMeta = leaveStatusMap[dateKey] || null;
        var leaveStatus = leaveMeta && leaveMeta.status ? leaveMeta.status : '';
        var status = mark && mark.status ? mark.status : (leaveStatus || calendarStatusMap[dateKey] || '');
        $cell.removeClass(statusClasses).addClass(status ? ('status-' + status) : 'status-none');
        $cell.attr('data-status', status);
        $cell.attr('data-in', mark && mark.in_time ? mark.in_time : '');
        $cell.attr('data-out', mark && mark.out_time ? mark.out_time : '');
        $cell.attr('data-leave-lock', leaveMeta && leaveMeta.lock ? '1' : '0');
        $cell.find('.self-day-status').text(statusLabel(status));
        var inStr = mark && mark.in_time ? formatTime12(mark.in_time) : '';
        var outStr = mark && mark.out_time ? formatTime12(mark.out_time) : '';
        var timeText = (inStr || outStr) ? (inStr + (outStr ? ' - ' + outStr : '')) : '';
        var $time = $cell.find('.self-day-time');
        if (!$time.length) {
            $time = $('<div class="self-day-time"></div>').appendTo($cell);
        }
        $time.text(timeText);
        if (!timeText) {
            $time.remove();
        }
    }

    function updateMonthlyStats() {
        var counts = { present:0, absent:0, leave:0, late:0, early_out:0, halfday:0, holiday:0, total:0 };
        Object.keys(marks).forEach(function (k) {
            var status = (marks[k] && marks[k].status) ? marks[k].status : '';
            if (!status) return;
            if (counts[status] !== undefined) counts[status]++;
            counts.total++;
        });
        var percent = counts.total > 0 ? ((counts.present / counts.total) * 100).toFixed(1) : '0.0';
        $('#stat_total').text(counts.total);
        $('#stat_present').text(counts.present);
        $('#stat_absent').text(counts.absent);
        $('#stat_leave').text(counts.leave);
        $('#stat_late').text(counts.late);
        $('#stat_early_out').text(counts.early_out);
        $('#stat_halfday').text(counts.halfday);
        $('#stat_holiday').text(counts.holiday);
        $('#stat_percent').text(percent + '%');
    }

    $('.self-day').not('.empty, .disabled').on('click', function () {
        selectedCell = $(this);
        var date = selectedCell.data('date');
        var mark = marks[date] || { status: '', in_time: '', out_time: '' };
        $('#modalDate').val(date);
        $('#modalDateDisplay').val(formatDateIndian(date));
        $('#modalStatus').val(mark.status || '');
        var inRaw = mark.in_time || '';
        var outRaw = mark.out_time || '';
        var shouldShowTimeFields = attendanceType === 1 || !!inRaw || !!outRaw;
        $('#modalTimeFields').toggle(shouldShowTimeFields);
        $('#modalInTime').val(inRaw ? String(inRaw).slice(0,5) : '');
        $('#modalOutTime').val(outRaw ? String(outRaw).slice(0,5) : '');
        $('#modalInTime, #modalOutTime').prop('readonly', attendanceType !== 1);
        $('#selfAttendanceMsg').text('');
        $('#selfAttendanceModal').modal('show');
    });

    function saveAttendance(clearMode) {
        var date = $('#modalDate').val();
        var payload = {
            _token: '<?php echo e(csrf_token()); ?>',
            date: date,
            status: clearMode ? '' : ($('#modalStatus').val() || ''),
            month: '<?php echo e($month); ?>',
            year: '<?php echo e($year); ?>'
        };

        if (attendanceType === 1) {
            var inRaw = clearMode ? '' : ($('#modalInTime').val() || '');
            var outRaw = clearMode ? '' : ($('#modalOutTime').val() || '');
            if (!clearMode && (!normalizeTimeInput($('#modalInTime')) || !normalizeTimeInput($('#modalOutTime')))) {
                $('#selfAttendanceMsg').text('Please enter valid time.');
                return;
            }
            payload.in_time = inRaw ? to24Hour(inRaw) : '';
            payload.out_time = outRaw ? to24Hour(outRaw) : '';
            if ((inRaw && !payload.in_time) || (outRaw && !payload.out_time)) {
                $('#selfAttendanceMsg').text('Please enter valid time.');
                return;
            }
        }

        $('#saveSelfAttendance, #clearSelfAttendance').prop('disabled', true);

        $.ajax({
            url: '<?php echo e(url('attendance/self')); ?>',
            type: 'POST',
            data: payload,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function (res) {
                if (res && res.ok) {
                    if (res.mark) {
                        marks[date] = { status: res.mark.status || '', in_time: res.mark.in_time || '', out_time: res.mark.out_time || '' };
                    } else {
                        delete marks[date];
                    }
                    if (selectedCell) paintCell(selectedCell, marks[date] || null);
                    updateMonthlyStats();
                    $('#selfAttendanceModal').modal('hide');
                } else {
                    $('#selfAttendanceMsg').text('Unable to save attendance.');
                }
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Unable to save attendance.';
                $('#selfAttendanceMsg').text(msg);
            },
            complete: function () {
                $('#saveSelfAttendance, #clearSelfAttendance').prop('disabled', false);
            }
        });
    }

    $('#saveSelfAttendance').on('click', function () { saveAttendance(false); });
    $('#clearSelfAttendance').on('click', function () { saveAttendance(true); });
    $(document).on('blur change', '#modalInTime, #modalOutTime', function () { normalizeTimeInput($(this)); });

    $('#openLeaveModal').on('click', function () {
        var today = new Date().toISOString().slice(0, 10);
        $('#leaveFromDate').val(today);
        $('#leaveToDate').val(today);
        $('#leaveReason').val('');
        $('#selfLeaveMsg').text('');
        $('#selfLeaveModal').modal('show');
    });

    $('#saveSelfLeave').on('click', function () {
        var fromDate = $('#leaveFromDate').val();
        var toDate = $('#leaveToDate').val();
        if (!fromDate || !toDate) {
            $('#selfLeaveMsg').text('Please select from and to date.');
            return;
        }

        $('#saveSelfLeave').prop('disabled', true);

        $.ajax({
            url: '<?php echo e(url('attendance/self')); ?>',
            type: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                leave_mode: 1,
                from_date: fromDate,
                to_date: toDate,
                reason: $('#leaveReason').val() || '',
                month: '<?php echo e($month); ?>',
                year: '<?php echo e($year); ?>'
            },
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function (res) {
                if (res && res.ok) {
                    $('#selfLeaveModal').modal('hide');
                    window.location.reload();
                } else {
                    $('#selfLeaveMsg').text('Unable to apply leave.');
                }
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Unable to apply leave.';
                $('#selfLeaveMsg').text(msg);
            },
            complete: function () {
                $('#saveSelfLeave').prop('disabled', false);
            }
        });
    });

    $(document).on('click', '.leave-action-btn', function () {
        var leaveId = $(this).data('leave-id');
        var action = $(this).data('action');
        var msg = action === 'cancel' ? 'Cancel this leave request?' : 'Delete this leave request?';
        if (!confirm(msg)) return;

        $.ajax({
            url: '<?php echo e(url('attendance/self')); ?>',
            type: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                leave_action_mode: 1,
                leave_id: leaveId,
                action: action,
                month: '<?php echo e($month); ?>',
                year: '<?php echo e($year); ?>'
            },
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function (res) {
                if (res && res.ok) {
                    window.location.reload();
                } else {
                    alert('Unable to process leave request.');
                }
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Unable to process leave request.';
                alert(msg);
            }
        });
    });

    updateMonthlyStats();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/attendance/self_calendar.blade.php ENDPATH**/ ?>