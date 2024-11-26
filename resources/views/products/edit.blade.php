@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">{{ isset($product) ? 'Edit Product' : 'Add Product' }}</h3>
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

        <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($product)) 
                @method('PUT') 
            @endif

            <div class="box-body">
                <!-- Product Code -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Product Code *</label>
                        <input type="text" name="product_code" class="form-control myInput" value="{{ old('product_code', $product->product_code ?? '') }}" required>
                        @error('product_code')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Product Name -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input type="text" name="product_name" class="form-control myInput" value="{{ old('product_name', $product->product_name ?? '') }}" required>
                        @error('product_name')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Brand -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Brand</label>
                        <select name="brand_id" class="form-control myInput" data-live-search="true">
                            <option value="">Select Brand...</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Category -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" class="form-control myInput">
                            <option value="">Select Category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Cost -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Product Cost *</label>
                        <input type="number" name="cost" class="form-control myInput" step="any" value="{{ old('cost', $product->cost ?? '') }}" required>
                        @error('cost')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Sale Price -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sale Price *</label>
                        <input type="number" name="price" class="form-control myInput" step="any" value="{{ old('price', $product->price ?? '') }}" required>
                        @error('price')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Alert Quantity -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Alert Quantity</label>
                        <input type="number" name="alert_quantity" class="form-control myInput" step="any" value="{{ old('alert_quantity', $product->alert_quantity ?? '') }}">
                        @error('alert_quantity')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Tax -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tax</label>
                        <select name="tax_id" class="form-control myInput">
                            <option value="">Select Tax...</option>
                            @foreach($taxes as $tax)
                                <option value="{{ $tax->id }}" {{ old('tax_id', $product->tax_id ?? '') == $tax->id ? 'selected' : '' }}>{{ $tax->name }}</option>
                            @endforeach
                        </select>
                        @error('tax_id')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Initial Stock -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Initial Stock</label>
                        <input type="number" name="initial_stock" class="form-control myInput" step="any" value="{{ old('initial_stock', $product->initial_stock ?? '') }}">
                        @error('initial_stock')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- UOM -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="uom">Unit of Measurement</label>
                        <select name="uom" id="uom" class="form-control">
                            <option value="">Select UOM</option>
                            @foreach($uoms as $uom)
                                <option value="{{ $uom->id }}" {{ old('uom', $product->uom_id ?? '') == $uom->id ? 'selected' : '' }}>{{ $uom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Product Image -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Product Image</label>
                        <input type="file" name="product_image" class="form-control myInput">
                        @if(isset($product) && $product->product_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $product->product_image) }}" alt="Product Image" class="img-fluid" width="100">
                            </div>
                        @endif
                        @error('product_image')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Product Details</label>
                        <textarea name="product_details" class="form-control" rows="3">{{ old('product_details', $product->product_details ?? '') }}</textarea>
                        @error('product_details')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Update Product' : 'Add Product' }}</button>
                <a href="{{ route('products.index') }}" class="btn btn-success">View Products</a>
            </div>
        </form>
    </div>
</div>
@endsection
