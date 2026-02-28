<?php

namespace App\Http\Controllers\master;
use Illuminate\Validation\Validator; 
use App\Models\User;
use App\Models\Master\Role;
use App\Models\Master\Branch;
use Session;
use Hash;
use Str;
use Redirect;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class RoleController extends Controller

{
    

    public function add(Request $request){
        
        if($request->isMethod('post')){
                $request->validate([
             'role'  => 'required|unique:role,name',
         ]);



            if(!empty($request->sidebar_id)){
             $sidebar_id = implode(',', $request->sidebar_id);
        }
        
        
        $class = new Role;//model name
        $class->user_id = Session::get('id');
        $class->session_id = Session::get('session_id');
        $class->branch_id = Session::get('branch_id');
		$class->name =$request->role;
        $class->save();
         return response()->json(['status' => 'success','message' => 'Role add Successfully.',]);
        }    
        $data = Role::whereNull('deleted_at')->get();
        return view('master.role.add',['role'=>$data]);
    }    
    

     public function edit(Request $request,$id){
         
                $add_pr = Role::find($id);
        if($request->isMethod('post')){
            
             $request->validate([
                //  'role'  => 'required',
                'role' => 'required|unique:role,name,' . $id,
                 ]);
                 
                $add_pr->user_id =Session::get('id');
                $add_pr->session_id = Session::get('session_id');
                $add_pr->branch_id = Session::get('branch_id');
                $add_pr->name =$request->role;
                $add_pr->save();                

                return response()->json(['status' => 'success', 'message' => 'Role Edited Successfully.','redirect' => url('role_add')]);
        }
          return view('master.role.edit',['add_pr'=>$add_pr]);
     }     



     public function delete(Request $request){
       
        $id = $request->delete_id;
       
        $sss = Role::find($id)->delete();
         return redirect::to('role_add')->with('message', 'Role  Delete Successfully.');
    }
 
public function role_permission(Request $request, $role_id){
    $permissionTypes = ['add','edit','view','delete','status','print'];

    if ($request->isMethod('post')) {
        $modules = $request->modules ?? [];
        $subModules = $request->sub_modules ?? [];

        foreach ($modules as $moduleId => $permTypes) {
            $data = [
                'sidebar_name' => DB::table('sidebars')->where('id', $moduleId)->value('name'),
                'updated_at' => now(),
                'role_id' => $role_id,
                'sidebar_id' => $moduleId
            ];

            foreach ($permissionTypes as $type) {
                $data[$type] = in_array($type, $permTypes) ? 1 : 0;
            }

            // Save selected sub-modules as comma-separated
            $subSelected = $subModules[$moduleId] ?? [];
            $data['sub_sidebar_id'] = !empty($subSelected) ? implode(',', $subSelected) : null;

            DB::table('role_permissions')->updateOrInsert(
                ['role_id' => $role_id, 'sidebar_id' => $moduleId],
                $data
            );
        }

        return response()->json(['status' => 'success', 'message' => 'Role Permissions saved successfully!']);
    }
    $branch = Branch::find(Session::get('branch_id'));

$branchSidebarIds = !empty($branch->branch_sidebar_id)
    ? explode(',', $branch->branch_sidebar_id)
    : [];

$modules = DB::table('sidebars')->whereNull('deleted_at')->whereIn('id', $branchSidebarIds)->orderBy('order_by')->get();
$subs = DB::table('sidebar_sub')->whereNull('deleted_at')->whereIn('sidebar_id', $branchSidebarIds)->get()->groupBy('sidebar_id');

$rolePermissions = DB::table('role_permissions')->where('role_id', $role_id)->get()->keyBy('sidebar_id');


    return view('master.role.permissions', compact('modules','subs','rolePermissions','role_id'));
}


    
}
