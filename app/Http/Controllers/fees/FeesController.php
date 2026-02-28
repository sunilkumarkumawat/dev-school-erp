<?php
 
namespace App\Http\Controllers\fees;

use Illuminate\Validation\Validator;
use App\Models\Student;
use App\Models\ClassType;
use App\Models\Admission;
use App\Models\BillCounter;
use App\Models\SmsSetting;
use App\Models\Account;
use App\Models\FeesStructure;
use App\Models\FeesGroup;
use App\Models\FeesMaster;
use App\Models\FeesDiscount;
use App\Models\FeesCollect;
use App\Models\Sessions;
use App\Models\PermissionMessages;
use PDF;
use App\Models\fees\FeesAdvance;
use App\Models\fees\FeesAdvanceHistory;
use App\Models\FeesDetail;
use App\Models\Invoice;
use App\Models\StoreItem;
use App\Models\StoreItemRequest;
use App\Models\StoreBillingDetail;
use App\Models\Master\MessageTemplate;
use App\Models\Master\Branch;
use App\Models\Master\PaymentMode;
use App\Models\Setting;
use App\Models\fees\FeesAssign;
use App\Models\fees\FeesDetailsInvoices;
use App\Models\fees\FeesAssignDetail;
use Session;
use Helper;
use Hash;
use Str;
use Redirect;
use Response;
use Auth;
use File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
use App\Http\Controllers\WhatsappController;
use DB;
class FeesController extends Controller

{


    
    
            public function FeesGroupRemoveDuplicateEntries(){
                    $allEntries = FeesAssignDetail::where('session_id', Session::get('session_id'))
                        ->where('branch_id', Session::get('branch_id'))
                        ->orderBy('admission_id')
                        ->orderBy('fees_group_id')
                        ->orderBy('id') // oldest entry first
                        ->get()
                        ->groupBy('admission_id');
                
                    $deletedCount = 0;
                
                    foreach ($allEntries as $admission_id => $entries) {
                        $groupTracker = [];
                        $duplicateFeesAssignIds = [];
                
                        foreach ($entries as $entry) {
                            if (in_array($entry->fees_group_id, $groupTracker)) {
                                $duplicateFeesAssignIds[] = $entry->id; // duplicate
                            } else {
                                $groupTracker[] = $entry->fees_group_id;
                            }
                        }
                
                        // Delete duplicate entries
                        if (!empty($duplicateFeesAssignIds)) {
                            FeesAssignDetail::whereIn('id', $duplicateFeesAssignIds)->delete();
                            $deletedCount += count($duplicateFeesAssignIds);
                        }
                    }
                
                    //echo "✅ $deletedCount duplicate fee group entries deleted from all students.";
                }

            public function feeDashboard(){
                return view('fees/fee_dashboard');
            }
  
            public function addFees(Request $request){
                $search['name'] = $request->name;
                $search['search_type'] = $request->search_type;
                $search['admission_type_id'] = $request->admission_type_id ?? '';
                $search['class_type_id'] = !empty($request->class_type_id) ? $request->class_type_id : 0;
                if ($request->isMethod('post')) {
                     $request->validate([
                        'class_type_id' => 'required',
                    ]);
                    $value = $request->name;
                    if ($request->class_type_id > 0 || $request->name != '' ) {
                        $data =  Admission::with('ClassTypes')->where('status',1)->where('session_id', Session::get('session_id'))->where('school','=',1);
                            $data = $data->where('branch_id', Session::get('branch_id'));
                       
                       if ($request->search_type != '') {
                            $data = $data->where($request->search_type,'LIKE', '%' . $request->name . '%' );
                        }
                    if ($request->class_type_id != '') {
                        $data = $data->where("class_type_id", $request->class_type_id);
                    }
                    if ($request->admission_type_id != '') {
                        $data = $data->where("admission_type_id", $request->admission_type_id);
                    }
                   
                    $allstudents = $data->orderBy('id', 'ASC')->get();
                    } 
                    else {
                        return redirect::to('Fees/add')->with('error', 'Please type the input value  !');
                    }
                    return  view('fees.fees_collect.add', ['data' => $allstudents, 'search' => $search]);
                }
                return  view('fees.fees_collect.add', ['search' => $search]);
            }
                


public function feesLedgerCollect(Request $request)
{
    $search['ledger_no'] = $request->ledger_no ?? ""; 

    if ($request->isMethod('post')) {

        $request->validate([
            'ledger_no' => 'required',
        ]);

        // 🔹 Students
        $students = Admission::leftJoin('class_types', 'class_types.id', '=', 'admissions.class_type_id')
            ->select(
                'admissions.id',
                'admissions.first_name',
                'admissions.ledger_no',
                'class_types.name as class_name'
            )
            ->where('admissions.branch_id', Session::get('branch_id'))
            ->where('admissions.session_id', Session::get('session_id'))
            ->where('admissions.status', 1)
            ->where('admissions.ledger_no', $request->ledger_no)
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'Ledger Not Found');
        }

        $studentIds = $students->pluck('id');

        // 🔹 Paid Records
        $payments = FeesDetail::leftJoin('fees_group', 'fees_group.id', '=', 'fees_detail.fees_group_id')
            ->select(
                'fees_detail.admission_id',
                'fees_detail.created_at',
                'fees_detail.receipt_no',
                'fees_detail.total_amount',
                'fees_group.name as group_name'
            )
            ->whereIn('fees_detail.admission_id', $studentIds)
            ->where('fees_detail.total_amount', '>', 0)
            ->orderBy('fees_detail.created_at', 'desc')
            ->get()
            ->groupBy('admission_id');

        foreach ($students as $student) {
            $student->paid_records = $payments[$student->id] ?? collect();
        }

        // 🔹 SUMMARY CALCULATION (Optimized)

        $assignSummary = DB::table('fees_assign_details')
            ->whereIn('admission_id', $studentIds)
            ->selectRaw('
                SUM(fees_group_amount) as total_assign,
                SUM(discount) as total_assign_discount
            ')
            ->first();

        $paidSummary = DB::table('fees_detail')
            ->whereIn('admission_id', $studentIds)
            ->selectRaw('
                SUM(total_amount) as total_paid,
                SUM(discount) as total_paid_discount
            ')
            ->first();

        $annualFees     = $assignSummary->total_assign ?? 0;
        $annualdiscount = $assignSummary->total_assign_discount ?? 0;

        $paidAmount     = $paidSummary->total_paid ?? 0;
        $paidDiscount   = $paidSummary->total_paid_discount ?? 0;

        // 🔹 Total Discount
        $totalDiscount = $annualdiscount + $paidDiscount;

        // 🔹 Final Payable
        $finalAmount = $annualFees - $totalDiscount;

        // 🔹 Outstanding
        $outstanding = $finalAmount - $paidAmount;

        // 🔹 Progress %
        $progress = $finalAmount > 0
            ? round(($paidAmount / $finalAmount) * 100)
            : 0;

        return view('fees.fees_collect.byLedger', compact(
            'students',
            'search',
            'annualFees',
            'paidAmount',
            'totalDiscount',
            'finalAmount',
            'outstanding',
            'progress'
        ));
    }

    return view('fees.fees_collect.byLedger', [
        'search' => $search,
        'students' => collect()
    ]);
}


            





public function siblingPreview(Request $request)
{
    $request->validate([
        'ledger_no'    => 'required',
        'total_amount' => 'required|numeric|min:1',
        'percentage'   => 'required|numeric|min:1|max:100',
    ]);

    $ledgerNo    = $request->ledger_no;
    $totalAmount = $request->total_amount;
    $percentage  = $request->percentage;

    $students = $this->getStudentsWithDue($ledgerNo);

    // सिर्फ preview flag
    return view('fees.fees_collect.sibling_pay', [
        'students'    => $students,
        'ledgerNo'    => $ledgerNo,
        'totalAmount' => $totalAmount,
        'percentage'  => $percentage,
        'preview'     => true
    ]);
}

public function ledgerPay(Request $request)
{
    $request->validate([
        'ledger_no'    => 'required',
        'total_amount' => 'required|numeric|min:1',
        'percentage'   => 'required|numeric|min:1|max:100',
    ]);

    $ledgerNo    = $request->ledger_no;
    $totalAmount = $request->total_amount;
    $percentage  = $request->percentage;

 $students = Admission::leftJoin('class_types','class_types.id','=','admissions.class_type_id')
        ->select('admissions.id','admissions.first_name','class_types.name as class_name')
        ->where('admissions.ledger_no',$ledgerNo)
        ->where('admissions.branch_id',Session::get('branch_id'))
        ->where('admissions.session_id',Session::get('session_id'))
        ->get();

    foreach ($students as $student) {

        $groups = DB::table('fees_assign_details')
            ->leftJoin('fees_group','fees_group.id','=','fees_assign_details.fees_group_id')
            ->select(
                'fees_assign_details.fees_group_id',
                'fees_group.name as group_name',
                'fees_assign_details.fees_group_amount'
            )
            ->where('fees_assign_details.admission_id',$student->id)
            ->get();

        foreach ($groups as $group) {

            $paid = DB::table('fees_detail')
                ->where('admission_id',$student->id)
                ->where('fees_group_id',$group->fees_group_id)
                ->sum('total_amount');

            $group->remaining = max(0, $group->fees_group_amount - $paid);
        }

        $student->groups = $groups->where('remaining','>',0)->values();
    }

    

    return view('fees.fees_collect.sibling_pay', [
        'students' => $students,
        'totalAmount' => $totalAmount,
        'ledgerNo' => $ledgerNo,
        'percentage' => $percentage
    ]);
}

/* 🔹 Helper Function */
private function getStudentsWithDue($ledgerNo)
{
    $students = Admission::leftJoin('class_types','class_types.id','=','admissions.class_type_id')
        ->select('admissions.id','admissions.first_name','class_types.name as class_name')
        ->where('admissions.ledger_no',$ledgerNo)
        ->where('admissions.branch_id',Session::get('branch_id'))
        ->where('admissions.session_id',Session::get('session_id'))
        ->get();

    foreach ($students as $student) {

        $groups = DB::table('fees_assign_details')
            ->leftJoin('fees_group','fees_group.id','=','fees_assign_details.fees_group_id')
            ->select(
                'fees_assign_details.fees_group_id',
                'fees_group.name as group_name',
                'fees_assign_details.fees_group_amount'
            )
            ->where('fees_assign_details.admission_id',$student->id)
            ->get();

        foreach ($groups as $group) {

            $paid = DB::table('fees_detail')
                ->where('admission_id',$student->id)
                ->where('fees_group_id',$group->fees_group_id)
                ->sum('total_amount');

            $group->remaining = max(0, $group->fees_group_amount - $paid);
        }

        $student->groups = $groups->where('remaining','>',0)->values();
    }

    return $students;
}



































