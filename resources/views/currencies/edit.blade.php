@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Edit Currency</h3>
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

        <!-- Currency Edit Form -->
        <form action="{{ route('currencies.update', $currency->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="box-body">
                <!-- Currency Name -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Currency Name *</label>
                        <input type="text" name="currency_name" class="form-control myInput" value="{{ old('currency_name', $currency->name) }}" required>
                        @error('currency_name')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Currency Code -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Currency Code *</label>
                        <input type="text" name="currency_code" class="form-control myInput" value="{{ old('currency_code', $currency->code) }}" required>
                        @error('currency_code')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Currency Symbol -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Currency Symbol</label>
                        <input type="text" name="currency_symbol" class="form-control myInput" value="{{ old('currency_symbol', $currency->symbol) }}">
                        @error('currency_symbol')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Update Currency</button>
                <button class="btn btn-success" onclick="location.href='{{ route('currencies.index') }}'">View Currencies</button>
            </div>
        </form>
    </div>
</div>
@endsection
