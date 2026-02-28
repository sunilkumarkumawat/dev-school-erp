@php
$classType = Helper::classType();
  $attendance = Helper::getAttendancePerformance($data->id,$data->class_type_id);
  $examIds = Helper::getExamsForPerformance($data->class_type_id);
  
 
    $subjectIds = Helper::getPerformaceSubjects($data->id,$data->class_type_id);
    $otherIds = Helper::getPerformaceOtherSubjets($data->id,$data->class_type_id);
    
   
@endphp

@extends('layout.app')
@section('content')


<div class="content-wrapper students_search">
<input type="hidden" id="value">
<input type="hidden" id="value2">
    <section class="content pt-3">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-home"></i> &nbsp;Students Academic Performance</h3>
                            
                            <div class="card-tools">
                                <!--<a href="{{url('add_user')}}" class="btn btn-primary  btn-sm" title="Add User"><i class="fa fa-plus"></i> Add User</a>-->
                            </div>

                        </div>
                    </div>
                </div>
            </div> 
 
         
                <div class="row">
                <div class="col-12 col-md-9">
                  
                        <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fa fa-chart-bar"></i>
               Average Subjects Score
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fa fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
        
                 @if(!empty($examIds[0]))
                    <div class="col-12 col-md-12" style="overflow-x:scroll;">
                   <button class='mt-2 mb-2 btn btn-primary btn-xs' onclick="printTable()">Print Table</button> 
                <table  id="printTable" class=" table table-bordered table-striped">
                  <thead id='main_thead'>
                      <tr>
                          <td colspan='{{(count($subjectIds)+1)+(count($otherIds))}}'>
                              
                              <table width='100%'>
                           
    <tr>
        <th rowspan='7' style='text-center;padding:10px'>
            <img src='{{env("IMAGE_SHOW_PATH")."profile/".$data->image ?? ""}}' width='150px' height='150px' id="student-image" />
        </th>
    </tr>
    <tr>
        <th>Name</th>
        <th id="student-name">{{$data->first_name ?? '' }} {{$data->last_name ?? '' }}</th>
    </tr>
    <tr>
        <th>Mobile</th>
        <th id="student-mobile">{{$data->mobile ?? '' }}</th>
    </tr>
    <tr>
        <th>Father</th>
        <th id="father-name">{{$data->father_name ?? '' }}</th>
    </tr>
    <tr>
        <th>Mother</th>
        <th id="mother-name">{{$data->mother_name ?? '' }}</th>
    </tr>
    <tr>
        <th>Father Mobile</th>
        <th id="father-mobile">{{$data->father_mobile ?? '' }}</th>
    </tr>
    <tr>
        <th>Attendance</th>
        <th id="attendance">{{$attendance}}</th>
    </tr>
    </table>
</td>
                      </tr>
                      
                    <tr role="row">
                
                @php
          
            $subjectList = [];
            $otherList = [];
            if(!empty($subjectIds))
            {
            $subjectList = Helper::getPerformaceSubjectsName($subjectIds);
            }
            if(!empty($otherIds))
            {
            $otherList = Helper::getPerformaceOtherSubjectsName($otherIds);
            }
            
                
                @endphp
                             
                         <th>Exam</th>
                          @if(!empty($subjectList[0]))
                          @foreach($subjectList as $list)
        
        
                        <th>
                            {{$list}}
                        </th>                          
                          
                          @endforeach
                           @endif
                            @if(!empty($otherList[0]))
                          @foreach($otherList as $list)
        
        
                        <th>
                            {{$list}}
                        </th>                          
                          
                          @endforeach
                        
                          @endif
                        </tr>
                        </thead>
                     <tbody>
                         
                         @if(!empty($examIds[0]))
                         @foreach($examIds as $exam)
                         
                         @php
                        $exam_name = DB::table('exams')->whereNull('deleted_at')->where('id',$exam)->first();
                         @endphp
                         
                         <tr>
                             <td>
                                {{ $exam_name->name ?? ''}}
                             </td>
                             
                                @if(!empty($subjectIds[0]))
                          @foreach($subjectIds as $subjectId)
                          @php
                            $performanceData = Helper::getParticularPerformaceData($data->id,$exam,$subjectId,$data->class_type_id);
                            
                            
        $maxMarks = $performanceData['max']->exam_maximum_marks ?? 0;
        $studentMarks = $performanceData['mark']->student_marks ?? 0;
        $percentage = $maxMarks > 0 ? number_format(($studentMarks / $maxMarks) * 100, 2) : 'N/A';

 
                          @endphp
                          <td >
                              <div  style='display:grid'>
                              <span class='text-success'>Obtained = {{$performanceData['mark']->student_marks ?? 0}}</span>
                              <span  class='text-danger'>Maximum = {{$performanceData['max']->exam_maximum_marks ?? 0}}</span>
                              <span  class='text-info'>Percentage = {{ $percentage}}%</span>
                               </div> 
                          </td>
                          
                          @endforeach
                          @endif
                          
                          
                        
                          
                          
                           @if(!empty($otherIds[0]))
                          @foreach($otherIds as $otherId)
                          @php
                            $performanceData = Helper::getParticularOtherPerformaceData($data->id,$exam,$otherId,$data->class_type_id);
                            
                            
       
        $studentMarks = $performanceData['mark']->student_marks ?? 0;
       

 
                          @endphp
                          <td >
                              <div  style='display:grid'>
                              <span class='text-success'>Obtained = {{$performanceData['mark']->student_marks ?? ''}}</span>
                             
                               </div> 
                          </td>
                          
                          @endforeach
                          @endif
                          
                          
                          
                          
                          
                          
                          
                          
                         </tr>
                         
                         @endforeach
                         
                         @endif
                         
                     </tbody>
                        
                        </table>
                  
                  </div>
                  @endif
                </div>
                
                
                </div>
               
         <div class='col-md-3'>
             
            <div class='row'>
                
            
                         <div class="col-12 col-md-12">
                 
                        <div class="info-box mb-3 text-dark">
                            <span class="info-box-icon bg-danger elevation-1"><i class="ion ion-stats-bars"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">STUDENT ATTENDANCE</span>
                                <span class="info-box-number">
                                    {{$attendance}}
                                 
                                </span>
                            </div>
                        </div>
                    
                </div>
      
            </div>
    
         </div>
                  
                  
                 

        
  </div>
  </div>
  </section>
  </div>
              
       <script>
          function printTable() {
            var printWindow = window.open('', '', 'height=600,width=800');
            var table = document.getElementById('printTable').outerHTML;

            // Create a print style for the new window
            var printStyle = `
                <style>
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                </style>
            `;

            printWindow.document.write('<html><head><title>Print Table</title>');
            printWindow.document.write(printStyle); // Add the print styles
            printWindow.document.write('</head><body>');
            printWindow.document.write(table);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
     
       </script>
            
            
@endsection