            public function feesGroup(Request $request){
           
                if ($request->isMethod('post')) {
                    $request->validate([
                        'name' => 'required|unique:class_types,name,NULL,id,branch_id,'.Session::get('branch_id').',session_id,'.Session::get('session_id'),
                    ]);
                    $fees_group = new FeesGroup; //model name
                    $fees_group->user_id = Session::get('id');
                    $fees_group->session_id = Session::get('session_id');
                    $fees_group->branch_id = Session::get('branch_id');
                    $fees_group->name = $request->name;
                    $fees_group->fees_refund = $request->fees_refund;
                    $fees_group->save();
                    return redirect::to('feesGroup')->with('message', 'Fees Group Added Successfully !');
                }
                $fees_group_list = FeesGroup::where('session_id', Session::get('session_id'));
               
                    $fees_group_list = $fees_group_list->where('branch_id', Session::get('branch_id'));
                
                
                $fees_group_list = $fees_group_list->orderBy('id', 'ASC')->get();
                return view('fees.fees.feesGroup', ['dataview' => $fees_group_list]);
            }

            public function feesGroupEdit(Request $request, $id){
                $data = FeesGroup::find($id);
                if ($request->isMethod('post')) {
                    $request->validate([
                        'name' => 'required',
                    ]);
                    $data->user_id = Session::get('id');
                    $data->session_id = Session::get('session_id');
                    $data->branch_id = Session::get('branch_id');
                    $data->name = $request->name;
                    $data->fees_refund = $request->fees_refund;
                    $data->save();
                    return redirect::to('feesGroup')->with('message', 'Fees Group Updated Successfully !');
                }
                $fees_group_list = FeesGroup::where('session_id', Session::get('session_id'))->where('branch_id', Session::get('branch_id'))->orderBy('id', 'DESC')->get();
                return view('fees.fees.feesGroupEdit', ['data' => $data, 'dataview' => $fees_group_list]);
            }
            
         
                 public function feesGroupDelete(Request $request)
               {
                    $id = $request->input('delete_id');
                    $feesGroup = FeesGroup::find($id);
                    if (!$feesGroup) {
                        return Redirect::to('feesGroup')->with('error', 'Fees Group not found!');
                    }
                    $feesGroup->delete();
                
                    return Redirect::to('feesGroup')->with('message', 'Fees Group Deleted Successfully!');
                }
                
                
            public function studentFeesOnclick(Request $request)
                    {
                        // Remove duplicate fee entries
                        // $this->FeesGroupRemoveDuplicateEntries();
                    
                        $sessionId = $request->session_id ?? Session::get('session_id');
                        $branchId = Session::get('branch_id');
                    
                        // Get the Bill Counter
                        $billCounter = BillCounter::where('type', 'FeesSlip')
                            ->where('session_id', $sessionId)
                            ->where('branch_id', $branchId)
                            ->first();
                    
                        $billCounterNo = $billCounter ? ($billCounter->counter + 1) : 1;
                    
                        // Get Student Admission Record
                        $checkStudent = Admission::where('session_id', $sessionId)
                            ->where('unique_system_id', $request->unique_system_id)
                            ->first();
                    
                        $admissionId = $checkStudent->id ?? null;
                    
                        if ($checkStudent) {
                            $stuData = $checkStudent;
                        } else {
                            $stuData = ['first_name' => 'not_found_not_found', 'class_type_id' => 'not'];
                        }
                    
                        // Get previous sessions (<= current session)
                        $previousSessionIds = Admission::where('unique_system_id', $request->unique_system_id)->pluck('session_id');
                        $sessions = Sessions::whereIn('id', $previousSessionIds)
                            ->where('id', '<=', $sessionId)
                            ->orderByDesc('id')
                            ->get();
                    
                        // Prepare fees-related data
                        $feesAssign = FeesAssign::where('session_id', $sessionId)
                            ->where('branch_id', $branchId)
                            ->where('admission_id', $admissionId)
                            ->first();
                    
                        $feesCollect = FeesCollect::where('session_id', $sessionId)
                            ->where('branch_id', $branchId)
                            ->where('admission_id', $admissionId)
                            ->first();
                    
                        $feesDetailsInvoices = FeesDetailsInvoices::where('session_id', $sessionId)
                            ->where('branch_id', $branchId)
                            ->where('admission_id', $admissionId)
                            ->orderByDesc('id')
                            ->get();
                    
                        $feesMaster = FeesMaster::where('session_id', $sessionId)
                            ->where('branch_id', $branchId)
                            ->where('class_type_id', $stuData['class_type_id'])
                            ->get();
                    
                        $stuFeeDet = FeesDetail::with(['PaymentMode', 'Admission', 'FeesCollect'])
                            ->where('session_id', $sessionId)
                            ->where('branch_id', $branchId)
                            ->where('admission_id', $admissionId)
                            ->where('fees_type', 0)
                            ->orderByDesc('id')
                            ->get();
                    
                        $billCounterFinal = BillCounter::where('session_id', $sessionId)
                            ->where('branch_id', $branchId)
                            ->where('type', 'FeesSlip')
                            ->first();
                    
                    
                        // Prepare data array for view
                        $data = [
                            'session_id' => $sessionId,
                            'BillCounter' => $billCounterFinal,
                            'stuData' => $stuData,
                            'sessions' => $sessions,
                            'FeesAssign' => $feesAssign,
                            'FeesCollect' => $feesCollect,
                            'FeesDetailsInvoices' => $feesDetailsInvoices,
                            'FeesMaster' => $feesMaster,
                            'stuFeeDet' => $stuFeeDet,
                        ];
                    
                        // Check if student has fees assigned
                        if (!empty($feesAssign->total_amount)) {
                            return view('fees.fees_collect.student_bill', ['data' => $data]);
                        }
                    
                        // No data found case
                        return response()->json(0);
                    }


 
            
            public function inventoryPaySubmit(Request $request){
                $enteredAmount = (Int)$request->get('enteredAmount');
                $collectedData = $request->get('collectedData');
                // Sort the array by 'pending' in ascending order
                usort($collectedData, function ($a, $b) {
                    return $a['pending'] - $b['pending'];
                });
                foreach ($collectedData as $item) {
                    $pending = (Int)($item['pending'] ?? 0);
                    $receipt = $item['receipt'];
                    $admissionId = $item['admission_id'];
                    if ($enteredAmount >= $pending) {
                        // Deduct the full pending amount and save to the database
                        $this->saveStoreReceipt($admissionId, $receipt, $pending);
                        $enteredAmount -= $pending;
                    }
                    else {
                        // Partial payment case, if enteredAmount is less than the pending amount
                        $this->saveStoreReceipt($admissionId, $receipt, $enteredAmount);
                        $enteredAmount = 0;
                        break; // Exit the loop as no more amount left to allocate
                    }
                }
            }
            function saveStoreReceipt($admissionId, $receipt, $amount) {
                if($amount > 0 ){
                    $pay = new StoreBillingDetail;
                    $pay->user_id = Session::get('id');
                    $pay->session_id =Session::get('session_id');
                    $pay->branch_id = Session::get('branch_id');
                    $pay->fees_counter_id = Session::get('counter_id');
                    $pay->admission_id = $admissionId;
                    $pay->receipt_no = $receipt;
                    $pay->amount = $amount;
                    $pay->date = date('Y-m-d');
                    $pay->save();
                }
            }
        
