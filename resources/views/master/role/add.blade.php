
@extends('layout.app')
@section('content')

     <div class="content-wrapper">
   
   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">    
        <div class="col-md-4 pr-0 ">
            <div class="card card-outline card-orange mr-1">
             <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-user-circle-o"></i> &nbsp;{{ __('master.Role') }}</h3>
                			<div class="card-tools">
           
            </div>
            
            </div>                                  

    <form id="form-submit" action="{{ url('role_add') }}" method="post" >
        @csrf
		<div class="row m-2">
                <div class="col-md-12">
					<label class="text-danger">{{ __('master.Role') }} *</label>
					<input type="text" class="form-control @error('role') is-invalid @enderror " id="role" name="role" placeholder="{{ __('master.Role') }}" value="{{old('role')}}">
				
				</div>
            </div>
	
          <div class="row m-2">
                    <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-submit">{{ __('common.submit') }}  </button>
                    </div>
                </div>
              </form>
            </div>          
        </div>
        
        <div class="col-md-8 pl-0">
            <div class="card card-outline card-orange ml-1">
             <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fa fa-user-circle-o"></i> &nbsp;{{ __('master.View Role') }}</h3>
            <div class="card-tools">
            <!--<a href="{{url('students/add')}}" class="btn btn-primary  btn-sm" ><i class="fa fa-plus"></i> Add</a>-->
            <a href="{{url('master_dashboard')}}" class="btn btn-primary  btn-sm" ><i class="fa fa-arrow-left"></i>{{ __('common.Back') }} </a>
            </div>
            
            </div>                 
                <div class="row m-2">
                    <div class="col-md-12">
                	</div>
                    <div class="col-md-12">
                       <table id="" class="table table-bordered table-striped dataTable dtr-inline ">
                          <thead>
                          <tr role="row">
              <th>{{ __('common.SR.NO') }}</th>
                    <th>{{ __('master.Role') }}  </th>
                    
                    <th>{{ __('common.Action') }}</th>
                  
          </thead>
          <tbody>
              
              @if(!empty($role))
                @php
                   $i=1
                @endphp
                @foreach ($role as $item)
                <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $item['name'] ?? '' }}</td>
                    
                        <td>
                            @if($item['id']  <= 5)
                            @else
                            @if(Session::get('role_id') == 1)
                                <a href="{{ url('role_Edit') }}/{{ $item['id'] ?? '' }}" class="btn btn-primary  btn-xs tooltip1" title1="Edit"><i class="fa fa-edit"></i></a> 
                               
                                <a href="javascript:;" data-id='{{$item['id'] }}' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-danger btn-xs ml-3 tooltip1" title1="Delete"><i class="fa fa-trash-o"></i></a>
                            @else
                               <a href="javascript:;" data-id='{{$item['id'] }}' data-bs-toggle="modal" data-bs-target="#Modal_id" class="deleteData btn btn-danger btn-xs ml-3tooltip1" title1="Delete"><i class="fa fa-trash-o"></i></a>
                            @endif
                            @endif
                            <a href="javascript:void(0);" 
                           class="btn btn-sm btn-info view-permissions" 
                           data-role="{{ $item->id }}">
                            <i class="fa fa-key"></i> Permission
                        </a>
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
    $("#select_all").change(function () {  
        $(".checkbox").prop('checked', $(this).prop("checked")); 
    });
</script>  
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

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-white">{{__('common.Delete Confirmation') }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>

      <!-- Modal body -->
      <form action="{{ url('role_delete') }}" method="post">
              	 @csrf
      <div class="modal-body">
              
            
            
              <input type=hidden id="delete_id" name=delete_id>
              <h5 class="text-white">{{__('common.Are you sure you want to delete') }}  ?</h5>
           
      </div>

      <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form" data-dismiss="modal">{{__('common.Close') }}</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">{{__('common.Delete') }}</button>
         </div>
       </form>

    </div>
  </div>
</div>
{{-- Modal --}}
<div class="modal fade" id="permissionModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Manage Permissions</h5>
         <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          &times;
        </button>
      </div>
      <div class="modal-body" id="permissionContent">
        <div class="text-center">
            <i class="fa fa-spinner fa-spin"></i> Loading...
        </div>
      </div>
    </div>
  </div>
</div>


<script>
$(document).on('click', '.view-permissions', function(){
    var role_id = $(this).data('role');
    $('#permissionContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
    $('#permissionModal').modal('show');

   $.get("{{ url('role/permission') }}/" + role_id, function(data){
    $('#permissionContent').html(data);

   
    $('.row-select-all').on('change', function() {
        let moduleId = $(this).data('module-id');
        let checked = $(this).is(':checked');
        $('input.permission-checkbox[data-module-id="'+moduleId+'"]').prop('checked', checked);
    });

  
    $('.check-type').on('change', function() {
        let type = $(this).data('type');
        $('input.permission-checkbox[value="'+type+'"]').prop('checked', $(this).is(':checked'));
    });
  });
});
</script>

@endsection

