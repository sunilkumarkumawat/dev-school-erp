<?php

namespace App\Models\exam;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ExamResultUpdate extends Model
{
        use SoftDeletes;
	protected $table = "exam_result_updates"; //table name
}