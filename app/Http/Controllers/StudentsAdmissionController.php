<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Validator;
use App\Models\User;
use App\Models\Enquiry;
use App\Models\Admission;
use App\Models\RollNumber;
use App\Models\StudentId;
use App\Models\StudentAction;
use App\Models\exam\FillMarks;
use App\Models\Classs;
use App\Models\ClassType;
use App\Models\Subject;
use App\Models\Sessions;
use App\Models\Master\Branch;
use App\Models\TcCertificate;
use App\Models\BillCounter;
use App\Models\SmsSetting;
use App\Models\BloodGroup;
use App\Models\DatatableFields;
use App\Models\FeesMaster;
use App\Models\FeesCollect;
use App\Models\WhatsappSetting;
use App\Models\FeesStructure;
use App\Models\FeesDetail;
use App\Models\StudentDocument;
use App\Models\Setting;
use App\Models\State;
use App\Models\Gender;
use App\Models\Master\MessageTemplate;
use App\Models\Master\MessageType;
use App\Models\City;
use App\Models\fees\FeesAssign;
use App\Models\fees\FeesAssignDetail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Models\fees\FeesDetailsInvoices;
use App\Models\StudentField;
use Session;
use Hash;
use PDF;
use Helper;
use Str;
use Mail;
use File;
use DB;
use Redirect;
use Auth;
use App\Imports\YourImportClassName;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Schema;


class StudentsAdmissionController extends Controller
{
           /*public function unique_system_id(){
                $data = Admission::whereNull('unique_system_id')->get();
                
                if(!empty($data)){ 
                    foreach($data as $item){
                        $find = Admission::find($item->id);
                        $uniqueId = strtoupper(Str::random(10));
                        $find->unique_system_id = $uniqueId;
                        $find->save();
                    }
                }
            }*/
    
            protected function unique_system_id($id){
                $uniqueId = strtoupper(Str::random(10));
                Admission::where('id',$id)->whereNull('unique_system_id')->update(['unique_system_id' => $uniqueId]);
            }
    
            protected function convertExcelDate($date){
                if(is_numeric($date)) {
                        return Carbon::createFromFormat('Y-m-d', '1899-12-30')->addDays($date);
                    }elseif (is_string($date)) {
                        try{
                            return Carbon::createFromFormat('Y-m-d', $date);
                        } catch (\Exception $e) {
                            try{
                                return Carbon::createFromFormat('d-m-Y', $date);
                            }catch (\Exception $e) {
                                try {
                                    return Carbon::createFromFormat('m-d-Y', $date);
                                }catch (\Exception $e) {
                                        return null;
                                }
                            }
                        }
                    }
                     return null;
            }
            public function saveAdmissionDatatableFields(Request $request){
                $data = DatatableFields::find(1);
                    if(!empty($data)){
                    $data->fields= implode(',', $request->fields);
                    $data->save();
                    }else
                        {
                            $data = new DatatableFields;
                            $data->fields = implode(',', $request->fields);
                            $data->save();
                        }
                return redirect::to('admissionView')->with('message','Datatable Fields Selected Successfully');
            }
    
            public function admissionStudentPrint(Request $request, $id){
                $student_admission = Admission::select('admissions.*', 'sessions.from_year', 'class_types.name as class_name','sessions.to_year', 'gender.name as genderName','countries.name as country_name','states.name as state_name','citys.name as city_name')
                ->leftJoin('gender','gender.id','admissions.gender_id')
                ->leftjoin('countries','countries.id','admissions.country_id')
                ->leftjoin('states','states.id','admissions.state_id')
                ->leftjoin('citys','citys.id','admissions.city_id')
                ->leftjoin('sessions','sessions.id','admissions.session_id')
                ->leftjoin('class_types','class_types.id','admissions.class_type_id')
                ->where('admissions.id',$id)->first();
                $printPreview = Helper::printPreview('Admission Print');
              
                return view($printPreview, ['data' => $student_admission]);
               // return view('print_file.student_print.admissionStudentPrint', ['data' => $student_admission]);
            }
    
            public function getStreamSubjects(Request $request){
                $data = Subject::where('class_type_id',$request->class_type_id)->get();
                return $data;
            }

