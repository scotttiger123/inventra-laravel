@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">
                Permissions for Role: 
                @if(strtolower($role->name) == 'sales manager')
                    <span style="color: #ff5733;">{{ ucfirst($role->name) }}</span>
                @else
                    {{ ucfirst($role->name) }}
                @endif
            </h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="text-right mb-3">
            <div class="btn-group">
                <a href="{{ route('roles.index') }}" class="btn btn-primary">
                    <i class="fa fa-mirror"></i> View Roles
                </a>
                <button type="button" class="btn btn-warning" id="toggleCheckboxes">
                    <i class="fa fa-check-square"></i> Check All
                </button>
            </div>
        </div>

        <form action="{{ route('roles.updatePermissions', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="table-responsive">
                <table id="permissionsTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Module Name</th>
                            <th colspan="4">Permissions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $assignedPermissions = $role->permissions->pluck('name')->toArray();
                            $groupedPermissions = $permissions->groupBy('module_name');
                        @endphp

                        @foreach($groupedPermissions as $moduleName => $modulePermissions)
                            <tr>                                        
                                    <td rowspan="{{ ceil(count($modulePermissions) / 4) }}" 
                                        style="background-color:#F8FAFC; color: #000; text-align: left; font-weight: bold; font-size: 16px; padding: 12px; border: 1px solidrgb(212, 219, 224); border-radius: 5px;">
                                        <strong>{{ ucfirst(str_replace('_', ' ', $moduleName)) }}</strong>
                                    </td>                                
                                @foreach($modulePermissions as $index => $permission)
                                    @if($index % 4 == 0 && $index != 0)
                                        </tr><tr>
                                    @endif
                                    <td>
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                            {{ in_array($permission->name, $assignedPermissions) ? 'checked' : '' }} 
                                            class="permission-checkbox flat-red">
                                        <label>{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-left mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Update Permissions
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('toggleCheckboxes').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });

        if (allChecked) {
            this.innerHTML = '<i class="fa fa-check-square"></i> Check All';
        } else {
            this.innerHTML = '<i class="fa fa-check-square"></i> Uncheck All';
        }
    });

    $(document).ready(function() {
        $('#permissionsTable').DataTable({
            "paging": true,
            "searching": true,
            "info": true,
            "columnDefs": [
                { "orderable": false, "targets": 1 }
            ]
        });
    });
</script>

@endsection
