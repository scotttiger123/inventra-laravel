@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">
                <i class="fa fa-edit"></i> Edit Customer
            </h3>
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
            <div class="text-right">
                <button class="btn btn-success" onclick="location.href='{{ route('customers.index') }}'">
                    <i class="fa fa-eye"></i> View Customers
                </button>
            </div>
        </div>

        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="box-body">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" class="form-control myInput" value="{{ old('name', $customer->name) }}" required>
                        @error('name')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control myInput" value="{{ old('email', $customer->email) }}" required>
                        @error('email')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control myInput" value="{{ old('phone', $customer->phone) }}">
                        @error('phone')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control myInput" value="{{ old('address', $customer->address) }}">
                        @error('address')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control myInput" value="{{ old('city', $customer->city) }}">
                        @error('city')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Postal Code</label>
                        <input type="text" name="postal_code" class="form-control myInput" value="{{ old('postal_code', $customer->postal_code) }}">
                        @error('postal_code')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tax Number</label>
                        <input type="text" name="tax_number" class="form-control myInput" value="{{ old('tax_number', $customer->tax_number) }}">
                        @error('tax_number')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Initial Balance</label>
                        <input type="number" name="initial_balance" class="form-control myInput" value="{{ old('initial_balance', $customer->initial_balance) }}">
                        @error('initial_balance')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Discount Type</label>
                        <select name="discount_type" class="form-control myInput">
                            <option value="flat" {{ old('discount_type', $customer->discount_type) == 'flat' ? 'selected' : '' }}>Flat</option>
                            <option value="percentage" {{ old('discount_type', $customer->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        </select>
                        @error('discount_type')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Discount Value</label>
                        <input type="number" name="discount_value" class="form-control myInput" step="any" value="{{ old('discount_value', $customer->discount_value) }}">
                        @error('discount_value')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
            </div>
            <div class="form-group mt-3">  
                <button type="submit" class="btn btn-primary">Update Customer</button>
            </div> 
        </form>
    </div>
</div>
@endsection
