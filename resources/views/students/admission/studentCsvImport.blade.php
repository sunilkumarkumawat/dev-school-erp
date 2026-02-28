@php
$getAdmissionDatatableFields = Helper::getAdmissionDatatableFieldCSVImport();
@endphp
@extends('layout.app')
@section('content')

<style>
    body{
        text-transform: capitalize;
    }
</style>				

<div class="content-wrapper">

	<section class="content p-1">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-md-12">
					<div class="card card-outline card-orange">
						<div class="card-header bg-primary">
							<h3 class="card-title"> &nbsp;            <strong class="text-white">📌 Student Import Guide</strong></h3>
							<div class="card-tools">
								<a href="{{url('studentsDashboard')}}" class="btn btn-primary  btn-sm"><i class="fa fa-arrow-left"></i> <span class="Display_none_mobile"> {{ __('common.Back') }} </span></a>
							</div>

						</div>
            
                        <div class="card-body p-3">

            <div class="row">

                <!-- STEP 1 -->
                <div class="col-md-4">
                    <div class="p-2 border-left border-primary">
                        <h6 class="font-weight-bold text-primary mb-2">STEP 1</h6>
                        <ul class="small mb-0">
                            <li>Proper column headings</li>
                            <li>No blank mandatory fields</li>
                            <li>Correct class name</li>
                            <li>No duplicate Admission No</li>
                            <li>Date format: Y-m-d</li>
                        </ul>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div class="col-md-4">
                    <div class="p-2 border-left border-success">
                        <h6 class="font-weight-bold text-success mb-2">STEP 2</h6>
                        <ul class="small mb-0">
                            <li>Click <b>Upload Excel</b></li>
                            <li>Select file</li>
                            <li>Map columns properly</li>
                            <li>Click <b>Save Mapping</b></li>
                        </ul>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div class="col-md-4">
                    <div class="p-2 border-left border-warning">
                        <h6 class="font-weight-bold text-warning mb-2">STEP 3</h6>
                        <ul class="small mb-0">
                            <li>🟢 Green = Valid</li>
                            <li>🔴 Red = Missing field</li>
                            <li>Fix errors & Submit</li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Required Note -->
            <div class="alert alert-light border mt-3 mb-0 py-2 small">
                <strong class="text-danger">⚠ Note:</strong>
                Required fields in <b>System Student Field Settings</b> 
                will automatically apply in Admission Form and Excel Import.
            </div>

        </div>

	            		<div class="col-md-3 mb-3">
                <input type="file" id="excelFile"  class="form-control"  data-fields='@json($getAdmissionDatatableFields)'>
                <div id="errorBox" class="alert alert-danger d-none mt-2"></div>
            </div>
                        </div>
                        </div>
                    </section>
                
                </div>


                <div class="modal fade" id="mappingModal">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4>Field Mapping</h4>
                      </div>
                      <div class="modal-body">
                        <div id="mappingContainer"></div>
                      </div>
                      <div class="modal-footer">
                        <button type="button"
                                class="btn btn-success"
                                id="saveMappingBtn">
                            Continue
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal fade" id="excelModal">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4>Excel Preview</h4>
                      </div>
                      <div class="modal-body p-2">
                
                    <div class="excel-preview-wrapper">
                
                        <div class="table-responsive excel-scroll">
                
                            <table class="table table-bordered table-sm"
                                   id="excelTable">
                
                                <thead></thead>
                                <tbody></tbody>
                
                            </table>
                
                        </div>
                
                    </div>
                
                </div>
<div class="modal-footer">
        <button type="button"
        class="btn btn-primary mt-2"
        id="saveDataBtn">
            Submit Data
</button>
      </div>
    </div>
    
  </div>
</div>
@php
$classMap = \App\Models\ClassType::where('session_id', Session::get('session_id'))
    ->where('branch_id', Session::get('branch_id'))
    ->pluck('id','name');
@endphp
<!-- XLSX Library -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

