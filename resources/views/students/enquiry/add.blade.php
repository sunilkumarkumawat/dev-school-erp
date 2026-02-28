@php
$getTypeclass = Helper::classType();
$getCountry = Helper::getCountry();
$getState = Helper::getState();
$getCity = Helper::getCity();
$getgenders = Helper::getgender();
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
              <h3 class="card-title"><i class="fa fa-address-book-o"></i> &nbsp;{{ __('student.Students Enquiry') }} </h3>
              <div class="card-tools">
                <a href="{{url('enquiryView')}}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> {{ __('common.View') }}</a>
                <a href="{{url('reception_file')}}" class="btn btn-primary btn-sm"><i class="fa fa-arrow-left"></i> {{ __('common.Back') }} </a>
              </div>
            </div>

            <form id="form-submit" action="{{ url('enquiryAdd') }}" method="post" enctype="multipart/form-data">
              @csrf

              <div class="row m-2">
                <div class="col-md-3">
                  <div class="form-group">
                    <label style="color:red;">{{ __('Full Name') }}*</label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                           onkeydown="return /[a-zA-Z ]/i.test(event.key)" id="first_name" name="first_name"
                           placeholder="{{ __('common.First Name') }}" value="{{ old('first_name') }}">
                    @error('first_name')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>

                <!-- Student Mobile (added) -->
                <div class="col-md-3">
                  <div class="form-group">
                    <label style="color:red;">{{ __('common.Mobile') }}*</label>
                    <input type="tel" class="form-control @error('mobile') is-invalid @enderror" id="mobile"
                           name="mobile" placeholder="{{ __('common.Mobile') }}" value="{{ old('mobile') }}"
                           maxlength="10" minlength="10" onkeypress="return isNumber(event)">
                    @error('mobile')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label style="color:red;">{{ __('common.Gender') }}*</label>
                    <select class="form-control @error('gender_id') is-invalid @enderror select2" id="gender_id" name="gender_id">
                      <option value="">{{ __('common.Select') }}</option>
                      @if(!empty($getgenders))
                        @foreach($getgenders as $value)
                          <option value="{{ $value->id }}" {{ ($value->id == old('gender_id')) ? 'selected' : '' }}>
                            {{ $value->name ?? '' }}
                          </option>
                        @endforeach
                      @endif
                    </select>
                    @error('gender_id') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>DOB</label>
                    <input type="date" class="form-control" id="dob" name="dob" placeholder=" Date Of Birth" value="{{ old('dob') }}">
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label style="color:red;">{{ __('common.Fathers Name') }}*</label>
                    <input type="text" class="form-control @error('father_name') is-invalid @enderror"
                           onkeydown="return /[a-zA-Z ]/i.test(event.key)" id="father_name" name="father_name"
                           placeholder="{{ __('common.Fathers Name') }}" value="{{ old('father_name') }}">
                    @error('father_name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>{{ __('common.Mothers Name') }}</label>
                    <input type="text" class="form-control @error('mother_name') is-invalid @enderror"
                           onkeydown="return /[a-zA-Z ]/i.test(event.key)" id="mother_name" name="mother_name"
                           placeholder="{{ __('common.Mothers Name') }}" value="{{ old('mother_name') }}">
                    @error('mother_name') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                  </div>
                </div>


                <!-- class -->
                <div class="col-md-3">
                  <div class="form-group">
                    <label>{{ __('common.Class') }}</label>
                    <select class="select2 form-control" id="class_type_id" name="class_type_id">
                      <option value="">{{ __('common.Select') }}</option>
                      @if(!empty($getTypeclass))
                        @foreach($getTypeclass as $type)
                          <option value="{{ $type->id ?? '' }}" {{ ($type->id == old('class_type_id')) ? 'selected' : '' }}>
                            {{ $type->name ?? '' }}
                          </option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>

                <!-- email -->
                <div class="col-md-3">
                  <div class="form-group">
                    <label>{{ __('common.E-Mail') }}</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('common.E-Mail') }}" value="{{ old('email') }}">
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>{{ __('No Of Child ') }}</label>
                    <input type="text" class="form-control" id="no_of_child" name="no_of_child" placeholder="{{ __('No Of Child ') }}" value="{{ old('no_of_child') }}">
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="assigned">Assigned By</label>
                    <select class="form-control select2" id="assigned_by" name="assigned_by" autocomplete="off">
                      <option value="">Select</option>
                      @if(!empty($users))
                        @foreach($users as $u)
                          <option value="{{ $u->id }}" {{ (old('assigned_by')==$u->id)?'selected':'' }}>{{ $u->first_name ?? $u->userName }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="reference">Reference</label>
                    <select class="form-control select2" id="reference_id" name="reference_id" autocomplete="off">
                      <option value="">Select</option>
                      @if(!empty($references))
                        @foreach($references as $ref)
                          <option value="{{ $ref->id }}" {{ (old('reference_id')==$ref->id)?'selected':'' }}>{{ $ref->name }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="response_select">Response</label>
                    <select class="form-control select2" id="response_id" name="response_id" autocomplete="off">
                      <option value="">Select</option>
                      @if(!empty($responses))
                        @foreach($responses as $resp)
                          <option value="{{ $resp->id }}" {{ (old('response_id')==$resp->id)?'selected':'' }}>{{ $resp->name }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>

                <!-- previous school textarea -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label>{{ __('Previous School') }}</label>
                    <textarea class="form-control" id="previous_school" name="previous_school" placeholder="{{ __('Previous School Name') }}" rows="2">{{ old('previous_school') }}</textarea>
                  </div>
                </div>

                <!-- response textarea (renamed) -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label>{{ __('Response') }}</label>
                    <textarea class="form-control" id="response" name="response" placeholder="{{ __('Response') }}" rows="2">{{ old('response') }}</textarea>
                  </div>
                </div>

                <!-- note (fixed name) -->
                <div class="col-md-12">
                  <div class="form-group">
                    <label>{{ __('Note') }}</label>
                    <textarea class="form-control" id="note" name="note" placeholder="{{ __('Note') }}" rows="2">{{ old('note') }}</textarea>
                  </div>
                </div>

              </div>

              <div class="row m-2">
                <div class="col-md-12 text-center pb-2">
                  <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                </div>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@section('scripts')
<script>
  $(function () {
    $('.select2').select2();
    $('.select2bs4').select2({ theme: 'bootstrap4' });
  });
  function isNumber(evt){ var ch = String.fromCharCode(evt.which); if(!(/[0-9]/).test(ch)) evt.preventDefault(); }
</script>
@endsection
@endsection
