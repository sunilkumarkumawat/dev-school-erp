<?php

namespace App\Http\Controllers\master;
use Illuminate\Validation\Validator; 

use App\Models\User;
use App\Models\Admin;
use App\Models\Account;
use App\Models\Admission;
use App\Models\Admit;
use App\Models\AdmitCardNote;
use App\Models\exam\AssignExam;
use App\Models\exam\Exam;
use App\Models\exam\AssignQuestion;
use App\Models\exam\ExamResult;
use App\Models\exam\ExamResultDetail;
use App\Models\exam\FillMarks;
use App\Models\exam\FillMinMaxMarks;
use App\Models\exam\Question;
use App\Models\examoffline\AssignOfflineExam;
use App\Models\examoffline\ExamOffline;
use App\Models\fees\FeesAssign;
use App\Models\fees\FeesCounter;
use App\Models\fees\FeesAssignDetail;
use App\Models\hostel\ElectricityBillPayment;
use App\Models\hostel\FoodMenuList;
use App\Models\hostel\Head;
use App\Models\hostel\Hostel;
use App\Models\hostel\HostelAssign;
use App\Models\hostel\HostelBed;
use App\Models\hostel\HostelBuilding;
use App\Models\hostel\HostelExpences;
use App\Models\hostel\HostelFloor;
use App\Models\hostel\HostelMeterUnit;
use App\Models\hostel\HostelRoom;
use App\Models\hostel\MessFeesStrucher;
use App\Models\hostel\MessFoodCategory;
use App\Models\hostel\MessFoodRoutine;
use App\Models\hostel\SecurityDeposit;
use App\Models\hostel\StudentExpense;
use App\Models\hostel\StudentExpenseDetail;
use App\Models\library\AssignBook;
use App\Models\library\BookInvoice;
use App\Models\library\Library;
use App\Models\library\LibraryAssign;
use App\Models\library\LibraryBook;
use App\Models\library\LibraryCabin;
use App\Models\library\LibraryCategory;
use App\Models\library\LibraryLocker;
use App\Models\library\LibraryPlan;
use App\Models\library\LibraryTimeSlot;
use App\Models\library\RetrunBook;
use App\Models\Setting;
use App\Models\Master\Branch;
use App\Models\Master\Complaint;
use App\Models\Master\BooksUniformShop;
use App\Models\Master\Bus;
use App\Models\Master\BusAssign;
use App\Models\Master\BusRoute;
use App\Models\Master\BusRouteAssign;
use App\Models\Master\EmailRecords;
use App\Models\Master\EmailTamplate;
use App\Models\Master\EnquiryStatus;
use App\Models\Master\EventManagement;
use App\Models\Master\Gallery;
use App\Models\Master\GatePass;
use App\Models\Master\Holidays;
use App\Models\Master\Homework;
use App\Models\Master\HomeworkDocuments;
use App\Models\Master\HomeworkReview;
use App\Models\Master\HourlyHomework;
use App\Models\Master\InvantoryBooking;
use App\Models\Master\InvantoryDresh;
use App\Models\Master\LeaveManagement;
use App\Models\Master\MessageTemplate;
use App\Models\Master\MessageType;
use App\Models\Master\NoticeBoard;
use App\Models\Master\Penalty;
use App\Models\Master\Prayer;
use App\Models\Master\RecycleBin;
use App\Models\Master\RegistrationTerms;
use App\Models\Master\Sport;
use App\Models\Master\Sports;
use App\Models\Master\Stork;
use App\Models\Master\TeacherSubject;
use App\Models\Master\Time_Table;
use App\Models\Master\TimePeriods;
use App\Models\Master\Uniform;
use App\Models\Master\UploadHomework;
use App\Models\Master\Sessions;
use App\Models\BillCounter;
use App\Models\PermissionManagement;
use App\Models\ClassType;
use App\Models\DownloadCenter;
use App\Models\EventeCertificate;
use App\Models\ExaminationAdmitCard;
use App\Models\ExaminationSchedule;
use App\Models\ExaminationScheduleDetail;
use App\Models\Invantory;
use App\Models\InvantoryItem;
use App\Models\getExamType;
use App\Models\FeesCollect;
use App\Models\FeesDetail;
use App\Models\SchoolCalender;
use App\Models\SportCertificate;
use App\Models\StudentMarksDetails;
use App\Models\StudentsMarks;
use App\Models\StaffSalary;
use App\Models\StaffSalaryDetail;
use App\Models\StudentAttendance;
use App\Models\StudentAction;
use App\Models\TcCertificate;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeacherDocuments;
use App\Models\TeachersAccounts;
use App\Models\ToDoList;
use App\Models\TotalDays;
use App\Models\Subject;
use App\Models\Remark;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Enquiry;
use App\Models\CcForm;
use App\Models\Chat;
use App\Models\City;

use Session;
use Hash;
use Str;
use Redirect;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionsController extends Controller

