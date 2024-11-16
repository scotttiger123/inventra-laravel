@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Edit Order</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Form for Editing Order -->
        <form action="{{ route('orders.update', $order->custom_order_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="order_date">Order Date</label>
                <input type="date" name="order_date" id="order_date" class="form-control" value="{{ old('order_date', $order->order_date) }}" required>
            </div>

            <div class="form-group">
                <label for="customer_id">Customer</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ $customer->id == $order->customer_id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="gross_amount">Gross Amount</label>
                <input type="number" name="gross_amount" id="gross_amount" class="form-control" value="{{ old('gross_amount', $order->grossAmount) }}" required>
            </div>

            <div class="form-group">
                <label for="order_discount">Order Discount</label>
                <input type="number" name="order_discount" id="order_discount" class="form-control" value="{{ old('order_discount', $order->orderDiscount) }}" required>
            </div>

            <div class="form-group">
                <label for="discount_type">Discount Type</label>
                <select name="discount_type" id="discount_type" class="form-control" required>
                    <option value="%" {{ $order->discount_type == '%' ? 'selected' : '' }}>Percentage</option>
                    <option value="-" {{ $order->discount_type == '-' ? 'selected' : '' }}>Fixed Amount</option>
                </select>
            </div>

            <div class="form-group">
                <label for="other_charges">Order Other Charges</label>
                <input type="number" name="other_charges" id="other_charges" class="form-control" value="{{ old('other_charges', $order->other_charges) }}" required>
            </div>

            <div class="form-group">
                <label for="net_total">Net Total</label>
                <input type="number" name="net_total" id="net_total" class="form-control" value="{{ old('net_total', $order->netTotal) }}" required>
            </div>

            <div class="form-group">
                <label for="paid">Paid</label>
                <input type="number" name="paid" id="paid" class="form-control" value="{{ old('paid', $order->paid) }}" required>
            </div>

            <div class="form-group">
                <label for="sale_note">Sale Note</label>
                <textarea name="sale_note" id="sale_note" class="form-control">{{ old('sale_note', $order->sale_note) }}</textarea>
            </div>

            <div class="form-group">
                <label for="staff_note">Staff Note</label>
                <textarea name="staff_note" id="staff_note" class="form-control">{{ old('staff_note', $order->staff_note) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Order</button>
        </form>
    </div>
</div>
@endsection
