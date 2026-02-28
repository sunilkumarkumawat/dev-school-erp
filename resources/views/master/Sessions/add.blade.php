
@extends('layout.app') 
@section('content')

<div class="content-wrapper">
    
   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">    
            <div class="col-md-4 pr-0 ">
            <div class="card card-outline card-orange mr-1">
             <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fa fa-calendar-check-o"></i> &nbsp;{{ __('master.Add Sessions') }} </h3>
            <div class="card-tools">
              </div>
            
            </div>                 
                <form id="form-submit" action="{{ url('session_add') }}" method="post">
                @csrf
                <div class="row m-2">
                        <div class="col-md-12">
        			<div class="form-group">
        				<label style="color:red;">{{ __('master.From Year') }}* </label>
        				<input type="text" class="form-control @error('from_year') is-invalid @enderror" id="year" name="from_year" placeholder="From Year" value="{{old('from_year')}}" maxlength="4" onkeypress="javascript:return isNumber(event)">
            		     
                        </select>
        		    </div>
    		    </div>
                        <div class="col-md-12">
        			<div class="form-group">
        				<label style="color:red;">{{ __('master.To Year') }}* </label>
        				<input type="text" class="form-control @error('to_year') is-invalid @enderror" id="to_year" name="to_year" placeholder="To Year" value="{{old('to_year')}}" maxlength="2" onkeypress="javascript:return isNumber(event)">
            		     
                        </select>
        		    </div>
    		    </div>
                                     	
                </div>
 
                <div class="row m-2">
                    <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-submit">{{ __('common.submit') }} </button>
                    </div>
                </div>
                </form>
            </div>          
        </div>
        
        <div class="col-md-8 pl-0">
            <div class="card card-outline card-orange ml-1">
             <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fa fa-calendar-check-o"></i> &nbsp;{{ __('master.View Sessions') }} </h3>
            <div class="card-tools">
        
            <a href="{{url('master_dashboard')}}" class="btn btn-primary  btn-sm" ><i class="fa fa-arrow-left"></i>{{ __('common.Back') }} </a>
            </div>
            
            </div>                 
                <div class="row m-2">
                    <div class="col-md-12">
                	</div>
                    <div class="col-md-12">
                       <table id="example1" class="table table-bordered table-striped dataTable dtr-inline ">
                          <thead class="bg-primary">
                          <tr role="row">
                              <th>{{ __('common.SR.NO') }}</th>
                              <th>{{ __('master.From Date') }}</th>
                              <th>{{ __('master.To Date') }}</th>
                              
                              <th>{{ __('common.Action') }}</th>
                              
                              
                              
                              
                          </thead>
                          <tbody id="">
                          
                          @if(!empty($sessions))
                                @php
                                   $i=1
                                @endphp
                                @foreach ($sessions  as $item)
                                <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $item['from_year'] ?? '' }}</td>
                                        <td>{{ $item['to_year'] ?? '' }}</td>
                                        
                                        <td>
                                            <a href="{{url('sessions_edit',$item->id)}}" class="btn btn-primary  btn-xs tooltip1 {{ Helper::permissioncheck(9)->edit ? '' : 'd-none' }}" title1="Edit Sessions"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:;"  data-id='{{$item->id}}' data-bs-toggle="modal" data-bs-target="#Modal_id"  class="deleteData btn btn-danger  btn-xs ml-3 tooltip1 {{ Helper::permissioncheck(9)->delete ? '' : 'd-none' }}" title1="Delete Sessions "><i class="fa fa-trash-o"></i></a>                                   
                                            
                                        </td>
                                       
                                    </tr>
                           @endforeach
                        @endif
                          </tbody>
                          </table>
                	</div>
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
  } );
</script>

<!-- The Modal -->
<div class="modal" id="Modal_id">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #555b5beb;">

      <div class="modal-header">
        <h4 class="modal-title text-white">{{ __('messages.Delete Confirmation') }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>

      <form action="{{ url('sessions_delete') }}" method="post">
              	 @csrf
      <div class="modal-body">
              <input type=hidden id="delete_id" name=delete_id>
              <h5 class="text-white">{{ __('messages.Are you sure you want to delete') }}  ?</h5>
      </div>
      <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">{{ __('messages.Delete') }}</button>
         </div>
       </form>
    </div>
  </div>
</div>
@endsection      