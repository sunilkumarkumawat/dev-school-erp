<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator; 
use App\Models\Admission;
use App\Models\FirebaseToken;
use App\Models\Notification;
use App\Models\User;
use Session;
use Carbon\Carbon;
use Str;
use Helper;
use Redirect;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller

{
         


public function saveDeviceToken(Request $request)
            {
                $device_token = trim((string) $request->input('device_token'));

                if (empty($device_token)) {
                    return response()->json(['status' => false, 'error' => 'Device token is required'], 400);
                }

                $entityType = Session::get('role_id') === 3 ? 'student' : 'teacher';
                $attendanceUniqueId = '';

                if ($entityType === 'student') {
                    $user = Admission::find(Session::get('id'));
                    if (!$user) {
                        return response()->json(['status' => false, 'error' => 'Unauthorized'], 401);
                    }
                    $attendanceUniqueId = trim((string) ($user->attendance_unique_id ?? ''));
                } else {
                    $user = User::find(Session::get('id'));
                    if (!$user) {
                        return response()->json(['status' => false, 'error' => 'Unauthorized'], 401);
                    }
                    $attendanceUniqueId = trim((string) ($user->attendance_unique_id ?? ''));
                }

                if ($attendanceUniqueId === '') {
                    return response()->json(['status' => false, 'error' => 'Attendance unique id not found'], 422);
                }

                $not = FirebaseToken::where('attendance_unique_id', $attendanceUniqueId)
                    ->orderByDesc('id')
                    ->first();

                if (empty($not)) {
                    $not = new FirebaseToken;
                }

                $not->attendance_unique_id = $attendanceUniqueId;
                $not->entity_type = $entityType;
                $not->branch_id = Session::get('branch_id') ?: 1;
                $not->session_id = Session::get('session_id') ?: 1;
                $not->device_token = $device_token;
                $not->platform = $request->input('platform', 'web');
                $not->save();

                FirebaseToken::where('attendance_unique_id', $attendanceUniqueId)
                    ->where('id', '!=', $not->id)
                    ->delete();

                return response()->json(['status' => true, 'message' => 'Device token saved!']);
            }

    
    
        public function notification(Request $request)
        {
            $tokens = FirebaseToken::pluck('device_token')->toArray();
            $array = [
                "title" => "Hello World!",
                "body" => "This is a notification with image and icon.",
                "tokens" => $tokens,
                "image" => "https://demo3.rusoft.in/schoolimage/setting/left_logo/17502424226852947652a461748931241_xbv2FRwzun.jpeg",
                "icon" => "https://rukmanisoftware.com/public/assets/img/header-logo.png",
                "data" => [
                    "customKey" => "customValue"
                ]
            ];
               // dd($array);

            $response = Http::withoutVerifying() // <— This bypasses SSL check
                ->post('https://147.93.102.162:8443/send-notification', $array);
        
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $response->body()
                ], $response->status());
            }
        }
        
        public function notificationFatch(Request $request){
    if ($request->has('hide_id')) {
        Notification::where('id', $request->hide_id)->where('admission_id', Session::get('id'))->update(['show_status' => 0]);
    }

    $notifications = Notification::where('admission_id', Session::get('id'))->where('show_status', 1)->orderBy('message_seen', 'ASC')->orderBy('id', 'desc')->take(20)->get();
    return view('notifications.notification_list', compact('notifications'));
}


public function notificationDetailStu(Request $request , $id){
    
    Notification::where('id', $id)->where('admission_id', Session::get('id'))->update(['message_seen' => 1]); 
    $notification = Notification::find($id);
    if (!$notification) abort(404, 'Notification not found');
    return view('notifications.notification_details', compact('notification'));
}


}
