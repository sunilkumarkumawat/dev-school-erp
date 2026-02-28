<?php

namespace App\Models;

use App\Models\User;
use Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Expense extends Model
{
    use SoftDeletes;
        
    protected $fillable = [
        'user_id', 'session_id', 'branch_id', 'name', 'date', 'quantity',
        'rate', 'amount', 'payment_mode_id', 'total_amt', 'attachment', 'description'
    ];
	protected $table = "expenses"; //table name
   
    public static function totalExpense(){
        $data=Expense::where('branch_id',Session::get('branch_id'))->where('session_id',Session::get('session_id'))->sum('amount');
        return $data;
    }
    
    public static function thisMonthExpense(){
        $data=Expense::where('branch_id',Session::get('branch_id'))->where('session_id',Session::get('session_id'))->whereMonth('date',date('m'))->sum('amount');
        return $data;
    }
    
    public static function todayExpense(){
        $data=Expense::where('branch_id',Session::get('branch_id'))->where('session_id',Session::get('session_id'))->where('date',date('Y-m-d'))->sum('amount');
        return $data;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
