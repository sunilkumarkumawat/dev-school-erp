@php
$getSetting = Helper::getSetting();
$getstudentbirthday = Helper::getstudentbirthday();
$getUsersBirthday = Helper::getUsersBirthday();
$getUser=Helper::getUser();
$getSession=Helper::getSession();
$getAllBranch = Helper::getAllBranch();
$roleName = DB::table('role')->whereNull('deleted_at')->find(Session::get('role_id'));
$data = Session::all();
@endphp
@php
    $apkData = DB::table('settings')->first();
@endphp
<style>
  .selectDesign {
    padding: 5px 10px;
    background: transparent;
    border: 1px solid #a5a5a5;
    border-radius: 4px;
    width: 100%;
  }
  @media screen and (min-width:600px) {
      .student-mobile{
          display:none;
      }
      .desktop-student{
          display:inline !important;
      }
  }
  
</style>










@if(Session::get('role_id') !== 3)
<nav class="main-header navbar navbar-expand navbar-white navbar-light p-0">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item ml-1">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fa fa-bars"></i></a>
    </li>
  </ul>
  
<ul class="navbar-nav " style="margin-left: 73px; margin-top: 25px;" id="navbar_nav">
    <li class="nav-item dropdown">
      <div class="Display_none_desktop" >
       <h4 class="first-name">{{ Session::get('first_name') ?? '' }} &nbsp &nbsp</h4>
      </div>
    </li>
      <li class="nav-item">
        <a  style="margin:5px;padding:0px;text-align: center; " class="whatsapp_login whatsapp" href="javascript:whatsapp_qrcode_get();" data-toggle="modal" data-target="#whatsapp_login">
           <i class="nav-icon fa fa-whatsapp" style="color:green;font-size:18px;"></i>&nbsp;<span class="d-md-inline d-none">Login status</span><br>
           <i class="nav-icon fa fa-circle" style="color:{{ Session::get('whatsapp') ? 'green' : 'red' }};font-size:12px;">&nbsp;</i></a>
  </li>
</ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto flex_centerd_profile">
  
