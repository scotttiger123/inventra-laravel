@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Create New Role</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Form to Create Role -->
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="roleName">Role Name</label>
                <input type="text" name="name" id="roleName" class="form-control" placeholder="Enter role name" required>
            </div>

            <div class="form-group">
                <label for="roleDescription">Role Description</label>
                <textarea name="description" id="roleDescription" class="form-control" placeholder="Enter role description" rows="4" required></textarea>
            </div>

            <!-- Submit Button -->
            <div class="text-right mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Create Role
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
