@php
  $classType = Helper::classType();
@endphp
@extends('layout.app') 
@section('content')

<div class="content-wrapper">
   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-md-12"> 
            <div class="card card-outline card-orange">
             <div class="card-header bg-primary">
                <h3 class="card-title"><i class="fa fa-address-book-o"></i> &nbsp; {{ __('Login Credential') }}</h3>
             </div>                 
             
             <!-- Search Form -->
             <form id="quickForm" action="{{ url('login_credential_reports') }}" method="post">
                @csrf 
                <div class="row m-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ __('common.Class') }}</label>
                            <select class="select2 form-control" id="class_type_id" name="class_type_id">
                                <option value="">{{ __('common.Select') }}</option>
                                @if(!empty($classType)) 
                                    @foreach($classType as $type)
                                        <option value="{{ $type->id ?? '' }}" {{ ($type->id == ($search['class_type_id'] ?? '')) ? 'selected' : '' }}>
                                            {{ $type->name ?? '' }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <label class="text-white">{{ __('common.Select') }}</label>
                        <button type="submit" class="btn btn-primary">{{ __('common.Search') }}</button>
                    </div>
                </div>
             </form>

             <!-- Results Table -->
             @if(!empty($search['class_type_id']) || !empty($search['name']))
             <div class="row m-2">
                <div class="col-12">
                    <table id="example1" class="table table-bordered table-striped dataTable dtr-inline padding_table">
                        <thead>
                            <tr>
                                <th>{{ __('common.SR.NO') }}</th>
                                <th>{{ __('common.Name') }}</th>
                                <th>{{ __('common.Class') }}</th>
                                <th>{{ __('Guardian Name') }}</th>
                                <th>{{ __('Student UserName') }}</th>
                                <th>{{ __('Password') }}</th>
                              
                                <th class="d-none">{{ __('common.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($data))
                                @php $i = 1; @endphp
                                @foreach ($data as $item)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                 
                                    <td>{{ $item['first_name'] ?? '' }} {{ $item['last_name'] ?? '' }}</td>
                                    <td>{{ $item['class_name'] ?? '' }}</td>
                                    <td>{{ $item['father_name'] ?? '' }}</td>
                                    <td>{{ $item['userName'] ?? '' }}</td>
                                    <td>{{ $item['confirm_password'] ?? '' }}</td>
                                   
                                    <td  class="d-none">
                                        <a href="{{ url('reset_pass', $item->id) }}" class="btn btn-primary btn-xs ml-3">Reset Password</a>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
             </div>
             @endif
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
@endsection
