@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Edit Payment</h3>

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

        <!-- Payment Edit Form -->
        <form action="{{ route('payments.update', $payment->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Indicates that it's an update (PUT method) -->

            <div class="box-body">
                <!-- Payable Type (hidden field, as the value is set dynamically) -->
                <div class="col-md-4" hidden>
                    <div class="form-group">
                        <label>Payable Type *</label>
                        <input type="text" name="payable_type" class="form-control myInput" value="{{ old('payable_type', $payment->payable_type) }}" required>
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
                                <option value="customer" {{ old('payment_head', $payment->payable_type) == 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="supplier" {{ old('payment_head', $payment->payable_type) == 'supplier' ? 'selected' : '' }}>Supplier</option>
                            </select>
                            
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                        <label for="payable_id_label" id="payableLabel">Payable  *</label>
                            <select name="payable_id" class="form-control" id="payable_id" required>
                                <option value="">Select...</option>
                                @foreach($payables as $payable)
                                    <option value="{{ $payable->id }}" 
                                        {{ old('payable_id', $payment->payable_id) == $payable->id ? 'selected' : '' }}>
                                        {{ $payable->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                <!-- Amount Field -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Amount *</label>
                        <input type="number" step="0.01" name="amount" class="form-control myInput" value="{{ old('amount', $payment->amount) }}" required>
                        @error('amount')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Amount Date Field (Defaults to current date) -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Date *</label>
                        <input type="date" name="payment_date" class="form-control myInput" value="{{ old('payment_date', $payment->payment_date) }}" required>
                        @error('payment_date')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Invoice ID Field -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label id="saleOrPurchase"> Order # </label>
                        <input type="number" name="invoice_id" class="form-control myInput" value="{{ old('invoice_id', $payment->invoice_id) }}">
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
                            <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ old('status', $payment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $payment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                            <option value="credit" {{ old('payment_type', $payment->payment_type) == 'credit' ? 'selected' : '' }}>Credit (Payment In)</option>
                            <option value="debit" {{ old('payment_type', $payment->payment_type) == 'debit' ? 'selected' : '' }}>Debit (Payment Out)</option>
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
                            <option value="Cash" {{ old('payment_method', $payment->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Online" {{ old('payment_method', $payment->payment_method) == 'Online' ? 'selected' : '' }}>Online</option>
                            <option value="Cheque" {{ old('payment_method', $payment->payment_method) == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="Money Order" {{ old('payment_method', $payment->payment_method) == 'Money Order' ? 'selected' : '' }}>Money Order</option>
                            <option value="Western Union" {{ old('payment_method', $payment->payment_method) == 'Western Union' ? 'selected' : '' }}>Western Union</option>
                            <option value="Money Gram" {{ old('payment_method', $payment->payment_method) == 'Money Gram' ? 'selected' : '' }}>Money Gram</option>
                            <option value="Skrill" {{ old('payment_method', $payment->payment_method) == 'Skrill' ? 'selected' : '' }}>Skrill</option>
                        </select>
                        @error('payment_method')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>

            <!-- Remarks/Note Field -->
            <div class="col-md-12">
                <div class="form-group">
                    <label>Remarks/Notes</label>
                    <textarea name="note" class="form-control myInput">{{ old('note', $payment->note) }}</textarea>
                    @error('note')
                        <span class="validation-msg text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Payment</button>
                <button type="button" class="btn btn-success" onclick="location.href='{{ route('payments.index') }}'">View Payments</button>
            </div>
        </form>
    </div>
</div>
@endsection