<script>
let genderList = @json(DB::table('gender')->whereNull('deleted_at')->pluck('name'));
window.classMap = @json($classMap);
window.classList = Object.keys(window.classMap || {});
let bloodGroupList = @json(DB::table('blood_groups')->whereNull('deleted_at')->pluck('name'));

window.admissionFields = @json($getAdmissionDatatableFields);
</script>

<script>

let currentJsonData = [];
let excelHeaders = [];
let fieldMapping = {};
let dbFields = Array.isArray(window.admissionFields)
    ? window.admissionFields
    : Object.values(window.admissionFields);

// ================= CLEAN STRING =================
function cleanString(str){
    return String(str).toLowerCase().replace(/\s+/g,'').trim();
}

// ================= FILE READ =================
document.getElementById('excelFile').addEventListener('change',function(e){

    const file = e.target.files[0];
    if(!file) return;

    const reader = new FileReader();

    reader.onload = function(event){

        const data = new Uint8Array(event.target.result);
        const workbook = XLSX.read(data,{type:'array'});
        const sheet = workbook.Sheets[workbook.SheetNames[0]];
        let jsonData = XLSX.utils.sheet_to_json(sheet,{defval:''});

        if(!jsonData.length){
            alert("No data found in Excel");
            return;
        }

        currentJsonData = jsonData;
        excelHeaders = Object.keys(jsonData[0]);

        renderMappingUI();
    };

    reader.readAsArrayBuffer(file);
});


// ================= MAPPING =================
function renderMappingUI(){

    let container = document.getElementById('mappingContainer');
    container.innerHTML='';

    excelHeaders.forEach(header=>{

        let rowDiv = document.createElement('div');
        rowDiv.className = "row mb-2 mapping-row";

        let col1 = document.createElement('div');
        col1.className = "col-md-5";
        col1.innerHTML = `<strong>${header}</strong>`;

        let col2 = document.createElement('div');
        col2.className = "col-md-7";

        let select = document.createElement('select');
        select.className = "form-control mapping-select";
        select.dataset.header = header;

        select.innerHTML = `<option value="">-- Map To Field --</option>`;

        dbFields.forEach(field => {

            if(!field.field_label || !field.field_name) return;

            let option = document.createElement('option');
            option.value = field.field_name;
            option.textContent = field.field_label;

            // ðŸ”¥ AUTO MATCH
            if(cleanString(field.field_label) === cleanString(header)){
                option.selected = true;
            }

            select.appendChild(option);
        });

        col2.appendChild(select);
        rowDiv.appendChild(col1);
        rowDiv.appendChild(col2);
        container.appendChild(rowDiv);
    });

    new bootstrap.Modal(document.getElementById('mappingModal')).show();
}


// ================= SAVE MAPPING =================
document.getElementById('saveMappingBtn').addEventListener('click',function(){

    fieldMapping = {};

    document.querySelectorAll('.mapping-select').forEach(select=>{
        if(select.value){
            fieldMapping[select.dataset.header] = select.value;
        }
    });

    if(Object.keys(fieldMapping).length === 0){
        alert("Please select at least one field");
        return;
    }

    let transformed = currentJsonData.map(row=>{
        let obj = {};
        for(let excelCol in fieldMapping){
            obj[fieldMapping[excelCol]] = row[excelCol];
        }
        return obj;
    });

    currentJsonData = transformed;

    bootstrap.Modal.getInstance(document.getElementById('mappingModal')).hide();

    renderPreviewTable();
});





