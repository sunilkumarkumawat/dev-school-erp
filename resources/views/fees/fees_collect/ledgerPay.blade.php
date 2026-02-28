@php
$classType = Helper::classType();
$getPaymentMode = Helper::getPaymentMode();
//dd($data);
@endphp
@extends('layout.app')
@section('content')

<style>
    /* =========================
   BASE WRAPPER
========================= */

.collect-wrapper {
    max-width: 1100px;
    margin: 0 auto;
    padding: 20px;
}

.collect-header {
        display: flex;
    /* flex-direction: column; */
    /* gap: 8px; */
    margin-bottom: 25px;
    justify-content: space-between;
}

.collect-header h2 {
    margin: 0;
    font-size: 28px;
}

.collect-header p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
}

.back-link {
    font-size: 14px;
    color: #2563eb;
    text-decoration: none;
    font-weight: bold;
}

/* =========================
   MAIN CARD
========================= */

.collect-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 25px;
    border: 1px solid #e2e8f0;
    margin-bottom: 30px;
}

/* =========================
   STUDENT BLOCK
========================= */

.student-block {
    margin-bottom: 30px;
    padding-left: 18px;
    border-left: 4px solid transparent;
}

.student-block.blue {
    border-color: #2563eb;
}

.student-block.pink {
    border-color: #ec4899;
}

.student-block.orange {
    border-color: #f97316;
}

.student-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.student-title h4 {
    margin: 0;
    font-weight:bold;
}

/* =========================
   FEE ROW GRID
========================= */

.fee-row {
    display: grid;
    grid-template-columns: 1fr 120px 120px 120px;
    gap: 15px;
    align-items: center;
    margin-bottom: 12px;
}

.header-row {
    font-weight: 600;
    color: #64748b;
    font-size: 13px;
}

/* =========================
   INPUTS
========================= */

input[type="number"],
input[type="text"],
input[type="date"],
select {
    width: 100%;
    padding: 8px 10px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
}

input:focus,
select:focus {
    outline: none;
    border-color: #2563eb;
}

/* =========================
   PAYMENT CONTROLS
========================= */

.payment-controls {
    margin-top: 20px;
}

.control-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-size: 13px;
    margin-bottom: 6px;
}

.form-group .required {
    color: red;
}

.full-width {
    grid-column: span 3;
}

/* =========================
   PAY BUTTON
========================= */

.pay-action {
    text-align: center;
}

