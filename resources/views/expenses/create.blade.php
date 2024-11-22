@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Page Title -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Add Expense</h3>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form for Adding Expense -->
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf

            <!-- Payment Date -->
            <div class="form-group">
                <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                <input 
                    type="date" 
                    id="payment_date" 
                    name="payment_date" 
                    class="form-control" 
                    required
                    value="{{ old('payment_date', date('Y-m-d')) }}">
                @error('payment_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Payment Head Dropdown -->
            <div class="form-group">
                <label for="payment_head">Expense Head <span class="text-danger">*</span></label>
                <select id="payment_head" name="payment_head" class="form-control" required>
                    <option value="" disabled selected>Select Expense Head</option>
                    @foreach($paymentHeads as $head)
                        <option value="{{ $head->id }}" {{ old('payment_head') == $head->id ? 'selected' : '' }}>
                            {{ $head->name }}
                        </option>
                    @endforeach
                </select>
                @error('payment_head')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Expense Amount -->
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input 
                    type="number" 
                    id="amount" 
                    name="amount" 
                    class="form-control" 
                    placeholder="Enter expense amount" 
                    required
                    value="{{ old('amount') }}" 
                    step="0.01" 
                    min="0">
                @error('amount')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Note -->
            <div class="form-group">
                <label for="note">Note</label>
                <textarea 
                    id="note" 
                    name="note" 
                    class="form-control" 
                    placeholder="Enter additional notes">{{ old('note') }}</textarea>
                @error('note')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button and View Expenses -->
            <div class="form-group text-right">
                <a href="{{ route('expenses.index') }}" class="btn btn-primary">
                    <i class="fa fa-eye"></i> View Expenses
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Save Expense
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