// ================= PREVIEW =================
function renderPreviewTable(){


    const thead = document.querySelector('#excelTable thead');
    const tbody = document.querySelector('#excelTable tbody');

    thead.innerHTML = '';
    tbody.innerHTML = '';

    // ðŸ”¥ ONLY SELECTED FIELDS
    let headers = Object.values(fieldMapping);

    let headerRow = '<tr>';

    headers.forEach(h=>{
        let fieldObj = dbFields.find(f => f.field_name === h);
        headerRow += `<th>${fieldObj ? fieldObj.field_label : h}</th>`;
    });

    headerRow += '<th>Status</th><th>Action</th></tr>';
    thead.innerHTML = headerRow;

    currentJsonData.forEach((row,index)=>{

        let tr = document.createElement('tr');
        tr.dataset.index = index;

        headers.forEach(header=>{

            let td = document.createElement('td');
            let fieldObj = dbFields.find(f => f.field_name === header);
            let element;

            if(fieldObj && fieldObj.field_type === 'select'){

                element = document.createElement('select');
                element.className = "form-control form-control-sm";

                if(fieldObj.required == 0){
                    element.classList.add("required-field");
                }

                let list = [];

                if(fieldObj.field_name === 'gender_id'){
                    list = genderList;
                }
                else if(fieldObj.field_name === 'class_type_id'){
                    list = window.classList;
                    element.classList.add("class-select");
                }
                else if(fieldObj.field_name === 'admission_type_id'){
                        list = ['Yes','No'];
                    }
                else if(fieldObj.field_name === 'category'){
                    list = ['OBC','ST','SC','BC','GEN','SBC','Other'];
                }
                else if(fieldObj.field_name === 'blood_group'){
                    list = ['A+','A-','B+','B-','O+','O-','AB+','AB-'];
                }
                else if(fieldObj.field_name === 'religion'){
                    list = ['Hindu','Islam','Sikh','Buddhism','Adivasi','Jain','Christianity','Other'];
                }
                else if(fieldObj.field_name === 'medium'){
                    list = ['Hindi','English'];
                }

                element.innerHTML = `<option value="">-- Select --</option>`;

                list.forEach(value=>{
                    let option = document.createElement('option');
                    option.value = value;
                    option.textContent = value;
                    if(String(value) === String(row[header])){
                        option.selected = true;
                    }
                    element.appendChild(option);
                });

            }
              else if(fieldObj && fieldObj.field_type === 'date'){

    element = document.createElement('input');   // 🔥 यह missing था
    element.className = "form-control form-control-sm";

    let normalized = safeNormalizeDate(row[header]);

    if(/^\d{4}-\d{2}-\d{2}$/.test(normalized)){
        element.type = 'date';
        element.value = normalized;
    }else{
        element.type = 'text';
        element.value = row[header] ?? '';
    }

    if(fieldObj.required == 0){
        element.classList.add("required-field");
    }
}
                else{

                element = document.createElement('input');
                element.type = 'text';
                element.className = "form-control form-control-sm";
                element.value = row[header] ?? '';

                if(fieldObj && fieldObj.required == 0){
                    element.classList.add("required-field");
                }
            }

            element.addEventListener('change',()=>{
                currentJsonData[index][header] = element.value;
                validateRow(tr,index);
            });

            td.appendChild(element);
            tr.appendChild(td);
        });

        let statusTd = document.createElement('td');
        tr.appendChild(statusTd);

        let actionTd = document.createElement('td');
        actionTd.innerHTML = `<button class="btn btn-danger btn-sm">Delete</button>`;
        actionTd.onclick = ()=>{
            currentJsonData.splice(index,1);
            renderPreviewTable();
        };

        tr.appendChild(actionTd);
        tbody.appendChild(tr);

        validateRow(tr,index);
    });

    new bootstrap.Modal(document.getElementById('excelModal')).show();
}
// ================= SAFE DATE NORMALIZE =================
function safeNormalizeDate(value){

    if(!value) return '';

    if(typeof value === 'number'){
        let date = new Date(Math.round((value - 25569) * 86400 * 1000));
        return date.toISOString().split('T')[0];
    }

    value = String(value).trim().replace(/[\/\.]/g,'-');
    let parts = value.split('-');

    if(parts.length === 3){

        if(parts[0].length === 4){
            return value;
        }

        if(parts[2].length === 4){
            return `${parts[2]}-${parts[1].padStart(2,'0')}-${parts[0].padStart(2,'0')}`;
        }
    }

    return value;
}

