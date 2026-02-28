<?php

namespace App\Http\Controllers\student_login;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator; 
use App\Models\Admission;
use App\Models\NotificationToken;
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

        public function notificationFatch(Request $request){
            if ($request->has('hide_id')) {
                Notification::where('id', $request->hide_id)->where('admission_id', Session::get('id'))->update(['show_status' => 0]);
            }
        
            $notifications = Notification::where('admission_id', Session::get('id'))->where('show_status', 1)->orderBy('message_seen', 'ASC')->orderBy('id', 'desc')->take(20)->get();
            return view('student_login.notification.notification_list', compact('notifications'));
        }
        
        
        public function notificationDetailStu(Request $request , $id){
            
            Notification::where('id', $id)->where('admission_id', Session::get('id'))->update(['message_seen' => 1]); 
            $notification = Notification::find($id);
            if (!$notification) abort(404, 'Notification not found');
            return view('student_login.notification.notification_details', compact('notification'));
        }


}
