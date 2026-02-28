@php

//$task = Helper::task();


@endphp

<style>
    .listing_flex marquee{
        width:60%;
    }
    .listing_flex form{
        width:100%;
    }
    .listing_flex{
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>


  @if(!empty($task))
  
  
               @foreach($task as $item)
                 @php
                $fdate = $item->created_at;
                $tdate = date('Y-m-d H:i:s');
                $datetime1 = new DateTime($fdate);
                $datetime2 = new DateTime($tdate);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');
                
                @endphp
                                <li class="listing_flex" id="_{{ $item->id ?? '' }}" type="submit">
                                <form id="myForm" action="{{ url('to_do_assign_view') }}" method="post">
                    @csrf     
                    <input type="hidden" name="to_do_list_id" value="{{$item->id ?? '' }}">
                                    <span class="text">{{ $item->name ?? '' }}</span>
                                    <small class="badge badge-secondary">{{$item->first_name ?? ''}}</small>
                                    <small class="badge badge-{{$days<=2 ? 'success' : ''}}{{$days>=3 && $days<=6  ? 'primary' : ''}}{{$days>=7  ? 'danger' : ''}}"><i class="fa fa-clock"></i> {{$days}}d</small>
                                    <small class="badge badge-{{$item->priority == 'low' ? 'success' : ''}}{{$item->priority == 'medium'  ? 'primary' : ''}}{{$item->priority == 'high' ? 'danger' : ''}}"><i class="fa fa-clock"></i>
                                    {{$item->priority == 'low' ? 'Low' : ''}}{{$item->priority == 'medium'  ? 'Medium' : ''}}{{$item->priority == 'high' ? 'High' : ''}}
                                    </small>
                                   
                                    <small class="badge badge-{{$item->status == 0 ? 'danger' : ''}}{{$item->status == 1  ? 'warning' : ''}}{{$item->status == 2 ? 'info' : ''}}{{$item->status == 3 ? 'success' : ''}}"><i class="fa fa-clock"></i>
                                    {{$item->status == 0 ? 'Pending' : ''}}{{$item->status == 1  ? 'Working' : ''}}{{$item->status == 2 ? 'Completed' : ''}}{{$item->status == 3 ? 'Verified' : ''}}
                                    </small>
                                    <marquee> {{$item->description ?? ''}}</marquee>        
                                     <div class="tools mr-2">
                                     <button type="submit" class=" btn btn-success btn-xs ml-3"> <i class="fa fa-eye"></i></button>
                                    </form>
                                    @if(Session::get("role_id") == 1)
                                        <i class="fa fa-trash-o task_delete" data-id="{{ $item->id ?? '' }}"></i>
                                    </div>
                                    @endif
                                </li>
                                
                                @endforeach
                                @endif