@if(Session::get('role_id') == 1)
    @if(count($getstudentbirthday) > 0 || count($getUsersBirthday) > 0)

   
    <li class="nav-item">
      <a class="nav-link" href="{{url('happy_birthday')}}">

        <img width="40px" style="margin-top:-8px" src="{{ env('IMAGE_SHOW_PATH').'default/birthday.webp' }}">
      </a>
    </li>

    @endif

    @endif




    <li class="nav-item dropdown">
      <div class="Display_none_mobile">
        <form action="{{url('changeBranch')}}" method="POST">
          @csrf
          <select class="selectDesign " id="branch_id" name="branch_id" onchange="this.form.submit()">
          @if(Session::get('role_id') != 3)
           
            @foreach($getAllBranch as $branch)

            <option value="{{ $branch->id ?? ''  }} " {{ ( $branch->id == Session::get('branch_id')) ? 'selected' : '' }}>{{ $branch->branch_name ?? ''  }} </option>
            @endforeach
            @endif
          </select>
        </form>
      </div>
    </li>

 
    <li class="nav-item dropdown">
      <div class="Display_none_mobile">
        <form action="{{url('sectionDataId')}}" method="POST">
          @csrf
          <select class="selectDesign " id="sessionData" name="sessionData" onchange="this.form.submit()">
            @if(!empty($getSession))
            @foreach($getSession as $type)
            <option value="{{ $type->id ?? ''  }} " {{ ( $type->id == Session::get('session_id')) ? 'selected' : '' }}>{{ $type->from_year ?? ''  }} - {{ $type->to_year ?? ''  }}</option>
            @endforeach
            @endif
          </select>
        </form>
      </div>
    </li>
   

    <li class="nav-item dropdown">
      <div class="Display_none_mobile">
        <a href="{{ URL::current() }}" id="refresh" class="refresh_btn" onclick="">Refresh!</a>
      </div>
    </li>

    @if(!empty(Session::get('id')))
    <li class="nav-item dropdown mobile_padding">
      <a class="user-panel" data-toggle="dropdown" href="#">
        @if(Session::get('role_id')==3)
        <img src="{{ env('IMAGE_SHOW_PATH').'/profile/'.$getUser['image'] }}" class="img-circle elevation-2" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/user_image.jpg' }}'">
        @else
        <img src="{{ env('IMAGE_SHOW_PATH').'/profile/'.$getUser['image'] }}" class="img-circle elevation-2" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/user_image.jpg' }}'">
        @endif
        {{-- <span class="badge badge-warning navbar-badge">15</span> --}}
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        {{-- <span class="dropdown-item dropdown-header">15 Notifications</span> --}}
        {{-- <div class="dropdown-divider"></div> --}}
        <div class="row border-bottom mr-0">
          <div class="col-md-4 col-4">
            @if(Session::get('role_id')==3)
            <img class="profile_user_img" src="{{ env('IMAGE_SHOW_PATH').'/profile/'.$getUser['image'] }}" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'/default/user_image.jpg' }}'">
            @else
            <img class="profile_user_img" src="{{ env('IMAGE_SHOW_PATH').'/profile/'.$getUser['image'] }}" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'/default/user_image.jpg' }}'">
            @endif
          </div>
          <div class="col-md-8 col-8 align_centerd">
            <div>
              <h4>{{ Session::get('first_name') ?? '' }}</h4>
              <p>{{ $roleName->name ?? '' }}</p>
            </div>
          </div>
        </div>

        <a href="{{ url('profile/edit') }}/{{Session::get('id') ?? '' }}" class="{{ url('profile/edit/'.Session::get('id'))  == URL::current() ? 'dropdown-item border-bottom back_active_header' : "dropdown-item border-bottom" }}">
          <i class="fa fa-user-circle mr-2"></i>Profile Setting
          {{-- <span class="float-right text-muted text-sm">3 mins</span> --}}
        </a>

        <a href="{{ url('change_password') }}" class="{{ url('change_password')  == URL::current() ? 'dropdown-item border-bottom back_active_header' : "dropdown-item border-bottom" }}">
          <i class="fa fa-key mr-2"></i>Change Password
          {{-- <span class="float-right text-muted text-sm">3 mins</span> --}}
        </a>
        
        <div class=" border-bottom d-flex align-items-center p-2 pl-3 ">
         <i class="fa fa-language" aria-hidden="true"></i>
            <form action="{{ url('changeLang') }}" method="POST" class="mb-0">
                @csrf
                <select class="selectDesign ml-2" id="lang" name="lang" onchange="this.form.submit()">
                    @php
                        $languages = DB::table('languages')->whereNull('deleted_at')->get();
                    @endphp
                    @foreach($languages as $type)
                        <option value="{{ $type->value ?? '' }}" {{ session()->get('locale') == $type->value ? 'selected' : '' }}>
                            {{ $type->name ?? '' }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        
        <div class="dropdown-item d-flex align-items-center border-bottom Display_none_PC">
         <i class="fa fa-language" aria-hidden="true"></i>
              <form action="{{url('changeBranch')}}" method="POST">
             @csrf
              <select class="selectDesign ml-2" id="branch_id" name="branch_id" onchange="this.form.submit()">
              @if(Session::get('role_id') != 3)
               
                <!--<option value=""> All Branch</option>-->
                @foreach($getAllBranch as $branch)
    
                <option value="{{ $branch->id ?? ''  }} " {{ ( $branch->id == Session::get('branch_id')) ? 'selected' : '' }}>{{ $branch->branch_name ?? ''  }} </option>
                @endforeach
                @endif
              </select>
        </form>
      </div>
       

        @if(Session::get('role_id') == 1)
        <a href="{{url('helpAndUpdate')}}" class="text-warning dropdown-item border-bottom">
          <i class="fa fa-question-circle-o mr-2"></i>Help & Updates
          {{-- <span class="float-right text-muted text-sm">3 mins</span> --}}
        </a>
        @endif
        
   @if(Session::get('role_id') == 1 || Session::get('role_id') == 2)
        <div class="dropdown-item border-bottom Display_none_PC">
         
          <div class="flex_row">
            <i class="fa fa-calendar-check-o mr-2"></i>
            <form action="{{url('sectionDataId')}}" method="POST">
              @csrf
              <select class="form-control select" id="sessionData" name="sessionData" onchange="this.form.submit()">
                @if(!empty($getSession))
                @foreach($getSession as $type)
                <option value="{{ $type->id ?? ''  }} " {{ ( $type->id == Session::get('session_id')) ? 'selected' : '' }}>{{ $type->from_year ?? ''  }} - {{ $type->to_year ?? ''  }}</option>
                @endforeach
                @endif
              </select>
            </form>
          </div>
         
        </div>
 @endif
 
        <a href="#" class="dropdown-item border-bottom text-danger" onclick="confirmLogout(event)">
          <i class="fa fa-sign-out mr-2"></i> Log Out
          {{-- <span class="float-right text-muted text-sm">3 mins</span> --}}
        </a>

        {{-- <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fa fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fa fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> --}}
      </div>
    </li>
    @endif 
        
  </ul>
</nav>

@endif


<!-- Modal -->
<div id="whatsapp_login" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
          <i class="fa fa-spinner fa-spin preloader1"></i>
        <ul style="list-style-type: none;  margin: 0;  padding: 0;  overflow: hidden;  background-color: #fffff;">
      <li style="display: block;  color: black;  text-align: center;  padding: 14px 16px;  text-decoration: none;float: left;">  Booking Date<br><b style="color:red" id="registration_date"></b></li>
      <li style="display: block;  color: black;  text-align: center;  padding: 14px 16px;  text-decoration: none;float: left;">  Server Expiry Date<br><b style="color:red" id="domain_expire_date"></b></li>
     
      <li style="display: block;  color: black;  text-align: center;  padding: 14px 16px;  text-decoration: none;float: left;">  Service Expiry Date<br><b style="color:red" id="emc_date"></b></li>
        <li style="display: block;  color: black;  text-align: center;  padding: 14px 16px;  text-decoration: none;float: left;"> SMS Balance<br><b style="color:red" id="sms"></b></li>
      <li style="display: block;  color: black;  text-align: center;  padding: 14px 16px;  text-decoration: none;float: left;"> Whatsapp Balance<br><b style="color:red" id="whatsapp_balance"> </b></li>
       <li style="display: block;  color: black;  text-align: center;  padding: 14px 16px;  text-decoration: none;float: left;">Student Limit<br><b style="color:red" id="register_student"></b></li>
</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<style>
.preloader1{
    position: absolute;
  top: 63px;
  left: 141px;
  font-size: 75px;
}
    



</style>







<script>
  $(document).ready(function() {
    // Function to show or hide the brand title
    function brandShow() {
      if ($('.sidebar').hasClass('os-host-scrollbar-horizontal-hidden')) {
        $('.brand_title').show();
      } else {
        $('.brand_title').hide();
      }
    }

    // Run the function initially to set the correct state
    brandShow();

    // Observe DOM changes and run the function when needed
    const observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.attributeName === 'class') {
          brandShow();
        }
      });
    });

    // Start observing the sidebar element for attribute changes
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
      observer.observe(sidebar, {
        attributes: true
      });
    }
  });
