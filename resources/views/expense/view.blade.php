@php
    $getHostel = Helper::getHostel();
    $getRole = Helper::getUsers();
@endphp
@extends('layout.app') 
@section('content')
<style>
    .top{
        margin-top: -12px;
    }
</style>
<div class="content-wrapper">

	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
							<h3 class="card-title"><i class="fa fa-credit-card"></i> &nbsp;{{ __('expense.View Expense') }}</h3>
							
							
							<div class="card-tools"> 
							    <a href="{{url('expenseAdd')}}" class="btn btn-primary btn-sm {{ Helper::permissioncheck(16)->add ? '' : 'd-none' }}"><i class="fa fa-plus"></i>{{ __('common.Add') }}</a> 
							</div>
							
						</div>
						<div class="card-body">
                        <form id="quickForm" action="{{ url('expenseView') }}" method="post">
                            @csrf 
                            <div class="row m-2">
             
                                <div class="col-md-2">
                                    <label><b>{{ __('User Name') }}</b></label>
                                    <select class="form-control" name="role">
                                        <option value="">Select</option>
                                        @foreach($getRole as $item)
                                            <option value="{{ $item->id }}" {{ ($search['role'] ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->first_name }} {{ $item->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                               <div class="col-6 col-md-2">
                                    <label><b>{{ __('Category') }}</b></label>
                                    <select class="form-control" name="category">
                                        <option value="">Select</option>
                                      <option value="Salary" {{ ($search->category ?? '') == 'Salary' ? 'selected' : '' }}>Salary</option>

                                        <option value="Loan Payments" {{ ($search->category ?? '') == 'Loan Payments' ? 'selected' : '' }}>Loan Payments</option>
                                        
                                        <option value="Mobile Bill & Recharge" {{ ($search->category ?? '') == 'Mobile Bill & Recharge' ? 'selected' : '' }}>Mobile Bill &amp; Recharge</option>
                                        
                                        <option value="School Building Maintenance" {{ ($search->category ?? '') == 'School Building Maintenance' ? 'selected' : '' }}>School Building Maintenance</option>
                                        
                                        <option value="Computer & Electronics" {{ ($search->category ?? '') == 'Computer & Electronics' ? 'selected' : '' }}>Computer &amp; Electronics</option>
                                        
                                        <option value="Laboratory Expenses" {{ ($search->category ?? '') == 'Laboratory Expenses' ? 'selected' : '' }}>Laboratory Expenses</option>
                                        
                                        <option value="Furniture Expense" {{ ($search->category ?? '') == 'Furniture Expense' ? 'selected' : '' }}>Furniture Expense</option>
                                        
                                        <option value="Fuel & Gas" {{ ($search->category ?? '') == 'Fuel & Gas' ? 'selected' : '' }}>Fuel &amp; Gas</option>
                                        
                                        <option value="Printing & Stationery Items" {{ ($search->category ?? '') == 'Printing & Stationery Items' ? 'selected' : '' }}>Printing &amp; Stationery Items</option>
                                        
                                        <option value="Donations And Taxes" {{ ($search->category ?? '') == 'Donations And Taxes' ? 'selected' : '' }}>Donations And Taxes</option>
                                        
                                        <option value="Electricity Bills" {{ ($search->category ?? '') == 'Electricity Bills' ? 'selected' : '' }}>Electricity Bills</option>
                                        
                                        <option value="Internet Bills" {{ ($search->category ?? '') == 'Internet Bills' ? 'selected' : '' }}>Internet Bills</option>
                                        
                                        <option value="Water Bills" {{ ($search->category ?? '') == 'Water Bills' ? 'selected' : '' }}>Water Bills</option>
                                        
                                        <option value="Staff Welfare Expenses" {{ ($search->category ?? '') == 'Staff Welfare Expenses' ? 'selected' : '' }}>Staff Welfare Expenses</option>
                                        
                                        <option value="Rent Expenses" {{ ($search->category ?? '') == 'Rent Expenses' ? 'selected' : '' }}>Rent Expenses</option>
                                        
                                        <option value="Event Expenses" {{ ($search->category ?? '') == 'Event Expenses' ? 'selected' : '' }}>Event Expenses</option>
                                        
                                        <option value="House Expenses" {{ ($search->category ?? '') == 'House Expenses' ? 'selected' : '' }}>House Expenses</option>
                                        
                                        <option value="Maintenance" {{ ($search->category ?? '') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        
                                        <option value="Insurance" {{ ($search->category ?? '') == 'Insurance' ? 'selected' : '' }}>Insurance</option>
                                        
                                        <option value="Education & Tuition" {{ ($search->category ?? '') == 'Education & Tuition' ? 'selected' : '' }}>Education &amp; Tuition</option>
                                        
                                        <option value="Sports Goods" {{ ($search->category ?? '') == 'Sports Goods' ? 'selected' : '' }}>Sports Goods</option>
                                        
                                        <option value="Other Charges" {{ ($search->category ?? '') == 'Other Charges' ? 'selected' : '' }}>Other Charges</option>

                                    </select>
                                </div>



                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{ __('expense.From Date') }}</label>
                                        <input type="date" class="form-control" name="from_date" value="{{ $search['from_date'] ?? '' }}">
                                    </div>
                                </div>
                        
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{ __('expense.To Date') }}</label>
                                        <input type="date" class="form-control" name="to_date" value="{{ $search['to_date'] ?? '' }}">
                                    </div>
                                </div>
                        
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('common.Search By Keywords') }}</label>
                                        <input type="text" class="form-control" name="keyword" placeholder="Search by keyword"
                                               value="{{ $search['keyword'] ?? '' }}">
                                    </div>
                                </div>
                        
                                <div class="col-md-1">
                                    <div class="Display_none_mobile">
                                        <label class="text-white">Search</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ __('common.Search') }}</button>
                                </div>
                            </div>
                        </form>

                            <div class="col-md-12" id="">

                        <table id="example1" class="table table-bordered table-striped dataTable dtr-inline ">
                            <thead class="bg-primary">
                                <tr role="row">
                                    <th>{{ __('common.SR.NO') }}</th>
                                    <th>Invoice No</th>
                                    <th>{{ __('Expense Head') }}</th>
                                    <th>{{ __('User Name') }}</th>
                                    <th>{{ __('common.Date') }}</th>
                                    <th>{{ __('expense.Quantity') }}</th>
                                    <th>{{ __('common.Amount') }}</th>
                                    
                                    <th>{{ __('common.Action') }}</th>
                                   
                                </tr>
                            </thead>
                            <tbody>

                                @if(!empty($data))
                                @php
                                    $i=1;
                                   $total = 0;
                                @endphp

                                @foreach ($data  as $item)
                                @php
                                    $expance = DB::table('expenses')->where('date',$item['date'])->get();
                                @endphp
                               <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $item['invoice_no'] ?? '' }}</td>
                                    
                                    @php
                                   
                                    $user_name = DB::table('users')->where('id',$item->user_id)->first();
                                    @endphp
                                    <td>{{ $item['name']?? 'Not Mentioned' }} </td>
                                    <td>{{ $user_name->first_name ?? 'Not Mentioned' }} {{ $user_name->last_name ?? '' }} </td>
                                    <td>{{ date('d-m-Y', strtotime($item['date'])) ?? '' }}</td>
                                    <td>{{ $item['quantity'] ?? '' }}</td>
                                    <td id="{{ $item['date'] ?? '' }}">{{ $item['amount'] ?? '' }}</td>
                                    
                                       <td>
                                            <a href="{{ url('expensePrint') }}/{{ $item['invoice_no'] }}" target="blank" class="btn btn-success btn-xs tooltip1 {{ Helper::permissioncheck(16)->print ? '' : 'd-none' }}" title1="Print Expense"><i class="fa fa-print"></i></a>
                                        
                                            <a href="{{ url('expenseEdit') }}/{{ $item['invoice_no'] }}" class="btn btn-primary btn-xs ml-1 tooltip1 {{ Helper::permissioncheck(16)->edit ? '' : 'd-none' }}" title1="Edit Expense"><i class="fa fa-edit"></i></a>
                                        
                                            <a href="javascript:;" data-id='{{ $item['id'] ?? '' }}' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-danger btn-xs ml-1 tooltip1 {{ Helper::permissioncheck(16)->delete ? '' : 'd-none' }}" title1="Delete Expense"><i class="fa fa-trash-o"></i></a>
                                        </td>

                                   
                                </tr>
                                @php
                                    $total += $item['amount'] ;
                                @endphp
                                @endforeach
                                <tfoot>
                                    <tr>
                                        <th class="text-white">Total</th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> <b>{{ __('messages.Total Amount') }}</b></th>
                                        <th> <b id="total_amt">₹ {{$total ?? ''}}</b></th>
                                        <th></th>   
                                    </tr>    
                                </tfoot>
                              @endif
                            </tbody>
                        </table>
                            </div>
                        </div>                        
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!--<div class="modal" id="Modal_new">-->
<!--	<div class="modal-dialog">-->
<!--		<div class="modal-content" style="margin-left: -30%;width: 160%;">-->
<!--			<div class="modal-header">-->
<!--			    	<h3>Total Expances</h3>-->
<!--				<h4 class="modal-title text-white"></h4>-->
<!--				<button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>-->
<!--			</div>-->
		
