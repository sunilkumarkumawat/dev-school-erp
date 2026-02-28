@extends('layout.app') 
@section('content')
@php
$classType = Helper::classTypeExam();
@endphp
<div class="content-wrapper">
<section class="content pt-3">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12 col-md-12">
            <div class="card card-outline card-orange">
               <div class="card-header bg-primary">
                  <h3 class="card-title"><i class="fa fa-leanpub"></i> &nbsp; {{ __('Fill Marks') }} </h3>
                  <div class="card-tools cl-6"> 
                   
                     <a href="{{ url('examination_dashboard') }}" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i> {{ __('messages.Back') }}  </a> 
                  </div>
               </div>
               <div class="card-body">
                  <form id="quickForm" action="{{ url('fill_marks') }}" method="post"  class="was-validated">
                     @csrf 
                     <div class="row">  
                        <div class="col-md-3">
                           <div class="form-group">
                              <label style="color:red;">{{ __('messages.Class') }}*</label>
                              <select class="select2 form-control @error('class_name') is-invalid @enderror " id="class_type_id" name="class_name" required>
                                 <option value="">{{ __('messages.Select') }}</option>
                                 @if(!empty($classType))
                                 @foreach($classType as $type)
                                 <option value="{{ $type->id ?? ''  }}" {{ ($type->id == $search['class_type_id']) ? 'selected' : '' }}>{{ $type->name ?? ''  }}</option>
                                 @endforeach
                                 @endif
                              </select>
                              
                             <div class="invalid-feedback">Please fill out this field.</div>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label style="color:red;">{{ __('messages.Exam Name') }}*</label>
                              <select class="select2 form-control @error('exam_id') is-invalid @enderror exam_id_" id="exam_id" name="exam_id" required >
                                 <option value="">{{ __('messages.Select') }}</option>
                               @if(!empty($examlist))
                                      @foreach($examlist as $item)
                                      
                                      <option value="{{ $item->exam_id ?? ''  }}" {{ ($item->exam_id == $search['exam_id']) ? 'selected' : '' }}>{{ $item->exam_name ?? ''  }}</option>
                                        @endforeach
                                      @endif
                               
                              </select>
                                 <div class="invalid-feedback">Please fill out this field.</div>
                           </div>
                        </div>
                     <div class="col-md-3">
                           <div class="form-group">
                              <label style="color:red;">{{ __('Subject Name') }}*</label>
                              <select class="select2 form-control @error('subject_id') is-invalid @enderror subject_id" id="subject_id" name="subject_name_id" required >
                                 <option value="">{{ __('messages.Select') }}</option>
                               @if(!empty($Allsubjects))
                                      @foreach($Allsubjects as $sub)
                                      
                                      <option value="{{ $sub->id ?? ''  }}" {{ ($sub->id == $search['subject_name_id']) ? 'selected' : '' }}>{{ $sub->name ?? ''  }}</option>
                                        @endforeach
                                      @endif
                               
                              </select>
                                 <div class="invalid-feedback">Please fill out this field.</div>
                           </div>
                        </div>
                        <div class="col-md-1 col-6">
                           <label for="" class="text-white">Search</label>
                           <button type="submit" class="btn btn-primary">{{ __('messages.Search') }}</button>
                        </div>
                     </div>
                  </form>
                  
                  @if(!empty($subjects))    
                  <div class="row m-2">
                     <div class="col-12">
                        <form action={{url('fill_marks_submit')}} method="post">
                           @csrf
                            <div class="card">
   <div class="card-header border-transparent">
       
      <div class="card-tools">
         <button type="button" class="btn btn-primary btn-tool" data-card-widget="collapse">
         <i class="fa fa-minus"></i>
         </button>
      </div>
   </div>
   <div class="card-body p-0" style="display: block;">
      <div class="table-responsive">
         <table class="table table-bordered table-striped dataTable dtr-inline" id="dataTable">
                            <thead>
                                <tr>
                                    <th>S No</th>
                                    <th>Subject Name</th>
                                    <th>Maximum Marks</th>
                                    <th>Minimum Marks</th>
                                    <!--<th>Marks Add in Total</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                 
                                @if($subjects->count() != 0)
                                 @if(!empty($subjects)) 
                                 @foreach($subjects as $key => $item )
                                 <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        @if($item->sub_name != '')
                                            {{$item->sub_name ?? ''}}
                                        @else 
                                        {{$item->name ?? ''}}
                                        @endif
                                        </td>
                                    <td>
                                     
                                     @php
                                     
                                     $old_value = DB::table('fill_min_max_marks')
                                     ->where('exam_id', $search['exam_id'] ?? '')
                                     ->where('class_type_id',$search['class_type_id'] ?? '')
                                     ->where('subject_id',$item->id ?? '')
                                     ->where('branch_id',Session::get('branch_id'))
                                     ->where('session_id',Session::get('session_id'))
                                     ->whereNull('deleted_at')->first();
                                     
                                   
                                     @endphp
                                     
                                     
                                       @if(!empty($old_value))
                                               <input type='hidden' name='fill_min_max_marks_id[]' class='max_min' data-max_min='{{$old_value->exam_maximum_marks ?? 100}}' value='{{$old_value->id ?? ''}}'/>
                                                 @else
                                                 <input type='hidden' name='fill_min_max_marks_id[]' class='max_min' data-max_min='{{$old_value->exam_maximum_marks ?? 100}}' value=''/>
                                               
                                            @endif
                                     
                                   <input type="hidden" name="subject_id[]" value="{{$item->id ?? ''}}" class="form-control" >
                                      
                                       <input type="text" name="exam_maximum_marks[]" value="{{ $old_value->exam_maximum_marks ?? 100 }}" class="form-control maximum_marks_input" id="subject_{{ $item->id }}" >
                                    </td>
                                    <td> <input type="text" name="exam_minimum_marks[]"  value="{{ $old_value->exam_minimum_marks ?? 30 }}" class="form-control" id="minimum_marks_{{ $item->id }}"></td>
                                   
                                 </tr>
                                 @endforeach
                                 @endif
                                 @else
                                 <tr>
                                    <td class="text-center" colspan="12">No Data Found</td>
                                </tr>
                                @endif 
                                 <input type="hidden" value="{{ $search['exam_id'] ?? '' }}" name="exam_id">
                                 <input type="hidden" value="{{ $search['class_type_id'] ?? '' }}" name="class_type_id">
                              </tbody>
                             </table>
                          </div>
                       </div>
                    </div>
                    <div class="col-12">
                        <span class='text-danger'>Note: - [T]-Trival,  [AB]-Absent,  [M]-Medical,  [JL]-Join Late,  </span>
                    </div>
                           <div class="col-12">
                               <div class="card">
                               <div class="card-header border-transparent">
                                                    @if(Session::get('role_id'))   <button onclick="downloadCSV()" type='button'>Download CSV</button>@endif
                            
                                  <div class="card-tools mb-1">
                            
                                     <button type="button" class="btn btn-primary btn-tool" data-card-widget="collapse">
                                     <i class="fa fa-minus"></i>
                                     </button>
                                  </div>
                               </div>
                               <div class="card-body p-0" style="display: block;">
                                  <div class="table-responsive">
                                     <table class="table table-bordered table-striped m-0" id='thead_data_table'>
                                                             <thead> 
                                                                <tr>
                                                                   <th>S No</th>
                                                                   <th>Adm Number</th>
                                                                   <th>Student's Name</th>
                                                                   <th>Father's Name</th>
                                                                   @php
                                                                   $count =0;
                                                                   @endphp
                                                                   @if(!empty($subjects)) 
                                                                   @foreach($subjects as $key => $item )
                                                                   <th style="text-transform: capitalize;">@if($item->sub_name != '')
                                                                    {{$item->sub_name ?? ''}}
                                                                    
                                                                    @else
                                                                    {{$item->name ?? ''}}
                                                                    @endif</th>
                                                                   @endforeach
                                                                   @endif
                                                                </tr>
                                                             </thead>
                                                          
                                                             <tbody>
                                                                 @if($data2->count() != 0)
                                                                <!--<input type="text" value="{{$count ?? ''}}" name="sub_count" />-->
                                                                @if(!empty($data2)) 
                                                              
                                                                @foreach($data2 as $key=> $item)
                                                                <tr>
                                                                   <td>{{$key+1 ?? ""}}</td>
                                                                   <td>{{$item->admissionNo ?? ""}}</td>
                                                                   <td>{{$item->first_name ?? ""}} {{$item->last_name ?? ""}}</td>
                                                                   <td>{{$item->father_name ?? ""}}</td>
                                                                   <input type="hidden" name="admission_id[]" value="{{$item->id ?? ""}}"  class="form-control"  style="width:100px">
                                                                  @if(!empty($subjects)) 
                                                                   @foreach($subjects as $key => $item1)
                                                                   @php
                                                                     $old_marks = DB::table('fill_marks')
                                                                     ->where('exam_id', $search['exam_id'] ?? '')
                                                                     ->where('admission_id',$item->id)
                                                                      ->where('subject_id',$item1->id ?? '')
                                                                     ->where('branch_id',Session::get('branch_id'))
                                                                     ->where('session_id',Session::get('session_id'))
                                                                     ->whereNull('deleted_at')->first();
                                                                     $fill_min_max_marks = DB::table('fill_min_max_marks')
                                                                     ->where('exam_id', $search['exam_id'] ?? '')
                                                                      ->where('subject_id',$item1->id ?? '')
                                                                     ->where('branch_id',Session::get('branch_id'))
                                                                     ->where('session_id',Session::get('session_id'))
                                                                     ->whereNull('deleted_at')->first();
                                                                 
                                                                    @endphp
                                                                    <td>
                                                                        
                                                                         @php
                                                                        $stream = [];
                                                                       
                                                                       if($classOrderBy > 10)
                                                                       {
                                                                       $stream  = explode(",", $item->stream_subject ?? '');
                                                                       }
                                                                      
                                                                        @endphp
                            
                                                                        @if(!empty($old_marks))
                                                                           <input type='hidden' name='fill_marks_id[]' value='{{$old_marks->id ?? ''}}'/>
                                                                           @else
                                                                             <input type='hidden' name='fill_marks_id[]' value=''/>
                                                                          
                                                                        @endif
                                                                        
                                                                        
                                                                      @php
    $isEditable = !($classOrderBy > 10 && !in_array($item1->id, $stream));
    $placeholder = $isEditable ? ($item1->sub_name ?? $item1->name) : 'Not Assigned';
        $NotAssignedClass = $isEditable ? ($item1->sub_name ?? $item1->name) : 'bg-warning';

    $oldMarks = $old_marks->student_marks ?? '';
    if($oldMarks >= 0){
    $minMarksClass = isset($fill_min_max_marks) && $fill_min_max_marks->exam_minimum_marks >= $oldMarks ? 'bg-danger' : '';
    }else{
    $minMarksClass = '';
    }
@endphp

<input type="text" 
       name="student_marks[]" 
       class="marks_add_input marks_subject form-control numbers {{ $minMarksClass }} {{$NotAssignedClass}}"  
       data-subject_id="{{ $item1->id ?? '' }}" 
       data-old_marks="{{ $oldMarks }}" 
       placeholder="{{ $placeholder }}"  
       value="{{ $oldMarks }}" 
       oninput="this.value = this.value.toUpperCase()" 
       {{ $isEditable ? '' : 'readonly' }}/>

                            
                                                                   <span class='marks_visible text-light' >{{$old_marks->student_marks ?? ''}}</span>
                                                                     <input type='hidden' name='check_null[]' value='{{$old_marks->student_marks ?? null}}'/>
                                                                    <input type='hidden' name='subject_id_fill[]' value='{{$item1->id ?? ''}}'/>
                                                                    <input type='hidden' name='other_subject[]' value='{{$item1->other_subject ?? ''}}'/>
                                                                        
                                                                   </td>
                                                                   @endforeach
                                                                   
                                                                   @endif
                                                                </tr>
                                                                @endforeach
                                                                @endif
                                                                
                                                                <tr >
                                                                   <td colspan="30">
                                                                      <center><input type="submit" name="finish" value="Submit" class=" m-3 btn   btn-success"></center>
                                                                   </td>
                                                                </tr>
                                                                @else
                                                                 <tr>
                                                                    <td class="text-center" colspan="12">No Data Found</td>
                                                                </tr>
                                                                @endif
                                                             </tbody>
                                                          </table>
                                  </div>
                               </div>
                            </div>
                              
                           </div>
                        </form>
                     </div>
                  </div>
                  @endif
               </div>
            </div>
         </div>
      </div>
</section>
</div>
<script src="{{URL::asset('public/assets/school/js/jquery.min.js')}}"></script>


<script>
$(document).ready(function(){
    $('.marks_add_input').on('input',function(){
         $(this).removeClass('bg-danger');
       var subject_id = $(this).data('subject_id');
       var maximum_marks = parseInt($('#subject_' + subject_id).val());
       var student_marks = parseInt($(this).val());
       var oldMarks = parseInt($(this).data('old_marks'));
    var minimum_marks = parseInt($('#minimum_marks_' + subject_id).val());
        if(minimum_marks >= student_marks){
          $(this).addClass('bg-danger');
        }
        
       if(student_marks > maximum_marks){
           toastr.error('You cannot enter marks greater than the maximum allowed.');
           $(this).val(oldMarks);
       }
       
    });
    
    

        $('#class_type_id').on('change', function(e){
            
                $("#stream_id").val("");
                $("#stream_subject").html("");
                $("#stream_id_div").css("display","none");
                $("#stream_subject_div").css("display","none");
                
                $(".div_stream_id_").css("display","none");
                $(".div_subject_id").css("display","block");
                $('#subject_id').prop('required',true);
                $('#stream_subject_id').prop('required',false);
                $('#stream_subject_id').val('')
                $('#stream_id_').prop('required',false);
                $('#stream_id_').val('');

                var baseurl = "{{ url('/') }}";
            	var class_type_id = $(this).val();
            	  
                $.ajax({
                    headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
            	    url: baseurl + '/examData/' + class_type_id,
            	    success: function(data){
    	         	    $("#exam_id").html(data);
            	    }
            	});
        });       
        
        $('.numbers').keyup(function(){
  if ($(this).val() > 100){
      window.toastr.options = {
          "toastClass": "toast-success-create-campaign",
          "closeButton": false,
          "debug": false,
          "newestOnTop": true,
          "progressBar": false,
          "positionClass": "toast-bottom-right",
          "onclick": null,
          "showDuration": "100",
          "hideDuration": "100",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut",
          "maxOpened":1,
          "preventOpenDuplicates": true
}
     toastr.error("No numbers above 100");
    $(this).val('100');
  }
});


$('.marks_subject').on('blur', function(e){

    var id = $(this).data('subject_id');
    var marks = $(this).val();
    var validate_from = $(".validate_subject_" + id).val();

    var allowedChars = /^(\d+(\.\d+)?|A|B|C|D|T|AB|M|JL|F)?$/i;

    if (marks !== "" && !allowedChars.test(marks)) {
        toastr.error('Invalid input. Only numbers, floats,A ,B ,C ,D T, AB, M, JL, F, or empty value are allowed.');
        $(this).val('');
        return;
    }

    if (marks !== "" && !isNaN(parseFloat(marks)) && parseFloat(marks) > parseFloat(validate_from)) {
        toastr.error('Number is greater than maximum marks');
        $(this).val('');
    }

});

    });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        
        var max_min = $('.max_min').length;
        var max_array =[];
         $(".max_min").each(function(index, element) {
  max_array.push($('.max_min').eq(index).attr('data-max_min'));
  });
  
for (var i = 0; i < max_min; i++) {
    var maxArrayValue = max_array[i];
    $("#thead_data_table th:eq(" + (i+4) + ")").append("(" + maxArrayValue + ")");
}
        
           $('.marks_visible').hide();
        
        $('.numbers').keyup(function(){
            
            var data_id = $(this).data('subject_id');
            
            
            var get_id_data = $('#subject_'+data_id).val();
            
            
        
  if (parseInt($(this).val()) > parseInt(get_id_data)){
      window.toastr.options = {
          "toastClass": "toast-success-create-campaign",
          "closeButton": false,
          "debug": false,
          "newestOnTop": true,
          "progressBar": false,
          "positionClass": "toast-bottom-right",
          "onclick": null,
          "showDuration": "100",
          "hideDuration": "100",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut",
          "maxOpened":1,
          "preventOpenDuplicates": true
}
     toastr.error("No numbers above "+get_id_data);
    $(this).val('');
  }
});
    });
</script>
<script>
    function downloadCSV() {
       $('.marks_visible').show();
       var table = document.getElementById("thead_data_table");

       var csv = [];
       var rows = table.querySelectorAll("tr");
       for (var i = 0; i < rows.length; i++) {
           var row = [], cols = rows[i].querySelectorAll("td, th");
           for (var j = 0; j < cols.length; j++)
               row.push(cols[j].innerText);
           csv.push(row.join(","));
       }
       var csv_string = csv.join("\n");
       var filename = "export.csv";
       var link = document.createElement("a");
       link.style.display = "none";
       link.setAttribute("href", 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
       link.setAttribute("download", filename);
       document.body.appendChild(link);
       link.click();
       document.body.removeChild(link);
       $('.marks_visible').hide();
   }

</script>
@endsection

