<?php

namespace App\Http\Controllers\offline_exam;
use Illuminate\Validation\Validator;
use App\Models\exam\Question;
use App\Models\exam\Exam;
use App\Models\exam\AssignExam;
use App\Models\exam\FillMinMaxMarks;
use App\Models\exam\FillMarks;
use App\Models\Admission;
use App\Models\exam\ExaminationSchedule;
use App\Models\exam\ExaminationScheduleDetail;
use App\Models\examoffline\PerformanceMarks;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassType;
use App\Models\Master\TeacherSubject;
use Session;
use Helper;
use Str;
use Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{

    public function exam_wise_report(Request $request)
    {
        
               $search["exam_id"] = $request->exam_id  ?? "";

        $search["class_type_id"] = $request->class_type_id;

        $students = "";
        $examlist = "";
        $list_subject = "";
        
           $examlist = AssignExam::select(
                "assign_exams.*",
                "exam.id as exam_id",
                "exam.name as exam_name"
            )
                ->leftjoin("exams as exam", "assign_exams.exam_id", "exam.id")
                ->where("assign_exams.class_type_id", $request->class_type_id)
                ->where('assign_exams.session_id',Session::get("session_id"))
                 ->where('exam.deleted_at',null)->where("assign_exams.branch_id", Session::get("branch_id"))->orderBy('exam.id','ASC')
                ->get();
                
        if ($request->isMethod("post")) {
         
            $request->validate([]);
          
            $list_subject = Subject::where("class_type_id", $request->class_type_id)
                ->where("branch_id", Session::get("branch_id"));
                if(!empty($request->subject_id)){
                   $list_subject = $list_subject ->whereIn('id',$request->subject_id);
                }
                $list_subject = $list_subject->orderBy("sort_by", "ASC")
                ->get();
            $students = Admission::where("class_type_id", $request->class_type_id)
                ->where("session_id", Session::get("session_id"))
                ->where("branch_id", Session::get("branch_id"))->where('status',1)
                ->orderBy("first_name", "ASC")
                ->get();
        return view("examination.offline_exam.report.exam_wise_report_print", ["search" => $search,'examlist'=>$examlist,'students'=>$students,'list_subject'=>$list_subject]);

        }
      
        return view("examination.offline_exam.report.exam_wise_report", ["search" => $search]);
    }
    
    
    public function subjectWiseReport(Request $request)
    {
        

        $search["class_type_id"] = $request->class_type_id;
               $search["exam_id"] = $request->exam_id  ?? "1";

        $students = "";
        $examlist = "";
        $list_subject = "";
        
           $examlist = AssignExam::select(
                "assign_exams.*",
                "exam.id as exam_id",
                "exam.name as exam_name"
            )
                ->leftjoin("exams as exam", "assign_exams.exam_id", "exam.id")
                ->where("assign_exams.class_type_id", $request->class_type_id)
                ->where('assign_exams.session_id',Session::get("session_id"))
                 ->where('exam.deleted_at',null)->where("assign_exams.branch_id", Session::get("branch_id"))->orderBy('exam.id','ASC')
                ->get();
               
        if ($request->isMethod("post")) {
         
            $request->validate([]);
          
            $list_subject = Subject::where("class_type_id", $request->class_type_id)
                ->where("branch_id", Session::get("branch_id"));
                if(!empty($request->subject_id)){
                   $list_subject = $list_subject ->whereIn('id',$request->subject_id);
                }
                $list_subject = $list_subject->orderBy("sort_by", "ASC")
                ->get();
            $students = Admission::where("class_type_id", $request->class_type_id)
                ->where("session_id", Session::get("session_id"))
                ->where("branch_id", Session::get("branch_id"))->where('status',1)
                ->orderBy("first_name", "ASC")
                ->get();
        return view("examination.offline_exam.report.subject_wise_report_print", ["search" => $search,'examlist'=>$examlist,'students'=>$students,'list_subject'=>$list_subject]);

        }
      
        return view("examination.offline_exam.report.subject_wise_report", ["search" => $search]);
    }
    public function greenSheetReport(Request $request)
    {
        

        $search["class_type_id"] = $request->class_type_id;
               $search["exam_id"] = $request->exam_id  ?? "1";

        $students = "";
        $examlist = "";
        $list_subject = "";
        
           $examlist = AssignExam::select(
                "assign_exams.*",
                "exam.id as exam_id",
                "exam.name as exam_name"
            )
                ->leftjoin("exams as exam", "assign_exams.exam_id", "exam.id")
                ->where("assign_exams.class_type_id", $request->class_type_id)
                ->where('assign_exams.session_id',Session::get("session_id"))
                 ->where('exam.deleted_at',null)->where("assign_exams.branch_id", Session::get("branch_id"))->orderBy('exam.id','ASC')
                ->get();
              
        if ($request->isMethod("post")) {
         
            $request->validate([]);
          
            $list_subject = Subject::where("class_type_id", $request->class_type_id)
                ->where("branch_id", Session::get("branch_id"));
                if(!empty($request->subject_id)){
                   $list_subject = $list_subject ->whereIn('id',$request->subject_id);
                }
                $list_subject = $list_subject->orderBy("sort_by", "ASC")
                ->get();
            $students = Admission::where("class_type_id", $request->class_type_id)
                ->where("session_id", Session::get("session_id"))
                ->where("branch_id", Session::get("branch_id"))->where('status',1)
                ->orderBy("first_name", "ASC")
                ->get();
        return view("examination.offline_exam.report.green_sheet_report_print", ["search" => $search,'examlist'=>$examlist,'students'=>$students,'list_subject'=>$list_subject]);

        }
      
        return view("examination.offline_exam.report.green_sheet_report", ["search" => $search]);
    }
    
    
    
    
}
