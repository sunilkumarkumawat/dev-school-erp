@php
    $getCountry = Helper::getCountry();
    $getState = Helper::getState();
    $getCity = Helper::getCity();
    $getaccounts = Helper::getaccount();
    $getSession=Helper::getSession();

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
                    <h3 class="card-title"><i class="fa fa-cogs"></i> &nbsp;{{ __('setting.Edit Setting') }} </h3>
                    <div class="card-tools">
                        <a href="{{url('viewSetting')}}" class="btn btn-primary  btn-sm" title="Back"><i class="fa fa-arrow-left"></i> {{ __('common.Back') }} </a>
                    </div>
                </div> 

        <div class="card-body">
         <form id="form-submit-edit" action="{{ url('editSetting') }}/{{$data->id}}" method="post"  enctype="multipart/form-data">   
         @csrf            
            <div class="row">
                 @if(Session::get('role_id') == 1)
                <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="branch" style="color:red;">{{ __('setting.Branch') }}*</label>
				    	 <select class="form-control @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" 
				    	 {{$data->branch_id == null ? '' :'disabled'}}
				    	 >
				    	     <option value="">Select</option>
				    	     @if(!empty($branch)) 
                                      @foreach($branch as $Branch)
                                         <option value="{{ $Branch->id ?? ''  }}"{{ ( $Branch['id'] == $data['branch_id']) ? 'selected' : '' }}>{{ $Branch->branch_name ?? ''  }}</option>
                                      @endforeach
                                  @endif
					       </select>	
			       
				    </div>
			    </div>
			    @endif
			    <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="Username" style="color:red;">{{ __('common.Name') }}*</label>
				    	<input type="text" class="form-control @error('name') is-invalid @enderror" onkeydown="return /[a-zA-Z ]/i.test(event.key)" id="name" name="name" placeholder="{{ __('common.Name') }}" value="{{ $data->name }}">
				
				    </div>
			    </div>                
                
			    <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="Username" style="color:red;">{{ __('common.Mobile No.') }}*</label>
				    	<input type="numbre" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" placeholder="{{ __('common.Mobile No.') }}" value="{{ $data->mobile }}" maxlength="10" onkeypress="javascript:return isNumber(event)">
				
				    </div>
			    </div>                
                
			    <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="Username" style="color:red;">{{ __('common.E-Mail') }}*</label>
				    	<input type="text" class="form-control @error('gmail') is-invalid @enderror" id="gmail" name="gmail" placeholder="{{ __('common.E-Mail') }}" value="{{ $data->gmail }}">
				
				    </div>
			    </div>  
			 <div class="col-md-3" >
                <div class="form-group">
                 <label>{{ __('common.Country') }}</label>
                  <select class="select2 form-control select2" name="country_id" id="country_id" >
                  	<option value="">{{ __('common.Select') }}</option>
                      @if(!empty($getCountry)) 
                          @foreach($getCountry as $country)
                             <option value="{{ $country->id ?? ''  }}" {{ ( $country['id'] == $data['country_id']) ? 'selected' : '' }}>{{ $country->name ?? ''  }}</option>
                          @endforeach
                      @endif
                    
              </select>
              </div>
            </div>
			<div class="col-md-3">
				<div class="form-group"> 
					<label for="State" class="required">{{ __('common.State') }}</label>
					<select class="select2 form-control" id="state_id" name="state_id">
						<option value="">{{ __('common.Select') }}</option>
                @if(!empty($getState)) 
                      @foreach($getState as $state)
                         <option value="{{ $state->id ?? ''}}" {{ ( $state['id'] == $data['state_id']) ? 'selected' : '' }}>{{ $state->name ?? ''}}</option>
                      @endforeach
                @endif
                  
                    </select>
				
				</div>
			</div>
			<div class="col-md-3">
			    <div class="form-group">
			        <label for="City">{{ __('common.City') }}</label>
			        <select class="select2 form-control" name="city_id" id="city_id" >
			        	<option value="">{{ __('common.Select') }}</option>
			            @if(!empty($getcitys)) 
                      @foreach($getcitys as $cities)
                         <option value="{{ $cities->id ?? ''  }}" {{ ( $cities['id'] == $data['city_id']) ? 'selected' : '' }}>{{ $cities->name ?? ''  }}</option>
                      @endforeach
                  @endif
				
					</select>
			    </div>
			</div>	    
                
			    <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="Username" style="color:red;">{{ __('common.Pin Code') }}*</label>
				    	<input type="numbre" class="form-control @error('pincode') is-invalid @enderror" onkeypress="javascript:return isNumber(event)" maxlength="6" id="pincode" name="pincode" placeholder="{{ __('common.Pin Code') }}" value="{{ $data->pincode }}">
				
				    </div>
			    </div>   
			    
			     <div class="col-md-3">
				    <div class="form-group"> 
					    <label for="Username" style="color:red;">{{ __('common.Address') }}*</label>
				    	<input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="{{ __('common.Address') }}" value="{{ $data->address }}">
				
				    </div>
			    </div>			    
               
                <div class="col-md-2">
				    <div class="form-group"> 
					    <label for="watermark_image" class="required">{{ __('Water Mark Image') }}</label>
				    	 <input type="file" class="form-control  @error('watermark_image') is-invalid @enderror" id="watermark_image" name="watermark_image" value="{{ $data->watermark_image }}" accept="image/png, image/jpg, image/jpeg">
										 <p class="text-danger" id="image_erro"></p>
					    
				    </div>
			    </div>
			    <div class="col-md-1 mt-4">
                    <td><img src="{{ env('IMAGE_SHOW_PATH').'/setting/watermark_image/'.$data['watermark_image'] }}" width="40px" height="40px" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'/default/no_image.png' }}'"></td>
                </div>
              
               <!-- <div class="col-md-2">
				    <div class="form-group"> 
					    <label for="right_logo" class="required">{{ __('setting.Right Logo') }}</label>
				    	 <input type="file" class="form-control  @error('right_logo') is-invalid @enderror" id="right_logo" name="right_logo" value="{{ $data->right_logo }}" accept="image/png, image/jpg, image/jpeg">
										 <p class="text-danger" id="image_erro"></p>
					    	@error('right_logo')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				    </div>
			    </div>
			     <div class="col-md-1 mt-4">
                    <td><img src="{{ env('IMAGE_SHOW_PATH').'/setting/right_logo/'.$data['right_logo'] }}" width="40px" height="40px" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/no_image.png' }}'"></td>
                </div>-->
                <div class="col-md-2">
				    <div class="form-group"> 
					    <label for="left_logo" class="required">{{ __('setting.Left Logo') }}</label>
				    	<input type="file" class="form-control  @error('left_logo') is-invalid @enderror" id="left_logo" name="left_logo" value="{{ $data->left_logo }}" accept="image/png, image/jpg, image/jpeg">
										 <p class="text-danger" id="image_errors"></p>
					
				    </div>
			    </div>
			     <div class="col-md-1 mt-4">
                    <td><img src="{{ env('IMAGE_SHOW_PATH').'/setting/left_logo/'.$data['left_logo'] }}" width="40px" height="40px" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/no_image.png' }}'"></td>
                </div>
                <div class="col-md-2">
				    <div class="form-group"> 
					    <label for="seal_sign" class="required">{{ __('setting.Seal & Sign.') }}</label>
				    	<input type="file" class="form-control  @error('seal_sign') is-invalid @enderror" id="seal_sign" name="seal_sign" value="{{ $data->seal_sign }}" accept="image/png, image/jpg, image/jpeg">
										 <p class="text-danger" id="image_er"></p>
					
				    </div>
			    </div>
			     <div class="col-md-1 mt-4">
                    <td><img src="{{ env('IMAGE_SHOW_PATH').'/setting/seal_sign/'.$data['seal_sign'] }}" width="40px" height="40px" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/no_image.png' }}'"></td>
                </div>
      <!--          <div class="col-md-2">-->
				  <!--  <div class="form-group"> -->
					 <!--   <label for="principal_sign" class="required">{{ __('Principal & Sign.') }}</label>-->
				  <!--  	<input type="file" class="form-control  @error('principal_sign') is-invalid @enderror" id="principal_sign" name="principal_sign" value="{{ $data->principal_sign }}" accept="image/png, image/jpg, image/jpeg">-->
						<!--				 <p class="text-danger" id="image_er"></p>-->
						<!--@error('principal_sign')-->
						<!--<span class="invalid-feedback" role="alert">-->
						<!--	<strong>{{ $message }}</strong>-->
						<!--</span>-->
				  <!--  	@enderror-->
				  <!--  </div>-->
			   <!-- </div>-->
			   <!--  <div class="col-md-1 mt-4">-->
      <!--              <td><img src="{{ env('IMAGE_SHOW_PATH').'/setting/principal_sign/'.$data['principal_sign'] }}" width="40px" height="40px" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/no_image.png' }}'"></td>-->
      <!--          </div>-->
      <!--          <div class="col-md-2">-->
				  <!--  <div class="form-group"> -->
					 <!--   <label for="exam_sign" class="required">{{ __('Exam & Sign.') }}</label>-->
				  <!--  	<input type="file" class="form-control  @error('exam_sign') is-invalid @enderror" id="exam_sign" name="exam_sign" value="{{ $data->exam_sign }}" accept="image/png, image/jpg, image/jpeg">-->
						<!--				 <p class="text-danger" id="image_er"></p>-->
						<!--@error('exam_sign')-->
						<!--<span class="invalid-feedback" role="alert">-->
						<!--	<strong>{{ $message }}</strong>-->
						<!--</span>-->
				  <!--  	@enderror-->
				  <!--  </div>-->
			   <!-- </div>-->
			   <!--  <div class="col-md-1 mt-4">-->
      <!--              <td><img src="{{ env('IMAGE_SHOW_PATH').'/setting/exam_sign/'.$data['exam_sign'] }}" width="40px" height="40px" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/no_image.png' }}'"></td>-->
      <!--          </div>-->
     <!--           <div class="col-md-2">-->
				 <!--   <div class="form-group"> -->
					<!--    <label for="treasurer_sign" class="required">{{ __('Treasurer & Sign.') }}</label>-->
				 <!--   	<input type="file" class="form-control  @error('treasurer_sign') is-invalid @enderror" id="treasurer_sign" name="treasurer_sign" value="{{ $data->treasurer_sign }}" accept="image/png, image/jpg, image/jpeg">-->
					<!--					 <p class="text-danger" id="image_er"></p>-->
					<!--	@error('treasurer_sign')-->
					<!--	<span class="invalid-feedback" role="alert">-->
					<!--		<strong>{{ $message }}</strong>-->
					<!--	</span>-->
				 <!--   	@enderror-->
				 <!--   </div>-->
			  <!--  </div>-->
			  <!--   <div class="col-md-1 mt-4">-->
     <!--               <td><img src="{{ env('IMAGE_SHOW_PATH').'/setting/treasurer_sign/'.$data['treasurer_sign'] }}" width="40px" height="40px" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/no_image.png' }}'"></td>-->
     <!--           </div>-->
			  <!--  <div class="col-md-3">-->
				 <!--   <div class="form-group"> -->
					<!--    <label for="Username">{{ __('setting.Tin No.') }}</label>-->
				 <!--   	<input type="text" class="form-control @error('tin_no') is-invalid @enderror"  id="tin_no" name="tin_no" placeholder="{{ __('setting.Tin No.') }}" value="{{ $data->tin_no }}">-->
					<!--	@error('tin_no')-->
					<!--	<span class="invalid-feedback" role="alert">-->
					<!--		<strong>{{ $message }}</strong>-->
					<!--	</span>-->
					<!--@enderror-->
				 <!--   </div>-->
			  <!--  </div>-->
              <div class="col-md-3">
                         <div class="form-group">
                         <label>{{ __('Current Active Session') }}</label>
                      <select class="form-control " id="current_active_session_id" name="current_active_session_id">
                             @if(!empty($getSession)) 
                                  @foreach($getSession as $type)
                                     <option value="{{ $type->id ?? ''  }} " {{ ( $type->id == $data['current_active_session_id']) ? 'selected' : '' }}>{{ $type->from_year ?? ''  }} - {{ $type->to_year ?? ''  }}</option>
                                  @endforeach
                              @endif
                             </select>
                        
                    </div>
                </div>
                
                  <div class="col-md-3">
				    <div class="form-group"> 
					   <label for="apk">Upload APK</label>
                        
                        <input type="file" class="form-control  @error('apk') is-invalid @enderror" id="apk" name="apk" value="{{ $data->apk }}" accept=".apk">
					
				    </div>
			    </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="firebaseNotification">Notifications</label><br>
                
                        <label class="switch">
                            <input type="checkbox" name="firebase_notification" id="firebaseNotification"
                                   value="1" {{ $data->firebase_notification ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
              <!--<div class="col-md-3">-->
              <!--           <div class="form-group">-->
              <!--           <label>{{ __('Login With Otp') }}</label>-->
              <!--        <select class="form-control " name="loginWithOtp">-->
              <!--              <option value='No' {{$data->loginWithOtp == 'No' ? 'selected' : ''}}>No</option>-->
              <!--              <option value='Yes' {{$data->loginWithOtp == 'Yes' ? 'selected' : ''}}>Yes</option>-->
              <!--               </select>-->
                        
              <!--      </div>-->
              <!--  </div>-->
                
             <div class="col-md-12 text-center">
    			<button type="submit" class="btn btn-primary btn-submit">{{ __('common.Update') }}</button>
    		</div>



            
            </div>
            </form>
        </div>

		
    </div>
</div>        

<!--<form class="col-md-3" method='post' action='{{url("addVillageList")}}'>-->
<!--    @csrf-->
<!--  <div >-->
<!--               <label>{{ __('Add Village/City') }}</label>-->
<!--                <input type="text"  class="form-control mb-2" name='village_name' placeholder="Enter village name" required>-->
<!--                <button id="addVillageBtn" class="btn btn-primary">Add Village</button>-->
<!--            </div>-->
            
<!--            </form>-->
<!--            <div class="col-md-9">-->
<!--                  <label>{{ __('Village/City List') }}</label>-->
<!--                  <div  class="d-flex flex-wrap m-1">-->
<!--                  
<!--                    @if(!empty($list))-->
                    
<!--                    @foreach($list as $item)-->
                    
<!--                     <div class="village-item pl-1  pr-1 d-flex align-items-center bg-white mr-2 " style='border:1px solid #d8d8d8'>-->
<!--                            <span class='mr-1'>{{$item->name ?? ''}}</span>-->
<!--                    <form class="col-md-3" method='post' action='{{url("deleteVillageList")}}'>-->
<!--    @csrf-->
<!--    <input type='hidden' name='delete_id' value='{{$item->id}}' />-->
<!--                            <button class="btn text-danger btn-sm deleteBtn">&times;</button>-->
<!--         </form-->
<!--                        </div>-->
<!--                    @endforeach-->
<!--                    @endif-->
<!--                </div>-->
<!--            </div>-->
<!--</div>-->

 


</div>


</div>
</section>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('#right_logo').change(function(e){
            $('#image_erro').html("");
            var fileName = $(this).val();
        var extension = fileName.split(".").pop();
        if (
          extension.toLowerCase() === "png" ||
          extension.toLowerCase() === "jpg" ||
          extension.toLowerCase() === "jpeg"
        ) {
            if (e.target.files[0].size > Img_Size) {
                $('#image_erro').html("please select Image Size under 2MB");
                $(this).val('');
            }else{
                $('#image_erro').html("");
            }
        }else{
            $('#image_erro').html("Image Size File");
            $(this).val('');
        }
        });
    });
    $(document).ready(function(){
        $('#left_logo').change(function(e){
            $('#image_errors').html("");
            var fileName = $(this).val();
        var extension = fileName.split(".").pop();
        if (
          extension.toLowerCase() === "png" ||
          extension.toLowerCase() === "jpg" ||
          extension.toLowerCase() === "jpeg"
        ) {
            if (e.target.files[0].size > Img_Size) {
                $('#image_errors').html("please select Image Size under 2MB");
                $(this).val('');
            }else{
                $('#image_errors').html("");
            }
        }else{
            $('#image_errors').html("Image Size File");
            $(this).val('');
        }
        });
    });
    $(document).ready(function(){
        $('#seal_sign').change(function(e){
            $('#image_er').html("");
            var fileName = $(this).val();
        var extension = fileName.split(".").pop();
        if (
          extension.toLowerCase() === "png" ||
          extension.toLowerCase() === "jpg" ||
          extension.toLowerCase() === "jpeg"
        ) {
            if (e.target.files[0].size > Img_Size) {
                $('#image_er').html("please select Image Size under 2MB");
                $(this).val('');
            }else{
                $('#image_er').html("");
            }
        }else{
            $('#image_er').html("Image Size File");
            $(this).val('');
        }
        });
    });
    </script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("firebaseNotification");

    toggle.addEventListener("change", function () {
        // Checkbox value change (so form gets correct value)
        this.value = this.checked ? 1 : 0;
        console.log("Firebase notification:", this.value);
    });
});
</script>
    <style>
    #image_erro{
        font-weight: bold;
    font-size: 14px;
    }
    #image_errors{
        font-weight: bold;
    font-size: 14px;
    }
    #image_er{
        font-weight: bold;
    font-size: 14px;
    }
    .switch {
  position: relative;
  display: inline-block;
  width: 46px;
  height: 24px;
}
.switch input { display: none; }
.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #ccc;
  transition: .4s;
  border-radius: 34px;
}
.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .4s;
  border-radius: 50%;
}
input:checked + .slider {
  background-color: #28a745;
}
input:checked + .slider:before {
  transform: translateX(22px);
}
    </style>
@endsection        