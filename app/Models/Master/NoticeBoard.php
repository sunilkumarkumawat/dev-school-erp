<?php

namespace App\Models\Master;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class NoticeBoard extends Model
{
        use SoftDeletes;
	protected $table = "notice_board"; //table name
	
}