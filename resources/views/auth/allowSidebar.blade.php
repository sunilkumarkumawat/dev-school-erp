<!DOCTYPE html>
<html lang="en">
@php
use Illuminate\Support\Facades\DB;

$sidebar = \App\Models\Sidebar::whereNull('deleted_at')
->orderBy('order_by','ASC')->get();

$sidebarSubs = DB::table('sidebar_sub')
->orderBy('sidebar_id')
->orderBy('id')
->get()
->groupBy('sidebar_id');

$roleType = Helper::roleType();
$getSession = Helper::getSession();
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Permissions</title>

    <link rel="stylesheet" href="{{ asset('public/assets/school/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/common.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="{{ asset('public/assets/school/js/jquery.min.js') }}"></script>
    <script src="{{URL::asset('public/assets/school/js/form/form_save.js')}}"></script>
<!-- Toastr -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 30px;
        }

        .bg_image{
    background:url('{{ env('IMAGE_SHOW_PATH').'default/Icon_images/rm347-porpla-01.jpg' }}')
    no-repeat center/cover;
}
        .main {
            width: 80%;
            background: #ffffffee;
            border-radius: 20px;
            padding: 30px;
            overflow: auto;
            height: 100%;
        }

        .heading {
            font-size: 18px;
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 3px;
            font-weight: 600;
        }

        .heading2 {
            font-size: 15px;
            margin: 25px 0 10px;
            font-weight: 600;
        }

        .refresh-button {
            padding: 12px 24px;
            background: #6639b5;
            color: #fff;
            border-radius: 4px;
            border: 1px solid #6639b5;
        }

        .refresh-button:hover {
            background: #fff;
            color: #ff5722;
            border-color: #ff5722;
        }
    </style>
</head>

<body class="bg_image">

    <div class="main">

        <h2 class="heading">Allocation Of Modules For New User</h2>

        <form action="{{ url('allowSidebar') }}" method="POST" id="form-submit">
            @csrf

            {{-- ================= Branch Details ================= --}}
            <h2 class="heading2">Branch Details</h2>
            <div class="container">

    <div id="branchRows">

        <div class="row branch-row align-items-end mb-2">

            <div class="col-md-2">
                <label>Branch Code <span class="text-danger">*</span></label>
                <input type="text" name="branch_code[]" class="form-control" placeholder="Branch Code">
            </div>

            <div class="col-md-3">
                <label>Branch Name <span class="text-danger">*</span></label>
                <input type="text" name="branch_name[]" class="form-control" placeholder="Branch Name">
            </div>

            

            <div class="col-md-2">
                <button type="button" class="btn btn-success add-row">
                     <i class="fa fa-plus"></i>   </button>
            </div>

        </div>

    </div>

