@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

$currentSessionId = Session::get('session_id');

/*
|--------------------------------------------------------------------------
| Dynamic Setup Steps
|--------------------------------------------------------------------------
*/

$steps = [

    'Session' => !empty($currentSessionId),

    'Class Created' => $currentSessionId 
        ? DB::table('class_types')
            ->where('session_id',$currentSessionId)
            ->where('branch_id', Session::get('branch_id'))
            ->whereNull('deleted_at')
            ->exists()
        : false,

    'Subject Created' => $currentSessionId
        ? DB::table('all_subjects')
            ->where('session_id',$currentSessionId)
            ->where('branch_id', Session::get('branch_id'))
            ->whereNull('deleted_at')
            ->exists()
        : false,

    'Class Wise Assign Subject' => $currentSessionId
        ? DB::table('subject')
            ->where('session_id',$currentSessionId)
            ->where('branch_id', Session::get('branch_id'))
            ->whereNull('deleted_at')
            ->exists()
        : false,

    'Fees Group Added' => $currentSessionId
        ? DB::table('fees_group')
            ->where('session_id',$currentSessionId)
            ->where('branch_id', Session::get('branch_id'))
            ->whereNull('deleted_at')
            ->exists()
        : false,
];

$totalSteps = count($steps);
$completedSteps = collect($steps)->filter()->count();
$percent = round(($completedSteps / $totalSteps) * 100);

@endphp


@if($percent < 100)

<!-- Font Awesome -->
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>

#setupOverlay{
    position:fixed;
    inset:0;
    background:linear-gradient(135deg,#4e73df,#1cc88a);
    display:flex;
    justify-content:center;
    align-items:center;
    z-index:99999;
    padding:15px;
}

#setupCard{
    background:#fff;
    width:100%;
    max-width:900px;
    padding:35px;
    border-radius:15px;
    box-shadow:0 25px 80px rgba(0,0,0,0.4);
    text-align:center;
}

.percent-text{
    font-size:22px;
    font-weight:bold;
    color:#1cc88a;
}

.progress-container{
    width:100%;
    background:#eee;
    border-radius:30px;
    overflow:hidden;
    margin:15px 0;
}

.progress-bar-custom{
    height:20px;
    width:0%;
    background:linear-gradient(90deg,#36b9cc,#1cc88a);
    border-radius:30px;
    transition:width 1s ease;
}

.setup-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:12px 0;
    border-bottom:1px solid #eee;
    font-size:16px;
}

.setup-item i{
    margin-left:8px;
    cursor:pointer;
    color:#4e73df;
}

.check{
    color:#28a745;
    font-weight:bold;
}

.toggle-box{
    display:none;
    background:#f8f9fc;
    padding:10px 15px;
    border-radius:8px;
    margin-bottom:10px;
    text-align:left;
}

.toggle-box ul{
    margin:0;
    padding-left:18px;
}

/* Mobile */
@media(max-width:768px){
    #setupCard{padding:20px;}
    .setup-item{
        flex-direction:column;
        align-items:flex-start;
        gap:8px;
    }
    .setup-item a{width:100%;}
}

</style>


<div id="setupOverlay">
    <div id="setupCard">

        <h2>🎓 School ERP Setup Progress</h2>

        <div class="percent-text">
            <span id="percentCounter">0</span>% Complete
        </div>

        <div class="progress-container">
            <div id="progressBar" class="progress-bar-custom"></div>
        </div>

        <div style="margin-top:15px; text-align:left;">

@foreach($steps as $title => $status)

    @php
        $slug = Str::slug($title);
        $data = collect();

        if($status && $title != 'Session'){
            switch($title){
                case 'Class Created':
                    $data = DB::table('class_types')
                        ->where('session_id',$currentSessionId)
                        ->where('branch_id', Session::get('branch_id'))
                        ->whereNull('deleted_at')
                        ->get();
                    break;

                case 'Subject Created':
                    $data = DB::table('all_subjects')
                        ->where('session_id',$currentSessionId)
                        ->where('branch_id', Session::get('branch_id'))
                        ->whereNull('deleted_at')
                        ->get();
                    break;

                case 'Class Wise Assign Subject':
                    $data = DB::table('subject')
                        ->where('session_id',$currentSessionId)
                        ->where('branch_id', Session::get('branch_id'))
                        ->whereNull('deleted_at')
                        ->get();
                    break;

                case 'Fees Group Added':
                    $data = DB::table('fees_group')
                        ->where('session_id',$currentSessionId)
                        ->where('branch_id', Session::get('branch_id'))
                        ->whereNull('deleted_at')
                        ->get();
                    break;
            }
        }
    @endphp

    <div class="setup-item">
        <span>
            {{ $title }}

            {{-- Show icon only if data exists --}}
            @if($status && $title != 'Session')
                <i class="fa fa-info-circle"
                   onclick="toggleBox('{{ $slug }}')">
                </i>
            @endif
        </span>

        @if($status)
            <span class="check">✔ Done</span>
        @else

            @switch($title)
                @case('Session')
                    <a href="{{ url('session_add') }}" class="btn btn-sm btn-primary">Select</a>
                    @break

                @case('Class Created')
                    <a href="{{ url('add_class') }}" class="btn btn-sm btn-primary">Add</a>
                    @break

                @case('Subject Created')
                    <a href="{{ url('create_subject') }}" class="btn btn-sm btn-primary">Add</a>
                    @break

                @case('Class Wise Assign Subject')
                    <a href="{{ url('add_subject') }}" class="btn btn-sm btn-primary">Add</a>
                    @break

                @case('Fees Group Added')
                    <a href="{{ url('feesGroup') }}" class="btn btn-sm btn-primary">Add</a>
                    @break
            @endswitch

        @endif
    </div>

    {{-- Detail Toggle --}}
    @if($status && $title != 'Session')
        <div id="{{ $slug }}" class="toggle-box">
            <ul>
                @foreach($data as $row)
                    <li>{{ $row->name }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endforeach

        </div>

    </div>
</div>

<script>

// Toggle
function toggleBox(id){
    let box = document.getElementById(id);
    box.style.display = box.style.display === "block" ? "none" : "block";
}

// Percentage Animation
let target = {{ $percent }};
let counter = 0;
let counterElement = document.getElementById('percentCounter');
let progressBar = document.getElementById('progressBar');

let interval = setInterval(function(){
    if(counter >= target){
        clearInterval(interval);
    } else {
        counter++;
        counterElement.innerHTML = counter;
        progressBar.style.width = counter + "%";
    }
},15);

</script>

@endif
