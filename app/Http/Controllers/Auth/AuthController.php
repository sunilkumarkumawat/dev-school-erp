<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Admission;
use App\Models\State;
use App\Models\IPSetting;
use App\Models\WhatsappApiResponse;
use App\Models\OtpRequest;
use App\Models\Sessions;
use App\Models\Setting;
use App\Models\LoginLog;
use App\Models\UserPermission;
use App\Models\BillCounter;
use App\Models\Master\Branch;
use App\Models\Master\MessageTemplate;
use App\Models\PermissionManagement;
use App\Models\Master\MessageType;
use Illuminate\Validation\Validator; 
use App\Helpers\helper;
use Session;
use Hash;
use Str;
use Redirect;
use Auth;
use DB;
use File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{


  public function newRegistration(Request $request)
    {
        // =========================
        // 1️⃣ GET Request -> Show Form
        // =========================
        if (!$request->isMethod('post')) {
            return view('auth.newRegistration');
        }

        // =========================
        // 2️⃣ Validation
        // =========================
        $request->validate([
            'softwareTokenNo' => 'required|string',
            'dbDatabase'      => 'required|string',
            'dbUsername'      => 'required|string',
            'dbPassword'      => 'required|string',
            'imageUploadPath' => 'required|string',
            'imageShowPath'   => 'required|url',
        ],[
            'softwareTokenNo.required' => 'Software Token Number required hai.',
            'dbDatabase.required'      => 'Database name required hai.',
            'dbUsername.required'      => 'Database username required hai.',
            'dbPassword.required'      => 'Database password required hai.',
            'imageUploadPath.required' => 'Image upload path required hai.',
            'imageShowPath.required'   => 'Image show URL valid hona chahiye.',
        ]);

        // =========================
        // 3️⃣ Current Domain
        // =========================
        $mainDomainUrl = rtrim(request()->getSchemeAndHttpHost(), '/');

        // =========================
        // 4️⃣ API Call for Token Verification
        // =========================
        try {
            $response = Http::timeout(10)
                ->get('https://web.rusofterp.in/api/checkInstallation/' . $request->softwareTokenNo);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'type'    => 'server_connection',
                'message' => 'Verification server se connect nahi ho pa raha.'
            ], 500);
        }

        if (!$response->successful()) {
            return response()->json([
                'status'  => 'error',
                'type'    => 'token_invalid',
                'message' => 'Software token invalid ya expire ho chuka hai.'
            ], 404);
        }

        $data = $response->json();
        $apiDomain = rtrim($data['domain'] ?? '', '/');

        // =========================
        // 5️⃣ Installation Check
        // =========================
        if (($data['installation'] ?? 0) == 1) {
            return response()->json([
                'status'  => 'error',
                'type'    => 'already_installed',
                'message' => 'Yeh software token pehle se install ho chuka hai.'
            ], 403);
        }

        // =========================
        // 6️⃣ Domain Match Check
        // =========================
        if (strtolower($apiDomain) != strtolower($mainDomainUrl)) {
            return response()->json([
                'status'  => 'error',
                'type'    => 'domain_mismatch',
                'message' => 'Yeh token is domain ke liye authorized nahi hai.',
                'your_domain' => $mainDomainUrl,
                'allowed_domain' => $apiDomain
            ], 403);
        }

        // =========================
        // 7️⃣ Update .env File
        // =========================
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return response()->json([
                'status'  => 'error',
                'type'    => 'env_missing',
                'message' => '.env file server par nahi mili.'
            ], 500);
        }

        $envData = file_get_contents($envPath);

        $updates = [
            'SOFTWARE_TOKEN_NO' => $request->softwareTokenNo,
            'IMAGE_UPLOAD_PATH' => $request->imageUploadPath,
            'IMAGE_SHOW_PATH'   => $request->imageShowPath,
            'DB_DATABASE'       => $request->dbDatabase,
            'DB_USERNAME'       => $request->dbUsername,
            'DB_PASSWORD'       => $request->dbPassword,
        ];

        foreach ($updates as $key => $value) {

            $value = addslashes($value);

            if (preg_match("/^{$key}=.*/m", $envData)) {
                $envData = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $envData
                );
            } else {
                $envData .= "\n{$key}=\"{$value}\"";
            }
        }

        file_put_contents($envPath, $envData);

        // =========================
        // 8️⃣ Clear Cache
        // =========================
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:cache');

        // =========================
        // 9️⃣ Optional: Clear Table
        // =========================
        $this->clearTable('approved');

        // =========================
        // 🔟 Clear Session
        // =========================
        Session::flush();

        return response()->json([
            'status'  => 'success',
            'type'    => 'installation_complete',
            'message' => 'Software installation successfully complete ho gaya.'
        ]);
    }

    

        public function siblingList(Request $request){
            session()->forget('sibling_active');
             return redirect("/");
        }
       public function siblingLogin(Request $request, $id)
        {
            $getUser = Admission::find($id);
        
            if (!$getUser) {
                return redirect()->back()->with('error', 'Sibling not found! ');
            }
        
            // рдЕрдЧрд░ account inactive рд╣реИ
            if ($getUser->status != 1) {
                return redirect()->back()->with('error', 'This account is inactive.');
            }
        
            $isStudent = true;
            session()->put('modal_name', 'Admission');
        
            $current_active_session_id = Setting::where('branch_id', $getUser->branch_id)
                ->value('current_active_session_id');
        
            if ($isStudent) {
                session()->put('session_id', $getUser->session_id);
            } else {
                session()->put('session_id', $current_active_session_id);
            }
        
            
            $request->session()->put([
                'id'         => $getUser->id,
                'name'       => $getUser->name,
                'email'      => $getUser->email,
                'branch_id'  => $getUser->branch_id,
                'userName'   => $getUser->userName,
                'first_name' => $getUser->first_name,
                'last_name'  => $getUser->last_name,
                'role_id'    => $getUser->role_id,
            ]);
        
            session()->put('class_type_id', $getUser->class_type_id);
            session()->put('sibling_active', 'yes');

            $rememberToken = bin2hex(random_bytes(32));
            $getUser->remember_token = $rememberToken;
            $getUser->save();
            cookie()->queue(cookie('remember_token', $rememberToken, 43200));
        
            $this->LoginLog($getUser->id, $getUser->first_name, $getUser->role_id);

            return redirect('/')->with('message', $getUser->first_name . ' login successful!');
        }

            
    public function getLogin(Request $request){
   
        if($request->isMethod('post')){
           // dd($request);
                $request->validate([
                    'user_name' => 'required',
                    'password' => 'required',
                ]);
                if (User::count() == 0) {
                    return response()->json(['status' => 'error', 'message' => 'Please add a branch.']);
                }
                $this->logFile();
                // Default Admin Login
                if ($request->user_name === 'rusoft' && $request->password === 'rusoft') {
                    $this->DefaultPassword();
                    return response()->json(['status' => 'success', 'redirect_url' => '/','message'=>'Login Successfully !']);
                }
                $userData = User::where('userName', $request->user_name)->whereNotIn('role_id', [3])->first();
                $isStudent = false;
            
                if (!$userData) {
                    $userData = Admission::where('userName', $request->user_name)->where('role_id', 3)->orderBy('id', 'DESC')->first();
                    if ($userData) {
                        $isStudent = true;
                           session()->put('modal_name','Admission');
                    }
                }
            
                if (!$userData) {
                    return response()->json(['status' => 'error', 'user_name_error' => ' Error! User not found.']);
                }
            
                // Check user status
                if ($userData->status != 1) {
                    return response()->json(['status' => 'error', 'message' => 'Your login details are inactive. Please contact Admin.']);
                }
            
                // Verify password
                if (!Hash::check($request->password, $userData->password)) {
                    return response()->json(['status' => 'error', 'password_error' => 'Invalid password. please try again']);
                }
                    $current_active_session_id = Setting::where('branch_id', $userData->branch_id)->first(['current_active_session_id']);

                $attendanceUniqueId = trim((string) ($userData->attendance_unique_id ?? ''));

              if ($isStudent) {
                    session()->put('session_id', $userData->session_id);
                }else{
                     session()->put('session_id', $current_active_session_id->current_active_session_id);
                }
                   // Set session variables
                        $request->session()->put([
                            'id' => $userData->id,
                            'name' => $userData->name,
                            'email' => $userData->email,
                            'branch_id' => $userData->branch_id,
                            'userName' => $userData->userName,
                            'first_name' => $userData->first_name,
                            'last_name' => $userData->last_name,
                            'role_id' => $userData->role_id,
                            'attendance_unique_id' => $attendanceUniqueId,
                        ]);

                    session()->put('class_type_id', $userData->class_type_id);
                    session()->put('attendance_unique_id', $attendanceUniqueId);
            
                   
                               
            
            
                $loginWithOtp = Setting::find(1)->loginWithOtp ?? 'No';
                if ($loginWithOtp === 'Yes') {
                    return response()->json(['status' => 'success', 'redirect_url' => route('auth.otpDiv')]);
                }
            
                $branch = Branch::find($userData->branch_id);
                if (!$branch || !$branch->branch_sidebar_id) {
                    return response()->json(['status' => 'error', 'message' => 'Please set sidebar.']);
                }
                // Generate remember token
                    $rememberToken = bin2hex(random_bytes(32)); 
                    $userData->remember_token = $rememberToken; 
                    $userData->save();
                    cookie()->queue(cookie('remember_token', $rememberToken, 43200));
                     $this->LoginLog($userData->id,$userData->first_name,$userData->role_id);
                
                return response()->json(['status' => 'success', 'redirect_url' => '/','message'=>'Login Successfully !']);
                }
                     return view('auth/login');        
}
    
   public function logFile(){
        $logFile = storage_path('logs/laravel.log');
        if (File::exists($logFile)) {
            File::put($logFile, ''); 
        } 
    }

     public function LoginLog($id,$name,$role_id){
     	$log  = new LoginLog;//model name
     	$log->user_id = Session::get('id');
        $log->session_id = Session::get('session_id');
        $log->branch_id = Session::get('branch_id');
		$log->ip_address =request()->ip();
		$log->name = $name;
		$log->role_id = $role_id;
        $log->save();
    
    }


   public function DefaultPassword(){
                 $userData = User::get()->first();
                    session()->put('id',$userData->id);
                    session()->put('name',$userData->name);
                    session()->put('email',$userData->email);
                    $current_active_session_id = Setting::where('branch_id',$userData->branch_id)->first(['current_active_session_id']);
                    session()->put('session_id',$current_active_session_id->current_active_session_id);
                    session()->put('branch_id',$userData->branch_id);
                    session()->put('userName',$userData->userName);
                    session()->put('first_name',$userData->first_name);
                    session()->put('last_name',$userData->last_name);
                    session()->put('father_name',$userData->father_name);
                    session()->put('mother_name',$userData->mother_name);
                    session()->put('mobile',$userData->mobile);
                    session()->put('countries_id',$userData->countries_id);
                    session()->put('state_id',$userData->state_id);
                    session()->put('city_id',$userData->city_id);
                    session()->put('photo',$userData->photo);
                    session()->put('regisUniqueId',$userData->regisUniqueId);
                    session()->put('role_id',$userData->role_id);
                    session()->put('created_at',$userData->created_at);
                    session()->put('edit',$userData->edit);
                    session()->put('class_type_id',$userData->class_type_id);
                    session()->put('attendance_unique_id',$userData->attendance_unique_id);

                    
                    // !! start session validation
                    $checkSessionUser = User::where('userName',$userData->userName)->where('role_id',$userData->role_id)
                                        ->get()->first();
                        if($checkSessionUser){
                            $branch = Branch::find(Session::get('branch_id'));
                            if($branch->branch_sidebar_id == "" || $branch->branch_sidebar_id == 0){
                               return redirect("setSidebar");
                            }else{
                               return redirect("/")->with('message','Login Successfully !');
                            }
                        }
            }


	public function logout() {
          Auth::logout();
          // Delete all cookies
                foreach ($_COOKIE as $key => $value) {
                   \Cookie::queue(\Cookie::forget($key));
                }
          Session::flush();
          return redirect("login")->with('message','Logout successfully!'); 
    }

   
  
    
    



