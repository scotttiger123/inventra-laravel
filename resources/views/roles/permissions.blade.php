@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">
                Permissions for Role: 
                @if(strtolower($role->name) == 'sales manager')
                    <span style="color: #ff5733;">{{ ucfirst($role->name) }}</span> <!-- Custom color for Sales Manager -->
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

        <!-- Button Group for View Roles and Check All -->
        <div class="text-right mb-3">
            <div class="btn-group">
                <!-- View Roles Button -->
                <a href="{{ route('roles.index') }}" class="btn btn-primary">
                    <i class="fa fa-mirror"></i> View Roles
                </a>

                <!-- Check All / Uncheck All Button -->
                <button type="button" class="btn btn-warning" id="toggleCheckboxes">
                    <i class="fa fa-check-square"></i> Check All
                </button>
            </div>
        </div>

        <!-- Permissions Listings Table -->
        <form action="{{ route('roles.updatePermissions', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Module Name</th>
                            <th>Grant Permission</th>
                            <th>Module Name</th>
                            <th>Grant Permission</th>
                            <th>Module Name</th>
                            <th>Grant Permission</th>
                            <th>Module Name</th>
                            <th>Grant Permission</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Convert assigned permissions to an array for checking
                            $assignedPermissions = $role->permissions->pluck('name')->toArray();
                            $count = 0;
                        @endphp

                        <!-- Iterate through each permission -->
                        @foreach($permissions as $permission)
                            @if($count % 4 == 0)
                                <tr>
                            @endif

                            <td>{{ ucfirst(str_replace('_', ' ', $permission->name)) }}</td>
                            <td>
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                    {{ in_array($permission->name, $assignedPermissions) ? 'checked' : '' }} class="permission-checkbox">
                            </td>

                            @php
                                $count++; // Increment the column counter
                            @endphp

                            @if($count % 4 == 0)
                                </tr>
                            @endif
                        @endforeach

                        <!-- Close the last row if it wasn't closed -->
                        @if($count % 4 != 0)
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Submit Button to Update Permissions -->
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
        
        // Toggle the state of all checkboxes
        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });

        // Change the button text based on the new state
        if (allChecked) {
            this.innerHTML = '<i class="fa fa-check-square"></i> Check All';
        } else {
            this.innerHTML = '<i class="fa fa-check-square"></i> Uncheck All';
        }
    });
</script>

@endsection
