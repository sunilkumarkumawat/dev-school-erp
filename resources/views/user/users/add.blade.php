@php
$getCountry = Helper::getCountry();
$getState = Helper::getState();
$getCity = Helper::getCity();
$roleType = Helper::roleType();
$getSetting = Helper::getSetting();

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
                            <h3 class="card-title"><i class="fa fa-desktop"></i> &nbsp;{{ __('user.Add User') }} </h3>
                            <div class="card-tools">
                                <a href="{{url('viewUser')}}"
                                    class="btn btn-primary  btn-sm {{ Helper::permissioncheck(6)->view ? '' : 'd-none' }}"
                                    title="View Users"><i class="fa fa-eye"></i> {{ __('common.View') }} </a>
                                <a href="{{url('user_dashboard')}}" class="btn btn-primary  btn-sm"
                                    title="View Users"><i class="fa fa-arrow-left"></i> {{ __('common.Back') }} </a>
                            </div>

                        </div>
                        <form id="form-submit" action="{{ url('addUser') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row m-2">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;">{{ __('Branch Access') }} *</label>
                                        <select
                                            class="form-control @error('access_branch_id') is-invalid @enderror select2 "
                                            multiple="" name="access_branch_id[]">
                                            <option value="">{{ __('common.Select') }}</option>
                                            @if(!empty($branch))
                                            @foreach($branch as $Branch)
                                            <option value="{{ $Branch->id ?? ''  }}" {{ $Branch->id ==
                                                Session::get('access_branch_id') ? 'selected' : ''}}>{{
                                                $Branch->branch_name ?? '' }}</option>
                                            @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;">{{ __(' Name') }} *</label>
                                        <input type="text"
                                            class="form-control @error('first_name') is-invalid @enderror"
                                            onkeydown="return /[a-zA-Z ]/i.test(event.key)" id="first_name"
                                            name="first_name" value="{{old('first_name')}}"
                                            placeholder="{{ __('common.First Name') }}">

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;">{{ __('common.Mobile No.') }} *</label>
                                        <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                            id="mobile" name="mobile" value="{{old('mobile')}}"
                                            placeholder="{{ __('common.Mobile No.') }} " maxlength="10"
                                            onkeypress="javascript:return isNumber(event)">


                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;">{{ __('common.Email') }} *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="exampleInputEmail1" name="email" value="{{old('email')}}"
                                            placeholder="{{ __('common.Email') }}">


                                    </div>
                                </div>
                                <!--<div class="col-md-2" >
                                    <div class="form-group">
                                     <label>Country</label>
                                      <select class="form-control select2" name="country" id="country_id">
                                          @if(!empty($getCountry)) 
                                              @foreach($getCountry as $country)
                                                 <option value="{{ $country->id ?? ''  }}" {{ ($country->id == Session::get('countries_id')) ? 'selected' : '' }}>{{ $country->name ?? ''  }}</option>
                                              @endforeach
                                          @endif
                                      
                                      
                                        	@error('country')
                        						<span class="invalid-feedback" role="alert">
                        							<strong>{{ $message }}</strong>
                        						</span>
                        					@enderror
                                      </select>
                                      </div>
                                    </div>-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="State" class="required" style="color:red;">{{ __('common.State')
                                            }}*</label>
                                        <select class="select2 form-control @error('state') is-invalid @enderror"
                                            id="state_id" name="state">
                                            <option value="">{{ __('common.Select') }}</option>
                                            @if(!empty($getState))
                                            @foreach($getState as $state)
                                            <option value="{{ $state->id ?? ''}}" {{ ($state->id ==
                                                $getSetting->state_id) ? 'selected' : '' }}>{{ $state->name ?? ''}}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="City" style="color:red;">{{ __('common.City') }}*</label>
                                        <select class="select2 form-control @error('city') is-invalid @enderror"
                                            name="city" id="city_id">
                                            <option value="">{{ __('common.Select') }}</option>
                                            @if(!empty($getCity))
                                            @foreach($getCity as $cities)
                                            <option value="{{ $cities->id ?? ''  }}" {{ ($cities->id ==
                                                $getSetting->city_id) ? 'selected' : '' }}>{{ $cities->name ?? '' }}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;">{{ __('common.Address') }} *</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" value="{{old('address')}}"
                                            placeholder="{{ __('common.Address') }}">

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;">{{ __('user.User Name') }}*</label>
                                        <input type="text" class="form-control @error('userName') is-invalid @enderror"
                                            id="userName" name="userName" value="{{old('userName')}}"
                                            placeholder="{{ __('user.User Name') }}">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label style="color:red;">{{ __('common.Password') }}*</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" value="{{old('password')}}"
                                        placeholder="{{ __('common.Password') }}">


                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="color:red;">{{ __('user.Role') }}*</label>
                                        <div class="input-group mb-3">
                                            <select class="form-control @error('role_id') is-invalid @enderror"
                                                name="role_id" id="role_id">
                                                <option value="">{{ __('common.Select') }}</option>
                                                @if(!empty($roleType))
                                                @php
                                                $array = [1,3];
                                                @endphp
                                                @foreach($roleType as $item)
                                                @if (in_array($item->id, $array)) {
                                                @else
                                                <option value="{{ $item->id ?? ''  }}">{{ $item->name ?? '' }}</option>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select>
                                            <div class="input-group-append">
                                                <a href="{{ url('role_add') }}" target="_blank">
                                                    <button class="btn btn-primary" type="button" style="height: 100%;">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Class Name</label>
                                        <div class="custom-multi" id="classMulti">
                                            <button type="button" class="custom-btn" onclick="toggleDropdown()">
                                                <span id="selectedText">None selected Class</span>
                                                <span>▼</span>
                                            </button>
                                            <div class="custom-dropdown" id="ClassDropdown">
                                
                                                <!-- ✅ Select All Fixed -->
                                                <label class="dropdown-item">
                                                    <input type="checkbox" id="selectAll" onchange="selectAllFees(this)">
                                                    Select All
                                                </label>
                                
                                                <div id="feesOptions">
                                
                                                    @php
                                                        $selectedClasses = explode(',', $data->class_type_id ?? '');
                                                    @endphp
                                
                                                    @if(!empty(Helper::classType()))
                                                        @foreach(Helper::classType() as $type)
                                                            <label class="dropdown-item">
                                                                <input type="checkbox"
                                                                       class="class-checkbox"
                                                                       value="{{ $type->id }}"
                                                                       data-name="{{ $type->name }}"
                                                                       {{ in_array($type->id, $selectedClasses) ? 'checked' : '' }}
                                                                       onchange="updateSelectedText()">
                                                                {{ $type->name }}
                                                            </label>
                                                        @endforeach
                                                    @endif
                                
                                                </div>
                                            </div>
                                        </div>
                                
                                        <!-- Hidden Field -->
                                        <input type="hidden" name="class_type_id" id="class_type_id"
                                               value="{{ $data->class_type_id ?? '' }}">
                                    </div>
                                </div>




                            </div>
                            <div class=" col-md-12 title">
                                <h5 style="color:red">Document Upload:-</h5>
                            </div>
                            <hr>
                            <div class="row m-2">

                                <!--camera img capture-->

                                <div class="row col md-12">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Photo</label>
                                            <input type="file" class="form-control " id="photo" name="photo" value=""
                                                accept="image/png, image/jpg, image/jpeg">
                                            <p class="text-danger" id="photo_error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Id Proof</label>
                                            <input type="file" class="form-control " id="id_proof" name="id_proof"
                                                value="{{old('id_proof')}}" accept="image/png, image/jpg, image/jpeg">
                                            <p class="text-danger" id="proof_error"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Qualification Proof</label>
                                            <input type="file" class="form-control " id="qualification_proof"
                                                name="qualification_proof" value="{{old('qualification_proof')}}"
                                                accept="image/png, image/jpg, image/jpeg">
                                            <p class="text-danger" id="qualification_errors"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Experience Letter</label>
                                            <input type="file" class="form-control " id="experience_letter"
                                                name="experience_letter" value="{{old('experience_letter')}}"
                                                accept="image/png, image/jpg, image/jpeg">
                                            <p class="text-danger" id="letter_errors"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Pan Card No.</label>
                                            <input type="text" class="form-control " id="pan_card" name="pan_card"
                                                placeholder="Pan Card No." value="{{old('pan_card')}}" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <input type="text" class="form-control " id="bank" name="bank"
                                                placeholder="Bank Name" value="{{old('bank')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Bank Account No.</label>
                                            <input type="text" class="form-control " id="account_no" name="account_no"
                                                placeholder="Bank Account No." value="{{old('account_no')}}"
                                                maxlength="18">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Bank IFSC Code</label>
                                            <input type="text" class="form-control " id="ifsc_code" name="ifsc_code"
                                                placeholder="Bank IFSC Code" value="{{old('ifsc_code')}}"
                                                maxlength="11">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Salary</label>
                                            <input type="text" class="form-control " id="salary" name="salary"
                                                placeholder="Salary" value="{{old('salary')}}"
                                                onkeypress="javascript:return isNumber(event)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-2 pb-2">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-submit">{{ __('common.submit')
                                        }}</button>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('#photo').change(function (e) {
            $('#image_error').html("");
            var fileName = $(this).val();
            var extension = fileName.split(".").pop();
            if (
                extension.toLowerCase() === "png" ||
                extension.toLowerCase() === "jpg" ||
                extension.toLowerCase() === "jpeg"
            ) {
                if (e.target.files[0].size > Img_Size) {
                    $('#image_error').html("please select Image Size under 2MB");
                    $(this).val('');
                } else {
                    $('#image_error').html("");
                }
            } else {
                $('#image_error').html("Image Size File");
                $(this).val('');
            }
        });
    });
</script>

<style>
    #image_error {
        font-weight: bold;
        font-size: 14px;
    }

    .blink_me {
        animation: blinker 1s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }
</style>


@endsection