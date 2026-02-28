@php
$getAdmissionDatatableFields = Helper::getAdmissionDatatableFields();
$classType = Helper::classType();
$getState = Helper::getState();
$getcitie = Helper::getCity();
$getgenders = Helper::getgender();
$getCountry = Helper::getCountry();
$bloodGroupType = Helper::bloodGroupType();
$gender = DB::table('gender')->whereNull('deleted_at')->pluck('name')->implode(',');
$villageList = DB::table('custom_villages_list')->whereNull('deleted_at')->pluck('name')->implode(',');
$class = DB::table('class_types')->whereNull('deleted_at')->pluck('name')->implode(',');

$setting = Db::table('settings')->whereNull('deleted_at')->first();
$stateList = DB::table('states')->where('id', 13)->pluck('name')->implode(',');
$cityList = DB::table('citys')->whereNull('deleted_at')->where('state_id', 13)->take(25)->pluck('name')->implode(',');
$bloodgroupList = DB::table('blood_groups')->whereNull('deleted_at')->pluck('name')->implode(',');
$getSession = Helper::getSession();

@endphp
@extends('layout.app')
@section('content')

<style>
    .fixed_item {
        position: sticky !important;
        right: -8px;
        background-color: white;
        z-index: 111;
        box-shadow: -6px 2px 6px #cecece;
    }

    .dropdown-menu.show {
        left: -79px !important;
    }

    .flex_centered {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 46px;
        width: 105px;
    }

    .flex_centered a {
        margin-left: 3px;
    }

    .nowrap {
        white-space: nowrap;
        font-size: 14px;
    }

    .colored_table thead tr {
        background-color: #002c54;
        color: white;
    }

    .colored_table thead tr th {
        padding: 10px;
    }

    .overflow_scroll {
        height: 250px;
        overflow: scroll;
    }
</style>

