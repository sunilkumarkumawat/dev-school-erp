<?php

namespace App\Models;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Notification extends Model
{
        use SoftDeletes;
	protected $table = "notifications"; //table name

    
}