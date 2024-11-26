@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">

        <h3 class="box-title custom-title">Edit Category</h3>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('category.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" name="name" class="form-control" id="name" value="{{ $category->name }}" required>
            </div>
            <div class="form-group">
                <label for="description">Category Description</label>
                <textarea name="description" class="form-control" id="description">{{ $category->description }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('category.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
