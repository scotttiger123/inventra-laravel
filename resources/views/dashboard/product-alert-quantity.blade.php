@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Product Alert Section -->
        <div class="row">
            <!-- Low Stock Alert Box -->
            <div class="col-lg-6 col-xs-12">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="lowStockCount">0</h3>
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
                        <h3 id="totalStockAlert">0</h3>
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
                    <th>Current Stock (UOM)</th>
                    <th>Alert Quantity</th>
                </tr>
            </thead>
            <tbody id="productList">
                
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
            'order': [[0, 'desc']] // Sorts by first column (Payment Date) in descending order
        });
});

    $(document).ready(function() {
        
        $.ajax({
            url: '/product-quantity-alerts-json', 
            type: 'GET',
            success: function(response) {
                // Update low stock count and total stock
                $('#lowStockCount').text(response.lowStockCount);
                $('#totalStockAlert').text(response.lowStockCount); // You can modify to calculate total stock from the response

                // Populate product list table
                var productRows = '';
                response.products.forEach(function(product) {
                    productRows += `
                        <tr>
                            <td>${product.product_name}</td>
                            <td>${product.category ? product.category.name : 'N/A'}</td>
                            <td class="badge-badge-danger">${product.current_stock} (${product.uom ? product.uom.name : 'N/A'})</td>
                            <td>${product.alert_quantity}</td>
                        </tr>
                    `;
                });

                // Insert product rows into the table
                $('#productList').html(productRows);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching low stock products:', error);
            }
        });
    });
</script>
@endsection
