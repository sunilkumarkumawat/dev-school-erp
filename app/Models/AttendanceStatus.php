<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AttendanceStatus extends Model
{
        use SoftDeletes;
	protected $table = "attendance_status"; //table name
	

    
}