@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Form Header -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Add New Tax</h3>
        </div>

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tax Form -->
        <form action="{{ route('tax.store') }}" method="POST">
            @csrf

            <!-- Tax Name -->
            <div class="form-group">
                <label for="name">Tax Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter tax name" required>
            </div>

            <!-- Tax Rate -->
            <div class="form-group">
                <label for="rate">Tax Rate (%) <span class="text-danger">*</span></label>
                <input type="number" name="rate" id="rate" class="form-control" placeholder="Enter tax rate" required step="0.01" min="0">
            </div>

            <!-- Submit Button -->
            <div class="text-right">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Save Tax
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
