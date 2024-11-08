@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Payment Listings</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Payment -->
        <div class="text-right">
            <a href="{{ route('payments.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Payment
            </a>
        </div>

        <!-- Payments Listings Table -->
        <table id="product-listings"class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Payment Method</th>
                    <th>Payment Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr id="paymentRow-{{ $payment->id }}">
                    <!-- Payment Method with badge class for Credit or Debit -->
                    <td>
                        
                            {{ ucfirst($payment->payment_method) }}
                        
                    </td>
                    
                    <!-- Payment Type with custom badge-badge-success class -->
                    <td>
                        <span class="badge {{ $payment->payment_type === 'credit' ? 'badge-badge-success' : 'badge-badge-danger' }}">
                            @if($payment->payment_type === 'credit')
                                Payment In (Credit)
                            @elseif($payment->payment_type === 'debit')
                                Payment Out (Debit)
                            @else
                                {{ ucfirst($payment->payment_type) }}
                            @endif
                        </span>
                    </td>

                    <td>
                        <span class="badge {{ $payment->status === 'Completed' ? 'badge-badge-success' : 'badge-danger' }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>

                    <td>
                        <div class="custom-dropdown text-center">
                            <button class="custom-dropdown-toggle" type="button">
                                Actions <i class="fa fa-caret-down"></i>
                            </button>
                            <div class="custom-dropdown-menu">
                                <!-- View Payment Option -->
                                <button 
                                    class="custom-dropdown-item" 
                                    type="button"
                                    data-toggle="modal" 
                                    data-target="#viewPaymentModal"
                                    data-id="{{ $payment->id }}"
                                    data-method="{{ $payment->payment_method }}"
                                    data-type="{{ $payment->payment_type }}"
                                    data-status="{{ $payment->status }}"
                                >
                                    <i class="fa fa-eye"></i> View
                                </button>
                              
                                <!-- Edit Payment Option -->
                                <a href="{{ route('payments.edit', $payment->id) }}" class="custom-dropdown-item">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                <!-- Delete Payment Option -->
                                <form id="deleteForm-{{ $payment->id }}" action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="custom-dropdown-item delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeletePayment({{ $payment->id }})" class="delete-btn btn btn-danger">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Viewing Payment Details -->
<div class="modal fade" id="viewPaymentModal" tabindex="-1" role="dialog" aria-labelledby="viewPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPaymentModalLabel">Payment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Payment Method:</strong> <span id="payment-method"></span></p>
                <p><strong>Payment Type:</strong> <span id="payment-type"></span></p>
                <p><strong>Status:</strong> <span id="payment-status"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
