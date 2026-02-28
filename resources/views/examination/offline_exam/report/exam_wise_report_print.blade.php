@php
    $getSetting=Helper::getSetting();
    $getSession=Helper::getSession();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>   Exam Wise Report </title>
    <style>


      .marksheet{
            border: 1px solid;
        }
        h2 {
            text-align: center;
            color: #d32f2f;
        }
    table td, table th {
      padding: 4px;
      vertical-align: top;
      border: 1px solid #01060b;
    }
    .table thead th {
      vertical-align: bottom;
      border: 1px solid #5e6975 !important;
    }
    </style>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
          /*  max-width: 714px;
            margin: 20px auto;*/
            /*max-width: 750px;
            margin: 0 auto;*/
            /* border: 0.5px solid; */
            /*line-height: 16px;*/
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0px;
        }


    </style>
</head> 

<body class='page'>
@php
    $session  = DB::table('sessions')->where('id',Session::get('session_id'))->whereNull('deleted_at')->first();
@endphp
                     <div class="row">
                        <div class='col-md-12'>
                                                 <div class="marksheet " id="print-area">
                            
                            
                            <table  style="margin-bottom: 0px;">
                                <tr>
                                    <td rowspan="2" style="border-right:none;">
                                       <img  src="{{ env('IMAGE_SHOW_PATH').'/setting/left_logo/'.$getSetting['left_logo'] }}" style="width: 150px;" onerror="this.src='{{ env('IMAGE_SHOW_PATH').'/default/rukmani_logo.png' }}'" >
                                    </td>
                                   
                                    <td  style="font-size:32px;text-align:center;border-left:none;border-bottom:none;">
                                        <span>
                                            <strong>{{$getSetting['name'] ?? ''}}</strong>
                                        </span>
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <td style="border-left:none;border-top:none;font-size:22px;text-align:center;">
                                        <p ><b >Address </b> {{$getSetting['address'] ?? ''}} </p>
                                        <p ><b >Phone:-</b> {{$getSetting['mobile'] ?? ''}} &nbsp;<b>Email :</b> {{$getSetting['gmail'] ?? ''}}</p>
                                        
                                    </td>
                                </tr>
                            </table>
                            
                            
                                    <table class="table" style="margin-bottom: 0px;display:none;"> 
                                        <tr>
                                              
                                             @if(Session::get('branch_id') == 1)
                                              <td colspan="12" class="logo" style="text-align: center; width: 57%;"> 
                                                  <img src="{{ env('IMAGE_SHOW_PATH').'/default/'}}KIDS_logo.jpg" style="width: 92%;margin-top: 4px; height: 78px;">
                                                </td>
                                              @else
                                              <td colspan="12" class="logo" style=" text-align: center; width: 70%;"> 
                                                        <img src="{{ env('IMAGE_SHOW_PATH').'/default/'}}Senior.jpg" style="width: 88%;height: 76px;margin-top: 4px;">
                                      </td>
                                              @endif 
                                            
                                        </tr>
                                      
                                    </table>
                                     @php
                                            $className = DB::table('class_types')->where('id',$search['class_type_id'])->where('branch_id',Session::get('branch_id'))->whereNull('deleted_at')->first();
                                                     $session=DB::table('sessions')->where('id',Session::get('session_id'))->whereNull('deleted_at')->first();
                                                     $exams_name=DB::table('exams')->where('id',$search['exam_id'])->whereNull('deleted_at')->first();

                                            @endphp
                                 
                                       
                            
                                <table class="table">
                                    <thead>
                                          <tr>
                                              
                                            
                                              <td colspan="6" style="font-size: 21px;padding: 8px;"> 
                                              SESSION : {{$session->from_year ?? '' }}{{"-"}}{{$session->to_year ?? ''}}
                                                </td>
                                              
                                             
                                              <td colspan="6" style="font-size: 21px;padding: 8px;text-align: center;">      Exam  : {{$exams_name->name ?? ''}}   </td>
                                               <td colspan="6" style="font-size: 21px;padding: 8px;text-align: right;">      Class : {{$className->name ?? ''}}   </td>
                                            
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th style="width: 34px;">S.R No</th>
                                            <th style="width: 133px;">Name of Student</th>
                                           <!-- <th>Father's Name</th>-->
                                            @if(!empty($list_subject))
                                            @php
                                           $total = 0;
                                            @endphp
                                                @foreach($list_subject as $key => $item_subject)
                                                @php
                                                 $number_max = DB::table('fill_min_max_marks')
                                                        ->where('exam_id',$search['exam_id'])
                                                                    ->where('subject_id',$item_subject->id ?? '')
                                                        ->where('class_type_id',$search['class_type_id'] ?? '')
                                                        ->where('session_id',Session::get('session_id'))
                                                        ->where('branch_id',Session::get('branch_id'))
                                                        ->where('deleted_at',null)
                                                        ->first();
                                                   if ($number_max !== null) {
                                                if($item_subject->other_subject == 0){ 
                                                $total += $number_max->exam_maximum_marks;
                                                }else{
                                                 $total += 0;
                                                } 
                                                }
                                                @endphp
                                                       <th style="padding: 0px 0px 4px 0px;">{{$item_subject->name ?? '' }} ({{$number_max->exam_maximum_marks ?? '-' }})</th>
                                                 @endforeach
                                             @endif
                                            <th>Total({{$total ?? ''}})</th>
                                          <!--  <th>Grade</th>-->
                                            <th>%</th>
                                            <th style="width: 72px;">Date</th>
                                            <th style="width: 82px;">Parents Sign.</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($students))
                                        @php
                                        $i = 1;
                                       
                                        @endphp
                                        @foreach($students as $student)
                                   <tr>
                                      
                                        <td style="text-align: center;">{{$i++}}</td>
                                        <td style="text-align: center;">{{$student->admissionNo ?? ''}}</td>
                                        <td>{{$student->first_name ?? ''}}</td>
