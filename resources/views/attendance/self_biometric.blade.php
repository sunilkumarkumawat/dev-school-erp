@extends('layout.app')
@section('content')

@include('attendance.theme')

<div class="content-wrapper attendance-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="card card-outline card-orange self-card">
                <div class="card-header bg-primary d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h3 class="card-title mb-0"><i class="fa fa-user"></i> &nbsp;Self Attendance (Biometric)</h3>
                        <div class="text-white-50">Mark your own attendance</div>
                    </div>
                    <div class="self-chip">{{ $displayName }} ({{ $uniqueId }})</div>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ url('attendance/self') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label>Check In</label>
                                <input type="time" name="in_time" class="form-control" value="{{ $mark->in_time ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label>Check Out</label>
                                <input type="time" name="out_time" class="form-control" value="{{ $mark->out_time ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="present" {{ ($mark->status ?? '') === 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ ($mark->status ?? '') === 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="leave" {{ ($mark->status ?? '') === 'leave' ? 'selected' : '' }}>Leave</option>
                                    <option value="late" {{ ($mark->status ?? '') === 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="early_out" {{ ($mark->status ?? '') === 'early_out' ? 'selected' : '' }}>Early out</option>
                                    <option value="halfday" {{ ($mark->status ?? '') === 'halfday' ? 'selected' : '' }}>Halfday</option>
                                    <option value="holiday" {{ ($mark->status ?? '') === 'holiday' ? 'selected' : '' }}>Holiday</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary">Save</button>
                            <a href="{{ url('attendance/self') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
