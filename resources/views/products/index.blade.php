@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <div class="form-border">
    
        <div class="row">
            @php
                $totalProducts = $totalProducts;  // Total product records
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
                    <th>Brand</th>
                    <th>Category</th>
                    <th>stock alert</th>
                    <th>Uom</th>
                    <th>Tax(%)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr id="productRow-{{ $product->id }}">
                    <td>{{ $product->product_code }}</td>
                    <td>{{ strtoupper($product->product_name) }}</td>
                    <td>{{ $product->cost }}</td>
                    <td>{{ $product->price }} </td>
                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>
                        @if($product->alert_quantity)
                            <span class="badge badge-badge-danger">{{ $product->alert_quantity }}</span>
                        @else
                            N/A
                        @endif
                    </td>

                    <td>{{ $product->uom ? $product->uom->name : 'N/A' }}</td> 
                    <!-- <td>{{ $product->tax ? $product->tax->rate . '%' : 'N/A' }}</td> -->

     
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
                                        data-uom="{{ json_decode($product->uom)->name }}"  
                                        data-details="{{ $product->product_details ?: 'No details available' }}"      
                                        data-initial-stock="{{ $product->initial_stock }}"
                                        data-alert-quantity="{{ $product->alert_quantity }}"
                                        
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
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection







