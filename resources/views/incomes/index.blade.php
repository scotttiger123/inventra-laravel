@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Total Summary Section -->
        <div class="row">
            @php
                $totalPayments = $totalPayments;  // Total number of payments
                $totalAmount = $totalAmount;  // Total sum of payments
                
            @endphp

            <!-- Total Payments Box -->
            <div class="col-lg-6 col-xs-">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalPayments }}</h3>
                        <p>Income Records</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-list"></i>
                    </div>
                </div>
            </div>

            <!-- Total Count Amount Box (New) -->
            <div class="col-lg-6 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ number_format($totalAmount, 2) }}</h3> <!-- Format the total count amount -->
                        <p>Total Income Amount</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-calculator"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments History Table Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Income History</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Payment -->
        <div class="text-right">
            <a href="{{ route('income.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add New Income
            </a>
        </div>

        <!-- Payments Listings Table -->
        <table id="income-listings" class="table table-bordered table-striped">
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
                @foreach($payments as $income)
                    <tr>
                        <td>{{ $income->payment_date }}</td>
                        <td>{{ $income->paymentHead->name ?? 'N/A' }}</td>
                        <td>{{ number_format($income->amount, 2) }}</td> <!-- Format amount -->
                        <td>{{ $income->note }}</td>
                        <td>
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- Edit income -->
                                    <a href="{{ route('income.edit', $income->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete income -->
                                    <form id="deleteForm-{{ $income->id }}" 
                                          action="{{ route('income.destroy', $income->id) }}" 
                                          method="POST" 
                                          class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteIncome({{ $income->id }})" class="btn btn-danger">
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
        $('#income-listings').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            'order': [[0, 'desc']] // Sorts by first column (Payment Date) in descending order
        });
    });

    function confirmDeleteIncome(id) {
        if (confirm('Are you sure you want to delete this income record?')) {
            document.getElementById('deleteForm-' + id).submit();
        }
    }
</script>

@endsection
