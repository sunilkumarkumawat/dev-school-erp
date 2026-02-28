@php
 $branch = \App\Models\Master\Branch::find(Session::get('branch_id'));

        $branchSidebarIds = !empty($branch->branch_sidebar_id) ? explode(',', $branch->branch_sidebar_id) : [];
      
    $sidebar = DB::table('sidebars')->whereNull('deleted_at')->whereIn('id', $branchSidebarIds)->orderBy('order_by','ASC')->get();
    $subSidebar = DB::table('sidebar_sub')->where('sub_sidebar','yes')->whereIn('sidebar_id', $branchSidebarIds)->whereNull('deleted_at')->orderBy('orderBy','ASC')->get();

$Permisn = Helper::getPermisn();
$getSetting = Helper::getSetting();
@endphp

<aside class="main-sidebar" id="sidebar">
    <a href="{{ url('/') }}">
        <div class="top_brand_section">
            <img src="{{ env('IMAGE_SHOW_PATH').'/setting/left_logo/'.$getSetting->left_logo ?? '' }}" 
                 alt="" class="brand_img" 
                 onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/no_image.png' }}'">
            <span class="brand_title">{{ $getSetting->name ?? '' }}</span>
        </div>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                @foreach($sidebar as $data)
                    @php
                        $submenus = Helper::getSubPermisn($data->id);
                        $activeSub = false;
                        foreach($subSidebar as $sub){
                            if(in_array($sub->id, $submenus) && url($sub->url) == URL::current()){
                                $activeSub = true;
                                break;
                            }
                        }
                    @endphp

                    <li class="nav-item {{ !empty($submenus) ? 'has-treeview' : '' }} {{ $activeSub ? 'menu-open' : '' }}">
                        <a href="{{ !empty($submenus) ? '#' : url($data->url) }}" 
                           class="nav-link {{ url($data->url) == URL::current() ? 'active' : '' }}">
                            <i class="nav-icon fa {{ $data->ican ?? '' }}"></i>
                            <p>
                                @if(Session::get('locale') == 'hi') 
                                    {{ $data->hindi_name ?? '' }} 
                                @else 
                                    {{ $data->name ?? '' }} 
                                @endif
                                @if(!empty($submenus))
                                    <i class="right fa fa-angle-left"></i>
                                @endif
                            </p>
                        </a>

                        @if(!empty($submenus))
                            <ul class="nav nav-treeview">
                                @foreach($subSidebar as $sub)
                                    @if(in_array($sub->id, $submenus))
                                        <li class="nav-item">
                                            <a href="{{ url($sub->url) }}" 
                                               class="nav-link {{ url($sub->url) == URL::current() ? 'active' : '' }}">
                                                <i class="fa fa-circle nav-icon"></i>
                                                <p>
                                                    @if(Session::get('locale') == 'hi') 
                                                        {{ $sub->hindi_name ?? '' }} 
                                                    @else 
                                                        {{ $sub->name ?? '' }} 
                                                    @endif
                                                </p>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                {{-- Help & Logout --}} @if(Session::get('role_id') == 1) 
                <li class="nav-item "> <a href="{{url('helpAndUpdate')}}" class="nav-link"> 
                
                 <i class="nav-icon fa fa-question-circle-o"></i>
                            <p>
                              Help & Updates
                            </p>
                        </a>
                </li> @endif
                 <li class="nav-item "> 
                 <a href="#" class="nav-link" onclick="confirmLogout(event)"> 
                
                 <i class="nav-icon fa fa-sign-out"></i>
                            <p>
                              Log Out
                            </p>
                        </a>
                </li>
              
            </ul>
        </nav>
    </div>
</aside>
<style>
  :root {
    --sidebar-color: #0e62ab;
    --sidebar-dark: #0c5594;
    --text-light: #ffffff;
    --text-dim: rgba(255, 255, 255, 0.8);
    --border: rgba(255, 255, 255, 0.15);
}


/* Default icon color (inactive menu) */
.nav-sidebar .nav-link .nav-icon {
    color: #ffffff !important;
    transition: color 0.3s ease !important;
}

/* Hover icon color (inactive hover) */
.nav-sidebar .nav-link:hover .nav-icon {
    color: #ffffff !important;
}

/* Active main menu icon */
.nav-sidebar .nav-link.active .nav-icon {
    color: #0e62ab !important;
}

/* Active submenu icon */
.nav-treeview .nav-link.active .nav-icon {
    color: #0e62ab !important;
}




/* Sidebar container */
.main-sidebar {
    background: var(--sidebar-color);
    color: var(--text-light);
    width: 250px;
    min-height: 100vh;
    position: fixed;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.25);
}

/* Brand Section */
.top_brand_section {
    display: flex;
    align-items: center;
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    background: var(--sidebar-dark);
    height:4.4rem;
}

.brand_img {
    width: 42px;
    height: 42px;
    border-radius: 6px;
    background: white;
    object-fit: cover;
    border: 2px solid var(--border);
}

.brand_title {
    margin-left: 10px;
    color: var(--text-light);
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    display:inline !important;
    overflow: auto;
}

/* Sidebar nav base */
.nav-sidebar > .nav-item > .nav-link {
    color: var(--text-dim);
    padding: 10px 16px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.nav-sidebar > .nav-item > .nav-link:hover {
    color: var(--text-light);
    background: rgba(255, 255, 255, 0.1);
    border-left-color: white;
    padding-left: 20px;
}

/* Active main menu */
.nav-sidebar > .nav-item > .nav-link.active {
    background: white !important;
    color: var(--sidebar-color) !important;
    font-weight: 700;
    border-left-color: white !important;
}

.nav-icon {
    margin-right: 10px;
    font-size: 0.9rem;
    /*color: var(--text-light);*/
}

.nav-link:hover .nav-icon {
    color: #fff;
}

/* Submenu (TreeView) */
.nav-treeview {
    margin-left: 20px;
    list-style: none;
    padding-left: 10px;
    display: none;
    border-left: 1px solid rgba(255, 255, 255, 0.2);
}

.menu-open > .nav-treeview {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

.nav-treeview .nav-link {
    color: rgba(255, 255, 255, 0.85);
    padding: 8px 12px;
    border-left: 2px solid transparent;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.nav-treeview .nav-link:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    padding-left: 18px;
}

.nav-treeview .nav-link.active {
    background: rgba(255, 255, 255, 0.25);
    border-left-color: #fff;
    color: #fff;
    font-weight: 700;
}

/* Arrow rotation */
.nav-link .right {
    margin-left: auto;
    transition: transform 0.3s ease;
}

.menu-open > .nav-link .right {
    transform: rotate(90deg);
}

/* Scrollbar styling */
.sidebar::-webkit-scrollbar {
    width: 5px;
}
.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 10px;
}
.nav-pills .nav-link:not(.active):hover {
  color: #fff;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.has-treeview > .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;

            // toggle open/close
            parent.classList.toggle('menu-open');
        });
    });
});
</script>
