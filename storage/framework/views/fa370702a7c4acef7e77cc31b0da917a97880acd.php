<?php
    $classType  = Helper::classType();
    $getsubject = Helper::getSubject();
    $date       = date('Y-m-d');
?>



<?php $__env->startSection('content'); ?>
<div class="content-wrapper bg-body">
    <section class="content pt-4">
        <div class="container-fluid">
            
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-body p-4">
                    
                    <div class="sms-header mb-3">
                        <h5><i class="fa fa-envelope"></i> Compact Student SMS Portal</h5>
                    </div>


                    <div class="filter-box mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="small font-weight-bold text-muted text-uppercase">
                                    Message Type Name
                                </label>
                            
                                <select class="form-control custom-select-lg"
                                        name="message_type_id"
                                        id="message_type_id">
                                    <option value="">Select Message Type</option>
                                    <?php $__currentLoopData = $messageTypes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
    
                            <div class="col-md-3">
                                <label class="small font-weight-bold text-muted text-uppercase">Class</label>
                                <select class="form-control custom-select-lg"
                                        id="class_type_id"
                                        name="class_type_id[]"
                                        multiple>
                                    <?php $__currentLoopData = $classType ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary mr-2" onclick="SearchValue()">Search</button>
                        <button class="btn btn-light">Reset</button>
                    </div>


                    <div class="table-responsive border rounded mt-3">
                        <table class="table table-bordered table-striped sms-table">
                            <thead class="bg-light">
                                <tr class="text-muted small text-uppercase">
                                    <th width="40"><input type="checkbox" id="select_all_students"></th>
                                    <th>Photo</th>
                                    <th>Application No.</th>
                                    <th>DOB</th>
                                    <th>Student Name</th>
                                    <th>Father's Name</th>
                                    <!--<th>Gender</th>-->
                                    <th>Mobile</th>
                                    <!--<th>Class</th>-->
                                </tr>
                            </thead>
                            <tbody id="student_list_show">
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        Please select class and click search
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between">
                            <h6 class="font-weight-bold mb-0">SMS APPROVED TEMPLATE CONTENT</h6>
                            <span class="badge badge-soft-primary">Template ID: SMS_2024_01</span>
                        </div>
                        <div class="card-body px-4">
                            <textarea class="form-control border bg-white shadow-none p-3"
                                      rows="6"
                                      id="approved_sms_content"
                                      style="border-radius: 10px; resize: none;">
                            </textarea>
                            <script>
                                $('#approved_sms_content').on('keyup', function () {
                                    extractVariablesFromSMS($(this).val());
                                });
                            </script>
                        </div>
                        <div class="card-footer bg-white border-0 px-4 pb-4">
                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                <div>
                                    <p class="small text-muted mb-0">CHARACTERS</p>
                                    <h6 class="font-weight-bold">136 / 1</h6>
                                </div>
                                <div class="text-center">
                                    <p class="small text-muted mb-0">SELECTED STUDENTS</p>
                                    <h6 class="font-weight-bold text-primary">4 Students Selected</h6>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success px-4 font-weight-bold shadow-sm" style="background-color: #11a351; border-color: #11a351;">
                                        <i class="fas fa-paper-plane mr-2"></i> SEND MESSAGE
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h6 class="font-weight-bold mb-0">VARIABLES AND INSTRUCTIONS</h6>
                        </div>
                        <div class="card-body px-4">
                            <p class="small text-muted mb-3">Use these dynamic placeholders in your message. They will be replaced with actual student data.</p>
                            
                            <div class="row no-gutters mb-3" id="dynamic_variables_box">
                                <div class="col-12 text-muted small">
                                    No variables found
                                </div>
                            </div>
                            <div class="p-3 rounded" style="background-color: #FFF9C4; border: 1px solid #FFF176;">
                                <p class="small mb-0 text-dark">
                                    <i class="fas fa-info-circle mr-1"></i> <b>Note:</b> 160 characters = 1 SMS credit. Ensure templates are approved by DLT before sending.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<style>
    /* ===== ADMIN SMS PANEL STYLE ===== */

.sms-header {
    background: #0b5ea8;
    color: #fff;
    padding: 10px 15px;
    font-weight: 600;
    border-radius: 3px;
}

.sms-header h5 {
    margin: 0;
    font-size: 15px;
}

/* FILTER BOX */
.filter-box {
    border: 1px solid #dcdcdc;
    background: #f9f9f9;
    padding: 15px;
}

/* LABELS */
label {
    font-size: 12px;
    font-weight: 600;
    color: #333;
}

/* INPUTS */
.form-control {
    height: 34px;
    font-size: 13px;
    border-radius: 2px;
    border: 1px solid #bfbfbf;
}

/* BUTTONS */
.btn-primary {
    background-color: #0b5ea8;
    border-color: #0b5ea8;
    font-size: 13px;
    padding: 5px 15px;
}

.btn-light {
    border: 1px solid #ccc;
    font-size: 13px;
}

/* TABLE */
.sms-table {
    font-size: 13px;
}

.sms-table thead th {
    background: #0b5ea8;
    color: #fff;
    font-weight: 600;
    padding: 8px;
    border: 1px solid #0b5ea8;
}

.sms-table td {
    padding: 6px 8px;
    vertical-align: middle;
    border: 1px solid #e0e0e0;
}

.sms-table tr:nth-child(even) {
    background: #f9f9f9;
}

/* CHECKBOX */
.sms-table input[type="checkbox"] {
    width: 14px;
    height: 14px;
}

/* PHOTO */
.sms-table img {
    border-radius: 50%;
}

/* REMOVE EXTRA ROUNDED */
.card {
    border-radius: 2px !important;
}

</style>
<script>
function SearchValue() {

    var class_type_id = $('#class_type_id').val(); // ARRAY milega

    if(class_type_id && class_type_id.length > 0){

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "<?php echo e(url('send_student_sms')); ?>",
            data: {
                class_type_id: class_type_id
            },
            success: function (data) {
                $('#student_list_show').html(data);
            }
        });

    } else {
        toastr.error('Please select at least one class');
    }
}
</script>

<script>
$('#message_type_id').change(function () {

    var message_type_id = $(this).val();

    if(message_type_id === ''){
        $('#approved_sms_content').val('');
        return;
    }

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: "<?php echo e(url('send_student_sms')); ?>",
        data: {
            message_type_id: message_type_id
        },
        success: function (response) {
            $('#approved_sms_content').val(response);
        
            // 🔥 VARIABLES AUTO DETECT
            extractVariablesFromSMS(response);
        }

    });
});
</script>

<script>
function extractVariablesFromSMS(text) {

    let variables = [];

    // 1️⃣ FIRST PRIORITY → {#variable#}
    let nestedVars = text.match(/{#\w+#}/g);
    if (nestedVars) {
        variables = variables.concat(nestedVars);
    }

    // 2️⃣ #variable# (ONLY if not already inside {# #})
    let hashVars = text.match(/#\w+#/g);
    if (hashVars) {
        hashVars.forEach(v => {
            let wrapped = `{${v}}`;
            let doubleWrapped = `{${v.replace(/#/g, '')}}`;

            if (
                !variables.includes(`{${v}}`) &&
                !variables.includes(`{#${v.replace(/#/g, '')}#}`)
            ) {
                variables.push(v);
            }
        });
    }

    // 3️⃣ {variable} (ONLY if not part of {# #})
    let braceVars = text.match(/{\w+}/g);
    if (braceVars) {
        braceVars.forEach(v => {
            let core = v.replace(/[{}]/g, '');
            if (
                !variables.includes(`{#${core}#}`) &&
                !variables.includes(`#${core}#`)
            ) {
                variables.push(v);
            }
        });
    }

    // ❌ Remove duplicates (final safety)
    variables = [...new Set(variables)];

    let html = '';

    if (variables.length > 0) {
        variables.forEach(function (item) {
            html += `
                <div class="col-6 p-1">
                    <span class="badge badge-light border w-100 py-2 text-muted">
                        ${item}
                    </span>
                </div>
            `;
        });
    } else {
        html = `
            <div class="col-12 text-muted small">
                No variables found
            </div>
        `;
    }

    $('#dynamic_variables_box').html(html);
}
</script>
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





<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/sms_service/send_student_sms.blade.php ENDPATH**/ ?>