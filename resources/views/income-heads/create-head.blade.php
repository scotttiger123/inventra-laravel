@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Page Title -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Create Income Categories</h3>
        </div>

        <!-- Form for Creating Income Head -->
        <form action="{{ route('income-heads.store-head') }}" method="POST">
            @csrf

            <!-- Income Head Name -->
            <div class="form-group">
                <label for="name">Income Categories Name <span class="text-danger">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control" 
                    placeholder="Enter income head name" 
                    required
                    value="{{ old('name') }}">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Income Head Description -->
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
                <a href="{{ route('income-heads.index-head') }}" class="btn btn-primary">
                    <i class="fa fa-eye"></i> View Heads
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Save Income Head
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
