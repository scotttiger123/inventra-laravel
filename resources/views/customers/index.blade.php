@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">
                Customers
            </h3>
        </div>
        <div class="text-right">
            <!-- Add Customer link -->
            <a href="{{ route('customers.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Customer
            </a>
        </div>

        <table id="customer-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr id="customerRow-{{ $customer->id }}">
                    <td>{{ strtoupper($customer->name) }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->city }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>                  
                        <div class="custom-dropdown text-center">
                            <button class="custom-dropdown-toggle" type="button">
                                Actions <i class="fa fa-caret-down"></i>
                            </button>

                            <div class="custom-dropdown-menu">
                                <!-- View Option -->
                                <button 
                                    class="custom-dropdown-item" 
                                    type="button"
                                    data-toggle="modal" 
                                    data-target="#viewCustomerModal"
                                    data-id="{{ $customer->id }}"
                                    data-name="{{ $customer->name }}"
                                    data-email="{{ $customer->email }}"
                                    data-phone="{{ $customer->phone }}"
                                    data-address="{{ $customer->address }}"
                                    data-city="{{ $customer->city }}"
                                    data-po-box="{{ $customer->po_box }}"
                                    data-initial-balance="{{ $customer->initial_balance }}"
                                    data-tax-number="{{ $customer->tax_number }}"
                                    data-discount-type="{{ $customer->discount_type }}"
                                    data-discount-value="{{ $customer->discount_value }}"
                                >
                                    <i class="fa fa-eye"></i> View
                                </button>
                              
                                <!-- Edit Option -->
                                <a href="{{ route('customers.edit', $customer->id) }}" class="custom-dropdown-item">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                <!-- Delete Option -->
                                <form id="deleteForm-{{ $customer->id }}" action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="custom-dropdown-item delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteCustomer({{ $customer->id }})" class="delete-btn btn btn-danger">
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

<!-- Modal for Viewing Customer Details -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1" role="dialog" aria-labelledby="viewCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCustomerModalLabel">Customer Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="customer-name"></span></p>
                <p><strong>Email:</strong> <span id="customer-email"></span></p>
                <p><strong>Phone:</strong> <span id="customer-phone"></span></p>
                <p><strong>Address:</strong> <span id="customer-address"></span></p>
                <p><strong>City:</strong> <span id="customer-city"></span></p>
                <p><strong>PO Box:</strong> <span id="customer-po-box"></span></p>
                <p><strong>Initial Balance:</strong> <span id="customer-initial-balance"></span></p>
                <p><strong>Tax Number:</strong> <span id="customer-tax-number"></span></p>
                <p><strong>Discount Type:</strong> <span id="customer-discount-type"></span></p>
                <p><strong>Discount Value:</strong> <span id="customer-discount-value"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
