<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserPermission extends Model
{
        use SoftDeletes;
	protected $table = "user_permission";
	protected $fillable = [
        'user_id',
        'sidebar_id',
        'sidebar_name',
        'sub_sidebar_id',
        'add',
        'edit',
        'view',
        'delete',
        'print',
        'status',
    ];
}