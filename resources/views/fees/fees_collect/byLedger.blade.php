@php
$classType = Helper::classType();
$getPaymentMode = Helper::getPaymentMode();
//dd($data);
@endphp
@extends('layout.app')
@section('content')




<div class="content-wrapper">
    
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-outline card-orange mb-0">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-money"></i> &nbsp;{{ __('Sibling Fee Management') }}</h3>
                            <div class="card-tools">
                                <a href="{{url('fees/index')}}" class="btn btn-primary  btn-sm" title="View Fees"><i class="fa fa-eye"></i>{{ __('common.View') }} </a>
                                <a href="{{url('fee_dashboard')}}" class="btn btn-primary  btn-sm" title="Back"><i class="fa fa-arrow-left"></i>{{ __('common.Back') }} </a>
                            </div>

                        </div>
                     <div class="card-body">
                            <form id="quickForm" method="post" action="{{ url('fees/ledger/collect') }}">
                                @csrf
                                <div class="row m-2">
                           
                                	     <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('Ledger No') }}</label> 
                                                 <input type="text" class="form-control" value="{{$search['ledger_no'] ?? ''}}" id="ledger_no" name="ledger_no" placeholder="{{ __('Type Ledger No.') }}">
                                     
                                          
                                            @error('ledger_no')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                  
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="text-white">{{ __('common.Search') }}</label>
                                            <button type="submit" class="btn btn-primary" id='form_submit'>{{ __('common.Search') }}</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="text-white">{{ __('Ledger Update Update') }}</label>
                                            <a target='_blank'href='{{url("ledger_update")}}'><button type="button" class="btn btn-success">{{ __('Ledger Update') }}</button></a>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                            
                            
                            
                            
   @if(isset($students))

<!-- Top Subtitle Row -->
<div class="ledger-top-row">
    <div class="ledger-top-left">
        <p>
            Consolidated educational billing for 
            {{ $students->pluck('first_name')->implode(', ') }}
            (AY {{ Session::get('session_name') }})
        </p>
    </div>
    <div class="ledger-top-right">
        <span class="dues-status">
            @if(!empty($outstanding))
            {{ $outstanding > 0 ? 'Dues Pending' : 'Paid' }}
            @endif
        </span>
    </div>
</div>


<div class="ledger-grid">

<!-- LEFT COLUMN -->
<div class="ledger-left">

<div class="student-row">
@foreach($students as $student)

@php
$studentFee = \App\Models\fees\FeesAssignDetail::where('admission_id',$student->id)
                ->sum('fees_group_amount');
@endphp

<div class="student-card glass-card">
    <div class="student-avatar">
        <img src="{{ env('IMAGE_SHOW_PATH').'profile/'.$student['image'] }}"
                                                    onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/user_image.jpg' }}'">
    </div>

    <h4>{{ $student->first_name }}</h4>
    <p class="student-class">{{ $student->class_name ?? '' }}</p>

    <div class="fee-section">
        <span>ANNUAL FEE</span>
        <h5>₹{{ number_format($studentFee) }}</h5>
    </div>
</div>

@endforeach
</div>


<!-- Payment History -->
@if(isset($students) && $students->count())

<div class="payment-history-card glass-card">

<h5 class="mb-3">Payment History</h5>

@foreach($students as $student)

    @if(isset($student->paid_records) && $student->paid_records->count())

    <div style="margin-top:25px; padding:10px; background:#1e293b; border-radius:8px;color: #fff;">
        <strong style="font-size:16px;">
            {{ $student->first_name }} 
            ({{ $student->class_name ?? '' }})
        </strong>
    </div>

    <table class="payment-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Receipt</th>
                <th>Group Name</th>
                <th>Paid Amount</th>
            </tr>
        </thead>
        <tbody>

        @foreach($student->paid_records ?? [] as $pay)
        <tr>
            <td>{{ date('d M Y', strtotime($pay->created_at)) }}</td>
            <td>{{ $pay->receipt_no ?? '-' }}</td>
            <td>{{ $pay->group_name ?? '-' }}</td>
            <td>₹{{ number_format($pay->total_amount) }}</td>
        </tr>
        @endforeach

        </tbody>
    </table>

    @endif

