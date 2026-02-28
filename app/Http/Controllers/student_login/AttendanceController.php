<?php

namespace App\Http\Controllers\student_login;

use Illuminate\Http\Request;
use App\Models\StudentAttendance;
use App\Http\Controllers\Controller;
use App\Models\Admission;
use Session;

class AttendanceController extends Controller
{

    
    public function view()
    {
     
        return view('student_login.attendence'); 
    }




    public function getAttendanceDates(Request $request)
    {
        $admission_id = $request->admission_id;
        $month = $request->month;
        $year = $request->year;
        $user = $request->user ?? 'Student';

        if(empty($admission_id) || empty($month) || empty($year)) {
            return response()->json(['data' => [], 'total' => []]);
        }

        $data = [];

        if ($user == 'Student') {
            $data = StudentAttendance::select(
                'student_attendance.*',
                'attendance_status.name as atten_status'
            )
            ->leftJoin('attendance_status', 'student_attendance.attendance_status_id', 'attendance_status.id')
            ->where('student_attendance.session_id', Session::get('session_id'))
            ->where('student_attendance.branch_id', Session::get('branch_id'))
            ->where('student_attendance.admission_id', $admission_id)
            ->whereMonth('student_attendance.date', '=', $month)
            ->whereYear('student_attendance.date', '=', $year)
            ->get();
        }

        $attendance = [];
        $total = [
            'Present' => 0,
            'Absent' => 0,
            'Holiday' => 0,
            'Leave' => 0,
            'Event' => 0,
            'Exam' => 0
        ];

        foreach ($data as $item) {
                $date = date('Y-m-d', strtotime($item->date));
                $attendance[$date] = $item->atten_status;
          

            switch ($item->atten_status) {

                case 'In':
                case 'Present':
                case 'Out':
                    $total['Present']++;
                    break;

                case 'Absent':
                    $total['Absent']++;
                    break;

                case 'Holiday':
                    $total['Holiday']++;
                    break;

                case 'Leave':
                    $total['Leave']++;
                    break;

                case 'Event':
                    $total['Event']++;
                    break;

                case 'Exam':
                    $total['Exam']++;
                    break;
            }
        }

        return response()->json([
            'data' => $attendance,
            'total' => $total
        ]);
    }
}