</div>


            {{-- ================= Personal Details ================= --}}
            <h2 class="heading2">Personal Details</h2>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Role <span style="color: red;">*</span></label>
                        <select name="role_id" class="form-control" required>
                            <option value="">Select</option>
                            @foreach($roleType as $item)
                            <option value="{{ $item->id }}" {{ $item->id==1?'selected':'' }}>
                                {{ $item->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
               <div class="col-md-2">
                    <div class="form-group">
                        <label>Current Session <span style="color: red;">*</span></label>
                        <select name="session_id" class="form-control">
                            @foreach($getSession as $s)
                            <option value="{{ $s->id }}">{{ $s->from_year }} - {{ $s->to_year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Director <span style="color: red;">*</span></label>
                        <input type="text" name="contact_person" class="form-control" placeholder="Director Name">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Mobile <span style="color: red;">*</span></label>
                        <input type="text" name="mobile" class="form-control" placeholder="Mobile">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Address">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>User Name <span style="color: red;">*</span></label>
                        <input type="text" name="user_name" class="form-control" placeholder="Username">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Password <span style="color: red;">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                </div>

            </div>

            {{-- ================= Services ================= --}}
            <h2 class="heading2">Service Details</h2>
            <div class="row">

                <div class="col-md-2">
                    <div class="form-group">
                        <label>WhatsApp</label>
                        <select name="whatsapp_status" class="form-control">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>SMS</label>
                        <select name="sms_status" class="form-control">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>Email</label>
                        <select name="email_status" class="form-control">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

            </div>

            {{-- ================= Module Allocation ================= --}}
            <h2 class="heading2 mt-4">Module Allocation</h2>

            <div class="row">
            
                <!-- LEFT SIDE : SIDEBARS -->
                <div class="col-md-6">
            
                    <!-- SELECT ALL -->
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" id="select_all" class="custom-control-input">
                        <label for="select_all" class="custom-control-label">
                            Select All Modules
                        </label>
                    </div>
            
                    <div class="row">
                        @foreach($sidebar as $s)
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input checkbox"
                                       id="sidebar_{{ $s->id }}"
                                       data-id="{{ $s->id }}"
                                       name="sidebar_id[]"
                                       value="{{ $s->id }}">
                                <label for="sidebar_{{ $s->id }}" class="custom-control-label">
                                    {{ $s->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
            
                </div>
            
                <!-- RIGHT SIDE : SUB SIDEBARS -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label><b>Alloted Sub Panels</b></label>
                        <select multiple id="sidebar_sub_id" class="form-control" style="height:300px">
            
                            @foreach($sidebarSubs as $sid => $subs)
                            <optgroup label="Module {{ $sid }}"
                                      class="sidebar_{{ $sid }}"
                                      style="display:none">
                                @foreach($subs as $sub)
                                <option value="{{ $sub->id }}">
                                    {{ $sub->name }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
            
                        </select>
                    </div>
                </div>
            
                <!-- HIDDEN INPUTS -->
                <div id="subSidebarHiddenInputs"></div>
            
            </div>


            <center class="mt-4">
                <button type="submit" class="refresh-button btn-submit">Submit</button>
            </center>

        </form>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const sidebarCheckboxes = document.querySelectorAll('.checkbox');
    const selectAll = document.getElementById('select_all');
    const selectBox = document.getElementById('sidebar_sub_id');
    const hiddenBox = document.getElementById('subSidebarHiddenInputs');

    /* ===============================
     | SELECT ALL MODULES
     =============================== */
    selectAll.addEventListener('change', function () {

        sidebarCheckboxes.forEach(cb => {
            cb.checked = this.checked;

            const sid = cb.dataset.id;
            const group = document.querySelector('.sidebar_' + sid);

            if (!group) return;

            if (this.checked) {
                group.style.display = 'block';
            } else {
                group.style.display = 'none';
                group.querySelectorAll('option').forEach(o => o.selected = false);
                hiddenBox.querySelectorAll('.sub-' + sid).forEach(e => e.remove());
            }
        });

        selectBox.dispatchEvent(new Event('change'));
    });

    /* ===============================
     | INDIVIDUAL SIDEBAR TOGGLE
     =============================== */
    sidebarCheckboxes.forEach(cb => {

        cb.addEventListener('change', function () {

            const sid = this.dataset.id;
            const group = document.querySelector('.sidebar_' + sid);

            if (!group) return;

            if (this.checked) {
                group.style.display = 'block';
            } else {
                group.style.display = 'none';
                group.querySelectorAll('option').forEach(o => o.selected = false);
                hiddenBox.querySelectorAll('.sub-' + sid).forEach(e => e.remove());
            }

            // update select all checkbox
            selectAll.checked = [...sidebarCheckboxes].every(c => c.checked);

            selectBox.dispatchEvent(new Event('change'));
        });

    });

    /* ===============================
     | SUB SIDEBAR SELECTION
     =============================== */
    selectBox.addEventListener('change', function () {

        hiddenBox.innerHTML = '';

        sidebarCheckboxes.forEach(cb => {

            if (!cb.checked) return;

            const sid = cb.dataset.id;
            const group = document.querySelector('.sidebar_' + sid);

            if (!group) return;

            group.querySelectorAll('option:checked').forEach(opt => {

                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = `sidebar_sub_id[${sid}][]`;
                input.value = opt.value;
                input.classList.add('sub-' + sid);

                hiddenBox.appendChild(input);
            });

        });

    });

});
</script>

<script>
document.addEventListener('click', function (e) {

    // ADD ROW
    if (e.target.closest('.add-row')) {

        let row = e.target.closest('.branch-row');
        let newRow = row.cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => input.value = '');

        let btn = newRow.querySelector('.add-row');
        btn.classList.remove('btn-success', 'add-row');
        btn.classList.add('btn-danger', 'remove-row');
        btn.innerHTML = '<i class="fa fa-minus"></i>';

        document.getElementById('branchRows').appendChild(newRow);
    }

    // REMOVE ROW
    if (e.target.closest('.remove-row')) {
        e.target.closest('.branch-row').remove();
    }

});
</script>



</body>

</html>