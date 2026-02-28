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
                        <h3 class="card-title mb-0 att-title"><i class="fa fa-qrcode"></i> &nbsp;QR Attendance</h3>
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
                    <p class="att-subtext">This page will be enabled later.</p>
                    <div class="att-panel">QR attendance setup is coming soon.</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
