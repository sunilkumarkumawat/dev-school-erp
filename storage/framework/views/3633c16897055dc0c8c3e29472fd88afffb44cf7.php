<?php $__env->startSection('content'); ?>

<?php echo $__env->make('attendance.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="content-wrapper attendance-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <span class="att-header-badge">ATTENDANCE</span>
                        <h3 class="card-title mb-0 att-title"><i class="fa fa-list"></i> &nbsp;Normal Attendance</h3>
                    </div>
                    <form method="get" action="<?php echo e(url('attendance/mark')); ?>">
                        <div class="att-pill att-date-pill">
                            <i class="fa fa-calendar"></i>
                            <input type="hidden" name="tab" value="<?php echo e($activeTab ?? 'students'); ?>">
                            <input type="date" name="date" class="att-date-input" value="<?php echo e($selectedDate); ?>" <?php if(!($allowBackDateForUser ?? false)): ?> min="<?php echo e(date('Y-m-d')); ?>" <?php endif; ?> max="<?php echo e(date('Y-m-d')); ?>" onchange="this.form.submit()">
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <?php $holidayLock = !empty($isHolidayDate); ?>
                    <p class="att-subtext">Status-only bulk attendance. No check-in/out time required.</p>
                    <?php if($holidayLock): ?>
                        <div class="alert alert-warning py-2">Selected date is a holiday from academic calendar. Attendance marking is locked.</div>
                    <?php endif; ?>

                    <ul class="nav nav-pills att-tabs" id="attendanceTabNormal" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(($activeTab ?? 'students') === 'students' ? 'active' : ''); ?>" href="<?php echo e(url('attendance/mark?tab=students&date='.$selectedDate)); ?>"><i class="fa fa-graduation-cap"></i> &nbsp;Students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(($activeTab ?? 'students') === 'staff' ? 'active' : ''); ?>" href="<?php echo e(url('attendance/mark?tab=staff&date='.$selectedDate)); ?>"><i class="fa fa-users"></i> &nbsp;Staff</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="attendanceTabContentNormal">
                        <div class="tab-pane fade <?php echo e(($activeTab ?? 'students') === 'students' ? 'show active' : ''); ?>" id="students-normal" role="tabpanel">
                            <form method="post" action="<?php echo e(url('attendance/mark?tab=students&date='.$selectedDate.'&class_type_id='.request('class_type_id'))); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="date" value="<?php echo e($selectedDate); ?>">
                                <div class="d-flex flex-wrap align-items-center" style="gap:10px;">
                                <select id="studentClassFilterNormal" name="class_type_id" class="form-control form-control-sm select2 att-filter">
                                    <option value="">All Classes</option>
                                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_type_id') == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <input type="hidden" name="class_type_id" id="classFilterInputNormal" value="<?php echo e(request('class_type_id')); ?>">
                                    <div class="ml-auto d-flex align-items-center" style="gap:8px;">
                                        <select id="bulkStatusStudentNormal" class="form-control form-control-sm att-filter" <?php if($holidayLock): ?> disabled <?php endif; ?>>
                                            <option value="">Bulk Status</option>
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="leave">Leave</option>
                                            <option value="late">Late</option>
                                            <option value="early_out">Early out</option>
                                            <option value="halfday">Halfday</option>
                                            <option value="holiday">Holiday</option>
                                        </select>
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="applyBulkStudentNormal" <?php if($holidayLock): ?> disabled <?php endif; ?>>Apply</button>
                                        <button class="btn btn-sm btn-primary" type="submit" <?php if($holidayLock): ?> disabled <?php endif; ?>>Save Students</button>
                                    </div>
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered att-table" id="studentsTableNormal">
                                        <thead>
                                            <tr>
                                                <th>Unique ID</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $uniqueId = $student->attendance_unique_id ?? ('STU-' . $student->id);
                                                    $mark = $attendanceMarks[$uniqueId] ?? null;
                                                    $statusVal = $mark->status ?? '';
                                                ?>
                                                <tr class="student-row-normal" data-class="<?php echo e($student->class_type_id); ?>">
                                                    <td><?php echo e($uniqueId); ?></td>
                                                    <td><?php echo e(trim($student->first_name . ' ' . $student->last_name)); ?></td>
                                                    <td>
                                                        <input type="hidden" name="rows[<?php echo e($i); ?>][unique_id]" value="<?php echo e($uniqueId); ?>">
                                                        <input type="hidden" name="rows[<?php echo e($i); ?>][entity_type]" value="student">
                                                        <select class="form-control form-control-sm status-select-normal" name="rows[<?php echo e($i); ?>][status]" <?php if($holidayLock): ?> disabled <?php endif; ?>>
                                                            <option value="">Select</option>
                                                            <option value="present" <?php echo e($statusVal === 'present' ? 'selected' : ''); ?>>Present</option>
                                                            <option value="absent" <?php echo e($statusVal === 'absent' ? 'selected' : ''); ?>>Absent</option>
                                                            <option value="leave" <?php echo e($statusVal === 'leave' ? 'selected' : ''); ?>>Leave</option>
                                                            <option value="late" <?php echo e($statusVal === 'late' ? 'selected' : ''); ?>>Late</option>
                                                            <option value="early_out" <?php echo e($statusVal === 'early_out' ? 'selected' : ''); ?>>Early out</option>
                                                            <option value="halfday" <?php echo e($statusVal === 'halfday' ? 'selected' : ''); ?>>Halfday</option>
                                                            <option value="holiday" <?php echo e($statusVal === 'holiday' ? 'selected' : ''); ?>>Holiday</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <?php if(!empty($statusVal)): ?>
                                                            <span class="att-status-badge" style="background:#d1f7e6;color:#0f5132;">Marked</span>
                                                        <?php else: ?>
                                                            <span class="att-status-badge">Not marked yet</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    <div class="att-empty d-none" id="studentsEmptyNormal">No students found for selected class.</div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade <?php echo e(($activeTab ?? 'students') === 'staff' ? 'show active' : ''); ?>" id="staff-normal" role="tabpanel">
                            <form method="post" action="<?php echo e(url('attendance/mark?tab=staff&date='.$selectedDate.'&role_id='.request('role_id'))); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="date" value="<?php echo e($selectedDate); ?>">
                                <div class="d-flex flex-wrap align-items-center" style="gap:10px;">
                                <select id="staffRoleFilterNormal" name="role_id" class="form-control form-control-sm select2 att-filter">
                                    <option value="">All Roles</option>
                                    <?php $__currentLoopData = $staffRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($role->id); ?>" <?php echo e(request('role_id') == $role->id ? 'selected' : ''); ?>><?php echo e($role->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <input type="hidden" name="role_id" id="roleFilterInputNormal" value="<?php echo e(request('role_id')); ?>">
                                    <div class="ml-auto d-flex align-items-center" style="gap:8px;">
                                        <select id="bulkStatusStaffNormal" class="form-control form-control-sm att-filter" <?php if($holidayLock): ?> disabled <?php endif; ?>>
                                            <option value="">Bulk Status</option>
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="leave">Leave</option>
                                            <option value="late">Late</option>
                                            <option value="early_out">Early out</option>
                                            <option value="halfday">Halfday</option>
                                            <option value="holiday">Holiday</option>
                                        </select>
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="applyBulkStaffNormal" <?php if($holidayLock): ?> disabled <?php endif; ?>>Apply</button>
                                        <button class="btn btn-sm btn-primary" type="submit" <?php if($holidayLock): ?> disabled <?php endif; ?>>Save Staff</button>
                                    </div>
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered att-table" id="staffTableNormal">
                                        <thead>
                                            <tr>
                                                <th>Unique ID</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $uniqueId = $member->attendance_unique_id ?? ('USR-' . $member->id);
                                                    $mark = $attendanceMarks[$uniqueId] ?? null;
                                                    $statusVal = $mark->status ?? '';
                                                ?>
                                                <tr class="staff-row-normal" data-role="<?php echo e($member->role_id); ?>">
                                                    <td><?php echo e($uniqueId); ?></td>
                                                    <td><?php echo e(trim($member->first_name . ' ' . $member->last_name)); ?></td>
                                                    <td>
                                                        <input type="hidden" name="rows[<?php echo e($i); ?>][unique_id]" value="<?php echo e($uniqueId); ?>">
                                                        <input type="hidden" name="rows[<?php echo e($i); ?>][entity_type]" value="staff">
                                                        <select class="form-control form-control-sm status-select-normal" name="rows[<?php echo e($i); ?>][status]" <?php if($holidayLock): ?> disabled <?php endif; ?>>
                                                            <option value="">Select</option>
                                                            <option value="present" <?php echo e($statusVal === 'present' ? 'selected' : ''); ?>>Present</option>
                                                            <option value="absent" <?php echo e($statusVal === 'absent' ? 'selected' : ''); ?>>Absent</option>
                                                            <option value="leave" <?php echo e($statusVal === 'leave' ? 'selected' : ''); ?>>Leave</option>
                                                            <option value="late" <?php echo e($statusVal === 'late' ? 'selected' : ''); ?>>Late</option>
                                                            <option value="early_out" <?php echo e($statusVal === 'early_out' ? 'selected' : ''); ?>>Early out</option>
                                                            <option value="halfday" <?php echo e($statusVal === 'halfday' ? 'selected' : ''); ?>>Halfday</option>
                                                            <option value="holiday" <?php echo e($statusVal === 'holiday' ? 'selected' : ''); ?>>Holiday</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <?php if(!empty($statusVal)): ?>
                                                            <span class="att-status-badge" style="background:#d1f7e6;color:#0f5132;">Marked</span>
                                                        <?php else: ?>
                                                            <span class="att-status-badge">Not marked yet</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    <div class="att-empty d-none" id="staffEmptyNormal">No staff found for selected role.</div>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .status-select-normal.status-present, .att-status-badge.status-present { background: #dcfce7; color: #166534; border-color: #86efac; }
    .status-select-normal.status-absent, .att-status-badge.status-absent { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
    .status-select-normal.status-leave, .att-status-badge.status-leave { background: #fef3c7; color: #92400e; border-color: #fcd34d; }
    .status-select-normal.status-late, .att-status-badge.status-late { background: #ffedd5; color: #9a3412; border-color: #fdba74; }
    .status-select-normal.status-early_out, .att-status-badge.status-early_out { background: #dbeafe; color: #1e3a8a; border-color: #93c5fd; }
    .status-select-normal.status-halfday, .att-status-badge.status-halfday { background: #ede9fe; color: #5b21b6; border-color: #c4b5fd; }
    .status-select-normal.status-holiday, .att-status-badge.status-holiday { background: #e2e8f0; color: #334155; border-color: #cbd5e1; }
    .status-select-normal.status-none, .att-status-badge.status-none { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
</style>

<script>
    function filterRowsNormal() {
        var classId = $('#studentClassFilterNormal').val();
        var roleId = $('#staffRoleFilterNormal').val();

        var studentVisible = 0;
        $('.student-row-normal').each(function () {
            var rowClass = $(this).data('class').toString();
            var show = !classId || rowClass === classId;
            $(this).toggle(show);
            if (show) studentVisible++;
        });
        $('#studentsEmptyNormal').toggleClass('d-none', studentVisible > 0);

        var staffVisible = 0;
        $('.staff-row-normal').each(function () {
            var rowRole = $(this).data('role').toString();
            var show = !roleId || rowRole === roleId;
            $(this).toggle(show);
            if (show) staffVisible++;
        });
        $('#staffEmptyNormal').toggleClass('d-none', staffVisible > 0);
    }

    function formatStatusLabelNormal(statusValue) {
        return statusValue.replace(/_/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });
    }

    function paintStatusNormal($select) {
        var statusValue = $select.val() || '';
        var classes = 'status-present status-absent status-leave status-late status-early_out status-halfday status-holiday status-none';
        var statusClass = statusValue ? ('status-' + statusValue) : 'status-none';

        $select.removeClass(classes).addClass(statusClass);

        var $badge = $select.closest('tr').find('.att-status-badge');
        if ($badge.length) {
            $badge.attr('style', '');
            $badge.removeClass(classes).addClass(statusClass);
            $badge.text(statusValue ? formatStatusLabelNormal(statusValue) : 'Not marked yet');
        }
    }

    function applyBulkStatusNormal(targetTableId, statusValue) {
        if (!statusValue) return;
        $('#' + targetTableId + ' tbody tr:visible').find('select.status-select-normal').each(function () {
            $(this).val(statusValue);
            paintStatusNormal($(this));
        });
    }

    $(document).ready(function () {
        $('.select2').select2({ theme: 'bootstrap4', width: 'resolve' });

        function updateQueryParamNormal(key, value) {
            var url = new URL(window.location.href);
            var current = url.searchParams.get(key) || '';
            var next = value || '';
            if (current === next) return;
            if (next) {
                url.searchParams.set(key, next);
            } else {
                url.searchParams.delete(key);
            }
            window.location.href = url.toString();
        }

        $('#studentClassFilterNormal').on('change', function () {
            updateQueryParamNormal('class_type_id', $(this).val());
        });
        $('#staffRoleFilterNormal').on('change', function () {
            updateQueryParamNormal('role_id', $(this).val());
        });

        $('#applyBulkStudentNormal').on('click', function () {
            applyBulkStatusNormal('studentsTableNormal', $('#bulkStatusStudentNormal').val());
        });
        $('#applyBulkStaffNormal').on('click', function () {
            applyBulkStatusNormal('staffTableNormal', $('#bulkStatusStaffNormal').val());
        });

        $('form[method="post"]').on('submit', function (e) {
            if (<?php echo json_encode($holidayLock, 15, 512) ?>) {
                e.preventDefault();
                alert('Attendance marking is not allowed on holiday dates.');
            }
        });

        $(document).on('change', 'select.status-select-normal', function () {
            paintStatusNormal($(this));
        });

        $('select.status-select-normal').each(function () {
            paintStatusNormal($(this));
        });

        var classId = $('#classFilterInputNormal').val();
        var roleId = $('#roleFilterInputNormal').val();
        if (classId) {
            $('#studentClassFilterNormal').val(classId);
        }
        if (roleId) {
            $('#staffRoleFilterNormal').val(roleId);
        }
        filterRowsNormal();
    });
</script>
<?php $__env->stopSection(); ?>






<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/attendance/normal.blade.php ENDPATH**/ ?>