 @php
  $getSetting = Helper::getSetting();
  $sidebarData = DB::table('students_sidebar')->whereNull('deleted_at')->orderBy('id', 'ASC')->get();
 @endphp
 <style>
     #sidebar ul {
    padding-bottom: 70px;
}
 </style>
<nav id="sidebar" class="app-sidebar">
  <div class="sidebar-header px-3 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <img src="{{ env('IMAGE_SHOW_PATH').'/setting/left_logo/'.$getSetting['left_logo'] ?? '' }}" alt="Logo" width="40px" class="me-2">
      <h6 class="m-0 text-white fw-bold">{{$getSetting->name}}</h6>
    </div>
    <button id="closeSidebar" class="btn btn-link text-white p-0">
      <i class="bi bi-x-lg fs-5"></i>
    </button>
  </div>

  <ul class="list-unstyled mt-3">
    @if(!empty($sidebarData))
        @foreach($sidebarData as $item)
            <li>
                <a href="{{url($item->url)}}" class="{{ url($item->url)  == URL::current() ? 'active' : "" }}">
                    <i class="fas {{$item->ican ?? '' }} me-2"></i>
                        {{$item->name ?? ''}}
                </a>
            </li>
        @endforeach
    @endif
    <li><a href="javascript:void(0)" id="sidebarThemeToggle" class="theme-toggle"> <i class="bi bi-moon me-2"></i><span>Dark Mode</span></a></li>
    <li><a href="{{url('profileStudent')}}" class="{{ url('profileStudent')  == URL::current() ? 'active' : "" }}"> <i class="fa fa-user me-2" aria-hidden="true"></i><span>Profile</span></a></li>
   <li>
    <a href="javascript:void(0);" id="logoutBtn" style="color:red;">
        <i class="fa fa-sign-out me-2"></i> Logout
    </a>
</li>
  </ul>
</nav>

<script>
document.getElementById("logoutBtn").addEventListener("click", function () {

   
    if(document.getElementById("closeSidebar")){
        document.getElementById("closeSidebar").click();
    }

    
    setTimeout(() => {

       
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to logout?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ url('logout') }}";
            }
        });

    }, 300); 
});
</script>

