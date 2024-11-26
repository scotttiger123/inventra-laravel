@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Add New Brand</h3>
        </div>

        <!-- Success and Error Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Brand Form -->
        <form action="{{ route('brand.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Brand Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="description">Brand Description</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Save Brand
                </button>
                <a href="{{ route('brand.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