            public function studentPaySubmit(Request $request){ 
               
                $cheque_image = '';
                $session_id = $request->session_id ?? Session::get('session_id');
                $BillCounter = BillCounter::where('session_id',$session_id)->where('branch_id',Session::get('branch_id'))->where('type', 'FeesSlip')->get()->first();
                $FeesAssign = FeesAssign::where('admission_id',$request->admission_id)->get()->first();
                $fees_details_id =[];
                $slip = "";
                if ($request->isMethod('post')) {
                    //dd($request);
                    $admission_id = $request->admission_id;
                    $data = Admission::where('id',$admission_id)->first();
                    if (!empty($admission_id)) {
                        if (!empty($request->selected_head)) {
                            $counter = !empty($BillCounter->counter) ? $BillCounter->counter : 0;
                            $BillCounter->counter = $counter + 1;
                            $BillCounter->save();
                            foreach($request->selected_head as $key=> $head){
                                if( ((int)$request->amount[$key]) != 0  || ((int)$request->discount_amount[$key]) != 0){
                                    $payOld = FeesCollect::where('admission_id',$admission_id)->first();
                                    if(!empty($payOld)){
                                        $pay = $payOld;
                                        $discount = FeesCollect::where('admission_id',$admission_id)->increment('discount', $request->discount_amount[$key] ?? 0);
                                        $amount = FeesCollect::where('admission_id', $admission_id)->increment('amount', $request->amount[$key] ?? 0);
                                        $payDetail = new FeesDetail; //model name
                                        $payDetail->user_id = Session::get('id');
                                        $payDetail->session_id = $session_id;
                                        $payDetail->branch_id = Session::get('branch_id');
                                        $payDetail->fees_collect_id = $payOld->id;
                                        $payDetail->fees_group_id = $head;
                                        $payDetail->receipt_no  = $request->slip_no;
                                        $payDetail->admission_id = $admission_id; 
                                        $payDetail->paid_amount = $request->amount[$key];
                                        $payDetail->installment_fine = $request->fine[$key];
                                        $payDetail->payment_mode_id = $request->payment_mode_id;
                                        $payDetail->discount = $request->discount_amount[$key];
                                        $payDetail->total_amount = $request->amount[$key]+$request->discount_amount[$key];
                                        $payDetail->status = $request->payment_status;
                                         $payDetail->date = $request->date;
                                        $payDetail->save();  
                                        $fees_details_id[]= $payDetail->id;
                                    }
                                    else{
                                        $pay = new FeesCollect;
                                        $pay->user_id = Session::get('id');
                                        $pay->session_id = $session_id;
                                        $pay->branch_id = Session::get('branch_id');
                                        $pay->admission_id = $request->admission_id;
                                        $pay->fees_assign_id = $FeesAssign->id;
                                        $pay->amount = $request->amount[$key];
                                        $pay->save();
                                        $collect_id = $pay->id;
                                        $payDetail = new FeesDetail; //model name
                                        $payDetail->user_id = Session::get('id');
                                        $payDetail->session_id = $session_id;
                                        $payDetail->branch_id = Session::get('branch_id');
                                        $payDetail->fees_collect_id = $collect_id;
                                        $payDetail->fees_group_id = $head;
                                        $payDetail->receipt_no  = $request->slip_no;
                                        $payDetail->admission_id = $admission_id;
                                        $payDetail->paid_amount = $request->amount[$key];
                                        $payDetail->installment_fine = $request->fine[$key];
                                        $payDetail->discount = $request->discount_amount[$key];
                                        $payDetail->total_amount = $request->amount[$key]+$request->discount_amount[$key];
                                        $payDetail->status = $request->payment_status;
                                        $payDetail->date = $request->date;
                                        $payDetail->payment_mode_id = $request->payment_mode_id;
                                        $payDetail->save();
                                        $fees_details_id[]= $payDetail->id;
                                    }
                                    
                                }
                            }
                        }
                        if(!empty($fees_details_id)){
                            $transaction_slip = '';
                            if ($request->file('payment_receipt')) {
                                $image = $request->file('payment_receipt');
                                $path = $image->getRealPath();
                                $transaction_slip =$image->getClientOriginalName();
                                $destinationPath = env('IMAGE_UPLOAD_PATH') . 'payment_receipt';
                                $image->move($destinationPath, $transaction_slip);
                            }
                            $invoice = new FeesDetailsInvoices();
                            $invoice->user_id = Session::get('id');
                            $invoice->session_id = $session_id;
                            $invoice->branch_id = Session::get('branch_id');
                            $invoice->fees_counter_id = Session::get('fees_counter_id');
                            $invoice->admission_id = $admission_id;
                            $invoice->fees_details_id = implode(',',$fees_details_id );
                            $invoice->payment_date = $request->date; 
                            $invoice->payment_mode = $request->payment_mode_id;
                            $invoice->transaction_id = $request->transition_id;
                            $invoice->bank_name = $request->bank_name;
                            $invoice->invoice_no = $request->slip_no;
                            $invoice->status = $request->payment_status;
                            $invoice->cheque_number = $request->cheque_number;
                            $invoice->cheque_date = $request->cheque_date;
                            $invoice->payment_receipt = $transaction_slip;  
                            $invoice->amount = $request->total_amount;
                            $invoice->total_fine = $request->total_fine;
                            $invoice->discount = $request->discount_given;
                            $invoice->remark = $request->other_fee_remark;
                            $invoice->save();
                            $fees_details_invoice_id = $invoice->id;
                            $slip = $invoice->invoice_no;
                            
 
    if ($request->advance_payment == 'yes') {
        $existingData = FeesAdvance::where('unique_system_id', $data->unique_system_id)->first();
        $balance = 0;
        if (!empty($existingData)) {
            $balance = $existingData->balance - $request->total_amount;
            $existingData->balance = $balance ?? ''; 
            $existingData->save();
            $advancahistory = new FeesAdvanceHistory;
            $advancahistory->debit = $request->total_amount; 
            $advancahistory->user_id = Session::get('id'); 
            $advancahistory->session_id = Session::get('session_id'); 
            $advancahistory->unique_system_id = $data->unique_system_id; 
            $advancahistory->branch_id = Session::get('branch_id'); 
            $advancahistory->date = $request->date; 
            $advancahistory->details = "Amount debited for this Receipt No . =" . $request->slip_no; 
            $advancahistory->fees_advance_id = $existingData->id; 
            $advancahistory->save();
        }
    }

                        }
                    }
                    
                
                    $template =  MessageTemplate::Select('message_templates.*','message_types.slug')
                            ->leftjoin('message_types','message_types.id','message_templates.message_type_id')
                          ->where('message_types.status',1)->where('message_types.slug','fees-collect')->first();
                          
                    $branch = Branch::find(Session::get('branch_id'));
                    $setting = Setting::where('branch_id',Session::get('branch_id'))->first();
                    $payment_mode = PaymentMode::where('id',$request->payment_mode_id)->first();
                    
                    $finalCollectedAmt = (int) ($request->total_amount ?? 0) + (int) ($request->total_fine ?? 0) + (int) ($request->discount_given ?? 0);


                    $arrey1 = array(
                                    '{#name#}',
                                    '{#collect_amount#}',
                                    '{#method#}',
                                    '{#school_name#}');
                    $arrey2 = array(
                                    $request->name,
                                    $finalCollectedAmt,
                                    $payment_mode->name,
                                    $setting->name);

             $invoice_data =  FeesDetailsInvoices::select('fees_details_invoices.*','admissions.first_name',
                    'admissions.last_name','class_types.name as class_name','class_types.id as class_type_id','admissions.father_name',
                    'admissions.admissionNo','payment_modes.name as payment_mode','payment_modes.id as payment_mode_id')
                    ->leftjoin('admissions as admissions', 'admissions.id', 'fees_details_invoices.admission_id')
                    ->leftjoin('class_types','class_types.id','admissions.class_type_id')
                    ->leftjoin('payment_modes','payment_modes.id','fees_details_invoices.payment_mode')
                    ->where('fees_details_invoices.session_id', Session::get('session_id'))
                    ->where('fees_details_invoices.branch_id', Session::get('branch_id'))
                    ->where('fees_details_invoices.id',$fees_details_invoice_id)->first();
                    $explode = explode(',',$invoice_data->fees_details_id);
                    $fess_print = FeesDetail::select('fees_detail.*','payment_modes.name as payment_mode','fees_group.name as fees_group_name')
                        ->leftJoin('payment_modes','payment_modes.id','fees_detail.payment_mode_id')
                        ->leftJoin('fees_collect','fees_collect.id','fees_detail.fees_collect_id')
                        ->leftJoin('fees_group','fees_group.id','fees_detail.fees_group_id')
                        ->whereIn('fees_detail.id',$explode);
                        $fess_print=$fess_print->get();
                   if ($request->has('checkbox_whatsapp')) {     
                    $printPreview = Helper::printPreview('Fees Collect');
                            
                            $pdf = PDF::loadView($printPreview,['data'=>$fess_print,'invoice_data'=>$invoice_data]);
        

                    $file_name = 'Fees Recipt '. $slip . '-' . time() . '.pdf';
                    $destinationPath = env('IMAGE_UPLOAD_PATH').'fees_receipt_pdf/';
                    $file_path = $destinationPath . $file_name;
                    file_put_contents($file_path, $pdf->output());
                    $file_show_path = env('IMAGE_SHOW_PATH').'fees_receipt_pdf/'.$file_name;
        
                    if($branch->whatsapp_srvc != 0){
                        if ($request->mobile != ""){
                            if($template->whatsapp_status != 0){
                                $whatsapp = str_replace($arrey1,$arrey2,$template->whatsapp_content);
                                Helper::sendWhatsappMessage($request->mobile,$whatsapp,$file_show_path);
                                // if(File::exists(env('IMAGE_UPLOAD_PATH').'fees_receipt_pdf/'.$file_name)){
                                //     File::delete(env('IMAGE_UPLOAD_PATH').'fees_receipt_pdf/'.$file_name);
                                // } 
                            }
                        }
                    }
                }
Helper::sendNotification(
 
    'Fee Payment Received', // title
    'We have received your payment of ₹'.$finalCollectedAmt.' on '.$invoice_data->payment_date.' via '.$payment_mode->name.' Thank you!', // body
    'student', // type
    $admission_id // admission_id
);


                }
                $response = $this->callAction('printFeesInvoice', [ 
                    'request' => new Request([
                        'fees_details_invoice_id' => $fees_details_invoice_id,
                    ])
                ]);
                return Response::json(array('status' => 'success','unique_system_id'=>$data->unique_system_id,'session_id' => $data->session_id,'slip'=>$slip,'fees_details_invoice_id'=>$fees_details_invoice_id)); 
            }
            
public function sendReceiptOnWhatsapp(Request $request)
{ 
    $fees_details_invoice_id = $request->fees_details_invoice_id;

    if ($request->isMethod('post')) {

        // Template
        $template = MessageTemplate::select('message_templates.*','message_types.slug','message_types.status as message_type_status')
            ->leftJoin('message_types','message_types.id','message_templates.message_type_id')
            ->where('message_types.slug','fees-collect')
            ->first();

        $branch   = Branch::find(Session::get('branch_id'));
        $setting  = Setting::where('branch_id',Session::get('branch_id'))->first();

        // Invoice + Student Info
        $invoice_data = FeesDetailsInvoices::select(
                'fees_details_invoices.*',
                'admissions.first_name','admissions.last_name','admissions.mobile',
                'class_types.name as class_name',
                'admissions.father_name','admissions.admissionNo',
                'payment_modes.name as payment_mode',
                'admissions.id as admission_id'
            )
            ->leftJoin('admissions','admissions.id','fees_details_invoices.admission_id')
            ->leftJoin('class_types','class_types.id','admissions.class_type_id')
            ->leftJoin('payment_modes','payment_modes.id','fees_details_invoices.payment_mode')
            ->where('fees_details_invoices.session_id',Session::get('session_id'))
            ->where('fees_details_invoices.branch_id',Session::get('branch_id'))
            ->where('fees_details_invoices.id',$fees_details_invoice_id)
            ->first();

        // Student info
        $student_name = $invoice_data->first_name.' '.$invoice_data->last_name;
        $mobile       = $invoice_data->mobile ?? null;
        $payment_mode = $invoice_data->payment_mode;

        // Collected Amount (total_amount + fine - discount)
        $finalCollectedAmt = (int) ($invoice_data->total_amount ?? 0) 
                           + (int) ($invoice_data->total_fine ?? 0) 
                           - (int) ($invoice_data->discount_given ?? 0);

        // Replace arrays for template
        $arrey1 = ['{#name#}','{#collect_amount#}','{#method#}','{#school_name#}'];
        $arrey2 = [$student_name,$finalCollectedAmt,$payment_mode,$setting->name];

        // Fee details
        $explode = explode(',',$invoice_data->fees_details_id);
        $fess_print = FeesDetail::select('fees_detail.*','payment_modes.name as payment_mode','fees_group.name as fees_group_name')
            ->leftJoin('payment_modes','payment_modes.id','fees_detail.payment_mode_id')
            ->leftJoin('fees_collect','fees_collect.id','fees_detail.fees_collect_id')
            ->leftJoin('fees_group','fees_group.id','fees_detail.fees_group_id')
            ->whereIn('fees_detail.id',$explode)
            ->get();

        // PDF Generate
        $printPreview = Helper::printPreview('Fees Collect');
        $pdf = PDF::loadView($printPreview,[
            'data'=>$fess_print,
            'invoice_data'=>$invoice_data
        ]);

        $slip = $invoice_data->id; // use invoice id as slip no.
        $file_name = 'Fees Receipt '.$slip.'-'.time().'.pdf';
        $destinationPath = env('IMAGE_UPLOAD_PATH').'fees_receipt_pdf/';
        $file_path = $destinationPath.$file_name;
        file_put_contents($file_path, $pdf->output());
        $file_show_path = env('IMAGE_SHOW_PATH').'fees_receipt_pdf/'.$file_name;
//dd($file_show_path);
        // Send WhatsApp
        
        $whatsapp = str_replace($arrey1, $arrey2, $template->whatsapp_content ?? '');
                                
                                if ($setting->firebase_notification == 1) {
                                    Helper::sendNotification(
                                        $template->title ?? 'Fee Payment Received',
                                        'We have received your payment of ₹'.$finalCollectedAmt.' on '.$invoice_data->payment_date.' via '.$payment_mode.' Thank you!',
                                        'student',
                                        $invoice_data->admission_id
                                    ); 
                                }
                                 
                                if ($template->message_type_status == 1) {
                                    if ($branch->whatsapp_srvc == 1) {
                                        if (!empty($mobile)) {
                                            Helper::MessageQueue($mobile,$whatsapp,$file_show_path);
                                        }
                                    }
                                }
        
      

        // // Push Notification
        // Helper::sendNotification(
        //     'Fee Payment Received',
        //     'We have received your payment of ₹'.$finalCollectedAmt.' on '.$invoice_data->payment_date.' via '.$payment_mode.' Thank you!',
        //     'student',
        //     $invoice_data->admission_id
        // );
    }



    return response()->json([
    'status' => 'success',
    'message' => 'WhatsApp message sent successfully!'
]);

}

 
            public function viewFees(Request $request){
                $serach['name'] = $request->name;
                $serach['class_type_id'] = $request->class_type_id;
                $serach['starting'] = $request->starting;
                $serach['ending'] = $request->ending;
                $serach['user_id'] = $request->user_id;
                $serach['admission_no'] = $request->admission_no;
                $data =  FeesDetailsInvoices::select('fees_details_invoices.*','class.name as class_name','admissions.admissionNo','admissions.first_name'
                ,'admissions.last_name','users.first_name as users_first_name'
                ,'users.last_name as users_last_name','admissions.father_name','admissions.school','payment_modes.name as payment_mode','payment_modes.id as payment_mode_id')
                ->leftjoin('admissions as admissions', 'admissions.id', 'fees_details_invoices.admission_id')
                ->leftjoin('class_types as class','class.id','admissions.class_type_id')
                ->leftjoin('payment_modes','payment_modes.id','fees_details_invoices.payment_mode')
                ->leftjoin('users','users.id','fees_details_invoices.user_id')
                ->where('fees_details_invoices.session_id', Session::get('session_id'))
                ->where('fees_details_invoices.branch_id', Session::get('branch_id'))
                ->where('fees_details_invoices.status', '!=', 3);
                if ($request->isMethod('post')) {
                    if (!empty($request->name)) {
                        $data = $data->where('admissions.first_name', 'LIKE', '%' . $request->name . '%')
                        ->orwhere('admissions.last_name', 'LIKE', '%' . $request->name . '%')
                        ->orwhere('admissions.father_name', 'LIKE', '%' . $request->name . '%')
                        ->orwhere('admissions.mother_name', 'LIKE', '%' . $request->name . '%')
                        ->orwhere('admissions.admissionNo', $request->name)
                        ->orwhere('admissions.mobile', 'LIKE', '%' . $request->name . '%')
                        ->orwhere('admissions.aadhaar', $request->name)
                        ->orwhere('admissions.email', 'LIKE', '%' . $request->name . '%');
                    }
                    if (!empty($request->starting)) {
                        $data = $data->whereBetween('fees_details_invoices.payment_date', [$request->starting, $request->ending]);
                    }
                    if (!empty($request->class_type_id)) {
                        $data = $data->where("admissions.class_type_id", $request->class_type_id);
                    }
                    if (!empty($request->user_id)) {
                        $data = $data->where("fees_details_invoices.user_id", $request->user_id);
                    }
                }
                else{
                    $data = $data->whereBetween('fees_details_invoices.payment_date', [date('Y-m-d'), date('Y-m-d')]);
                    $serach['starting'] = date('Y-m-d');
                    $serach['ending'] = date('Y-m-d');
                }
                if (Session::get('role_id') > 1) {
                    $data = $data->where('fees_details_invoices.user_id', Session::get('id'));
                }
                $data = $data->where('admissions.school','=',1)->orderBy('fees_details_invoices.id', 'DESC')->get();
                
                return view('fees.fees_collect.index', ['data' => $data, 'serach' => $serach]);
            }
    
            public function AssignFeesEdit(Request $request,$id){
                $data = FeesAssignDetail::select('fees_assign_details.*','fees_group.id as feesGroupId')
                ->leftJoin('fees_group','fees_group.id','fees_assign_details.fees_group_id')
                ->where('fees_assign_details.admission_id',$id)
                ->get();
                $feesAssign = FeesAssign::where('admission_id',$id)->first(); 
                if ($request->isMethod('post')) {
                    $feesAssign->emi_check = $request->emi_check;
                    $feesAssign->save();
                    for($i=0; $i < count($request->fees_group_id); $i++ ){
                        $values = FeesAssignDetail::where('fees_assign_id',$request->fees_assign_id[$i])
                        ->where('fees_master_id',$request->fees_master_id[$i])
                        ->where('fees_group_id',$request->fees_group_id[$i])
                        ->where('admission_id',$id)
                        ->first();
                        if(!empty($values)){
                            $values->fees_group_amount = $request->amount[$i];
                            $values->save();
                        }
                        else{
                            $values = new FeesAssignDetail;
                            $values->user_id = Session::get('id');
                            $values->branch_id = Session::get('branch_id');
                            $values->session_id = Session::get('session_id');
                            $values->fees_group_amount = $request->amount[$i];
                            $values->admission_id = $request->admission_id[$i];
                            $values->fees_assign_id = $request->fees_assign_id[$i];
                            $values->fees_master_id = $request->fees_master_id[$i];
                            $values->fees_group_id = $request->fees_group_id[$i];
                            $values->save();
                        }
                    }
                    return redirect::to('student_assign_fees')->with('message', 'Assign Fees Update Successfully.');
                }
                return view('fees.assign_fees_student.edit',['data'=>$data,'feesAssign'=>$feesAssign]);
            }


            public function getFeesDetail(Request $request){
                $admission_id = $request->admission_id;
                $fees = FeesCollect::with('Student')->with('ClassTypes')->with('PaymentMode')->orderBy('id', 'DESC')->groupBy('admission_id')->get();
                $feesDetail = FeesDetail::where('admission_id', $admission_id)->with('FeesType')->orderBy('id', 'DESC')->get();
                $html = "";
                $name = "n";
                $count = 1;
                foreach ($feesDetail   as $key => $item) {
                    $html .= '<tr><td>' . $count++ . '</td><td>' . $item['FeesType']['name'] . '<input type="hidden" name="fees_type_id[]" value="' . $item['fees_type_id'] . '"></td><td title="Click on the amount for edit"><span id="' . $name . $count . '" class="editable">' . $item['amount'] . '</span></td>
                    <td><a href="" class="btn btn-primary  btn-xs ml-3"><i class="fa fa-edit"></i></a></td></tr>';
                    // return view('fees.fees_collect.index',['data'=>$fees,'dataview'=>$feesDetail]);
                }
                echo $html;
            }
  
            public function printPayement($id){
                $explode = explode(',',$id);
                $fess_print = FeesDetail::select('fees_detail.*','admissions.first_name','fees_group.name as fees_group_name','admissions.last_name','class_types.name as class_name','admissions.father_name','admissions.admissionNo','payment_modes.name as payment_mode')
                ->leftJoin('admissions','admissions.id','fees_detail.admission_id')
                ->leftJoin('payment_modes','payment_modes.id','fees_detail.payment_mode_id')
                ->leftJoin('fees_collect','fees_collect.id','fees_detail.fees_collect_id')
                ->leftJoin('fees_group','fees_group.id','fees_detail.fees_group_id')
                ->leftJoin('class_types','class_types.id','admissions.class_type_id')
                ->whereIn('fees_detail.id',$explode)->get();
                
                //dd($fess_print);
                $printPreview = Helper::printPreview('Fees Collect');
                // dd($printPreview);
                return view($printPreview, ['data' => $fess_print]);
                // return view('print_file.student_print.print_fees', ['data' => $fess_print]);
            }
    
            public function printFeesInvoice(Request $request){
                // dd($request);
                $explode = [];
                if(!empty($request->fees_details_invoice_id)){
                    $invoice_data =  FeesDetailsInvoices::select('fees_details_invoices.*','admissions.first_name','users.email as user_email',
                    'admissions.last_name','admissions.category','class_types.name as class_name','gender.name as gender_name','class_types.id as class_type_id','admissions.father_name',
                    'admissions.admissionNo','payment_modes.name as payment_mode','payment_modes.id as payment_mode_id')
                    ->leftjoin('admissions as admissions', 'admissions.id', 'fees_details_invoices.admission_id')
                    ->leftjoin('users as users', 'users.id', 'fees_details_invoices.user_id')
                    ->leftjoin('class_types','class_types.id','admissions.class_type_id')
                    ->leftjoin('gender','gender.id','admissions.gender_id')
                    ->leftjoin('payment_modes','payment_modes.id','fees_details_invoices.payment_mode')
                    ->where('fees_details_invoices.branch_id', Session::get('branch_id'))
                    ->where('fees_details_invoices.id',$request->fees_details_invoice_id)->first();
                    $explode = explode(',',$invoice_data->fees_details_id);
                    $fess_print = FeesDetail::select('fees_detail.*','payment_modes.name as payment_mode','fees_group.name as fees_group_name')
                        ->leftJoin('payment_modes','payment_modes.id','fees_detail.payment_mode_id')
                        ->leftJoin('fees_collect','fees_collect.id','fees_detail.fees_collect_id')
                        ->leftJoin('fees_group','fees_group.id','fees_detail.fees_group_id')
                        ->whereIn('fees_detail.id',$explode);
                        $fess_print=$fess_print->get();
                        
                    $printPreview = Helper::printPreview('Fees Collect');
                    // dd($printPreview);
                    return view($printPreview, ['data'=>$fess_print,'invoice_data'=>$invoice_data]);
                } 
                else{            
                    return redirect::to('fee_dashboard');
                }
                //dd($fess_print);
            }
    
            public function printPayementGenerate($id){
                $fess_print = FeesDetail::with('Admission')->with('PaymentMode')->with('FeesCollect')->with('ClassTypes')->find($id);
                //dd($fess_print);
                $printPreview =    Helper::printPreview('Fees Collect');
                //dd($printPreview);
                $randomString = Str::random(10);
                $pdf = PDF::loadView($printPreview, ['data' => $fess_print]);
                file_put_contents(env('IMAGE_UPLOAD_PATH'). 'feesPaymentPdf' . '/' .$randomString.$fess_print->receipt_no . '.pdf', $pdf->output());
                $file_url = env('IMAGE_SHOW_PATH') . 'feesPaymentPdf' . '/' .$randomString.$fess_print->receipt_no . '.pdf';  
                FeesDetail::where('id',$id)->update(['fees_pdf_name' => $file_url]);
                return redirect::to('fees/index')->with('message', 'PDF Generated Successfully !');
                // return view($printPreview, ['data' => $fess_print]);
                // return view('print_file.student_print.print_fees', ['data' => $fess_print]);
            }

            public function collectFeesDelete(Request $request){
                $admissionId = $request->admission_id ;
                $fee_invoice_id = $request->fees_invoice_id;
                $data = Admission::where('id',$admissionId)->first();
                $FeesDetailsInvoices = FeesDetailsInvoices::where('session_id',$request->session_id)->where('id',$fee_invoice_id)->first();
                if(!empty($FeesDetailsInvoices)){
                    $explode = explode(',', $FeesDetailsInvoices->fees_details_id);
                    $fees_detail_ids = FeesDetail::whereIn('id',$explode)->update(['status'=>3]);
                    FeesDetailsInvoices::where('session_id',$request->session_id)->where('id',$fee_invoice_id)->update(['status'=>3]);
                    $total_collected = FeesDetail::where('admission_id',$admissionId)->where('status','!=' ,3)->sum('total_amount');
                    $fees_collect = FeesCollect::where('admission_id',$admissionId)->first();
                    $fees_collect->amount= $total_collected;
                    $fees_collect->save();
                }
                return Response::json(array('status' => 'success','unique_system_id'=>$data->unique_system_id,'session_id' => $data->session_id)); 
            }
  
            public function feesSearchData(Request $request){
                $name = $request->post('name');
                $class_type_id = $request->get('class_type_id');
                $fees_type_id = $request->get('fees_type_id');
                $data =  FeesCollect::with('Student')->with('PaymentMode');
                if (!empty($name)) {
                    $data = $data->where("student_name", $name);
                }
                if (!empty($class_type_id)) {
                    $data = $data->where("class_type_id", $class_type_id);
                }
                $allfees = $data->orderBy('id', 'DESC')->get();
                return  view('fees.fees_collect.fees_search_data', ['data' => $allfees]);
            }

       
            public function feesMasterData(Request $request){
                $data =  FeesMaster::find($request->fees_master_id);
                $paidAmount =  FeesDetail::where('class_type_id', $request->class_type_id)->where('fees_type_id', $data['fees_type_id'])->sum('total_amount');
                //dd($data);
                if ($paidAmount > 0) {
                    $net_amount =  $data['amount'] - $paidAmount;
                } 
                else {
                    $net_amount = $data['amount'];
                }
                echo json_encode($net_amount);
            }

            public function ledgerSave(Request $request){
                if(!empty($request->admission_id)){
                    foreach($request->admission_id as $key => $ids)
                {
                $find = Admission::find($ids);
                    $find->ledger_no = $request->ledger_number[0] ?? null; 
                        $find->save();
                    }
                    return redirect::to('ledger_update')->with('message', 'Ledger Number Updated Successfully');
                }
            }
            
            public function ledgerUpdate(Request $request){
                $serach['name'] = $request->name;
                $serach['class_type_id'] = !empty($request->class_type_id) ? $request->class_type_id : 0;
                if ($request->isMethod('post')) {
                    $value = $request->name;
                    $data = Admission::with('ClassTypes')->where('status', 1)->where('session_id', Session::get('session_id'))->where('school','=',1)->where('branch_id', Session::get('branch_id'));
                    
                    if (!empty($request->name)) {
                        $data = $data->where(function ($query) use ($value) {
                            $query->where('first_name', 'like', '%' . $value . '%');
                            $query->orWhere('userName', 'like', '%' . $value . '%');
                            $query->orWhere('mobile', 'like', '%' . $value . '%');
                            $query->orWhere('aadhaar', 'like', '%' . $value . '%');
                            $query->orWhere('email', 'like', '%' . $value . '%');
                            $query->orWhere('father_name', 'like', '%' . $value . '%');
                            $query->orWhere('mother_name', 'like', '%' . $value . '%');
                            $query->orWhere('address', 'like', '%' . $value . '%');
                            $query->orWhere('admissionNo', 'like', '%' . $value . '%');
                        });
                    }
                    if (!empty($request->class_type_id)) {
                        $data = $data->where("class_type_id", $request->class_type_id);
                    }
                    $allstudents = $data->orderBy('id', 'DESC')->get();
                    return  view('fees.fees_collect.studentSearchList', ['data' => $allstudents]);
                }
                return view('fees.fees_collect.ledgerUpdate',['serach' => $serach]);
            }

