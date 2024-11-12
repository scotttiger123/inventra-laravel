@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Add Warehouse</h3>
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

        <!-- Warehouse Creation Form -->
        <form action="{{ route('warehouses.store') }}" method="POST">
            @csrf
            <div class="box-body">
                <!-- Warehouse Name -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Warehouse Name *</label>
                        <input type="text" name="name" class="form-control myInput" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Location -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control myInput" value="{{ old('location') }}">
                        @error('location')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Manager Name -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Manager Name</label>
                        <input type="text" name="manager_name" class="form-control myInput" value="{{ old('manager_name') }}">
                        @error('manager_name')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Contact Number -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="contact_number" class="form-control myInput" value="{{ old('contact_number') }}">
                        @error('contact_number')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
               
            </div>

            <!-- Form Action Buttons -->
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Add Warehouse</button>
                <button class="btn btn-success" onclick="location.href='{{ route('warehouses.index') }}'">View Warehouses</button>
            </div>
        </form>
    </div>
</div>
@endsection