<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary flex_items_toggel">
                            <h3 class="card-title"><i class="fa fa-address-book-o"></i> &nbsp;{{ __('Admission List') }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#BulkImages">
                                    <i class="fa fa-picture-o" aria-hidden="true"></i> Bulk Images </button>
                                <a href="{{url('admissionAdd')}}"
                                    class="btn btn-primary  btn-sm {{ Helper::permissioncheck(3)->add ? '' : 'd-none' }}"><i
                                        class="fa fa-plus"></i><span class="Display_none_mobile"> {{ __('common.Add') }}
                                    </span></a>
                                <a href="{{url('studentsDashboard')}}" class="btn btn-primary  btn-sm"><i
                                        class="fa fa-arrow-left"></i><span class="Display_none_mobile"> {{
                                        __('common.Back') }} </span></a>
                            </div>

                        </div>

                        <!-- The Modal -->
                        <div class="modal" id="BulkImages">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">Bulk Images Upload & Download</h4>

                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <form action="{{ url('studentBulkImageUpload') }}" method="post"
                                            enctype='multipart/form-data'>
                                            @csrf
                                            <div class="row g-3">
                                                <!-- Image Upload Input -->
                                                <div class="col-6 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label>Choose Images <span class="text-danger">*</span></label>
                                                        <input type="file" class="form-control" name="image[]" multiple
                                                            required />
                                                    </div>
                                                </div>

                                                <!-- Bulk Upload Button -->
                                                <div class="col-3 col-md-2 col-lg-1 mt-4">
                                                    <button type="submit" class="btn btn-success tooltip1"
                                                        title1="Images Upload">
                                                        <i class="fa fa-upload" aria-hidden="true"></i>

                                                    </button>
                                                </div>

                                                <!-- Bulk Download Button -->
                                                <div class="col-3 col-md-2 col-lg-1 mt-4">
                                                    <button type="button" class="btn btn-info  tooltip1"
                                                        id="downloadZip" title1="Bulk Images Download [Zip]">
                                                        <i class="fa fa-arrow-circle-down" aria-hidden="true"></i>

                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>


                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <hr class="m-0 ml-2 mr-2 border-secondary">

                        <div class="row m-2">

                            <form id="quickForm" action="{{ url('admissionView') }}" method="post">
                                @csrf
                                <div class="row ">

                                    {{-- Category --}}
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select class="form-control" id="category" name="category">
                                                <option value="">{{ __('common.Select') }}</option>
                                                <option value="OBC" {{ old('category', $search['category'] ?? ''
                                                    )=='OBC' ? 'selected' : '' }}>OBC</option>
                                                <option value="ST" {{ old('category', $search['category'] ?? '' )=='ST'
                                                    ? 'selected' : '' }}>ST</option>
                                                <option value="SC" {{ old('category', $search['category'] ?? '' )=='SC'
                                                    ? 'selected' : '' }}>SC</option>
                                                <option value="BC" {{ old('category', $search['category'] ?? '' )=='BC'
                                                    ? 'selected' : '' }}>BC</option>
                                                <option value="GEN" {{ old('category', $search['category'] ?? ''
                                                    )=='GEN' ? 'selected' : '' }}>GEN</option>
                                                <option value="SBC" {{ old('category', $search['category'] ?? ''
                                                    )=='SBC' ? 'selected' : '' }}>SBC</option>
                                                <option value="Other" {{ old('category', $search['category'] ?? ''
                                                    )=='Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Class --}}
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('common.Class') }}</label>
                                            <select
                                                class="form-control select2 @error('class_type_id') is-invalid @enderror"
                                                id="class_type_id" name="class_type_id">
                                                <option value="">{{ __('common.Select') }}</option>
                                                @if(!empty($classType))
                                                @foreach($classType as $type)
                                                <option value="{{ $type->id }}" {{ old('class_type_id',
                                                    $search['class_type_id'] ?? '' )==$type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('class_type_id')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message
                                                    }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="">{{ __('common.Select') }}</option>
                                                <option value="1" {{ old('status', $search['status'] ?? '' )==1
                                                    ? 'selected' : '' }}>{{ __('Continue') }}</option>
                                                <option value="0" {{ old('status', $search['status'] ?? '' )==0
                                                    ? 'selected' : '' }}>{{ __('Discontinue') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Gender --}}
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>{{ __('common.Gender') }}</label>
                                            <select class="form-control" id="gender_id" name="gender_id">
                                                <option value="">{{ __('common.Select') }}</option>
                                                @if(!empty($getgenders))
                                                @foreach($getgenders as $value)
                                                <option value="{{ $value->id }}" {{ old('gender_id',
                                                    $search['gender_id'] ?? '' )==$value->id ? 'selected' : '' }}>
                                                    {{ $value->name }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Admission Type --}}
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Ad. Type</label>
                                            <select class="form-control" id="admission_type_id"
                                                name="admission_type_id">
                                                <option value="">{{ __('common.Select') }}</option>
                                                <option value="1" {{ old('admission_type_id',
                                                    $search['admission_type_id'] ?? '' )==1 ? 'selected' : '' }}>Non RTE
                                                </option>
                                                <option value="2" {{ old('admission_type_id',
                                                    $search['admission_type_id'] ?? '' )==2 ? 'selected' : '' }}>RTE
                                                </option>
                                            </select>
                                            <span class="invalid-feedback" id="admission_type_id_invalid" role="alert">
                                                <strong>The Admission Type is required</strong>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Blood Group --}}
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Blood G.</label>
                                            <select class="form-control" id="blood_group" name="blood_group">
                                                <option value="">{{ __('common.Select') }}</option>
                                                @if(!empty($bloodGroupType))
                                                @foreach($bloodGroupType as $bloodtype)
                                                <option value="{{ $bloodtype->name }}" {{ old('blood_group',
                                                    $search['blood_group'] ?? '' )==$bloodtype->name ? 'selected' : ''
                                                    }}>
                                                    {{ $bloodtype->name }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Search Type --}}
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Search Type</label>
                                            <select class="form-control" id="search_type" name="search_type">
                                                <option value="">{{ __('common.Select') }}</option>
                                                <option value="first_name" {{ old('search_type', $search['search_type']
                                                    ?? '' )=='first_name' ? 'selected' : '' }}>Name</option>
                                                <option value="admissionNo" {{ old('search_type', $search['search_type']
                                                    ?? '' )=='admissionNo' ? 'selected' : '' }}>Admission No</option>
                                                <option value="father_name" {{ old('search_type', $search['search_type']
                                                    ?? '' )=='father_name' ? 'selected' : '' }}>Father Name</option>
                                                <option value="mother_name" {{ old('search_type', $search['search_type']
                                                    ?? '' )=='mother_name' ? 'selected' : '' }}>Mother Name</option>
                                                <option value="mobile" {{ old('search_type', $search['search_type']
                                                    ?? '' )=='mobile' ? 'selected' : '' }}>Mobile</option>
                                                <option value="aadhaar" {{ old('search_type', $search['search_type']
                                                    ?? '' )=='aadhaar' ? 'selected' : '' }}>Aadhaar</option>
                                                <option value="jan_aadhaar" {{ old('search_type', $search['search_type']
                                                    ?? '' )=='jan_aadhaar' ? 'selected' : '' }}>Jan Aadhaar</option>
                                                <option value="address" {{ old('search_type', $search['search_type']
                                                    ?? '' )=='address' ? 'selected' : '' }}>Address</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Name / Keywords --}}
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{ __('common.Search By Keywords') }}</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name"
                                                placeholder="{{ __('Ex. Name, Admission No, Mobile, Aadhaar etc.') }}"
                                                value="{{ old('name', $search['name'] ?? '') }}">
                                            @error('name')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message
                                                    }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="col-md-1 ">
                                        <div class="Display_none_mobile">
                                            <label class="text-white">{{ __('common.Search') }}</label>
                                        </div>
                                        <button type="submit" class="btn btn-primary">{{ __('common.Search') }}</button>
                                    </div>

                                </div>
                            </form>

                        </div>

                        <hr class="m-0 ml-2 mr-2">
                        <div class="row m-2">
                            <div class="col-12" style="overflow-x:scroll;">
                               
                                <button type="button"
                                        id="multiDeleteBtn"
                                        class="btn btn-danger btn-sm"
                                        style="display:none; margin-bottom:10px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#multiDeleteModal">
                                    <i class="fa fa-trash"></i> Delete Selected
                                </button>

        
                                <table id="studentList"
                                    class="table table-bordered table-striped dataTable dtr-inline nowrap">
                                    <thead id='main_thead' class="bg-primary">
                                        <tr role="row">
                                            <th style="width: 36px;">{{ __('common.SR.NO') }}</th>


                                            @php
                                            $table = DB::table('datatable_fields')->first();
                                            $dataTable = explode(',',$table->fields);
                                            @endphp

                                            @if($dataTable)
                                            @foreach($dataTable as $val)

                                            <th class="text-center">{{ $val }}</th>
                                            @endforeach
                                            @endif

                                            <th class="fixed_item bg-primary"><span>{{ __('common.Action') }}</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="product_list_show">

                                        @if(!empty($data))
                                        @php
                                        $i=0;
                                        @endphp
                                        @foreach ($data as $item)
                                        @php

                                        $blood_group =
                                        DB::table('blood_groups')->whereNull('deleted_at')->where('id',$item->blood_group)->first();
                                        $genderName =
                                        DB::table('gender')->whereNull('deleted_at')->where('id',$item->gender_id)->first();
                                        @endphp
                                        <tr>
                                            <td style="vertical-align: inherit;">
                                                <input type='checkbox' class='checkbox_id'
                                                    data-admission_no="{{$item['admissionNo'] ?? ''}}"
                                                    data-name="{{ $item['first_name'] ?? '' }} {{ $item['last_name'] ?? '' }}"
                                                    data-mobile="{{$item['mobile'] ?? ''}}"
                                                    data-father_name="{{$item['father_name'] ?? ''}}"
                                                    value='{{$item->id}}' style="width: 19px;height: 18px;"> {{++$i}}
                                            </td>


                                            @if($dataTable)
                                            @foreach($dataTable as $val)

                                            @if($val == 'Student Photo')
                                            <!-- Table Image -->
                                            <td class="text-center">
                                                <img width="50px" height="50px" style="border-radius:3px;padding:1px"
                                                    class="profileImg pointer" data-id="{{ $item['id'] ?? '' }}"
                                                    src="{{ env('IMAGE_SHOW_PATH').'profile/'.$item['image'] }}"
                                                    onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/user_image.jpg' }}'">
                                                <div style="display: none;">{{$item['image'] ?? ''}}</div>
                                            </td>

                                            @elseif($val == 'Student Name')
                                            <td class="myBtn  editable" style="cursor:pointer;"
                                                data-id="{{$item->id ?? ''}}" data-field='first_name'
                                                data-modal='Admission'>{{ $item['first_name'] ?? '' }} {{
                                                $item['last_name'] ?? '' }}</td>
                                            @elseif($val == 'Date Of .Birth')
                                            <td>@if(!empty($item['dob'])) {{ date('d-m-Y', strtotime($item['dob'])) ??
                                                '' }}@endif</td>
                                            @elseif($val == 'State')
                                            <td>{{ $item['State']['name'] ?? '-' }}</td>
                                            @elseif($val == 'City')
                                            <td>{{ $item['City']['name'] ?? '-' }}</td>
                                            @elseif($val == 'Blood Group')
                                            <td>{{ $blood_group->name ?? '-' }}</td>
                                            @elseif($val == 'Gender')
                                            <td>{{ $genderName->name ?? '-' }}</td>
                                            @elseif($val == 'Admission Type(Non RTE)')

                                            <td class="text-center">
                                                @if($item['admission_type_id'] == 1)
                                                <p>Non RTE</p>

                                                @elseif($item['admission_type_id'] == 2)

                                                <p>RTE</p>
                                                @endif
                                            </td>
                                            @elseif($val == 'Date Of Admission')
                                            <td>
                                                @if(!empty($item['admission_date']))

                                                {{date('d-m-Y', strtotime($item['admission_date'])) ?? '' }}

                                                @endif
                                            </td>
                                            @elseif($val == 'Class')
                                            <td>{{ $item['ClassTypes']['name'] ?? '-' }}</td>
                                            @elseif($val == 'Fees Progress')
                                            @php
                                            $fees_assign = App\Models\fees\FeesAssignDetail::
                                            where('admission_id', $item->id)
                                            ->sum('fees_group_amount');

                                            $payFees = DB::table('fees_detail')
                                            ->where('session_id', Session::get('session_id'))
                                            ->where('admission_id', $item->id)
                                            ->whereNull('deleted_at')
                                            ->whereIn('status', [0,1])
                                            ->sum('total_amount');

                                            $paidPercentage = 0;
                                            if ($fees_assign > 0) {
                                            $paidPercentage = round(($payFees / $fees_assign) * 100, 2);
                                            }
                                            @endphp

                                            <td class="text-center">
                                                <div class="progress">
                                                    <div class="progress-bar text-dark" role="progressbar"
                                                        aria-valuenow="{{$paidPercentage ?? 0}}" aria-valuemin="0"
                                                        aria-valuemax="100"
                                                        style="width: {{$paidPercentage ?? 0}}%; min-width: 30px;">
                                                        {{$paidPercentage ?? 0}}%
                                                    </div>
                                                </div>
                                            </td>
                                            @elseif($val == 'Attendance Unique Id')

                                            <td>{{ $item['attendance_unique_id'] ?? '-' }}</td>
                                            @else
                                            @php
                                            $field = $getAdmissionDatatableFields[$val] ?? null;
                                            @endphp

                                            <td class="text-center editable" data-id="{{ $item->id ?? '' }}"
                                                data-field="{{ $field }}" data-modal="Admission">
                                                {{ $field ? ($item[$field] ?? '') : '' }}
                                            </td>

                                            @endif
                                            @endforeach
                                            @endif



                                            <td class="fixed_item">
                                                <div class="flex_centered">
                                                    <a href="{{ url('studentDetail') }}/{{ $item['id'] ?? '' }}"
                                                        class="{{ Helper::permissioncheck(3)->view ? '' : 'd-none' }}">
                                                        <button class="btn btn-primary  btn-xs tooltip1"
                                                            title1="Student Details"><i
                                                                class="fa fa-arrow-circle-right"></i></button>
                                                    </a>
                                                    <a href="{{url('admissionStudentPrint')}}/{{$item->id}}"
                                                        target="blank"
                                                        class="{{ Helper::permissioncheck(3)->print ? '' : 'd-none' }}">
                                                        <button class="btn btn-success btn-xs tooltip1"
                                                            title1="Student Admission Print"><i
                                                                class="fa fa-print"></i></button>
                                                    </a>
                                                    <button
                                                        class="btn btn-primary btn-xs openEditModal tooltip1 {{ Helper::permissioncheck(3)->edit ? '' : 'd-none' }}"
                                                        data-id="{{ $item->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#editStudentModal" title1="Edit Student"
                                                        style="margin-left: 3px;">
                                                        <i class="fa fa-edit"></i>
                                                    </button>


                                                    <a href="javascript:;" data-id='{{$item->id}}'
                                                        data-bs-toggle="modal" data-bs-target="#Modal_id"
                                                        class="deleteData {{ Helper::permissioncheck(3)->delete ? '' : 'd-none' }}">
                                                        <button class="btn btn-danger btn-xs tooltip1"
                                                            title1="Delete"><i class="fa fa-trash-o"></i></button>
                                                    </a>
                                                </div>
                                            </td>

                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title">Admission Edit</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Body (Form via AJAX load) -->
            <div class="modal-body" id="editStudentContent">
                <!-- AJAX se form yahan load hoga -->
            </div>

        </div>
    </div>