</script>

<script>
    function confirmLogout(event) {
    event.preventDefault();
    document.getElementById('logout-confirmation').style.display = 'flex';
}

function closePopup() {
    document.getElementById('logout-confirmation').style.display = 'none';
}

function logout() {
    window.location.href = "{{url('logout')}}"; 
}

</script>
<script>

  $('.whatsapp_login').click(function() {
      checkSchoolToken();
      
  } );
  
function checkSchoolToken () {
    var token_no = "{{ env('SOFTWARE_TOKEN_NO') }}";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "https://rukmanisoftware.com/api/checkSchoolToken/" + token_no,
        success: function(response, status, xhr) {
            // Check response and status code
            if (response && xhr.status === 200) {
                
            $.ajax({
                type: "POST",
                url: "/set_session_count",
                data:{
                    data:response.data,
                },
               
                success:function(response1){ 
                  if (response1 && xhr.status === 200) {
                      
                    $('#registration_date').text(response1.registration_date);
                    $('#domain_expire_date').text(response1.domain_expire_date);
                    $('#whatsapp_balance').text(response1.whatsapp_balance);
                    $('#sms').text(0);
                    $('#emc_date').text(response1.emc_date);
                    $('#register_student').text(response1.register_student + '/' + response1.student_count);
                                    }

                 
                }, complete: function() {
                        $('.preloader1').hide();
                        $('.preloader1').removeClass('disabled');
                    }
            });
               
            } else {
                alert("Invalid response format.");
            }
        },
        error: function(xhr, status, error) {
            console.error("API error:", error);
        }
    });

}







</script>
<script>
    function refreshPage(event) {
    event.preventDefault(); 
    const animation = document.getElementById('refresh');
    animation.style.display = 'flex';

   
    setTimeout(() => {
        animation.style.display = 'none';
        location.reload(); 
    }, 1000); 
}



