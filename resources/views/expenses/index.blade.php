@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Total Summary Section -->
        <div class="row">
            @php
                $totalPayments = $totalPayments;  // Total number of payments
                $totalAmount = $totalAmount;  // Total sum of payments
                $totalCountAmount = $totalCountAmount;  // New total count amount
            @endphp

            <!-- Total Payments Box -->
            <div class="col-lg-6 col-xs-">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalPayments }}</h3>
                        <p>Expenses</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-list"></i>
                    </div>
                </div>
            </div>

            <!-- Total Count Amount Box (New) -->
            <div class="col-lg-6 col-xs-12">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ number_format($totalCountAmount, 2) }}</h3> <!-- Format the total count amount -->
                        <p>Total Expenses Amount</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-calculator"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments History Table Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Expenses History</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Payment -->
        <div class="text-right">
            <a href="{{ route('expenses.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add New Expense
            </a>
        </div>

        <!-- Payments Listings Table -->
        <table id="expenses-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $expenses)
                    <tr>
                        <td>{{ $expenses->payment_date }}</td>
                        <td>{{ $expenses->paymentHead->name ?? 'N/A' }}</td>
                        <td>{{ number_format($expenses->amount, 2) }}</td> <!-- Format amount -->
                        <td>{{ $expenses->note }}</td>
                        <td>
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- Edit expenses -->
                                    <a href="{{ route('expenses.edit', $expenses->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete expenses -->
                                    <form id="deleteForm-{{ $expenses->id }}" 
                                          action="{{ route('expenses.destroy', $expenses->id) }}" 
                                          method="POST" 
                                          class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeletePayment({{ $expenses->id }})" class="btn btn-danger">
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

<script>
    
    $(function () {
    $('#expenses-listings').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': true,
        'info': true,
        'autoWidth': true,
        'order': [[0, 'desc']] // Sorts by first column (Payment Date) in descending order
    });
});
</script>

@endsection