</div>



<!-- Single Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="imageForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="admission_id" id="admission_id">
            <input type="hidden" name="action_type" id="action_type" value="upload">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Student Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    <img id="previewImgModal" src="" style="max-width:150px; border-radius:5px;">
                    <input type="file" name="student_img" id="student_img" class="form-control mt-2">
                </div>

                <div class="modal-footer" style="display:flex;">
                    <button type="button" class="btn btn-danger" id="deleteBtn"><i class="fa fa-trash"
                            aria-hidden="true"></i></button>
                    <button type="button" class="btn btn-primary" id="rotateBtn"><i class="fa fa-repeat"
                            aria-hidden="true"></i></button>
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- The Modal -->
<div class="modal" id="Modal_id">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #555b5beb;">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-white">Delete Confirmation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times"
                        aria-hidden="true"></i></button>
            </div>

            <!-- Modal body -->
            <form action="{{ url('admissionDelete') }}" method="post">
                @csrf
                <div class="modal-body">



                    <input type=hidden id="delete_id" name=delete_id>
                    <h5 class="text-white">Are you sure you want to delete ?</h5>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">Delete</button>
                </div>
            </form>

        </div>
    </div>
</div>


<div class="modal fade" id="multiDeleteModal">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #555b5beb;">

            <div class="modal-header">
                <h4 class="modal-title text-white">Delete Confirmation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ url('admissionDelete') }}" method="post">
                @csrf

                <div class="modal-body">

                    <!-- Hidden input for multiple IDs -->
                    <input type="hidden" name="ids" id="multi_delete_ids">

                    <h5 class="text-white">
                        Are you sure you want to delete selected students?
                    </h5>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit"
                            class="btn btn-danger">
                        Delete
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


