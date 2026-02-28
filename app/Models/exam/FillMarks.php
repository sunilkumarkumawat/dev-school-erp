<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FillMarks extends Model
{
    use SoftDeletes;

    protected $table = 'fill_marks';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'user_id',
        'branch_id',
        'session_id',
        'fill_min_max_marks_id',
        'exam_id',
        'admission_id',
        'class_type_id',
        'subject_id',
        'student_marks',
        'exam_maximum_marks',
        'updated_by'
    ];

    /**
     * Dates for soft delete
     */
    protected $dates = ['deleted_at'];

    /* =======================
       RELATIONSHIPS (Optional but Useful)
       ======================= */

    public function student()
    {
        return $this->belongsTo(\App\Models\Admission::class, 'admission_id');
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }

    public function exam()
    {
        return $this->belongsTo(\App\Models\Exam::class, 'exam_id');
    }
}
