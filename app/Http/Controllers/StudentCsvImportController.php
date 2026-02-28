<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Validation\Validator;
use App\Models\User;
use App\Models\Enquiry;
use App\Models\Admission;
use App\Models\RollNumber;
use App\Models\StudentId;
use App\Models\StudentAction;
use App\Models\exam\FillMarks;
use App\Models\Classs;
use App\Models\ClassType;
use App\Models\Subject;
use App\Models\Sessions;
use App\Models\Master\Branch;
use App\Models\TcCertificate;
use App\Models\BillCounter;
use App\Models\SmsSetting;
use App\Models\BloodGroup;
use App\Models\DatatableFields;
use App\Models\FeesMaster;
use App\Models\FeesCollect;
use App\Models\WhatsappSetting;
use App\Models\FeesStructure;
use App\Models\FeesDetail;
use App\Models\StudentDocument;
use App\Models\Setting;
use App\Models\State;
use App\Models\Gender;
use App\Models\Master\MessageTemplate;
use App\Models\Master\MessageType;
use App\Models\City;
use App\Models\fees\FeesAssign;
use App\Models\fees\FeesAssignDetail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\fees\FeesDetailsInvoices;
use Session;
use Hash;
use PDF;
use Helper;
use Str;
use Mail;
use File;
use DB;
use Redirect;
use Auth;
use App\Imports\YourImportClassName;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;