<div id="datatableFieldsModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="form-group clearfix">
                    <div class="icheck-success d-inline">
                        <input type="checkbox" id="master_checkbox">
                        <label for="master_checkbox">Select All</label>
                    </div>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{url('saveAdmissionDatatableFields')}}" method='post'>
                    @csrf
                    <div class="row">
                        @if(!empty($getAdmissionDatatableFields))
                        @foreach($getAdmissionDatatableFields as $key => $dataFields)
                        @if($key != 'SR.NO')
                        <div class="col-md-3">
                            <div class="form-group clearfix">
                                <div class="icheck-success d-inline">
                                    <input type="checkbox" class="checkbox" id="field_{{ $dataFields ?? '' }}"
                                        name='fields[]' {{ in_array($key, $dataTable) ? 'checked' : '' }}
                                        value="{{ $key ?? '' }}">
                                    <label for="field_{{ $dataFields ?? '' }}">{{ $key ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endif
                        <div class="col-md-12 text-center">
                            <button class='btn btn-primary' type='submit'>Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="myLargeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Compose Message</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">


                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-xs message-button" data-message="Hello {#name#},
            
            This is Administrator from {#school_name#}. I hope this message finds you well.
            
            We are pleased to provide you with your credentials to access our schoolâ€™s online platform:
            
            APK: https://demo3.rusoft.in/schoolimage/default/GreenGarden.apk
            Username: {#user_name#}
            Password: {#password#}
            Please ensure you keep these credentials secure and do not share them with others. If you have any questions or need further assistance, feel free to contact us at {#school_mobile#}.
            
            Thank you and have a great day!
            
            Best regards,
            Administrator
            {#school_name#}
            {#school_mobile#}">Credentials</button>
                        <button class="btn btn-info btn-xs message-button" data-message="Hello {#name#},
            
            This is Administrator from {#school_name#}. I hope this message finds you well.
            
            
            
            
            
            
            If you have any questions or need further assistance, feel free to contact us at {#school_mobile#}.
            
            Thank you and have a great day!
            
            Best regards,
            Administrator
            {#school_name#}
            {#school_mobile#}">Message Format</button>


                        <button class="btn btn-success btn-xs message-button" data-message="Hello {#name#} ðŸŽ‰âœ¨,
            
            This is Administrator from {#school_name#}. I hope this message finds you well.
            
            We are delighted to extend our warmest wishes to you and your family on this joyous occasion of Diwali ðŸª”. May the festival of lights bring you peace, prosperity, and happiness.
            
            Please enjoy the celebrations safely and cherish these special moments with your loved ones ðŸŽ‡ðŸŽ†. If you have any questions or need further assistance, feel free to contact us at {#school_mobile#}.
            
            Thank you and have a great day! ðŸŒŸ
            
            Best regards,
            Administrator
            {#school_name#}
            {#school_mobile#}">Diwali Wishes</button>
                        <button class="btn btn-dark btn-xs message-button" data-message="Hello {#name#},
            
            This is Administrator from {#school_name#}. I hope this message finds you well.
            
            We are excited to extend our heartfelt wishes to you and your family on the vibrant festival of Holi. May your life be filled with the colors of joy, happiness, and love.
            
            Please enjoy the festivities safely and create beautiful memories with your loved ones. If you have any questions or need further assistance, feel free to contact us at {#school_mobile#}.
            
            Thank you and have a great day!
            
            Best regards,
            Administrator
            {#school_name#}
            {#school_mobile#}">Holi Wishes</button>


                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <!-- Message Input -->
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Message:</label>
                            <textarea class="form-control" id="message-text" rows='10' required></textarea>
                        </div>

                        <!-- Attachment Input -->
                        <div class="form-group">
                            <div class='d-flex text-center'>
                                <div class='mr-3'>
                                    <img id='img_preview' style='border:1px solid #888;border-radius:5px;padding:5px'
                                        src='https://demo3.rusoft.in/schoolimage/default/6605525.jpg' width='80px'
                                        height='80px' />
                                </div>
                                <div class='text-left'>
                                    <label for="attachment-file" class="col-form-label">Attachment:</label>
                                    <input type="file" class="form-control" id="attachment-file" accept="image/*">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column (col-md-8) -->
                    <div class="col-md-8 overflow_scroll">
                        <span class='text-danger' style='font-size:11px'>Note: In the event that a mobile number is not
                            available,the system will omit that particular user from processing. <br></span>

                        <table class="table table-striped table-bordered">
                            <thead id='secondary_thead'>
                                <tr>
                                    <th>Admission No</th>
                                    <th>Name</th>
                                    <th>F Name</th>
                                    <th>Mobile</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id='secondary_tbody'>

                            </tbody>
                        </table>

                    </div>

                    <div class="col-md-12">
                        <hr class=" border-muted">
                        <h5 class="modal-title mb-1" id="myModalLabel">Today's Sent Messages</h5>
                        <!-- Table for Rows -->
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Message Id</th>
                                    <th>Message</th>
                                    <th>Attachment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id='third_tbody'>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class='text-left'><span style='display:none' class='previousIds'><input type='checkbox'
                            id='previousIds' /> Do Not Use Previous Id's</span><br>
                    <button type="button" class="btn btn-info" id="reset_modal">Reset</button>
                    <button type="button" class="btn btn-secondary" id="close_modal">Close</button>
                    <button type="submit" class="btn btn-primary" id='sendButton'>Send</button>
                </div>

            </div>

        </div>
    </div>
</div>


<script>
    $(document).on("click", ".openEditModal", function () {
        var id = $(this).data("id");

        $.ajax({
            url: "/admissionEdit/" + id,
            type: "GET",
            success: function (data) {
                $("#editStudentContent").html(data);
                $("#editStudentModal").modal("show");
            },
            error: function () {
                alert("Something went wrong while loading form.");
            }
        });
    });

    $(document).ready(function () {
        $('.message-button').click(function () {
            var message = $(this).data('message');

            $('#message-text').val(message);
        });
    });
</script>

<script>
    let currentImgTag = null;
    let currentImgId = '';
    let rotationAngle = 0;
    const csrf_token = '{{ csrf_token() }}';
    const save_url = '{{ url("imageRotateSave") }}';

    // Open modal and set preview
    $('.profileImg').click(function () {
        currentImgTag = this;
        currentImgId = $(this).data('id');
        rotationAngle = 0; // reset rotation

        const src = $(this).attr('src') || '';
        $('#previewImgModal').attr('src', src);
        $('#admission_id').val(currentImgId);
        $('#action_type').val('upload');

        var modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    });

    // Preview selected file
    $('#student_img').on('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => $('#previewImgModal').attr('src', e.target.result);
        reader.readAsDataURL(file);
    });

    // Single AJAX function: upload or delete
    function sendImageRequest(action) {
        let formData = new FormData();
        formData.append('_token', csrf_token);
        formData.append('admission_id', currentImgId);
        formData.append('action_type', action);

        if (action === 'upload') {
            let file = $('#student_img')[0].files[0];
            if (!file) { alert('Select image first'); return; }
            formData.append('student_img', file);
        }

        $.ajax({
            url: save_url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.success) {
                    const newUrl = res.image_url + '?t=' + new Date().getTime();
                    $(currentImgTag).attr('src', newUrl);
                    $('#previewImgModal').attr('src', newUrl);

                    if (action === 'delete') {
                        $('#student_img').val('');
                    }

                    // Hide modal
                    var modalEl = document.getElementById('imageModal');
                    bootstrap.Modal.getOrCreateInstance(modalEl).hide();

                    toastr.success(res.message || 'Success!');
                } else {
                    alert(res.message || 'Action failed!');
                }
            },
            error: function () {
                alert('Something went wrong!');
            }
        });
    }

    // Upload
    $('#imageForm').on('submit', function (e) {
        e.preventDefault();
        sendImageRequest('upload');
    });

    // Delete
    $('#deleteBtn').click(function () { sendImageRequest('delete'); });

    // Rotate
    $('#rotateBtn').click(function () {
        const imgEl = document.getElementById('previewImgModal');
        if (!imgEl || !imgEl.src) return;

        rotationAngle += 90;
        rotationAngle %= 360;

        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.src = imgEl.src;

        img.onload = function () {
            if (rotationAngle % 180 === 0) {
                canvas.width = img.width;
                canvas.height = img.height;
            } else {
                canvas.width = img.height;
                canvas.height = img.width;
            }

            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.rotate(rotationAngle * Math.PI / 180);
            ctx.drawImage(img, -img.width / 2, -img.height / 2);

            const dataUrl = canvas.toDataURL('image/jpeg');
            $('#previewImgModal').attr('src', dataUrl);
            $(currentImgTag).attr('src', dataUrl);

            // Save rotated image to server
            canvas.toBlob(function (blob) {
                let formData = new FormData();
                formData.append('_token', csrf_token);
                formData.append('admission_id', currentImgId);
                formData.append('action_type', 'upload');
                formData.append('student_img', blob, 'rotated.jpg');

                $.ajax({
                    url: save_url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        if (res.success) {
                            var newUrl = res.image_url + '?t=' + new Date().getTime();
                            $('#previewImgModal').attr('src', newUrl);
                            $(currentImgTag).attr('src', newUrl);
                            // toastr.success(res.message || 'Rotated successfully!');
                        } else {
                            alert(res.message || 'Rotate save failed!');
                        }
                    },
                    error: function () { alert('Something went wrong during save!'); }
                });
            }, 'image/jpeg', 0.9);
        };
    });
</script>
<script>
    $('.deleteData').click(function () {
        var delete_id = $(this).data('id');

        $('#delete_id').val(delete_id);
    });


</script>
<script>


    $('#reset_modal').click(function () {
        $('#studentList_wrapper').find('#btn-whatsapp').trigger('click');
        // $('#myLargeModal').modal('hide');

    });

    $(document).ready(function () {

        var dataCount = parseInt('{{count($data)}}');

        $("#studentList").DataTable({
            "processing": true, // Show processing indicator
            "ordering": true,
            "searching": true,
            "serverSide": false,
            "lengthChange": false, "autoWidth": false, "lengthChange": true, // Default number of rows per page
            "lengthMenu": [10, 20, 50, dataCount],
            "buttons": [{
                text: 'Column Visibility',
                action: function (e, dt, node, config) {
                    $('#datatableFieldsModal').modal('show');
                }
            }, {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible:not(:last-child)' // Export only visible columns except the last one
                },
                customize: function (xlsx) {
                    var cellName = '';
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var rows = sheet.getElementsByTagName('row');
                    $('row:first c', sheet).attr('s', '2'); // first row is bold
                    // Create a new row after A1
                    var newRow = '<row r="2"><c t="inlineStr" r="A2"><is><t></t></is></c></row>';
                    var newNode = $.parseXML(newRow).documentElement;

                    // Adjust the row numbers for subsequent rows
                    for (var i = 1; i < rows.length; i++) {
                        var row = rows[i];
                        var rowIndex = parseInt(row.getAttribute('r'), 10);
                        row.setAttribute('r', rowIndex + 1);


                        // Adjust cell references within the row
                        var cells = row.getElementsByTagName('c');
                        for (var j = 0; j < cells.length; j++) {
                            var cell = cells[j];
                            var cellRef = cell.getAttribute('r');
                            var newCellRef = cellRef.replace(/(\d+)/, function (match) {
                                return parseInt(match, 10) + 1;
                            });
                            cell.setAttribute('r', newCellRef);
                        }
                    }

                    // Insert the new row after the first row
                    sheet.getElementsByTagName('row')[0].parentNode.insertBefore(newNode, rows[1]);

                    // Modify all header cells' background color, font size, color, and border
                    var styles = xlsx.xl['styles.xml'];
                    var fills = styles.getElementsByTagName('fills')[0];
                    var fonts = styles.getElementsByTagName('fonts')[0];
                    var borders = styles.getElementsByTagName('borders')[0];

                    // Add new fill
                    var fillIndex = fills.childNodes.length;
                    var fill = $.parseXML('<fill><patternFill patternType="solid"><fgColor rgb="6639b5"/></patternFill></fill>').documentElement;
                    fills.appendChild(fill);

                    // Add new font
                    var fontIndex = fonts.childNodes.length;
                    var font = $.parseXML('<font><sz val="14"/><color rgb="ffffff"/></font>').documentElement;
                    fonts.appendChild(font);

                    // Add new border
                    var borderIndex = borders.childNodes.length;
                    var border = $.parseXML('<border><left style="thin"/><right style="thin"/><top style="thin"/><bottom style="thin"/></border>').documentElement;
                    borders.appendChild(border);

                    // Add new xf for the cell
                    var cellXfs = styles.getElementsByTagName('cellXfs')[0];
                    var xfIndex = cellXfs.childNodes.length;
                    var xf = $.parseXML('<xf applyFill="1" applyFont="1" applyBorder="1" fontId="' + fontIndex + '" fillId="' + fillIndex + '" borderId="' + borderIndex + '">' + '<alignment vertical="center"/>' + '</xf>').documentElement;
                    cellXfs.appendChild(xf);

                    // Apply the style to all header cells
                    var headerCells = sheet.querySelectorAll('row:first-of-type c');
                    headerCells.forEach(function (cell) {
                        cell.setAttribute('s', xfIndex);
                    });

                    var dataCells = sheet.querySelectorAll('row:not(:first-of-type) c');
                    var dataValidations = sheet.getElementsByTagName('dataValidations')[0];
                    if (!dataValidations) {
                        dataValidations = sheet.createElement('dataValidations');
                        sheet.getElementsByTagName('worksheet')[0].appendChild(dataValidations);
                    }

                    // Iterate through the rows and add data validation to each V4 cell
                    var numberOfRows = dataCells.length;
                    // Apply the same style to cells with text from <th>
                    var tableHeaders = $('#studentList thead th');
                    var count = -1;
                    var head = '';
                    tableHeaders.each(function (index, th) {
                        count++;
                        var thText = $(th).text();
                        var headerCells = sheet.querySelectorAll('row c[r^="' + String.fromCharCode(65 + index) + '"] is t');
                        head = thText;


                        headerCells.forEach(function (headerCell) {

                            if (headerCell.textContent === thText) {

                                // headerCell.parentElement.parentElement.setAttribute('s', xfIndex);
                            }
                        });
                        function indexToColumnName(index) {
                            let columnName = '';
                            while (index >= 0) {
                                columnName = String.fromCharCode((index % 26) + 65) + columnName;
                                index = Math.floor(index / 26) - 1;
                            }

                            return columnName;
                        }

                        if (head === 'Class') {
                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;

                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"{{$class}}"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }

                        }

                        if (head === 'Gender') {
                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;
                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"{{$gender}}"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }


                        }

                        if (head === 'State') {

                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;

                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"{{$stateList}}"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }


                        }


                        if (head === 'City') {

                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;

                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"{{$cityList}}"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }


                        }


                        if (head === 'Blood Group') {

                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;

                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"{{$bloodgroupList}}"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }


                        }

                        if (head === 'Admission Type') {
                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;
                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"Non RTE,RTE"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }

                        }

                        if (head === 'Income Tax Payee Father') {
                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;
                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"Yes,No"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }

                        }

                        if (head === 'Income Tax Payee Mother') {
                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;
                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"Yes,No"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }

                        }

                        if (head === 'Income Tax Payee Mother') {
                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;
                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"Yes,No"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }

                        }

                        if (head === 'BPL') {
                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;
                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"Yes,No"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }

                        }

                        if (head === 'Religion') {
                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;

                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"HINDU,ISLAM,SIKH,BUDDHISM,ADIVASI,JAIN,CHRISTIANITY,OTHER"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }

                        }
                        if (head === 'Category') {
                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;

                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"OBC,SC,ST,BC,GEN,SBC,Other"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }

                        }

                        if (head === 'Transport') {
                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;
                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"Yes,No"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }

                        }
                        if (head === 'Village') {
                            for (var rowIndex = 4; rowIndex <= numberOfRows; rowIndex++) {
                                var cellRef = indexToColumnName(count) + rowIndex;
                                var dataValidation = sheet.createElement('dataValidation');
                                dataValidation.setAttribute('type', 'list');
                                dataValidation.setAttribute('allowBlank', '1');
                                dataValidation.setAttribute('showInputMessage', '1');
                                dataValidation.setAttribute('showErrorMessage', '1');
                                dataValidation.setAttribute('sqref', cellRef); // Apply to the current V cell
                                var formula1 = sheet.createElement('formula1');
                                formula1.textContent = '"{{$villageList}}"'; // Options for the dropdown
                                dataValidation.appendChild(formula1);
                                dataValidations.appendChild(dataValidation);
                            }

                        }

                        dataValidations.setAttribute('count', dataValidations.childNodes.length);

                    });

                    // Remove the style from cells with text from <td>
                    // var dataCells = sheet.querySelectorAll('row:not(:first-of-type) c');
                    dataCells.forEach(function (cell) {
                        var textNode = cell.querySelector('is t');
                        if (textNode) {
                            var text = textNode.textContent;
                            var tdTexts = $('#studentList tbody td').map(function () { return $(this).text(); }).get();
                            if (tdTexts.includes(text)) {
                                cell.removeAttribute('s');
                            }
                        }
                    });

                    $('row', sheet).first().attr('ht', '150').attr('customHeight', "1");


                    var cellA1 = sheet.querySelector('c[r="A1"]');

                    // If cell A1 does not exist, create it
                    if (!cellA1) {
                        cellA1 = sheet.createElement('c');
                        cellA1.setAttribute('r', 'A1');
                        cellA1.setAttribute('t', 'inlineStr');

                        var worksheet = sheet.getElementsByTagName('worksheet')[0];
                        var sheetData = worksheet.getElementsByTagName('sheetData')[0];

                        if (!sheetData) {
                            sheetData = sheet.createElement('sheetData');
                            worksheet.appendChild(sheetData);
                        }

                        var row1 = sheet.querySelector('row[r="1"]');
                        if (!row1) {
                            row1 = sheet.createElement('row');
                            row1.setAttribute('r', '1');
                            sheetData.appendChild(row1);
                        }

                        row1.appendChild(cellA1);
                    }

                    // Find or create the text element for cell A1
                    var isNode = cellA1.querySelector('is');
                    if (!isNode) {
                        isNode = sheet.createElement('is');
                        cellA1.appendChild(isNode);
                    }

                    var tNode = isNode.querySelector('t');
                    if (!tNode) {
                        tNode = sheet.createElement('t');
                        isNode.appendChild(tNode);
                    }

                    // Set the new text content
                    tNode.textContent = '{{$setting->name}}\nAddress:{{$setting->address}}\nMobile:{{$setting->mobile}} Email:{{$setting->gmail}}\nDate:{{date("d-M-Y")}}';
                }
            }, 'pdf'
            ]
        }).buttons().container().appendTo('#studentList_wrapper .col-md-6:eq(0)');

    });


</script>

<script>
    function todayWhatsappMessages() {
        $('#third_tbody').html('');
        var baseUrl = "{{ url('/') }}";
        $.ajax({
            headers: { 'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content') },
            url: baseUrl + '/todayWhatsappMessages',
            method: 'POST',
            success: function (response) {
                if (response.status) {
                    response.data.forEach(function (item) {
                        var attachmentContent = item.attachment ? `<a target="_blank" href="${item.attachment}" ><img src="${item.attachment}" width="40px" height="40px"/></a>` : "";

                        var newRow = `<tr>
                              <td class='messageId'>${item.message_id}</td>
                              <td class='old_message'>${item.message}</td>
                                <td class='attachment'>${attachmentContent}</td>
                    
                              <td ><a class="useMe"data-ids="${response.ids[item.message_id]}" style="cursor:pointer;  text-decoration: underline; color:blue">Use Me</a></td>
                            </tr>`;
                        $('#third_tbody').append(newRow);


                    });
                }
            },

            error: function (error) {
                console.error('Error sending data:', error);
            }
        });
    }


    $(document).ready(function () {

        function generateRandom8DigitNumber() {
            let min = 10000000; // minimum 8-digit number (10000000)
            let max = 99999999; // maximum 8-digit number (99999999)
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }




        var messageId = '';
        var attachment2 = '';
        var id = [];
        var i = 0;
        var filter = [];
        var arr = [];
        var previousIds = [];
        var notUsePreviousIds = false;
        var sending = true;
        var customElement = $('<div>', {
            id: 'custom-element',
            class: 'custom-element-class'
        });




        // Load the buttons into the custom element
        customElement.load('{{ url("messangerButtons") }} #custom-buttons', function () {
            // Append the custom element to the wrapper after loading the buttons
            $('#studentList_wrapper .col-md-6:eq(1)').append(customElement);
        });

        $('#studentList_wrapper').on('click', '#btn-sms', function () {
            toastr.error('Service is unavailabe')
            // Add your SMS handling code here
        });


        $('#close_modal').click(function () {
            sending = false;
            $('#myLargeModal').modal('hide');
        });

        $('#studentList_wrapper').on('click', '#btn-checkall', function () {
            var status = parseInt($(this).attr('data-status'));

            if (status == 0) {
                $(this).attr('data-status', 1);
                $(this).removeAttr('class');
                $(this).attr('class', 'btn btn-secondary btn-sm');
                $('#check_box_icon').removeAttr('class');
                $('#check_box_icon').attr('class', 'fa fa-check-square');
                $('.checkbox_id').prop('checked', true);
            } else {
                $(this).attr('data-status', 0);
                $(this).removeAttr('class');
                $(this).attr('class', 'btn btn-outline-secondary btn-sm');
                $('#check_box_icon').removeAttr('class');
                $('#check_box_icon').attr('class', 'fa fa-square-o');
                $('.checkbox_id').prop('checked', false);
            }
        });

        $('#studentList_wrapper').on('click', '#btn-whatsapp', function () {

            $('#img_preview').attr('src', 'https://demo3.rusoft.in/schoolimage/default/6605525.jpg')
            var length = $('.checkbox_id:checked').length;
            if (length > 0) {
                messageId = generateRandom8DigitNumber();
                id = [];
                previousIds = [];
                notUsePreviousIds = false;
                sending = true;

                if ($('#previousIds').is(':checked')) {
                    $('#previousIds').prop('checked', false);
                }
                $('.previousIds').hide();

                todayWhatsappMessages();
                $('#message-text').val('');

                $('#myLargeModal').modal('show');
                $('#secondary_tbody').html('');
                $(".checkbox_id").each(function (index) {
                    if (this.checked) {
                        var admission_no = $(this).data('admission_no');
                        var name = $(this).data('name');
                        var mobile = $(this).data('mobile');
                        var f_name = $(this).data('father_name');
                        var status = mobile ? 'Pending' : 'Mobile Missing';
                        var ids = $(this).val()


                        if (mobile != '') {
                            id.push({ id: ids, mobile: mobile });
                        }

                        var newRow = `<tr>
                              <td>${admission_no}</td>
                              <td>${name}</td>
                               <td>${f_name}</td>
                              <td>${mobile}</td>
                              <td class='status_action' id="status_${ids}">${status}</td> 
                            </tr>`;

                        $('#secondary_tbody').append(newRow);
                    }

                });
            } else {
                toastr.error('Please Select Students');
            }
        });


        $('#sendButton').click(function () {
            i = 0;

            arr = [];
            if (notUsePreviousIds) {
                filter = $.grep(id, function (item) {
                    return $.inArray(parseInt(item.id), previousIds) === -1;
                });

                arr = filter;

            }
            else {
                arr = id;
            }



            function processNext() {
                //toastr.info('done');
            }
            //  $('#sendButton').css('display','none')
            for (i = 0; i < arr.length; i++) {


                if (sending) {


                    sendPostRequest(arr[i]);

                }
                setTimeout(processNext, 1000);
            }

            if (arr.length == 0) {
                $('.status_action').each(function () {

                    if ($(this).text().trim() === "Pending") {


                        $(this).text("Skipped").addClass('text-info');
                    }
                });
            }

        });

        function sendPostRequest(data) {
            var baseUrl = "{{ url('/') }}";
            var message = $('#message-text').val();
            var fileInput = $('#attachment-file')[0];
            var file = fileInput.files[0];

            var formData = new FormData();
            formData.append('message_id', messageId);
            formData.append('id', data.id);
            formData.append('message', message);
            formData.append('modal', 'Admission');
            formData.append('mobile', data.mobile);
            formData.append('attachment2', attachment2);
            if (file) {
                formData.append('image', file);
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                url: baseUrl + '/sendWhatsapp',
                method: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from automatically transforming the data into a query string
                contentType: false, // Prevent jQuery from overriding the Content-Type header
                success: function (response) {
                    if (response.status) {
                        $('#status_' + response.id).addClass('text-success');
                        $('#status_' + response.id).text('Sent');
                    }
                    if (!response.status) {
                        $('#status_' + response.id).addClass('text-danger');
                        $('#status_' + response.id).text(response.message);
                    }
                    if (i == arr.length) {
                        setTimeout(todayWhatsappMessages, 3000)

                        $('.status_action').each(function () {
                            if ($(this).text().trim() === "Pending") {

                                $(this).text("Skipped").addClass('text-info');
                            }
                        });

                    }
                },
                error: function (error) {
                    console.error('Error sending data:', error);
                    // Handle error - e.g., display an error message
                }
            });
        }

        $('#studentList_wrapper').on('click', '#btn-email', function () {
            toastr.error('Service is unavailabe')
            // Add your Email handling code here 
        });
        $('#third_tbody').on('click', '.useMe', function () {
            $('.status_action').each(function () {

                if ($(this).text().trim() !== "Mobile Missing") {

                    $(this).text("Pending");
                    $(this).attr('class', '');
                    $(this).addClass('status_action');
                }
            });


            var preId = $(this).data('ids');

            previousIds = preId.split(',').map(Number);

            previousIds = previousIds.filter(item => item !== 0);

            var text = $(this).closest('tr').find('.old_message').text();
            var text1 = $(this).closest('tr').find('.messageId').text();
            var url = $(this).closest('tr').find('.attachment a').attr('href');
            $('#message-text').val(text);

            messageId = text1;
            $('.previousIds').show();
            $('#attachment-file').val('');
            attachment2 = url;
            $('#img_preview').attr('src', url);
            toastr.info('Message applied in the textarea')


        });
        $('#previousIds').click(function () {

            if (this.checked) {

                notUsePreviousIds = true;
            } else {
                notUsePreviousIds = false;
            }

        });



        $('#attachment-file').on('change', function (event) {

            var file = event.target.files[0];
            if (file) {
                if (file.type.match('image.*')) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#img_preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                } else {
                    toastr.error('Please select a valid image file.');
                    $(this).val(''); // Clear the input
                    $('#img_preview').attr('src', '{{ env("IMAGE_SHOW_PATH") . "default/6605525.jpg" }}');
                }
            }
        });
    });


    $(document).ready(function () {
        $('.verify_admission').click(function () {
            var id = $(this).data('id');
            var session_id = $(this).data('session_id');

            $('#id').val(id);
            $('#sessionId').val(session_id);

            $('#verify_modal').modal('show');
        });
    });


