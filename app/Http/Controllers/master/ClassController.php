<?php

namespace App\Http\Controllers\master;
use Illuminate\Validation\Validator; 
use App\Models\User;
use App\Models\Admin;
use App\Models\ClassType;
use Session;
use Hash;
use Str;
use Redirect;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class ClassController extends Controller

{

           public function add(Request $request){
                if($request->isMethod('post')){
            
                    $request->validate(
                    [
                        'name' => 'required|unique:class_types,name,NULL,id,branch_id,'.Session::get('branch_id').',session_id,'.Session::get('session_id'),
                    ],
                    [
                        'name.required' => 'Class name is required',
                        'name.unique'   => 'This class already exists',
                    ]);
            
                    $orderBy = 0;
            
                    $arrayOfStrings = [
                        'play','kg','nursery','lkg','ukg','prep',
                        'first','1','second','2','third','3','fourth','4',
                        'fifth','5','sixth','6','seventh','7','eighth','8',
                        'ninth','9','tenth','10','eleventh','11','twelfth','12',
                    ];
            
                    $number = ['0','0','0','0','0','0','1','1','2','2','3','3','4','4',
                               '5','5','6','6','7','7','8','8','9','9','10','10','11','11','12','12'];
            
                    $stringToCheck = strtolower($request->name);
            
                    foreach ($arrayOfStrings as $key => $string) {
                        if (strpos($stringToCheck, $string) !== false) {
                            $orderBy = $number[$key];
                        }
                    }
            
                    $class = new ClassType;
                    $class->user_id    = Session::get('id');
                    $class->session_id = Session::get('session_id');
                    $class->branch_id  = Session::get('branch_id');
                    $class->name       = $request->name;
                    $class->orderBy    = $orderBy;
                    $class->save();
            
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'Student Class added Successfully.',
                    ]);
                }
            
                $alladd_type = ClassType::where('branch_id',Session('branch_id'))
                                ->where('session_id',Session('session_id'))
                                ->orderBy('orderBy','ASC')
                                ->get();
            
                return view('master.class.add',['data'=>$alladd_type]);
            }

    
            public function edit(Request $request, $id){

                $data = ClassType::findOrFail($id);
            
                if($request->isMethod('post')){
            
                    $request->validate(
                    [
                        'name' => 'required|unique:class_types,name,'.$id.',id,branch_id,'.Session::get('branch_id').',session_id,'.Session::get('session_id'),
                    ],
                    [
                        'name.required' => 'Class name is required',
                        'name.unique'   => 'This class already exists',
                    ]);
            
                    $data->session_id = Session::get('session_id');
                    $data->branch_id  = Session::get('branch_id');
                    $data->name       = $request->name;
                    $data->save();
            
                    return response()->json([
                        'status'   => 'success',
                        'message'  => 'Student Class Updated Successfully.',
                        'redirect' => url('add_class')
                    ]);
                }
            
                return view('master.class.edit', ['data' => $data]);
            }

            public function delete(Request $request){
                $id = $request->delete_id;
                $sss = ClassType::find($id)->delete();
                return redirect::to('add_class')->with('message', 'Class  Delete Successfully.');
            }
            
            
            public function saveSelectedClasses(Request $request)
                    {
                        $class_ids = $request->input('class_id', []);
                        $class_names = $request->input('class', []);
                    
                        if (!empty($class_ids)) {
                            foreach ($class_ids as $class_id) {
                                $class_name = $class_names[$class_id] ?? '';
                                $orderBy = $this->getClassOrder($class_name);
                    
                                $class = new ClassType;
                                $class->user_id = Session::get('id');
                                $class->session_id = Session::get('session_id');
                                $class->branch_id = Session::get('branch_id');
                                $class->name = $class_name;
                                $class->orderBy = $orderBy;
                                $class->save();
                            }
                        }
                    
                        return back()->with('success', 'Selected classes saved successfully.');
                    }
     
                function getClassOrder($class_name) {
                                    $class_name = strtolower(trim($class_name));
                                
                                    $map = [
                                        'play' => 0, 'kg' => 0, 'nursery' => 0, 'lkg' => 0, 'ukg' => 0, 'prep' => 0,
                                        'first' => 1, '1st' => 1, 'one' => 1,
                                        'second' => 2, '2nd' => 2, 'two' => 2,
                                        'third' => 3, '3rd' => 3, 'three' => 3,
                                        'fourth' => 4, '4th' => 4, 'four' => 4,
                                        'fifth' => 5, '5th' => 5, 'five' => 5,
                                        'sixth' => 6, '6th' => 6, 'six' => 6,
                                        'seventh' => 7, '7th' => 7, 'seven' => 7,
                                        'eighth' => 8, '8th' => 8, 'eight' => 8,
                                        'ninth' => 9, '9th' => 9, 'nine' => 9,
                                        'tenth' => 10, '10th' => 10, 'ten' => 10,
                                        'eleventh' => 11, '11th' => 11, 'eleven' => 11,
                                        'twelfth' => 12, '12th' => 12, 'twelve' => 12,
                                    ];
                                
                                    foreach ($map as $key => $val) {
                                        if (strpos($class_name, $key) !== false) {
                                            return $val;
                                        }
                                    }
                                
                                    if (preg_match('/\b(\d{1,2})\b/', $class_name, $match)) {
                                        return (int) $match[1];
                                    }
                                
                                    return 0;
                                }


}
