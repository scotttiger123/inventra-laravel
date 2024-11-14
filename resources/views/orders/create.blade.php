@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- <div class="box-header with-border">
            <h3 class="box-title custom-title">Create Order</h3>
            
        </div> -->
            <div id="success-message" class="alert alert-success" style="display: none;"></div>
            <div id="error-message" class="alert alert-danger" style="display: none;"></div>
        
            <div class="row" >
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box compact-info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-credit-card"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Balance</span>
                            <span class="info-box-number">$1,410</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box compact-info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-cogs"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Stock</span>
                            <span class="info-box-number">410</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box compact-info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-usd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Last Price</span>
                            <span class="info-box-number">$13,648</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box compact-info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-percent"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Discount</span>
                            <span class="info-box-number">10%</span>
                        </div>
                    </div>
                </div>
            </div>

        <form id="orderForm" action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="box-body">
                                     
                <div class="row" >
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="input-group" style="width: 100%;"> 
                                    <div class="input-group-addon" onclick="GetItems(this.value)" data-toggle="modal" data-target="#">
                                    <i class="fa fa-calendar"></i>

                                    </div>
                                    <input type="datetime-local" name ='order_date' id="datetimepicker_dark1" class="form-control myInput" style="width: 100%;" tabindex="1">
                                </div>
                            </div>

                            <div class="col-md-2">
                                        <div class="input-group" style="width: 100%;"> 
                                            <div class="input-group-addon" data-toggle="modal" data-target="#CreateNewCustomerModal">
                                                <i class="fa fa-plus"></i>
                                            </div>
                                            <!-- Input for selecting customer name -->
                                            <input type="text" 
                                                list="customer-names" 
                                                style="width: 100%;" 
                                                name="customer-name" 
                                                class="form-control myInput" 
                                                placeholder="Select Customer Name" 
                                                tabindex="1" 
                                                id="customer-name-input">

                                            <datalist id="customer-names">
                                                
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->name }}" data-id="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </datalist>

                                            <!-- Hidden field for storing customer ID -->
                                            <input type="hidden" name="customer_id" id="customer-id">
                                        </div>
                            </div>

                            <!-- Salesperson Selection -->
                            <div class="col-md-2">
                                <div class="input-group" style="width: 100%;"> 
                                    <!-- Input for selecting salesperson name -->
                                    <input type="text" 
                                        list="salesperson-names" 
                                        style="width: 100%;" 
                                        name="salesperson-name" 
                                        class="form-control myInput" 
                                        placeholder="Select Salesperson Name" 
                                        tabindex="1" 
                                        id="salesperson-name-input">

                                    <datalist id="salesperson-names">
                                        @foreach($salespersons as $salesperson)
                                            <option value="{{ $salesperson->name }}" data-id="{{ $salesperson->id }}">{{ $salesperson->name }}</option>
                                        @endforeach
                                    </datalist>

                                    <!-- Hidden field for storing salesperson ID -->
                                    <input type="hidden" name="salesperson_id" id="salesperson-id">
                                </div>
                            </div>

                    

                            <div class="col-md-2">
                                <div class="input-group" style="width: 100%;">
                                    <div class="input-group-addon" onclick="GetItems(this.value)" data-toggle="modal" data-target="#">
                                        <i class="fa fa-search"></i>
                                    </div>
                                    <input type="text" list="orderList" name="custom_order_id" class="form-control myInput" placeholder="Sale Id." >
                                        
                                    <div onclick="edit_invoice()" class="input-group-addon" data-toggle="modal" data-target="#">
                                        <i class="fa fa-edit"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="text" list="orderStatusList" name="order_status" id="order_status" class="form-control myInput" placeholder="Order Status" >
                                <datalist id="orderStatusList">
                                    <option value=""></option>
                                    <option value="Return">Return</option>
                                    <option value="Hold">Hold</option>
                                    <option value="Pending">Pending</option>
                                </datalist>
                            </div>
                            
                            
                        </div>
                        <div class="row">
                                                    
                            <!-- <div class="col-md-2">
                                <div class="input-group" style="width: 100%;"> 
                                    <div class="input-group-addon" data-toggle="modal" >
                                        <i class="fa fa-plus"></i>
                                    </div>
                                    <input type="text" list="product_name" style="width: 100%;" name="product" class="form-control myInput" placeholder="Product Code/Name" tabindex="1" id="Product">
                                    <datalist id="product_name">
                                        @foreach($products as $product)
                                            <option value="{{ $product->product_name }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div> -->
                            <div class="col-md-2">
                                <div class="input-group" style="width: 100%;"> 
                                    <div class="input-group-addon" data-toggle="modal" data-target="#CreateNewProd">
                                        <i class="fa fa-plus"></i>
                                    </div>
                                    <input type="text" list="product_name" style="width: 100%;" name="product" class="form-control myInput" placeholder="Product Code" tabindex="1" id="product-input">
                                    <datalist id="product_name">
                                        @foreach($products as $product)
                                        <option value="{{ $product->product_code }}" data-id="{{ $product->id }}">{{ $product->product_name }}</option>

                                        @endforeach
                                    </datalist>
                                </div>
                            </div>

                            <!-- Hidden input for product ID -->
                            <input type="hidden" id="product-id" name="product_id">

                            <div class="col-sm-1" >
                                <input type="text"  class="form-control myInput" name="qty" value="" placeholder="Quantity" id="qty_id" tabindex="2"  onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();">
                            </div>
                            <div class="col-xs-1">
                                <input type="text" id="price_id" class="form-control myInput" name="" onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();" placeholder="Price" >
                            </div>
                            <div class="col-xs-1">
                                <select id="discount_type" class="form-control myInput" tabindex="2" onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();">
                                    <option value="percentage">%</option>
                                    <option value="flat">Flat</option>
                                </select>
                            </div>
                            <div class="col-xs-1">
                                <input type="text" class="form-control myInput" id="discount_value" placeholder="Discount" tabindex="2" onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();">
                            </div>
                            <div class="col-xs-2">
                                <input type="text" list="UOMList" class="form-control myInput" id="uom_id" name="unit" tabindex="3" value="" onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();" placeholder="UOM">
                                <datalist id="UOMList">
                                    @foreach($uoms as $uom)
                                        <option value="{{ $uom->abbreviation }}" data-id="{{ $uom->id }}">{{ $uom->name }} ({{ $uom->abbreviation }})</option>
                                    @endforeach
                                </datalist>
                            </div>

                        </div>
                            <div class="row">
                            
                                <div class="col-xs-4">
                                    <div class="input-group" style="width: 100%;"> 
                                             <div class="input-group-addon" data-toggle="modal" >
                                                <i class="fa fa-tags"></i> 
                                            </div>  
                                    
                                        <input type="text" id="sale_note" name="sale_note" tabindex="4"  placeholder="Sale Note / Remarks"  class="form-control myInput">
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="input-group" style="width: 100%;"> 
                                             <div class="input-group-addon" data-toggle="modal" >
                                                <i class="fa fa-tags"></i> 
                                            </div>  
                                    
                                        <input type="text" id="staff_note" name="sale_note" tabindex="4"  placeholder="Staff Note / Remarks"  class="form-control myInput">
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                        <div class="form-group">
                                            <input type="checkbox" id="exit_warehouse" name="exit_warehouse" value="1" class="flat-red" checked>
                                            <label for="exit_warehouse">Exit Warehouse</label>
                                            
                                            <span>Check this if the product is exiting from the warehouse.</span>
                                        </div>
                                </div>        
                                
                                    <!-- Add Product Button -->
                                <button type="button" class="btn btn-info" onclick="addItemToOrder()">Add Product</button>
                                <!-- <button id="submitOrder" class="btn btn-success" type="button">Submit Order</button> -->
                                <button id="submitOrder" class="btn btn-success" type="button">
                                    Submit Order
                                    <i id="loader" style = 'display:none' class="fa fa-refresh fa-spin"></i>
                                </button>
                                <!-- Success and Error Message Containers -->
                            </div>
                            
                            <meta name="csrf-token" content="{{ csrf_token() }}">
    
                    </div>
                    
                      <!-- Order Items Table Section -->
                      <div class="col-xs-12">
                            
                            <div class="scrollable-table-container" style="height: 400px; overflow-y: auto;">
                                <table class="table table-bordered table-striped" id="orderItemsTable" style = "margin-top:10px;background-color :#f4f4f4">
                                    <thead>
                                        <tr>
                                            <th>Product Code/Name</th>
                                            <th style = "width:150px">Quantity</th>
                                            <th>Uom</th>
                                            <th>Rate</th>
                                            <th>Discount (%)</th>
                                            <th>Net Rate</th>
                                            <th>Amount</th>
                                            <th>Exit Warehouse</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End Order Item -->

                        <!-- Financials Section (Gross Amount, Discount, etc.) -->
                        <div class="table-container" >
                            <div class="row" >
                                <div class="col-xs-2">
                                    <div class="small-box" >
                                        <div class="inner">
                                            <h3><input id="gross_amount_id" type="text" value="0" class="form-control" placeholder="0"></h3>
                                            <p>GROSS AMOUNT</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <div class="small-box">
                                        <div class="inner" >
                                            <h3><input type="text" id="order_discount_id" value="" class="form-control" placeholder="Order Discount "></h3>
                                            <label >
                                                <input type="radio" name="order_discount_type" class="flat-red" checked> Flat 
                                            </label> &nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" name="order_discount_type" id="percentage_discount" class="flat-red" value="percentage"> Percentage 
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-2">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3><input type = 'number'name="" value="" id = 'other_charges_id' class="form-control"></h3>
                                            <p>OTHER CHARGES</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-2">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3><input id="net_amount_id" type="number" value="" class="form-control" placeholder="0"></h3>
                                            <p>NET AMOUNT</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-2">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3><input name="paid_amount" id="paid_amount_id" type="number" class="form-control"></h3>
                                            <p>PAID AMT</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-2">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3><input name="" id="balance_id" type="number" class="form-control"></h3>
                                            <p>BALANCE</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Models  -->
