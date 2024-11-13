@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Add Customer</h3>
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
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="box-body">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control myInput" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control myInput" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control myInput" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control myInput" value="{{ old('address') }}">
                        @error('address')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control myInput" value="{{ old('city') }}">
                        @error('city')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>PO Box</label>
                        <input type="text" name="po_box" class="form-control myInput" value="{{ old('po_box') }}">
                        @error('po_box')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tax Number</label>
                        <input type="text" name="tax_number" class="form-control myInput" value="{{ old('tax_number') }}">
                        @error('tax_number')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Initial Balance</label>
                        <input type="number" name="initial_balance" class="form-control myInput" step="any" value="{{ old('initial_balance') }}">
                        @error('initial_balance')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Discount Type</label>
                        <select name="discount_type" class="form-control myInput">
                            <option value="">Select Discount Type...</option>
                            <option value="flat" {{ old('discount_type') == 'flat' ? 'selected' : '' }}>Flat</option>
                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        </select>
                        @error('discount_type')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Discount Value</label>
                        <input type="number" name="discount_value" class="form-control myInput" step="any" value="{{ old('discount_value') }}">
                        @error('discount_value')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Add Customer</button>
                <button class="btn btn-success" onclick="location.href='{{ route('customers.index') }}'">View Customers</button>
            </div>
        </form>
    </div>
</div>
@endsection
