@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Product Alert Section -->
        <div class="row">
            @php
                // Count products with stock below alert threshold
                $lowStockCount = $products->count();
                // Calculate total stock of alert products
                $totalQuantity = $products->sum('current_stock');
            @endphp

            <!-- Low Stock Alert Box -->
            <div class="col-lg-6 col-xs-12">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $lowStockCount }}</h3>
                        <p>Products Below Alert Quantity</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-alert-circled"></i>
                    </div>
                </div>
            </div>

            <!-- Total Stock Alert Box -->
            <div class="col-lg-6 col-xs-6">
                <div class="small-box" style="background-color:#B13C2E">
                    <div class="inner">
                        <h3>{{ number_format($totalQuantity, 2) }}</h3>
                        <p>Total Stock for Alert Products</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Products Below Alert Quantity</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <table id="product-alert-list" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Current Stock (UOM)</th> <!-- Updated Column Name -->
                    <th>Alert Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr @if($product->current_stock <= $product->alert_quantity)  @endif>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td class="badge-badge-danger">
                            {{ $product->current_stock }} ({{ $product->uom->name ?? 'N/A' }}) 
                        </td>
                        <td>{{ $product->alert_quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script>
    $(function () {
        $('#product-alert-list').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            'order': [[0, 'asc']] // Sort by product name in ascending order
        });
    });
</script>
@endsection
