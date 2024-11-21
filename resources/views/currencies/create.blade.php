@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Create New Currency</h3>
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
        </div>

        <form action="{{ route('currencies.store') }}" method="POST">
            @csrf

            <!-- Currency Information Row -->
            <div class="row">
                <!-- Currency Name Field -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Currency Name *</label>
                        <input type="text" name="currency_name" class="form-control myInput" placeholder="Enter currency name" required />
                    </div>
                </div>

                <!-- Currency Code Field -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Currency Code *</label>
                        <input type="text" name="currency_code" class="form-control myInput" placeholder="Enter currency code (e.g., USD)" required />
                    </div>
                </div>
            </div>

            <!-- Currency Symbol Row -->
            <div class="row mt-3">
                <!-- Currency Symbol Field -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Currency Symbol *</label>
                        <input type="text" name="currency_symbol" class="form-control myInput" placeholder="Enter currency symbol (e.g., $)" required />
                    </div>
                </div>
            </div>

            <!-- Submit Button Row -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Create Currency</button>
                    <a href="{{ route('currencies.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
