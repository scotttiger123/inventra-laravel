@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">System Settings</h3>
        </div>

        @if (session('success'))
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

        <form action="{{ route('settings.edit') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="currency-symbol">Currency</label>
        <select name="currency-symbol" id="currency-symbol" class="form-control" required>
            <option value="">Select Currency</option>
            @foreach ($currencies as $currency)
                <option value="{{ $currency->symbol }}" 
                    @if($currency->symbol == old('currency-symbol', $currencySymbol)) selected @endif>
                    {{ $currency->name }} ({{ $currency->symbol }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="company-name">Company Name</label>
        <input type="text" name="company-name" id="company-name" class="form-control" 
               value="{{ old('company-name', $companyName) }}">
    </div>

    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control" 
               value="{{ old('phone', $phone) }}">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" 
               value="{{ old('email', $email) }}">
    </div>

    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" name="address" id="address" class="form-control" 
               value="{{ old('address', $address) }}">
    </div>

    <div class="form-group">
        <label for="invoice-footer">Invoice Footer</label>
        <textarea name="invoice-footer" id="invoice-footer" class="form-control" rows="3">{{ old('invoice-footer', $invoiceFooter) }}</textarea>
    </div>

    <div class="form-group form-check">
        <input type="checkbox" name="enable-invoice-footer" id="enable-invoice-footer" class="form-check-input" 
               @if (old('enable-invoice-footer', $enableInvoiceFooter) == '1') checked @endif>
        <label for="enable-invoice-footer" class="form-check-label">Enable Invoice Footer</label>

        <!-- Hidden input to ensure 0 is sent when checkbox is unchecked -->
        <input type="hidden" name="enable-invoice-footer" value="0">
    </div>

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
