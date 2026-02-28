<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Setting extends Model
{
        use SoftDeletes;
	protected $table = "settings"; //table name
	protected $fillable = [
        'user_id',
        'session_id',
        'current_active_session_id',
        'role_id',
        'branch_id',
        'name',
        'mobile',
        'gmail',
        'country_id',
        'state_id',
        'address',
    ];
	public function City()
    {
        return $this->belongsTo('App\Models\City','city_id');
    }
    
    	public function Country()
    {
        return $this->belongsTo('App\Models\Country','country_id');
    }
    
    	public function State()
    {
        return $this->belongsTo('App\Models\State','state_id');
    }
    	public function Account()
    {
        return $this->belongsTo('App\Models\Account','account_id');
    }
    
   
    
}

