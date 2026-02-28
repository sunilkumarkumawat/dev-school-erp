@php $getCountry = Helper::getCountry();
$getState = Helper::getState();
$getCity = Helper::getCity();
@endphp
@extends('layout.app')
@section('content')

<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 pr-0">
                    <div class="card card-outline card-orange mr-1">
                        <div class="card-header bg-primary flex_items_toggel">
                            <h3 class="card-title"><i class="fa fa-leanpub"></i> &nbsp;{{ __('Assign Subject ') }}</h3>
                            <div class="card-tools"><a href="{{url('master_dashboard')}}"
                                    class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i><span
                                        class="Display_none_mobile"> Back </span></a></div>
                        </div>

                        <form id="quickForm" action="{{ url('select_class') }}" method="post">
                            @csrf
                            <div class="row m-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('messages.Class') }}</label>
                                        <select class="form-control select2" id="class_type_id" name="class_type_id">
                                            <option value="">{{ __('messages.Select') }}</option>
                                            @if(!empty(Helper::classType()))
                                            @foreach(Helper::classType() as $type)
                                            <option value="{{ $type->id ?? ''  }}" {{ ($type->id ==
                                                $search['class_type_id']) ? 'selected' : '' }}>{{ $type->name ?? '' }}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-2 ">
                                    <button type="submit" class="btn btn-primary mt-4">{{ __('Search') }}</button><br>
                                </div>
                            </div>
                        </form>


                        @if(!empty($search['class_type_id']))
                        <form id="form-submit" action="{{ url('add_subject') }}" method="post">
                            @csrf

                            <div class="row m-2">
                                <input type="hidden" name="class_type_id" value="{{$search['class_type_id'] ?? ''}}" />
                                <div class="col-md-12">
                                    <label class="">{{ __('Select Subject') }}</label>

                                </div>


                                <div class="col-md-12">



                                    @php
                                    $checkbox =
                                    DB::table('all_subjects')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('deleted_at',null)->get();
                                    @endphp

                                    @foreach($checkbox as $item)

                                    @php
                                    $subject =
                                    DB::table('subject')->where('session_id',Session::get('session_id'))->where('branch_id',Session::get('branch_id'))->where('name',$item->name)->
                                    where('class_type_id',$search['class_type_id'])->where('deleted_at',null)->first();
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-2 ">
                                            <label style="cursor:pointer;">
                                                <input type="checkbox"
                                                       name="add_subject[]"
                                                       value="{{$item->name ?? ''}}"
                                                       {{$subject !='' ? 'checked' : '' }} />
                                                &nbsp;&nbsp;  <span
                                                style="width:300px">{{$item->name ?? ''}} </span>
                                            &nbsp;&nbsp;
                                            </label>
                                        </div>
                                        <div class="col-md-2 ">
                                            {{$item->other_subject == 1 ? 'Other' : 'Main' }}
                                        </div>
                                    </div>



                                    <br>

                                    @endforeach


                                </div>
                            </div>
                            <div class="row m-2">
                                <div class="col-md-12 text-center"><button type="submit"
                                        class="btn btn-primary btn-submit">{{ __('Save') }}</button><br></div>
                            </div>
                        </form>

                        @endif
                    </div>
                </div>
            </div>

            @if(!empty($section) && count($section) > 0)
            <form id="form-submit-edit" action="{{url('subjectOrderBy')}}" method="post">
                @csrf
                <div class="col-md-12 pl-0">
                    <div class="card  ml-1">


                        <div class="col-md-12" style="overflow-x:scroll;">
                            <table id="example1" class="table table-bordered table-striped dataTable dtr-inline nowrap">
                                <thead class="bg-primary">
                                    <tr role="row">
                                        <th>{{ __('messages.Sr.No.') }}</th>
                                        <th>{{ __('messages.Subject') }}</th>
                                        <th>{{ __('Sort') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>Class</th>
                                        <th>{{ __('Delete') }}</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @php $i=1
                                    @endphp
                                    @foreach ($section as $item)
                                    <tr>
                                        @php
                                        $resortData =
                                        DB::table('class_types')->where('id',$item['class_type_id'])->first()
                                        @endphp
                                        <td>{{ $i++ }}
                                            <input type="hidden" name="subject_id[]" value="{{ $item['id'] ?? '' }}" />
                                        </td>
                                        <td>{{ $item['name'] ?? '' }}</td>
                                        <td> <input type="text" name="sort_by[]" class="w-25"
                                                value="{{ $item['sort_by'] ?? '' }}" /></td>
                                        <td>{{$item['other_subject'] == 1 ? 'Other' : 'Main' }}</td>
                                        <td>{{ $resortData->name ?? '' }}</td>
                                        <td>
                                            <!--<a href="{{ url('edit_subject') }}/{{ $item['id'] ?? '' }}" class="btn btn-primary  btn-xs" title="Edit" ><i class="fa fa-edit"></i></a> -->
                                            <a href="javascript:;" data-id='{{$item[' id'] }}' data-bs-toggle="modal"
                                                data-bs-target="#Modal_id"
                                                class="deleteData btn btn-primary btn-xs ml-3" title="Delete"><i
                                                    class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="6" class=" p-2 text-center"><button
                                                class=" btn btn-primary btn-submit" type="submit">Submit</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </form>
            @endif
        </div>
</div>
</div>
</section>
</div>


<script src="{{URL::asset('public/assets/school/js/jquery.min.js')}}"></script>

<script>
    $(".deleteData").click(function () {
        var delete_id = $(this).data("id");

        $("#delete_id").val(delete_id);
    });
</script>



<!-- The Modal -->
<div class="modal" id="Modal_id">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #555b5beb;">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-white">{{ __('messages.Delete Confirmation') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times"
                        aria-hidden="true"></i></button>
            </div>

            <!-- Modal body -->
            <form action="{{ url('delete_subject') }}" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="delete_id" name="delete_id" />
                    <h5 class="text-white">{{ __('messages.Are you sure you want to delete') }} ?</h5>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form"
                        data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('messages.Delete')
                        }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection