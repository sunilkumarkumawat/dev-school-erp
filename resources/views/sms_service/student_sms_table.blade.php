@if(count($students) > 0)
@foreach($students as $student)
<tr>
    <td>
        <input type="checkbox" class="student_checkbox" name="admission_id[]" value="{{ $student->id }}">
    </td>
    <td>
        <img src="https://ui-avatars.com/api/?name={{ $student->first_name }}+{{ $student->last_name }}"
             class="rounded-circle" width="30">
    </td>
    <td>{{ $student->application_no }}</td>
    <td>{{ date('d-M-Y', strtotime($student->dob)) }}</td>
    <td class="font-weight-bold">
        {{ $student->first_name }} {{ $student->last_name }}
    </td>
    <td>{{ $student->father_name }}</td>
    <!--<td>{{ ucfirst($student->gender) }}</td>-->
    <td>{{ $student->mobile }}</td>
    <!--<td>-->
    <!--    <span class="badge badge-light border">-->
    <!--        {{ $student->classType->name ?? '' }}-->
    <!--    </span>-->
    <!--</td>-->
</tr>
@endforeach
@else
<tr>
    <td colspan="9" class="text-center text-danger">
        No students found
    </td>
</tr>

<script>
$(document).on('change', '#select_all_students', function () {
    $('.student_checkbox').prop('checked', this.checked);
});

$(document).on('change', '.student_checkbox', function () {
    let total   = $('.student_checkbox').length;
    let checked = $('.student_checkbox:checked').length;

    $('#select_all_students').prop('checked', total === checked);
});
</script>

@endif
