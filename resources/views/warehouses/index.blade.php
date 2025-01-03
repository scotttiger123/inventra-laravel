@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Warehouse Listings</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        
        <div class="text-left">
            <a href="{{ route('warehouse.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Warehouse
            </a>
        </div>

        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Manager Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($warehouses as $warehouse)
                <tr id="warehouseRow-{{ $warehouse->id }}">
                    <td>{{ strtoupper($warehouse->name) }}</td>
                    <td>{{ $warehouse->location }}</td>
                    <td>{{ $warehouse->manager_name }}</td>
                    <td>
                        <div class="custom-dropdown text-center">
                            <button class="custom-dropdown-toggle" type="button">
                                Actions <i class="fa fa-caret-down"></i>
                            </button>
                            <div class="custom-dropdown-menu">
                                <!-- View Warehouse Option -->
                                <button 
                                    class="custom-dropdown-item" 
                                    type="button"
                                    data-toggle="modal" 
                                    data-target="#viewWarehouseModal"
                                    data-id="{{ $warehouse->id }}"
                                    data-name="{{ $warehouse->name }}"
                                    data-location="{{ $warehouse->location }}"
                                    data-manager-name="{{ $warehouse->manager_name }}"
                                    data-contact-number="{{ $warehouse->contact_number }}"
                                    data-status="{{ $warehouse->status }}"
                                >
                                    <i class="fa fa-eye"></i> View
                                </button>

                                <!-- Edit Warehouse Option -->
                                <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="custom-dropdown-item">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                <!-- Delete Warehouse Option -->
                                <form id="deleteForm-{{ $warehouse->id }}" action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" class="custom-dropdown-item delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteWarehouse({{ $warehouse->id }})" class="delete-btn btn btn-danger">
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

<!-- Modal for Viewing Warehouse Details (View-Only) -->
<div class="modal fade" id="viewWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="viewWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewWarehouseModalLabel">Warehouse Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="warehouse-name"></span></p>
                <p><strong>Location:</strong> <span id="warehouse-location"></span></p>
                <p><strong>Manager Name:</strong> <span id="warehouse-manager-name"></span></p>
                <p><strong>Contact Number:</strong> <span id="warehouse-contact-number"></span></p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection
