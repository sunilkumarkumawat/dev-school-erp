  @php
  $getSetting = Helper::getSetting();
  @endphp

<style>
    /* New Css Start */

       .top_brand_section {
           display: flex;
           align-items: center;
           border-bottom: 2px solid white;
           margin-bottom: 20px;
           padding-bottom: 20px;
           padding-top: 20px;
           padding-left: 10px;
           padding-right: 10px;
           position: relative;
           height: 70px;
       }

       .brand_img {
           width: 40px;
           height: 40px;
       }

       .brand_title {
           margin-bottom: 0px;
           width: 200px;
           font-size: 14px;
           font-weight: 600;
           color: white;
           margin-left: 10px;
       }
       @media screen and (max-width:600px) {
      .elevation-4{
       background-color:#0094ae !important;
       
      }
      .nav-sidebar .nav-item>.nav-link {
          color:black;
          font-weight:bold;
      }
     
      .brand_title{
          color: black;
          font-weight:bold;
          text-align:center;
      }

      /*ul li:hover{*/
      /*    background-color:black !important;*/
      /*    color:white !important;*/
      /*}*/
    }
   
</style>
  
<aside class="main-sidebar bg-light  elevation-4">
  
<a href="{{url('/')}}">
  <div class="top_brand_section">
       <img src="{{ env('IMAGE_SHOW_PATH').'/setting/left_logo/'.$getSetting['left_logo'] ?? '' }}" alt="" class="brand_img">
       <p class="brand_title" style="display:none;">{{$getSetting->name}}</p>
   </div>
</a>

    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">


@php
$sidebarData = DB::table('students_sidebar')->whereNull('deleted_at')->orderBy('id', 'ASC')->get();
@endphp

@if(!empty($sidebarData))

@foreach($sidebarData as $item)
 <li class="nav-item menu-open ">
                    <a href="{{url($item->url)}}{{$item->url == 'student_fees_details' ? '/'.Session::get('id') : ''}}" class="nav-link {{ url($item->url)  == URL::current() ? 'active' : "" }}">
                    <i class="nav-icon fas {{$item->ican ?? '' }}"></i>
                    <p>{{$item->name ?? ''}}</p>
                    </a>
                </li>

@endforeach
@endif
                <li class="nav-item menu-open ">
                    <a href="#" class="nav-link " onclick="confirmLogout(event)">
                    <i class="nav-icon fa fa-sign-out"></i>
                    <p>Log Out</p>
                    </a>
                </li>
            
            </ul>
        </nav>
    </div>
</aside>
 