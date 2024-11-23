@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Edit Role</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Role Edit Form -->
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="box-body">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Role Name *</label>
                        <input type="text" name="name" class="form-control myInput" value="{{ old('name', $role->name) }}" required>
                        @error('name')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control myInput">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Update Role</button>
                <button type="button" class="btn btn-success" onclick="location.href='{{ route('roles.index') }}'">Back to Roles</button>
            </div>
        </form>
    </div>
</div>
@endsection
