@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Create New Transfer</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <form action="{{ route('transfers.store') }}" method="POST">
            @csrf

            <!-- Form Fields Row 1 -->
            <div class="row">
                <!-- Date Field -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Date:</label>
                            <input type="text" class="form-control pull-right myInput" id="datepicker" name="created_at">
                        <!-- /.input group -->
                    </div>
                </div>

                
                <div class="col-md-4">
                    <div class="form-group">
                        <label>From Warehouse *</label>
                        <select required name="from_warehouse_id" class="selectpicker form-control myInput" data-live-search="true" data-live-search-style="begins" title="Select warehouse...">
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- To Warehouse Field -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>To Warehouse *</label>
                        <select required name="to_warehouse_id" class="selectpicker form-control myInput" data-live-search="true" data-live-search-style="begins" title="Select warehouse...">
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Product and Quantity Fields Row -->
            <div class="row mt-3">
                <!-- Product Search Field -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Select Product</label>
                        <!-- Searchable Product Dropdown -->
                        <select required name="product_id" class="form-control myInput selectpicker" data-live-search="true" title="Type to search for a product...">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Quantity Field -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control myInput" placeholder="Enter quantity" required />
                    </div>
                </div>
            </div>

            <!-- Submit Button Row -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Create Transfer</button>
                    <a href="{{ route('transfers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
