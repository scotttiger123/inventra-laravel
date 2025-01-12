@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Form Header -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">System Settings</h3>
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

        <!-- Settings Form -->
        <form action="{{ route('settings.store') }}" method="POST">
            @csrf

            @foreach ($settings as $setting)
                <div class="form-group">
                    <label for="{{ $setting->name }}">{{ ucfirst(str_replace('-', ' ', $setting->name)) }}</label>

                    @if ($setting->name === 'currency-symbol')
                        <!-- Currency Dropdown -->
                        <select name="{{ $setting->name }}" id="{{ $setting->name }}" class="form-control" required>
                            <option value="">Select Currency</option>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->symbol }}" 
                                    @if($setting->value == $currency->symbol) selected @endif>
                                    {{ $currency->name }} ({{ $currency->symbol }})
                                </option>
                            @endforeach
                        </select>
                    @elseif ($setting->name === 'invoice-footer')
                        <!-- Textarea for Invoice Footer -->
                        <textarea name="{{ $setting->name }}" id="{{ $setting->name }}" 
                                  class="form-control" rows="3">{{ $setting->value }}</textarea>
                    @elseif ($setting->name === 'enable-invoice-footer')
                        <!-- Checkbox for Enable Invoice Footer -->
                        <div class="form-check">
                            <input type="checkbox" name="{{ $setting->name }}" id="{{ $setting->name }}" 
                                   class="form-check-input" 
                                   @if($setting->value == '1') checked @endif>
                            <label for="{{ $setting->name }}" class="form-check-label">Enable Invoice Footer</label>
                        </div>
                    @else
                        <!-- Default Input for Other Settings -->
                        <input type="text" name="{{ $setting->name }}" id="{{ $setting->name }}" 
                               class="form-control" value="{{ $setting->value }}">
                    @endif
                </div>
            @endforeach

            <!-- Submit and Back Buttons -->
            <div class="text-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Save
                </button>
                <button type="button" class="btn btn-secondary" onclick="location.href='{{ route('settings.index') }}'">
                    <i class="fa fa-arrow-left"></i> Back
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