/* function fetchBalance() {
        $.ajax({
            url: '/check-balance',
            type: 'GET',
            success: function(response) {
                $('#balanceResult').html("Balance: " + JSON.stringify(response));
            },
            error: function(error) {
                console.log(error);
                $('#balanceResult').html("Error fetching balance.");
            }
        });
    }*/

 
    
 
    const firebaseConfig = {
        apiKey: "{{ env('FIREBASE_API_KEY') }}",
        authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
        projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
        storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
        messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
        appId: "{{ env('FIREBASE_APP_ID') }}",
        measurementId: "{{ env('FIREBASE_MEASUREMET_ID') }}"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    async function requestAndSaveToken() {
        try {
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                console.warn('Notification permission not granted');
                return;
            }

            const token = await messaging.getToken({
                vapidKey: "{{ env('FIREBASE_VAPID_KEY') }}"
            });
//alert(token);
            console.log('FCM Token:', token);
            saveDeviceToken(token);

        } catch (error) {
            console.error('Error getting FCM token', error);
        }
    }

    function saveDeviceToken(token) {
        fetch("{{ url('/saveDeviceToken') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ device_token: token, platform: 'web' })
        })
        .then(r => r.json())
        .then(d => console.log('Save token response', d))
        .catch(e => console.error('Save token error', e));
    }

    window.onload = requestAndSaveToken;


    
</script>
<script>
  window.addEventListener('load', function() {
const id = "{{Session::get('attendance_unique_id')}}";
const modal = "{{Session::get('modal_name')}}" || 'User';
    const user = {
      'attendance_unique_id':id,
      'modal':modal
    };

    // Save user info as JSON string in localStorage
    localStorage.setItem('user', JSON.stringify(user));
  });
</script>



<style>
.Display_none_desktop{
    display:none;
}
  @media screen and (max-width:600px) {
      .Display_none_desktop{
        display:block;
        color:black;
        font-size:20px;
      display: flex;
       
    }
    .first-name{
        display:inline;
    }
    #navbar_nav{
        margin-left:0px !important;
        margin-top:0px !important;
    }
    .role-name{
        font-size:15px;
        display:inline;
    }
    .user-panel {
      padding: 0px 0px !important
    }
   
  }

  .solid {
    border: solid thin;
    margin: 4px;
    width: 110px;
    height: 91px;
  }

  .center {
    margin-left: 33%;
  }

  .user-panel {
    padding: 0px 1rem;
  }

  .user-panel img {
    height: 2rem;
    width: 2rem;
    margin-top: 4px;
  }

  .preloader {
    /*background-color:#f7f7f7e8;
*/
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 999999;
    -webkit-transition: .6s;
    -o-transition: .6s;
    transition: .6s;
    margin: 0 auto;
  }

  .preloader .preloader-circle {
    width: 169px;
    height: 169px;
    position: relative;
    border-style: solid;
    border-width: 1px;
    border-top-color: #ff2020;
    border-bottom-color: transparent;
    border-left-color: transparent;
    border-right-color: transparent;
    z-index: 10;
    border-radius: 50% ! important;
    -webkit-box-shadow: 0 1px 5px 0 rgba(35, 181, 185, 0.15);
    box-shadow: 0 1px 5px 0 rgba(35, 181, 185, 0.15);
    background-color: #ffffff;
    -webkit-animation: zoom 2000ms infinite ease;
    animation: zoom 2000ms infinite ease;
    -webkit-transition: .6s;
    -o-transition: .6s;
    transition: .6s;
  }

  .preloader .preloader-circle2 {
    border-top-color: #0078ff;
  }

  .preloader .preloader-img {
    position: absolute;
    top: 50%;
    z-index: 200;
    left: 0;
    right: 0;
    margin: 0 auto;
    text-align: center;
    display: inline-block;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    transform: translateY(-50%);
    padding-top: 6px;
    -webkit-transition: .6s;
    -o-transition: .6s;
    transition: .6s;
  }

  .preloader .preloader-img img {
    max-width: 163px
  }

  . .preloader .pere-text strong {
    font-weight: 800;
    color: #dca73a;
    text-transform: uppercase;
  }

  @-webkit-keyframes zoom {
    0% {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
      -webkit-transition: .6s;
      -o-transition: .6s;
      transition: .6s
    }

    100% {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
      -webkit-transition: .6s;
      -o-transition: .6s;
      transition: .6s
    }
  }

  @keyframes zoom {
    0% {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
      -webkit-transition: .6s;
      -o-transition: .6s;
      transition: .6s
    }

    100% {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
      -webkit-transition: .6s;
      -o-transition: .6s;
      transition: .6s;
    }
  }

  .section-padding2 {
    padding-top: 200px;
    padding-bottom: 200px;
  }

  @media only screen and (min-width: 1200px) and (max-width: 1600px) {
    .section-padding2 {
      padding-top: 200px;
      padding-bottom: 200px;
    }
  }

  @media only screen and (min-width: 992px) and (max-width: 1199px) {
    .section-padding2 {
      padding-top: 200px;
      padding-bottom: 200px;
    }
  }

  @media only screen and (min-width: 768px) and (max-width: 991px) {
    .section-padding2 {
      padding-top: 100px;
      padding-bottom: 100px;
    }
  }

  @media only screen and (min-width: 576px) and (max-width: 767px) {
    .section-padding2 {
      padding-top: 50px;
      padding-bottom: 50px;
    }
  }

  @media (max-width: 575px) {
    .section-padding2 {
      padding-top: 50px;
      padding-bottom: 50px
    }
  }

  .padding-bottom {
    padding-bottom: 250px;
  }

  @media only screen and (min-width: 1200px) and (max-width: 1600px) {
    .padding-bottom {
      padding-bottom: 250px;
    }
  }

  @media only screen and (min-width: 992px) and (max-width: 1199px) {
    .padding-bottom {
      padding-bottom: 150px;
    }
  }

  @media only screen and (min-width: 768px) and (max-width: 991px) {
    .padding-bottom {
      padding-bottom: 40px;
    }
  }

  @media only screen and (min-width: 576px) and (max-width: 767px) {
    .padding-bottom {
      padding-bottom: 10px;
    }
  }

  @media (max-width: 575px) {
    .padding-bottom {
      padding-bottom: 10px;
    }
  }

  .lf-padding {
    padding-left: 60px;
    padding-right: 60px;
  }

  @media only screen and (min-width: 992px) and (max-width: 1199px) {
    .lf-padding {
      padding-left: 60px;
      padding-right: 60px;
    }
  }

  @media only screen and (min-width: 768px) and (max-width: 991px) {
    .lf-padding {
      padding-left: 30px;
      padding-right: 30px
    }
  }

  @media only screen and (min-width: 576px) and (max-width: 767px) {
    .lf-padding {
      padding-left: 15px;
      padding-right: 15px;
    }
  }

  .align-items-center {
    -ms-flex-align: center !important;
    align-items: center !important;
  }

  .justify-content-center {
    -ms-flex-pack: center !important;
    justify-content: center !important;
  }

  .d-flex {
    display: -ms-flexbox !important;
    display: flex !important;
  }

    
  
    
 
    
    .confirmation-popup {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    z-index: 9999;
    justify-content: center;
    align-items: center;
    }
    
   
    #logout-confirmation .btn{
        box-shadow:2px 2px 2px black;
        margin: 10px;
    }
