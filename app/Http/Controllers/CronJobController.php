<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Validator; 
use App\Models\User;
use App\Models\Admission;
use App\Models\Salary;
use App\Models\SmsSetting;
use App\Models\WhatsappSetting;
use App\Models\StaffAttendance;
use App\Models\TeacherCategory;
use App\Models\StudentAttendance;
use App\Models\TeacherAttendance;
use App\Models\Teacher;
use App\Models\Master\MessageTemplate;
use App\Models\AttendanceStatus;
use App\Models\Setting;
use App\Models\Master\Branch;
use App\Models\CronJobs;
use Session;
use Hash;
use Helper;
use Str;
use Redirect;
use Carbon\Carbon;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CronJobController extends Controller

{

 
    public function cronJobs(){

       // $this->attendanceSendMassage();
        

    }   
  
   
   public function updateStaffAttendanceStatus($todate){
         
         $student = TeacherAttendance::whereDate('date',$todate)->groupBy('staff_id')->orderBy('id','ASC')->pluck('staff_id')->implode(',');
         
         if(!empty($student))
         {
             $student = explode(',',$student);
        
        foreach($student as $item)
        
        {
        $data = TeacherAttendance::whereDate('date',$todate)->where('staff_id',$item)->get();
        
            // Filter the data
$filteredData = collect();
$inRecord = null;

foreach ($data as $key=>$record) {
       $updateStatus = TeacherAttendance::find($data[0]->id); 
    if ($key == 0) {
      
        $inRecord = $record;
        
     
        if(empty($updateStatus->attendance_status_message))
        {
            
            $array = [['biomatric'=>'yes','in'=>$inRecord->time,'in_message_status'=>'','out'=>'','out_message_status'=>'']];
            $updateStatus->attendance_status_message = json_encode($array);
                        
            $updateStatus->save();
        }
        else
        {
            $array = json_decode($updateStatus->attendance_status_message, true);
    $array[0]['in'] = $inRecord->time;
    $updateStatus->attendance_status_message = json_encode($array); 
    
    $updateStatus->save();
        }
        
    } else {
        
        if(empty($inRecord->time))
        {
            return;
        }
        
        $inTime = Carbon::parse($inRecord->time);
        $outTime = Carbon::parse($record->time);

        if ($inTime->diffInMinutes($outTime) >= 1) {
            // Keep checkout when second punch is at least 1 minute after check-in.
        
           if(!empty($updateStatus->attendance_status_message))
        {
            $array = json_decode($updateStatus->attendance_status_message, true);
    $array[0]['out'] = $record->time;
    $updateStatus->out_time=$outTime;
      $updateStatus->attendance_status_message = json_encode($array);
    
    if($array[0]['out_message_status'] == '') 
    {
    $updateStatus->save();
    }
        }
        if(($record->id ?? '') != '')
           {
               //StudentAttendance::find($record->id)->forceDelete();
                             $duplicate = TeacherAttendance::find($record->id);

if ($duplicate) {
    $existingEntries = $updateStatus->duplicate_entries ?? '';
    $newTime = $duplicate->time;
    if ($existingEntries) {
        $updateStatus->duplicate_entries = $existingEntries . ',' . $newTime;
    } else {
        $updateStatus->duplicate_entries = $newTime;
    }
     if($array[0]['out_message_status'] != '') 
    {
    $updateStatus->save();
    }
    
    $duplicate->forceDelete();
}
           }
            $inRecord = null; // Reset for the next pair
        } 
        else
        {
           if(($record->id ?? '') != '')
           {
               
              // StudentAttendance::find($record->id)->forceDelete();
              $duplicate = TeacherAttendance::find($record->id);

if ($duplicate) {
    $existingEntries = $updateStatus->duplicate_entries ?? '';
    $newTime = $duplicate->time;
    if ($existingEntries) {
        $updateStatus->duplicate_entries = $existingEntries . ',' . $newTime;
    } else {
        $updateStatus->duplicate_entries = $newTime;
    }
    $updateStatus->save();
    $duplicate->forceDelete();
}
           }
        }
    }
}
       
        }
       
         }
     }
   

      public function attendanceSendMassage(){
         $todate = date('Y-m-d');
      // $todate = '2025-01-29';
     $this->updateAttendanceStatus($todate);
    

                                $template =  MessageTemplate::Select('message_templates.*','message_types.slug')
                                        ->leftjoin('message_types','message_types.id','message_templates.message_type_id')
                                      ->where('message_types.status',1)->where('message_types.slug','attendance')->first();
    
    $student = StudentAttendance::leftJoin('admissions', 'student_attendance.admission_id', '=', 'admissions.id')
    ->whereDate('student_attendance.date', $todate)
    ->where(function($query) {
        $query->where('student_attendance.message_status', 0)
              ->orWhereRaw('JSON_EXTRACT(attendance_status_message, "$[0].out_message_status") = ""');
    })
    ->orderBy('student_attendance.id', 'ASC')
    ->select('student_attendance.*', 'admissions.first_name', 'admissions.last_name', 'admissions.mobile')
    ->groupBy('student_attendance.admission_id')
    ->get();
            $receiverNames =[];
            
             foreach($student as $studen){
                    if(!empty($studen))
                    {
                         $branch = Branch::find($studen->branch_id);
                                $setting = Setting::where('branch_id',$studen->branch_id)->first(); 
                                $arrey1 =   array(
                                                '{#name#}',
                                                '{#today_day#}',
                                                '{#attendance_time#}',
                                                '{#attendance_status#}',
                                                '{#support_no#}',
                                                '{#school_name#}');
                   
                    $decode = json_decode($studen->attendance_status_message ?? []);
                       
                      if(!empty($decode))
                      {
                          if($decode[0]->biomatric == 'yes')
                          {
                              if($decode[0]->in_message_status != 'Checked')
                              {
                                   
                                  $mark = 'IN';
                                //  if ($studen->time > '17:46:00') {
                                //           //  $mark = 'Absent'; 
                                //             $mark = 'OUT';
                                //         } 
                                    $arrey2 = array(
                                                $studen->first_name." ".$studen->last_name,
                                                date('d-m-Y',strtotime($studen->date)),
                                                date('h:i A',strtotime($decode[0]->in)),
                                                    'IN',
                                                $setting->mobile,
                                                $setting->name);
                                                
                                                  $whatsapp = str_replace($arrey1,$arrey2,$template->whatsapp_content);
                                                  $whatsapp = preg_replace('/Absent.*?"/', 'Absent"', $whatsapp);
                                                  if($decode[0]->in != '')
                                                  {
                                  $receiverNames[] = ['field'=>'in_message_status','attendance_id'=>$studen->id,'mobile'=>$studen->mobile,'message'=>$whatsapp];
                                                  }
                                                      
                                                  }
                              elseif($decode[0]->in_message_status == 'Checked' && $decode[0]->out_message_status != 'Checked')
                              {
                                    $arrey2 = array(
                                                $studen->first_name." ".$studen->last_name,
                                                date('d-m-Y',strtotime($studen->date)),
                                                date('h:i A',strtotime($decode[0]->out)),
                                                'Out',
                                                $setting->mobile,
                                                $setting->name);
                                                
                                                 $whatsapp = str_replace($arrey1,$arrey2,$template->whatsapp_content);
                                                 if($decode[0]->out != '')
                                                  {
                                                     
                                  $receiverNames[] = ['field'=>'out_message_status','attendance_id'=>$studen->id,'mobile'=>$studen->mobile,'message'=>$whatsapp];
                              }
                              }
                          }
                          else
                          {
                              //without biomatrci
                          }
                           
                      }
                       
                    }
             }
          
                            if($template->status != 1){
                               
                                  if(empty($branch->whatsapp_srvc))
                                  {
                                     
                                      return;
                                  }
                                     
                                if($branch->whatsapp_srvc != 0){
                                  
                                 if(!empty($receiverNames)) 
                                 {
                                     foreach($receiverNames as $item)
                                     {
                                                                         

                                        if($template->whatsapp_status != 0){
                                              
   
                                        $response =   Helper::sendWhatsappMessage($item['mobile'],$item['message']); 
                                         // $response1 = json_decode($response);
                                       
                                      // $messageTimestamp = isset($response1->message->messageTimestamp) ? $response1->message->messageTimestamp : null;

  $attendance = StudentAttendance::find($item['attendance_id']);

if ($attendance) {
    $attendanceStatusMessage = json_decode($attendance->attendance_status_message, true);

$field = $item['field'];
    $attendanceStatusMessage[0][$field] = 'Checked';

    $attendance->attendance_status_message = json_encode($attendanceStatusMessage);
    
if($field == 'in_message_status' )
{
    $attendance->message_status =1;
}
else
{
        $attendance->message_status=2;
}
    $attendance->save();
}

                                        }
                                       
                                 }
                                }
                                 
                                }
                                
                               
                            }
                 
    } 

   
        public function updateAttendanceStatus($todate){
         
         $student = StudentAttendance::whereDate('date',$todate)->groupBy('admission_id')->orderBy('id','ASC')->pluck('admission_id')->implode(',');
         
         if(!empty($student))
         {
             $student = explode(',',$student);
        
        foreach($student as $item)
        
        {
        $data = StudentAttendance::whereDate('date',$todate)->where('admission_id',$item)->get();
        
            // Filter the data
$filteredData = collect();
$inRecord = null;

foreach ($data as $key=>$record) {
       $updateStatus = StudentAttendance::find($data[0]->id); 
    if ($key == 0) {
      
        $inRecord = $record;
        
     
        if(empty($updateStatus->attendance_status_message))
        {
            
            $array = [['biomatric'=>'yes','in'=>$inRecord->time,'in_message_status'=>'','out'=>'','out_message_status'=>'']];
            $updateStatus->attendance_status_message = json_encode($array);
                        
            $updateStatus->save();
        }
        else
        {
            $array = json_decode($updateStatus->attendance_status_message, true);
    $array[0]['in'] = $inRecord->time;
    $updateStatus->attendance_status_message = json_encode($array); 
    
    $updateStatus->save();
        }
        
    } else {
        
        if(empty($inRecord->time))
        {
            return;
        }
        
        $inTime = Carbon::parse($inRecord->time);
        $outTime = Carbon::parse($record->time);

        if ($inTime->diffInMinutes($outTime) >= 1) {
            // Keep checkout when second punch is at least 1 minute after check-in.
        
           if(!empty($updateStatus->attendance_status_message))
        {
            $array = json_decode($updateStatus->attendance_status_message, true);
    $array[0]['out'] = $record->time;
    $updateStatus->out_time=$outTime;
      $updateStatus->attendance_status_message = json_encode($array);
    
    if($array[0]['out_message_status'] == '') 
    {
    $updateStatus->save();
    }
        }
        if(($record->id ?? '') != '')
           {
               //StudentAttendance::find($record->id)->forceDelete();
                             $duplicate = StudentAttendance::find($record->id);

if ($duplicate) {
    $existingEntries = $updateStatus->duplicate_entries ?? '';
    $newTime = $duplicate->time;
    if ($existingEntries) {
        $updateStatus->duplicate_entries = $existingEntries . ',' . $newTime;
    } else {
        $updateStatus->duplicate_entries = $newTime;
    }
     if($array[0]['out_message_status'] != '') 
    {
    $updateStatus->save();
    }
    
    $duplicate->forceDelete();
}
           }
            $inRecord = null; // Reset for the next pair
        } 
        else
        {
           if(($record->id ?? '') != '')
           {
               
              // StudentAttendance::find($record->id)->forceDelete();
              $duplicate = StudentAttendance::find($record->id);

if ($duplicate) {
    $existingEntries = $updateStatus->duplicate_entries ?? '';
    $newTime = $duplicate->time;
    if ($existingEntries) {
        $updateStatus->duplicate_entries = $existingEntries . ',' . $newTime;
    } else {
        $updateStatus->duplicate_entries = $newTime;
    }
    $updateStatus->save();
    $duplicate->forceDelete();
}
           }
        }
    }
}
       
        }
       
         }
     }
   












    
   
}
