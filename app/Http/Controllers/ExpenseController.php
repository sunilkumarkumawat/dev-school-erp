<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Validator;
use App\Models\User;
use App\Models\Admission;
use App\Models\ClassType;
use App\Models\hostel\Hostel;
use App\Models\hostel\HostelBuilding;
use App\Models\BillCounter;
use App\Models\hostel\HostelFloor;
use App\Models\hostel\HostelRoom;
use App\Models\hostel\HostelBed;
use App\Models\hostel\HostelAssign;
use App\Models\Master\MessageTemplate;
use App\Models\Master\Branch;
use App\Models\Setting;
use App\Models\Expense;
use App\Models\Remark;
use Session;
use Hash;
use Helper;
use File;
use Str;
use Redirect;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
            public function expenseAdd(Request $request)
                {
                    if ($request->isMethod('post')) {
                
                        // $request->validate([
                        //     'name'            => 'required|array',
                        //     'name.*'          => 'required|string|max:255',
                        //     'date'            => 'required|array',
                        //     'date.*'          => 'required|date',
                        //     'role'            => 'required|array',
                        //     'role.*'          => 'nullable|integer',
                        //     'quantity'        => 'nullable|array',
                        //     'rate'            => 'nullable|array',
                        //     'amount'          => 'nullable|array',
                        //     'payment_mode_id' => 'nullable|array',
                        //     'description'     => 'nullable|array',
                        //     'attachment'      => 'nullable|array',
                        //     'attachment.*'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                        // ]);
                
                        $sessionId = Session::get('session_id');
                        $branchId  = Session::get('branch_id');
                
                        // Fetch once (not inside loop)
                        $template = MessageTemplate::select('message_templates.*', 'message_types.slug','message_types.status as message_type_status')
                            ->leftJoin('message_types', 'message_types.id', '=', 'message_templates.message_type_id')
                            ->where('message_types.slug','expenses')
                            ->first();
                       
                        $branch  = Branch::find($branchId);
                        $setting = Setting::where('session_id', $sessionId)->where('branch_id', $branchId)->first();
                        $admin   = User::find(Session::get('id'));
                       
                        $attachment = null;
                        if ($request->hasFile('attachment')) {
                            $image = $request->file('attachment');
                            $attachment = time() . uniqid() . '_' . $image->getClientOriginalName();
                            $destinationPath = env('IMAGE_UPLOAD_PATH') . 'expense';
                            $image->move($destinationPath, $attachment);
                        }
                   $invoiceNo =  str_pad((Expense::where('session_id', Session::get('session_id'))->where('branch_id', Session::get('branch_id'))->max('invoice_no') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

                        foreach ($request->name as $count => $expName) {
                            if (isset($request->id[$count]) && $request->id[$count]) {
                                $expense = Expense::find($request->id[$count]);
                                $invoiceCounter = $expense->invoice_no;
                            } else {
                                $expense = new Expense;
                                $invoiceCounter = $invoiceNo;

                            }
                            $expense->session_id = Session::get('session_id');
                            $expense->branch_id = Session::get('branch_id');
                            $expense->user_id = $request->role ?? null;
                            $expense->invoice_no = $invoiceCounter ?? null;
                            $expense->category = $request->category[$count] ?? null;
                            $expense->name = $request->name[$count];
                            $expense->date = $request->date;
                            $expense->quantity = $request->quantity[$count];
                            $expense->rate = $request->rate[$count];
                            $expense->amount = $request->amount[$count];
                            $expense->total_amt = $request->total_amt ?? null;
                            $expense->payment_mode_id = $request->payment_mode_id ?? null;
                            $expense->attachment = $attachment;
                            $expense->description = $request->description;
                            $expense->save();
                         
                           
                                $placeholders = [
                                    '{#name#}', '{#expenses_name#}', '{#description#}',
                                    '{#quantity#}', '{#amount#}', '{#total_amount#}',
                                    '{#date#}', '{#support_no#}', '{#school_name#}'
                                ];
                
                                $values = [
                                    trim("{$admin->first_name} {$admin->last_name}"),
                                    $expName,
                                    $request->description[$count] ?? '',
                                    $request->quantity[$count] ?? '',
                                    $request->rate[$count] ?? '',
                                    $request->amount[$count] ?? '',
                                    isset($request->date[$count]) ? date('d-m-Y', strtotime($request->date[$count])) : '',
                                    $setting->mobile ?? '',
                                    $setting->name ?? ''
                                ];
                              
                                  $whatsapp = str_replace($placeholders, $values, $template->whatsapp_content);
                                 
                                if ($setting->firebase_notification == 1) {
                                    Helper::sendNotification(
                                        $template->title ?? 'Teacher',
                                        $whatsapp,
                                        'teacher',
                                        $admin->id
                                    ); 
                                }
                                 
                                if ($template->message_type_status == 1) {
                                    if ($branch->whatsapp_srvc == 1) {
                                        $mobile = $request->mobile  ?? '';
                                        if (!empty($mobile)) {
                                            Helper::MessageQueue($admin->mobile, $whatsapp);
                                        }
                                    }
                                }
                             
                        }
                
                        // Open print page for last expense
                        $url = '/expensePrint/' . $expense->id;
                        echo "<script>window.open('$url', '_blank');</script>";
                
                        return redirect()->to('expenseView')->with('message', 'Expense Added Successfully!');
                    }
                
                    return view('expense.add');
                }

        
       
            
            
           public function expenseView(Request $request)
            {
                //  dd($request->category);
                $search = [
                    'category'  => $request->category,
                    'role'      => $request->role,
                    'from_date' => $request->from_date,
                    'to_date'   => $request->to_date,
                    'keyword'   => $request->keyword,
                ];
            
                $data = Expense::where('session_id', Session::get('session_id'))
                    ->where('branch_id', Session::get('branch_id'));
            
                if ($request->isMethod('post')) {

                    if (!empty($request->role)) {
                        $data = $data->where('user_id', $request->role);
                    }
            
                    if (!empty($request->category)) {
                        $data = $data->where('category', $request->category);
                    }

                    if (!empty($request->from_date) && !empty($request->to_date)) {
                        $data = $data->whereBetween('date', [$request->from_date, $request->to_date]);
                    }

                    if (!empty($request->keyword)) {
                        $data = $data->where(function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->keyword . '%')
                              ->orWhere('description', 'LIKE', '%' . $request->keyword . '%');
                        });
                    }
                }
            
                $data = $data->orderBy('id', 'DESC')
                    ->whereNull('deleted_at')
                    ->get();
            
                return view('expense.view', [
                    'data'    => $data,
                    'search'  => $search,
                    'getRole' => \App\Models\User::all(),
                ]);
            }


            public function expenseEdit($invoice_no)
            {
                $data = Expense::where('invoice_no', $invoice_no)->get();
                return view('expense.edit', ['data' => $data]);
            }
            public function expenseDelete(Request $request){
                $id = $request->delete_id;
                $bed = Expense::find($id);
                if (File::exists(env('IMAGE_UPLOAD_PATH') . 'expense/' . $bed->attachment)) {
                    File::delete(env('IMAGE_UPLOAD_PATH') . 'expense/' . $bed->attachment);
                }
                $bed->delete();
                return redirect::to('expenseView')->with('message', 'Expense Deleted Successfully !');
            }
            
            
            public function expensePrint($invoice_no){
                $data = Expense::with('user') // eager load user relation
                    ->where('invoice_no', $invoice_no)
                    ->get();
                return view('print_file.expense.expense_print', ['data' => $data]);
            }
            
            


}