<!-- Create customer modal  -->
<div class="modal fade" id="CreateNewCustomerModal" tabindex="-1" role="dialog" aria-labelledby="CreateNewCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CreateNewCustomerModalLabel">Create New Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="create-customer-form" method="POST" action="{{ route('customer.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="new-customer-name">Customer Name</label>
                        <input type="text" class="form-control" id="new-customer-name" name="name" placeholder="Enter customer name" required>
                    </div>
                    <div class="form-group">
                        <label for="new-customer-email">Customer Email</label>
                        <input type="email" class="form-control" id="new-customer-email" name="email" placeholder="Enter customer email" required>
                    </div>
                    <div class="form-group">
                        <label for="new-customer-phone">Phone Number</label>
                        <input type="text" class="form-control" id="new-customer-phone" name="phone" placeholder="Enter phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="new-customer-address">Address</label>
                        <input type="text" class="form-control" id="new-customer-address" name="address" placeholder="Enter address" required>
                    </div>
                    <div class="form-group">
                        <label for="new-customer-city">City</label>
                        <input type="text" class="form-control" id="new-customer-city" name="city" placeholder="Enter city" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitCustomer">Create Customer</button>
                </form>
                <div id="success-message-customer-save" style="display:none;"></div>
                <div id="error-message-customer-save" style="display:none;"></div>
            </div>
        </div>
    </div>
</div>

<style>
   .small-box {
    background-color: #000;
    color: #fff;
    border-radius: 5px; /* Adjust value as needed for desired roundness */
    
}
</style>
@endsection
