@extends('layout.app')
@section('content')

@include('attendance.theme')

<div class="content-wrapper attendance-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h3 class="card-title mb-0"><i class="fa fa-list"></i> &nbsp;Attendance Report</h3>
                        <div class="text-white-50">Day wise ya month wise attendance report</div>
                    </div>
                    @php
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
                    @endphp
                    <a href="{{ $exportUrl }}" class="btn btn-sm btn-outline-light"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills att-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ ($activeTab ?? 'students') === 'students' ? 'active' : '' }}"
                               href="{{ url('attendance/report?tab=students&report_mode='.$reportMode.'&date='.$selectedDate.'&month='.$month.'&year='.$year) }}"><i class="fa fa-graduation-cap"></i> &nbsp;Students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ ($activeTab ?? 'students') === 'staff' ? 'active' : '' }}"
                               href="{{ url('attendance/report?tab=staff&report_mode='.$reportMode.'&date='.$selectedDate.'&month='.$month.'&year='.$year) }}"><i class="fa fa-users"></i> &nbsp;Staff</a>
                        </li>
                    </ul>

                    <form method="get" action="{{ url('attendance/report') }}" class="mt-2">
                        <input type="hidden" name="tab" value="{{ $activeTab ?? 'students' }}">
                        <div class="d-flex flex-wrap align-items-center att-filter-row" style="gap:10px;">
                            @if(($activeTab ?? 'students') === 'staff')
                                <select name="role_id" class="form-control form-control-sm select2" style="min-width:220px;">
                                    <option value="">All Roles</option>
                                    @foreach($staffRoles as $role)
                                        <option value="{{ $role->id }}" {{ (string)$roleFilter === (string)$role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select name="class_type_id" class="form-control form-control-sm select2" style="min-width:220px;">
                                    <option value="">All Classes</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ (string)$classFilter === (string)$class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            @endif

                            <select name="report_mode" id="report_mode" class="form-control form-control-sm" style="min-width:180px;">
                                <option value="day_wise" {{ ($reportMode ?? 'day_wise') === 'day_wise' ? 'selected' : '' }}>Day Wise</option>
                                <option value="monthly" {{ ($reportMode ?? 'day_wise') === 'monthly' ? 'selected' : '' }}>Month Wise</option>
                            </select>

                            <div id="dayFields" class="d-flex" style="gap:10px;">
                                <input type="date" name="date" class="form-control form-control-sm" value="{{ $selectedDate ?? date('Y-m-d') }}" style="max-width:220px;">
                            </div>

                            <div id="monthFields" class="d-flex" style="gap:10px; display:none;">
                                <select name="month" class="form-control form-control-sm att-chip">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('M', mktime(0,0,0,$m,1)) }}</option>
                                    @endfor
                                </select>

                                <select name="year" class="form-control form-control-sm att-chip">
                                    @for($y = date('Y')-3; $y <= date('Y')+1; $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
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
                                @forelse($rows as $row)
                                    <tr>
                                        <td>{{ $row['unique_id'] }}</td>
                                        <td>{{ $row['name'] }}</td>
                                        <td>{{ $row['counts']['present'] }}</td>
                                        <td>{{ $row['counts']['absent'] }}</td>
                                        <td>{{ $row['counts']['leave'] }}</td>
                                        <td>{{ $row['counts']['late'] }}</td>
                                        <td>{{ $row['counts']['early_out'] }}</td>
                                        <td>{{ $row['counts']['halfday'] }}</td>
                                        <td>{{ $row['counts']['holiday'] }}</td>
                                        <td><b>{{ $row['counts']['total'] }}</b></td>
                                        <td>
                                            @if(($activeTab ?? 'students') === 'staff')
                                                <a class="btn btn-sm btn-outline-secondary" href="{{ url('attendance/view?tab=staff&staff='.$row['unique_id'].'&month='.$month.'&year='.$year) }}" title="View Attendance">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-outline-secondary" href="{{ url('attendance/view?tab=students&student='.$row['unique_id'].'&month='.$month.'&year='.$year) }}" title="View Attendance">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">No data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="2">Overall Total</td>
                                    <td>{{ $totals['present'] ?? 0 }}</td>
                                    <td>{{ $totals['absent'] ?? 0 }}</td>
                                    <td>{{ $totals['leave'] ?? 0 }}</td>
                                    <td>{{ $totals['late'] ?? 0 }}</td>
                                    <td>{{ $totals['early_out'] ?? 0 }}</td>
                                    <td>{{ $totals['halfday'] ?? 0 }}</td>
                                    <td>{{ $totals['holiday'] ?? 0 }}</td>
                                    <td>{{ $totals['total'] ?? 0 }}</td>
                                    <td>-</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h5 class="mb-2">Date-wise Summary ({{ date('d/m/Y', strtotime($dateFrom)) }} - {{ date('d/m/Y', strtotime($dateTo)) }})</h5>
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
                                    @forelse($dateWiseSummary as $date => $counts)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($date)) }}</td>
                                            <td>{{ $counts['present'] }}</td>
                                            <td>{{ $counts['absent'] }}</td>
                                            <td>{{ $counts['leave'] }}</td>
                                            <td>{{ $counts['late'] }}</td>
                                            <td>{{ $counts['early_out'] }}</td>
                                            <td>{{ $counts['halfday'] }}</td>
                                            <td>{{ $counts['holiday'] }}</td>
                                            <td><b>{{ $counts['total'] }}</b></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">No summary found.</td>
                                        </tr>
                                    @endforelse
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
@endsection