.main-pay-btn {
    width: 100%;
    padding: 14px;
    border-radius: 12px;
    border: none;
    font-size: 16px;
    font-weight: 600;
    background: linear-gradient(to right, #2563eb, #1d4ed8);
    color: white;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(37,99,235,0.3);
}

.main-pay-btn:hover {
    opacity: 0.95;
}

.secure-text {
    margin-top: 10px;
    font-size: 12px;
    color: #64748b;
}

/* =========================
   RESPONSIVE
========================= */

/* Tablet */
@media (max-width: 992px) {

    .fee-row {
        grid-template-columns: 1fr 1fr;
    }

    .header-row {
        display: none;
    }

    .control-row {
        grid-template-columns: repeat(2, 1fr);
    }

    .full-width {
        grid-column: span 2;
    }

}

/* Mobile */
@media (max-width: 576px) {

    .collect-card {
        padding: 18px;
    }

    .fee-row {
        grid-template-columns: 1fr;
    }

    .control-row {
        grid-template-columns: 1fr;
    }

    .full-width {
        grid-column: span 1;
    }

    .collect-header {
        align-items: flex-start;
    }

}

</style>
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
                <div class="collect-wrapper">

    <!-- Page Title -->
    <div class="collect-header">
        <div>
            <h2>Make a Payment</h2>
            <p>Complete your fee payment securely</p>
        </div>
        <a href="#" class="back-link">← Back to Dashboard</a>
    </div>

    <div class="collect-card">

        <!-- Student 1 -->
        <div class="student-block blue">

            <div class="student-title">
                <span class="student-dot"></span>
                <h4> <i class="fa fa-user" style="color:blue;"></i>  &nbsp;  &nbsp;Suresh (Grade 10)</h4>
            </div>

            <div class="fee-row header-row">
                <div>Fee Head</div>
                <div>Amount</div>
                <div>Discount</div>
                <div>Fine</div>
            </div>

            <div class="fee-row">
                <div>Tuition Fee</div>
                <div><input type="number" class="amount-input" value="8000"></div>
                <div><input type="number" class="discount-input" value="0"></div>
                <div><input type="number" class="fine-input" value="0"></div>
            </div>

            <div class="fee-row">
                <div>Bus Fee</div>
                <div><input type="number" class="amount-input" value="2000"></div>
                <div><input type="number" class="discount-input" value="0"></div>
                <div><input type="number" class="fine-input" value="0"></div>
            </div>

        </div>
<hr>
        <!-- Student 2 -->
        <div class="student-block pink">

            <div class="student-title">
                <span class="student-dot"></span>
                <h4><i class="fa fa-user" style="color:pink;"></i>  &nbsp;  &nbsp;Priyanka (Grade 8)</h4>
            </div>

            <div class="fee-row">
                <div>Tuition Fee</div>
                <div><input type="number" class="amount-input" value="6000"></div>
                <div><input type="number" class="discount-input" value="0"></div>
                <div><input type="number" class="fine-input" value="0"></div>
            </div>

            <div class="fee-row">
                <div>Lab Fee</div>
                <div><input type="number" class="amount-input" value="1000"></div>
                <div><input type="number" class="discount-input" value="0"></div>
                <div><input type="number" class="fine-input" value="0"></div>
            </div>

        </div>
<hr>
        <!-- Student 3 -->
        <div class="student-block orange">

            <div class="student-title">
                <span class="student-dot"></span>
                <h4><i class="fa fa-user" style="color:orange;"></i> &nbsp;  &nbsp; Ramesh (Grade 4)</h4>
            </div>

            <div class="fee-row">
                <div>Tuition Fee</div>
                <div><input type="number" class="amount-input" value="2500"></div>
                <div><input type="number" class="discount-input" value="0"></div>
                <div><input type="number" class="fine-input" value="0"></div>
            </div>

            <div class="fee-row">
                <div>Activity Fee</div>
                <div><input type="number" class="amount-input" value="500"></div>
                <div><input type="number" class="discount-input" value="0"></div>
                <div><input type="number" class="fine-input" value="0"></div>
            </div>

        </div>
<hr>
        <!-- Payment Controls Section -->
        <div class="payment-controls">

            <div class="control-row">

                <div class="form-group">
                    <label class="required">Payment Mode</label>
                    <select class="form-control" id="paymentMode">
                        <option value="Cash">Cash</option>
                        <option value="UPI">UPI</option>
                        <option value="Card">Card</option>
                        <option value="Net Banking">Net Banking</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Payment Status</label>
                    <select class="form-control" id="paymentStatus">
                        <option value="Payment Receive">Payment Receive</option>
                        <option value="Pending">Pending</option>
                        <option value="Partial">Partial</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Total</label>
                    <input type="text" id="grandTotal" class="form-control" value="20000.00" readonly>
                </div>

                <div class="form-group">
                    <label>Total Fine</label>
                    <input type="text" id="totalFine" class="form-control" value="0.00" readonly>
                </div>

            </div>

            <div class="control-row">

                <div class="form-group">
                    <label class="required">Payment Date</label>
                    <input type="date" class="form-control" value="2026-02-12">
                </div>

                <div class="form-group full-width">
                    <label>Remark</label>
                    <input type="text" class="form-control" placeholder="Remark">
                </div>

            </div>

        </div>

    </div> <!-- END collect-card -->

    <!-- Pay Button -->
    <div class="pay-action">
        <button type="button" class="main-pay-btn">
            🔒 Proceed to Pay ₹20,000
        </button>
        <p class="secure-text">PCI-DSS Compliant Secure Checkout</p>
    </div>

</div> <!-- END collect-wrapper -->

            </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const amountInputs   = document.querySelectorAll(".amount-input");
    const discountInputs = document.querySelectorAll(".discount-input");
    const fineInputs     = document.querySelectorAll(".fine-input");

    const grandTotalField = document.getElementById("grandTotal");
    const totalFineField  = document.getElementById("totalFine");
    const payButton       = document.querySelector(".main-pay-btn");

    function calculateTotals() {

        let totalAmount = 0;
        let totalFine   = 0;

        amountInputs.forEach((amountInput, index) => {

            let amount   = parseFloat(amountInput.value) || 0;
            let discount = parseFloat(discountInputs[index].value) || 0;
            let fine     = parseFloat(fineInputs[index].value) || 0;

            let rowTotal = amount - discount + fine;

            totalAmount += rowTotal;
            totalFine   += fine;

        });

        grandTotalField.value = totalAmount.toFixed(2);
        totalFineField.value  = totalFine.toFixed(2);

        // Update button text
        payButton.innerHTML = "🔒 Proceed to Pay ₹" + totalAmount.toLocaleString();

    }

    // Add event listeners
    [...amountInputs, ...discountInputs, ...fineInputs].forEach(input => {
        input.addEventListener("input", calculateTotals);
    });

    // Initial calculation
    calculateTotals();

});

</script>

@endsection