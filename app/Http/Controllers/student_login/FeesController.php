<?php

namespace App\Http\Controllers\student_login;
use App\Models\User;
use App\Models\State;
use App\Models\fees\FeesAssign;
use App\Models\FeesCollect;
use App\Models\Admission;
use App\Models\Master\Branch;
use App\Models\Master\Homework;
use Illuminate\Validation\Validator; 
use App\Models\Master\PaymentMode;
use App\Models\FeesDetail;
use App\Models\fees\FeesDetailsInvoices;
use App\Models\fees\FeesAssignDetail;
use Session;
use Hash;
use Str;
use Redirect;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Detection\MobileDetect;


class FeesController extends Controller
{
 
  
               public function feesHistory(Request $request)
{
    $admission_id = Session::get('id');
    $currentAdmission = Admission::find($admission_id);

    if (!$currentAdmission) {
        return back()->withErrors(['error' => 'Admission record not found.']);
    }

    $ActiveSession_id = $request->session_id ?? Session::get('session_id');

    $admissions = Admission::where('unique_system_id', $currentAdmission->unique_system_id)
        ->get(['id', 'unique_system_id', 'session_id']);

    $filteredAdmission = $admissions->where('session_id', $ActiveSession_id)->first();

    $getFees = collect();

    if (!empty($filteredAdmission)) {

        // Assign fees
        $getFees = FeesAssignDetail::select(
            'fees_assign_details.*',
            'fees_group.name as group_name')
        ->join('fees_group', 'fees_group.id', '=', 'fees_assign_details.fees_group_id')
        ->where('admission_id', $filteredAdmission->id)
        ->get();

      
    }
    
    // Initialize
    $grand_total = 0;
    $Paids = 0;
    $Discount = 0;
    $Fine = 0;
    $balances = 0;

    foreach($getFees as $item) {

        $pad = \App\Models\FeesDetail::where('fees_type',0)
                ->where('status',0)
                ->where('admission_id',$admission_id)
                ->where('fees_group_id',$item->fees_group_id)
                ->sum('total_amount');

        $balance = $item->fees_group_amount - $pad;

        // Fine calculation
        $fine_amt = 0;
        if (!empty($item->installment_due_date)) {
            if ($item->installment_due_date < date('Y-m-d')) {
                $fine_amt = ($balance / 100) * $item->installment_fine;
            }
        }

        $grand_total += $item->fees_group_amount;
        $Paids       += $pad;
        $Discount    += $item->discount;
        $Fine        += $fine_amt;
        $balances    += $balance;
    }
// SUMMARY DATA
    $summary = [
        "previousSessionDue" => 0,             // अगर चाहें तो previous session से fetch कर दूँ
        "currentSessionDue"  => $balances,
        "lateFees"           => $Fine,
        "totalFees"          => $grand_total,
    ];

  
    return view('student_login.fees_history', [
        'getFees' => $getFees,
        'summary' => $summary,
        
    ]);
}



        
                 
                 


    
}
