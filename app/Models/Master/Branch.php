<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Branch extends Model
{
        use SoftDeletes;
	protected $table = "branch"; //table name
	  protected $fillable = [
        'user_id',
        'session_id',
        'branch_code',
        'branch_name',
        'branch_count',
        'contact_person',
        'mobile',
        'email',
        'address',
        'branch_sidebar_id',
        'sidebar_sub_id',
        'expert_name',
        'sms_srvc',
        'email_srvc',
        'whatsapp_srvc',
    ];
}