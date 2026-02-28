@php
 $notificationCount = DB::table('notifications')->where('admission_id', Session::get('id'))->where('show_status', 1)->count();
@endphp

<footer class="app-footer d-flex justify-content-around align-items-center" style="padding: 0px 5px 0px 10px;">
    
    <a href="{{ url('dashboard') }}" class="footer-link {{ request()->is('dashboard') ? 'active' : '' }}">
   <img src="{{ asset('public/assets/student_login/img/icons/footer_home.gif') }}" alt="" width="30px">
    <span><b>Home</b></span>
  </a>
    
  <a href="{{ url('profileStudent') }}" class="footer-link {{ request()->is('profileStudent') ? 'active' : '' }}">
     <img src="{{ asset('public/assets/student_login/img/icons/profile_edit.gif') }}" alt="" width="30px" height="30px">
    <span><b>Profile</b></span>
  </a>
  <a href="#" id="hardRefreshBtn" class="footer-link">
   <img src="{{ asset('public/assets/student_login/img/icons/refresh-149.png') }}" alt="" width="30px" style="background-color: transparent;">
    <span><b>Refresh</b></span>
  </a>
  
  <a href="{{ url('AttendanceView_student') }}" class="footer-link {{ request()->is('AttendanceView_student') ? 'active' : '' }}" >
    <img src="{{ asset('public/assets/student_login/img/icons/footer_attadance.gif') }}" alt="" width="30px">
    <span><b>Attendance</b></span>
  </a>
  <a  href="{{url('notificationFatchStudent')}}" class="footer-link {{ request()->is('notificationFatchStudent') ? 'active' : '' }}" role="button" >
                  <div class="centerd_text_icon">
                    <div class="ms-auto d-flex align-items-center text-white mr-2" style="font-size:20px; position: relative;">
                        <img src="{{ asset('public/assets/student_login/img/icons/footer_alert.gif') }}" alt="" width="30px">
                        @if($notificationCount > 0)
                            <span style="position: absolute;top: -2px;right: 0px;background: red;color: white;border-radius: 50%;padding: 0px 5px;font-size: 12px;">
                                {{ $notificationCount }}
                            </span>
                        @endif
                       
                    </div>
                    </div>
            <span><b>Alert</b></span>
    </a>
</footer>
