<?php

namespace App\Models\Master;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
class UploadHomework extends Model
{
        use SoftDeletes;
	protected $table = "upload_homeworks"; //table name

    public function Admission(){
       return $this->belongsTo('App\Models\Admission','admission_id');
    }	

    public function ClassType(){
        return $this->belongsTo('App\Models\ClassType','class_type_id');
    }

     public function Section(){
        return $this->belongsTo('App\Models\Master\Section','section_id');
    } 

   public static function countTodayAssignment()
{
    $data = UploadHomework::where('session_id', Session::get('session_id'))
        ->where('branch_id', Session::get('branch_id'))
        ->where('submission_date', date('Y-m-d'))
        ->groupBy('admission_id');

    if (Session::get('role_id') == 2) {

        $classTeacherOf = DB::table('teachers')
            ->where('id', Session::get('teacher_id'))
            ->whereNull('deleted_at')
            ->first();

        $classes = [];

        if (!empty($classTeacherOf->class_type_id)) {

            if (is_string($classTeacherOf->class_type_id)) {
                $classes = unserialize($classTeacherOf->class_type_id);
            } elseif (is_array($classTeacherOf->class_type_id)) {
                $classes = $classTeacherOf->class_type_id;
            }
        }

        if (!empty($classes)) {
            $data->whereIn('class_type_id', $classes);
        }
    }

    return $data->count();
}










  
}