@extends('layout.app')
@section('content')

@include('payroll.theme')

@php
    $loanCount = is_countable($rows ?? null) ? count($rows) : 0;
    $activeCount = collect($rows ?? [])->where('is_active', true)->count();
    $closedCount = collect($rows ?? [])->where('is_active', false)->count();
    $totalRemaining = collect($rows ?? [])->sum('remaining');
@endphp

<div class="content-wrapper payroll-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="payroll-hero">
                <div class="payroll-hero-inner">
                    <div>
                        <div class="payroll-hero-kicker">Payroll Center</div>
                        <div class="payroll-hero-title">Loan / Advance Report</div>
                        <div class="payroll-hero-subtitle">All staff loans and advances with remaining balance.</div>
                        <div class="payroll-hero-chips">
                            <span class="payroll-chip"><i class="fa fa-list"></i> Total: {{ $loanCount }}</span>
                            <span class="payroll-chip"><i class="fa fa-check-circle"></i> Active: {{ $activeCount }}</span>
                            <span class="payroll-chip"><i class="fa fa-archive"></i> Closed: {{ $closedCount }}</span>
                        </div>
                    </div>
                    <div class="payroll-hero-actions">
                        <button type="button" class="btn btn-sm btn-payroll btn-payroll-light" data-toggle="modal" data-target="#loanAddModal">
                            <i class="fa fa-plus"></i> Add Loan/Advance
                        </button>
                        <a href="{{ url('payroll/staff') }}" class="btn btn-sm btn-payroll btn-payroll-light">
                            <i class="fa fa-arrow-left"></i> Back to Payroll
                        </a>
                    </div>
                </div>
            </div>

            <div class="payroll-stats">
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Total Loans</div>
                    <div class="payroll-stat-value">{{ $loanCount }}</div>
                    <div class="payroll-stat-sub">Records in system</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Active Loans</div>
                    <div class="payroll-stat-value">{{ $activeCount }}</div>
                    <div class="payroll-stat-sub">Open balances</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Closed Loans</div>
                    <div class="payroll-stat-value">{{ $closedCount }}</div>
                    <div class="payroll-stat-sub">Paid off</div>
                </div>
                <div class="payroll-stat">
                    <div class="payroll-stat-label">Remaining Balance</div>
                    <div class="payroll-stat-value">{{ number_format((float)$totalRemaining, 2) }}</div>
                    <div class="payroll-stat-sub">All staff</div>
                </div>
            </div>

            <div class="card card-outline card-orange payroll-card">
                <div class="card-header payroll-card-header">
                    <div>
                        <div class="payroll-card-title"><i class="fa fa-list"></i> Loan Register</div>
                        <div class="payroll-inline-note">Track advances, deductions, and remaining balances.</div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center" style="gap:8px;">
                        <span class="badge-soft">Remaining: {{ number_format((float)$totalRemaining, 2) }}</span>
                        <span class="badge-soft">Active: {{ $activeCount }}</span>
                    </div>
                </div>

                <div class="card-body">
                    <form method="get" action="{{ url('payroll/staff/loans') }}">
                        <div class="d-flex flex-wrap align-items-center payroll-filter" style="gap:10px;">
                            <select name="type" class="form-control form-control-sm" style="min-width:160px;">
                                <option value="" {{ $typeFilter === '' ? 'selected' : '' }}>All Types</option>
                                <option value="loan" {{ $typeFilter === 'loan' ? 'selected' : '' }}>Loan</option>
                                <option value="advance" {{ $typeFilter === 'advance' ? 'selected' : '' }}>Advance</option>
                            </select>

                            <select name="status" class="form-control form-control-sm" style="min-width:160px;">
                                <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="closed" {{ $statusFilter === 'closed' ? 'selected' : '' }}>Closed</option>
                                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All</option>
                            </select>

                            <button class="btn btn-sm btn-payroll btn-payroll-primary">Load</button>

                        </div>
                    </form>


                    <div class="table-responsive mt-3">
                        <table class="table table-bordered payroll-table" id="loanTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Staff</th>
                                    <th>Unique ID</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-right">Monthly Deduct</th>
                                    <th>Start</th>
                                    <th class="text-right">Deducted</th>
                                    <th class="text-right">Paid</th>
                                    <th class="text-right">Remaining</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rows as $r)
                                    <tr>
                                        <td>{{ $r['id'] }}</td>
                                        <td>{{ $r['name'] }}</td>
                                        <td>{{ $r['display_unique_id'] ?? $r['unique_id'] }}</td>
                                        <td>{{ ucfirst($r['type']) }}</td>
                                        <td>{{ $r['title'] ?? '-' }}</td>
                                        <td class="text-right">{{ number_format($r['principal_amount'], 2) }}</td>
                                        <td class="text-right">{{ number_format($r['monthly_deduction'], 2) }}</td>
                                        <td>{{ $r['start'] }}</td>
                                        <td class="text-right">{{ number_format($r['deducted'], 2) }}</td>
                                        <td class="text-right">{{ number_format($r['paid'] ?? 0, 2) }}</td>
                                        <td class="text-right"><b>{{ number_format($r['remaining'], 2) }}</b></td>
                                        <td>
                                            @if($r['is_active'])
                                                <span class="badge badge-payroll badge-payroll-success">Active</span>
                                            @else
                                                <span class="badge badge-payroll badge-payroll-muted">Closed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button
                                                class="btn btn-sm btn-payroll btn-payroll-outline btn-edit-loan"
                                                data-toggle="modal"
                                                data-target="#loanEditModal"
                                                data-loan-id="{{ $r['id'] }}"
                                                data-unique-id="{{ $r['unique_id'] }}"
                                                data-type="{{ $r['type'] }}"
                                                data-title="{{ $r['title'] }}"
                                                data-remark="{{ $r['remark'] ?? '' }}"
                                                data-amount="{{ $r['principal_amount'] }}"
                                                data-monthly="{{ $r['monthly_deduction'] }}"
                                                data-start="{{ $r['start'] }}"
                                                data-active="{{ $r['is_active'] ? 1 : 0 }}"
                                            >
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button
                                                class="btn btn-sm btn-payroll btn-payroll-outline btn-pay-loan"
                                                data-toggle="modal"
                                                data-target="#loanPayModal"
                                                data-loan-id="{{ $r['id'] }}"
                                                data-remaining="{{ $r['remaining'] }}"
                                            >
                                                <i class="fa fa-credit-card"></i>
                                            </button>
                                            @if($r['is_active'])
                                                <form method="post" action="{{ url('payroll/staff/loans') }}" style="display:inline-block;" onsubmit="return confirm('Close this loan/advance?');">
                                                    @csrf
                                                    <input type="hidden" name="action" value="close_loan">
                                                    <input type="hidden" name="loan_id" value="{{ $r['id'] }}">
                                                    <button class="btn btn-sm btn-payroll btn-payroll-danger"><i class="fa fa-times"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center text-muted">No data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Loan/Advance Modal -->