            public function feesLedger(Request $request){
                $search['name'] = $request->name;
                $search['class_type_id'] = $request->class_type_id ?? '';
                $serach['starting'] = $request->starting;
                $serach['ending'] = $request->ending;
                $data = Admission::select('admissions.*','fees_assigns.total_amount','fees_detail.fees_counter_id','fees_detail.date','class_types.name as className','fees_assigns.total_discount as assign_discount','fees_collect.amount as collect_amount', 'fees_collect.discount')
                    ->leftJoin('fees_assigns as fees_assigns', 'fees_assigns.admission_id', 'admissions.id')
                    ->leftJoin('class_types', 'class_types.id', 'admissions.class_type_id')
                    ->leftJoin('fees_collect as fees_collect', 'fees_collect.admission_id', 'admissions.id')
                    ->leftJoin('fees_detail as fees_detail', 'fees_detail.admission_id', 'admissions.id')
                    ->where('admissions.status',1)
                    ->where('admissions.school',1)
                    ->groupBy('admissions.id');
                if ($request->isMethod('post')) {
                    if (!empty($request->name)) {
                        $value = $request->name;
                        $data = $data->where(function ($query) use ($value) {
                            $query->where("admissions.first_name", 'like', '%' . $value . '%');
                            $query->orwhere("admissions.last_name", 'like', '%' . $value . '%');
                            $query->orwhere("admissions.mobile", 'like', '%' . $value . '%');
                            $query->orwhere("admissions.email", 'like', '%' . $value . '%');
                            $query->orwhere("admissions.aadhaar", 'like', '%' . $value . '%');
                            $query->orwhere("admissions.father_name", 'like', '%' . $value . '%');
                            $query->orwhere("admissions.mother_name", 'like', '%' . $value . '%');
                            $query->orwhere("admissions.address", 'like', '%' . $value . '%');
                        });
                    }
                    if (!empty($request->class_type_id)) {
                        $data = $data->where('admissions.class_type_id',$request->class_type_id);
                    }
                    if (!empty($request->starting)) {
                    $data = $data->whereBetween('fees_detail.date', [$request->starting, $request->ending]);
                    }
                    if (!empty($request->starting)) {
                        $data = $data->whereBetween('fees_detail.date', [$request->starting, $request->ending]);
                    }
                }
             
                    $data = $data->where('admissions.branch_id', Session::get('branch_id'))->where('admissions.session_id', Session::get('session_id'))->orderBy('admissions.id', 'DESC')->get();
                
                return view('fees.ledger.view', ['data' => $data, 'search' => $search]);
            }

            public function fees_ledger_view(Request $request) {
                $getFees = FeesAssignDetail::select('fees_assign_details.*', 'fees_group.name as group_name')
                    ->join('fees_group', 'fees_group.id', '=', 'fees_assign_details.fees_group_id')
                    ->where('admission_id',$request->admission_id)
                    ->get();
            
                $html = '<table class="table">
                    <thead>
                        <tr class="sky_tr">
                            <th>#</th> 
                            <th>Fees Type</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Fine</th>
                            <th>Paid</th>
                            <th style="text-align: right;">Balance</th>
                        </tr>
                    </thead>
                    <tbody>';
            
                if (!$getFees->isEmpty()) {
                    $i = 1;
                    $grand_total = 0;
                    $Paids = 0;
                    $Discount = 0;
                    $Fine = 0;
                    $balances = 0;
                    $fine_amt = 0;
            
                    foreach ($getFees as $item) {
                        $feesDetails = FeesDetail::where('fees_type', 0)
                                    ->whereIn('status', [0, 1, 2])
                                    ->where('admission_id',$request->admission_id)
                                    ->where('fees_group_id', $item->fees_group_id)
                                    ->selectRaw('SUM(total_amount) as total_amount, SUM(discount) as total_discount, SUM(installment_fine) as installment_fine')
                                    ->first();

                                $pad = $feesDetails->total_amount;
                                $discounts = $feesDetails->total_discount;
                                $fine_amt = $feesDetails->installment_fine;

            
                        $balance = $item->fees_group_amount-$item->discount - $pad;
                    
            
                        $html .= '<tr>
                            <td>' . $i++ . '</td>
                            <td>' . ($item->group_name ?? '') . '</td>
                            <td>' . (!empty($item->installment_due_date) ? date('d-M-Y', strtotime($item->installment_due_date)) : '') . '</td>
                            <td>' . ($item->fees_group_amount > $pad ? '<span class="label1 label-danger-custom">Unpaid</span>' : '<span class="label1 label-success-custom">Total Paid</span>') . '</td>
                            <td>' . ($item->fees_group_amount-$item->discount ?? '0') . '</td>
                            <td>' . ($discounts ?? '0') . '</td>
                            <td>' . ($fine_amt ?? '0'). '</td>
                            <td>' . ($pad ?? '0') . '</td>
                            <td style="text-align: right;">' . ($balance ?? '') . '</td>
                        </tr>';
            
                        $grand_total += $item->fees_group_amount-$item->discount;
                        $Paids += $pad;
                        $Discount += $discounts;
                        $Fine += $fine_amt;
                        $balances += $balance;
                    }
            
                    $html .= '<tr>
                        <td colspan="12">
                            <div class="row">
                            <div class="col-6"></div>
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th colspan="3" style="text-align: right; font-weight: normal;"><strong>Grand Total:</strong> ' . $grand_total . '</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" style="text-align: right; font-weight: normal;"><strong>Paid:</strong> ' . $Paids . '</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" style="text-align: right; font-weight: normal;"><strong>Discount:</strong> ' . $Discount . '</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" style="text-align: right; font-weight: normal;"><strong>Fine:</strong> ' . $Fine . '</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" style="text-align: right; font-weight: normal;"><strong>Balance:</strong> ' . $balances . '</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>';
                } else {
                    $html .= '<tr class="text-center">
                        <td colspan="9"><b>!! NO DATA FOUND !!</b></td>
                    </tr>';
                }
            
                $html .= '</tbody></table>';
            
                return response()->json(['html' => $html]);
            }
    
           
    
            public function getFeesGroup(Request $request){
                $data =  FeesMaster::Select('fees_master.*','groups.name as fees_group_name','class.name as class_name','session.from_year as from_year','session.to_year as to_year')
                ->leftjoin('fees_group as groups','groups.id','fees_master.fees_group_id')
                ->leftjoin('class_types as class','class.id','fees_master.class_type_id')
                ->leftjoin('sessions as session','session.id','fees_master.session_id')
                ->where('fees_master.class_type_id',$request->class_type_id)->where('fees_master.session_id', $request->session_id)->where('fees_master.branch_id', Session::get('branch_id'))->get();
                return Response::json(array('data' => $data)); 
            }
            public function feesRemainderCron(Request $request){
                $search['name'] = $request->name;
                $search['class_type_id'] = $request->class_type_id ?? '';
                $serach['ending'] = $request->ending;
                $search['status'] = $request->status;
                //$search['batch'] = $request->batch;
                $search['admissionNo'] = $request->admissionNo;
                $search['session_id'] = $request->session_id;
                
                $session = Session::get('session_id');
                $branch_id = Session::get('branch_id');
                
                $studentArray='';
                if ($request->isMethod('post')) {     
                // Start Query
                $admission_ids = Admission::select('admissions.*', 'class_types.name as class_name')
                    ->leftJoin('class_types', 'class_types.id', '=', 'admissions.class_type_id')
                    ->where('admissions.school', 1)
                    ->where('admissions.session_id', $request->session_id ?? $session)
                    ->where('admissions.branch_id', $branch_id);
                
                // Apply Search Filter
                if (!empty($request->name)) {
                    $value = $request->name;
                    $admission_ids->where(function ($query) use ($value) {
                        $query->where("admissions.first_name", 'like', '%' . $value . '%')
                              ->orWhere("admissions.last_name", 'like', '%' . $value . '%')
                              ->orWhere("admissions.mobile", 'like', '%' . $value . '%')
                              ->orWhere("admissions.email", 'like', '%' . $value . '%')
                              ->orWhere("admissions.aadhaar", 'like', '%' . $value . '%')
                              ->orWhere("admissions.father_name", 'like', '%' . $value . '%')
                              ->orWhere("admissions.mother_name", 'like', '%' . $value . '%')
                              ->orWhere("admissions.address", 'like', '%' . $value . '%');
                    });
                }
                
                // Filter by Admission Number
                if (!empty($request->admissionNo)) {
                    $admission_ids->where("admissions.admissionNo", $request->admissionNo);
                }
                
                // Filter by Class Type
                if (!empty($request->class_type_id)) {
                    $admission_ids->where('admissions.class_type_id', $request->class_type_id);
                }
                
                // Filter by Batch
                /*if (!empty($request->batch)) {
                    $admission_ids->where("admissions.batch", $request->batch);
                }*/
                
                // Filter by Status
                if (isset($request->status) && $request->status !== '') {
                    $admission_ids->where("admissions.status", $request->status);
                } else {
                    $admission_ids->where("admissions.status", 1);
                }
                
                $admission_ids = $admission_ids->orderBy('admissions.class_type_id', 'ASC')->get();

                $template = MessageTemplate::Select('message_templates.*','message_types.slug')
                ->leftjoin('message_types','message_types.id','message_templates.message_type_id')
                ->where('message_types.status',1)->where('message_types.slug','feesreminder')->first();
                $setting = Setting::where('session_id',$session)->where('branch_id', Session::get('branch_id'))->first();     
                $studentArray =[];
                foreach($admission_ids as $student){
                    $fees_assigned = FeesAssign::where('admission_id',$student->id)->where('session_id',$session)->first();
                   $fees_collected = FeesDetail::where('admission_id',$student->id)->whereIn('status',[0,1,2])->sum('total_amount');

                    $isRemaining = (($fees_assigned->total_amount ?? 0)-($fees_assigned->total_discount ?? 0))-($fees_collected ?? 0);
                    // dd($isRemaining);
                    if($isRemaining >0 ){
                        $getHead =  FeesAssignDetail::Select('fees_assign_details.*','fees_group.name as group_name')
                        ->leftjoin('fees_group','fees_group.id','fees_assign_details.fees_group_id')
                        ->where('admission_id',$student->id)->where('fees_assign_details.session_id',$session)
                        //   ->whereNotNull('installment_due_date')
                        //   ->whereDate('installment_due_date','<=', date('Y-m-d'))
                        ->get();
                        $AllRemainingFees = '';
                        $total_pending = 0;
                        $remainingAmount = 0;
                        $installment_due_date = '';
                        foreach($getHead as $head){
                            //if($head->installment_due_date >= date('Y-m-d') || $head->installment_due_date === null)
                            //{
                                $feesDetails = FeesDetail::where('admission_id',$student->id)->where('fees_group_id',$head->fees_group_id)->whereIn('status',[0,1,2])->sum('paid_amount');
                                $remainingAmount = (($head->fees_group_amount ?? 0) - ($head->discount ?? 0)) - ($feesDetails ?? 0);
                                if($remainingAmount > 0){
                                    $line = $head->group_name . ' = Rs.' . number_format($remainingAmount);
                                    $AllRemainingFees .= $line . "\n";
                                    $total_pending += $remainingAmount;
                                    $installment_due_date = $head->installment_due_date;
                                }
                            // }
                        }         
                        $AllRemainingFees .= '<span class="bg-danger p-1">*TOTAL PENDING:' . ' = Rs.' . $total_pending.'*</span>';
                        $arrey1 = array(
                            '{#name#}',
                            '{#class_name#}',
                            '{#fees_remain#}',
                            '{#school_name#}',
                            '{#dur_date#}',
                        );
                        $arrey2 = array(
                            ($student->first_name ?? 0).' '.($student->last_name ?? ''),
                            $student->class_name ?? '',
                            preg_replace('/<br\s*\/?>/', '', nl2br($AllRemainingFees)) ,
                            $setting->name ?? '',
                            // date("d-m-Y", strtotime($installment_due_date)),
                            '',
                        );
                        $message = str_replace($arrey1,$arrey2,$template->whatsapp_content);       
                        //dd($message);    
                        if($remainingAmount > 0)
                            {
                                $studentArray[] =  array( 'id'=>$student->id,
                                'name'=>($student->first_name ?? 0).' '.($student->last_name ?? ''),
                                'className'=>$student->class_name,
                                'class_type_ids'=>$student->class_type_id,
                                'mobile'=>$student->mobile,
                                'admission_id'=>$student->id,
                                'admissionNo'=>$student->admissionNo,
                                'father_name'=>$student->father_name,
                                'category'=>$student->category,
                                'student_type'=>$student->student_type,
                                'course'=>$student->course,
                                'batch'=>$student->batch,
                                'status'=>$student->status,
                                'gender_id'=>$student->gender_id,
                                'session_id'=>$student->session_id,
                                'fees_assigned'=>$fees_assigned->total_amount,
                                'pendings'=>$AllRemainingFees,
                                'message'=>$message,
                            );
                            
                            
                        }
                    }
                }
            }
            if (isset($studentArray[0]['class_type_ids']) && empty($request->class_type_id)) {
                $search['class_type_id'] = $studentArray[0]['class_type_ids'];
            }
           
              return view('fees.dues.duesList',['data' => $studentArray,'search'=>$search]);
            }
            
    
    
