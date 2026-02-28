<?php

namespace App\Http\Controllers\student_login;
use Illuminate\Validation\Validator;
use App\Models\exam\Question;
use App\Models\exam\Exam;
use App\Models\exam\AssignExam;
use App\Models\exam\FillMinMaxMarks;
use App\Models\exam\FillMarks;
use App\Models\Admission;
use App\Models\exam\ExaminationSchedule;
use App\Models\exam\ExaminationScheduleDetail;
use App\Models\examoffline\PerformanceMarks;
use DB;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassType;
use App\Models\Master\TeacherSubject;
use Session;
use Helper;
use Str;
use Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExamsController extends Controller
{
public function resultCard(Request $request)
{
    $admission_id = Session::get('id');
    $class_type_id = Session::get('class_type_id');

    $examResultRaw = DB::table('fill_marks')
        ->where('admission_id', $admission_id)
        ->where('class_type_id', $class_type_id)
        ->whereNull('deleted_at')
        ->get();

    $examResult = $examResultRaw->groupBy('exam_id');

    $allRanks = [];
    foreach ($examResult as $exam_id => $results) {
        $students = DB::table('fill_marks')
            ->select('admission_id', DB::raw('SUM(student_marks) as total_marks'))
            ->where('exam_id', $exam_id)
            ->where('class_type_id', $class_type_id)
            ->groupBy('admission_id')
            ->orderByDesc('total_marks')
            ->get();

        $rank = null;
        foreach ($students as $key => $stu) {
            if ($stu->admission_id == $admission_id) {
                $rank = $key + 1;
                break;
            }
        }
        $allRanks[$exam_id] = $rank;
    }

    // Prepare chart data
    $chartData = [];
    foreach ($examResult as $exam_id => $results) {
        foreach ($results as $res) {
            $subject = DB::table('subject')->find($res->subject_id);
            $percent = $res->exam_maximum_marks > 0
                ? round(($res->student_marks / $res->exam_maximum_marks) * 100, 2)
                : 0;

            $chartData[$exam_id][] = [
                'subject_id' => $res->subject_id,
                'subject_name' => $subject->name ?? 'Unknown',
                'percent' => $percent
            ];
        }
    }

    // ✅ Pass all variables to view
    return view('student_login.exams.view', [
        'examResult' => $examResult,
        'allRanks' => $allRanks,
        'chartData' => $chartData
    ]);
}





}