<div class="modal fade payroll-modal" id="loanAddModal" tabindex="-1" role="dialog" aria-labelledby="loanAddModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="{{ url('payroll/staff/loans') }}">
                @csrf
                <input type="hidden" name="action" value="add_loan">
                <div class="modal-header">
                    <h5 class="modal-title" id="loanAddModalLabel"><i class="fa fa-plus"></i> Add Loan / Advance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Staff</label>
                                <select name="unique_id" class="form-control form-control-sm">
                                    @foreach($staffList as $s)
                                        <option value="{{ 'USR-' . $s->id }}">
                                            {{ trim(($s->first_name ?? '').' '.($s->last_name ?? '')) }} ({{ trim((string)($s->attendance_unique_id ?? '')) !== '' ? $s->attendance_unique_id : ('USR-' . $s->id) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="loan_type" class="form-control form-control-sm">
                                    <option value="advance">Advance</option>
                                    <option value="loan">Loan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" step="0.01" min="0" name="principal_amount" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Monthly Deduct</label>
                                <input type="number" step="0.01" min="0" name="monthly_deduction" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Start Month</label>
                                <select name="start_month" class="form-control form-control-sm">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}">{{ date('M', mktime(0,0,0,$m,1)) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Start Year</label>
                                <select name="start_year" class="form-control form-control-sm">
                                    @for($y = date('Y')-3; $y <= date('Y')+1; $y++)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Remark</label>
                                <input type="text" name="remark" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-payroll btn-payroll-outline" data-dismiss="modal">Close</button>
                    <button class="btn btn-payroll btn-payroll-primary"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Loan/Advance Modal -->
<div class="modal fade payroll-modal" id="loanEditModal" tabindex="-1" role="dialog" aria-labelledby="loanEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="{{ url('payroll/staff/loans') }}">
                @csrf
                <input type="hidden" name="action" value="update_loan">
                <input type="hidden" name="loan_id" id="edit_loan_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="loanEditModalLabel"><i class="fa fa-edit"></i> Edit Loan / Advance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Staff</label>
                                <select name="unique_id" id="edit_unique_id" class="form-control form-control-sm">
                                    @foreach($staffList as $s)
                                        <option value="{{ 'USR-' . $s->id }}">
                                            {{ trim(($s->first_name ?? '').' '.($s->last_name ?? '')) }} ({{ trim((string)($s->attendance_unique_id ?? '')) !== '' ? $s->attendance_unique_id : ('USR-' . $s->id) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Type</label>
                                <select name="loan_type" id="edit_type" class="form-control form-control-sm">
                                    <option value="advance">Advance</option>
                                    <option value="loan">Loan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="is_active" id="edit_active" class="form-control form-control-sm">
                                    <option value="1">Active</option>
                                    <option value="0">Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" step="0.01" min="0" name="principal_amount" id="edit_amount" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Monthly Deduct</label>
                                <input type="number" step="0.01" min="0" name="monthly_deduction" id="edit_monthly" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Start Month</label>
                                <select name="start_month" id="edit_start_month" class="form-control form-control-sm">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}">{{ date('M', mktime(0,0,0,$m,1)) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Start Year</label>
                                <select name="start_year" id="edit_start_year" class="form-control form-control-sm">
                                    @for($y = date('Y')-3; $y <= date('Y')+1; $y++)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" id="edit_title" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Remark</label>
                                <input type="text" name="remark" id="edit_remark" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-payroll btn-payroll-outline" data-dismiss="modal">Close</button>
                    <button class="btn btn-payroll btn-payroll-primary"><i class="fa fa-save"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loan Payment Modal -->
<div class="modal fade payroll-modal" id="loanPayModal" tabindex="-1" role="dialog" aria-labelledby="loanPayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form method="post" action="{{ url('payroll/staff/loans') }}">
                @csrf
                <input type="hidden" name="action" value="add_payment">
                <input type="hidden" name="loan_id" id="pay_loan_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="loanPayModalLabel"><i class="fa fa-credit-card"></i> Loan/Advance Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Payment Date</label>
                        <input type="date" name="payment_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" step="0.01" min="0" name="amount" id="pay_amount" class="form-control form-control-sm" required>
                        <small class="text-muted">Remaining: <span id="pay_remaining">0.00</span></small>
                    </div>
                    <div class="form-group">
                        <label>Remark</label>
                        <input type="text" name="remark" class="form-control form-control-sm" placeholder="Optional">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-payroll btn-payroll-outline" data-dismiss="modal">Close</button>
                    <button class="btn btn-payroll btn-payroll-primary"><i class="fa fa-save"></i> Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        try {
            $('#loanTable').DataTable({
                pageLength: 25,
                ordering: true,
                searching: true
            });
        } catch (e) {}

        $('.btn-edit-loan').on('click', function(){
            var start = ($(this).data('start') || '').split('/');
            var startMonth = start.length > 0 ? parseInt(start[0], 10) : '';
            var startYear = start.length > 1 ? parseInt(start[1], 10) : '';

            $('#edit_loan_id').val($(this).data('loan-id'));
            $('#edit_unique_id').val($(this).data('unique-id'));
            $('#edit_type').val($(this).data('type'));
            $('#edit_amount').val($(this).data('amount'));
            $('#edit_monthly').val($(this).data('monthly'));
            $('#edit_start_month').val(startMonth);
            $('#edit_start_year').val(startYear);
            $('#edit_active').val($(this).data('active'));
            $('#edit_title').val($(this).data('title'));
            $('#edit_remark').val($(this).data('remark'));
        });

        $('.btn-pay-loan').on('click', function(){
            var remaining = parseFloat($(this).data('remaining') || 0);
            $('#pay_loan_id').val($(this).data('loan-id'));
            $('#pay_amount').val(remaining > 0 ? remaining.toFixed(2) : '');
            $('#pay_remaining').text(remaining.toFixed(2));
        });
    });
</script>

@endsection
