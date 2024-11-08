@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">
                Suppliers
            </h3>
        </div>
        <div class="text-right">
            <!-- Add Supplier link -->
            <a href="{{ route('suppliers.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Supplier
            </a>
        </div>

        <table id="supplier-listings" class="table table-bordered table-striped">
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
                @foreach($suppliers as $supplier)
                <tr id="supplierRow-{{ $supplier->id }}">
                    <td>{{ strtoupper($supplier->name) }}</td>
                    <td>{{ $supplier->email }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ $supplier->city }}</td>
                    <td>{{ $supplier->address }}</td>
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
                                    data-target="#viewSupplierModal"
                                    data-id="{{ $supplier->id }}"
                                    data-name="{{ $supplier->name }}"
                                    data-email="{{ $supplier->email }}"
                                    data-phone="{{ $supplier->phone }}"
                                    data-address="{{ $supplier->address }}"
                                    data-city="{{ $supplier->city }}"
                                    data-po-box="{{ $supplier->po_box }}"
                                    data-initial-balance="{{ $supplier->initial_balance }}"
                                    data-tax-number="{{ $supplier->tax_number }}"
                                    data-discount-type="{{ $supplier->discount_type }}"
                                    data-discount-value="{{ $supplier->discount_value }}"
                                >
                                    <i class="fa fa-eye"></i> View
                                </button>
                              
                                <!-- Edit Option -->
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="custom-dropdown-item">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                <!-- Delete Option -->
                                <form id="deleteForm-{{ $supplier->id }}" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="custom-dropdown-item delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteSupplier({{ $supplier->id }})" class="delete-btn btn btn-danger">
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

<!-- Modal for Viewing Supplier Details -->
<div class="modal fade" id="viewSupplierModal" tabindex="-1" role="dialog" aria-labelledby="viewSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSupplierModalLabel">Supplier Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="supplier-name"></span></p>
                <p><strong>Email:</strong> <span id="supplier-email"></span></p>
                <p><strong>Phone:</strong> <span id="supplier-phone"></span></p>
                <p><strong>Address:</strong> <span id="supplier-address"></span></p>
                <p><strong>City:</strong> <span id="supplier-city"></span></p>
                <p><strong>PO Box:</strong> <span id="supplier-po-box"></span></p>
                <p><strong>Initial Balance:</strong> <span id="supplier-initial-balance"></span></p>
                <p><strong>Tax Number:</strong> <span id="supplier-tax-number"></span></p>
                <p><strong>Discount Type:</strong> <span id="supplier-discount-type"></span></p>
                <p><strong>Discount Value:</strong> <span id="supplier-discount-value"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
