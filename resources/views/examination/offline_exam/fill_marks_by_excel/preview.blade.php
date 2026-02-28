@extends('layout.app')
@section('content')

<div class="content-wrapper p-4">
<h3>Preview Marks</h3>

<form method="POST" action="{{ url('save-marks') }}">
@csrf

<table class="table table-bordered">
<tr>
@foreach($mapping['marks'] as $sid => $col)
<th>{{ $col }}</th>
@endforeach
</tr>

@foreach($rows as $r)
<tr>
@foreach($mapping['marks'] as $col)
<td>{{ $r[$col] }}</td>
@endforeach
</tr>
@endforeach
</table>

<button class="btn btn-success">Save Marks</button>
</form>

</div>
@endsection