// ================= VALIDATION =================
function validateRow(tr,index){

    let invalid = false;

    if(currentJsonData[index]._duplicate){
        invalid = true;
        tr.style.backgroundColor = "#fff3cd";
        tr.querySelector('td:nth-last-child(2)').innerHTML =
            "<span class='badge bg-warning'>Duplicate</span>";
        return;
    }

    tr.querySelectorAll('.required-field').forEach(input=>{
        if(!input.value){
            invalid = true;
            input.style.border = "1px solid red";
        }else{
            input.style.border = "";
        }
    });

    if(invalid){
        tr.style.backgroundColor = "#f8d7da";
        tr.querySelector('td:nth-last-child(2)').innerHTML =
            "<span class='badge bg-danger'>Invalid</span>";
    }else{
        tr.style.backgroundColor = "";
        tr.querySelector('td:nth-last-child(2)').innerHTML =
            "<span class='badge bg-success'>Valid</span>";
    }
}
document.getElementById('saveDataBtn').addEventListener('click', function(){

    let validData = [];
    let hasError = false;

    document.querySelectorAll(".field-error").forEach(el => el.remove());

    document.querySelectorAll("#excelTable tbody tr").forEach(tr => {

        let index = tr.dataset.index;
        let rowData = currentJsonData[index];

        let rowInvalid = false;

        // ðŸ”¥ Duplicate check
        if(rowData._duplicate){
            rowInvalid = true;
            hasError = true;
        }

        // ðŸ”¥ Dynamic Required Check
        tr.querySelectorAll('.required-field').forEach(input => {

            if(!input.value){

                rowInvalid = true;
                hasError = true;

                input.style.border = "1px solid red";

                let error = document.createElement("div");
                error.classList.add("field-error");
                error.style.color = "red";
                error.style.fontSize = "12px";
                error.innerText = "This field is required.";

                input.parentNode.appendChild(error);

            } else {
                input.style.border = "";
            }
        });

        if(!rowInvalid){
            validData.push(rowData);
        }
    });

    if(hasError){
      //  alert("Please fix highlighted errors before submitting.");
        return;
    }

    if(validData.length === 0){
        alert("No valid data to save.");
        return;
    }

    let btn = this;
    btn.disabled = true;
    btn.innerText = "Saving...";

    fetch(`{{ url('student-import-save') }}`,{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body:JSON.stringify({
            data: validData
        })
    })
    .then(response => response.json())
    .then(res => {

        btn.disabled = false;
        btn.innerText = "Submit Data";

        if(res.status){
            alert(res.message || "Upload Successful.");
            window.location.href = "{{ url('admissionView') }}";
        } else {
            alert(res.message || "Error while saving data.");
        }

    })
    .catch(error => {

        btn.disabled = false;
        btn.innerText = "Submit Data";
        alert("Server Error.");
    });

});</script>





<style>
.mapping-row {
    cursor: pointer;
    padding: 6px;
    border-radius: 6px;
    transition: 0.2s;
}

.mapping-row:hover {
    background-color: #f1f7ff;
}

.mapping-row.active {
    background-color: #cfe2ff !important;
    border-left: 4px solid #0d6efd;
}
</style>
<style>

.excel-preview-wrapper {
    max-height: 70vh;
    overflow: hidden;
}

.excel-scroll {
    max-height: 65vh;
    overflow-y: auto;
    overflow-x: auto;
    border: 1px solid #dee2e6;
}

/* Sticky Header */
#excelTable thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 10;
}

/* Smooth Scroll */
.excel-scroll::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

.excel-scroll::-webkit-scrollbar-thumb {
    background: #0d6efd;
    border-radius: 10px;
}

</style>




















<style>
    .alert-subl {
  color: #31708f;
  border-color: #ddd;
}
</style>
@endsection