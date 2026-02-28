<?php

namespace App\Models;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class MessageQueue extends Model
{
        use SoftDeletes;
			protected $fillable = [
        'user_id',
        'branch_id',
        'session_id',
        'sent_at',
        'message_id',
        'response',
        'category',
        'receiver_number',
        'content',
        'media_link',
        'file_name',
        'message_type',
        'message_status',
        'submitted_at'
];
	//protected $table = "message_queue"; //table name

}