@php
    $permissionTypes = ['add','edit','view','delete','status','print'];
@endphp

<form method="post" action="{{ url("role/permission/$role_id") }}">
    @csrf
    <input type="hidden" name="role_id" value="{{ $role_id }}" />

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr style="background: #d7d7d7;">
                <th>Module / Page</th>
                @foreach($permissionTypes as $type)
                    <th class="text-center">
                        {{ ucfirst($type) }} 
                        <input type="checkbox" class="check-type" data-type="{{ $type }}">
                       
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($modules as $module)
                @php
                    $modulePermissions = $rolePermissions[$module->id] ?? null;
                    $subModules = $subs[$module->id] ?? collect();
                    $subSelected = $modulePermissions ? explode(',', $modulePermissions->sub_sidebar_id ?? '') : [];
                    $collapseId = 'subModule'.$module->id;
                @endphp
                <tr>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                            <strong>
                                 <input type="checkbox" class="row-select-all" data-module-id="{{ $module->id }}" id="rowSelect{{ $module->id }}">   
                                <i class="{{ $module->ican ?? '' }}"></i> {{ $module->name }}
                                </strong>
                                 
                            </div>
                            @if($subModules->isNotEmpty())
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                                    Sub Modules
                                </button>
                            @endif
                        </div>

                       @if($subModules->isNotEmpty())
                            <div class="collapse mt-2" id="{{ $collapseId }}">
                                <select 
                                    class="form-select form-select-sm select2-multiple" 
                                    name="sub_modules[{{ $module->id }}][]" 
                                    multiple="multiple"
                                    data-placeholder="Select Sub Modules"
                                >
                                    @foreach($subModules as $sub)
                                        <option value="{{ $sub->id }}" {{ in_array($sub->id, $subSelected) ? 'selected' : '' }}>
                                            {{ $sub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif



                    </td>

                    @foreach($permissionTypes as $type)
                        <td class="text-center">
                            <input type="checkbox" 
                                   class="permission-checkbox {{ $type }}" 
                                   data-module-id="{{ $module->id }}"  
                                   name="modules[{{ $module->id }}][]"  
                                   value="{{ $type }}" 
                                   {{ $modulePermissions && $modulePermissions->$type ? 'checked' : '' }}>
                        </td>
                    @endforeach
                </tr>
            @endforeach
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
