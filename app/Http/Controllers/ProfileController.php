<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Validator; 
use App\Models\User;
use App\Models\Profile;
use App\Models\Admission;
use App\Models\StudentDocument;
use Helper;
use Session;
use Hash;
use Str;
use Redirect;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
class ProfileController extends Controller

{
    public function profileEdit(Request $request,$id){
    
          $data = User::find($id);
      
        
            if($request->isMethod('post')){
                $request->validate([
                    'first_name' => 'required',
                    'mobile' => 'required',
                   // 'email' => 'required',
                    'dob' => 'required',
                    'father_name' => 'required',
                ]);
                
          
              
          
                    if ($request->file('photo')) {
                            $image = $request->file('photo');
                             $ext = $image->getClientOriginalExtension(); // jpg, png, jpeg आदि
                             $photo = uniqid(). '.' . $ext;
                            $destinationPath = env('IMAGE_UPLOAD_PATH') . 'profile/';
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0755, true);
                            }
                            if (isset($data->image) && File::exists($destinationPath . $data->image)) {
                                File::delete($destinationPath . $data->image);
                            }
                                $compressedImage = Image::make($image)
                                    ->resize(600, null, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })
                            ->encode('jpg', 80); // Adjust quality as needed
                            $compressedImage->save($destinationPath . $photo);
                            $data->image = $photo;
                            session()->put('photo',$photo);         
                        }
                        
                        
                           

           
            $data->first_name =$request->first_name;
            $data->dob= $request->dob;
            $data->email =$request->email;
            $data->mobile =$request->mobile;
            $data->father_name =$request->father_name;
            $data->mother_name =$request->mother_name;
            $data->father_mobile = $request->father_mobile;
            $data->city_id= $request->city_id;
        	$data->country_id= $request->country_id;
        	$data->state_id= $request->state_id;
    		$data->pincode= $request->pincode;
    		$data->address  = $request->address;
    		$data->userName  = $request->userName;
            $data->save();

            session()->put('first_name',$request->first_name);
            session()->put('email',$request->email);
            session()->put('userName',$request->userName);
            session()->put('father_name',$request->father_name);
            session()->put('mobile',$request->mobile);
            session()->put('country_id',$request->countries_id);
            session()->put('state_id',$request->state_id);
            session()->put('city_id',$request->city_id);
            
            return redirect::to('/')->with('message', 'Profile Updated Successfully.');
		
        }
   
            return view('profile.profile',["data"=>$data]);
        
    }
    
    public function document_upload(Request $request, $id)
        {
            $data = Admission::find($id);
        
            if (!$data) {
                return redirect()->back()->with('error', 'Student not found.');
            }
        
            // Validate request
            $request->validate([
              
            ]);
        
            // Handle file upload
            if ($request->hasFile('file')) {
                
                 $file = '';
                    if ($request->file('file')) {
                        $image = $request->file('file');
                        $file = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                        $destinationPath = env('IMAGE_UPLOAD_PATH') . 'student_document/';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    if (isset($data->file) && File::exists($destinationPath . $data->file)) {
                        File::delete($destinationPath . $data->file);
                    }
                    $compressedImage = Image::make($image)
                        ->resize(600, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->encode('jpg', 80); // Adjust quality as needed
                        $compressedImage->save($destinationPath . $file);
                      
                    }
        
                // Create a new document record
                $document = new StudentDocument();
                $document->admission_id = $id;
                $document->title = $request->title;
                $document->file = $file;
                $document->remark = $request->remark;
                $document->save();
            }
        
            return redirect()->back()->with('message', 'Document uploaded successfully.');
        }



}















