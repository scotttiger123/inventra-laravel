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
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
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
        <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                
               
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="payment_head" class="text-primary font-weight-bold">
                            Transaction Type <span class="text-danger">*</span>
                        </label>
                            <small class="d-block text-muted">Choose the category of payment (e.g., Customer, Supplier)</small>
                        
                        <select id="payment_head" name="payment_head" class="form-control myInput" required>
                            <option value="">Select Category...</option>
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
                        <small class="d-block text-muted">The entity or account against which the payment is made</small>
                        <select name="payable_id" class="form-control myInput" id="payable_id" required>
                            <option value="">Select...</option>
                            
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
                        <input type="number" step="0.01" name="amount" class="form-control myInput" value="{{ old('amount') }}" >
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
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            
                        </select>
                        @error('status')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
 
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Payment Method *</label>
                        <select name="payment_method" class="form-control myInput" required>
                            @foreach ($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->name }}" {{ old('payment_method') == $paymentMethod->name ? 'selected' : '' }}>
                                    {{ $paymentMethod->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Account</label>
                        <select name="account_id" class="form-control myInput" >
                            <option value="">Select Account</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('account_id')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-3">
                        <label class="mr-2">Import CSV</label>
                        <input type="file" name="import_csv" class="form-control btn btn-primary" >
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                       <label class="mr-2">Sample File</label>
                        <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#importCsvModal">
                            <i class="fa fa-info-circle"></i>
                        </button>
                    </div>     
                </div>    
            
            </div>
            
            <!-- Remarks/Note Field (New Field Added) -->
                 <div class="col-md-12">
                    <div class="form-group">
                        <label>Remarks/Notes</label>
                        <small class="d-block text-muted">Provide any additional information regarding the payment (e.g.,credit not / debit note)</small>
    
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
</div><!-- Modal for CSV Import -->
<div class="modal fade" id="importCsvModal" tabindex="-1" role="dialog" aria-labelledby="importCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importCsvModalLabel">Import CSV File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <small class="form-text text-muted">
                    If a CSV file is selected, the amount field in the form will not be required. You only need to select the following fields for importing the CSV:
                    <ul style="padding-left:30px">
                        
                        <li><strong>Transaction Type</strong> (e.g., Customer, Supplier)</li>
                        <li><strong>Payable</strong> (The entity or account against which the payment is made)</li>

                    </ul>    
                    Upload a CSV file containing payment details. The file should include the following fields:
                    <ul style="padding-left:30px">
                        <li><strong>Amount</strong> (Payment amount)</li>
                        <li><strong>Date</strong> (Payment date)</li>
                        <li><strong>Status</strong> (Completed, Pending)</li>
                        <li><strong>Payment Method</strong> (Cash, Online, etc.)</li>
                        <li><strong>Payment Note</strong> (Any credit or debit note)</li>
                    </ul>    
                </small>
                
                <a href="{{ route('download-sample-excel') }}" class="btn btn-success mt-3">
                    <i class="fa fa-download"></i> Download Sample Excel
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

