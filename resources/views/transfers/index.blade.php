@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Transfer Listings</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Create a New Transfer -->
        <div class="text-left">
            <a href="{{ route('transfers.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Create Transfer
            </a>
        </div>

        <!-- Transfer Listings Table -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Product Name</th>  <!-- Add this column for product name -->
                    <th>From Warehouse</th>
                    <th>To Warehouse</th>
                    <th>Quantity</th>
                    <th>Transfer Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transfers as $transfer)
                <tr id="transferRow-{{ $transfer->id }}">
                    <td>{{ $transfer->product->product_name ?? 'Product not found' }}</td>  <!-- Display product name -->
                    <td>{{ $transfer->fromWarehouse->name }}</td>
                    <td>{{ $transfer->toWarehouse->name }}</td>
                    <td>{{ $transfer->quantity }}</td>
                    <td>{{ $transfer->date }}</td>
                    <td>
                        <div class="custom-dropdown text-center">
                            <button class="custom-dropdown-toggle" type="button">
                                Actions <i class="fa fa-caret-down"></i>
                            </button>
                            <div class="custom-dropdown-menu">
                                <!-- View Transfer Option -->
                                <button 
                                    class="custom-dropdown-item" 
                                    type="button"
                                    data-toggle="modal" 
                                    data-target="#viewTransferModal"
                                    data-from-warehouse="{{ $transfer->fromWarehouse->name }}"
                                    data-to-warehouse="{{ $transfer->toWarehouse->name }}"
                                    data-quantity="{{ $transfer->quantity }}"
                                    data-date="{{ $transfer->date }}"
                                >
                                    <i class="fa fa-eye"></i> View
                                </button>

                                <!-- Edit Transfer Option -->
                                <a href="{{ route('transfers.edit', $transfer->id) }}" class="custom-dropdown-item">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                <!-- Delete Transfer Option -->
                                <form id="deleteForm-{{ $transfer->id }}" action="{{ route('transfers.destroy', $transfer->id) }}" method="POST" class="custom-dropdown-item delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeleteTransfer({{ $transfer->id }})" class="delete-btn btn btn-danger">
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

<!-- Modal for Viewing Transfer Details (View-Only) -->
<div class="modal fade" id="viewTransferModal" tabindex="-1" role="dialog" aria-labelledby="viewTransferModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTransferModalLabel">Transfer Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>From Warehouse:</strong> <span id="from-warehouse"></span></p>
                <p><strong>To Warehouse:</strong> <span id="to-warehouse"></span></p>
                <p><strong>Quantity:</strong> <span id="quantity"></span></p>
                <p><strong>Date:</strong> <span id="transfer-date"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#viewTransferModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var fromWarehouse = button.data('from-warehouse');
        var toWarehouse = button.data('to-warehouse');
        var quantity = button.data('quantity');
        var date = button.data('date');

        var modal = $(this);
        modal.find('#from-warehouse').text(fromWarehouse);
        modal.find('#to-warehouse').text(toWarehouse);
        modal.find('#quantity').text(quantity);
        modal.find('#transfer-date').text(date);
    });

    function confirmDeleteTransfer(transferId) {
        if (confirm('Are you sure you want to delete this transfer?')) {
            document.getElementById(`deleteForm-${transferId}`).submit();
        }
    }
</script>
@endsection