<!--                                        <td>{{$student->father_name ?? ''}}</td>
-->                                        
                                        @if(!empty($list_subject))
                                            @php
                                                $total_obtained = 0; // Initialize total obtained marks
                                                $exam_maximum_marks = 0; // Initialize total max marks
                                            @endphp
                                    
                                            @foreach($list_subject as $key => $item_subject)
                                                @php
                                               
                                                    // Fetch marks for this subject
                                                    $number = DB::table('fill_marks')
                                                        ->where('exam_id', $search['exam_id'])
                                                        ->where('subject_id', $item_subject->id ?? '')
                                                        ->where('admission_id', $student->id)
                                                        ->where('session_id', Session::get('session_id'))
                                                        ->where('branch_id', Session::get('branch_id'))
                                                        ->where('deleted_at', null)
                                                        ->first();
                                    
                                                    // Fetch max marks for this subject
                                                    $number_max = DB::table('fill_min_max_marks')
                                                        ->where('exam_id', $search['exam_id'])
                                                        ->where('subject_id', $item_subject->id ?? '')
                                                        ->where('class_type_id', $search['class_type_id'] ?? '')
                                                        ->where('session_id', Session::get('session_id'))
                                                        ->where('branch_id', Session::get('branch_id'))
                                                        ->where('deleted_at', null)
                                                        ->first();
                                    
                                                    
                                                    if ($number_max !== null) {
                                                        if ($number !== null) {
                                                            $student_marks = $number->student_marks;
                                                        if($item_subject->other_subject == 0){
                                                            if ($student_marks == 'AB' || $student_marks == 'F') {
                                                            
                                                                $total_obtained += 0; 
                                                                $exam_maximum_marks += $number_max->exam_maximum_marks; 
                                                            } elseif (is_numeric($student_marks)) {
                                                                $total_obtained += floatval($student_marks); 
                                                                $exam_maximum_marks += $number_max->exam_maximum_marks; 
                                                            }
                                                            }else{
                                                             $total_obtained += 0; 
                                                                $exam_maximum_marks += 0; 
                                                            }
                                                        }
                                                    }
                                                @endphp
                                    
                                                <td>{{$number->student_marks ?? '-'}}</td>
                                            @endforeach
                                    
                                            <td>{{$total_obtained ?? ''}}</td>
                                            @php
                                                $percentage = ($exam_maximum_marks > 0) ? ($total_obtained / $exam_maximum_marks) * 100 : 0;
                                            @endphp
                                            <!--<td>
                                                @if( $percentage >=60 && $percentage <=100 )
                                                         A
                                                        @elseif( $percentage >=48 && $percentage <=59.99)
                                                      B
                                                        @elseif( $percentage >=36 && $percentage <=47.99)
                                                       C
                                                        @elseif( $percentage >=0.1 && $percentage <=35.99)
                                                     F
                                                       @else
                                                       -
                                                            @endif
                                                
                                            </td>-->
                                    
                                            
                                            <td style="text-align: center;">{{ round($percentage, 2) }}</td>
                                            <td>  </td>
                                            <td>  </td>
                                        @endif
                                    </tr>

                                        @endforeach
                                     @endif
                                  
                                        <!-- Continue rows here... -->
                                    </tbody>
                                </table>
                             </div>
     

                     </div>
                    
    
 </div>

    
    
</body>
<style>
    @page
{
size: landscape;
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function stripeRows() {
            $('.strippedTable tbody tr:even').addClass('striped');
        }
        stripeRows();
    });
</script>
<script type="text/javascript">
window.print();
</script>
</html>