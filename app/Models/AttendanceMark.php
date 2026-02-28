<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceMark extends Model
{
    protected $table = 'attendance_marks';

    protected $fillable = [
        'unique_id',
        'entity_type',
        'date',
        'in_time',
        'out_time',
        'status',
        'branch_id',
        'session_id',
        'created_by',
    ];
}
