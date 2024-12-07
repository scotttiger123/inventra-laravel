@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Form Header -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Add New Account</h3>
        </div>

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Account Form -->
        <form action="{{ route('accounts.store') }}" method="POST">
            @csrf

            <!-- Account Number -->
            <div class="form-group">
                <label for="account_no">Account Number <span class="text-danger">*</span></label>
                <input type="text" name="account_no" id="account_no" class="form-control" placeholder="Enter account number" required>
            </div>

            <!-- Account Name -->
            <div class="form-group">
                <label for="name">Account Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter account name" required>
            </div>

            <!-- Initial Balance -->
            <div class="form-group">
                <label for="initial_balance">Initial Balance</label>
                <input type="number" name="initial_balance" id="initial_balance" class="form-control" placeholder="Enter initial balance" step="0.01" min="0">
            </div>

            <!-- Note -->
            <div class="form-group">
                <label for="note">Note</label>
                <textarea name="note" id="note" class="form-control" placeholder="Enter a note (optional)"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="text-right">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Save Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