            public function admissionAdd(Request $request){
            
               if (Helper::classType()->isEmpty()) {
    return redirect('add_class')
        ->with('error', 'Please add class first!');
}


                
                $BillCounter = BillCounter::where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('type', 'Admission')->get()->first();
                    if (!empty($BillCounter)) {
                        $counter = !empty($BillCounter->counter) ? $BillCounter->counter : 0;
                        $BillCounterNo = $counter + 1;
                    }
                    
                    if ($request->isMethod('post')) {
                         // fresh query हर बार
                        $student_fields_required = DB::table('student_fields')
                            ->whereNull('deleted_at')
                            ->get(['field_name', 'status', 'required'])
                            ->keyBy('field_name'); // toArray() न करें
                    
                        $rules = [];
                    
                        foreach ($student_fields_required as $field => $obj) {
                            if ($obj->status == 0 && $obj->required == 0) {
                                if ($field === 'mobile' || $field === 'father_mobile') {
                                    $rules[$field] = 'required|digits:10';
                                } else {
                                    $rules[$field] = 'required';
                                }
                            }
                        }
                        $request->validate($rules);
                        $student_image = '';
                    if ($request->file('student_img')) {
                        $image = $request->file('student_img');
                        $ext = $image->getClientOriginalExtension(); // jpg, png, jpeg आदि
                        $student_image = ($request->admissionNo ??  uniqid()). '.' . $ext;
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
                        $compressedImage->save($destinationPath . $student_image);
                        
                    }
                   
                    $father_image = '';
                    if ($request->file('father_img')) {
                        $image = $request->file('father_img');
                        $father_image = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                        $destinationPath = env('IMAGE_UPLOAD_PATH') . 'father_image/';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    if (isset($data->father_image) && File::exists($destinationPath . $data->father_image)) {
                        File::delete($destinationPath . $data->father_image);
                    }
                    $compressedImage = Image::make($image)
                        ->resize(600, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->encode('jpg', 80); // Adjust quality as needed
                        $compressedImage->save($destinationPath . $father_image);
                        
                    }
                  
                    $mother_image = '';
                    if ($request->file('mother_img')) {
                        $image = $request->file('mother_img');
                        $mother_image = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                        $destinationPath = env('IMAGE_UPLOAD_PATH') . 'mother_image/';
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    if (isset($data->mother_img) && File::exists($destinationPath . $data->mother_img)) {
                        File::delete($destinationPath . $data->mother_img);
                    }
                    $compressedImage = Image::make($image)
                        ->resize(600, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->encode('jpg', 80); // Adjust quality as needed
                        $compressedImage->save($destinationPath . $mother_image);
                       
                    }
                   
                        $counter = !empty($BillCounter->counter) ? $BillCounter->counter : 0;
                        $BillCounter->counter = $counter + 1;
                        $BillCounter->save();
                        $addadmission = new Admission(); //model name
                        $addadmission->user_id = Session::get('id');
                        $addadmission->session_id = Session::get('session_id');
                        $addadmission->branch_id = Session::get('branch_id');
                        
                        
                        $addadmission->admissionNo = $request->admissionNo;
                        $addadmission->ledger_no = $request->ledger_no;
                        $addadmission->student_pen = $request->student_pen;
                        $addadmission->apaar_id = $request->apaar_id;
                        $addadmission->school = '1';
                        $addadmission->library = '0';
                        $addadmission->hostel = '0';
                        $addadmission->roll_no = $request->roll_no;
                        $addadmission->admission_date = $request->admission_date;
                        $addadmission->admission_type_id = $request->admission_type_id;
                        $addadmission->class_type_id = $request->class_type_id;
                            if(!empty($request->stream_subject)){
                                $addadmission->stream_subject = implode(',', $request->stream_subject);
                            }
                            $addadmission->attendance_unique_id = str_pad((Admission::max('attendance_unique_id') + 1), 4, '0', STR_PAD_LEFT);
                            $addadmission->first_name = $request->first_name;
                            $addadmission->last_name = $request->last_name;
                            $addadmission->aadhaar = $request->aadhaar;
                            $addadmission->jan_aadhaar = $request->jan_aadhaar;
                            $addadmission->previous_school = $request->previous_school;
                            $addadmission->email = $request->email;
                            $addadmission->mobile = $request->mobile;
                            $addadmission->father_name = $request->father_name;
                            $addadmission->mother_name = $request->mother_name;
                            $addadmission->father_mobile = $request->father_mobile;
                            $addadmission->dob = $request->dob;
                            $addadmission->relation_student = $request->relation_student;
                            $addadmission->school_namestudied_last_year = $request->school_namestudied_last_year;
                            $addadmission->house = $request->house;
                            $addadmission->height = $request->height;
                            $addadmission->weight = $request->weight;
                            $addadmission->gender_id = $request->gender_id;
                            $addadmission->admission_type_id = $request->admission_type_id;
                            $addadmission->blood_group = $request->blood_group;
                            $addadmission->medium = $request->medium;
                            $addadmission->address = $request->address;
                            $addadmission->country_id = $request->country;
                            $addadmission->village_city = $request->village_city;
                            $addadmission->city_id = $request->city;
                            $addadmission->state_id = $request->state;
                            $addadmission->pincode = $request->pincode;
                            $addadmission->family_id = $request->family_id;
                            $addadmission->religion = $request->religion;
                            $addadmission->category = $request->category;
                            $addadmission->caste_category = $request->caste_category;
                            $addadmission->transport = $request->transport;
                            $addadmission->bus_number = $request->bus_number;
                            $addadmission->bus_route = $request->bus_route;
                            $addadmission->stoppage = $request->stoppage;
                            $addadmission->transpor_charges = $request->transpor_charges;
                            $addadmission->guardian_name = $request->guardian_name;
                            $addadmission->guardian_mobile = $request->guardian_mobile;
                            $addadmission->mother_mob = $request->mother_mob;
                            $addadmission->father_aadhaar = $request->father_aadhaar;
                            $addadmission->mother_aadhaar = $request->mother_aadhaar;
                            $addadmission->family_annual_income = $request->family_annual_income;
                            $addadmission->bank_account = $request->bank_account;
                            $addadmission->bank_name = $request->bank_name;
                            $addadmission->branch_name = $request->branch_name;
                            $addadmission->ifsc = $request->ifsc;
                            $addadmission->micr_code = $request->micr_code;
                            $addadmission->image = $student_image;
                            $addadmission->father_img = $father_image;
                            $addadmission->mother_img = $mother_image;
                            $addadmission->remark_1 = $request->remark_1;
                            $addadmission->bank_account_holder = $request->bank_account_holder;
                            $addadmission->district = $request->district;
                            $addadmission->tehsil = $request->tehsil;
                            $addadmission->father_pancard = $request->father_pancard;
                            $addadmission->mother_pancard = $request->mother_pancard;
                            $addadmission->bpl = $request->bpl;
                            $addadmission->bpl_certificate_no = $request->bpl_certificate_no;
                            $addadmission->father_occupation = $request->father_occupation;
                            $addadmission->mother_occupation = $request->mother_occupation;
                            $addadmission->password = Hash::make($request->admissionNo);
                            $addadmission->confirm_password = $request->admissionNo;
                            $status = 1;
                            if(($request->newStudentRegistration ?? '') == 'newStudentRegistration'){
                                $status = 'newStudentRegistration';
                            }
                            $addadmission->status = $status;
                            
                            $class_name = ClassType::find($request->class_type_id);
                            $initials = substr($request->first_name, 0, 3);
                            $birthYear = date('Y', strtotime($request->dob));
                            $random_number = Str::random(10);
                            $cleanedMobile = preg_replace('/[^0-9]/', '', $request->mobile ?? $random_number);
                            $username = strtoupper($initials).strtoupper($class_name->name).substr($cleanedMobile, -3);
                            $addadmission->userName = $request->admissionNo;
                            
                            $studentFields = StudentField::where('branch_id', Session::get('branch_id'))->where('type', 'new_input')->get();
                            if(!empty($studentFields)){
                                foreach ($studentFields as $field) {
                                    if ($field->field_type == 'checkbox') {
                                        // Checkbox multiple values (array) → string में save
                                        $addadmission->{$field->field_name} = implode(',', $request->input($field->field_name, []));
                                    } 
                                    else {
                                        // बाकी सब direct save
                                        $addadmission->{$field->field_name} = $request->input($field->field_name);
                                    }
                                    }
                                }
                            $addadmission->save();
                            
                            $addadmission_id = $addadmission->id;
                            $this->unique_system_id($addadmission_id);
                            
                            $feesGroup = new FeesAssign();
                            $feesGroup->user_id = Session::get('id');
                            $feesGroup->session_id = Session::get('session_id');
                            $feesGroup->branch_id = Session::get('branch_id');
                            $feesGroup->admission_id = $addadmission_id;
                            $feesGroup->save();
                            $feesGroupId = $feesGroup->id;
                
                            $assign_count =0;
                            $fees_group_amount =0;
                            $fees_group_discount =0;
                            
                            if (!empty($request->fees_master_id)) {
                                $assign_count = 0; // Ensure $assign_count is defined
                                if (is_array($request->fees_assign)) { // Check if $request->fees_assign is an array
                                    for ($count = 0; $count < count($request->fees_master_id); $count++) {
                                        if (in_array($request->fees_master_id[$count], $request->fees_assign)) {
                                            $feesGroupDetail = new FeesAssignDetail(); // model name
                                            $feesGroupDetail->user_id = Session::get('id');
                                            $feesGroupDetail->session_id = Session::get('session_id');
                                            $feesGroupDetail->branch_id = Session::get('branch_id');
                                            $feesGroupDetail->fees_group_id = $request->fees_group_id[$count];
                                            $feesGroupDetail->fees_master_id = $request->fees_assign[$assign_count];
                                            $feesGroupDetail->fees_group_amount = $request->fees_group_amount[$count];
                                            $feesGroupDetail->class_type_id = $request->class_type_id ?? null;
                                            $fees_group_amount += $request->fees_group_amount[$count];
                                            $feesGroupDetail->discount = $request->discount[$count];
                                            $fees_group_discount += $request->discount[$count];
                                            $feesGroupDetail->fees_breakdown = $request->fees_breakdown[$count];
                                            $feesGroupDetail->fees_assign_id = $feesGroupId;
                                            $feesGroupDetail->admission_id = $addadmission_id;
                                            $feesGroupDetail->save();
                                            $assign_count++;
                                        }
                                    }
                                } else {
                                   
                                }
                            }
                         
                            $feesGroup->total_amount =$fees_group_amount;
                            $feesGroup->total_discount = $fees_group_discount;
                            $feesGroup->net_amount = $fees_group_amount-$fees_group_discount;
                            $feesGroup->save();
                             
                            $template = MessageTemplate::select('message_templates.*', 'message_types.slug','message_types.status as message_type_status')
                                    ->leftJoin('message_types', 'message_types.id', 'message_templates.message_type_id')
                                    ->where('message_types.slug', 'student-admission')
                                    ->first();
                                
                                $branch = Branch::find(Session::get('branch_id'));
                                $setting = Setting::where('branch_id', Session::get('branch_id'))->first();
                                
                                $arrey1 = [
                                    '{#name#}',
                                    '{#school_name#}',
                                    '{#user_name#}',
                                    '{#password#}',
                                    '{#email#}',
                                    '{#mobile#}',
                                ];
                                
                                $arrey2 = [
                                    $addadmission->first_name . " " . $addadmission->last_name,
                                    $setting->name ?? '',
                                    $addadmission->userName ?? '',
                                    $addadmission->confirm_password ?? '',
                                    $addadmission->email ?? '',
                                    $addadmission->mobile ?? '',
                                ];
                                
                                $whatsapp = str_replace($arrey1, $arrey2, $template->whatsapp_content ?? '');
                                
                                // ✅ Firebase Notification 
                                if ($setting->firebase_notification == 1) {
                                    Helper::sendNotification(
                                        $template->title ?? 'Admission Notification',
                                        $whatsapp,
                                        'student',
                                        $addadmission->id // instead of $attendance['admission_id']
                                    ); 
                                }
                                 
                                // ✅ WhatsApp Message (only if template active)
                                if ($template->message_type_status == 1) {
                                    if ($branch->whatsapp_srvc == 1) {
                                        $mobile = $addadmission->mobile ?? $request->mobile ?? '';
                                        if (!empty($mobile)) {
                                            Helper::MessageQueue($mobile, $whatsapp);
                                        }
                                    }
                                }

                                          
           return response()->json([ 'status' => 'success','message' => 'Admission Added Successfully.','print_url' => url('/admissionStudentPrint/' . $addadmission->id) 
            ]);
           
                }
                return view('students.admission.add', ['BillCounter' => $BillCounterNo]);
            }

            public function admissionView(Request $request){

                    $search['admissionNo'] = $request->admissionNo;
                    $search['class_type_id'] = $request->class_type_id;
                    $search['category'] = $request->category;
                    $search['gender_id'] = $request->gender_id;
                    $search['admission_type_id'] = $request->admission_type_id;
                    $search['blood_group'] = $request->blood_group;
                    $search['status'] = $request->status ?? 1;
                    $search['name'] = $request->name;
                    $search['search_type'] = $request->search_type;
                        $alladmission = collect(); // default empty

                if ($request->isMethod('post')) {
                     
    $input = $request->only([
        'admissionNo','class_type_id','category','gender_id',
        'admission_type_id','blood_group','name','search_type',
        'father_name','mother_name','mobile','address'
    ]);

    $request->validate([
        'search_type' => 'nullable',
        'name' => [
            'nullable',
            function ($attribute, $value, $fail) use ($request) {
                // If search_type is selected, Search By Keywords cannot be empty
                if (!empty($request->search_type) && empty($value)) {
                    $fail('Search By Keywords is required when Search Type is selected.');
                    return; // stop further validation
                }

                // Additional validation based on search_type
                switch ($request->search_type) {
                    case 'first_name':
                        if (strlen($value) < 3) {
                            $fail('First Name must be at least 3 characters.');
                        }
                        break;
                    case 'father_name':
                        if (strlen($value) < 3) {
                            $fail('Father Name must be at least 3 characters.');
                        }
                        break;
                    case 'mother_name':
                        if (strlen($value) < 3) {
                            $fail('Mother Name must be at least 3 characters.');
                        }
                        break;
                    case 'mobile':
                        if (strlen($value) < 3) {
                            $fail('Mobile must be at least 3 digits.');
                        }
                        break;
                    case 'address':
                        if (strlen($value) < 3) {
                            $fail('Address must be at least 3 characters.');
                        }
                        break;
                    case 'admissionNo':
                        if (strlen($value) < 1) {
                            $fail('Admission No must be at least 1 digit.');
                        }
                        break;
                }
            },
        ],
    ]);


    
                    $data = Admission::select('admissions.*','class.name as class_name')->with(['City:id,name','State:id,name'])
                        ->leftJoin('class_types as class','class.id','admissions.class_type_id')
                        ->with('City')->with('State')->where('admissions.session_id', Session::get('session_id'))
                        ->where('admissions.branch_id', Session::get('branch_id'));
                        if ($request->search_type != '') {
                            $data = $data->where($request->search_type,'LIKE', '%' . $request->name . '%' );
                        }
                        if ($request->admissionNo != '') {
                            $data = $data->where("admissionNo", $request->admissionNo);
                        }
                        if ($request->class_type_id != '') {
                            $data = $data->where("class_type_id", $request->class_type_id);
                        }
                     
                        if ($request->category != '') {
                            $data = $data->where("category", $request->category);
                        }
                        if ($request->gender_id != '') {
                            $data = $data->where("gender_id", $request->gender_id);
                        }
                        if ($request->admission_type_id != '') {
                            $data = $data->where("admission_type_id", $request->admission_type_id);
                        }
                        if ($request->blood_group != '') {
                            $data = $data->where("blood_group", $request->blood_group);
                        }
                       
                        if ($request->status != '') {
                            $data = $data->where("status", $request->status);
                        }else
                        {
                            $data = $data->where("status", 1);
                        }
                        
                        $alladmission = $data->where('school',1)->orderBy('first_name','ASC')->get();
                        return view('students.admission.view', ['data' => $alladmission, 'search' => $search]);

                    }
                return view('students.admission.view', ['data' => $alladmission, 'search' => $search]);
            }
            
            public function admissionView2(Request $request){
                // dd($request);
                $search['admissionNo'] = $request->admissionNo;
                $search['class_type_id'] = $request->class_type_id;
                $search['state_id'] = $request->state_id;
                $search['city_id'] = $request->city_id;
                $search['name'] = $request->name;
                // $alladmission = Admission::orderBy('first_name', 'ASC')->where('session_id', Session::get('session_id'));
                $alladmission = Admission::select('admissions.*','class.name as class_name')
                    ->leftJoin('class_types as class','class.id','admissions.class_type_id')->orderBy('admissions.first_name', 'ASC')->where('admissions.session_id', Session::get('session_id'));
                    if (Session::get('role_id') > 1) {
                        $alladmission = $alladmission->where('admissions.branch_id', Session::get('branch_id'));
                    }
                $alladmission =  $alladmission->get();
                //dd($search);
                return view('students.admission.view_2', ['data' => $alladmission, 'search' => $search]);
            }

            public function admissionEdit(Request $request, $id){
                $data = Admission::find($id);
                    if ($request->isMethod('post')) {
                        $sessionId = Session::get('session_id');
                        $request->validate([
                           //  'admissionNo' => ['nullable',Rule::unique('admissions')->where(function ($query) use ($sessionId) {
                            // $query->where('session_id', $sessionId);})->ignore($id)],
                            // 'aadhaar' => 'unique:admissions,aadhaar',
                            // 'mobile' => 'unique:admissions,mobile',
                             'first_name' => 'required',
                                'gender_id' => 'required',
                                'mobile' => 'required|digits:10',
                                'father_name' => 'required',
                                'mother_name' => 'required',
                                'dob' => 'required',
                                'mobile' => 'required',
                                'father_mobile' => 'required',
                                'admission_type_id' => 'required',
                        ]);
                         if ($request->file('student_img')) {
                            $image = $request->file('student_img');
                             $ext = $image->getClientOriginalExtension(); // jpg, png, jpeg आदि
                             $student_image = ($request->admissionNo ??  uniqid()). '.' . $ext;
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
                            $compressedImage->save($destinationPath . $student_image);
                            $data->image = $student_image;
                        }
                
                        if ($request->file('father_img')) {
                            $image = $request->file('father_img');
                            $father_image = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                            $destinationPath = env('IMAGE_UPLOAD_PATH') . 'father_image/';
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0755, true);
                            }
                            if (isset($data->father_image) && File::exists($destinationPath . $data->father_image)) {
                                File::delete($destinationPath . $data->father_image);
                            }
                            $compressedImage = Image::make($image)
                                ->resize(600, null, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })
                            ->encode('jpg', 80); // Adjust quality as needed
                            $compressedImage->save($destinationPath . $father_image);
                            $data->father_img = $father_image;
                        }
                        if ($request->file('mother_img')) {
                            $image = $request->file('mother_img');
                            $mother_image = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                            $destinationPath = env('IMAGE_UPLOAD_PATH') . 'mother_image/';
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0755, true);
                            }
                            if (isset($data->mother_img) && File::exists($destinationPath . $data->mother_img)) {
                                File::delete($destinationPath . $data->mother_img);
                            }
                            $compressedImage = Image::make($image)
                            ->resize(600, null, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            })
                            ->encode('jpg', 80); // Adjust quality as needed
                            $compressedImage->save($destinationPath . $mother_image);
                            $data->mother_img = $mother_image;
                        }
                       
                        $data->user_id = Session::get('id');
                        
                        $data->admissionNo = $request->admissionNo;
                        $data->ledger_no = $request->ledger_no;
                        $data->student_pen = $request->student_pen;
                        $data->apaar_id = $request->apaar_id;
                        $data->roll_no = $request->roll_no;
                        $data->admission_date = $request->admission_date;
                        $data->admission_type_id = $request->admission_type_id;
                        $data->class_type_id = $request->class_type_id;
                        if(!empty($request->stream_subject)){
                            $data->stream_subject = implode(',', $request->stream_subject);
                        }
                        $data->first_name = $request->first_name;
                        $data->aadhaar = $request->aadhaar;
                        $data->jan_aadhaar = $request->jan_aadhaar;
                        $data->previous_school = $request->previous_school;
                        $data->email = $request->email;
                        $data->mobile = $request->mobile;
                        $data->father_name = $request->father_name;
                        $data->mother_name = $request->mother_name;
                        $data->father_mobile = $request->father_mobile;
                        $data->dob = $request->dob;
                        $data->gender_id = $request->gender_id;
                        $data->admission_type_id = $request->admission_type_id;
                        $data->blood_group = $request->blood_group;
                        $data->medium = $request->medium;
                        $data->address = $request->address;
                        $data->country_id = $request->country;
                        $data->village_city = $request->village_city;
                        $data->family_id = $request->family_id;
                        $data->religion = $request->religion;
                        // $data->nationalty = $request->nationalty;
                        $data->category = $request->category;
                        $data->caste_category = $request->caste_category;
                        $data->transport = $request->transport;
                        $data->bus_number = $request->bus_number;
                        $data->bus_route = $request->bus_route;
                        $data->stoppage = $request->stoppage;
                        $data->transpor_charges = $request->transpor_charges;
                        $data->guardian_name = $request->guardian_name;
                        $data->guardian_mobile = $request->guardian_mobile;
                        $data->mother_mob = $request->mother_mob;
                        $data->father_aadhaar = $request->father_aadhaar;
                        $data->mother_aadhaar = $request->mother_aadhaar;
                        $data->family_annual_income = $request->family_annual_income;
                        $data->bank_account = $request->bank_account;
                        $data->bank_name = $request->bank_name;
                        $data->branch_name = $request->branch_name;
                        $data->ifsc = $request->ifsc;
                        $data->micr_code = $request->micr_code;
                        $data->city_id = $request->city;
                        $data->state_id = $request->state;
                        $data->relation_student = $request->relation_student;
                        $data->school_namestudied_last_year = $request->school_namestudied_last_year;
                        $data->house = $request->house;
                        $data->height = $request->height;
                        $data->weight = $request->weight;
                        $data->pincode = $request->pincode;
                        $data->remark_1 = $request->remark_1;
                        $data->bank_account_holder = $request->bank_account_holder;
                        $data->district = $request->district;
                        $data->tehsil = $request->tehsil;
                        $data->father_pancard = $request->father_pancard;
                        $data->mother_pancard = $request->mother_pancard;
                        $data->bpl = $request->bpl;
                        $data->bpl_certificate_no = $request->bpl_certificate_no;
                        $data->father_occupation = $request->father_occupation;
                        $data->mother_occupation = $request->mother_occupation;
                        
                        $data->save();
                        $addadmission_id = $id;
                        $this->unique_system_id($id);
                     
                         return response()->json(['status' => 'success', 'message' => 'Admission Updated Successfully.']);
                       
                }
                 
                $getstate = State::where('country_id', $data['country_id'])->get();
                $getcitie = City::where('state_id', $data['state_id'])->get();
                  
                   return view('students.admission.edit', ['data' => $data, 'getState' => $getstate, 'getCity' => $getcitie]);
            }

            // public function admissionDelete(Request $request){
            //     $id = $request->delete_id;
            //     $admission = Admission::find($id);
            //     $fess = FeesDetail::where('admission_id',$admission->id)->get();
            //     $marks = FillMarks::where('admission_id',$admission->id)->get();
            //     if(count($marks) > 0 || count($fess) > 0){
            //         return redirect::to('admissionView')->with('error', 'This student has not been removed because his fees or field mark Etc. data has been entered.');
            //     };
            //     if (File::exists(env('IMAGE_UPLOAD_PATH') . 'father_image/' . $admission->father_img)) {
            //         File::delete(env('IMAGE_UPLOAD_PATH') . 'father_image/' . $admission->father_img);
            //     }
            //         $admission->delete();
            //     return redirect::to('admissionView')->with('message', 'Admission Deleted Successfully !');
            // }
            
            public function admissionDelete(Request $request)
            {
                // ==========================
                // MULTI DELETE
                // ==========================
                if ($request->ids) {
            
                    $ids = explode(',', $request->ids);
            
                    $notDeleted = 0;
                    $deleted = 0;
            
                    foreach ($ids as $id) {
            
                        $admission = Admission::find($id);
            
                        if (!$admission) {
                            continue;
                        }
            
                        $fees  = FeesDetail::where('admission_id', $admission->id)->count();
                        $marks = FillMarks::where('admission_id', $admission->id)->count();
            
                        // Same rule as single delete
                        if ($fees > 0 || $marks > 0) {
                            $notDeleted++;
                            continue;
                        }
            
                        $imagePath = env('IMAGE_UPLOAD_PATH') . 'father_image/' . $admission->father_img;
            
                        if ($admission->father_img && File::exists($imagePath)) {
                            File::delete($imagePath);
                        }
            
                        $admission->delete();
                        $deleted++;
                    }
            
                    if ($notDeleted > 0) {
                        return redirect()->to('admissionView')
                            ->with('error', "$notDeleted student(s) not deleted because related data exists.");
                    }
            
                    return redirect()->to('admissionView')
                        ->with('message', "$deleted student(s) deleted successfully.");
                }
            
                // ==========================
                // SINGLE DELETE
                // ==========================
                if ($request->delete_id) {
            
                    $admission = Admission::find($request->delete_id);
            
                    if (!$admission) {
                        return redirect()->to('admissionView')
                            ->with('error', 'Student not found.');
                    }
            
                    $fees  = FeesDetail::where('admission_id', $admission->id)->count();
                    $marks = FillMarks::where('admission_id', $admission->id)->count();
            
                    if ($fees > 0 || $marks > 0) {
                        return redirect()->to('admissionView')
                            ->with('error', 'This student cannot be deleted because related data exists.');
                    }
            
                    $imagePath = env('IMAGE_UPLOAD_PATH') . 'father_image/' . $admission->father_img;
            
                    if ($admission->father_img && File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
            
                    $admission->delete();
            
                    return redirect()->to('admissionView')
                        ->with('message', 'Admission Deleted Successfully!');
                }
            }









        public function admissionStudentIdPrint(Request $request, $id){
            // $student_id = Admission::find($id);
            $student_id =  Admission::Select('admissions.*','sessions.from_year','class_types.name as class_name','sessions.to_year')
            ->leftjoin('sessions','sessions.id','admissions.session_id')
            ->leftjoin('class_types','class_types.id','admissions.class_type_id')
            ->where('admissions.id', $id)->first();
            $printPreviewId = Helper::printPreview('Student Id Print');
            //dd($printPreviewId);
            return view($printPreviewId, ['data' => $student_id]);
            // return view('print_file.student_print.admissionStudentIdPrint', ['data' => $student_id]);
        }
            
        public function studentDetail($id){
    
            $data = Admission::select('admissions.*','sessions.from_year','sessions.to_year','class.name as class_name','gender.name as genderName','countries.name as country_name','states.name as state_name','citys.name as city_name')
            ->leftJoin('class_types as class','class.id','admissions.class_type_id')
            ->leftJoin('gender','gender.id','admissions.gender_id')
            ->leftjoin('countries','countries.id','admissions.country_id')
            ->leftjoin('states','states.id','admissions.state_id')
            ->leftjoin('citys','citys.id','admissions.city_id')
            ->leftjoin('sessions','sessions.id','admissions.session_id')
            ->orderBy('class.orderBy', 'ASC')
            ->where([['admissions.session_id', Session::get('session_id')],
                    ['admissions.branch_id', Session::get('branch_id')],
                    ['admissions.id', $id]])->first();
                    $promotion_history = Admission::select('admissions.*','class.name as class_name','gender.name as genderName')
                    ->leftJoin('class_types as class', 'class.id', 'admissions.class_type_id')
                    ->leftJoin('gender', 'gender.id', 'admissions.gender_id')
                    ->where('admissions.unique_system_id', $data->unique_system_id)
                    ->orderBy('class.orderBy', 'ASC')
                    ->get();
                 $siblings = [];
                if ($data) {
                    $siblings = Admission::select('admissions.*','class.name as class_name','gender.name as genderName' )
                        ->leftJoin('class_types as class', 'class.id', 'admissions.class_type_id')
                        ->leftJoin('gender', 'gender.id', 'admissions.gender_id')
                        ->where('admissions.father_name', $data->father_name)
                        ->where('admissions.father_mobile', $data->father_mobile)
                        ->where('admissions.id', '!=', $id) // Exclude the main student
                        ->where([
                            ['admissions.session_id', Session::get('session_id')],
                            ['admissions.branch_id', Session::get('branch_id')]
                        ])
                        ->orderBy('class.orderBy', 'ASC')
                        ->get();
                }
                  $getDocuments = '';
                    if (!empty($data)) {
                        $getDocuments = StudentDocument::select('student_documents.*')
                            ->where('admission_id', $data->id)
                            ->get();
                    }
                $getFees='';
                $getPaidFees='';
                if (!empty($data)) {
                     $getFees = FeesAssignDetail::select('fees_assign_details.*', 'fees_group.name as group_name')
                    ->join('fees_group', 'fees_group.id', '=', 'fees_assign_details.fees_group_id')
                    ->where('admission_id', $data->id)
                    ->get();
            
                $getPaidFees = FeesDetailsInvoices::select('fees_details_invoices.*', 'payment_modes.name as payment_mode')
                    ->join('payment_modes', 'payment_modes.id', '=', 'fees_details_invoices.payment_mode')
                    ->whereIn('status', [0, 1])
                    ->where('admission_id', $data->id)
                    ->get();
                }           
          
         
            return view('students.admission.studentDetail', ['data' => $data, 'getFees' => $getFees,  'getPaidFees' => $getPaidFees ,'siblings' => $siblings,'getDocuments' => $getDocuments,'promotion_history'=>$promotion_history]);
        }
            
            
        public function bulkIdPrint(Request $request){
            $classtype = $request->class_type_id;
            $admission_ids = Admission::where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('class_type_id',$classtype)->pluck('id')->implode(',');
            if(!empty($admission_ids)){
                $data= explode(',',$admission_ids);
            }
            return view('students.student_id.bulkIdPrint',['admission_ids'=>$data]);
        }
    
        public function studentBulkImageUpload(Request $request)
        {
            if ($request->isMethod('post')) {
                if ($request->file('image')) {
                    foreach ($request->file('image') as $img) {
                        $originalName = $img->getClientOriginalName();
                        $filenameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
        
                        $admission = Admission::where('branch_id', Session::get('branch_id'))
                            ->where('session_id',Session::get('session_id'))
                            ->where('admissionNo', $filenameWithoutExtension)
                            ->first();
        
                        if ($admission) {
                            // Old image delete
                            $oldImagePath = env('IMAGE_UPLOAD_PATH') . 'profile/' . $admission->image;
                            if ($admission->image && File::exists($oldImagePath)) {
                                File::delete($oldImagePath);
                            }
        
                            // New image name with extension
                             $ext = $image->getClientOriginalExtension(); // jpg, png, jpeg आदि
                            $student_image = ($admission->admissionNo ?? uniqid()) . '.'.$ext;
                            $destinationPath = env('IMAGE_UPLOAD_PATH') . 'profile/';
        
                            if (!file_exists($destinationPath)) {
                                mkdir($destinationPath, 0755, true);
                            }
        
                            // Compress and save new image
                            $compressedImage = \Image::make($img)
                                ->resize(600, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                })
                                ->encode('jpg', 80); // quality 80%
        
                            $compressedImage->save($destinationPath . $student_image);
        
                            // Update DB
                            $admission->image = $student_image;
                            $admission->save();
                        }
                    }
        
                    return redirect('admissionView')->with('message', 'Images Updated Successfully');
                }
            }
        }
            

        public function category_wise_report(Request $request){
            $student_id =  Admission::Select('admissions.*','sessions.from_year','class_types.name as class_name','sessions.to_year')
            ->leftjoin('sessions','sessions.id','admissions.session_id')
            ->leftjoin('class_types','class_types.id','admissions.class_type_id')
            ->get();
            return view('students.report/category_wise_report', ['data' => $student_id]);
        }

        public function streamUpdate(Request $request){
            $search["exam_id"] = $request->exam_id  ?? "";
            $data = [];
            $list_subject = '';
            $search["class_type_id"] = $request->class_type_id;
            if ($request->isMethod('post')) {
                $data = Admission::where('class_type_id',$request->class_type_id)->where("branch_id", Session::get("branch_id"))->orderBy('first_name','ASC')->get();
                $list_subject = Subject::where("class_type_id", $request->class_type_id)->where("branch_id", Session::get("branch_id"))->orderBy("sort_by", "ASC")->get();
            }
            
            return view('students.admission.stream_update',['search'=>$search,'data'=>$data,'list_subject'=>$list_subject]);
        }
    
        public function streamUpdateSave(Request $request){
            if ($request->isMethod('post')) {
                if($request->admission_id != [] && $request->subject_id != []){
                    for ($i = 0; $i < count($request->admission_id); $i++) {
                        $data = Admission::find($request->admission_id[$i]);
                        $a1 = !empty($data->stream_subject) ? explode(',', $data->stream_subject) : [];
                        $a2 = $request->subject_id;
                        $r = array_merge($a1, $a2);
                        $r_unique = array_unique($r);
                        //dd($r_unique);
                        if (!empty($request->subject_id)) {
                            $data->stream_subject = implode(',', $r_unique);
                            $data->save();  
                        }
                    }
                   return response()->json(['status' => 'success','message' => 'Stream Updated Successfully.',]);
                }else{
                    return redirect::to('stream_update')->with('error', 'please select Students checkbox !');
                }
            }
        }
            
        public function streamRemove(Request $request,$admission_id,$subject_id){
            $data = Admission::find($admission_id);
            if (!$data) {
                return response()->json(['error' => 'Admission not found.'], 404);
            }
            $a1 = !empty($data->stream_subject) ? explode(',', $data->stream_subject) : [];
            // Using array_filter to remove the subject
            $a1 = array_filter($a1, function($subject) use ($subject_id) {
                return $subject != $subject_id;
            });
            // Reindex the array if needed
            $a1 = array_values($a1);
            $data->stream_subject = implode(',', $a1);
            $data->save();
            return response()->json(['success' => 'Subject Updated Successfully '], 200);
        }
            
        public function folderCompressor(Request $request) {
            $path = '/home/rusoft/public_html/greengarden/schoolimage/profile/';
            $images = \File::allFiles($path);
            foreach ($images as $file) {
                $filePath = $file->getRealPath();
                // Check if the file is a valid image
                if (!@getimagesize($filePath)) {
                    \Log::warning("Invalid image file: " . $file->getFilename());
                    continue; // Skip invalid images
                }
                $student_image = $file->getFilename();
                $destinationPath = $path;
                // Create the destination directory if it doesn't exist
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                // Delete old image if it exists
                if (isset($data->image) && \File::exists($destinationPath . $data->image)) {
                    \File::delete($destinationPath . $data->image);
                }
                $compressedImage = Image::make($filePath)
                    ->resize(600, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('jpg', 80); // Adjust quality as needed
                // Save the compressed image
                $compressedImage->save($destinationPath . $student_image);
            }
            return redirect::to('/')->with('message', 'folder Compressor Successfully!');
        }
                 
      
        public function login_credential_reports(Request $request)
        {
            $search = [
                'class_type_id' => $request->class_type_id,
            ];
            $query = Admission::select('admissions.*', 'class.name as class_name')
                ->leftJoin('class_types as class', 'class.id', '=', 'admissions.class_type_id')
                ->where('admissions.branch_id', Session::get('branch_id'))->where('admissions.session_id', Session::get('session_id'));
                
            if (!empty($search['class_type_id'])) {
                $query->where('admissions.class_type_id', $search['class_type_id']);
            }
            
            $data = $query->get();
            return view('students.login_credential.view', [ 'search' => $search,'data' => $data,]);
        }


        public function studentUserNameCreate(Request $request)
        {
            // ✅ Selected IDs lo
            if (empty($request->student_ids)) {
                return back()->with('error', 'No students selected.');
            }
        
            $ids = array_filter(explode(',', $request->student_ids));
        
            if (empty($ids)) {
                return back()->with('error', 'Invalid student selection.');
            }
        
            $usernameOrder = explode(',', $request->username_order);
            $passwordOrder = explode(',', $request->password_order);
        
            $nameLetters = $request->name_letters ?? 4;
            $mobileDigits = $request->mobile_digits ?? 4;
        
            // 🔥 IMPORTANT CHANGE: ONLY SELECTED STUDENTS
            $students = Admission::select('admissions.*', 'class.name as class_name')
                ->leftJoin('class_types as class', 'class.id', '=', 'admissions.class_type_id')
                ->whereIn('admissions.id', $ids) // ⭐⭐⭐ THIS IS THE FIX
                ->where('admissions.branch_id', Session::get('branch_id'))
                ->where('admissions.session_id', Session::get('session_id'))
                ->get();
        
            foreach ($students as $student) {

                $username = '';
                $password = '';
            
                // =============================
                // 1️⃣ Username Always From Fields
                // =============================
                if (!empty($usernameOrder)) {
                    foreach ($usernameOrder as $key) {
                        if (!empty($key)) {
                            $username .= $this->getFieldValue($student, $key, $nameLetters, $mobileDigits);
                        }
                    }
                }
            
                // =============================
                // 2️⃣ Password Logic
                // =============================
            
                // Priority 1 → Custom Password
                if (!empty($request->custom_password)) {
            
                    $password = trim($request->custom_password);
            
                } 
                // Priority 2 → Password Fields
                elseif (!empty($passwordOrder)) {
            
                    foreach ($passwordOrder as $key) {
                        if (!empty($key)) {
                            $password .= $this->getFieldValue($student, $key, $nameLetters, $mobileDigits);
                        }
                    }
            
                    if (empty($password)) {
                        $password = '333666';
                    }
            
                } 
                // Priority 3 → Default fallback
                else {
                    $password = '333666';
                }
            
                $student->username = $username;
                $student->password = Hash::make($password);
                $student->confirm_password = $password;
                $student->save();
            }
            
            return back()->with('success', 'Selected students credentials generated successfully!');
        }
            
            
        private function getFieldValue($student, $key, $nameLetters = 4, $mobileDigits = 4)
        {
            switch ($key) {
                case 'name':
                    return substr(strtolower(preg_replace('/\s+/', '', $student->first_name)), 0, $nameLetters);
                case 'mobile':
                    return substr(preg_replace('/\D/', '', $student->mobile), -$mobileDigits);
                case 'dob':
                    if (!$student->dob) return '';
                    $dob = date('dmy', strtotime($student->dob)); //e.g. 150805
                    return $dob;
                case 'admission_no':
                    return $student->admissionNo;
                case 'class':
                    return strtolower(preg_replace('/\s+/', '', $student->class_name));
                default:
                    return '';
            }
        }
         public function imageRotateSave(Request $request)
        {
                    $id = $request->admission_id;
                    $admission = Admission::find($id);
                
                    if (!$admission) {
                        return response()->json(['status'=>'error','message'=>'Admission not found!']);
                    }
            $action = $request->action_type;
            if($action == 'upload'){
                if ($request->file('student_img')) {
                                $image = $request->file('student_img');
                                $ext = $image->getClientOriginalExtension(); // jpg, png, jpeg आदि
                                $student_image = ($admission->admissionNo ??  uniqid()). '.' . $ext;
                                $admission->image = $student_image;
                                $admission->save();
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
                                $compressedImage->save($destinationPath . $student_image);
                               $image_url =  env('IMAGE_SHOW_PATH'). 'profile/'.$student_image;
                            }
                 return response()->json(['success'=>true,'image_url'=>$image_url,'message'=>' file uploaded']);
                }
                if($action == 'delete'){
                if($admission->image && file_exists(env('IMAGE_UPLOAD_PATH') . 'profile/'.$admission->image)){
                   // unlink(env('IMAGE_UPLOAD_PATH') . 'profile/'.$admission->image);
                    File::delete(env('IMAGE_UPLOAD_PATH') . 'profile/' . $admission->image);
                }
                $admission->image = null;
                $admission->save();
                return response()->json(['success'=>true,'message'=>' The image was deleted successfully']);
               }
        
              return response()->json(['success'=>false,'message'=>'Invalid action']);    
                
        }
        



    }
