@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Edit Tax</h3>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('tax.update', $tax->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Tax Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $tax->name }}" required>
            </div>
            
            <div class="form-group">
                <label for="rate">Tax Rate (%)</label>
                <input type="number" step="0.01" name="rate" id="rate" class="form-control" value="{{ $tax->rate }}" required>
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Update
                </button>
                <a href="{{ route('tax.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
