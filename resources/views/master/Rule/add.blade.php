@php
$roleType = Helper::roleType();
@endphp
@extends('layout.app')
@section('content')

<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-image"></i>{{ __('master.Add Rule') }} </h3>
                            <div class="card-tools">
                                <a href="{{url('master_dashboard')}}" class="btn btn-primary btn-sm" title="View Gallery">
                                    <i class="fa fa-arrow-left"></i> {{ __('common.Back') }}
                                </a>
                            </div>
                        </div>

                        <form id="form-submit" action="{{ url('rules_add') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row m-2">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="color: red;">{{ __('common.Name') }}*</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" placeholder="{{ __('common.Name') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="color: red;">{{ __('master.Role Name') }}*</label>
                                        <select class="select2 form-control @error('role_id') is-invalid @enderror" name="role_id" id="role_id">
                                            <option value="">Select</option>
                                            @if(!empty($roleType))
                                                @foreach($roleType as $item)
                                                    <option value="{{ $item->id ?? '' }}">{{ $item->name ?? '' }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('master.Description') }}</label>
                                        <textarea class="form-control" id="description" name="description"
                                            placeholder="{{ __('master.Description') }}"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row m-2">
                                <div class="col-md-12 mt-3 mb-3 text-center">
                                    <button type="submit" class="btn btn-primary btn-submit">{{ __('common.Submit') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- View Table Section --}}
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-support"></i> &nbsp; {{ __('master.View Rule') }}</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped dataTable dtr-inline ">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>{{ __('common.SR.NO') }}</th>
                                                <th>Name</th>
                                                <th>{{ __('master.Role Name') }}</th>
                                                <th>{{ __('master.Description') }}</th>
                                                <th>{{ __('common.Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($data))
                                                @php $i = 1; @endphp
                                                @foreach ($data as $item)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $item['name'] ?? '' }}</td>
                                                        <td>{{ $item['role_name'] ?? '' }}</td>
                                                        <td>{!! $item['description'] ?? '' !!}</td>
                                                        <td>
                                                            <a href="{{url('rules_edit')}}/{{$item->id}}" class="btn btn-primary btn-xs ml-3 {{ Helper::permissioncheck(9)->edit ? '' : 'd-none' }}" title="Edit Rule"><i class="fa fa-edit"></i></a>
                                                            <a href="javascript:;" data-id='{{$item->id}}' data-bs-toggle="modal" data-bs-target="#Modal_id"
                                                                class="deleteData btn btn-danger btn-xs ml-3 {{ Helper::permissioncheck(9)->delete ? '' : 'd-none' }}" title="Delete Rule"><i class="fa fa-trash-o"></i></a>
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
        </div>
    </section>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal" id="Modal_id">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #555b5beb;">
            <div class="modal-header">
                <h4 class="modal-title text-white">{{ __('common.Delete Confirmation') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <form action="{{ url('rules_delete') }}" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="delete_id" name="delete_id">
                    <h5 class="text-white">{{ __('common.Are you sure you want to delete') }} ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('common.Close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('common.Delete') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>
<script>
    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('#description'))
        .then(editor => {
            console.log('CKEditor initialized successfully');
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
        });

    // Handle Delete Modal
    $('.deleteData').click(function() {
        var delete_id = $(this).data('id');
        $('#delete_id').val(delete_id);
    });
</script>

@endsection
