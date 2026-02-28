<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FillMinMaxMarks extends Model
{
    use SoftDeletes;

    protected $table = 'fill_min_max_marks';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'user_id',
        'branch_id',
        'session_id',
        'exam_id',
        'class_type_id',
        'subject_id',
        'stream_id',
        'exam_minimum_marks',
        'exam_maximum_marks'
    ];

    protected $dates = ['deleted_at'];

    /* =======================
       RELATIONSHIPS
       ======================= */

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }

    public function exam()
    {
        return $this->belongsTo(\App\Models\Exam::class, 'exam_id');
    }

    public function fillMarks()
    {
        return $this->hasMany(\App\Models\Exam\FillMarks::class, 'fill_min_max_marks_id');
    }
}
