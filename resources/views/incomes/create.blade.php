@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Page Title -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Add Income</h3>
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

        <!-- Form for Adding Income -->
        <form action="{{ route('income.store') }}" method="POST">
            @csrf

            <!-- Row 1: Payment Date and Income Source -->
            <div class="row">
                <div class="col-md-6">
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
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_head">Select Income Head</label>
                        <select id="payment_head" name="payment_head" class="form-control">
                            @foreach($paymentHeads as $paymentHead)
                                <option value="{{ $paymentHead->id }}">{{ $paymentHead->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Row 2: Amount and Note -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="amount">Amount <span class="text-danger">*</span></label>
                        <input 
                            type="number" 
                            id="amount" 
                            name="amount" 
                            class="form-control" 
                            placeholder="Enter income amount" 
                            required
                            value="{{ old('amount') }}" 
                            step="0.01" 
                            min="0">
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
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
                </div>
            </div>

            <!-- Submit Button and View Income -->
            <div class="form-group text-right">
                <a href="{{ route('income.index') }}" class="btn btn-primary">
                    <i class="fa fa-eye"></i> View Income
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Save Income
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
