<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\WebUser;
use App\Models\User;
use App\Models\Admission;
use App\Models\ClassType;
use App\Models\NotificationToken;
use App\Models\FirebaseToken;
use App\Models\BillCounter;
use App\Models\UserDocument;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\WalletDetail;
use App\Models\ForgotOtps;
use App\Models\NewsLetter;
use App\Models\EmailTamplate;
use Validator;
use Hash;
use File;
use App;
use URL;
use DB;
use Image;
use Carbon;
use Str;
use App\Helpers\helpers;
use Mail;
use Illuminate\Support\Facades\Log;


class UserController extends BaseController
{
public function saveDeviceToken(Request $request)
{
    try {
        $attendanceUniqueId = trim((string) ($request->input('attendance_unique_id') ?? $request->input('userId')));
        $modelName = trim((string) $request->input('model'));
        $deviceToken = trim((string) $request->input('device_token'));

        if ($attendanceUniqueId === '' || $deviceToken === '') {
            return response()->json([
                'status' => false,
                'error' => 'attendance_unique_id and device_token are required'
            ], 400);
        }

        $branchId = (int) ($request->input('branch_id') ?: 1);
        $sessionId = (int) ($request->input('session_id') ?: 1);
        $entityType = '';
        $modelLower = strtolower($modelName);

        if ($modelLower === 'admission' || $modelLower === 'student') {
            $entityType = 'student';
        } elseif ($modelLower === 'user' || $modelLower === 'teacher' || $modelLower === 'staff') {
            $entityType = 'teacher';
        }

        // Resolve missing/invalid model by attendance_unique_id lookup.
        if ($entityType === '') {
            $admission = Admission::select('branch_id', 'session_id')
                ->where('attendance_unique_id', $attendanceUniqueId)
                ->first();

            if ($admission) {
                $entityType = 'student';
                $branchId = (int) ($admission->branch_id ?: $branchId);
                $sessionId = (int) ($admission->session_id ?: $sessionId);
            } else {
                $user = User::select('branch_id', 'session_id', 'role_id')
                    ->where('attendance_unique_id', $attendanceUniqueId)
                    ->first();

                if ($user) {
                    $entityType = ((int) ($user->role_id ?? 0) === 3) ? 'student' : 'teacher';
                    $branchId = (int) ($user->branch_id ?: $branchId);
                    $sessionId = (int) ($user->session_id ?: $sessionId);
                }
            }
        }

        if ($entityType === '') {
            // Final fallback keeps API functional even when model/lookup is unavailable.
            $entityType = 'teacher';
        }

        $token = FirebaseToken::where('attendance_unique_id', $attendanceUniqueId)
            ->orderByDesc('id')
            ->first();

        if (empty($token)) {
            $token = new FirebaseToken();
        }

        $token->attendance_unique_id = $attendanceUniqueId;
        $token->entity_type = $entityType;
        $token->branch_id = $branchId;
        $token->session_id = $sessionId;
        $token->device_token = $deviceToken;
        $token->platform = $request->input('platform', 'android');
        $token->save();

        // Keep exactly one active row per attendance_unique_id.
        FirebaseToken::where('attendance_unique_id', $attendanceUniqueId)
            ->where('id', '!=', $token->id)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Device token saved successfully'
        ]);
    } catch (\Throwable $e) {
        Log::error('saveDeviceToken failed', [
            'attendance_unique_id' => $request->input('attendance_unique_id'),
            'userId' => $request->input('userId'),
            'model' => $request->input('model'),
            'platform' => $request->input('platform'),
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'status' => false,
            'error' => 'Unable to save device token'
        ], 500);
    }
}

}
