@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">
                 Products
            </h3>
        </div>
        <div class="text-right">
            <!-- Add Product link -->
            <a href="{{ route('products.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Product
            </a>
        </div>

        <table id="product-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Cost</th>
                    <th>Price</th>
                    <th>UOM</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr id="productRow-{{ $product->id }}">
                    <td>{{ $product->product_code }}</td>
                    <td>{{ strtoupper($product->product_name) }}</td>
                    <td>{{ $product->cost }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->uom }}</td>
                    <td>                  
                    
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>

                                <div class="custom-dropdown-menu">
                                 <!-- View Option -->
                                 <button 
                                        class="custom-dropdown-item" 
                                        type="button"
                                        data-toggle="modal" 
                                        data-target="#viewProductModal"
                                        data-id="{{ $product->id }}"
                                        data-code="{{ $product->product_code }}"
                                        data-name="{{ $product->product_name }}"
                                        data-cost="{{ $product->cost }}"
                                        data-price="{{ $product->price }}"
                                        data-uom="{{ $product->uom }}"
                                        data-details="{{ $product->product_details }}"
                                        data-initial-stock="{{ $product->initial_stock }}"
                                        data-alert-quantity="{{ $product->alert_quantity }}"
                                        data-tax-id="{{ $product->tax_id }}"
                                        data-image="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('dist/img/product-default.jpg') }}"
                                    >
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                  
                                    <!-- Edit Option -->
                                    <a href="{{ route('products.edit', $product->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>


                                    <!-- Delete Option -->
                                    <form id="deleteForm-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $product->id }})" class="delete-btn btn btn-danger">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </td>    
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>  

<!-- Modal for Viewing Product Details -->
<div class="modal fade" id="viewProductModal" tabindex="-1" role="dialog" aria-labelledby="viewProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewProductModalLabel">Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Product Image:</strong><br> 
                    <img id="product-image" src="" alt="Product Image" class="img-fluid product-image-modal" />
                </p>
                <p><strong>Code:</strong> <span id="product-code"></span></p>
                <p><strong>Name:</strong> <span id="product-name"></span></p>
                <p><strong>Cost:</strong> <span id="product-cost"></span></p>
                <p><strong>Price:</strong> <span id="product-price"></span></p>
                <p><strong>UOM:</strong> <span id="product-uom"></span></p>
                <p><strong>Details:</strong> <span id="product-details"></span></p>
                <p><strong>Initial Stock:</strong> <span id="product-initial-stock"></span></p>
                <p><strong>Alert Quantity:</strong> <span id="product-alert-quantity"></span></p>
                <p><strong>Tax ID:</strong> <span id="product-tax-id"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection







