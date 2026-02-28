@php
  $classType = Helper::classTypeExam();
  $getsubject = Helper::getSubject();
   $examTerm = DB::table('exam_terms')->where('branch_id',Session::get('branch_id'))->where('session_id',Session::get('session_id'))->whereNull('deleted_at')->get();
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
                    <h3 class="card-title"><i class="nav-icon fas fa fa-leanpub"></i> &nbsp;{{__('examination.Add Exams') }}</h3>
                    <div class="card-tools">
                    <a href="{{url('view/exam')}}" class="btn btn-primary  btn-sm" title="View Users"><i class="fa fa-eye"></i> {{ __('common.View') }} </a>
                    <a href="{{url('view/exam')}}" class="btn btn-primary  btn-sm" title="View Users"><i class="fa fa-arrow-left"></i> {{ __('common.Back') }} </a>
                    </div>
                    
                    </div>        
                <form id="quickForm" action="{{ url('add/exam') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row m-2">
                        <div class="col-md-3">
            			<div class="form-group">
            				<label>{{ __('Exam Term') }}</label>
            				<select class="select2 form-control exam_term_id" id="exam_term_id" name="exam_term_id" >
                                 <option value="">{{ __('messages.Select') }}</option>
                                @if(!empty($examTerm)) 
                                     @foreach($examTerm as $type)
                                     <option value="{{ $type->id}} ">{{ $type->name ?? ''  }} </option>
                                     @endforeach
                                @endif
                                    @error('exam_term_id')
                                     <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                     </span>
                                @enderror
                              </select>
            		    </div>
            		</div>
                       <div class="col-md-3">
            			<div class="form-group">
            				<label style="color:red;">{{ __('examination.Exam Name') }}*</label>
            				<input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('examination.Exam Name') }}" value="{{old('name')}}">
                             @error('name')
            					<span class="invalid-feedback" role="alert">
            						<strong>{{ $message }}</strong>
            					</span>
            				@enderror
            		    </div>
            		</div>
                     
            <!--		<div class="col-md-3">
						<div class="form-group">
							<label style="color:red;">{{ __('common.Class') }}*</label>
							<select class="form-control select2 @error('class_type_id') is-invalid @enderror" id="class_type_id" name="class_type_id">
								<option value="">{{ __('common.Select') }}</option>
								@if(!empty($classType))
								@foreach($classType as $type)
								<option value="{{ $type->id ?? ''  }}">{{ $type->name ?? ''  }}</option>
								@endforeach
								@endif
							</select>
							@error('class_type_id')
            					<span class="invalid-feedback" role="alert">
            						<strong>{{ $message }}</strong>
            					</span>
            				@enderror
						</div>
					</div>-->
            		
                    <!--<div class="col-md-3">
            			<div class="form-group">
            				<label style="color:red;">{{ __('examination.Exam Date') }}</label>
                            <input type="date" id="exam_date" name="exam_date" class="form-control @error('exam_date') is-invalid @enderror" value="{{old('exam_date')}}">
                             @error('exam_date')
            					<span class="invalid-feedback" role="alert">
            						<strong>{{ $message }}</strong>
            					</span>
            				@enderror
            		    </div>
            		</div>
            		
                    <div class="col-md-3">
            			<div class="form-group">
            				<label style="color:red;">{{ __('examination.Exam Code') }}</label>
                            <input type="text" id="exam_code" name="exam_code" placeholder="{{ __('examination.Exam Code') }}" class="form-control @error('exam_code') is-invalid @enderror" value="{{old('exam_code')}}">
                             @error('exam_code')
            					<span class="invalid-feedback" role="alert">
            						<strong>{{ $message }}</strong>
            					</span>
            				@enderror
            		    </div>
            		</div>
        		
                    <div class="col-md-3">
            			<div class="form-group">
            				<label>{{ __('examination.Time Duration In Minutes') }}</label>
                            <input type="number" id="duration" name="duration" class="form-control @error('duration') is-invalid @enderror" min="5" step="5"value="{{old('duration') ?? 5}}" >
                             @error('duration')
            					<span class="invalid-feedback" role="alert">
            						<strong>{{ $message }}</strong>
            					</span>
            				@enderror
            		    </div>
            		</div>-->        
                    <div class="col-md-12">
            			<div class="form-group">
            				<label>{{ __('examination.Description') }}</label>
                            <textarea id="description" name="description"  placeholder="{{ __('examination.Description') }}" class="form-control @error('description') is-invalid @enderror ">{{old('description')}}</textarea>
                             @error('description')
            					<span class="invalid-feedback" role="alert">
            						<strong>{{ $message }}</strong>
            					</span>
            				@enderror
            		    </div>
            		</div> 
        		
		        </div>

                <div class="row m-2 pb-2">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary ">{{ __('common.Submit') }}</button>
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
    $("[type='number']").keypress(function (evt) {
    evt.preventDefault();
    
    alert("Use only Buttons For Increase/Decrease Time")
    
});

 window.onload=function(){
var today = new Date().toISOString().split('T')[0];
document.getElementsByName("exam_date")[0].setAttribute('min', today);

var now = new Date();

var day = ("0" + now.getDate()).slice(-2);
var month = ("0" + (now.getMonth() + 1)).slice(-2);

var today = now.getFullYear()+"-"+(month)+"-"+(day) ;

document.getElementsByName("exam_date")[0].value = today;
    }

</script>



@endsection