public function allowSidebar(Request $request)
{
    if (!$request->isMethod('post')) {
        return view('auth.allowSidebar');
    }

    /* ===============================
     | 1️⃣ VALIDATION
     =============================== */
    $request->validate([
        'role_id'         => 'required',
        'session_id'      => 'required',
        'branch_code.*'   => 'required',
        'branch_name.*'   => 'required',
        'contact_person' => 'required',
        'mobile'          => 'required',
        'user_name'       => 'required|unique:users,userName',
        'password'        => 'required|min:6',
        'sidebar_id'      => 'required|array',
    ]);

    DB::beginTransaction();

    try {

        /* ===============================
         | 2️⃣ CREATE USER
         =============================== */
        $user = new User();
        $user->session_id       = $request->session_id;
        $user->role_id          = $request->role_id;
        $user->userName         = $request->user_name;
        $user->first_name       = $request->contact_person;
        $user->mobile           = $request->mobile;
        $user->email            = $request->email;
        $user->address          = $request->address;
        $user->status           = 1;
        $user->password         = Hash::make($request->password);
        $user->confirm_password = $request->password;
        $user->save();

        $sidebarIds    = $request->sidebar_id;
        $subSidebarMap = $request->sidebar_sub_id ?? [];

        $allSubIds = collect($subSidebarMap)->flatten()->unique()->toArray();
        $branchIds = [];

        /* ===============================
         | 3️⃣ CREATE MULTIPLE BRANCHES
         =============================== */
        foreach ($request->branch_code as $index => $branchCode) {

            $branch = new Branch();
            $branch->user_id           = $user->id;
            $branch->session_id        = $request->session_id;
            $branch->branch_code       = $branchCode;
            $branch->branch_name       = $request->branch_name[$index];
            $branch->contact_person    = $request->contact_person;
            $branch->mobile            = $request->mobile;
            $branch->email             = $request->email;
            $branch->address           = $request->address;
            $branch->branch_sidebar_id = implode(',', $sidebarIds);
            $branch->sidebar_sub_id    = implode(',', $allSubIds);
            $branch->expert_name       = $request->expert_name;
            $branch->sms_srvc          = $request->sms_status ?? 0;
            $branch->email_srvc        = $request->email_status ?? 0;
            $branch->whatsapp_srvc     = $request->whatsapp_status ?? 0;
            $branch->save();

            $branchIds[] = $branch->id;

            /* ===============================
             | 4️⃣ USER PERMISSIONS
             =============================== */
            foreach ($sidebarIds as $sidebarId) {

                $subIds = $subSidebarMap[$sidebarId] ?? [];

                $permission = new UserPermission();
                $permission->user_id        = $user->id;
                $permission->sidebar_id     = $sidebarId;
                $permission->sidebar_name   = DB::table('sidebars')
                                                ->where('id', $sidebarId)
                                                ->value('name');
                $permission->sub_sidebar_id = implode(',', $subIds);
                $permission->add            = 1;
                $permission->edit           = 1;
                $permission->view           = 1;
                $permission->delete         = 1;
                $permission->print          = 1;
                $permission->status         = 1;
                $permission->save();
            }

            /* ===============================
             | 5️⃣ BILL COUNTER (ALL SESSIONS)
             =============================== */
            $counterTypes = ['Enquiry', 'Admission', 'FeesSlip', 'User'];
            $sessions     = Sessions::all();

            foreach ($sessions as $session) {
                foreach ($counterTypes as $type) {

                    $exists = BillCounter::where([
                        'user_id'    => $user->id,
                        'branch_id'  => $branch->id,
                        'session_id' => $session->id,
                        'type'       => $type,
                    ])->exists();

                    if (!$exists) {
                        $counter = new BillCounter();
                        $counter->user_id    = $user->id;
                        $counter->branch_id  = $branch->id;
                        $counter->session_id = $session->id;
                        $counter->type       = $type;
                        $counter->counter    = 0;
                        $counter->save();
                    }
                }
            }

            /* ===============================
             | 6️⃣ SETTINGS
             =============================== */
            $setting = new Setting();
            $setting->user_id                   = $user->id;
            $setting->session_id                = $request->session_id;
            $setting->current_active_session_id = $request->session_id;
            $setting->role_id                   = $request->role_id;
            $setting->branch_id                 = $branch->id;
            $setting->name                      = $branch->branch_name;
            $setting->mobile                    = $branch->mobile;
            $setting->gmail                     = $branch->email;
            $setting->country_id                = 101;
            $setting->state_id                  = 33;
            $setting->address                   = $branch->address;
            $setting->save();
        }

        /* ===============================
         | 7️⃣ UPDATE USER BRANCH ACCESS
         =============================== */
        $user->branch_id        = $branchIds[0];               // default branch
        $user->access_branch_id = implode(',', $branchIds);    // all branches
        $user->save();

        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => 'Account created successfully',
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => basename($e->getFile()),
        ], 500);
    }
}





     public function clearTable($status=null){
    if($status == 'approved'){
          File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Signature_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Signature1_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Signature2_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Signature3_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'banner_image');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Book_img'); 
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'bus_photo');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'college_id');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'covid_certificate');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'download_center');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'earing_products');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'expense');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'expense_bill');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'father_adhar');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'father_image');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'father_photo');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'guardian_photo');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'homework');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'hostel');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'library');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'mother_photo');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'mother_image');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'other_document');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'parmanent');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'photo');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'police_verification');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'profile');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'school_gallery');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'setting');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'student_image');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'uniform_image');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'uploadHomework');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'user');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'student_id_proof');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Signature4_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'chat');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'student_document');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'whatsapp_documents');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'uplode_qr');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Sudent_Id_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'studentIdPdf');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'student_expence_bill');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'fees_receipt_pdf');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'expense');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'certificates_images');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'payment_receipt');


        
        DB::table('accounts')->truncate();
        DB::table('admissions')->truncate();
        DB::table('admit_cards')->truncate();
        DB::table('admit_card_note')->truncate();
        DB::table('all_subjects')->truncate();
        DB::table('assign_books')->truncate();
        DB::table('assign_exams')->truncate();
        DB::table('assign_questions')->truncate();
        DB::table('bill_counters')->truncate();
        DB::table('birthday_wishes')->truncate();
        DB::table('books_uniform_shops')->truncate();
        DB::table('book_invoices')->truncate();
        DB::table('branch')->truncate();
        DB::table('bus')->truncate();
        DB::table('bus_assign_students')->truncate();
        DB::table('bus_route')->truncate();
        DB::table('bus_route_assign')->truncate();
        DB::table('class_types')->truncate();
        DB::table('complaint')->truncate();
        DB::table('c_certificates_form')->truncate();
        DB::table('download_center')->truncate();
        DB::table('electricity_bill_payments')->truncate();
        DB::table('enquirys')->truncate();
        DB::table('evente_certificates')->truncate();
        DB::table('event_management')->truncate();
        DB::table('examination_admit_cards')->truncate();
        DB::table('examination_schedules')->truncate();
        DB::table('exams')->truncate();
        DB::table('expenses')->truncate();
        DB::table('failed_jobs')->truncate();
        DB::table('fees_assign_details')->truncate();
        DB::table('fees_collect')->truncate();
        DB::table('fees_detail')->truncate();
        DB::table('fees_group')->truncate();
        DB::table('fees_master')->truncate();
        DB::table('fill_marks')->truncate();
        DB::table('fill_min_max_marks')->truncate();
        DB::table('food_menu_lists')->truncate();
        DB::table('gallery')->truncate();
        DB::table('gate_passes')->truncate();
        DB::table('heads')->truncate();
        DB::table('holidays')->truncate();
        DB::table('homeworks')->truncate();
        DB::table('homework_documents')->truncate();
        DB::table('homework_review')->truncate();
        DB::table('hostel')->truncate();
        DB::table('hostel_assign')->truncate();
        DB::table('hostel_bed')->truncate();
        DB::table('hostel_building')->truncate();
        DB::table('hostel_expences')->truncate();
        DB::table('hostel_floor')->truncate();
        DB::table('hostel_meter_units')->truncate();
        DB::table('hostel_room')->truncate();
        DB::table('hourly_homework')->truncate();
        DB::table('hourly_upload_homeworks')->truncate();
        DB::table('inventorys')->truncate();
        DB::table('inventory_details')->truncate();
        DB::table('inventory_items')->truncate();
        DB::table('inventory_sales')->truncate();
        DB::table('inventory_sale_details')->truncate();
        DB::table('invoices')->truncate();
        DB::table('jobs')->truncate();
        DB::table('leave_management')->truncate();
        DB::table('levels_digital')->truncate();
        DB::table('librarys')->truncate();
        DB::table('library_assign')->truncate();
        DB::table('library_books')->truncate();
        DB::table('library_cabins')->truncate();
        DB::table('library_categarys')->truncate();
        DB::table('library_lockers')->truncate();
        DB::table('library_plans')->truncate();
        DB::table('library_time_slots')->truncate();
        DB::table('mess_fees_strucher')->truncate();
        DB::table('mess_food_categorys')->truncate();
        DB::table('mess_food_routine')->truncate();
        DB::table('notice_board')->truncate();
        DB::table('online_payment_transactions')->truncate();
        DB::table('pelantys')->truncate();
        DB::table('prayers')->truncate();
        DB::table('questions')->truncate();
        DB::table('questions_digital')->truncate();
        DB::table('question_types_digital')->truncate();
        DB::table('recycle_bins')->truncate();
        DB::table('registration_remarks')->truncate();
        DB::table('registration_terms')->truncate();
        DB::table('rules')->truncate();
        DB::table('school_calender')->truncate();
        DB::table('school_desk')->truncate();
        DB::table('security_deposit')->truncate();
        DB::table('sell_invantory_items')->truncate();
		DB::table('sell_inventory')->truncate();
		DB::table('settings')->truncate();
		DB::table('sms_settings')->truncate();
		DB::table('sports')->truncate();
		DB::table('sports_certificates')->truncate();
		DB::table('staff_salarys')->truncate();
		DB::table('staff_salary_details')->truncate();
		DB::table('student_attendance')->truncate();
		DB::table('student_expenses')->truncate();
		DB::table('student_expense_details')->truncate();
		DB::table('subject')->truncate();
		DB::table('tc_certificates')->truncate();
		DB::table('teacher_subjects')->truncate();
		DB::table('time_periods')->truncate();
		DB::table('total_days')->truncate();
		DB::table('to_do_list')->truncate();
		DB::table('uniforms')->truncate();
		DB::table('upload_homeworks')->truncate();
		DB::table('users')->truncate();
		DB::table('whatsapp_groups')->truncate();
		DB::table('fees_details_invoices')->truncate();
		DB::table('fees_assign_details')->truncate();
		DB::table('fees_advances')->truncate();
		DB::table('fees_advance_historys')->truncate();
		DB::table('call_logs')->truncate();
		DB::table('custom_villages_list')->truncate();
		DB::table('user_permission')->truncate();
		DB::table('fees_assigns')->truncate();
		DB::table('login_logs')->truncate();
        Session::put('clearTable','approved');
    
      
}
else
{
    return redirect::to('admin/login')->with('error','You are not authorized for this route.');  
}
    }
    
    protected function unique_system_id($id){
        $uniqueId = strtoupper(Str::random(10));
        Admission::where('id',$id)->whereNull('unique_system_id')->update(['unique_system_id' => $uniqueId]);
    }
    

    
    public function folderClear(){
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Signature_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Signature1_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Signature2_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Signature3_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'banner_image');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Book_img'); 
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'bus_photo');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'college_id');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'covid_certificate');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'download_center');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'earing_products');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'expense');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'expense_bill');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'father_adhar');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'father_image');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'father_photo');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'guardian_photo');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'homework');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'hostel');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'library');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'mother_photo');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'mother_image');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'other_document');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'parmanent');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'photo');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'police_verification');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'profile');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'school_gallery');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'setting');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'student_image');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'uniform_image');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'uploadHomework');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'user');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'student_id_proof');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Signature4_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'chat');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'student_document');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'whatsapp_documents');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'uplode_qr');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'Sudent_Id_img');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'studentIdPdf');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'student_expence_bill');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'fees_receipt_pdf');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'expense');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'certificates_images');
        File::deleteDirectory(env('IMAGE_UPLOAD_PATH').'payment_receipt');

        
            return redirect::to('/')->with('message', 'folder Clear Successfully.');
    }
    
   public function mpinLogin(Request $request)
{
    $request->validate([
        'mpin' => 'required|digits:4'
    ]);

    // mPIN user check
    $userData = User::where('mpin', $request->mpin)->first();
    if(empty($userData)){
        $userData = Admission::where('mpin', $request->mpin)
                    ->where('role_id', 3)
                    ->orderBy('id', 'DESC')
                    ->first();
    }

    // тЭМ If user not found
    if(empty($userData)){
        return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid mPIN'
                ]);
            }

   $loginRequest = Request::create('', 'POST', [
    'user_name' => $userData->userName,
    'password'  => $userData->confirm_password
]);

 // тЬЕ Attach session to new request (IMPORTANT)
    $loginRequest->setLaravelSession(session()->driver());

     $this->getLogin($loginRequest);
    return response()->json(['status' => 'success', 'redirect_url' => '/','message'=>'Login Successfully !']);

}

    public function saveMpin(Request $request)
        {
            $request->validate([
                'user_name' => 'required',
                'password'  => 'required',
                'mpin'      => 'required|digits:4'
            ]);
        
            // check in users table
            $user = User::where('userName', $request->user_name)->first();
        
            // if not found check admission table (optional)
            if(!$user){
                $user = Admission::where('userName', $request->user_name)
                                 ->where('role_id',3)
                                 ->first();
            }
        
            if(!$user){
                return response()->json(['status'=>'error','message'=>'User not found!']);
            }
        
            // verify password
            if(!Hash::check($request->password, $user->password)){
                return response()->json(['status'=>'error','message'=>'Wrong Password!']);
            }
            
            // тЬЕ Check mPIN unique in BOTH tables
                $mpinExistsUser = User::where('mpin', $request->mpin)->exists();
                $mpinExistsAdmission = Admission::where('mpin', $request->mpin)->exists();
            
                if ($mpinExistsUser || $mpinExistsAdmission) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'This mPIN is already taken, please choose another'
                    ]);
                }

        
            // тЬЕ save plain mPIN
            $user->mpin = $request->mpin;
            $user->save();
        
            return response()->json([
                'status'=>'success',
                'message'=>'mPIN saved successfully тЬЕ'
            ]);
        }



}
