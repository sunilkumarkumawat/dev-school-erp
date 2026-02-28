@extends('layout.app')
@section('content')

@php
    $classes = Helper::classTypeExam();
@endphp
@php
    $className = null;

    if(!empty($selectedClass)){
        $classObj = $classes->firstWhere('id', $selectedClass);
        if($classObj){
            $className = str_replace(' ', '_', $classObj->name);
        }
    }
@endphp

<div class="content-wrapper import-ui">
<section class="content">
<div class="shell">

<h1>Import Student Marks</h1>

<div class="row mb-3">
    <div class="col-md-3">
        <form action="{{ url('fill-marks-by-excel') }}" method="POST">
            @csrf
            <label style="color:red;">Class*</label>
            <select name="class_type_id"  id="class_type_id"
                    class="form-control"
                    onchange="this.form.submit()">
                <option value="">Select Class</option>
                @foreach($classes as $class)
                @php
                $className = null;
            
                if(!empty($selectedClass)){
                    $classObj = $classes->firstWhere('id', $selectedClass);
                    if($classObj){
                        $className = str_replace(' ', '_', $classObj->name);
                    }
                }
            @endphp

                    <option value="{{ $class->id }}"
                        {{ ($selectedClass ?? '') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="col-md-4 mt-4">

{{-- ================= HIDDEN TABLE (EXCEL SOURCE) ================= --}}
@if(!empty($subjects) && count($subjects))
<table id="studentList"
       class="table table-bordered table-striped nowrap"
       style="visibility:hidden;height:0;">

    <thead>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            @foreach($subjects as $sub)
                <th>{{ $sub->name }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
         <tr>
            <th>Total Marks</th>
            <th></th>
            @foreach($subjects as $sub)
                <th></th>
            @endforeach
        </tr>
        @foreach($students as $stu)
            <tr>
                <td>{{ $stu->id }}</td>
                <td>{{ $stu->first_name ?? '' }}</td>
                @foreach($subjects as $sub)
                    <td></td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
@endif
{{-- =============================================================== --}}
</div>
    <div class="col-md-3">
       <div class="form-group">
          <label style="color:red;">{{ __('messages.Exam Name') }}*</label>
          <select class="select2 form-control @error('exam_id') is-invalid @enderror exam_id_" id="exam_id" name="exam_id" >
             <option value="">{{ __('messages.Select') }}</option>
           @if(!empty($examlist))
                  @foreach($examlist as $item)
                  
                  <option value="{{ $item->exam_id ?? ''  }}">{{ $item->exam_name ?? ''  }}</option>
                    @endforeach
                  @endif
           
          </select>
             
       </div>
    </div>
</div>
<form id="marksUploadForm" enctype="multipart/form-data">

<div class="content-grid mt-4">

    {{-- LEFT : UPLOAD --}}
    <div class="upload-box">
        <div class="drop-area">
            <div class="drop-icon">⬆</div>
            <h3>Upload Excel</h3>
            <p>
                Download sample file and fill marks<br>
                according to subject master
            </p>

            <!-- 🔥 name="excel_file" MUST -->
           <input type="file"
       id="marks_file"
       name="excel_file"
       hidden
       accept=".xlsx,.csv"
       required>


            <button type="button"
        class="browse-btn"
        onclick="document.getElementById('marks_file').click()">
    Browse File
</button>


            <div class="support-text">
                Supported formats: .xlsx, .csv
            </div>
        </div>
    </div>

    {{-- RIGHT : INSTRUCTIONS --}}
    <div>
        <div class="panel">
            <h4>Instructions</h4>
            <ul style="padding-left:18px;font-size:13px;color:#4b5563">
                <li>Admission No must exist</li>
                <li>Do not rename columns</li>
                <li>Marks numeric and [T]-Trival, [AB]-Absent, [M]-Medical, [JL]-Join Late</li>
                <li>Subjects auto from master</li>
            </ul>
        </div>
    </div>

</div>
<!-- Progress Bar -->
<div id="uploadProgressBox" style="display:none;margin-top:15px;">
    <div style="font-size:13px;margin-bottom:6px;">Uploading file…</div>
    <div class="progress">
        <div id="uploadProgress"
             class="progress-bar progress-bar-striped progress-bar-animated"
             role="progressbar"
             style="width:0%">
            0%
        </div>
    </div>
</div>

<div id="previewSection" class="mt-4"></div>

<div class="footer">
    <span class="subtext">Please upload correct Excel file</span>


</div>

</form>


</div>
</section>
</div>
<script>
$('#marks_file').on('change', function(){

    let fileInput = this;

    if(fileInput.files.length === 0){
        return;
    } 

    let classId = $('#class_type_id').val();
    if(!classId){
        toastr.warning('Please select class first', 'Class Required');
        fileInput.value = '';
        return;
    }
    let examId = $('#exam_id').val();
    if (!examId) {
        toastr.error('Please select Exam Name', 'Exam Required');
        return;
    } 

    let formData = new FormData();
    formData.append('excel_file', fileInput.files[0]);
    formData.append('class_type_id', classId);
    formData.append('exam_id', $('#exam_id').val());
    formData.append('_token', "{{ csrf_token() }}");

    // RESET UI
    $('#previewSection').html('');
    $('#uploadProgressBox').show(); 
    $('#uploadProgress').css('width','0%').text('0%');

    $.ajax({
        url: "{{ url('marks-import-preview-ajax') }}",
        type: "POST",
        data: formData,
        processData:false,
        contentType:false,

        // 🔥 PROGRESS BAR
        xhr: function () {
            let xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    let percent = Math.round((evt.loaded / evt.total) * 100);
                    $('#uploadProgress')
                        .css('width', percent + '%')
                        .text(percent + '%');
                }
            }, false);
            return xhr;
        },

        success:function(res){

            $('#uploadProgress')
                .removeClass('progress-bar-animated')
                .text('Upload Complete');

            if(res.status){

                let rows = res.rows;

                let html = `<h4 class="mt-3">Preview Student Marks</h4>
                <table class="table table-bordered table-sm">
                    <thead><tr>`;

                rows[0].forEach(h => {
                    html += `<th>${h}</th>`;
                });

                html += `</tr></thead><tbody>`;

                for(let i=1;i<rows.length;i++){
                    html += `<tr>`;
                    rows[i].forEach(c=>{
                        html += `<td>${c ?? ''}</td>`;
                    });
                    html += `</tr>`;
                }

             
                 html += `</tbody></table>
                <button class="btn btn-success mt-2"
                        type="button" id="confirmSave"
                        data-class="${classId}"
                         data-exam="${examId}">
                        Confirm & Save
                </button>`;

                $('#previewSection').html(html);
                $('#confirmSave').data('rows', rows);
            }

            // auto hide progress
            setTimeout(()=>{
                $('#uploadProgressBox').fadeOut();
            }, 600);
        },

        error:function(){
            $('#uploadProgressBox').hide();
            alert('Upload failed, please try again');
        }
    });

});
</script>
<script>
$(document).off('click', '#confirmSave'); // 🔥 avoid duplicate binding

