<?php

namespace App\Http\Controllers\initial;
use Illuminate\Validation\Validator; 
use App\Helpers\helper;
use Exception;
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
use App\Services\RuntimeSync;

class InitialController extends Controller
{

    public function helpAndUpdate(Request $request){
        $softwareTokenNo = env('SOFTWARE_TOKEN_NO');
        return view('initial.helpAndUpdateView', ['softwareTokenNo' => $softwareTokenNo]);
    }
    
    public function backup(RuntimeSync $RuntimeSync, Request $request)
    {
        try {
    
            // 🔥 PICK CREDS FROM ADMIN HEADERS
            $driveConfig = [
                'client_id'     => $request->header('X-GDRIVE-CLIENT-ID'),
                'client_secret' => $request->header('X-GDRIVE-CLIENT-SECRET'),
                'refresh_token' => $request->header('X-GDRIVE-REFRESH-TOKEN'),
                'root_folder_id'     => $request->header('X-GDRIVE-FOLDER-ID'),
            ];
    
            foreach ($driveConfig as $k => $v) {
                if (empty($v)) {
                    throw new \Exception("Missing Google Drive credential: {$k}");
                }
            }
            
            $projectName = $request->header('X-PROJECT-NAME');
    
            if (empty($projectName)) {
                throw new \Exception('Project name missing');
            }
            $projectTokenNo = $request->header('X-PROJECT-TOKEN-NO');
    
            if (empty($projectTokenNo)) {
                throw new \Exception('Project Token No missing');
            }
            $keepLast = (int) $request->header('X-BACKUP-KEEP', 3);
            $keepLast = max(1, min($keepLast, 7)); 
    
    
            // ⚠️ NEVER use dd() here in production
            set_time_limit(0);
    
            $zipPath = $RuntimeSync->backupProject($projectTokenNo);
    
            if (!file_exists($zipPath)) {
                throw new \Exception('Backup zip not created');
            }
    
            $fileSize = filesize($zipPath);
          
            if ($fileSize <= 0) {
                throw new \Exception('Backup zip is empty');
            }
    
            // upload to Google Drive
            $uploadResponse = $RuntimeSync->uploadToGoogleDrive($zipPath, $driveConfig, $projectName, $keepLast);
    
            if (!isset($uploadResponse['id'])) {
                throw new \Exception('Google Drive upload failed');
            }
            
            unlink($zipPath);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Backup completed',
                'file' => [
                    'name' => basename($zipPath),
                    'size' => $fileSize,
                    'drive_file_id' => $uploadResponse['id'],
                    'download_url' =>
                        'https://drive.google.com/file/d/' . $uploadResponse['id'] . '/view'
                ]
            ]);
    
        } catch (\Throwable $e) {
    
            // 🔥 THIS IS THE KEY
            return response()->json([
                'status' => 'failed',
                'error_type' => class_basename($e),
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ], 500);
        }
    }

    public function updateInitialConfig(Request $request)
    {
        try {

            $path = config_path('initialConfig.php');

            // 1️⃣ Prepare new config array
            $configData = [
                'instant' => [
                    'enabled' => (bool) $request->input('enabled', false),
                    'type'    => $request->input('type', 'header'),
                    'title'   => $request->input('title', 'This is title'),
                    'message' => $request->input('message', '🚀 New feature launched!'),
                ],
            ];

            // 2️⃣ Convert array to PHP config file format
            $content = "<?php\n\nreturn " . var_export($configData, true) . ";\n";

            // 3️⃣ Create or Update file
            File::put($path, $content);

            return response()->json([
                'status'  => true,
                'message' => 'Initial config updated successfully',
                'data'    => $configData
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Failed to update initial config',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

}