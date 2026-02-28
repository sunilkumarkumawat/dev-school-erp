@extends('layout.app') 
@section('content')
<div class="content-wrapper">
	<section class="content pt-3">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
							<h3 class="card-title"><i class="fa fa-edit"></i> &nbsp;{{ __('invantory.Edit Invantory') }}</h3>
							<div class="card-tools"> <a href="{{url('invantory_item_add')}}" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i>{{ __('common.Back') }} </a> </div>
						</div>
					<form id="form-submit-edit"
      action="{{ url('invantory_item_edit/'.$data->id) }}"
      method="post">
    @csrf

    <div class="row col-12">

        {{-- NAME --}}
        <div class="col-md-3">
            <div class="form-group">
                <label class="text-danger">{{ __('common.Name') }} *</label>
                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $data->name) }}"
                       placeholder="{{ __('common.Name') }}">

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- HSN CODE --}}
        <div class="col-md-3">
            <div class="form-group">
                <label>HSN Code</label>
                <input type="text"
                       name="hsn_code"
                       class="form-control @error('hsn_code') is-invalid @enderror"
                       value="{{ old('hsn_code', $data->hsn_code) }}"
                       placeholder="HSN Code">

                @error('hsn_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- UNIT --}}
        <div class="col-md-2">
            <div class="form-group">
                <label class="text-danger">Unit *</label>
                <select name="unit"
                        class="form-control @error('unit') is-invalid @enderror">
                    <option value="">Select Unit</option>

                    @foreach(['PCS','KG','LTR','BOX'] as $unit)
                        <option value="{{ $unit }}"
                            {{ old('unit', $data->unit) == $unit ? 'selected' : '' }}>
                            {{ $unit }}
                        </option>
                    @endforeach
                </select>

                @error('unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- MRP --}}
        <div class="col-md-3">
            <div class="form-group">
                <label class="text-danger">MRP *</label>
                <input type="number"
                       step="0.01"
                       name="mrp"
                       class="form-control @error('mrp') is-invalid @enderror"
                       value="{{ old('mrp', $data->mrp) }}"
                       placeholder="MRP">

                @error('mrp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

    </div>

    {{-- SUBMIT --}}
    <div class="row m-2 pb-2">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary btn-submit">{{ __('common.Update') }}</button>
                        </div>
                    </div>

</form>

				</div>
			</div>
		</div>
	</section>
</div>
@endsection