            public function feesModification(Request $request){
                $admissionNo = $request->admissionNo ?? '';
                $class_type_id= $request->class_type_id ?? '';
                $admission_type_id= $request->admission_type_id_modify ?? '';
                $data =  FeesAssign::Select('fees_assigns.*','admissions.first_name','admissions.last_name','admissions.admissionNo','admissions.mobile')
                ->leftjoin('admissions','admissions.id','fees_assigns.admission_id')->where('admissions.session_id',Session::get('session_id'))
                ->where('admissions.branch_id',Session::get('branch_id'));
                if($class_type_id != ''){
                    $data= $data->where('admissions.class_type_id',$class_type_id);
                }
                if($admission_type_id != ''){
                    $data= $data->where('admissions.admission_type_id',$admission_type_id);
                }
                if($admissionNo != ''){
                    $data= $data->where('admissions.admissionNo',$admissionNo);
                }
                $data = $data ->get();
              
                return view('fees.modification.fees_modification', ['data' => $data]);
            }
    
       
    
            public function updateAssignedFees(Request $request){
                $feesAssignedId = $request->fees_assign_detail_id ?? '';
                $value = $request->value ?? '';
                $field = $request->field ?? '';
                $feesAssignDetail = FeesAssignDetail::find($feesAssignedId);
                $admission_id = $feesAssignDetail->admission_id;
                $feesAssignDetail->$field = $value;
                $feesAssignDetail->save();
                $feesAssignDetail = FeesAssignDetail::where('branch_id',Session::get('branch_id'))->where('admission_id',$admission_id)->get();   
                $total_amount = 0;
                $total_discount = 0;
                if(!empty($feesAssignDetail)){
                    foreach($feesAssignDetail as $item){ 
                        $total_amount += $item->fees_group_amount ?? 0;
                        $total_discount += $item->discount ?? 0;
                    }
                }
                $feesAssign = FeesAssign::find($feesAssignDetail[0]->fees_assign_id);
                $feesAssign->total_amount = $total_amount ?? 0;
                $feesAssign->total_discount = $total_discount ?? 0;
                $feesAssign->net_amount = $total_amount-$total_discount;
                $feesAssign->save();
                return Response::json(array('message' =>'Fees Updated Successfully' )); 
            }
    
