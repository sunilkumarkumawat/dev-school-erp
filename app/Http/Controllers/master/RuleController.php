<?php

namespace App\Http\Controllers\master;
use Illuminate\Validation\Validator; 
use App\Models\User;
use App\Models\Master\Rule;
use App\Models\Master\SchoolDesk;
use Session;
use Hash;
use Str;
use Redirect;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RuleController extends Controller

{
            public function add(Request $request){
                $data = Rule::select('rules.*','role.name as role_name')
                ->leftjoin('role','role.id','rules.role_id')->get();
                if($request->isMethod('post')){
                    $request->validate([
                        'name'  => 'required',
                        'role_id'  => 'required',
                    ]);
                    $holiday = new Rule;//model name
                    $holiday->name =$request->name;
                    $holiday->role_id =$request->role_id;
                    $holiday->description =$request->description;
                    $holiday->save();
                    return response()->json(['status' => 'success','message' => 'Rule add Successfully.',]);
                }  
                return view('master.Rule.add',['data'=>$data]);
            } 
 
            public function edit(Request $request,$id){
                $data = Rule::find($id);
                if($request->isMethod('post')){
                    $request->validate([
                        'name'  => 'required',
                        'role_id'  => 'required',
                    ]);
                    $data->name =$request->name;
                    $data->role_id =$request->role_id;
                    $data->description =$request->description;
                    $data->save();
                     return response()->json(['status' => 'success','message' => 'Rule Update  Successfully.',]);
                }
                return view('master.Rule.edit',['data'=>$data]);
            }
     
            public function delete(Request $request){
                $id = $request->delete_id;
                $sss = Rule::find($id)->delete();
                return redirect::to('rules_add')->with('message', 'Rule  Delete Successfully.');
            }
     
            public function schoolDeskEdit(Request $request){
                $old = SchoolDesk::first();
                if($request->isMethod('post')){
                    $request->validate([
                        'description' => 'required',
                       
                    ]);
                    if(empty($old)){
                        $data = new SchoolDesk;
                    }
                    else{
                        $data = SchoolDesk::find(1);
                    }
                    $data->user_id = Session::get('id');
                    $data->session_id = Session::get('session_id');
                    $data->branch_id = Session::get('branch_id');
                    $data->description = $request->description;
                    $data->save();
                    return response()->json(['status' => 'success', 'message' => 'School  Desk add Successfully.','redirect' => url('school_desk')]);
                    
                }
                return view('master.schoolDesk.school_desk',['data'=>$old]);
            } 
        
            public function schoolDeskView(Request $request){
                $data = SchoolDesk::where('id',1)->first();
                return view('master.schoolDesk.school_desk_view', ['data' => $data]);
            }
    
}
