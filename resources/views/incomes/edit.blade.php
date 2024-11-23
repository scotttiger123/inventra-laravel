@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">

        <!-- Edit Income Section -->
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Income</h3>
                    </div>

                    <!-- Form to Edit Income -->
                    <form action="{{ route('income.update', $income->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="box-body">
                            <!-- Payment Date -->
                            <div class="form-group">
                                <label for="payment_date">Payment Date</label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ old('payment_date', $income->payment_date) }}" required>
                            </div>

                            <!-- Payment Head -->
                            <div class="form-group">
                                <label for="payment_head">Payment Head</label>
                                <select class="form-control" id="payment_head" name="payment_head" required>
                                    @foreach($paymentHeads as $head)
                                        <option value="{{ $head->id }}" {{ $head->id == old('payment_head', $income->payment_head) ? 'selected' : '' }}>
                                            {{ $head->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Amount -->
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $income->amount) }}" required>
                            </div>

                            <!-- Note -->
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea class="form-control" id="note" name="note">{{ old('note', $income->note) }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('income.index') }}" class="btn btn-success">
                                <i class="fa fa-eye"></i> View Incomes
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
