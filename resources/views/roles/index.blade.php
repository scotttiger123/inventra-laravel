@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">

        <!-- Total Summary Section (optional) -->
        <div class="row">
            @php
                $totalRoles = $roles->count();
            @endphp

            <div class="col-lg-12 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalRoles }}</h3>
                        <p>Total Roles</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-person"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role/Permission Header Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Role/Permission Listings</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Role -->
        <div class="text-right">
            <a href="{{ route('roles.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Role
            </a>
        </div>

        <!-- Role Listings Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr id="roleRow-{{ $role->id }}">
                        <td>{{ ucfirst($role->name) }}</td>
                        <td>{{ $role->description }}</td>
                        <td>
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- Edit Role Option -->
                                    <a href="{{ route('roles.edit', $role->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Change Permissions Option -->
                                    <a href="{{ route('roles.permissions', $role->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-cogs"></i> Change Permissions
                                    </a>

                                    <!-- Delete Role Option -->
                                    <form id="deleteForm-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteRole({{ $role->id }})" class="delete-btn btn btn-danger">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Confirm delete role
    function confirmDeleteRole(roleId) {
        if (confirm("Are you sure you want to delete this role?")) {
            $('#deleteForm-' + roleId).submit();
        }
    }
</script>
@endsection
