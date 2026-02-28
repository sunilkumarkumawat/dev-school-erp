<?php

namespace App\Http\Controllers\master;
use Illuminate\Validation\Validator; 
use App\Models\User;
use App\Models\TeacherCategory;
use App\Models\Subject;
use App\Models\Master\TimePeriods;
use App\Models\Master\AllSubjects;
use Session;
use Hash;
use Str;
use DB;
use Redirect;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller

{
    

            public function add(Request $request){
                $search['class_type_filter'] = '';
                $search['class_type_id'] = '';
                $data = Subject::where('class_type_id',$request->class_type_id)->where('session_id',Session::get('session_id'))->get();
                //dd($request);
                if($request->isMethod('post')){
                     $request->validate([
                            
                            'class_type_id'  => 'required',
                        ]);
                       

                    $old_arr = [];
                    // foreach($request->add_subject as $key => $item)
                    // {
                    //     $dta= Subject::where('class_type_id',$request->class_type_id)->where('id',$item)->first();
                   
                    //     if(empty($dta))
                    //     {
                    //         $old_arr[] = $item;
                    //     }
                    // }
                    //dd($old_arr);
                    $data = Subject::whereNotIn('id',$request->add_subject)->where('class_type_id',$request->class_type_id)->where('session_id',Session::get('session_id'))->delete();
                    if(!empty($request->add_subject)){
                        foreach($request->add_subject as $key => $item){
                         	$string = $item;
                         	$ucfirstString = ucwords(strtolower($string), '\',. ');
                         	$ucfirstString =	trim($ucfirstString);
                         	$other_subject = AllSubjects::where('name',$ucfirstString)->first();
                            $users = DB::table('subject')->where('name',$item)->where('class_type_id',$request->class_type_id)->where('session_id',Session::get('session_id'))->first();
                            if(!empty($users)){
                                if($users == null){
                                    //   $users = DB::table('subject')->where('name',$item)->where('class_type_id',$request->class_type_id)->update(['deleted_at'=>null]);
                                }
                                else{
                                    $subject = DB::table('subject')->where('name',$item)->where('class_type_id',$request->class_type_id)->where('session_id',Session::get('session_id'))->update(['deleted_at'=>null,'other_subject'=>$other_subject->other_subject]);
                                }
                            }
                            else{
                                $subject = new Subject;//model name
                                $subject->user_id = Session::get('id');
                                $subject->session_id = Session::get('session_id');
                                $subject->branch_id = Session::get('branch_id');
                        		$subject->name = $item;
                        		$subject->other_subject = $other_subject->other_subject;
                        		$subject->sort_by =$key+1;
                        		$subject->class_type_id =$request->class_type_id;
                                $subject->save();
                            }
                        }
                        return response()->json(['status' => 'success','message' => 'Subject Assigned Successfully.',]);
                    }
                    else{
                        return redirect::to('add_subject')->with('error', 'Something Went Wrong');
                    }
                }
                return view('master.subject.add_subject',['section'=>$data,'search'=>$search]);
            }   
        
            public function selectClass(Request $request){
                $search['class_type_id'] = $request->class_type_id;
                $search['class_type_filter '] = '';
                if($request->isMethod('post')){
                    $data = Subject::where('class_type_id',$request->class_type_id)->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->orderBy('id', 'DESC')->get();
                    return view('master.subject.add_subject',['section'=>$data,'search'=>$search]);
                }
            }    
            
            public function edit(Request $request,$id){
                $data = Subject::find($id);
                    if($request->isMethod('post')){
                        // $request->validate([
                        //     'subject'  => 'required',
                        //     'class_type_id'  => 'required',
                        // ]);  
                        $request->validate([
                            'subject' => 'required',
                            'class_type_id' => 'required|unique:class_types,class_type_id,'.$id.',id,branch_id,'.Session::get('branch_id').',session_id,'.Session::get('session_id'),
                        ]);

                        $check = Subject::where('id', '<>', $id)->where('class_type_id',$request->class_type_id)->where('name',$request->subject)->where('branch_id',Session::get('branch_id'))->where('session_id',Session::get('session_id'))->get();
                        if(count($check) == 0){
                            $data->session_id = Session::get('session_id');
                            $data->branch_id = Session::get('branch_id');
                            $data->class_type_id =$request->class_type_id;
                            $data->name =$request->subject;
                            $data->save();
                        }
                        else{
                        return redirect::to('add_subject')->with('error', 'This subject already Add for this class ');
                    }
                    return redirect::to('add_subject')->with('message', 'Subject Edited Successfully.');
                }
                return view('master.subject.edit_subject',['data'=>$data]);
            }  
   
            public function createSubjects(Request $request){
                $data = AllSubjects::where('branch_id',Session::get('branch_id'))->orderBy('name','ASC')->where('session_id',Session::get('session_id'))->get();
                    if($request->isMethod('post')){
                         $request->validate([
                        'add_subject'  => 'required',
                    ]);
          	        $string = $request->add_subject;
	                $ucfirstString = ucwords(strtolower($string), '\',. ');
                    $ucfirstString =	trim($ucfirstString);
	                $olddata = AllSubjects:: where('name',$ucfirstString)->where('session_id',Session::get('session_id'))->get();
	                if(count($olddata) > 0){
	                  return response()->json(['error' => 'errors','message' => 'Already added.'], 400); // 400 is Bad Request

	                }
	                else{
	                    $subject = new AllSubjects;//model name
                        $subject->user_id = Session::get('id');
                        $subject->session_id = Session::get('session_id');
                        $subject->branch_id = Session::get('branch_id');
                		$subject->name =$ucfirstString;
                		$subject->other_subject =$request->other_subject ?? 0;
                        $subject->save();
                        return response()->json(['status' => 'success','message' => 'Subject Created Successfully.','print_url' => url('/create_subject/')]);
	                }
                }
                return view('master.subject.create_subject',['data'=>$data,]);
            }
    
            public function deleteCreateSubject(Request $request){
                $id = $request->delete_id;
                $delete = AllSubjects::find($id)->delete();
                return redirect::to('create_subject')->with('message', 'Subject  Deleted Successfully.');
            }
 
            public function delete(Request $request){
                $id = $request->delete_id;
                $sss = Subject::find($id)->delete();
                return redirect::to('add_subject')->with('message', 'Subject  Deleted Successfully.');
            }
    
            public function deletePeriods(Request $request){
                $id = $request->delete_id;
                $sss = TimePeriods::find($id)->delete();
                return redirect::to('time_periods')->with('message', 'Period  Deleted Successfully.');
            }
            
            public function timePeriods(Request $request){
                if($request->isMethod('post')){
                    $request->validate([
                        'from_time'  => 'required',
                        'to_time'  => 'required',
                        'period_name'  => 'required',
                    ]);
                    $data = new TimePeriods;//model name
                    $data->user_id = Session::get('id');
                    $data->session_id = Session::get('session_id');
                    $data->branch_id = Session::get('branch_id');
            		$data->from_time =$request->from_time;
            		$data->to_time =$request->to_time;
            		$data->period_name =$request->period_name;
                    $data->save();
                    
                   return response()->json(['status' => 'success','message' => 'Period added Successfully.',]);
                }
                $data = TimePeriods::where('branch_id',Session::get('branch_id'))->where('session_id',Session::get('session_id'))->orderBy('id', 'ASC')->get();
                return view('master.time_table.add',['data'=>$data]);
            }
    
            public function editTimePeriods(Request $request,$id){
                $data = TimePeriods::find($id);
                if($request->isMethod('post')){
                    $request->validate([
                        'from_time'  => 'required',
                        'to_time'  => 'required',
                        'period_name'  => 'required',
                    ]);  
                    $data->user_id = Session::get('id');
                    $data->session_id = Session::get('session_id');
                    $data->branch_id = Session::get('branch_id');
            		$data->from_time =$request->from_time;
            		$data->to_time =$request->to_time;
            		$data->period_name =$request->period_name;
                    $data->save();
                    return response()->json(['status' => 'success', 'message' => 'Period Updated Successfully.','redirect' => url('time_periods')]);
                }
              return view('master.time_table.edit',['data'=>$data]);
            }  
     
            public function subjectOrderBy(Request $request){
                if($request->isMethod('post')){
                    $request->validate([
                    ]);  
                    if(!empty($request->subject_id)){
                        foreach($request->subject_id as $key => $item){
                            $data = Subject::find($request->subject_id[$key]);
                            $data->sort_by = $request->sort_by[$key];
                            $data->save();
                        }
                    }
                        return response()->json(['status' => 'success','message' => ' Updated Successfully.',]);
                }
            }
     
           
            public function multiEditSubjects(Request $request){
                if($request->isMethod('post')){
                    if(!empty($request->add_subject)){
                        foreach($request->id as $key=>$item){
                         	$string = $request->add_subject[$key];
                        	$ucfirstString = ucwords(strtolower($string), '\',. ');
                            $ucfirstString =	trim($ucfirstString);
	                        $string2 ='other_subject_'.$item;
                            $data = AllSubjects::find($item);
                    		$data->name =$ucfirstString;
                    		$data->other_subject =$request->$string2;
                            $data->save();
                        } 
                        return response()->json(['status' => 'success','message' => 'Subject Updated Successfully.',]);
                    }
                    else{
                        return redirect::to('create_subject')->with('error', 'Something went wrong ');
                    }
                }
            }  
            
        public function saveSelectedSubjects(Request $request)
                    {
                        $Allsubjects_id = $request->input('Allsubjects_id', []);
                        $sub_names = $request->input('subjects', []);
                    
                        if (!empty($Allsubjects_id)) {
                            foreach ($Allsubjects_id as $Sub_id) {
                                $sub_name = $sub_names[$Sub_id] ?? '';
                                 $subject = new AllSubjects;//model name
                        $subject->user_id = Session::get('id');
                        $subject->session_id = Session::get('session_id');
                        $subject->branch_id = Session::get('branch_id');
                		$subject->name =$sub_name;
                		$subject->other_subject =$request->other_subject ?? 0;
                        $subject->save();
                                $subject->save();
                            }
                        }
                    
                        return back()->with('success', 'Selected classes saved successfully.');
                    }

}
