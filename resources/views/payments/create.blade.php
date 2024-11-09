@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Add Payment</h3>

            <!-- Success or error message display -->
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

        <!-- Payment Creation Form -->
        <form action="{{ route('payments.store') }}" method="POST">
            @csrf
            <div class="box-body">
                <!-- Payable Type (hidden field, as the value is set dynamically) -->
                <div class="col-md-4" hidden>
                    <div class="form-group">
                        <label>Payable Type *</label>
                        <input type="text" name="payable_type" class="form-control myInput" value="{{ old('payable_type') }}" required>
                        @error('payable_type')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Payment Head Selection -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Payment Head *</label>
                        <select id="payment_head" name="payment_head" class="form-control myInput" required>
                            <option value="">Select Head...</option>
                            <option value="customer" {{ old('payment_head') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="supplier" {{ old('payment_head') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                        </select>
                        @error('payment_head')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <!-- Payable ID Dropdown (Dynamically populated based on payment head) -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="payable_id_label" id="payableLabel">Payable  *</label>
                        <select name="payable_id" class="form-control myInput" id="payable_id" required>
                            <option value="">Select...</option>
                            <!-- Options will be populated via AJAX -->
                        </select>
                        @error('payable_id')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Amount Field -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Amount *</label>
                        <input type="number" step="0.01" name="amount" class="form-control myInput" value="{{ old('amount') }}" required>
                        @error('amount')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Amount Date Field (Defaults to current date) -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Date *</label>
                        <input type="date" name="payment_date" class="form-control myInput" value="{{ old('amount_date', date('Y-m-d')) }}" required>
                        @error('amount_date')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Invoice ID Field -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label id="saleOrPurchase"> Order # </label>
                        <input type="number" name="invoice_id" class="form-control myInput" value="{{ old('invoice_id') }}">
                        @error('invoice_id')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Status Field -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status *</label>
                        <select name="status" class="form-control myInput" required>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Payment Type Selection -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Payment Type *</label>
                        <select name="payment_type" class="form-control myInput" required>
                            <option value="credit" class="badge-badge-success" {{ old('payment_type') == 'credit' ? 'selected' : '' }}><span class="text-success">+ </span> Credit (Payment In)</option>
                            <option value="debit" class="badge-badge-danger" {{ old('payment_type') == 'debit' ? 'selected' : '' }}><span class="text-danger">- </span> Debit (Payment Out)</option>
                            
                        </select>
                        @error('payment_type')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Payment Method *</label>
                        <select name="payment_method" class="form-control myInput" required>
                            <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Online" {{ old('payment_method') == 'Online' ? 'selected' : '' }}>Online</option>
                            <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="Money Order" {{ old('payment_method') == 'Money Order' ? 'selected' : '' }}>Money Order</option>
                            <option value="Western Union" {{ old('payment_method') == 'Western Union' ? 'selected' : '' }}>Western Union</option>
                            <option value="Money Gram" {{ old('payment_method') == 'Money Gram' ? 'selected' : '' }}>Money Gram</option>
                            <option value="Skrill" {{ old('payment_method') == 'Skrill' ? 'selected' : '' }}>Skrill</option>
                        </select>
                        @error('payment_method')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>
            <!-- Remarks/Note Field (New Field Added) -->
            <div class="col-md-12">
                    <div class="form-group">
                        <label>Remarks/Notes</label>
                        <textarea name="note" class="form-control myInput">{{ old('note') }}</textarea>
                        @error('note')
                            <span class="validation-msg text-danger">{{ $note }}</span>
                        @enderror
                    </div>
                </div>

            <!-- Submit Buttons -->
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Payment</button>
                <button type="button" class="btn btn-success" onclick="location.href='{{ route('payments.index') }}'">View Payments</button>
            </div>
        </form>
    </div>
</div>

@endsection