$(document).on('click', '#confirmSave', function (e) {
    e.preventDefault();

    let rows = $(this).data('rows');
    let classId = $(this).data('class');
    let examId  = $(this).data('exam'); // 🔥 HERE
if (!examId) {
        toastr.error('Exam ID missing. Please reselect exam.');
        return;
    }
    
    if (!rows || rows.length === 0) {
        toastr.error('No data found to save', 'Error');
        return;
    }

    $.ajax({
        url: "{{ url('marks-import-save-ajax') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            rows: rows,
            class_type_id: classId,
            exam_id: examId
        },
        beforeSend: function () {
            toastr.info('Saving marks, please wait...', 'Saving');
        },
        success: function (res) {
            if (res.status) {
                toastr.success(res.message, 'Success');
                $('#previewSection').html('');
                $('#marks_file').val('');
            } else {
                toastr.error('Save failed', 'Error');
            }
        },
        error: function () {
            toastr.error('Server error while saving', 'Error');
        }
    });
});
</script>





{{-- ================= DATATABLE + EXCEL ================= --}}
<script>
$(document).ready(function(){

    if ($('#studentList').length) {

        $('#studentList').DataTable({
            paging: false,
            searching: false,
            info: false,
            ordering: false,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                title: '',
                filename:'Class_{{ $className }}_Student_Marks',
                text: '<i class="fa fa-arrow-down"></i> Download Sample Import File',
                className: 'btn btn-primary',

                customize: function (xlsx) {
                    // ❌ NO sheetProtection
                    // ❌ NO lockedStyle / unlockedStyle
                    // ✅ Excel fully editable
                }
            }]
        });

    }

});
</script>



{{-- ================= CSS ================= --}}
<style>
#studentList{visibility:hidden;height:0;}
#studentList_wrapper{visibility:visible;}

.import-ui{background:#f6f8fb;padding:28px;}
.shell{max-width:1200px;margin:auto;background:#fff;border-radius:16px;padding:32px;box-shadow:0 10px 25px rgba(15,23,42,.08);}
h1{font-size:24px;margin-bottom:12px;}
.subtext{color:#6b7280;}

.content-grid{display:grid;grid-template-columns:2fr 1fr;gap:26px;}

.upload-box{background:#fff;border-radius:14px;padding:30px;border:1px solid #e5e7eb;}
.drop-area{border:2px dashed #c7d2fe;border-radius:14px;padding:60px 20px;text-align:center;}
.drop-icon{width:52px;height:52px;border-radius:50%;background:#eef2ff;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:#2563eb;font-size:22px;}
.browse-btn{margin-top:14px;background:#2563eb;color:#fff;border:none;padding:10px 22px;border-radius:8px;cursor:pointer;}
.support-text{margin-top:10px;font-size:13px;color:#6b7280;}

.panel{background:#fff;border-radius:14px;padding:18px;border:1px solid #e5e7eb;}
.panel h4{font-size:15px;margin-bottom:8px;}

.footer{display:flex;justify-content:space-between;align-items:center;margin-top:26px;}
.next-btn{background:#e5e7eb;color:#9ca3af;border:none;padding:10px 22px;border-radius:8px;cursor:not-allowed;}
</style>

@endsection
