@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Total Summary Section -->
        <div class="row">
            @php
                $totalAccounts = $totalAccounts; // Total account records
            @endphp

            <!-- Total Account Records Box -->
            <div class="col-lg-12 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalAccounts }}</h3>
                        <p>Account Records</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-list"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account History Table Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Account History</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Account -->
        <div class="text-right">
            <a href="{{ route('accounts.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add New Account
            </a>
        </div>

        <!-- Accounts Listings Table -->
        <table id="account-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Account No</th>
                    <th>Name</th>
                    <th>Initial Balance</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                    <tr>
                        <td>{{ $account->account_no }}</td>
                        <td>{{ $account->name }}</td>
                        <td>{{ number_format($account->initial_balance, 2) }}</td>
                        <td>{{ $account->note ?? 'N/A' }}</td>
                        <td>
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- Edit Account -->
                                    <a href="{{ route('accounts.edit', $account->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete Account -->
                                    <form id="deleteForm-{{ $account->id }}" 
                                          action="{{ route('accounts.destroy', $account->id) }}" 
                                          method="POST" 
                                          class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteAccount({{ $account->id }})" class="btn btn-danger">
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
        $('#account-listings').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            'order': [[0, 'desc']] // Sorts by first column (Account No) in descending order
        });
    });

    function confirmDeleteAccount(id) {
        if (confirm('Are you sure you want to delete this account record?')) {
            document.getElementById('deleteForm-' + id).submit();
        }
    }
</script>

@endsection
