@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Permissions for Role: {{ ucfirst($role->name) }}</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Edit Role -->
        <div class="text-right mb-3">
            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">
                <i class="fa fa-edit"></i> Edit Role
            </a>
        </div>

        <!-- Permissions Listings Table -->
        <form action="{{ route('roles.updatePermissions', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Permission Name</th>
                            <th>Access</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{{ ucfirst($permission->name) }}</td>
                                <td>
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                           {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Submit Button to Update Permissions -->
            <div class="text-right mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Update Permissions
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