.popup-content {
  background-color: white;
  padding: 20px;
  max-width: 600px;
  text-align: center;
}
</style>


<style>
    .custom-multi{position:relative;width:100%}
.custom-btn{
    width:100%;padding:8px 12px;border:1px solid #ccc;
    background:#fff;text-align:left;cursor:pointer;
    display:flex;justify-content:space-between
}
.custom-dropdown{
    display:none;position:absolute;width:100%;
    max-height:250px;overflow:auto;
    background:#fff;border:1px solid #ccc;z-index:999
}
.dropdown-item{padding:6px;display:block}
</style>
<script>

function toggleDropdown(){
    const d = document.getElementById('ClassDropdown');
    d.style.display = d.style.display === 'block' ? 'none' : 'block';
}

function selectAllFees(src){
    document.querySelectorAll('.class-checkbox')
        .forEach(cb => cb.checked = src.checked);

    updateSelectedText();
}

function updateSelectedText(){
    let names = [];
    let ids = [];

    document.querySelectorAll('.class-checkbox:checked').forEach(cb=>{
        names.push(cb.dataset.name);
        ids.push(cb.value);
    });

    document.getElementById('selectedText').innerText =
        names.length ? names.join(', ') : 'None Selected Class';

    document.getElementById('class_type_id').value = ids.join(',');

    // Auto update Select All state
    let total = document.querySelectorAll('.class-checkbox').length;
    let checked = document.querySelectorAll('.class-checkbox:checked').length;

    document.getElementById('selectAll').checked = (total === checked);
}

// Page load pe selected text show kare
document.addEventListener("DOMContentLoaded", function(){
    updateSelectedText();
});

// Outside click close (Fixed)
document.addEventListener('click', function(e){

    const multi = document.getElementById('classMulti');
    const dropdown = document.getElementById('ClassDropdown');

    if(!multi || !dropdown) return;   // 🔥 Safe check

    if(!multi.contains(e.target)){
        dropdown.style.display = 'none';
    }

});

</script>