@extends('layout.app')
@section('content')

@include('attendance.theme')

<div class="content-wrapper attendance-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center" style="gap:10px;">
                        <span class="att-header-badge">ATTENDANCE</span>
                        <h3 class="card-title mb-0 att-title"><i class="fa fa-check-square-o"></i> &nbsp;Biometric Attendance</h3>
                    </div>
                    <form method="get" action="{{ url('attendance/mark') }}">
                        <div class="att-pill att-date-pill">
                            <i class="fa fa-calendar"></i>
                            <input type="hidden" name="tab" value="{{ $activeTab ?? 'students' }}">
                            <input type="date" name="date" class="att-date-input" value="{{ $selectedDate }}" @if(!($allowBackDateForUser ?? false)) min="{{ date('Y-m-d') }}" @endif max="{{ date('Y-m-d') }}" onchange="this.form.submit()">
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @php $holidayLock = !empty($isHolidayDate); @endphp
                    <p class="att-subtext">Check-in/out with bulk status control. Students and staff are separated.</p>
                    @if($holidayLock)
                        <div class="alert alert-warning py-2">Selected date is a holiday from academic calendar. Attendance marking is locked.</div>
                    @endif

                    <ul class="nav nav-pills att-tabs" id="attendanceTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ ($activeTab ?? 'students') === 'students' ? 'active' : '' }}" href="{{ url('attendance/mark?tab=students&date='.$selectedDate) }}"><i class="fa fa-graduation-cap"></i> &nbsp;Students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($activeTab ?? 'students') === 'staff' ? 'active' : '' }}" href="{{ url('attendance/mark?tab=staff&date='.$selectedDate) }}"><i class="fa fa-users"></i> &nbsp;Staff</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="attendanceTabContent">
                        <div class="tab-pane fade {{ ($activeTab ?? 'students') === 'students' ? 'show active' : '' }}" id="students" role="tabpanel">
                            <form method="post" action="{{ url('attendance/mark?tab=students&date='.$selectedDate.'&class_type_id='.request('class_type_id')) }}">
                                @csrf
                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                                <div class="d-flex flex-wrap align-items-center" style="gap:10px;">
                                <select id="studentClassFilter" name="class_type_id" class="form-control form-control-sm select2 att-filter" style="width:220px;">
                                        <option value="">All Classes</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ request('class_type_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="class_type_id" id="classFilterInput" value="{{ request('class_type_id') }}">
                                <div class="ml-auto d-flex align-items-center att-actions" style="gap:10px;">
                                    <div class="att-field">
                                        <label>Bulk Check In</label>
                                        <input type="time" id="bulkInStudent" class="form-control form-control-sm att-time att-time-input" step="60" @if($holidayLock) disabled @endif>
                                    </div>
                                    <div class="att-field">
                                        <label>Bulk Check Out</label>
                                        <input type="time" id="bulkOutStudent" class="form-control form-control-sm att-time att-time-input" step="60" @if($holidayLock) disabled @endif>
                                    </div>
                                    <div class="att-field">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="applyBulkTimeStudent" @if($holidayLock) disabled @endif>Apply Time</button>
                                    </div>
                                    <div class="att-field">
                                        <label>Bulk Status</label>
                                        <select id="bulkStatusStudent" class="form-control form-control-sm att-filter" @if($holidayLock) disabled @endif>
                                        <option value="">Bulk Status</option>
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="leave">Leave</option>
                                        <option value="late">Late</option>
                                        <option value="early_out">Early out</option>
                                        <option value="halfday">Halfday</option>
                                        <option value="holiday">Holiday</option>
                                        </select>
                                    </div>
                                    <div class="att-field">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="applyBulkStudent" @if($holidayLock) disabled @endif>Apply Status</button>
                                    </div>
                                    <div class="att-field">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-sm btn-primary" type="submit" @if($holidayLock) disabled @endif>Save Students</button>
                                    </div>
                                </div>
                            </div>

                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered att-table" id="studentsTable">
                                        <thead>
                                            <tr>
                                                <th>Unique ID</th>
                                                <th>Name</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                            <th>Status</th>
                                            <th></th>
                                            <th>Reset</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($students as $i => $student)
                                                @php
                                                    $uniqueId = $student->attendance_unique_id ?? ('STU-' . $student->id);
                                                    $mark = $attendanceMarks[$uniqueId] ?? null;
                                                    $inTime = $mark->in_time ?? null;
                                                    $outTime = $mark->out_time ?? null;
                                                    $statusVal = $mark->status ?? '';
                                                @endphp
                                                <tr class="student-row" data-class="{{ $student->class_type_id }}">
                                                    <td>{{ $uniqueId }}</td>
                                                    <td>{{ trim($student->first_name . ' ' . $student->last_name) }}</td>
                                                    <td>
                                                        <input type="time" class="form-control form-control-sm att-time-input" name="rows[{{ $i }}][in_time]" value="{{ $inTime ? date('H:i', strtotime($inTime)) : '' }}" step="60" @if($holidayLock) disabled @endif>
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control form-control-sm att-time-input" name="rows[{{ $i }}][out_time]" value="{{ $outTime ? date('H:i', strtotime($outTime)) : '' }}" step="60" @if($holidayLock) disabled @endif>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="rows[{{ $i }}][unique_id]" value="{{ $uniqueId }}">
                                                        <input type="hidden" name="rows[{{ $i }}][entity_type]" value="student">
                                                        <select class="form-control form-control-sm status-select" name="rows[{{ $i }}][status]" @if($holidayLock) disabled @endif>
                                                            <option value="">Select</option>
                                                            <option value="present" {{ $statusVal === 'present' ? 'selected' : '' }}>Present</option>
                                                            <option value="absent" {{ $statusVal === 'absent' ? 'selected' : '' }}>Absent</option>
                                                            <option value="leave" {{ $statusVal === 'leave' ? 'selected' : '' }}>Leave</option>
                                                            <option value="late" {{ $statusVal === 'late' ? 'selected' : '' }}>Late</option>
                                                            <option value="early_out" {{ $statusVal === 'early_out' ? 'selected' : '' }}>Early out</option>
                                                            <option value="halfday" {{ $statusVal === 'halfday' ? 'selected' : '' }}>Halfday</option>
                                                            <option value="holiday" {{ $statusVal === 'holiday' ? 'selected' : '' }}>Holiday</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        @if(!empty($statusVal) || !empty($inTime) || !empty($outTime))
                                                            <span class="att-status-badge" style="background:#d1f7e6;color:#0f5132;">Marked</span>
                                                        @else
                                                            <span class="att-status-badge">Not marked yet</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-danger reset-row" @if($holidayLock) disabled @endif>Reset</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="att-empty d-none" id="studentsEmpty">No students found for selected class.</div>
                                </div>
                    </form>
                        </div>

                        <div class="tab-pane fade {{ ($activeTab ?? 'students') === 'staff' ? 'show active' : '' }}" id="staff" role="tabpanel">
                            <form method="post" action="{{ url('attendance/mark?tab=staff&date='.$selectedDate.'&role_id='.request('role_id')) }}">
                                @csrf
                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                                <div class="d-flex flex-wrap align-items-center" style="gap:10px;">
                                <select id="staffRoleFilter" name="role_id" class="form-control form-control-sm select2 att-filter" style="width:220px;">
                                        <option value="">All Roles</option>
                                        @foreach($staffRoles as $role)
                                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="role_id" id="roleFilterInput" value="{{ request('role_id') }}">
                                <div class="ml-auto d-flex align-items-center att-actions" style="gap:10px;">
                                    <div class="att-field">
                                        <label>Bulk Check In</label>
                                        <input type="time" id="bulkInStaff" class="form-control form-control-sm att-time att-time-input" step="60" @if($holidayLock) disabled @endif>
                                    </div>
                                    <div class="att-field">
                                        <label>Bulk Check Out</label>
                                        <input type="time" id="bulkOutStaff" class="form-control form-control-sm att-time att-time-input" step="60" @if($holidayLock) disabled @endif>
                                    </div>
                                    <div class="att-field">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="applyBulkTimeStaff" @if($holidayLock) disabled @endif>Apply Time</button>
                                    </div>
                                    <div class="att-field">
                                        <label>Bulk Status</label>
                                        <select id="bulkStatusStaff" class="form-control form-control-sm att-filter" @if($holidayLock) disabled @endif>
                                        <option value="">Bulk Status</option>
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="leave">Leave</option>
                                        <option value="late">Late</option>
                                        <option value="early_out">Early out</option>
                                        <option value="halfday">Halfday</option>
                                        <option value="holiday">Holiday</option>
                                        </select>
                                    </div>
                                    <div class="att-field">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="applyBulkStaff" @if($holidayLock) disabled @endif>Apply Status</button>
                                    </div>
                                    <div class="att-field">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-sm btn-primary" type="submit" @if($holidayLock) disabled @endif>Save Staff</button>
                                    </div>
                                </div>
                            </div>

                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered att-table" id="staffTable">
                                        <thead>
                                            <tr>
                                                <th>Unique ID</th>
                                                <th>Name</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                            <th>Status</th>
                                            <th></th>
                                            <th>Reset</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($staff as $i => $member)
                                                @php
                                                    $uniqueId = $member->attendance_unique_id ?? ('USR-' . $member->id);
                                                    $mark = $attendanceMarks[$uniqueId] ?? null;
                                                    $staffIn = $mark->in_time ?? null;
                                                    $staffOut = $mark->out_time ?? null;
                                                    $statusVal = $mark->status ?? '';
                                                @endphp
                                                <tr class="staff-row" data-role="{{ $member->role_id }}">
                                                    <td>{{ $uniqueId }}</td>
                                                    <td>{{ trim($member->first_name . ' ' . $member->last_name) }}</td>
                                                    <td>
                                                        <input type="time" class="form-control form-control-sm att-time-input" name="rows[{{ $i }}][in_time]" value="{{ $staffIn ? date('H:i', strtotime($staffIn)) : '' }}" step="60" @if($holidayLock) disabled @endif>
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control form-control-sm att-time-input" name="rows[{{ $i }}][out_time]" value="{{ $staffOut ? date('H:i', strtotime($staffOut)) : '' }}" step="60" @if($holidayLock) disabled @endif>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="rows[{{ $i }}][unique_id]" value="{{ $uniqueId }}">
                                                        <input type="hidden" name="rows[{{ $i }}][entity_type]" value="staff">
                                                        <select class="form-control form-control-sm status-select" name="rows[{{ $i }}][status]" @if($holidayLock) disabled @endif>
                                                            <option value="">Select</option>
                                                            <option value="present" {{ $statusVal === 'present' ? 'selected' : '' }}>Present</option>
                                                            <option value="absent" {{ $statusVal === 'absent' ? 'selected' : '' }}>Absent</option>
                                                            <option value="leave" {{ $statusVal === 'leave' ? 'selected' : '' }}>Leave</option>
                                                            <option value="late" {{ $statusVal === 'late' ? 'selected' : '' }}>Late</option>
                                                            <option value="early_out" {{ $statusVal === 'early_out' ? 'selected' : '' }}>Early out</option>
                                                            <option value="halfday" {{ $statusVal === 'halfday' ? 'selected' : '' }}>Halfday</option>
                                                            <option value="holiday" {{ $statusVal === 'holiday' ? 'selected' : '' }}>Holiday</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        @if(!empty($statusVal) || !empty($staffIn) || !empty($staffOut))
                                                            <span class="att-status-badge" style="background:#d1f7e6;color:#0f5132;">Marked</span>
                                                        @else
                                                            <span class="att-status-badge">Not marked yet</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-danger reset-row" @if($holidayLock) disabled @endif>Reset</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="att-empty d-none" id="staffEmpty">No staff found for selected role.</div>
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
    .status-select.status-present, .att-status-badge.status-present { background: #dcfce7; color: #166534; border-color: #86efac; }
    .status-select.status-absent, .att-status-badge.status-absent { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
    .status-select.status-leave, .att-status-badge.status-leave { background: #fef3c7; color: #92400e; border-color: #fcd34d; }
    .status-select.status-late, .att-status-badge.status-late { background: #ffedd5; color: #9a3412; border-color: #fdba74; }
    .status-select.status-early_out, .att-status-badge.status-early_out { background: #dbeafe; color: #1e3a8a; border-color: #93c5fd; }
    .status-select.status-halfday, .att-status-badge.status-halfday { background: #ede9fe; color: #5b21b6; border-color: #c4b5fd; }
    .status-select.status-holiday, .att-status-badge.status-holiday { background: #e2e8f0; color: #334155; border-color: #cbd5e1; }
    .status-select.status-none, .att-status-badge.status-none { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
    .att-status-badge.status-time { background: #fff7ed; color: #9a3412; border-color: #fed7aa; }
</style>

<script>
    function filterRows() {
        var classId = $('#studentClassFilter').val();
        var roleId = $('#staffRoleFilter').val();

        var studentVisible = 0;
        $('.student-row').each(function () {
            var rowClass = $(this).data('class').toString();
            var show = !classId || rowClass === classId;
            $(this).toggle(show);
            if (show) studentVisible++;
        });
        $('#studentsEmpty').toggleClass('d-none', studentVisible > 0);

        var staffVisible = 0;
        $('.staff-row').each(function () {
            var rowRole = $(this).data('role').toString();
            var show = !roleId || rowRole === roleId;
            $(this).toggle(show);
            if (show) staffVisible++;
        });
        $('#staffEmpty').toggleClass('d-none', staffVisible > 0);
    }

    function formatStatusLabel(statusValue) {
        return statusValue.replace(/_/g, ' ').replace(/\b\w/g, function (c) { return c.toUpperCase(); });
    }

    function to24Hour(time12) {
        var value = (time12 || '').trim().toUpperCase();
        if (!value) return '';

        var m12 = value.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/);
        if (m12) {
            var h = parseInt(m12[1], 10);
            var min = m12[2];
            var meridian = m12[3];
            if (h < 1 || h > 12) return null;
            if (meridian === 'AM') {
                h = h === 12 ? 0 : h;
            } else {
                h = h === 12 ? 12 : h + 12;
            }
            return String(h).padStart(2, '0') + ':' + min;
        }

        var m24 = value.match(/^(\d{2}):(\d{2})$/);
        if (m24) {
            return value;
        }

        return null;
    }

    function to12Hour(timeValue) {
        var v = (timeValue || '').trim();
        if (!v) return '';

        var m24 = v.match(/^(\d{2}):(\d{2})(?::\d{2})?$/);
        if (m24) {
            var h = parseInt(m24[1], 10);
            var min = m24[2];
            var ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12;
            h = h ? h : 12;
            return String(h).padStart(2, '0') + ':' + min + ' ' + ampm;
        }

        var m12 = v.toUpperCase().match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/);
        if (m12) {
            var hh = parseInt(m12[1], 10);
            if (hh < 1 || hh > 12) return '';
            return String(hh).padStart(2, '0') + ':' + m12[2] + ' ' + m12[3];
        }

        return '';
    }

    function normalizeTimeInput($input) {
        var val = ($input.val() || '').trim();
        if (!val) return true;
        if (($input.attr('type') || '').toLowerCase() === 'time') {
            var ok = to24Hour(val) !== null;
            $input.toggleClass('is-invalid', !ok);
            return ok;
        }
        var display = to12Hour(val);
        if (!display) {
            $input.addClass('is-invalid');
            return false;
        }
        $input.removeClass('is-invalid').val(display);
        return true;
    }

    function normalizeAllTimeInputs($scope) {
        var ok = true;
        $scope.find('input.att-time-input').each(function () {
            if (!normalizeTimeInput($(this))) {
                ok = false;
            }
        });
        return ok;
    }

    function convertFormTimesTo24($form) {
        var valid = true;
        $form.find('input.att-time-input').each(function () {
            var $input = $(this);
            var displayVal = ($input.val() || '').trim();
            if (!displayVal) {
                $input.removeClass('is-invalid');
                return;
            }
            var v24 = to24Hour(displayVal);
            if (!v24) {
                $input.addClass('is-invalid');
                valid = false;
            } else {
                $input.removeClass('is-invalid').val(v24);
            }
        });
        return valid;
    }
    function paintStatus($select) {
        var statusValue = $select.val() || '';
        var classes = 'status-present status-absent status-leave status-late status-early_out status-halfday status-holiday status-none status-time';
        var statusClass = statusValue ? ('status-' + statusValue) : 'status-none';
        var $row = $select.closest('tr');

        $select.removeClass(classes).addClass(statusClass);

        var $badge = $row.find('.att-status-badge');
        if ($badge.length) {
            var hasAnyTime = ($row.find('input.att-time-input').eq(0).val() || '') !== '' || ($row.find('input.att-time-input').eq(1).val() || '') !== '';
            $badge.attr('style', '');
            $badge.removeClass(classes);
            if (statusValue) {
                $badge.addClass(statusClass).text(formatStatusLabel(statusValue));
            } else if (hasAnyTime) {
                $badge.addClass('status-time').text('Marked (Time)');
            } else {
                $badge.addClass('status-none').text('Not marked yet');
            }
        }
    }

    function applyBulkStatus(targetTableId, statusValue) {
        if (!statusValue) return;
        $('#' + targetTableId + ' tbody tr:visible').find('select.status-select').each(function () {
            $(this).val(statusValue);
            paintStatus($(this));
        });
    }

    function applyBulkTime(targetTableId, inTime, outTime) {
        if (inTime) {
            $('#' + targetTableId + ' tbody tr:visible').each(function () {
                var $inp = $(this).find('input.att-time-input').eq(0);
                var v = (($inp.attr('type') || '').toLowerCase() === 'time') ? (to24Hour(inTime) || inTime) : (to12Hour(inTime) || inTime);
                $inp.val(v);
            });
        }
        if (outTime) {
            $('#' + targetTableId + ' tbody tr:visible').each(function () {
                var $out = $(this).find('input.att-time-input').eq(1);
                var v2 = (($out.attr('type') || '').toLowerCase() === 'time') ? (to24Hour(outTime) || outTime) : (to12Hour(outTime) || outTime);
                $out.val(v2);
            });
        }
        $('#' + targetTableId + ' tbody tr:visible').each(function () {
            paintStatus($(this).find('select.status-select'));
        });
    }

    $(document).ready(function () {
        $('.select2').select2({ theme: 'bootstrap4', width: 'resolve' });

        function formatDateIndian(dateStr) {
            if (!dateStr) return '';
            var parts = dateStr.split('-');
            return parts.length === 3 ? (parts[2] + '/' + parts[1] + '/' + parts[0]) : dateStr;
        }

        function updateQueryParam(key, value) {
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

        $('#studentClassFilter').on('change', function () {
            updateQueryParam('class_type_id', $(this).val());
        });
        $('#staffRoleFilter').on('change', function () {
            updateQueryParam('role_id', $(this).val());
        });

        $('#applyBulkStudent').on('click', function () {
            applyBulkStatus('studentsTable', $('#bulkStatusStudent').val());
        });
        $('#applyBulkStaff').on('click', function () {
            applyBulkStatus('staffTable', $('#bulkStatusStaff').val());
        });
        $('#applyBulkTimeStudent').on('click', function () {
            applyBulkTime('studentsTable', $('#bulkInStudent').val(), $('#bulkOutStudent').val());
        });
        $('#applyBulkTimeStaff').on('click', function () {
            applyBulkTime('staffTable', $('#bulkInStaff').val(), $('#bulkOutStaff').val());
        });

        $(document).on('click', '.reset-row', function () {
            var $row = $(this).closest('tr');
            $row.find('input.att-time-input').val('');
            $row.find('select.status-select').val('');
            paintStatus($row.find('select.status-select'));
        });

        $(document).on('change', 'select.status-select', function () {
            paintStatus($(this));
        });

        $(document).on('change blur', 'input.att-time-input', function () {
            normalizeTimeInput($(this));
            paintStatus($(this).closest('tr').find('select.status-select'));
        });

        normalizeAllTimeInputs($(document));

        $('select.status-select').each(function () {
            paintStatus($(this));
        });

        $('form[method="post"]').on('submit', function (e) {
            if (@json($holidayLock)) {
                e.preventDefault();
                alert('Attendance marking is not allowed on holiday dates.');
                return;
            }
            var $form = $(this);
            if (!normalizeAllTimeInputs($form) || !convertFormTimesTo24($form)) {
                e.preventDefault();
                alert('Please enter time in hh:mm AM/PM format.');
            }
        });

        var classId = $('#classFilterInput').val();
        var roleId = $('#roleFilterInput').val();
        if (classId) {
            $('#studentClassFilter').val(classId);
        }
        if (roleId) {
            $('#staffRoleFilter').val(roleId);
        }
        filterRows();
    });
</script>
@endsection




