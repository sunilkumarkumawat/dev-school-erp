@php
$classType = Helper::classType();
$getSetting=Helper::getSetting();
         $session=DB::table('sessions')->where('id',Session::get('session_id'))->whereNull('deleted_at')->first();

@endphp
@extends('layout.app')
@section('content')



<div class="content-wrapper">
  <section class="content pt-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card card-outline card-orange">
            <div class="card-header bg-primary flex_items_toggel">
              <h3 class="card-title"><i class="fa fa-address-book-o"></i> &nbsp;{{ __('Students Category Wise Report ') }}</h3>
              <div class="card-tools">
                <a href="{{url('studentsDashboard')}}" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><span class="Display_none_mobile">{{ __('common.Back') }} </span></a>
              </div>

            </div>
        <hr class="m-0 ml-2 mr-2">
            <div class="row m-2" >
              <div class="col-12" id="downloadLeaflet">
             <table class="header" style="width:100%;"> 
                <tr>
                              
                     @if(Session::get('branch_id') == 1)
                        <td colspan="12" class="logo" style="text-align: center;"> 
                            <table style="width:100%;">
                                  <tr>
                                       <td style="width: 30%;text-align: start;">
                                         &nbsp &nbsp <img  src="{{ env('IMAGE_SHOW_PATH').'/setting/left_logo/'.$getSetting['left_logo'] }}" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'/default/rukmani_logo.png' }}'" style="width:100px;height: 70px;"/>
                                       </td>
                                         <td style="text-align:start;">
                                                <span style="font-size:40px;font-weight:bold;">{{$getSetting['name'] ?? ''}}</span>
                                         </td>
                                  </tr>
                            </table>
                        </td>
        
                              @else
                              <td colspan="12" class="logo" style="text-align: center;width: 70%;"> 
                                        <img src="{{ env('IMAGE_SHOW_PATH').'/default/'}}Senior.jpg" style="width: 88%;height: 76px;margin-top: 4px;">
                              </td>
                  @endif 
            </tr>
        </table>
                            <h3 style="text-align: center;margin: 14px;">Category Wise  Report Session :-{{$session->from_year ?? '' }}{{"-"}}{{$session->to_year ?? ''}}</h3>
                            <div class="table-responsive">
                <table id="example11" class="table table-bordered  dataTable  nowrap"  border="1" cellspacing="0" cellpadding="5" style="border: 1px solid black;margin-left: -10px;" >
  <thead>
    <tr>
      <th class="center">S.No.</th>
      <th>Class Name</th>
      @php
      $categories = array("GENERAL", "OBC", "ST", "SC", "BC", "SBC");
      @endphp
	     @foreach ($categories as $cat)
           <th class="center" colspan="3">{{$cat ?? '' }} Enrollment</th>
      	@endforeach 
      <th class="center" colspan="3">Total Enrollment</th> 
    </tr>
    <tr>
      <th></th>
      <th></th>
       @foreach ($categories as $cat)
              <th> Boys</th>
              <th>Girls</th>
              <th> Total</th>
      	@endforeach 
     
   
      <th class="center" ><b>Total Boys</b></th>
      <th class="center" ><b>Total Girls</b></th>
      <th class="center" ><b>G Total </b></th>
    </tr>
  </thead>
  <tbody>
      @if(!empty($classType))
       @php
                               $TotalGirls = 0;
      $TotalBoys = 0;
                       
      $i=1;
     
      @endphp
                      @foreach($classType as $type)
                          <tr>
                              <td class="center">{{$i++}}</td>
                              <td>{{$type->name ?? ''}}</td>
                             @php
                                                      $TotalGirls = 0;
      $TotalBoys = 0;
                             @endphp
                             
                             
                               @foreach ($categories as $cat)
                               @php
                               

                               $Boys = DB::table('admissions')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('class_type_id',$type->id)->where('gender_id',1)->where('category',$cat)->whereNull('deleted_at')->where('status',1)->count();
                               $Girls = DB::table('admissions')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('class_type_id',$type->id)->where('gender_id',2)->where('category',$cat)->whereNull('deleted_at')->where('status',1)->count();
                              $TotalBoys += $Boys;
                              $TotalGirls += $Girls;
                               @endphp
                                      <td>{{$Boys ?? ''}}</td>
                                      <td>{{$Girls ?? '' }}</td>
                                      <td>{{$Boys+$Girls}}</td>
                              	@endforeach 
                              <td class="center" ><b>{{$TotalBoys ?? ''}}</b></td>
                              <td class="center" ><b>{{$TotalGirls ?? '' }}</b></td>
                              <td class="center" ><b>{{$TotalBoys+$TotalGirls}}</b></td>
                            </tr>

  
 
    
                                @endforeach
                     @endif
                        </tr>
                        </tbody>
                        <tfoot>
                       <tr>
                              <td></td>
                              <td class="center"><b>Total</b></td>
                              @php
                              $Boys_Total1 = 0;
                              $Girls_Total2 = 0;
                              @endphp
                                @foreach ($categories as $cat1)
                               @php
                               

                               $Boys_Total = DB::table('admissions')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('gender_id',1)->where('status',1)->where('category',$cat1)->whereNull('deleted_at')->count();
                               $Girls_Total = DB::table('admissions')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('gender_id',2)->where('status',1)->where('category',$cat1)->whereNull('deleted_at')->count();
                             
                               @endphp
                                      <td><b>{{$Boys_Total ?? ''}}</b></td>
                                      <td><b>{{$Girls_Total ?? '' }}</b></td>
                                      <td><b>{{$Boys_Total+$Girls_Total}}</b></td>
                                      @php
                                      $Boys_Total1 +=$Boys_Total;
                                      $Girls_Total2 +=$Girls_Total;
                                      @endphp
                              	@endforeach 
                             <td class="center"><b>{{$Boys_Total1 ?? ''}}</b></td>
                              <td class="center"><b>{{$Girls_Total2 ?? '' }}</b></td>
                              <td class="center"><b>{{$Boys_Total1+$Girls_Total2}}</b></td>
                            </tr>
                            
                    </tfoot>
                        
                </table>
                </div>
              </div>
            </div>
            <div style="text-align:center;">
                 <button class="btn btn-sm btn-primary my-2" id="printFile" style="width:170px;"><i class="fa-fa-print"aria-hidden="true"></i> Print</button>
            </div>
             
            </div>
            </div>
            </div>
            </div>
            </section>
</div>



<style>
    .table-responsive {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

</style>


<style>
    table.dataTable > thead > tr > th:not(.sorting_disabled), table.dataTable > thead > tr > td:not(.sorting_disabled) {
  padding-right: 0px;
 
  padding-top: 10px;
  padding-bottom: 10px;
}
.table-bordered td, .table-bordered th {
  border: 1px solid black;
    border-bottom-width: 1px;
    border-left-width: 1px;
     padding-top: 10px;
  padding-bottom: 10px;
}
@page {
    margin: 2cm;
    
}

@media screen {
   .table-bordered td, .table-bordered th {
  border: 2px solid #000;
  
}
}
.center {
  text-align: center;
}
</style>
<script>
$(document).ready(function() {
    $("#printFile").click(function() {
        printContent();
    });
});

function printContent() {
    var styles = '';

    $(document).ready(function() {
        $('style, link[rel="stylesheet"]').each(function() {
            styles += $(this).prop('outerHTML');
        });
        var content = $("#downloadLeaflet").html();
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Student Data</title>' + styles + '</head><body style="margin-left:10px;">');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
            
        setTimeout(function() {
            printWindow.print();
            printWindow.close();
        }, 500);
    });
}

</script>

            @endsection
            
