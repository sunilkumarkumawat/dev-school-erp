<?php

namespace App\Http\Controllers\offline_exam;

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
use App\Exports\MarksTemplateExport;
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

use Maatwebsite\Excel\Facades\Excel;

class MarksImportController extends Controller
{

 
public function FillMarksByExcel(Request $request)
{
    $subjects = [];
    $students = [];
    $examlist = [];
    $selectedClass = $request->class_type_id ?? null;

    if ($selectedClass) {

        // Subjects (class wise)
        $subjects = Subject::where('branch_id', Session::get('branch_id'))
            ->where('session_id', Session::get('session_id'))->where('class_type_id', $selectedClass)
            ->orderBy('sort_by','ASC')
            ->get();
  $examlist = AssignExam::select(
                "assign_exams.*",
                "exam.id as exam_id",
                "exam.name as exam_name"
            )
                ->leftjoin("exams as exam", "assign_exams.exam_id", "exam.id")
                ->where("assign_exams.class_type_id", $selectedClass)
                ->where('assign_exams.session_id',Session::get("session_id"))
                 ->where('exam.deleted_at',null)->where("assign_exams.branch_id", Session::get("branch_id"))->orderBy('exam.id','ASC')
                ->get();
        // Students (branch + class wise)
        $students = Admission::where('branch_id', Session::get('branch_id'))
            ->where('session_id', Session::get('session_id'))
            ->where('status',1)
            ->where('class_type_id', $selectedClass)
            ->orderBy('first_name','ASC')
            ->get();
    }

    return view('examination.offline_exam.fill_marks_by_excel.fill_marks_by_excel',compact('subjects', 'students', 'selectedClass', 'examlist'));
}

 public function previewAjax(Request $request)
    {
        $request->validate([
            'excel_file'    => 'required|mimes:xlsx,csv',
            'class_type_id' => 'required',
            'exam_id' => 'required'
        ]);

        // 🔥 Direct read Excel into array (NO Import class)
        $sheets = Excel::toArray([], $request->file('excel_file'));
        $rows   = $sheets[0] ?? [];

        return response()->json([
            'status' => true,
            'rows'   => $rows
        ]);
    }

public function saveAjax(Request $request)
{
    $request->validate([
        'rows'          => 'required|array',
        'class_type_id' => 'required',
       // 'exam_id' => 'required'
    ]);
//dd($request);
    $rows     = $request->rows;
    $classId  = $request->class_type_id;
    $exam_id  = $request->exam_id;

    $branchId  = Session::get('branch_id');
    $sessionId = Session::get('session_id');
    $userId    = Session::get('id');

    $subjects = Subject::where('class_type_id', $classId)
        ->orderBy('sort_by','ASC')
        ->get()
        ->values();

    /* =====================
       ROW 2 = TOTAL MARKS
    ====================== */
    $totalMarksRow = $rows[1] ?? [];
    $subjectMaxMarks = [];

    foreach ($subjects as $i => $subject) {
        if (is_numeric($totalMarksRow[$i+2] ?? null)) {
            $subjectMaxMarks[$subject->id] = $totalMarksRow[$i+2];
        }
    }

    DB::beginTransaction();

    try {

        /* =====================
           ROW 3+ = STUDENTS
        ====================== */
        for ($r = 2; $r < count($rows); $r++) {

            $row = $rows[$r];

            // ✅ Student ID
            $studentId = $row[0] ?? null;
            if (!$studentId) continue;

            $student = Admission::find($studentId);
            if (!$student) continue;

            foreach ($subjects as $i => $subject) {

                $marks = $row[$i + 2] ?? null;
                if ($marks === null || $marks === '') continue;

                /* =====================
                   MIN MAX UPSERT
                ====================== */
                $minMax = DB::table('fill_min_max_marks')->updateOrInsert(
                    [
                        'class_type_id' => $classId,
                        'subject_id'    => $subject->id,
                        'branch_id'     => $branchId,
                        'session_id'    => $sessionId,
                    ],
                    [
                        'user_id'              => $userId,
                        'exam_id'              => $exam_id,
                        'exam_maximum_marks'   => $subjectMaxMarks[$subject->id] ?? 100,
                        'created_at'           => now(),
                        'updated_at'           => now(),
                    ]
                );

                // fetch minMax record
                $minMaxRow = DB::table('fill_min_max_marks')
                    ->where('class_type_id',$classId)
                    ->where('subject_id',$subject->id)
                    ->where('branch_id',$branchId)
                    ->where('session_id',$sessionId)
                    ->first();

                /* =====================
                   FILL MARKS UPSERT
                ====================== */
                DB::table('fill_marks')->updateOrInsert(
                    [
                        'admission_id' => $student->id,
                        'subject_id'   => $subject->id,
                        'exam_id'      => $exam_id,
                    ],
                    [
                        'user_id'               => $userId,
                        'branch_id'             => $branchId,
                        'session_id'            => $sessionId,
                        'fill_min_max_marks_id' => $minMaxRow->id,
                        'class_type_id'         => $classId,
                        'student_marks'         => $marks,
                        'exam_maximum_marks'    => $minMaxRow->exam_maximum_marks,
                        'updated_by'            => $userId,
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ]
                );
            }
        }

        DB::commit();

        return response()->json([
            'status'  => true,
            'message' => 'Marks saved successfully'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'status'  => false,
            'message' => $e->getMessage()
        ], 500);
    }
}





}
