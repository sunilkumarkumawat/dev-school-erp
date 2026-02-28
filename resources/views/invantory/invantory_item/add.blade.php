
@extends('layout.app') 
@section('content')

<div class="content-wrapper">
  

    <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">   
  <div class="col-md-12 pr-0">
            <div class="card card-outline card-orange mr-1">
             <div class="card-header bg-primary">
							<h3 class="card-title"><i class="fa fa-archive"></i> &nbsp;Invantory Item</h3>
							<div class="card-tools"> <a href="{{url('invantory_dashboard')}}" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i>{{ __('common.Back') }}</a> </div>
						</div>
						<form id="form-submit" action="{{ url('invantory_item_add') }}" method="post" > 
						    @csrf						
							<div class="row col-12">
							    
								<div class="col-md-3">
									<div class="form-group">
										<label style="color: red;">{{ __('common.Name') }}*</label>
										<input class="form-control  @error('name') is-invalid @enderror" type="text" id="name" name="name" placeholder="{{ __('common.Name') }}"> 
                                        								    
								    </div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Hsn Code</label>
										<input class="form-control  @error('hsn_code') is-invalid @enderror" type="text" id="hsn_code" name="hsn_code" placeholder="Hsn Code"> 
                                        								    
								    </div>
								</div>
							        <div class="col-md-2">
							              <div class="form-group">
                                        <label class="form-label" style="color: red;">Unit*</label>
                                            <select class="form-control @error('unit') is-invalid @enderror" name="unit" id="unit">
                                            <option value="">Select Unit</option>
                                            <option value="PCS">PCS</option>
                                            <option value="KG">KG</option>
                                            <option value="LTR">LTR</option>
                                            <option value="BOX">BOX</option>
                                        </select>
                                        </div>
                                    </div>
                                    
								<div class="col-md-3">
									<div class="form-group">
										<label style="color: red;">Mrp*</label>
										<input class="form-control  @error('mrp') is-invalid @enderror" type="text" id="mrp" name="mrp" placeholder="Mrp"> 
                                        								    
								    </div>
								</div>
							
								</div>
							
							 <div class="row m-2 pb-2">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-submit">Submit Item</button>
                        </div>
                    </div>

					        
					    </form>
					    </div>
				</div>
			<div class="col-md-12 pl-0">
    <div class="card card-outline card-orange ml-1">

        <div class="card-header bg-primary">
            <h3 class="card-title">
                <i class="fa fa-archive"></i> Inventory Item
            </h3>
        </div>

        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>SR.NO</th>
                        <th>Name</th>
                        <th>HSN Code</th>
                        <th>Unit</th>
                        <th>MRP</th>
                        <th>Available Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                @php $i = 1; @endphp

                @foreach($data as $item)

                    @php
                        // STOCK CALCULATION
                        $purchased = DB::table('inventory_details')
                            ->where('inventory_item_id', $item->id)
                            ->whereNull('deleted_at')
                            ->sum('qty');

                        $sold = DB::table('inventory_sale_details')
                            ->where('inventory_item_id', $item->id)
                            ->whereNull('deleted_at')
                            ->sum('qty');

                        $stock = $purchased - $sold;

                        // LOW STOCK LIMIT
                        $lowStockLimit = 5;

                        // STATUS LOGIC
                        if ($stock <= 0) {
                            
                            $badgeClass = 'bg-danger';
                            $statusText = 'Out of Stock';
                        } elseif ($stock <= $lowStockLimit) {
                           
                            $badgeClass = 'bg-warning text-dark';
                            $statusText = 'Low Stock';
                        } else {
                          
                            $badgeClass = 'bg-success';
                            $statusText = 'In Stock';
                        }
                    @endphp

                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->hsn_code ?? '-' }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>₹ {{ number_format($item->mrp, 2) }}</td>
                        <td>
                            <span class="badge {{ $badgeClass }}">
                                {{ $stock }} ({{ $statusText }})
                            </span>
                        </td>
                        <td>
                            <a href="{{ url('invantory_item_edit/'.$item->id) }}"
                               class="btn btn-sm btn-primary">
                                <i class="fa fa-edit"></i>
                            </a>

                            <button class="btn btn-sm btn-danger deleteData"
                                    data-id="{{ $item->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#Modal_id">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                @endforeach
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
    $('.deleteData').click(function() {
	var delete_id = $(this).data('id');
	$('#delete_id').val(delete_id);
});
</script>

<div class="modal" id="Modal_id">
	<div class="modal-dialog">
		<div class="modal-content" style="background: #555b5beb;">
			<div class="modal-header">
				<h4 class="modal-title text-white">{{ __('common.Delete Confirmation') }}</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
			<form action="{{ url('invantory_item_delete') }}" method="post"> 
			    @csrf
				<div class="modal-body">
					<input type=hidden id="delete_id" name=delete_id>
					<h5 class="text-white">{{ __('common.Are you sure you want to delete') }} ?</h5> </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal">{{ __('common.Close') }}</button>
					<button type="submit" class="btn btn-danger waves-effect waves-light">{{ __('common.Delete') }}</button>
				</div>
			</form>
		</div>
	</div>
		</div>
	</div>
		</div>
	</div>
	</section>
</div>

				
				
		
@endsection