</script>

<!-- JSZip और FileSaver CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script>
    $(document).ready(function () {
        $('#downloadZip').on('click', function () {
            let btn = $(this);
            btn.prop('disabled', true);
            btn.text('Generating Please Wait...');

            var data = @json($data);
            var urls = [];
            var admissionNo = [];

            // URLs और filenames तैयार करें
            data.forEach(function (item) {
                if (item.image && item.image.trim() !== '') {
                    urls.push(`{{ env('IMAGE_SHOW_PATH').'profile/' }}${item.image}`);
                    admissionNo.push(item.admissionNo);
                }
            });

            if (urls.length === 0) {
                alert("No images found!");
                btn.prop('disabled', false);
                btn.html('<i class="fa fa-spinner" aria-hidden="true"></i>');
                return;
            }

            var zip = new JSZip();
            var count = 0;
            var zipFilename = "{{$setting->name}}.zip";

            urls.forEach(function (url, i) {
                var filename = admissionNo[i] + ".jpg";

                fetch(url, { mode: 'cors' }) // CORS safe
                    .then(response => {
                        if (!response.ok) throw new Error("HTTP error " + response.status);
                        return response.blob();
                    })
                    .then(blob => {
                        zip.file(filename, blob, { binary: true });
                        count++;

                        if (count === urls.length) {
                            zip.generateAsync({ type: 'blob' }).then(function (content) {
                                saveAs(content, zipFilename);
                                btn.prop('disabled', false);
                                btn.html('<i class="fa fa-arrow-circle-down" aria-hidden="true"></i>');

                            });
                        }
                    })
                    .catch(function (error) {
                        console.error("Error fetching image:", error);
                    });
            });
        });
    });
</script>


<script>
    $(document).ready(function(){

    // Show/Hide button
    $(document).on('change', '.checkbox_id', function () {

        if ($('.checkbox_id:checked').length > 0) {
            $('#multiDeleteBtn').show();
        } else {
            $('#multiDeleteBtn').hide();
        }
    });

    // Modal open hone se pehle selected IDs set karo
    $('#multiDeleteBtn').click(function () {

        let ids = [];

        $('.checkbox_id:checked').each(function () {
            ids.push($(this).val());
        });

        $('#multi_delete_ids').val(ids.join(','));
    });

});

</script>

@endsection