@endforeach

</div>

@endif

</div>


<!-- RIGHT COLUMN -->
<div class="ledger-right">

<!-- Financial Summary -->
<div class="financial-summary-card glass-card">

<h6 class="summary-title">Financial Summary</h6>

<div class="summary-row">
    <span>Combined Base Fee</span>
    <span>₹{{ number_format($annualFees ?? 0) }}</span>
</div>

<div class="summary-row discount">
    <span>Sibling Discount</span>
    <span> ₹{{ number_format($totalDiscount ?? 0) }}</span>
</div>

<div class="summary-total">
    <p>Final Negotiated Amount</p>
    <h3>₹{{ number_format($finalAmount ?? 0) }}</h3>
    <span class="year-badge">
        AY {{ Session::get('session_name') }}
    </span>
</div>

</div>


<!-- Outstanding -->
<div class="outstanding-card glass-card">

<div class="outstanding-header">
    <span>Outstanding Balance</span>
</div>

<div class="outstanding-amount">
    <h2>₹{{ number_format($outstanding ?? 0) }}</h2>
    <span class="pending-text">
          @if(!empty($outstanding))
            {{ $outstanding > 0 ? ' Pending' : 'Paid' }}
            @endif
    </span>
</div>

<div class="progress-section">
    <div class="progress-bar">
        <div class="progress-fill" 
             style="width:{{ $progress ?? 0 }}%;">
        </div>
    </div>

    <div class="progress-info">
        <span>Payment Progress</span>
        <span>{{ $progress ?? 0}}%</span>
    </div>
</div>

<form action="{{ url('ledger/pay') }}" method="post">
@csrf

<input type="hidden" name="ledger_no" value="{{ $search['ledger_no'] ?? '' }}">

<!-- 🔹 Single Pay Amount Input -->
<div style="margin-top:20px;">
    <label><strong>Enter Payment Amount</strong></label>
    <input type="number"
           name="pay_amount"
           class="form-control"
           placeholder="Enter Amount"
           min="0"
           required>
</div>

<br>

<button type="submit" class="pay-button">
    Pay Now
</button>

</form>

</div>


<!-- Help Section -->
<div class="help-card glass-card">
<h6>Need assistance?</h6>
<p>
Contact the accounts office for scholarship or installment queries.
</p>
<a href="mailto:accounts@school.com">
Contact Accounts Department
</a>
</div>

</div>
</div>

@endif


</div> <!-- END card-body -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>




<style>

/* =========================
   BASE BACKGROUND
========================= */

.content-wrapper {
    background: #f1f5f9;
}

.card-body {
    padding: 25px;
}

/* =========================
   TOP ROW
========================= */

.ledger-top-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.ledger-top-row p {
    font-size: 14px;
    color: #64748b;
    margin: 0;
}

.dues-status {
    font-size: 13px;
    font-weight: 600;
    color: #047857;
}

/* =========================
   GRID LAYOUT (IMPORTANT)
========================= */

.ledger-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 32px;
}

/* =========================
   STUDENT CARDS
========================= */

.student-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 32px;
}

.student-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: 0.2s ease;
}

.student-card:hover {
    border-color: #c7d2fe;
}

.student-avatar {
    width: 80px;
    height: 80px;
    margin: 0 auto 15px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #f8fafc;
}

.student-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.student-card h4 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 4px;
}

.student-class {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 15px;
}

.fee-section {
    border-top: 1px solid #f1f5f9;
    padding-top: 12px;
}

.fee-section span {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #94a3b8;
}

.fee-section h5 {
    font-size: 20px;
    font-weight: 800;
    margin-top: 6px;
}

/* =========================
   PAYMENT HISTORY
========================= */

