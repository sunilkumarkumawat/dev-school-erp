<?php
$classType = Helper::classType();
?>

<?php $__env->startSection('content'); ?>

<div class="content-wrapper">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-sign-in"></i> &nbsp; <?php echo e(__('Login Credential')); ?>

                            </h3>
                            <div class="card-tools">
                                <a href="<?php echo e(url('studentsDashboard')); ?>" class="btn btn-primary  btn-sm"><i
                                        class="fa fa-arrow-left"></i><?php echo e(__('common.Back')); ?></a>

                            </div>
                        </div>

                        <!-- Search Form -->

                        <div class="row m-2">
                            <div class="col-md-8">
                                <form id="quickForm" action="<?php echo e(url('login_credential_reports')); ?>" method="post"
                                    class="row">
                                    <?php echo csrf_field(); ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo e(__('common.Class')); ?></label>
                                            <select class="select2 form-control" id="class_type_id"
                                                name="class_type_id">
                                                <option value=""><?php echo e(__('common.Select')); ?></option>
                                                <?php if(!empty($classType)): ?>
                                                <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($type->id ?? ''); ?>" <?php echo e(($type->id ==
                                                    ($search['class_type_id'] ?? '')) ? 'selected' : ''); ?>>
                                                    <?php echo e($type->name ?? ''); ?>

                                                </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mt-4">
                                        <button type="submit" class="btn btn-primary mt-md-1"><?php echo e(__('common.Search')); ?></button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-4 mt-4">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#generateModal" id="openModalBtn">
                                    Generate Usernames & Passwords
                                </button>
                            </div>
                        </div>



                        <!-- Results Table -->
                        <?php if(!empty($search['class_type_id']) || !empty($search['name'])): ?>
                        <div class="row m-2">
                            <div class="col-12">
                                <table id="example1"
                                    class="table table-bordered table-striped dataTable dtr-inline padding_table">
                                    <thead>
                                        <tr>
                                            <th style="width:100px;">
                                                <div class="d-flex">
                                                    <input type="checkbox" id="select_all">
                                                    <span><?php echo e(__('common.SR.NO')); ?></span>
                                                </div>
                                            </th>
                                            <th><?php echo e(__('Class')); ?></th>
                                            <th><?php echo e(__('common.Name')); ?></th>
                                            <th><?php echo e(__('Father Name')); ?></th>
                                            <th><?php echo e(__('UserName')); ?></th>
                                            <th><?php echo e(__('Password')); ?></th>




                                            <th class="d-none"><?php echo e(__('common.Action')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($data)): ?>
                                        <?php $i = 1; ?>
                                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="width:100px;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="checkbox" class="student_checkbox" value="<?php echo e($item->id); ?>">
                                                    <span class="fw-bold"><?php echo e($i++); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo e($item['class_name'] ?? ''); ?></td>
                                            <td><?php echo e($item['first_name'] ?? ''); ?> <?php echo e($item['last_name'] ?? ''); ?></td>
                                            <td><?php echo e($item['father_name'] ?? ''); ?></td>

                                            <td><?php echo e($item['userName'] ?? ''); ?></td>
                                            <td><?php echo e($item['confirm_password'] ?? ''); ?></td>


                                            <td class="d-none">
                                                <a href="<?php echo e(url('reset_pass', $item->id)); ?>"
                                                    class="btn btn-primary btn-xs ml-3">Reset Password</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- MODAL -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?php echo e(url('studentUserNameCreate')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="student_ids" id="student_ids">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Username & Password Generator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <!-- USERNAME CHECKBOXES -->
                        <div class="col-md-6">
                            <h6>Username Fields</h6>
                            <div><input type="checkbox" id="username_admission_no"> Admission No</div>
                            <div><input type="checkbox" id="username_name"> Name (first
                                <input type="number" name="name_letters" value="4" style="width:60px"> letters)
                            </div>
                            <div><input type="checkbox" id="username_mobile"> Mobile (last
                                <input type="number" name="mobile_digits" value="4" style="width:60px"> digits)
                            </div>
                            <div><input type="checkbox" id="username_dob"> DOB (DDMMYY)</div>
                            <div><input type="checkbox" id="username_class"> Class</div>
                        </div>

                        <!-- PASSWORD CHECKBOXES -->
                        <div class="col-md-6">
                            <h6>Password Fields</h6>
                            <div><input type="checkbox" id="password_admission_no"> Admission No</div>
                            <div><input type="checkbox" id="password_name"> Name</div>
                            <div><input type="checkbox" id="password_mobile"> Mobile</div>
                            <div><input type="checkbox" id="password_dob"> DOB</div>
                            <div><input type="checkbox" id="password_class"> Class</div>
                            <p>Or Custom Password</p>
                            <div id="customSection"><input type="text" name="custom_password" id="custom_password"
                                class="form-control" placeholder="Enter Custom Password">
                            </div>
                        </div>
                    </div>
                    
                    <!--<hr>-->
                    
                    <!--<h6>Or Custom Credentials</h6>-->
                    
                    <!--<div class="row" id="customSection">-->
                    
                        <!-- Custom Password -->
                    <!--    <div class="col-md-6">-->
                    <!--        <input type="text" name="custom_password" id="custom_password"-->
                    <!--            class="form-control" placeholder="Enter Custom Password">-->
                    <!--    </div>-->
                    <!--</div>-->
                

                    <hr>

                    <!-- EXAMPLE STUDENT INPUT -->
                    <h6>Example Student:</h6>
                    <div class="row">
                        <div class="col-md-3">Name: <input type="text" id="ex_name" value="Ravi Kumar"
                                class="form-control"></div>
                        <div class="col-md-3">Mobile: <input type="text" id="ex_mobile" value="9876543210"
                                class="form-control"></div>
                        <div class="col-md-3">DOB: <input type="date" id="ex_dob" value="2005-08-15"
                                class="form-control"></div>
                        <div class="col-md-3">Admission No: <input type="text" id="ex_admission" value="ADM123"
                                class="form-control"></div>
                        <div class="col-md-3">Class: <input type="text" id="ex_class" value="10th A"
                                class="form-control"></div>
                    </div>

                    <!-- LIVE PREVIEW -->
                    <div class="mt-3">
                        <p>Username Preview: <b id="usernamePreview">[Username]</b></p>
                        <p>Password Preview: <b id="passwordPreview">[Password]</b></p>
                    </div>

                    <!-- Hidden field for sending order -->
                    <input type="hidden" name="username_order" id="username_order">
                    <input type="hidden" name="password_order" id="password_order">

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Generate for All Students</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JAVASCRIPT LOGIC -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const usernameFields = [
            { id: 'username_admission_no', key: 'admission_no' },
            { id: 'username_name', key: 'name' },
            { id: 'username_mobile', key: 'mobile' },
            { id: 'username_dob', key: 'dob' },
            { id: 'username_class', key: 'class' }
        ];

        const passwordFields = [
            { id: 'password_admission_no', key: 'admission_no' },
            { id: 'password_name', key: 'name' },
            { id: 'password_mobile', key: 'mobile' },
            { id: 'password_dob', key: 'dob' },
            { id: 'password_class', key: 'class' }
        ];

        const example = {
            name: document.getElementById('ex_name'),
            mobile: document.getElementById('ex_mobile'),
            dob: document.getElementById('ex_dob'),
            admission: document.getElementById('ex_admission'),
            class: document.getElementById('ex_class')
        };

        function getFieldValue(key) {
            switch (key) {
                case 'name':
                    const len = document.querySelector('[name="name_letters"]').value || 4;
                    return example.name.value.trim().toLowerCase().replace(/\s+/g, '').substring(0, len);
                case 'mobile':
                    const digits = document.querySelector('[name="mobile_digits"]').value || 4;
                    return example.mobile.value.trim().replace(/\D/g, '').slice(-digits);
                case 'dob':
                    const dob = example.dob.value;
                    if (!dob) return '';
                    const parts = dob.split('-');
                    return parts.length === 3 ? parts[2] + parts[1] + parts[0].slice(2) : '';
                case 'admission_no':
                    return example.admission.value.trim();
                case 'class':
                    return example.class.value.trim().toLowerCase().replace(/\s+/g, '');
                default:
                    return '';
            }
        }

        function updateOrdersAndPreview() {
            const u_order = [];
            const p_order = [];
            let username = '';
            let password = '';

            usernameFields.forEach(f => {
                const cb = document.getElementById(f.id);
                if (cb.checked) {
                    u_order.push(f.key);
                    username += getFieldValue(f.key);
                }
            });

            passwordFields.forEach(f => {
                const cb = document.getElementById(f.id);
                if (cb.checked) {
                    p_order.push(f.key);
                    password += getFieldValue(f.key);
                }
            });

            document.getElementById('username_order').value = u_order.join(',');
            document.getElementById('password_order').value = p_order.join(',');
            document.getElementById('usernamePreview').innerText = username || '[Username]';
            document.getElementById('passwordPreview').innerText = password || '[Password]';
        }

        // Trigger on checkbox/field change
        document.querySelectorAll('input[type="checkbox"], input[type="number"], input[type="text"], input[type="date"]').forEach(el => {
            el.addEventListener('input', updateOrdersAndPreview);
            el.addEventListener('change', updateOrdersAndPreview);
        });

        // Initialize once
        updateOrdersAndPreview();
                
                // =============================
        // SELECT ALL FUNCTIONALITY
        // =============================
        const selectAll = document.getElementById('select_all');
        
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                document.querySelectorAll('.student_checkbox').forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        }
        
        // Individual checkbox change -> uncheck select_all if needed
        document.querySelectorAll('.student_checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                if (!this.checked) {
                    selectAll.checked = false;
                }
            });
        });
        
        
        // =============================
        // PASS SELECTED IDS TO MODAL
        // =============================
        const openModalBtn = document.getElementById('openModalBtn');
        
        if (openModalBtn) {
            openModalBtn.addEventListener('click', function (e) {
        
                let selectedIds = [];
        
                document.querySelectorAll('.student_checkbox:checked').forEach(cb => {
                    selectedIds.push(cb.value);
                });
        
                if (selectedIds.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one student.');
                    return false;
                }
        
                document.getElementById('student_ids').value = selectedIds.join(',');
            });
        }

    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const customPassword = document.getElementById('custom_password');

    const passwordCheckboxes = document.querySelectorAll(
        '#generateModal input[id^="password_"]'
    );

    // When typing custom password → disable password checkboxes
    customPassword.addEventListener('input', function () {

        if (customPassword.value.trim() !== '') {
            passwordCheckboxes.forEach(cb => {
                cb.checked = false;
                cb.disabled = true;
            });
        } else {
            passwordCheckboxes.forEach(cb => {
                cb.disabled = false;
            });
        }

        updateOrdersAndPreview();
    });

    // When selecting password checkboxes → disable custom password
    passwordCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {

            const anyChecked = Array.from(passwordCheckboxes)
                .some(box => box.checked);

            if (anyChecked) {
                customPassword.value = '';
                customPassword.disabled = true;
            } else {
                customPassword.disabled = false;
            }

            updateOrdersAndPreview();
        });
    });

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/students/login_credential/view.blade.php ENDPATH**/ ?>