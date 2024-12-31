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
                        <input type="text" name="product_code" class="form-control myInput" value="{{ old('product_code') }}" >
                        @error('product_code')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input type="text" name="product_name" class="form-control myInput" value="{{ old('product_name') }}" >
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
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
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
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Product Cost *</label>
                        <input type="number" name="cost" class="form-control myInput" step="any" value="{{ old('cost') }}" >
                        @error('cost')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sale Price *</label>
                        <input type="number" name="price" class="form-control myInput" step="any" value="{{ old('price') }}" >
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
                            @foreach($taxes as $tax)
                                <option value="{{ $tax->id }}" {{ old('tax_id') == $tax->id ? 'selected' : '' }}>{{ $tax->name }}</option>
                            @endforeach
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
                        <label for="uom">Unit of Measurement</label>
                        <select name="uom" id="uom" class="form-control">
                            <option value="">Select UOM</option>
                            @foreach($uoms as $uom)
                                <option value="{{ $uom->id }}">{{ $uom->name }}</option>
                            @endforeach
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
                <div class="col-md-3">
                        <label class="mr-2">Import Bulk Product</label>
                        <input type="file" name="import_csv" class="form-control myInput" accept=".csv">
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                       <label class="mr-2">Sample CSV</label>
                        <button type="button" class="btn btn-info ml-2 custom-grey" data-toggle="modal" data-target="#importCsvModal">
                            <i class="fa fa-info-circle"></i>
                        </button>
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

<div class="modal fade" id="importCsvModal" tabindex="-1" role="dialog" aria-labelledby="importCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importCsvModalLabel">Import CSV File Guidelines</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="form-text text-muted">
                    Please ensure your CSV file meets the following requirements for successful import:
                </p>
                <ul style="padding-left: 20px; margin-bottom: 15px;">
                    <li>Fields marked with <strong>*</strong> are mandatory.</li>
                    <li>The correct column order is:
                        <ul style="padding-left: 20px;">
                            <li><strong>Image</strong> - Optional. It must be stored in the <code>products</code> directory with the same name as mentioned here.</li>
                            <li><strong>name*</strong></li>
                            <li><strong>code*</strong></li>
                            <li><strong>Uom*</strong> (e.g., piece or kg)</li>
                            <li><strong>cost*</strong></li>
                            <li><strong>price*</strong></li>
                            <li><strong>brand</strong></li>
                            <li><strong>category</strong></li>
                            <li><strong>alert qty</strong></li>
                            <li><strong>product tax</strong></li>
                            <li><strong>initial stock</strong></li>
                            <li><strong>product details</strong></li>
                            
                        </ul>
                    </li>
                    <li>Ensure all required fields are correctly filled before upload.</li>
                </ul>
                <a href="{{ route('download-sample-product-csv') }}" class="btn btn-success mt-3">
                    <i class="fa fa-download"></i> Download Sample CSV File
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-grey {
    background-color: #d3d3d3; 
    color: #000; 
    border: 1px solid #ccc; 
}
</style>
@endsection
