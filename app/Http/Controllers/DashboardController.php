<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Validator; 
use App\Models\SidebarSub;
use App\Models\User;
use App\Models\Dashboard;
use App\Models\FeesMaster;
use App\Models\TeacherAttendance;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use App\Models\PermissionManagement;
use App\Models\Enquiry;
use App\Models\hostel\HostelAssign;
use App\Models\Admission;
use App\Models\FeesDiscount;
use App\Models\library\LibraryAssign;
use App\Helpers\helper;
use Session;
use Hash;
use Str;
use Redirect;
use Response;
use Auth;
use App\Models\FeesDetail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Detection\MobileDetect;

class DashboardController extends Controller

{
    
    public function dashboard(){

        
        $current_date = date('Y-m-d');
        $result = array();
        $barnch =Session::all();

        if($barnch['role_id'] != 3){
            
            return view('dashboard.admin_dashboard');            
        }
        else{
                return view('student_login.dashboard');
            
        }

     
      
    }

 

  
    
 
    

    public function taskList(){
        return view('task_list');
    }

    
    public function studentDetail($id){
         $studentDetail = Admission::with('ClassTypes')->with('Section')->find($id);
         $feesDetail =  FeesDetail::with('PaymentMode')->with('FeesCollect')->with('FeesType')->where('admission_id', $id)->where('branch_id',Session::get('branch_id'))->get();
        return view('dashboard.admin.student_detail',['data'=>$studentDetail,'feesDetail'=>$feesDetail]);
    }

    public function stuStatus(Request $request){
        
       if($request->id >0){
        $data = Admission::where('id',$request->id)->update(['status'=>$request->status]);
      
       if(!empty($data)){
       echo json_encode(1);
            }else{
                 echo json_encode(0);
            }
        }else{
        echo json_encode(2);
        }
        
    }       


    
    




	
}
