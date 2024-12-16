@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="row">
            @php
                $totalProducts = $productQuantities->count() ?? 0;
            @endphp

            <div class="col-lg-12 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalProducts }}</h3>
                        <p>Product Records</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-cart"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-header with-border">
            <h3 class="box-title custom-title">Warehouse Report</h3>
        </div>

        <form method="GET">
            <div class="row">
                <div class="col-md-4">
                    <label for="warehouse">Select Warehouse</label>
                    <select name="warehouse_id" id="warehouse" class="form-control">
                        <option value="">-- Select Warehouse --</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Filter</button>
                </div>
            </div>
        </form>

        <div class="row mt-4">
            <div class="col-md-12">
                <h4>Warehouse Report</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                    <thead>
        <tr>
            <th>Product Name</th>
            <th>Purchased Quantity</th>
            <th>Sold Quantity</th>
            <th>Transfer In</th>
            <th>Transfer Out</th>
            <th>Remaining Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach($productQuantities as $product)
            <tr>
                <td>{{ $product['product_name'] }}</td>
                <td>{{ $product['purchased_quantity'] }}</td>
                <td>{{ $product['sold_quantity'] }}</td>
                <td>{{ $product['transfer_in_quantity'] }}</td>
                <td>{{ $product['transfer_out_quantity'] }}</td>
                <td>{{ $product['remaining_quantity'] }}</td>
            </tr>
        @endforeach
    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection