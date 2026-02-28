@php
    $getHostel = Helper::getHostel();
    $getPaymentMode = Helper::getPaymentMode();
    $getRole = Helper::getUsers();
@endphp

@extends('layout.app') 
@section('content')

<div class="content-wrapper">
  <section class="content pt-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-outline card-orange">
            <div class="card-header bg-primary">
              <h3 class="card-title"><i class="fa fa-credit-card"></i> &nbsp;{{ __('expense.Add Expense') }}</h3>
              <div class="card-tools">
                <a href="{{url('expenseView')}}" class="btn btn-primary btn-sm">
                  <i class="fa fa-eye"></i> {{ __('common.View') }}
                </a>
              </div>
            </div>
            <form id="quickForm" action="{{ url('expenseAdd') }}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-6 col-md-3">
                        <label class="text-danger">{{ __('common.Date') }}*</label>
                        <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="text-danger"><b>{{ __('User Name') }}*</b></label>
                        <select class="form-control" name="role" required>
                            <option value="">Select</option>
                            @foreach($getRole as $item)
                                <option value="{{ $item->id }}">{{ $item->first_name }} {{ $item->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="g-3 mt-3 border rounded p-3 " id="form-rows-container">
                  <div class="row expense-entry" id="appendRow_0">
                    <div class="col-6 col-md-2">
                        <label class="text-danger"><b>{{ __('Category') }}*</b></label>
                      <select class="form-control" name="category[]" required>
                            <option value="">Select</option>
                            <option value="Salary">Salary</option>
                            <option value="Loan Payments">Loan Payments</option>
                            <option value="Mobile Bill & Recharge">Mobile Bill &amp; Recharge</option>
                            <option value="School Building Maintenance">School Building Maintenance</option>
                            <option value="Computer & Electronics">Computer &amp; Electronics</option>
                            <option value="Laboratory Expenses">Laboratory Expenses</option>
                            <option value="Furniture Expense">Furniture Expense</option>
                            <option value="Fuel & Gas">Fuel &amp; Gas</option>
                            <option value="Printing & Stationery Items">Printing &amp; Stationery Items</option>
                            <option value="Donations And Taxes">Donations And Taxes</option>
                            <option value="Electricity Bills">Electricity Bills</option>
                            <option value="Internet Bills">Internet Bills</option>
                            <option value="Water Bills">Water Bills</option>
                            <option value="Staff Welfare Expenses">Staff Welfare Expenses</option>
                            <option value="Rent Expenses">Rent Expenses</option>
                            <option value="Event Expenses">Event Expenses</option>
                            <option value="House Expenses">House Expenses</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Insurance">Insurance</option>
                            <option value="Education & Tuition">Education &amp; Tuition</option>
                            <option value="Sports Goods">Sports Goods</option>
                            <option value="Other Charges">Other Charges</option>
                        </select>

                    </div>
                    <div class="col-6 col-md-3">
                      <label class="text-danger">{{ __('Expense name') }}*</label>
                      <input type="text" class="form-control" name="name[]" placeholder="Expense Name" required>
                    </div>
                    <div class="col-6 col-md-2">
                      <label class="text-danger">{{ __('expense.Rate') }}*</label>
                      <input type="text" class="form-control" onkeyup="calculateAmount(this.value,0);" id="rate_0" name="rate[]" required>
                    </div>
                    <div class="col-6 col-md-2">
                      <label class="text-danger">{{ __('expense.Quantity') }}*</label>
                      <input type="text" class="form-control" value="1" onkeyup="calculateAmount(this.value,0);" id="quantity_0" name="quantity[]" required>
                    </div>
                    <div class="col-6 col-md-2">
                      <label class="text-danger">{{ __('expense.Total') }}*</label>
                      <input type="text" class="form-control amount" id="amount_0" name="amount[]" readonly required>
                    </div>
                    <div class="col-6 col-md-1 text-center">
                      <button type="button" class="btn btn-primary btn-sm mt-4 add-entry"><i class="fa fa-plus"></i></button>
                      <button type="button" class="btn btn-danger btn-sm mt-4 remove-entry"><i class="fa fa-trash"></i></button>
                    </div>
                  </div>
                </div>
                
                <div class="row g-3 mt-4">
                  <div class="col-6 col-md-3">
                    <label class="text-danger"><b>{{ __('expense.Total Amount') }}*</b></label>
                    <input type="text" class="form-control" id="total_amt" name="total_amt" readonly>
                  </div>
                  <div class="col-6 col-md-3">
                    <label class="text-danger"><b>{{ __('Payment Mode') }}*</b></label>
                    <select class="form-control" name="payment_mode_id" required>
                      @foreach($getPaymentMode as $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-6 col-md-3">
                    <label><b>{{ __('Attachment') }}</b></label>
                    <input type="file" class="form-control" name="attachment" accept="image/png, image/jpg, image/jpeg">
                    <p class="text-danger" id="image_error"></p>
                  </div>
                  <div class="col-6 col-md-3">
                    <label><b>{{ __('expense.Description') }}</b></label>
                    <textarea name="description" class="form-control" placeholder="If have any description write here..."></textarea>
                  </div>
                </div>
                
                <div class="row mt-4">
                  <div class="col text-center">
                    <button type="submit" class="btn btn-primary">{{ __('common.Submit') }}</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  function calculateAmount(value, row_id) {
    var quantity = $('#quantity_' + row_id).val();
    var rate = $('#rate_' + row_id).val();
    var amount = quantity * rate;
    $('#amount_' + row_id).val(amount);
    calculateSum();
  }

  function calculateSum() {
    let sum = 0;
    $(".amount").each(function () {
      if (!isNaN(this.value) && this.value.length != 0) {
        sum += parseFloat(this.value);
      }
    });
    $("#total_amt").val(sum.toFixed(2));
  }

  $(document).ready(function () {
    let rowIndex = 1;

    $(document).on('click', '.add-entry', function () {
      const newRow = $('.expense-entry').first().clone();
      newRow.find('input, select, textarea').each(function () {
        $(this).val('');
      });
    //   newRow.find('[id]').each(function () {
    //     const id = $(this).attr('id');
    //     if (id.includes('rate')) $(this).attr('id', 'rate_' + rowIndex);
    //     if (id.includes('quantity')) $(this).attr('id', 'quantity_' + rowIndex);
    //     if (id.includes('amount')) $(this).attr('id', 'amount_' + rowIndex);
    //   });
      newRow.find('[id]').each(function () {
        const id = $(this).attr('id');
        
        if (id.includes('rate')) {
            const newId = 'rate_' + rowIndex;
            $(this).attr('id', newId);
            $(this).attr('onkeyup', 'calculateAmount(this.value,' + rowIndex + ')');
        }
        if (id.includes('quantity')) {
            const newId = 'quantity_' + rowIndex;
            $(this).attr('id', newId);
            $(this).attr('onkeyup', 'calculateAmount(this.value,' + rowIndex + ')');
        }
        if (id.includes('amount')) {
            const newId = 'amount_' + rowIndex;
            $(this).attr('id', newId);
        }
    });

      newRow.attr('id', 'appendRow_' + rowIndex);
      $('#form-rows-container').append(newRow);
      rowIndex++;
    });

    $(document).on('click', '.remove-entry', function () {
      if ($('.expense-entry').length > 1) {
        $(this).closest('.expense-entry').remove();
        calculateSum();
      }
    });
  });
</script>

<style>

.expense-entry {
    border-bottom: 1px solid #ccc;
    padding-bottom: 15px;
    margin-bottom: 15px;
  }
  .expense-entry:last-child {
    border-bottom: none;
  }
  #image_error {
    font-weight: bold;
    font-size: 14px;
  }
  .action_container>* {
    color: #fff;
    text-decoration: none;
    display: inline-block;
    padding: 4px 7px;
    cursor: pointer;
    transition: 0.3s ease-in-out;
  }
  textarea {
    height: calc(2.25rem) !important;
  }
</style>
@endsection