.payment-history-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.payment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #f1f5f9;
}

.payment-header h5 {
    font-weight: 600;
    margin: 0;
}

.payment-header a {
    font-size: 13px;
    font-weight: 600;
    color: #2563eb;
}

.payment-table {
    width: 100%;
    border-collapse: collapse;
}

.payment-table th {
    padding: 14px 24px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #94a3b8;
    text-align: left;
}

.payment-table td {
    padding: 16px 24px;
    font-size: 14px;
    border-top: 1px solid #f1f5f9;
}

.payment-table tfoot td {
    font-weight: 700;
    background: #f8fafc;
}

.text-right {
    text-align: right;
}

/* =========================
   RIGHT COLUMN STACK
========================= */

.ledger-right {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* =========================
   FINANCIAL SUMMARY
========================= */

.financial-summary-card {
    background: #1f5fa9;
    color: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 8px 20px rgba(31,95,169,0.25);
}

.summary-title {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 20px;
    opacity: 0.8;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    margin-bottom: 12px;
}

.summary-row.discount {
    color: #86efac;
}

.summary-total {
    border-top: 1px solid rgba(255,255,255,0.3);
    padding-top: 14px;
    margin-top: 14px;
}

.summary-total p {
    font-size: 12px;
    opacity: 0.8;
    margin-bottom: 5px;
}

.summary-total h3 {
    font-size: 28px;
    font-weight: 800;
    margin: 0;
}

.year-badge {
    display: inline-block;
    margin-top: 8px;
    font-size: 11px;
    background: rgba(255,255,255,0.2);
    padding: 4px 8px;
    border-radius: 6px;
}

/* =========================
   OUTSTANDING CARD
========================= */

.outstanding-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.outstanding-header {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    color: #64748b;
    margin-bottom: 10px;
}

.outstanding-amount {
    display: flex;
    align-items: baseline;
    gap: 10px;
}

.outstanding-amount h2 {
    font-size: 30px;
    font-weight: 800;
    margin: 0;
}

.pending-text {
    font-size: 13px;
    color: #dc2626;
    font-weight: 600;
}

.progress-bar {
    background: #e2e8f0;
    height: 6px;
    border-radius: 999px;
    margin-top: 15px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: #1f5fa9;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #94a3b8;
    margin-top: 8px;
}

.pay-button {
    width: 100%;
    margin-top: 16px;
    padding: 10px;
    background: #1f5fa9;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.pay-button:hover {
    background: #184c86;
}

/* =========================
   HELP CARD
========================= */

.help-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px dashed #cbd5e1;
    padding: 18px;
}

.help-card h6 {
    font-weight: 600;
    margin-bottom: 6px;
}

.help-card p {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 8px;
}

.help-card a {
    font-size: 13px;
    font-weight: 600;
    color: #2563eb;
}



@media (max-width: 1024px) {

    .ledger-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .ledger-right {
        order: 2;
        max-width: 80%;
    }

    .ledger-left {
        order: 1;
    }

    .student-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {

    .card-body {
        padding: 15px;
    }

    .ledger-top-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .student-row {
        grid-template-columns: 1fr;
    }

    .student-card {
        padding: 16px;
        max-width: 80%;
    }
.payment-history-card{
    max-width: 80%;
}
    .student-avatar {
        width: 70px;
        height: 70px;
    }

    .financial-summary-card,
    .outstanding-card,
    .help-card {
        padding: 18px;
    }

    .payment-table th,
    .payment-table td {
        padding: 12px 14px;
        font-size: 13px;
    }

    .outstanding-amount h2 {
        font-size: 24px;
    }

    .summary-total h3 {
        font-size: 22px;
    }
}

@media (max-width: 480px) {

    .student-avatar {
        width: 60px;
        height: 60px;
    }

    .fee-section h5 {
        font-size: 18px;
    }

    .pay-button {
        padding: 12px;
        font-size: 14px;
    }

    .payment-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
}


</style>

@endsection