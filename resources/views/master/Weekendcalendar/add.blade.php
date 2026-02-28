@php
    $getAttendanceStatus = Helper::getAttendanceStatus();
    $calendarMonth = isset($calendarMonth) ? (int) $calendarMonth : (int) date('n');
    $calendarYear = isset($calendarYear) ? (int) $calendarYear : (int) date('Y');
    $monthStart = sprintf('%04d-%02d-01', $calendarYear, $calendarMonth);
    $daysInMonth = (int) date('t', strtotime($monthStart));
    $firstDayOfWeek = (int) date('w', strtotime($monthStart));
    $monthName = $monthName ?? date('F', strtotime($monthStart));

    $eventsPayload = $calendarEvents ?? [];
@endphp
@extends('layout.app')
@section('content')

@include('attendance.theme')
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<div class="content-wrapper attendance-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h3 class="card-title mb-0"><i class="fa fa-calendar"></i> &nbsp;Attendance Calendar Management</h3>
                        <div class="text-white-50">Click on date to add holiday/event. Multiple events and date ranges supported.</div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="get" action="{{ url('attendance/add_weekend') }}" class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-2">
                                <select name="month" class="form-control form-control-sm">
                                    @for($m=1;$m<=12;$m++)
                                        <option value="{{ $m }}" {{ $calendarMonth === $m ? 'selected' : '' }}>{{ date('M', mktime(0,0,0,$m,1)) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="year" class="form-control form-control-sm">
                                    @for($y=date('Y')-2;$y<=date('Y')+2;$y++)
                                        <option value="{{ $y }}" {{ $calendarYear === $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-primary">Load Month</button>
                            </div>
                        </div>
                    </form>

                    <div class="d-flex text-muted mb-2" style="justify-content:space-between;font-size:12px;">
                        <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                    </div>

                    <div class="att-calendar-grid">
                        @for($i=0;$i<42;$i++)
                            @php $day = $i - $firstDayOfWeek + 1; @endphp
                            @if($day < 1 || $day > $daysInMonth)
                                <div class="att-day empty"></div>
                            @else
                                @php
                                    $dateStr = sprintf('%04d-%02d-%02d', $calendarYear, $calendarMonth, $day);
                                    $dayEvents = $calendarEvents[$dateStr] ?? [];
                                    $cls = '';
                                    foreach ($dayEvents as $ev) {
                                        $n = strtolower((string)($ev['attendance_status_name'] ?? ''));
                                        if ($n === 'holiday') { $cls = 'status-holiday'; break; }
                                        if ($n === 'event') { $cls = 'status-event'; }
                                        if ($cls === '' && $n !== '') { $cls = 'status-other'; }
                                    }
                                @endphp
                                <div class="att-day {{ $cls }}" data-date="{{ $dateStr }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>{{ $day }}</strong>
                                        @if(count($dayEvents) > 0)
                                            <span class="badge badge-dark">{{ count($dayEvents) }}</span>
                                        @endif
                                    </div>
                                    <div class="event-list-mini">
                                        @foreach(array_slice($dayEvents, 0, 2) as $ev)
                                            <div class="event-chip">{{ $ev['attendance_status_name'] ?: 'Status' }} - {{ ($ev['event_title'] ?? '') ?: ($ev['event_schedule'] ?? '') }}</div>
                                        @endforeach
                                        @if(count($dayEvents) > 2)
                                            <div class="event-chip muted">+{{ count($dayEvents) - 2 }} more</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="calendarEventModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Calendar Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="calendarEventForm">
                    @csrf
                    <input type="hidden" name="mode" value="1">
                    <input type="hidden" name="event_id" id="modalEventId" value="">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>From Date</label>
                            <input type="date" class="form-control" name="from_date" id="modalFromDate" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>To Date</label>
                            <input type="date" class="form-control" name="to_date" id="modalToDate" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Event Title</label>
                            <input type="text" class="form-control" name="event_title" id="modalEventTitle" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Status</label>
                            <select class="form-control select2" name="attendance_status" id="modalAttendanceStatus" required>
                                <option value="">Select</option>
                                @foreach($getAttendanceStatus as $attendance_status)
                                    <option value="{{ $attendance_status->id }}">{{ $attendance_status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Description</label>
                            <input type="text" class="form-control" name="event_description" id="modalEventDescription" placeholder="Optional details">
                        </div>
                    </div>
                    <div class="border rounded p-2 mb-2">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h6 class="mb-1">Auto Message (Event/Holiday)</h6>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="modalAutoMessageEnabled">
                                <label class="custom-control-label" for="modalAutoMessageEnabled">Enable Auto Message</label>
                            </div>
                        </div>
                        <small class="text-muted d-block mb-2">Same date ke multiple events/holidays ke liye ek hi message/services use hoga.</small>
                        <div id="calendarMessageFields">
                            <label class="mb-1">Message Services</label>
                            <div class="d-flex flex-wrap mb-2" style="gap:12px;">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input modal-message-service" type="checkbox" id="cal_msg_whatsapp" value="whatsapp" checked>
                                    <label for="cal_msg_whatsapp" class="custom-control-label">WhatsApp</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input modal-message-service" type="checkbox" id="cal_msg_firebase" value="firebase" checked>
                                    <label for="cal_msg_firebase" class="custom-control-label">Firebase</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input modal-message-service" type="checkbox" id="cal_msg_sms" value="sms" checked>
                                    <label for="cal_msg_sms" class="custom-control-label">SMS</label>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label>Message Text</label>
                                <textarea class="form-control" id="modalAutoMessageText" rows="2" maxlength="1000" placeholder="Enter auto message for this date"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex" style="gap:8px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="newCalendarEvent">New Entry</button>
                        <button type="button" class="btn btn-sm btn-warning" id="quickHoliday">Mark Holiday</button>
                        <button type="button" class="btn btn-sm btn-info" id="quickEvent">Mark Event</button>
                    </div>
                </form>
                <div id="calendarEventMsg" class="small text-muted mt-2"></div>
                <hr>
                <h6 class="mb-2">Existing events on selected date</h6>
                <div id="selectedDateEvents" class="small text-muted">No events.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger mr-auto d-none" id="deleteCalendarEvent" style="display:none !important;">Delete Selected</button>
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCalendarEvent">Save</button>
            </div>
        </div>
    </div>
</div>

<style>
    .att-calendar-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:8px; }
    .att-day { min-height:96px; border:1px solid #dbe2ea; border-radius:10px; background:#fff; padding:6px; cursor:pointer; }
    .att-day.empty { background:#f8fafc; border-style:dashed; cursor:default; }
    .att-day.status-holiday { background:#fee2e2; border-color:#fca5a5; }
    .att-day.status-event { background:#dbeafe; border-color:#93c5fd; }
    .att-day.status-other { background:#fef3c7; border-color:#fcd34d; }
    .event-list-mini { margin-top:4px; }
    .event-chip { font-size:10px; padding:2px 6px; border:1px solid #cbd5e1; border-radius:999px; margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .event-chip.muted { color:#64748b; border-style:dashed; }
    .selected-date-event-row { border:1px solid #e5e7eb; border-radius:8px; padding:8px; margin-bottom:8px; }
    .selected-date-event-row.active { border-color:#3b82f6; background:#eff6ff; }
    .selected-date-event-actions .btn { padding:.1rem .4rem; font-size:11px; }
</style>

<script>
$(function() {
    var eventsByDate = @json($eventsPayload);
    var selectedDate = '';
    var selectedEventId = '';

    function escHtml(str) {
        return String(str || '').replace(/[&<>'\"]/g, function(ch){
            return {'&':'&amp;','<':'&lt;','>':'&gt;','\'' : '&#39;','\"':'&quot;'}[ch] || ch;
        });
    }

    function statusClass(events) {
        var cls = '';
        (events || []).forEach(function(ev){
            var name = String(ev.attendance_status_name || '').toLowerCase();
            if (name === 'holiday') { cls = 'status-holiday'; return; }
            if (name === 'event' && cls !== 'status-holiday') { cls = 'status-event'; return; }
            if (!cls && name) { cls = 'status-other'; }
        });
        return cls;
    }

    function renderCell(dateStr) {
        var $cell = $('.att-day[data-date="' + dateStr + '"]');
        if (!$cell.length) return;

        var events = eventsByDate[dateStr] || [];
        $cell.removeClass('status-holiday status-event status-other').addClass(statusClass(events));

        var topHtml = '<div class="d-flex justify-content-between align-items-center"><strong>' + parseInt(dateStr.slice(-2), 10) + '</strong>';
        if (events.length > 0) topHtml += '<span class="badge badge-dark">' + events.length + '</span>';
        topHtml += '</div>';

        var listHtml = '<div class="event-list-mini">';
        events.slice(0, 2).forEach(function(ev){
            var t = ev.event_title || ev.event_schedule || '';
            listHtml += '<div class="event-chip">' + escHtml(ev.attendance_status_name || 'Status') + ' - ' + escHtml(t) + '</div>';
        });
        if (events.length > 2) listHtml += '<div class="event-chip muted">+' + (events.length - 2) + ' more</div>';
        listHtml += '</div>';

        $cell.html(topHtml + listHtml);
    }

    function renderSelectedDateEvents(dateStr) {
        var items = eventsByDate[dateStr] || [];
        if (!items.length) {
            $('#selectedDateEvents').html('<span class="text-muted">No events.</span>');
            return;
        }

        var html = '';
        items.forEach(function(ev){
            var title = ev.event_title || ev.event_schedule || '';
            var desc = ev.event_description || '';
            var isActive = String(selectedEventId) && String(selectedEventId) === String(ev.id);
            var msgTag = Number(ev.auto_message_enabled || 0) === 1 ? ' <span class="badge badge-success">Auto Msg</span>' : '';
            html += ''
                + '<div class="selected-date-event-row ' + (isActive ? 'active' : '') + '" data-event-id="' + escHtml(ev.id) + '">'
                + '  <div class="d-flex justify-content-between align-items-start">'
                + '    <div>'
                + '      <div><b>' + escHtml(ev.attendance_status_name || 'Status') + '</b> - ' + escHtml(title) + msgTag + '</div>'
                + (desc ? ('      <div class="text-muted mt-1">' + escHtml(desc) + '</div>') : '')
                + '    </div>'
                + '    <div class="selected-date-event-actions">'
                + '      <button type="button" class="btn btn-outline-primary btn-sm pick-existing-event" data-event-id="' + escHtml(ev.id) + '">Edit</button> '
                + '      <button type="button" class="btn btn-outline-danger btn-sm remove-existing-event" data-event-id="' + escHtml(ev.id) + '">Delete</button>'
                + '    </div>'
                + '  </div>'
                + '</div>';
        });
        $('#selectedDateEvents').html(html);
    }

    function clearFormForDate(dateStr) {
        selectedEventId = '';
        $('#modalEventId').val('');
        $('#modalFromDate').val(dateStr || '');
        $('#modalToDate').val(dateStr || '');
        $('#modalEventTitle').val('');
        $('#modalEventDescription').val('');
        $('#modalAttendanceStatus').val('').trigger('change');
        $('#modalAutoMessageEnabled').prop('checked', false);
        $('.modal-message-service').prop('checked', true);
        $('#modalAutoMessageText').val('');
        toggleCalendarMessageFields();
        $('#deleteCalendarEvent').addClass('d-none');
        $('#calendarEventMsg').text('');
    }

    function parseServiceList(raw) {
        if (Array.isArray(raw)) return raw;
        if (!raw) return ['whatsapp', 'firebase', 'sms'];
        return String(raw).split(',').map(function(v){ return $.trim(String(v).toLowerCase()); }).filter(Boolean);
    }

    function setMessageServices(raw) {
        var services = parseServiceList(raw);
        if (!services.length) services = ['whatsapp', 'firebase', 'sms'];
        $('.modal-message-service').prop('checked', false);
        services.forEach(function(s){
            $('.modal-message-service[value="' + s + '"]').prop('checked', true);
        });
    }

    function getSelectedMessageServices() {
        var arr = [];
        $('.modal-message-service:checked').each(function(){ arr.push($(this).val()); });
        return arr.length ? arr : ['whatsapp', 'firebase', 'sms'];
    }

    function toggleCalendarMessageFields() {
        $('#calendarMessageFields').toggle($('#modalAutoMessageEnabled').is(':checked'));
    }

    function loadEventInForm(ev) {
        if (!ev) return;
        selectedEventId = String(ev.id || '');
        $('#modalEventId').val(ev.id || '');
        $('#modalFromDate').val(ev.date || selectedDate || '');
        $('#modalToDate').val(ev.date || selectedDate || '');
        $('#modalEventTitle').val(ev.event_title || ev.event_schedule || '');
        $('#modalEventDescription').val(ev.event_description || '');
        $('#modalAttendanceStatus').val(String(ev.attendance_status || '')).trigger('change');
        $('#modalAutoMessageEnabled').prop('checked', Number(ev.auto_message_enabled || 0) === 1);
        setMessageServices(ev.message_services || 'whatsapp,firebase,sms');
        $('#modalAutoMessageText').val(ev.auto_message_text || '');
        toggleCalendarMessageFields();
        $('#deleteCalendarEvent').toggleClass('d-none', !selectedEventId);
        renderSelectedDateEvents(selectedDate || ev.date || '');
    }

    function getEventById(eventId, dateStr) {
        var items = eventsByDate[dateStr] || [];
        for (var i = 0; i < items.length; i++) {
            if (String(items[i].id) === String(eventId)) return items[i];
        }
        return null;
    }

    function upsertLocalEvent(item, oldDate) {
        var targetOldDate = oldDate || item.date;
        Object.keys(eventsByDate).forEach(function(ds) {
            eventsByDate[ds] = (eventsByDate[ds] || []).filter(function(ev){ return String(ev.id) !== String(item.id); });
        });
        if (!eventsByDate[item.date]) eventsByDate[item.date] = [];
        eventsByDate[item.date].push(item);
        eventsByDate[item.date].sort(function(a, b){ return Number(a.id || 0) - Number(b.id || 0); });
        if (targetOldDate && targetOldDate !== item.date) renderCell(targetOldDate);
        renderCell(item.date);
    }

    function removeLocalEvent(eventId, dateStr) {
        if (!eventsByDate[dateStr]) return;
        eventsByDate[dateStr] = eventsByDate[dateStr].filter(function(ev){ return String(ev.id) !== String(eventId); });
        renderCell(dateStr);
    }

    function findStatusValue(keyword) {
        var result = '';
        $('#modalAttendanceStatus option').each(function(){
            if (($(this).text() || '').toLowerCase().indexOf(keyword) !== -1) {
                result = $(this).val();
                return false;
            }
        });
        return result;
    }

    $('.select2').select2({ theme: 'bootstrap4', width: 'resolve' });
    toggleCalendarMessageFields();
    $('#modalAutoMessageEnabled').on('change', toggleCalendarMessageFields);

    $('.att-day').not('.empty').on('click', function() {
        selectedDate = $(this).data('date');
        clearFormForDate(selectedDate);
        renderSelectedDateEvents(selectedDate);
        var existing = (eventsByDate[selectedDate] || []);
        if (existing.length) loadEventInForm(existing[0]);
        $('#calendarEventModal').modal('show');
    });

    $('#quickHoliday').on('click', function() {
        var val = findStatusValue('holiday');
        if (val) $('#modalAttendanceStatus').val(val).trigger('change');
        if (!($('#modalEventTitle').val() || '').trim()) $('#modalEventTitle').val('Holiday');
    });

    $('#quickEvent').on('click', function() {
        var val = findStatusValue('event');
        if (val) $('#modalAttendanceStatus').val(val).trigger('change');
        if (!($('#modalEventTitle').val() || '').trim()) $('#modalEventTitle').val('Event');
    });

    $('#newCalendarEvent').on('click', function() {
        clearFormForDate(selectedDate || $('#modalFromDate').val() || '');
        renderSelectedDateEvents(selectedDate || $('#modalFromDate').val() || '');
        var items = eventsByDate[selectedDate || ''] || [];
        if (items.length) {
            $('#modalAutoMessageEnabled').prop('checked', Number(items[0].auto_message_enabled || 0) === 1);
            setMessageServices(items[0].message_services || 'whatsapp,firebase,sms');
            $('#modalAutoMessageText').val(items[0].auto_message_text || '');
            toggleCalendarMessageFields();
        }
    });

    $('#saveCalendarEvent').on('click', function() {
        var eventId = $('#modalEventId').val();
        var payload = {
            _token: '{{ csrf_token() }}',
            mode: eventId ? 2 : 1,
            event_id: eventId,
            from_date: $('#modalFromDate').val(),
            to_date: $('#modalToDate').val(),
            event_title: $('#modalEventTitle').val(),
            event_description: $('#modalEventDescription').val(),
            attendance_status: $('#modalAttendanceStatus').val(),
            auto_message_enabled: $('#modalAutoMessageEnabled').is(':checked') ? 1 : 0,
            auto_message_text: $('#modalAutoMessageText').val(),
            message_services: getSelectedMessageServices()
        };

        if (!payload.from_date || !payload.to_date || !payload.event_title || !payload.attendance_status) {
            $('#calendarEventMsg').text('All fields are required.');
            return;
        }
        if (payload.auto_message_enabled && !$.trim(payload.auto_message_text || '')) {
            $('#calendarEventMsg').text('Message text is required when Auto Message is enabled.');
            return;
        }

        $('#saveCalendarEvent').prop('disabled', true);

        $.ajax({
            url: '{{ url('attendance/add_weekend') }}',
            type: 'POST',
            data: payload,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function(res) {
                if (!(res && res.ok)) {
                    $('#calendarEventMsg').text('Unable to save event.');
                    return;
                }

                if (payload.mode === 2 && res.item) {
                    upsertLocalEvent(res.item, selectedDate);
                    selectedDate = res.item.date || selectedDate;
                    loadEventInForm(res.item);
                    renderSelectedDateEvents(selectedDate);
                } else {
                    (res.items || []).forEach(function(item){
                        upsertLocalEvent(item);
                    });
                    if (selectedDate) {
                        renderSelectedDateEvents(selectedDate);
                        var first = (eventsByDate[selectedDate] || [])[0];
                        if (first) loadEventInForm(first);
                    }
                }

                $('#calendarEventMsg').text(res.message || 'Saved successfully.');
                setTimeout(function(){ $('#calendarEventModal').modal('hide'); }, 400);
            },
            error: function(xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Unable to save event.';
                $('#calendarEventMsg').text(msg);
            },
            complete: function() {
                $('#saveCalendarEvent').prop('disabled', false);
            }
        });
    });

    $(document).on('click', '.pick-existing-event', function() {
        var eventId = $(this).data('event-id');
        var ev = getEventById(eventId, selectedDate);
        if (ev) {
            loadEventInForm(ev);
            $('#calendarEventMsg').text('');
        }
    });

    function deleteCalendarEvent(eventId) {
        if (!eventId) return;
        $.ajax({
            url: '{{ url('attendance/add_weekend') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                mode: 3,
                event_id: eventId
            },
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            success: function(res) {
                if (!(res && res.ok)) {
                    $('#calendarEventMsg').text('Unable to delete event.');
                    return;
                }

                var dateStr = res.date || selectedDate;
                removeLocalEvent(eventId, dateStr);

                var items = eventsByDate[dateStr] || [];
                if (String(selectedDate) === String(dateStr)) {
                    if (items.length) {
                        loadEventInForm(items[0]);
                    } else {
                        clearFormForDate(dateStr);
                    }
                    renderSelectedDateEvents(dateStr);
                }

                $('#calendarEventMsg').text(res.message || 'Deleted successfully.');
            },
            error: function(xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Unable to delete event.';
                $('#calendarEventMsg').text(msg);
            }
        });
    }

    $(document).on('click', '.remove-existing-event', function() {
        var eventId = $(this).data('event-id');
        if (!confirm('Delete this saved event/holiday?')) return;
        deleteCalendarEvent(eventId);
    });

    $('#deleteCalendarEvent').on('click', function() {
        var eventId = $('#modalEventId').val();
        if (!eventId) return;
        if (!confirm('Delete selected saved event/holiday?')) return;
        deleteCalendarEvent(eventId);
    });
});
</script>
@endsection
