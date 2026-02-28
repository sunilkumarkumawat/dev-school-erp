@extends('layout.app')
@section('content')
@php
$classType = Helper::classType();
@endphp

<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<div class="content-wrapper">
<section class="content pt-3">
<div class="container-fluid">

<div class="row">
<div class="col-12">

<div class="card card-outline card-primary mt-4">
    <div class="card-header bg-primary">
        <h4 class="card-title">
            <i class="fa fa-calendar-check-o"></i>
            Student Daily Attendance Report
        </h4>
    </div>

    <div class="card-body">

        {{-- Filters --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <select id="daily_class_type" class="form-control select2">
                    <option value="">--Select Class--</option>
                    @foreach($classType as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <input type="date" id="daily_date" class="form-control">
            </div>

            <div class="col-md-3">
                <select id="daily_status" class="form-control select2">
                    <option value="">--Status--</option>
                    @foreach($attendanceStatus as $st)
                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-3">
                <button class="btn btn-success" onclick="loadDailyReport()">SEARCH</button>
                <button class="btn btn-secondary" onclick="resetDaily()">RESET</button>
            </div>
        </div>

        <div class="mb-2 text-danger" id="daily_msg"></div>
        <div class="mb-2"><b>Attendance Date:</b> <span id="show_date">-</span></div>

        {{-- TABLE --}}
        <div class="table-responsive wrapper">
            <table id="table"
                class="table table-bordered table-striped border dataTable paddingTable">

                <thead class="bg-primary">
                    <tr>
                        <th>Adm. No</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                        <th>Mobile No</th>
                        <th>Attendance</th>
                    </tr>
                </thead>

                <tbody id="daily_body">
                    <tr>
                        <td colspan="7" class="text-center">Record = 0</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-2">
            <b>Record = <span id="record_count">0</span></b>
        </div>

    </div>
</div>

</div>
</div>

</div>
</section>
</div>

{{-- Loading Modal --}}
<div class="modal" id="loadingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-body text-center">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Loading...</p>
        </div>
    </div>
</div>

{{-- JS --}}
<script>
function loadDailyReport() {

    let class_id = $('#daily_class_type').val();
    let date = $('#daily_date').val();
    let status = $('#daily_status').val();

    if (!class_id || !date) {
        toastr.error('Class and Date are required');
        return;
    }

    $('#daily_body').html(`<tr><td colspan="7" class="text-center">Loading...</td></tr>`);
    $('#daily_msg').html('');
    $('#show_date').text(date);

    $.ajax({
        url: "{{ url('studentsAttendanceDailyReport') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            class_type_id: class_id,
            date: date,
            status: status
        },
        success: function(res) {

            let html = '';
            let count = 0;

            if (res.data.length === 0) {
                $('#daily_msg').html('⚠ No Attendance marked for this date.');
                html = `<tr><td colspan="7" class="text-center">Record = 0</td></tr>`;
            } else {

                res.data.forEach(item => {
                    count++;

                    html += `
                        <tr>
                            <td>${item.admissionNo}</td>
                            <td>${item.name}</td>
                            <td>${item.father_name}</td>
                            <td>${item.mobile ?? '-'}</td>
                            <td>
                                <div class="attendance-box">
                                    
                            
                                    ${item.in_time ? `<div class="att-time in">In: ${item.in_time}</div>` : ''}
                                    ${item.out_time ? `<div class="att-time out">Out: ${item.out_time}</div>` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                });
            }

            $('#daily_body').html(html);
            $('#record_count').text(count);
        }
    });
}

function resetDaily() {
    $('#daily_class_type').val('').trigger('change');
    $('#daily_date').val('');
    $('#daily_status').val('').trigger('change');
    $('#daily_body').html(`<tr><td colspan="7" class="text-center">Record = 0</td></tr>`);
    $('#record_count').text(0);
    $('#daily_msg').html('');
    $('#show_date').text('-');
}
</script>

{{-- SAME CSS REUSED --}}
<style>

.attendance-box {
    text-align: center;
    line-height: 1.4;
}

.att-badge {
    display: inline-block;
    width: 28px;
    height: 28px;
    line-height: 28px;
    border-radius: 4px;
    font-weight: bold;
    color: #ff0000;
    margin-bottom: 4px;
}

/* Status Colors */
.att-P { background: #28a745; }  /* Present */
.att-A { background: #dc3545; }  /* Absent */
.att-H { background: #6c757d; }  /* Holiday */

/* Time text */
.att-time {
    font-size: 12px;
}

.att-time.in {
    color: #17a2b8;
}

.att-time.out {
    color: #17a2b8;
}

.wrapper {
    position: relative;
    overflow: auto;
    white-space: nowrap;
}

.paddingTable th,
.paddingTable td {
    padding: 4px !important;
    vertical-align: middle;
}

.table td {
    height: 30px !important;
}

.dataTable thead th {
    white-space: nowrap;
}
</style>
@endsection
