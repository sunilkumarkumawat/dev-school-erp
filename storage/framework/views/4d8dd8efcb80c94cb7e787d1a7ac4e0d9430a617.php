<?php
    $permissionTypes = ['add','edit','view','delete','status','print'];
?>

<form method="post" action="<?php echo e(url("role/permission/$role_id")); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="role_id" value="<?php echo e($role_id); ?>" />

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr style="background: #d7d7d7;">
                <th>Module / Page</th>
                <?php $__currentLoopData = $permissionTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="text-center">
                        <?php echo e(ucfirst($type)); ?> 
                        <input type="checkbox" class="check-type" data-type="<?php echo e($type); ?>">
                       
                    </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $modulePermissions = $rolePermissions[$module->id] ?? null;
                    $subModules = $subs[$module->id] ?? collect();
                    $subSelected = $modulePermissions ? explode(',', $modulePermissions->sub_sidebar_id ?? '') : [];
                    $collapseId = 'subModule'.$module->id;
                ?>
                <tr>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                            <strong>
                                 <input type="checkbox" class="row-select-all" data-module-id="<?php echo e($module->id); ?>" id="rowSelect<?php echo e($module->id); ?>">   
                                <i class="<?php echo e($module->ican ?? ''); ?>"></i> <?php echo e($module->name); ?>

                                </strong>
                                 
                            </div>
                            <?php if($subModules->isNotEmpty()): ?>
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo e($collapseId); ?>">
                                    Sub Modules
                                </button>
                            <?php endif; ?>
                        </div>

                       <?php if($subModules->isNotEmpty()): ?>
                            <div class="collapse mt-2" id="<?php echo e($collapseId); ?>">
                                <select 
                                    class="form-select form-select-sm select2-multiple" 
                                    name="sub_modules[<?php echo e($module->id); ?>][]" 
                                    multiple="multiple"
                                    data-placeholder="Select Sub Modules"
                                >
                                    <?php $__currentLoopData = $subModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($sub->id); ?>" <?php echo e(in_array($sub->id, $subSelected) ? 'selected' : ''); ?>>
                                            <?php echo e($sub->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        <?php endif; ?>



                    </td>

                    <?php $__currentLoopData = $permissionTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="text-center">
                            <input type="checkbox" 
                                   class="permission-checkbox <?php echo e($type); ?>" 
                                   data-module-id="<?php echo e($module->id); ?>"  
                                   name="modules[<?php echo e($module->id); ?>][]"  
                                   value="<?php echo e($type); ?>" 
                                   <?php echo e($modulePermissions && $modulePermissions->$type ? 'checked' : ''); ?>>
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary btn-sm">Save Permissions</button>
</form>
<script>
$(document).ready(function() {
    $('.select2-multiple').select2({
        width: '100%',
        closeOnSelect: false,  // checkbox जैसा व्यवहार देगा
        allowClear: true,
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Column "Check All"
    document.querySelectorAll('.check-type').forEach(headerCheckbox => {
        headerCheckbox.addEventListener('change', function () {
            const type = this.dataset.type;
            document.querySelectorAll(`.permission-checkbox.${type}`).forEach(cb => cb.checked = this.checked);
        });
    });

    // Row "Select All"
    document.querySelectorAll('.row-select-all').forEach(rowCheckbox => {
        rowCheckbox.addEventListener('change', function () {
            const moduleId = this.dataset.moduleId;
            const checked = this.checked;
            document.querySelectorAll(`.permission-checkbox[data-module-id="${moduleId}"]`).forEach(cb => cb.checked = checked);
            document.querySelectorAll(`select[name^="sub_modules[${moduleId}]"] option`).forEach(opt => opt.selected = checked);
        });
    });
});
</script>
<?php /**PATH /home/rusofterp/public_html/dev/resources/views/master/role/permissions.blade.php ENDPATH**/ ?>