<!--			    <div class="modal-body">-->
<!--			        <div class="row">-->
<!--			            <div class="col-md-12" id="appendTable"></div>-->
<!--			        </div>-->
<!--			    </div>-->

					  
<!--				<div class="modal-footer">-->
<!--					<button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal">{{ __('messages.Close') }}</button>-->
<!--				</div>-->
		
<!--		</div>-->
<!--	</div>-->
<!--</div> -->
<!--</div> -->



















<!-- The Modal -->
<div class="modal" id="Modal_id">
	<div class="modal-dialog">
		<div class="modal-content" style="background: #555b5beb;">
			<div class="modal-header">
				<h4 class="modal-title text-white">{{ __('common.Delete Confirmation') }}</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
			<form action="{{ url('expenseDelete') }}" method="post"> 
			    @csrf
				<div class="modal-body">
					<input type=hidden id="delete_id" name=delete_id>
					<h5 class="text-white">{{ __('common.Are you sure you want to delete') }}  ?</h5> </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal">{{ __('common.Close') }}</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light">{{ __('common.Delete') }}</button>
				</div>
			</form>
		</div>
	</div>
</div> 


   
<script>
$('.deleteData').click(function() {
	var delete_id = $(this).data('id');
	$('#delete_id').val(delete_id);
});

// $(".expanseShow").click(function(){
//     var expanse = $(this).data('expanse');
//     var tablestart = "<table class='table table-bordered table-striped dataTable dtr-inline'><tr><th>Name</th><th>Name</th><th>Name</th><th>Name</th></tr>";
//     var tableend = "<tr><td></td><td></td><td><b>Total</b></td><td id='sumamt'></td></tr></table>";
//     var tr = "";
//     var sumamt = 0;
//     $.each( expanse, function( key, value ) {
//         tr = tr + "<tr><td>" + value.name + "</td><td>" + value.date + "</td><td>" + value.quantity + "</td><td>" + value.amount + "</td></tr>";
//         sumamt += parseInt(value.amount);
//     });
//     var table = tablestart + tr + tableend;
//     $("#appendTable").html(table);
//     $("#sumamt").html(sumamt);
// })
</script>


@endsection      