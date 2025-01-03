@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="row">
            @php
                $totalProducts = $totalProducts;  // Total number of products
            @endphp

            <!-- Total Product Records Box -->
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
            <h3 class="box-title custom-title">Stock Report</h3>
        </div>

        <div class="text-right">
            <a href="{{ route('products.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Product
            </a>
        </div>

        <form >
            <div class="row">
                <!-- Warehouse Dropdown -->
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
                        <button type="submit" class="btn btn-primary" style="margin-top: 25px;">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                    </div>

            </div>
        </form>

        <table id="product-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Current Stock</th>
                    <th>Unit of Measure (UOM)</th>
                    <th>History</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr id="productRow-{{ $product->id }}">
                    <td>
                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('dist/img/no-product-img.png') }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                    </td>
                    <td>{{ strtoupper($product->product_name) }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>{{ $product->current_stock }}</td> <!-- Displaying current stock -->
                    <td>
                        @if($product->uom) 
                            {{ $product->uom->name }} 
                        @else
                            N/A
                        @endif
                    </td> 
                    <td>
                        <a href="{{ route('products.stockHistory', $product->id) }}" class="btn btn-success">
                            <i class="fa fa-history"></i> Stock History
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
