@extends('layout.app')
@section('content')

@include('payroll.theme')

@php
    $monthLabel = date('F', mktime(0, 0, 0, $month, 1));
    $staffCount = is_countable($rows ?? null) ? count($rows) : 0;
    $totalSalaryNow = (float)($totals['salary_till_now'] ?? 0);
    $totalManual = (float)($totals['manual_deductions'] ?? 0);
    $totalLoan = (float)($totals['loan_deductions'] ?? 0);
    $totalDeduction = $totalManual + $totalLoan;
    $totalNet = (float)($totals['net_salary'] ?? ($totalSalaryNow - $totalDeduction));
    $leaveAllowedLabel = isset($payrollSetting) && $payrollSetting->paid_leave_limit !== null
        ? $payrollSetting->paid_leave_limit . ' days'
        : 'Not Set';
@endphp

<div class="content-wrapper payroll-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="payroll-hero">
                <div class="payroll-hero-inner">
                    <div>
                        <div class="payroll-hero-kicker">Payroll Center</div>
                        <div class="payroll-hero-title">Staff Payroll</div>
                        <div class="payroll-hero-subtitle">Salary calculation from attendance ({{ $monthLabel }} {{ $year }})</div>
                        <div class="payroll-hero-chips">
                            <span class="payroll-chip"><i class="fa fa-calendar"></i> {{ $monthLabel }} {{ $year }}</span>
                            <span class="payroll-chip"><i class="fa fa-clock-o"></i> Till: {{ \Carbon\Carbon::parse($rangeEnd)->format('d/m/Y') }}</span>
                            @if(isset($payrollSetting))
                                <span class="payroll-chip"><i class="fa fa-leaf"></i> Leave Allowed: {{ $leaveAllowedLabel }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="payroll-hero-actions">
                        <button type="button" class="btn btn-sm btn-payroll btn-payroll-light" data-toggle="modal" data-target="#payrollSettingsModal">
                            <i class="fa fa-sliders"></i> Payroll Settings
                        </button>
                        <a href="{{ url('payroll/staff/loans') }}" class="btn btn-sm btn-payroll btn-payroll-light">
                            <i class="fa fa-list"></i> Loan / Advance
                        </a>
                        <form method="post" action="{{ url('payroll/staff?month='.$month.'&year='.$year) }}" style="display:inline-block;">
                            @csrf
                            <input type="hidden" name="action" value="generate_salary_bulk">
                            <button type="submit" class="btn btn-sm btn-payroll btn-payroll-accent">
                                <i class="fa fa-check-circle"></i> Generate Salary
                            </button>
                        </form>
                        <button type="button" class="btn btn-sm btn-payroll btn-payroll-primary" data-toggle="modal" data-target="#salaryModal">
                            <i class="fa fa-cog"></i> Set Salary
                        </button>
                    </div>
                </div>
            </div>

            <div class="payroll-stats">
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Total Staff</div>
                    <div class="payroll-stat-value">{{ $staffCount }}</div>
                    <div class="payroll-stat-sub">Active this period</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Salary Till Now</div>
                    <div class="payroll-stat-value">{{ number_format($totalSalaryNow, 2) }}</div>
                    <div class="payroll-stat-sub">Before deductions</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Deductions</div>
                    <div class="payroll-stat-value">{{ number_format($totalDeduction, 2) }}</div>
                    <div class="payroll-stat-sub">Manual + loans</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Net Payable</div>
                    <div class="payroll-stat-value {{ $totalNet < 0 ? 'text-danger' : '' }}">{{ number_format($totalNet, 2) }}</div>
                    <div class="payroll-stat-sub">Calculated total</div>
                </div>
            </div>

            <div class="card card-outline card-orange payroll-card">
                <div class="card-header payroll-card-header">
                    <div>
                        <div class="payroll-card-title"><i class="fa fa-money"></i> Payroll Register</div>
                        <div class="text-muted small">Manage salaries, deductions, and slips for the selected period.</div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center" style="gap:8px;">
                        <span class="badge-soft">Period: {{ $monthLabel }} {{ $year }}</span>
                        <span class="badge-soft">Till: {{ \Carbon\Carbon::parse($rangeEnd)->format('d/m/Y') }}</span>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Validation Error:</strong>
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="get" action="{{ url('payroll/staff') }}">
                        <div class="d-flex flex-wrap align-items-center payroll-filter">
                            <select name="month" class="form-control form-control-sm" style="min-width:120px;">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ (int)$month === (int)$m ? 'selected' : '' }}>{{ date('M', mktime(0,0,0,$m,1)) }}</option>
                                @endfor
                            </select>
                            <select name="year" class="form-control form-control-sm" style="min-width:120px;">
                                @for($y = date('Y')-3; $y <= date('Y')+1; $y++)
                                    <option value="{{ $y }}" {{ (int)$year === (int)$y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            <button class="btn btn-sm btn-payroll btn-payroll-primary">Load</button>

                            <div class="ml-auto payroll-inline-note">
                                Till: <span class="badge-soft">{{ \Carbon\Carbon::parse($rangeEnd)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </form>

                    @if(isset($payrollSetting))
                        <div class="d-flex flex-wrap align-items-center mt-2" style="gap:8px;">
                            <span class="badge-soft">Leave Allowed: {{ $leaveAllowedLabel }}</span>
                            <span class="badge-soft">Early Out Credit: {{ number_format((float)($payrollSetting->early_out_weight ?? 1), 2) }} day</span>
                            <span class="badge-soft">Half Day Credit: {{ number_format((float)($payrollSetting->halfday_weight ?? 0.5), 2) }} day</span>
                        </div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered payroll-table" id="payrollTable">
                            <thead>
                                <tr>
                                    <th>Unique ID</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th class="text-right">Monthly Salary</th>
                                    <th class="text-center">Working Days</th>
                                    <th class="text-right">Per Day Salary</th>
                                    <th class="text-center">Paid Days</th>
                                    <th class="text-right">Salary Till Now</th>
                                    <th class="text-right">Manual Deduction</th>
                                    <th class="text-right">Loan Deduction</th>
                                    <th class="text-right">Net Salary</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rows as $row)
                                    <tr>
                                        <td>{{ $row['unique_id'] }}</td>
                                        <td>{{ $row['name'] }}</td>
                                        <td>{{ $row['role'] }}</td>
                                        <td class="text-right">{{ number_format($row['monthly_salary'], 2) }}</td>
                                        <td class="text-center">{{ $row['working_days'] }}</td>
                                        <td class="text-right">{{ number_format($row['per_day_salary'], 2) }}</td>
                                        <td class="text-center">{{ rtrim(rtrim(number_format($row['paid_days'], 2), '0'), '.') }}</td>
                                        <td class="text-right"><b>{{ number_format($row['salary_till_now'], 2) }}</b></td>
                                        <td class="text-right">
                                            {{ ($row['manual_deductions'] ?? 0) > 0 ? '-' . number_format($row['manual_deductions'], 2) : '0.00' }}
                                        </td>
                                        <td class="text-right">
                                            {{ ($row['loan_deductions'] ?? 0) > 0 ? '-' . number_format($row['loan_deductions'], 2) : '0.00' }}
                                        </td>
                                        @php $netVal = (float) ($row['net_salary'] ?? 0); @endphp
                                        <td class="text-right"><b class="{{ $netVal < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($netVal, 2) }}</b></td>
                                        <td>
                                            @if(!empty($row['generated_at']))
                                                <span class="badge badge-payroll badge-payroll-success mr-1">Generated</span>
                                                <form method="post" action="{{ url('payroll/staff?month='.$month.'&year='.$year) }}" style="display:inline-block;" onsubmit="return confirm('Reset generated salary?');">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reset_salary">
                                                    <input type="hidden" name="unique_id" value="{{ $row['unique_id'] }}">
                                                    <button class="btn btn-sm btn-payroll btn-payroll-danger mr-1">Reset</button>
                                                </form>
                                                <a class="btn btn-sm btn-payroll btn-payroll-outline" target="_blank" href="{{ url('payroll/staff/slip-pdf?staff='.$row['unique_id'].'&month='.$month.'&year='.$year) }}">
                                                    <i class="fa fa-file-pdf-o"></i> PDF
                                                </a>
                                            @else
                                                <form method="post" action="{{ url('payroll/staff?month='.$month.'&year='.$year) }}" style="display:inline-block;">
                                                    @csrf
                                                    <input type="hidden" name="action" value="generate_salary">
                                                    <input type="hidden" name="unique_id" value="{{ $row['unique_id'] }}">
                                                    <button class="btn btn-sm btn-payroll btn-payroll-success mr-1">Generate</button>
                                                </form>
                                                <a class="btn btn-sm btn-payroll btn-payroll-outline" href="{{ url('payroll/staff/edit?staff='.$row['unique_id'].'&month='.$month.'&year='.$year) }}" title="Edit Payroll">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-muted">No staff found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(!empty($rows))
                                <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-right">Total</th>
                                        <th class="text-right">{{ number_format($totals['salary_till_now'] ?? 0, 2) }}</th>
                                        <th class="text-right">{{ ($totals['manual_deductions'] ?? 0) > 0 ? '-' . number_format($totals['manual_deductions'], 2) : '0.00' }}</th>
                                        <th class="text-right">{{ ($totals['loan_deductions'] ?? 0) > 0 ? '-' . number_format($totals['loan_deductions'], 2) : '0.00' }}</th>
                                        @php $totalNet = (float) ($totals['net_salary'] ?? 0); @endphp
                                        <th class="text-right"><span class="{{ $totalNet < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($totalNet, 2) }}</span></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    <div class="payroll-inline-note mt-2">
                        Calculation uses Payroll Settings (credits/limits). You can change rules from <b>Payroll Settings</b>.
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Payroll Settings Modal -->
<div class="modal fade payroll-modal" id="payrollSettingsModal" tabindex="-1" role="dialog" aria-labelledby="payrollSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="{{ url('payroll/staff?month='.$month.'&year='.$year) }}">
                @csrf
                <input type="hidden" name="action" value="settings">
                <div class="modal-header">
                    <h5 class="modal-title" id="payrollSettingsModalLabel"><i class="fa fa-sliders"></i> Payroll Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Paid Leave Allowed (Days / Month)</label>
                                <input type="number" min="0" max="31" name="paid_leave_limit" class="form-control form-control-sm"
                                       value="{{ old('paid_leave_limit', $payrollSetting->paid_leave_limit ?? '') }}"
                                       placeholder="Leave blank for Unlimited">
                                <small class="text-muted">If set, extra leave days will be treated as unpaid (0 credit).</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Early Out Credit (Paid Day)</label>
                                @php $earlyOut = old('early_out_weight', $payrollSetting->early_out_weight ?? 1); $earlyOutVal = (float) $earlyOut; @endphp
                                <select name="early_out_weight" class="form-control form-control-sm">
                                    <option value="1.00" {{ $earlyOutVal == 1.0 ? 'selected' : '' }}>1.00 (No Deduction)</option>
                                    <option value="0.50" {{ $earlyOutVal == 0.5 ? 'selected' : '' }}>0.50 (Half Day)</option>
                                    <option value="0.00" {{ $earlyOutVal == 0.0 ? 'selected' : '' }}>0.00 (Full Deduction)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Late Frequency (Every N = Penalty)</label>
                                <input type="number" min="1" max="31" name="late_frequency" class="form-control form-control-sm"
                                       value="{{ old('late_frequency', $payrollSetting->late_frequency ?? '') }}"
                                       placeholder="e.g. 3">
                                <small class="text-muted">Below frequency, late counts as full day credit.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Early Out Frequency (Every N = Penalty)</label>
                                <input type="number" min="1" max="31" name="early_out_frequency" class="form-control form-control-sm"
                                       value="{{ old('early_out_frequency', $payrollSetting->early_out_frequency ?? '') }}"
                                       placeholder="e.g. 3">
                                <small class="text-muted">Below frequency, early-out counts as full day credit.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Leave Credit (Paid Day)</label>
                                @php $leaveW = old('leave_weight', $payrollSetting->leave_weight ?? 1); $leaveVal = (float) $leaveW; @endphp
                                <select name="leave_weight" class="form-control form-control-sm">
                                    <option value="1.00" {{ $leaveVal == 1.0 ? 'selected' : '' }}>1.00 (Paid Leave)</option>
                                    <option value="0.00" {{ $leaveVal == 0.0 ? 'selected' : '' }}>0.00 (Unpaid Leave)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Half Day Credit (Paid Day)</label>
                                @php $halfW = old('halfday_weight', $payrollSetting->halfday_weight ?? 0.5); $halfVal = (float) $halfW; @endphp
                                <select name="halfday_weight" class="form-control form-control-sm">
                                    <option value="0.50" {{ $halfVal == 0.5 ? 'selected' : '' }}>0.50</option>
                                    <option value="1.00" {{ $halfVal == 1.0 ? 'selected' : '' }}>1.00</option>
                                    <option value="0.00" {{ $halfVal == 0.0 ? 'selected' : '' }}>0.00</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Salary Modal -->
<div class="modal fade payroll-modal" id="salaryModal" tabindex="-1" role="dialog" aria-labelledby="salaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form method="post" action="{{ url('payroll/staff?month='.$month.'&year='.$year) }}">
                @csrf
                <input type="hidden" name="action" value="salary">
                <div class="modal-header">
                    <h5 class="modal-title" id="salaryModalLabel"><i class="fa fa-cog"></i> Set Staff Salary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th>Staff</th>
                                    <th>Role</th>
                                    <th style="width:220px;" class="text-right">Set Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sr = 1; @endphp
                                @foreach($staff as $member)
                                    @php
                                        $roleName = $rolesById[$member->role_id]->name ?? '-';
                                        $old = $member->salary ?? '';
                                    @endphp
                                    <tr>
                                        <td>{{ $sr++ }}</td>
                                        <td>{{ trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) }} ({{ trim((string)($member->attendance_unique_id ?? '')) !== '' ? $member->attendance_unique_id : ('USR-' . $member->id) }})</td>
                                        <td>{{ $roleName }}</td>
                                        <td class="text-right">
                                            <input type="number" step="0.01" min="0" name="salary[{{ $member->id }}]" class="form-control form-control-sm text-right" value="{{ $old }}">
                                            <div class="text-muted small mt-1 text-left">
                                                Saved: {{ $old !== '' ? number_format((float)$old, 2) : '-' }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Salary</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        try {
            $('#payrollTable').DataTable({
                pageLength: 25,
                ordering: true,
                searching: true
            });
        } catch (e) {}
    });
</script>

@if($errors->any() && old('action') === 'settings')
    <script>
        $(function(){ $('#payrollSettingsModal').modal('show'); });
    </script>
@endif

@if($errors->any() && old('action') === 'salary')
    <script>
        $(function(){ $('#salaryModal').modal('show'); });
    </script>
@endif

@endsection
