@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Page Title -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Create Expense Head</h3>
        </div>

        <!-- Form for Creating Expense Head -->
        <form action="{{ route('expenses-heads.store') }}" method="POST">
            @csrf

            <!-- Expense Head Name -->
            <div class="form-group">
                <label for="name">Expense Head Name <span class="text-danger">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control" 
                    placeholder="Enter expense head name" 
                    required
                    value="{{ old('name') }}">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Expense Head Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control" 
                    placeholder="Enter a description">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button and View Heads -->
            <div class="form-group text-right">
                <a href="{{ route('expenses-heads.index') }}" class="btn btn-primary">
                    <i class="fa fa-eye"></i> View Heads
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Save Expense Head
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