class StudentCsvImportController extends Controller
{
        
          
    
            
public function studentCsvImport(){
        return view('students/admission/studentCsvImport ');
    }

public function studentImportSave(Request $request)
{
    $data = $request->data;

    if (empty($data)) {
        return response()->json([
            'status' => false,
            'message' => 'No data received'
        ]);
    }

    try {

        DB::beginTransaction();

        $insertData = [];

        $maxBiomax = Admission::selectRaw('MAX(CAST(attendance_unique_id AS UNSIGNED)) as max_id')
            ->value('max_id');

        $biomaxCounter = (int)$maxBiomax;

        foreach ($data as $row) {

            if (empty(array_filter($row))) {
                continue;
            }

            // 🔥 FIELD TRANSFORM LOOP
            foreach ($row as $key => $value) {
//dd($row);
                switch ($key) {

                    case 'class_type_id':
                        $class = ClassType::where('name', $value)
                            ->where('branch_id', Session::get('branch_id'))
                            ->first();
                        $row[$key] = $class->id ?? null;
                        break;

                    case 'gender_id':
                        $gender = Gender::where('name', $value)->first();
                        $row[$key] = $gender->id ?? null;
                        break;

                    case 'state_id':
                        $state = State::where('name', $value)->first();
                        $row[$key] = $state->id ?? null;
                        break;

                    case 'city_id':
                        $city = City::where('name', $value)->first();
                        $row[$key] = $city->id ?? null;
                        break;

                    case 'admission_type_id':
                        $mapping = ["Yes" => 1, "No" => 2];
                        $row[$key] = $mapping[$value] ?? 1;
                        break;

                    case 'dob':
                    case 'admission_date':
                        $row[$key] = $this->convertExcelDate($value);
                        break;

                    case 'mobile_no':
                        $row[$key] = preg_replace('/[^0-9]/', '', (string)$value);
                        break;
                }
            }

            $biomaxCounter++;

            $row['session_id'] = Session::get('session_id');
            $row['user_id'] = Session::get('id');
            $row['branch_id'] = Session::get('branch_id');
            $row['status'] = 1;
            $row['school'] = 1;
            $row['attendance_unique_id'] = str_pad($biomaxCounter, 4, '0', STR_PAD_LEFT);
            $row['unique_system_id'] = strtoupper(Str::random(10));

            if (isset($row['first_name'])) {
                $row['userName'] = $row['first_name'];
                $row['password'] = Hash::make($row['first_name']);
                $row['confirm_password'] = $row['first_name'];
            }

            $insertData[] = $row;
        }

        if (count($insertData) > 0) {
            DB::table('admissions')->insert($insertData);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Students Imported Successfully'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error("Import Error: " . $e->getMessage());

        return response()->json([
            'status' => false,
            'message' => 'Something went wrong'
        ]);
    }
}
    protected function convertExcelDate($date)
{
    if (empty($date)) {
        return null;
    }

    // Excel numeric date
    if (is_numeric($date)) {
        try {
            return ExcelDate::excelToDateTimeObject($date)
                ->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    $formats = [
        'Y-m-d',
        'd-m-Y',
        'm-d-Y',
        'd/m/Y',
        'm/d/Y',
        'd.m.Y',
        'm.d.Y',
        'Y/m/d',
        'd/m/y'
    ];

    foreach ($formats as $format) {
        try {
            return Carbon::createFromFormat($format, $date)->format('Y-m-d');
        } catch (\Exception $e) {
            continue;
        }
    }

    try {
        return Carbon::parse($date)->format('Y-m-d');
    } catch (\Exception $e) {
        return null;
    }
}
            
     /*   public function studentExcelAdd(Request $request)
        {   
            $array = Helper::getAdmissionDatatableFields();
            $branch = Branch::find(Session::get('session_id'));
            $the_file = $request->file('excel');
        
            try {
                $spreadsheet = IOFactory::load($the_file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $row_limit = $sheet->getHighestDataRow();
                $column_limit = $sheet->getHighestDataColumn();
                $row_range = range(3, $row_limit);
                $highestColumnNumber = $this->columnLetterToNumber($column_limit);
        
                $data = array();
                $val2 = [];
        
                foreach ($row_range as $row) {
                    $val = [];
                    $columnBValue = '';
        
                   for ($i = 0; $i < $highestColumnNumber; $i++) {
                            $colLetter = $this->indexToColumnName($i);
                            $cell = $sheet->getCell($colLetter . $row);
                        
                            // ✅ Always take formatted value
                            $value = $cell->getFormattedValue();
                        
                            if ($value instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                                $value = $value->getPlainText();
                            }
                        
                            $index = trim((string) $sheet->getCell($colLetter . 2)->getValue());
                        
                            // Transform known columns
                            switch ($index) {
                                case 'Class':
                                    $classType = ClassType::where('name', $value)
                                                ->where('branch_id', Session::get('branch_id'))
                                                ->first();
                                    $value = $classType->id ?? '';
                                    break;
                                case 'Admission Type':
                                    $admissionTypeMapping = ["Yes" => 1, "No" => 2];
                                    $value = $admissionTypeMapping[$value] ?? '1';
                                    break;
                                case 'Gender':
                                    $genderType = Gender::where('name', $value)->first();
                                    $value = $genderType->id ?? '';
                                    break;
                                case 'State':
                                    $state = State::where('name', $value)->first();
                                    $value = $state->id ?? '';
                                    break;
                                case 'City':
                                    $city = City::where('name', $value)->first();
                                    $value = $city->id ?? '';
                                    break;
                                case 'Date Of  Birth':
                                case 'Date Of Admission':
                                    $value = $this->convertExcelDate($value);
                                    break;
                                case 'Mobile No.':
                                    $value = preg_replace('/[^0-9]/', '', (string)$value);
                                    break;
                            }
                        
                            // Skip SR.NO and unknown fields
                            if ($index != 'SR.NO' && isset($array[$index]) && Schema::hasColumn('admissions', $array[$index])) {
                                $val[$array[$index]] = $value;
                            }
                        
                            // Username setup
                            if ($colLetter === 'B') {
                                $columnBValue = $value ?? '';
                            }
                        
                            
                        }
                        $maxAttendanceId = Admission::selectRaw('MAX(CAST(attendance_unique_id AS UNSIGNED)) as max_id')->value('max_id');


                    // Add default values
                    $val['session_id'] = Session::get('session_id');
                    $val['user_id'] = Session::get('id');
                    $val['branch_id'] = Session::get('branch_id');
                    $val['status'] = 1;
                    $val['school'] = 1;
                    $val['attendance_unique_id'] = str_pad(($maxAttendanceId + 1), 4, '0', STR_PAD_LEFT);
                    $val['unique_system_id'] = strtoupper(Str::random(10));
                    $val['userName'] = $columnBValue;
                    $val['password'] = Hash::make($columnBValue);
                    $val['confirm_password'] = $columnBValue;
                   // $val['admission_date'] = date('Y-m-d');

                    $val2[] = $val;
                }
        //dd($val2);
                DB::table('admissions')->insert($val2);
            } catch (Exception $e) {
                Log::error("Student import error: " . $e->getMessage());
                return redirect('admissionAdd')->with('error', 'Error: Student Not Added!');
            }
        
            return redirect('admissionView')->with('message', 'Student Add Successful!');
        }
       
      function indexToColumnName($index) {
                $columnName = '';
                    while ($index >= 0) {
                        $columnName = chr(($index % 26) + 65) . $columnName;
                        $index = intdiv($index, 26) - 1;
                    }
                return $columnName;
            }
          
      function columnLetterToNumber($columnLetter) {
                $columnNumber = 0;
                $length = strlen($columnLetter);
                    for ($i = 0; $i < $length; $i++) {
                        $columnNumber = $columnNumber * 26 + (ord($columnLetter[$i]) - ord('A') + 1);
                    }
                return $columnNumber;
            }

          protected function convertExcelDate($date)
                    {
                        if (empty($date)) {
                            return null;
                        }
                    
                        // ✅ Excel numeric date (serial number)
                        if (is_numeric($date)) {
                            try {
                                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)
                                    ->format('Y-m-d');
                            } catch (\Exception $e) {
                                return null;
                            }
                        }
                    
                        // ✅ String date with multiple formats
                        $formats = ['Y-m-d', 'd-m-Y', 'm-d-Y', 'd/m/Y', 'm/d/Y', 'd.m.Y', 'm.d.Y'];
                    
                        foreach ($formats as $format) {
                            try {
                                return \Carbon\Carbon::createFromFormat($format, $date)->format('Y-m-d');
                            } catch (\Exception $e) {
                                continue;
                            }
                        }
                    
                        // ✅ Fallback: let Carbon try automatically
                        try {
                            return \Carbon\Carbon::parse($date)->format('Y-m-d');
                        } catch (\Exception $e) {
                            return null;
                        }
                    }*/




            }


