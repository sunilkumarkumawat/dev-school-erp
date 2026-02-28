@php
$getCountry = Helper::getCountry();
$getState = Helper::getState();
$getCity = Helper::getCity();
$roleType = Helper::roleType();
@endphp
@extends('layout.app')
@section('content')


 <div class="content-wrapper">
	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-edit"></i> &nbsp;{{ __('master.Edit Role') }} </h3>
                            <div class="card-tools">
                                        <a href="{{url('role_add')}}" class="btn btn-primary  btn-sm"><i class="fa fa-eye"></i>{{ __('common.View') }} </a>
                                        <a href="{{url('master_dashboard')}}" class="btn btn-primary  btn-sm" ><i class="fa fa-arrow-left"></i> {{ __('common.Back') }}</a>

                        </div>
                           </div>                      
                            <form id="form-submit-edit" action="{{ url('role_Edit') }}/{{$add_pr['id'] ?? ''}}" method="post" >
                                @csrf
                        		<div class="row mb-2 m-2">
                        		    <div class="col-md-4">
                        				<div class="form-group">
                        					<label class="text-danger">{{ __('master.Role') }} *</label>
                        					<input type="text" class="form-control @error('role') is-invalid @enderror " id="role" name="role" placeholder="Role" value="{{$add_pr['name'] ?? ''}}">
                        				
                        				</div>
                        			</div>
                                </div>
                        	
                                <div class="col-md-12 text-center">
                        			<button type="submit" class="btn btn-primary btn-submit">{{ __('common.Update') }}</button><br><br>
                        		</div>
                            	</form>
   </div>
        </div>
    </div>
    </section>
</div>
   <script>
    $("#select_all").change(function () {  
        $(".checkbox").prop('checked', $(this).prop("checked")); 
    });
</script>  
@endsection

