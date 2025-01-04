@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Form Header -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Edit Currency Setting</h3>
        </div>

        <!-- Display Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

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

        <!-- Currency Setting Form -->
        <form action="{{ route('settings.update', $setting->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Currency Selection -->
            <div class="form-group">
                        <label for="value">Currency <span class="text-danger">*</span></label>
                        <select name="value" id="value" class="form-control" required>
                            <option value="">Select Currency</option>
                            @foreach ($currencies as $currencyItem)
                                <option value="{{ $currencyItem->symbol }}" 
                                    @if(old('value', $setting->value) == $currencyItem->symbol) selected @endif>
                                    {{ $currencyItem->name }} ({{ $currencyItem->symbol }})
                                </option>
                            @endforeach
                        </select>
                        @error('value')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>

            <!-- Submit Button -->
            <div class="text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Update Setting
                </button>
                <button type="button" class="btn btn-secondary" onclick="location.href='{{ route('settings.index') }}'">
                    <i class="fa fa-arrow-left"></i> Back
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
