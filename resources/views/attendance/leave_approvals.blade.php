@extends('layout.app')
@section('content')

@include('attendance.theme')

<div class="content-wrapper attendance-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h3 class="card-title mb-0"><i class="fa fa-check-square-o"></i> &nbsp;Leave Approvals</h3>
                        <div class="text-white-50">Approve/Reject self attendance leave requests</div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="get" action="{{ url('attendance/leave/approvals') }}" class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <select name="status" class="form-control form-control-sm">
                                    <option value="2" {{ $statusFilter === '2' ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ $statusFilter === '1' ? 'selected' : '' }}>Approved</option>
                                    <option value="0" {{ $statusFilter === '0' ? 'selected' : '' }}>Rejected</option>
                                    <option value="3" {{ $statusFilter === '3' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-primary">Load</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User Type</th>
                                    <th>Name</th>
                                    <th>Attendance ID</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rows as $row)
                                    <tr>
                                        <td>{{ $row->id }}</td>
                                        <td>{{ strtoupper((string) $row->user_type) }}</td>
                                        <td>{{ $row->person_name ?? '-' }}</td>
                                        <td>{{ $row->attendance_unique_id }}</td>
                                        <td>{{ !empty($row->from_date) ? date('d/m/Y', strtotime($row->from_date)) : '-' }}</td>
                                        <td>{{ !empty($row->to_date) ? date('d/m/Y', strtotime($row->to_date)) : '-' }}</td>
                                        <td>{{ $row->reason ?: '-' }}</td>
                                        <td><span class="badge {{ $row->status_class }}">{{ $row->status_label }}</span></td>
                                        <td>
                                            @if((string) $row->status === '2')
                                                <form method="post" action="{{ url('attendance/leave/approvals/action') }}" style="display:inline-block;">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="leave_id" value="{{ $row->id }}">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button class="btn btn-sm btn-success" onclick="return confirm('Approve this leave request?')">Approve</button>
                                                </form>
                                                <form method="post" action="{{ url('attendance/leave/approvals/action') }}" style="display:inline-block;">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="leave_id" value="{{ $row->id }}">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Reject this leave request?')">Reject</button>
                                                </form>
                                            @endif

                                            @if((string) $row->status === '1')
                                                <form method="post" action="{{ url('attendance/leave/approvals/action') }}" style="display:inline-block;">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="leave_id" value="{{ $row->id }}">
                                                    <input type="hidden" name="action" value="cancel">
                                                    <button class="btn btn-sm btn-warning" onclick="return confirm('Cancel this approved leave?')">Cancel</button>
                                                </form>
                                            @endif

                                            <form method="post" action="{{ url('attendance/leave/approvals/action') }}" style="display:inline-block;">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="leave_id" value="{{ $row->id }}">
                                                <input type="hidden" name="action" value="delete">
                                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this leave request?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No leave requests found.</td>
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
@endsection
