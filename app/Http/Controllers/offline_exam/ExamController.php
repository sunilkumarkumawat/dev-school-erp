<?php

namespace App\Http\Controllers\offline_exam;
use Illuminate\Validation\Validator;
use App\Models\exam\Question;
use App\Models\exam\Exam;
use App\Models\exam\ExamTerm;
use App\Models\exam\AssignExam;
use Session;
use Helper;
use Str;
use Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExamController extends Controller
{
    public function viewExam(Request $request){
        $search['name'] = $request->name;
            $data = Exam::select('exams.*','class_type.name as class_name','assign_exam.class_type_id as class_id','exam_terms.name as exam_term_name')
                ->leftjoin('assign_exams as assign_exam','exams.id','assign_exam.exam_id')
                ->leftjoin('exam_terms','exam_terms.id','exams.exam_term_id')
                ->leftjoin('class_types as class_type','class_type.id','assign_exam.class_type_id')
            ->where('exams.session_id',Session::get('session_id'))
            ->where('exams.branch_id',Session::get('branch_id'));
          
        
            if($request->isMethod('post')){
                if (!empty($request->name)){
                    $data = $data->where("exams.name",'like','%'.$request->name.'%');
                }
            }
            $data = $data->groupBy('exams.id')->orderBy('id','DESC')->get();
          
          
     //    dd(Session::get('role_id'));

     
      
      
    return view('examination.offline_exam.exam.view ',['data'=>$data,'search'=>$search]);
    }
    

    
    public function addExam(Request $request){
        // dd($request);
         if($request->isMethod('post')){
                 $request->validate([
                     
         'name'  => 'required',
        //  'class_type_id'  => 'required',
        
         ]);
         $add = new Exam;//model name
	     $add->user_id = Session::get('id');
	     $add->session_id = Session::get('session_id');
         $add->branch_id = Session::get('branch_id');
		 $add->name =$request->name;
		 $add->class_type_id =$request->class_type_id;
		 $add->exam_term_id =$request->exam_term_id;
		 $add->description =$request->description;
	     $add->save();
	
		  return redirect::to('view/exam')->with('message', 'Exam added Successfully.');
        }

        return view('examination.offline_exam.exam.add');
    } 
    
     public function editExam(Request $request, $id){
         $data = Exam::find($id);
         
        
            if($request->isMethod('post')){
                $request->validate([

         'name'  => 'required',
        //  'class_type_id'  => 'required',
   
         ]);

	     $data->user_id = Session::get('id');
         $data->session_id = Session::get('session_id');
         $data->branch_id = Session::get('branch_id');	     
		 $data->name =$request->name;
		 $data->class_type_id =$request->class_type_id;
		 $data->exam_term_id =$request->exam_term_id;
	     $data->save();

            return redirect::to('view/exam')->with('message', 'Exam Updated Successfully.');
        }

        return view('examination.offline_exam.exam.edit',['data'=>$data]);
    } 
    
   public function assignExam(Request $request, $id){
        $examId = $id;
          $data2 = Exam::where('id',$id)->first();
           $AssignExam = AssignExam::select('assign_exams.*','class_types.name as class_name')
        ->leftjoin('class_types','class_types.id','assign_exams.class_type_id')->where('assign_exams.exam_id',$id)->where('assign_exams.session_id',Session::get('session_id'))->where('assign_exams.branch_id',Session::get('branch_id'))->get();
        
        
//          if($request->isMethod('post')){
//                  $request->validate([
                     
//             'class_type_id'  => 'required',
//          ]);
//          $old = AssignExam::where('class_type_id',$request->class_type_id)->where('exam_id',$examId)->first();
//          if(!empty($old)){
//           return redirect::to('assign/exam/'.$examId)->with('error', 'Class already exists !');
//          }
//          $add = new AssignExam; //model name
// 	     $add->user_id = Session::get('id');
// 	     $add->session_id = Session::get('session_id');
//          $add->branch_id = Session::get('branch_id');    
// 		 $add->class_type_id = $request->class_type_id;
// 		 $add->exam_id = $examId;
// 	     $add->save();
	     
	          
// 		 return redirect::to('assign/exam/'.$examId)->with('message', 'Exam Assigned Successfully.');
//         }


        if($request->isMethod('post')){

            $request->validate([
                'class_type_id.*' => 'required',
                'total_marks.*' => 'required|numeric',
                // 'exam_date.*' => 'required|date',
                // 'result_declaration_date.*' => 'required|date'
            ]);
        
            // UPDATE MODE
            if(!empty($request->edit_id)){
        
                $update = AssignExam::find($request->edit_id);
        
                $update->class_type_id = $request->class_type_id[0];
                $update->total_marks = $request->total_marks[0];
                $update->exam_date = $request->exam_date[0];
                $update->result_declaration_date = $request->result_declaration_date[0];
                $update->save();
        
                return redirect()->back()->with('message','Exam Updated Successfully');
            }
        
            // INSERT MODE
            foreach($request->class_type_id as $key => $classId){
        
                $exists = AssignExam::where('class_type_id',$classId)
                    ->where('exam_id',$examId)
                    ->where('session_id',Session::get('session_id'))
                    ->where('branch_id',Session::get('branch_id'))
                    ->first();
        
                if(!$exists){
        
                    $add = new AssignExam;
                    $add->user_id = Session::get('id');
                    $add->session_id = Session::get('session_id');
                    $add->branch_id = Session::get('branch_id');
                    $add->exam_id = $examId;
                    $add->class_type_id = $classId;
                    $add->total_marks = $request->total_marks[$key];
                    $add->exam_date = $request->exam_date[$key];
                    $add->result_declaration_date = $request->result_declaration_date[$key];
                    $add->save();
                }
            }
        
            return redirect()->back()->with('message','Exam Assigned Successfully');
        }


        return view('examination.offline_exam.exam.assign',['AssignExam'=>$AssignExam,'data'=>$data2]);
    } 
    

    
     public function deleteAssignExam(Request $request){
        $question = AssignExam::find($request->assign_id)->delete();
        return redirect::to('assign/exam/'.$request->exam_id)->with('message', 'Class Unassigned Successfully.');
    }
    
    
    public function viewExamTerm(Request $request){
        $search['name'] = $request->name;
            $data = ExamTerm::select('exam_terms.*')
            ->where('exam_terms.session_id',Session::get('session_id'))
            ->where('exam_terms.branch_id',Session::get('branch_id'));

            if($request->isMethod('post')){
                if (!empty($request->name)){
                    $data = $data->where("exam_terms.name",'like','%'.$request->name.'%');
                }
            }
            $data = $data->groupBy('exam_terms.id')->orderBy('id','DESC')->get();
          
          
     //    dd(Session::get('role_id'));

     
      
      
    return view('examination.offline_exam.exam_term.view ',['data'=>$data,'search'=>$search]);
    }
    
    
    public function addExamTerm(Request $request){
         if($request->isMethod('post')){
                 $request->validate([
                     
         'name'  => 'required',
        //  'class_type_id'  => 'required',
        
         ]);
         $add = new ExamTerm;//model name
	     $add->user_id = Session::get('id');
	     $add->session_id = Session::get('session_id');
         $add->branch_id = Session::get('branch_id');
		 $add->name =$request->name;
	     $add->save();
	
		  return redirect::to('view/exam_term')->with('message', 'Exam Term added Successfully.');
        }

        return view('examination.offline_exam.exam_term.add');
    } 
    
     public function editExamTerm(Request $request, $id){
         $data = ExamTerm::find($id);
            if($request->isMethod('post')){
                $request->validate([

         'name'  => 'required',
        //  'class_type_id'  => 'required',
   
         ]);

	     $data->user_id = Session::get('id');
         $data->session_id = Session::get('session_id');
         $data->branch_id = Session::get('branch_id');	     
		 $data->name =$request->name;
	     $data->save();

            return redirect::to('view/exam_term')->with('message', 'Exam Term Updated Successfully.');
        }

        return view('examination.offline_exam.exam_term.edit',['data'=>$data]);
    } 
    
    public function deleteExamTerm(Request $request)
{
    $examTerm = ExamTerm::find($request->id);

    if (!$examTerm) {
        return Redirect::to('view/exam_term')->with('error', 'Exam Term not found.');
    }

    $examTerm->delete();

    return Redirect::to('view/exam_term')->with('message', 'Exam Term Deleted Successfully.');
}
    
}