{
    
            public function add(Request $request){
                if($request->isMethod('post')){
                    $request->validate([
                        'from_year'  => 'required|unique:sessions,from_year',
                        'to_year'  => 'required',
                    ]);
                    $class = new Sessions;//model name
                    $class->user_id = Session::get('id');
                    $class->branch_id = Session::get('branch_id');
            		$class->from_year =$request->from_year;
            		$class->to_year =$request->to_year;
                    $class->save();
                    $sessions_id = $class->id;
                    $data = BillCounter::where('branch_id',Session::get('branch_id'))->get();
                    foreach($data as $val){
                        $add = Branch::all();
                         foreach($add as $val1){
                        $bill = new BillCounter;
                        $bill->user_id = Session::get('id');
                        $bill->branch_id = $val1->id;
                        $bill->session_id = $sessions_id;
                        $bill->type = $val->type;
                        $bill->counter = 0;
                        $bill->save();
                         }
                    }
                    // return redirect::to('session_add')->with('message', ' Sessions add Successfully.');
                     return response()->json(['status' => 'success','message' => 'Sessions add Successfully.',]);
                }    
                    $data = Sessions::all();
                    return view('master.Sessions.add',['sessions'=>$data]);
            }    
    
            public function edit(Request $request,$id){
                $data = Sessions::find($id);
                if($request->isMethod('post')){
                    $request->validate([
                        'from_year'  => 'required',
                        'to_year'  => 'required',
                    ]);
                    $data->user_id =Session::get('id');
                    $data->branch_id = Session::get('branch_id');
                    $data-> from_year=$request->from_year;
                    $data-> to_year=$request->to_year;
                    $data->save();
                    return response()->json(['status' => 'success', 'message' => 'Sessions Updated Successfully.','redirect' => url('session_add')]);
                }
                $dataview = Sessions::all();
                return view('master.Sessions.edit',['data'=>$data, 'dataview'=>$dataview]);
            }    
          /*  public function delete(Request $request){
               
                $id = $request->delete_id;
               
                $sss = Sessions::find($id)->delete();
                 return redirect::to('sessions_add')->with('message', 'Sessions  Delete Successfully.');
            }*/
 
            public function delete(Request $request){
                AssignBook::where('session_id',$request->delete_id)->delete();
                Account::where('session_id',$request->delete_id)->delete();
                Admission::where('session_id',$request->delete_id)->delete();
                Admit::where('session_id',$request->delete_id)->delete();
                AssignExam::where('session_id',$request->delete_id)->delete();
                BillCounter::where('session_id',$request->delete_id)->delete();
                Setting::where('session_id',$request->delete_id)->delete();
                Complaint::where('session_id',$request->delete_id)->delete();
                CcForm::where('session_id',$request->delete_id)->delete();
                Chat::where('session_id',$request->delete_id)->delete();
                DownloadCenter::where('session_id',$request->delete_id)->delete();
                Enquiry::where('session_id',$request->delete_id)->delete();
                EventeCertificate::where('session_id',$request->delete_id)->delete();
                // ExaminationAdmitCard::where('session_id',$request->delete_id)->delete();
                // ExaminationSchedule::where('session_id',$request->delete_id)->delete();
                // ExaminationScheduleDetail::where('session_id',$request->delete_id)->delete();
                Expense::where('session_id',$request->delete_id)->delete();
                FeesCollect::where('session_id',$request->delete_id)->delete();
                FeesDetail::where('session_id',$request->delete_id)->delete();
                // getExamType::where('session_id',$request->delete_id)->delete();
                Invantory::where('session_id',$request->delete_id)->delete();
                InvantoryItem::where('session_id',$request->delete_id)->delete();
                Invoice::where('session_id',$request->delete_id)->delete();
                Remark::where('session_id',$request->delete_id)->delete();
                SchoolCalender::where('session_id',$request->delete_id)->delete();
                SportCertificate::where('session_id',$request->delete_id)->delete();
                StaffSalary::where('session_id',$request->delete_id)->delete();
                StaffSalaryDetail::where('session_id',$request->delete_id)->delete();
                StudentAction::where('session_id',$request->delete_id)->delete();
                StudentAttendance::where('session_id',$request->delete_id)->delete();
                // StudentMarksDetails::where('session_id',$request->delete_id)->delete();
                StudentsMarks::where('session_id',$request->delete_id)->delete();
                Subject::where('session_id',$request->delete_id)->delete();
                TcCertificate::where('session_id',$request->delete_id)->delete();
                Teacher::where('session_id',$request->delete_id)->delete();
                TeacherAttendance::where('session_id',$request->delete_id)->delete();
                TeacherDocuments::where('session_id',$request->delete_id)->delete();
                TeachersAccounts::where('session_id',$request->delete_id)->delete();
                ToDoList::where('session_id',$request->delete_id)->delete();
                // TotalDays::where('session_id',$request->delete_id)->delete();
                AssignQuestion::where('session_id',$request->delete_id)->delete();
                Exam::where('session_id',$request->delete_id)->delete();
                ExamResult::where('session_id',$request->delete_id)->delete();
                ExamResultDetail::where('session_id',$request->delete_id)->delete();
                FillMarks::where('session_id',$request->delete_id)->delete();
                FillMinMaxMarks::where('session_id',$request->delete_id)->delete();
                Question::where('session_id',$request->delete_id)->delete();
                AssignOfflineExam::where('session_id',$request->delete_id)->delete();
                ExamOffline::where('session_id',$request->delete_id)->delete();
                FeesAssign::where('session_id',$request->delete_id)->delete();
                FeesAssignDetail::where('session_id',$request->delete_id)->delete();
                ElectricityBillPayment::where('session_id',$request->delete_id)->delete();
                FoodMenuList::where('session_id',$request->delete_id)->delete();
                Head::where('session_id',$request->delete_id)->delete();
                Hostel::where('session_id',$request->delete_id)->delete();
                HostelAssign::where('session_id',$request->delete_id)->delete();
                HostelBed::where('session_id',$request->delete_id)->delete();
                HostelBuilding::where('session_id',$request->delete_id)->delete();
                HostelExpences::where('session_id',$request->delete_id)->delete();
                HostelFloor::where('session_id',$request->delete_id)->delete();
                HostelMeterUnit::where('session_id',$request->delete_id)->delete();
                HostelRoom::where('session_id',$request->delete_id)->delete();
                MessFeesStrucher::where('session_id',$request->delete_id)->delete();
                MessFoodCategory::where('session_id',$request->delete_id)->delete();
                MessFoodRoutine::where('session_id',$request->delete_id)->delete();
                SecurityDeposit::where('session_id',$request->delete_id)->delete();
                StudentExpense::where('session_id',$request->delete_id)->delete();
                StudentExpenseDetail::where('session_id',$request->delete_id)->delete();
                BookInvoice::where('session_id',$request->delete_id)->delete();
                Library::where('session_id',$request->delete_id)->delete();
                LibraryAssign::where('session_id',$request->delete_id)->delete();
                LibraryBook::where('session_id',$request->delete_id)->delete();
                LibraryCabin::where('session_id',$request->delete_id)->delete();
                LibraryCategory::where('session_id',$request->delete_id)->delete();
                LibraryLocker::where('session_id',$request->delete_id)->delete();
                LibraryPlan::where('session_id',$request->delete_id)->delete();
                LibraryTimeSlot::where('session_id',$request->delete_id)->delete();
                // RetrunBook::where('session_id',$request->delete_id)->delete();
                BooksUniformShop::where('session_id',$request->delete_id)->delete();
                Bus::where('session_id',$request->delete_id)->delete();
                BusAssign::where('session_id',$request->delete_id)->delete();
                BusRoute::where('session_id',$request->delete_id)->delete();
                EventManagement::where('session_id',$request->delete_id)->delete();
                Gallery::where('session_id',$request->delete_id)->delete();
                GatePass::where('session_id',$request->delete_id)->delete();
                Holidays::where('session_id',$request->delete_id)->delete();
                Homework::where('session_id',$request->delete_id)->delete();
                HomeworkDocuments::where('session_id',$request->delete_id)->delete();
                HomeworkReview::where('session_id',$request->delete_id)->delete();
                HourlyHomework::where('session_id',$request->delete_id)->delete();
                // InvantoryBooking::where('session_id',$request->delete_id)->delete();
                // InvantoryDresh::where('session_id',$request->delete_id)->delete();
                LeaveManagement::where('session_id',$request->delete_id)->delete();
                NoticeBoard::where('session_id',$request->delete_id)->delete();
                Penalty::where('session_id',$request->delete_id)->delete();
                Prayer::where('session_id',$request->delete_id)->delete();
                RecycleBin::where('session_id',$request->delete_id)->delete();
                RegistrationTerms::where('session_id',$request->delete_id)->delete();
                Sport::where('session_id',$request->delete_id)->delete();
                Sports::where('session_id',$request->delete_id)->delete();
                Stork::where('session_id',$request->delete_id)->delete();
                TeacherSubject::where('session_id',$request->delete_id)->delete();
                Time_Table::where('session_id',$request->delete_id)->delete();
                TimePeriods::where('session_id',$request->delete_id)->delete();
                Uniform::where('session_id',$request->delete_id)->delete();
                UploadHomework::where('session_id',$request->delete_id)->delete();
                $setting = Sessions::find($request->delete_id)->delete();
                return redirect::to('session_add')->with('message', 'Sessions Deleted Successfully.');
            } 
            
            
            
            
            public function session_all(Request $request){
                $Counter[] = 'Teacher';
		$Counter[] = 'StudentRegistration';
		$Counter[] = 'StudentAdmission';
		$Counter[] = 'FeesSlip';
		$Counter[] = 'LibraryId';
		$Counter[] = 'HostelFees';
		$Counter[] = 'Hostel';
		//dd($Counter);
                    $add = Branch::all();
                     foreach($add as $val1){
                             $data = Sessions::all();

                    foreach($data as $val){
                         foreach($Counter as $Counter1){
                             
                        $bill = new BillCounter;
                        $bill->user_id = Session::get('id');
                        $bill->branch_id = $val1->id;
                        $bill->session_id = $val->id;
                        $bill->type = $Counter1;
                        $bill->counter = 0;
                        $bill->save();
                    }
                    }
                     }
                   
                   	
                    return view('master.Sessions.add',['sessions'=>$data]);
            } 
    
}
