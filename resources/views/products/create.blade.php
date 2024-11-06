@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Add Product</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Product Code *</label>
                        <input type="text" name="product_code" class="form-control myInput" value="{{ old('product_code') }}" required>
                        @error('product_code')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input type="text" name="product_name" class="form-control myInput" value="{{ old('product_name') }}" required>
                        @error('product_name')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Brand</label>
                        <select name="brand_id" class="form-control myInput" data-live-search="true">
                            <option value="">Select Brand...</option>
                            <option value="1" {{ old('brand_id') == 1 ? 'selected' : '' }}>Apple</option>
                            <option value="2" {{ old('brand_id') == 2 ? 'selected' : '' }}>Samsung</option>
                            <!-- Add other brands as needed -->
                        </select>
                        @error('brand_id')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" class="form-control myInput">
                            <option value="">Select Category...</option>
                            <option value="1" {{ old('category_id') == 1 ? 'selected' : '' }}>Electronics</option>
                            <option value="2" {{ old('category_id') == 2 ? 'selected' : '' }}>Fashion</option>
                            <!-- Add other categories as needed -->
                        </select>
                        @error('category_id')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Product Cost *</label>
                        <input type="number" name="cost" class="form-control myInput" step="any" value="{{ old('cost') }}" required>
                        @error('cost')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sale Price *</label>
                        <input type="number" name="price" class="form-control myInput" step="any" value="{{ old('price') }}" required>
                        @error('price')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Alert Quantity</label>
                        <input type="number" name="alert_quantity" class="form-control myInput" step="any" value="{{ old('alert_quantity') }}">
                        @error('alert_quantity')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tax</label>
                        <select name="tax_id" class="form-control myInput">
                            <option value="">Select Tax...</option>
                            <option value="1" {{ old('tax_id') == 1 ? 'selected' : '' }}>GST</option>
                            <option value="2" {{ old('tax_id') == 2 ? 'selected' : '' }}>VAT</option>
                            <!-- Add other tax options as needed -->
                        </select>
                        @error('tax_id')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Initial Stock</label>
                        <input type="number" name="initial_stock" class="form-control myInput" step="any" value="{{ old('initial_stock') }}">
                        @error('initial_stock')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="uom">Unit of Measure</label>
                        <select name="uom" id="uom" class="form-control myInput">
                            <option value="pcs" {{ old('uom') == 'pcs' ? 'selected' : '' }}>Pieces</option>
                            <option value="kg" {{ old('uom') == 'kg' ? 'selected' : '' }}>Kilogram</option>
                            <option value="ltr" {{ old('uom') == 'ltr' ? 'selected' : '' }}>Litre</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Product Image</label>
                        <input type="file" name="product_image" class="form-control myInput">
                        @error('product_image')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Product Details</label>
                        <textarea name="product_details" class="form-control" rows="3">{{ old('product_details') }}</textarea>
                        @error('product_details')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Add Product</button>
                <button class="btn btn-success" onclick="location.href='{{ route('products.index') }}'">View Products</button>

            </div>
        </form>
    </div>
</div>
@endsection
