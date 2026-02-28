@extends('layout.app') 
@section('content')
@php

$classType = Helper::ClassType();
$setting = Helper::getSetting();
@endphp
<div class="content-wrapper">
<section class="content pt-3">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12 col-md-12">
            <div class="card card-outline card-orange">
               <div class="card-header bg-primary">
                  <h3 class="card-title"><i class="fa fa-leanpub"></i> &nbsp; {{ __('Stream Update') }} </h3>
                  <div class="card-tools d-flex align-item-center"> 
                     <a href="{{ url('studentsDashboard') }}" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i>{{ __('messages.Back') }}  </a> 
                  </div>
               </div>
               <div class="card-body">
                     <div class="row">
                        <div class='col-md-10'>
                        <form id="quickForm_find" action="{{ url('stream_update') }}" method="post">
                             @csrf 
                            <div class="row">
                            <div class="col-md-3">
                               <div class="form-group">
                                  <label class="text-danger">{{ __('messages.Class') }}*</label>
                                  <select class="select2 form-control @error('class_type_id') is-invalid @enderror " id="class_type_id" name="class_type_id">
                                     <option value="">{{ __('messages.Select') }}</option>
                                     @if(!empty($classType))
                                     @foreach($classType as $type)
                                     @if($type->orderBy > 10)
                                     <option value="{{ $type->id ?? ''  }}" {{ ($type->id == $search['class_type_id']) ? 'selected' : '' }}>{{ $type->name ?? ''  }}</option>
                                     @endif
                                     @endforeach
                                     @endif
                                  </select>
                                  @error('class_type_id')
                                  <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                               </div>                            </div>
                           
                          
                            
                         <div class="col-md-1 col-6">
                               <label for="" class="text-white">Search</label>
                               <button type="submit" class="btn btn-primary">{{ __('messages.Search') }}</button>
                            </div>
                            </div>
                        </form>
                        </div>
                     </div>
                     @if(count($data) > 0)
                     <hr>
                     <div class="row">
                        <div class='col-md-12'>
                        <form id="form-submit" action="{{ url('stream_update_save') }}" method="post">
                             @csrf 
                            <div class="row">
                            
                            <div class="col-md-3">
                               <div class="form-group">
                                  <label class="text-danger">{{ __('Subject Name') }}*</label>
                                  <table id="" class="table table-bordered table-striped dataTable dtr-inline ">
                                    
                                    <tbody >
                                  
                                      @if(!empty($list_subject) && count($list_subject) > 0)
                                        @php
                                           $i=1;
                                         
                                        @endphp
                                            @foreach ($list_subject as $type1)
                                                <tr>
                                                    <td>                                  
                                                        <input type="checkbox" data-value="view" name="subject_id[]" class="viewcheck2" value="{{ $type1->id ?? '' }}">
                                                    </td>
                                                    <td>{{ $type1->name ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="2">Subject not found
                                                       ( <a href="{{ url('add_subject') }}" class="">  Assign Subject </a>)
</td>
                                            </tr>
                                        @endif

                                  </tbody>
                            </table>
                               
                               </div>
                            </div>
                            <div class="col-md-9" style="font-size: 13px;">
                               <label class="text-danger">{{ __('Student Name') }}*</label>
                                <table id="" class="table table-bordered table-striped dataTable dtr-inline ">
                                    <thead class="bg-primary">
                                        <tr role="row">
                                          <th> <input type="checkbox" id="view1">{{ __('student.Ad. No') }}</th>
                                           <th>{{ __('common.Name') }}</th>
                                           <th>{{ __('Subject') }}</th>
                                        </tr>
                                       </thead>
                                    <tbody >
                                  
                                  @if(!empty($data))
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($data as $item)
                                                @php
                                                    $subject_ids = explode(',', $item['stream_subject']);
                                        
                                                    $subject = DB::table('subject')->whereIn('id', $subject_ids)->get();
                                                @endphp
                                        
                                                <tr>
                                                    <td>								    
                                                        <input type="checkbox" data-value="view" id="{{ $item->id ?? '' }}" name="admission_id[]" class="viewcheck" value="{{ $item->id ?? '' }}">
                                                      <label for="{{ $item->id ?? '' }}">  {{ $item['admissionNo'] }}</label>
 
                                                    </td>
                                                    <td>{{ $item['first_name'] ?? '' }} {{ $item['last_name'] ?? '' }}</td>
                                                    <td>
                                                        @if(!empty($subject))
                                                            <ul class="subject-list">
                                                                @foreach ($subject as $sub)
                                                                    <li class="subject-item" id="admission-{{ $item->id }}-{{ $sub->id }}">
                                                                        {{ $sub->name ?? '' }}
                                                                        <div class="delete-btn" onclick="deleteSubject('{{ $sub->id }}','{{ $item->id }}')">
                                                                            <i class="fa fa-trash-o"></i>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                  </tbody>
                            </table>
                
            </div>
                        @if(count($data) > 0)    
                         <div class="col-md-12 col-6 text-center">
                               <label for="" class="text-white">Search</label>
                               <button type="submit" class="btn btn-primary btn-submit">{{ __('Save') }}</button>
                            </div>
                            @endif
                            </div>
                        </form>
                        </div>
                     </div>
                     @endif
                
            </div>
</section>
</div>
<script>
    // ✅ View1 checkbox logic (unchanged)
    $("#view1").click(function () {
        if ($(this).is(':checked')) {
            $(".viewcheck").prop('checked', false);
            $(".viewcheck").prop('checked', true);
        } else {
            $(".viewcheck").prop('checked', false);
        }
    });

    $(document).ready(function () {
        $('#class_type_id').on('change', function (e) {
            var baseurl = "{{ url('/') }}";
            var class_type_id = $(this).val();

            // ✅ Condition check like first code
            if (class_type_id > 0) {
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content') },
                    type: 'get',
                    url: baseurl + '/subjectGetData/' + class_type_id,
                    success: function (data) {
                        $("#subject_id").html(data);
                    },
                    error: function () {
                        alert('Something went wrong, please try again.');
                    }
                });
            } else {
                alert('Please select a Class Type!');
                $("#subject_id").html('<option value="">Select Subject</option>'); // reset subject dropdown
            }
        });
    });
</script>

<!-- Add your custom CSS -->
<style>
    .subject-list {
        list-style-type: none;
        padding: 0;
    }

    .subject-item {
        position: relative;
        display: inline-block;
        margin-right: 10px;
    }

    .delete-btn {
          display: none;
          /*position: absolute;*/
          top: 0;
         /* right: -16px;*/
          background-color: white;
          color: red;
          border: none;
          padding: -2px;
          cursor: pointer;
          z-index: 1100;
        }
    /* Show delete button on hover */
    .subject-item:hover .delete-btn {
        display: inline-block;
    }
</style>

<script>
var baseurl = "{{ url('/') }}";

function deleteSubject(subjectId, admission_id) {
    if (confirm("Are you sure you want to delete this subject?")) {
        $.ajax({
            url: `${baseurl}/stream_remove/${admission_id}/${subjectId}`,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                  
                    $('#admission-' + admission_id + '-' + subjectId).remove();
                } else {
                    alert('Error deleting the subject.');
                }
            },
            error: function() {
                alert('An error occurred while deleting the subject.');
            }
        });
    }
}

</script>
@endsection
