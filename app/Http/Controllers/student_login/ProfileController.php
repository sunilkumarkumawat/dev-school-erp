<?php

namespace App\Http\Controllers\student_login;
use App\Models\User;
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
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
class ProfileController extends Controller

{
   public function profileEdit(Request $request)
{
    $id = Session::get('id');
    $data = Admission::find($id);

    // =======================
    // DELETE PHOTO (AJAX)
    // =======================
    if ($request->delete_photo == true) {

        if ($data->image && file_exists(env('IMAGE_UPLOAD_PATH') . 'profile/' . $data->image)) {
            unlink(env('IMAGE_UPLOAD_PATH') . 'profile/' . $data->image);
        }

        $data->image = null;
        $data->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Photo Removed Successfully.'
        ]);
    }

    // =======================
    // UPDATE OR UPLOAD PHOTO
    // =======================
    if ($request->file('photo')) {

        // delete old image if exists
        if ($data->image && file_exists(env('IMAGE_UPLOAD_PATH') . 'profile/' . $data->image)) {
            unlink(env('IMAGE_UPLOAD_PATH') . 'profile/' . $data->image);
        }

        $image = $request->file('photo');
        $student_image = time() . uniqid() . $image->getClientOriginalName();
        $destinationPath = env('IMAGE_UPLOAD_PATH') . 'profile/';
        $image->move($destinationPath, $student_image);

        $data->image = $student_image;
        $data->save();

        return response()->json([
            'status'     => 'success',
            'message'    => 'Photo uploaded successfully.',
            'image_url'  => env('IMAGE_SHOW_PATH') . '/profile/' . $student_image
        ]);
    }

    // =======================
    // UPDATE TEXT INFO
    // =======================
    if ($request->isMethod('post')) {

        $data->first_name     = $request->first_name ?? $data->first_name;
        $data->last_name      = $request->last_name ?? $data->last_name;
        $data->email          = $request->email ?? $data->email;
        $data->mobile         = $request->mobile ?? $data->mobile;

        $data->aadhaar        = $request->aadhaar ?? $data->aadhaar;
        $data->dob            = $request->dob ?? $data->dob;

        $data->father_name    = $request->father_name ?? $data->father_name;
        $data->father_mobile  = $request->father_mobile ?? $data->father_mobile;
        $data->mother_name    = $request->mother_name ?? $data->mother_name;
        $data->mother_mob     = $request->mother_mob ?? $data->mother_mob;

        $data->address        = $request->address ?? $data->address;

        $data->save();
         Session::put('first_name', $data->first_name);
        return response()->json([
            'status'  => 'success',
            'message' => 'Profile Updated Successfully.'
        ]);
    }

    return view('student_login.profile', ["data" => $data]);
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