            public function deleteAssignedFees(Request $request){
                $assign_id = $request->fees_assign_detail_id ?? '' ;
                $deleteData = FeesAssignDetail::find($assign_id);
                $admission_id = $deleteData->admission_id;
                $deleteData->delete();  
                $feesAssignDetail=FeesAssignDetail::where('branch_id',Session::get('branch_id'))->where('admission_id',$admission_id)->get();    
                $total_amount = 0;
                $total_discount = 0;
                if(!empty($feesAssignDetail)){
                    foreach($feesAssignDetail as $item){
                        $total_amount += $item->fees_group_amount ?? 0;
                        $total_discount += $item->discount ?? 0;
                    }
                }
                $feesAssign = FeesAssign::find($feesAssignDetail[0]->fees_assign_id);
                $feesAssign->total_amount = $total_amount ?? 0;
                $feesAssign->total_discount = $total_discount ?? 0;
                $feesAssign->net_amount = $total_amount-$total_discount;
                $feesAssign->save();
                return Response::json(array('id' =>$assign_id )); 
            }
            public function getStudentsList(Request $request){
                $fees_assign_details = FeesAssignDetail::where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))
                ->groupBy('admission_id')->pluck('admission_id')->implode(',');
                $admissionIds = [];
                if(!empty($fees_assign_details)){
                    $admissionIds = explode(',', $fees_assign_details);
                }
                $class_type_id = $request->class_type_id ?? '';
                $admissionNo= $request->admissionNo ?? '';
                $data = Admission::where('session_id',Session::get('session_id'))
               ->where('status',1)
               ->where('branch_id',Session::get('branch_id'));
                if($class_type_id != ''){
                    $data= $data->where('class_type_id', $class_type_id);
                }
                if($request->admission_type_id != ''){
                    $data = $data->where('admission_type_id',$request->admission_type_id);
                }
                if($admissionNo != ''){
                    $data= $data->where('admissionNo',$admissionNo);
                }
                $data = $data->get();
                return view('fees.modification.admissionList', ['data' => $data]);
            }
            public function createFeesInstallment(Request $request){
                if(!empty($request->installment_name)){
                    foreach($request->installment_name as $key=> $name)
                {
                $fees_group = FeesGroup::where('name' , $name)->first();            
                if(!empty($fees_group)){
                    $fees_group = $fees_group;
                }
                else
                {
                   $fees_group = new FeesGroup; //model name
                }
                $fees_group->user_id = Session::get('id');
                    $fees_group->session_id = Session::get('session_id');
                    $fees_group->branch_id = Session::get('branch_id');
                    $fees_group->name = $name;
                        $fees_group->fees_type = 'installment';
                        $fees_group->description = $request->description;
                        $fees_group->save();
                    }
                    return redirect::to('feesGroup')->with('message','Fees Group Created successfully');
                }
            }
            public function createFeesInstallmentClassWise(Request $request){
                if(!empty($request->installmentRow)){
                    $returnStatus['fees_master'] = [];
                    foreach($request->installmentRow as $key=> $row){
                        $returnStatus['entry'] = false;
                        $fees_group = FeesGroup::find($request->installment_id[$key]);          
                        if(!empty($fees_group)){
                            $fees_group = $fees_group;
                        }
                        $fees_group->user_id = Session::get('id');
                        $fees_group->session_id = Session::get('session_id');
                        $fees_group->branch_id = Session::get('branch_id');
                        $fees_group->name = $request->installment_name[$key];
                        $fees_group->fees_type = 'installment';
                        $fees_group->save();
                        if(!empty($request->installment_class_type_id)){
                            $fees_master = FeesMaster::where('fees_group_id' , $fees_group->id)->where('class_type_id' , $request->installment_class_type_id)->first();
                            if(!empty($fees_master)){
                                $fees_master = $fees_master;
                                $isUsed1 = FeesDetail::where('fees_group_id',$fees_group->id)->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->count();
                                $isUsed2 = FeesAssignDetail::where('fees_group_id',$fees_group->id)->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->count();
                                if(($isUsed1 + $isUsed2) == 0){
                                   $returnStatus['entry'] = true;
                                   $returnStatus['fees_master'][] = $fees_master->id;
                                }else{
                                   $returnStatus['entry'] = false;
                                }
                            }
                            else{
                                $fees_master = new FeesMaster; //model name
                                $returnStatus['entry'] = true;
                                $returnStatus['fees_master'][] = $fees_master->id;
                            }
                            $fees_master->user_id = Session::get('id');
                            $fees_master->session_id = Session::get('session_id');
                            $fees_master->branch_id = Session::get('branch_id');
                            $fees_master->fees_group_id = $fees_group->id;
                            $fees_master->amount = $request->installment_value[$key];
                            $fees_master->installment_month = $request->installment_month[$key];
                            $fees_master->installment_fine = $request->installment_fine[$key];
                            $fees_master->installment_due_date = $request->installment_due_date[$key];
                            $fees_master->class_type_id = $request->installment_class_type_id;
                            $fees_master->save();
                        }
                    }
                    $returnStatus['class_type_id'] = $request->installment_class_type_id;
                    return $returnStatus;
                }
            }
        
            public function assignFeesMultipleStudents(Request $request){
                
                
                  if (empty($request->admissionIds)) {
                        return back()->with('error', 'No students selected');
                    }
                    // ✅ normalize fees_master_ids
                    $feesMasterIds = $request->fees_master_ids;
                    if (is_array($feesMasterIds)) {
                        $feesMasterIds = implode(',', $feesMasterIds);
                    }
                    $feesMasterIds = array_filter(explode(',', $feesMasterIds));
    
                if(!empty($request->admissionIds)){
                    foreach($request->admissionIds as $admission){
                        if(!empty($feesMasterIds)){
                            foreach($feesMasterIds as $master_id){
                               
                                $fees_master = FeesMaster::find($master_id);
                                $fees_groups = FeesGroup::find($fees_master->fees_group_id);
                                $fees_assign_details = FeesAssignDetail::where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))
                                ->where('fees_master_id',$master_id)->where('admission_id',$admission)->first();
                                if(empty($fees_assign_details)){       
                                    $feesAssign = FeesAssign::where('admission_id', $admission)->first();
                                    if(!empty($feesAssign)){
                                        $feesAssign = $feesAssign;
                                    }else{
                                        $feesAssign = new FeesAssign();
                                    }
                                    $feesAssign->user_id = Session::get('id');
                                    $feesAssign->session_id = Session::get('session_id');
                                    $feesAssign->branch_id = Session::get('branch_id');
                                    $feesAssign->admission_id = $admission;
                                    $feesAssign->save();
                                    $values = FeesAssignDetail::where('fees_assign_id',$feesAssign->id)
                                    ->where('fees_master_id',$fees_master->id)
                                    ->where('fees_group_id',$fees_master->fees_group_id)
                                    ->where('admission_id',$admission)
                                    ->first();
                                    if(!empty($values)){
                                        $values = $values;
                                    }else{
                                        $values = new FeesAssignDetail;
                                    }
                                    $values->user_id = Session::get('id');
                                    $values->branch_id = Session::get('branch_id');
                                    $values->session_id = Session::get('session_id');
                                    $values->fees_group_amount = $fees_master->amount;
                                    $values->admission_id = $admission;
                                    $values->fees_assign_id = $feesAssign->id;
                                    $values->class_type_id = $fees_master->class_type_id;
                                    $values->fees_master_id = $fees_master->id;
                                    $values->fees_group_id = $fees_master->fees_group_id;
                                    if (isset($fees_groups->fees_refund)) {
                                    $values->fees_refund = $fees_groups->fees_refund;
                                    } else {
                                    $values->fees_refund = 'no';
                                    }                                    $values->installment_month = $fees_master->installment_month;
                                    $values->installment_fine = $fees_master->installment_fine;
                                    $values->installment_due_date= $fees_master->installment_due_date;
                                    $values->save();
                                    $total_assign_detail = FeesAssignDetail::where('admission_id',$admission)->sum('fees_group_amount');
                                    $discount_assign_detail = FeesAssignDetail::where('admission_id',$admission)->sum('discount');
                                    $amountIncrement = FeesAssign::where('id',$feesAssign->id)->update(['total_amount'=>$total_assign_detail ]);
                                    $amountIncrement = FeesAssign::where('id',$feesAssign->id)->update(['net_amount'=>($total_assign_detail-$discount_assign_detail) ]);
                                    //   $amountIncrement = FeesAssign::where('id',$feesAssign->id)->increment('total_amount', $request->installment_value[$key] );
                                    //   $amountIncrement = FeesAssign::where('id',$feesAssign->id)->increment('net_amount', $request->installment_value[$key] );
                                }             
                            }
                        }
                    }
                    return redirect::to("feesMasterAdd")->with('message','Students Assigned Successfully');
                }
            }
        
            public function getMasterData(Request $request){
                $masterData = FeesMaster::select('fees_master.*','fees_group.name as fees_group_name')
                ->leftJoin('fees_group','fees_group.id','fees_master.fees_group_id')
                ->where('fees_master.class_type_id',$request->class_type_id)
                ->where('fees_master.session_id',Session::get('session_id'))
                ->where('fees_master.branch_id',Session::get('branch_id'))
                ->get();
                return $masterData; 
            }
        
            public function caReport(Request $request){
                $search['name'] = $request->name;
                $search['user_id'] = $request->user_id;
                $search['class_type_id'] = $request->class_type_id;
                $search['starting'] = $request->starting;
                $search['ending'] = $request->ending;
                $search['admission_no'] = $request->admission_no;
                $data =  FeesDetailsInvoices::select('fees_details_invoices.*','class.name as class_name','admissions.image','admissions.mobile','admissions.admissionNo','admissions.first_name'
                ,'admissions.last_name','users.first_name as users_first_name'
                ,'users.last_name as users_last_name','admissions.father_name','admissions.school','payment_modes.name as payment_mode','payment_modes.id as payment_mode_id')
                ->leftjoin('admissions as admissions', 'admissions.id', 'fees_details_invoices.admission_id')
                ->leftjoin('class_types as class','class.id','admissions.class_type_id')
                ->leftjoin('payment_modes','payment_modes.id','fees_details_invoices.payment_mode')
                ->leftjoin('users','users.id','fees_details_invoices.user_id')
                ->where('fees_details_invoices.session_id', Session::get('session_id'))
                ->where('fees_details_invoices.branch_id', Session::get('branch_id'));
                if ($request->isMethod('post')) {
                    if (!empty($request->name)) {
                        $data = $data->where('admissions.first_name', 'LIKE', '%' . $request->name . '%')
                        ->orwhere('admissions.last_name', 'LIKE', '%' . $request->name . '%')
                        ->orwhere('admissions.father_name', 'LIKE', '%' . $request->name . '%')
                        ->orwhere('admissions.mother_name', 'LIKE', '%' . $request->name . '%')
                        ->orwhere('admissions.admissionNo', $request->name)
                        ->orwhere('admissions.mobile', 'LIKE', '%' . $request->name . '%')
                        ->orwhere('admissions.aadhaar', $request->name)
                        ->orwhere('admissions.email', 'LIKE', '%' . $request->name . '%');
                    }
                    if (!empty($request->starting)) {
                        $data = $data->whereBetween('fees_details_invoices.payment_date', [$request->starting, $request->ending]);
                    }
                    if (!empty($request->class_type_id)) {
                        $data = $data->where("admissions.class_type_id", $request->class_type_id);
                    }
                    if (!empty($request->user_id)) {
                        $data = $data->where("fees_details_invoices.user_id", $request->user_id);
                    }
                    if (!empty($request->admission_no)) {
                        $data = $data->where("admissions.admissionNo", $request->admission_no);
                    }
                }
                if (Session::get('role_id') > 1) {
                    $data = $data->where('fees_details_invoices.user_id', Session::get('id'));
                }
                $data = $data->where('admissions.school','=',1)->orderBy('fees_details_invoices.id', 'DESC')->get();
                return view('fees.reports.CA', ['data' => $data, 'search' => $search]);
            }
        
            public function fees_cheque(Request $request){
                $search['name'] = $request->name;
                $search['class_type_id'] = $request->class_type_id ?? '';
                $serach['starting'] = $request->starting;
                $serach['ending'] = $request->ending;
                if ($request->isMethod('post')) {
                    $update = FeesDetailsInvoices::find($request->id);
                    
                    if(!empty($update))
                    {
                        $update->status = $request->status_id ?? '';
                        $update->remark = $request->remark ?? '';
                        $update->save();
                        $feesDetailsId = explode(',', $update->fees_details_id); // Convert string to array

                if (!empty($feesDetailsId)) {
                        $fees_ = FeesDetail::whereIn('id', $feesDetailsId)->update(['status' => $request->status_id ?? '']);
                        
                    } 

                    }
                }
                $data =  FeesDetailsInvoices::select('fees_details_invoices.*','class.name as class_name','admissions.admissionNo','admissions.mobile','admissions.first_name'
                ,'admissions.last_name','admissions.father_name','admissions.school','payment_modes.name as payment_mode','payment_modes.id as payment_mode_id')
                ->leftjoin('admissions as admissions', 'admissions.id', 'fees_details_invoices.admission_id')
                ->leftjoin('class_types as class','class.id','admissions.class_type_id')
                ->leftjoin('payment_modes','payment_modes.id','fees_details_invoices.payment_mode')
                ->where('fees_details_invoices.session_id', Session::get('session_id'))
                ->where('fees_details_invoices.branch_id', Session::get('branch_id'))
                ->where('fees_details_invoices.status', 1);
             
                $data = $data->where('school', '>', 0)->orderBy('fees_details_invoices.payment_date','DESC')->get();
               return view('fees.fees_cheque', ['data' => $data, 'search' => $search]);
            }
     

    
}

