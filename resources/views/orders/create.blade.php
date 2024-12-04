@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
            <div id="success-message" class="alert alert-success" style="display: none;"></div>
            <div id="error-message" class="alert alert-danger" style="display: none;"></div>
        
            <div class="row" >
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box compact-info-box" >
                        <span class="info-box-icon bg-grey"  style="border-radius: 5px;!important"><i class="ion ion-pricetag"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Gross Amount</span>
                            <span class="info-box-number" id ="gross_amount_label" >0.00</span>
                            <input type = 'hidden' name = 'gross_amount' id="gross_amount_id" type="number" value="0" class="form-control"  placeholder="0" readonly></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box compact-info-box" >
                        <span class="info-box-icon bg-grey"  style="border-radius: 5px;!important"><i class="fa fa-credit-card"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Net  Amount</span>
                            <span class="info-box-number" id ="net_amount_label" >0.00</span>
                            <input type = 'hidden' name="net_amount" id="net_amount_id" type="number"  class="form-control"  readonly></h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box compact-info-box">
                        <span class="info-box-icon" style="border-radius: 5px;!important" ><i class="fa fa-usd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Paid Amount</span>
                            <span class="info-box-number" id = 'paid_amount_label'>0.00</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box compact-info-box" >
                        <span class="info-box-icon bg-grey"  style="border-radius: 5px;!important"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Amount Due</span>
                            <span class="info-box-number" id ="balance_label" >0.00</span>
                            <input type = 'hidden' name="balance" id="balance_id" type="number" class="form-control" readonly></h3>
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
                                    <div class="input-group-addon" onclick="GetItems(this.value)" >
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
                           
                            <div class="col-md-2">
                                <div class="input-group" style="width: 100%;"> 
                                    
                                    <input type="text" 
                                        list="salesperson-names" 
                                        style="width: 100%;" 
                                        name="salesperson-name" 
                                        class="form-control myInput" 
                                        placeholder="select sales person" 
                                        tabindex="1" 
                                        id="salesperson-name-input">

                                    <datalist id="salesperson-names">
                                        @foreach($salespersons as $salesperson)
                                            <option value="{{ $salesperson->name }}" data-id="{{ $salesperson->id }}">{{ $salesperson->name }}</option>
                                        @endforeach
                                    </datalist>
                                  
                                    <input type="hidden" name="salesperson_id" id="salesperson-id">
                                </div>
                            </div>
                               <!-- Search Button to Get Invoice -->
                                <div class="col-md-2">
                                    <div class="input-group" style="width: 100%;">
                                      
                                        <div class="input-group-addon" onclick="getInvoiceDetails()" >
                                            <i class="fa fa-search"></i>
                                        </div>
                                        <input type="text" list="orderList" name="custom_order_id" class="form-control myInput" placeholder="Sale Id.">
                                       
                                        <div onclick="getOrderForEdit()" class="input-group-addon" data-toggle="modal" >
                                            <i class="fa fa-edit"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                                <input 
                                                    type="text" 
                                                    list="orderStatusList" 
                                                    style="width: 100%;" 
                                                    name="order_status" 
                                                    class="form-control myInput" 
                                                    placeholder="Select Status" 
                                                    tabindex="1" 
                                                    id="order_status" 
                                                    value="{{ $defaultStatus->status_name ?? 'Complete' }}"> 

                                                <datalist id="orderStatusList">
                                                    @foreach($statuses as $status)
                                                        <option value="{{ $status->status_name }}" data-id="{{ $status->id }}">
                                                            {{ $status->status_name }}
                                                        </option>
                                                    @endforeach
                                                </datalist>

                                                <input 
                                                    type="hidden" 
                                                    name="status_id" 
                                                    id="status-id" 
                                                    value="{{ $defaultStatus->id ?? $statuses->firstWhere('status_name', 'Complete')->id }}"> 
                                            
                                 </div>
                        </div>
                        <div class="row">
                                                    
                            <div class="col-md-4">
                                <div class="input-group" style="width: 100%;"> 
                                    <div class="input-group-addon" data-toggle="modal" data-target="#CreateNewProd">
                                        <i class="fa fa-barcode"></i>
                                    </div>
                                    <input type="text" list="product_name" style="width: 100%;" name="product" onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();" class="form-control myInput" placeholder="Product Code" tabindex="1" id="product-input">
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
                                <input type="number"  class="form-control myInput" name="qty" value="" placeholder="Quantity" id="qty_id" tabindex="2"  onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();">
                            </div>
                            <div class="col-xs-1">
                                <input type="number" id="price_id" class="form-control myInput" tabindex="3" name="" onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();" placeholder="Price" >
                                
                            </div>
                            <div class="col-xs-1">
                                <select id="discount_type" class="form-control myInput" tabindex="4" onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();">
                                    <option value="percentage">%</option>
                                    <option value="flat">Flat</option>
                                </select>
                            </div>
                            <div class="col-xs-1">
                                <input type="number" class="form-control myInput" id="discount_value" placeholder="Discount" tabindex="5" onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();">
                            </div>
                            <div class="col-xs-2">
                                <input type="text" list="UOMList" class="form-control myInput" id="uom_id" name="unit" tabindex="6" value="" onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();" placeholder="UOM">
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
                                    
                                        <input type="text" id="sale_note" name="sale_note" tabindex="7"  placeholder="Sale Note / Remarks"  class="form-control myInput">
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="input-group" style="width: 100%;"> 
                                             <div class="input-group-addon" data-toggle="modal" >
                                                <i class="fa fa-tags"></i> 
                                            </div>  
                                    
                                        <input type="text" id="staff_note" name="sale_note" tabindex="8"  placeholder="Staff Note / Remarks"  class="form-control myInput">
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
                                <button id="updateOrder" style = 'display:none' class="btn btn-warning" type="button">
                                        updateOrder
                                    <i id="loader" style = 'display:none' class="fa fa-refresh fa-spin"></i>
                                </button>
                                <button type="button" style = 'display:none' id="cancelOrder" class="btn btn-secondary">Cancel</button>

                                
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
    
                        <!-- Financials Section (Gross Amount, Discount, etc.) -->
                        <div class="table-container" >
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box custom">
                                        <span class="info-box-icon" style="border-radius: 5px;!important"><i class="fa fa-percent"></i></span>
                                        <div class="info-box-content">
                                            <select name="tax_rate" id="tax_rate" class="form-control">
                                                <option value="" selected>Order Tax</option>
                                                    @foreach($taxes as $tax)
                                                        <option value="{{ $tax->rate }}">{{ $tax->name }} ({{ $tax->rate }}%)</option>
                                                    @endforeach
                                            </select>
                                            <span class="info-box-number"> TAX(%)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box custom">
                                        <span class="info-box-icon" style="border-radius: 5px;!important"><i class="fa fa-tag"></i></span>
                                        <div class="info-box-content">
                                            <input type="number"  name = 'order_discount' id="order_discount_id" value="" class="form-control" tabindex="9" placeholder="Order Discount ">
                                            <span class="info-box-number"> 
                                            <label >
                                                <input type="radio" name="order_discount_type" id = "flat_discount_radio" class="flat-red" value='flat' checked> Flat 
                                            </label> &nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" name="order_discount_type" id="percentage_discount_radio" class="flat-red" value="percentage"> Percentage 
                                            </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box custom" >
                                        <span class="info-box-icon" style="border-radius: 5px;!important"><i class="fa fa-money"></i></span>
                                        <div class="info-box-content">
                                            <input type = 'number' name="other_charges" value="" id = 'other_charges_id' class="form-control" tabindex="9" >
                                            <span class="info-box-number"> OTHER CHARGES</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box custom">
                                        <span class="info-box-icon" style="border-radius: 5px;!important"><i class="fa fa-dollar"></i></span>
                                        <div class="info-box-content">
                                            <input name="paid_amount" id="paid_amount_id" type="number" class="form-control" tabindex="10" >
                                            <span class="info-box-number"> PAID AMOUNT</span>
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
 
<!-- Modal for Invoice View -->
<div id="invoiceModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Invoice Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="invoiceContent">
               
                <!-- Main content -->
                <section class="invoice">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <div class="logo-img-invoice">
                                  <img src="{{ asset('dist/img/logo.png') }}" alt="Inventra Logo">
                                  <small class="date-inv">Date: <span id="invoiceDate">N/A</span></small>
                                  </div>
                               
                            </h2>
                        </div>
                    </div>

                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            To
                            <address>
                                <strong id="invoiceToName">N/A</strong><br>
                                <span id="invoiceToAddress">N/A</span><br>
                                Phone: <span id="invoiceToPhone">N/A</span><br>
                                Email: <span id="invoiceToEmail">N/A</span>
                            </address>
                        </div>

                        <div class="col-sm-4 invoice-col">
                            
                            <br>
                            <b>Order ID:</b> <span id="orderId">N/A</span><br>
                            <b>Payment Due:</b> <span id="AmountDueTop">N/A</span><br>
                            <b>Account:</b> <span id="accountNumber">N/A</span>
                        </div>
                    </div>

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Rate</th>
                                        <th>Discount</th>
                                        <th>Net Rate</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="invoiceItems">
                                    <!-- Items will be dynamically injected here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-xs-6">
                            <p class="lead">Note:</p>
                                 <p class="text-muted well well-sm no-shadow" id = "saleNote" style="margin-top: 10px;">
                                    
                                </p>
                        </div>

                        <div class="col-xs-6">
                            <p class="lead">Amount Due : <span id="AmountDue">N/A</span></p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th style="width:50%">Subtotal:</th>
                                        <td id="subtotalAmount">$0.00</td>
                                    </tr>
                                    <tr>
                                        <th>Tax(%)</th>
                                        <td id="taxRate">0.00</td>
                                    </tr>
                                    <tr>
                                        <th>Discount</th>
                                        <td id="discountAmount">0.00</td>
                                    </tr>
                                    <tr>
                                        <th>Other Charges:</th>
                                        <td id="otherCharges">0.00</td>
                                    </tr>
                                    <tr>
                                        <th>Paid:</th>
                                        <td id="paidAmount">0.00</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </section>
                
            </div>
             <!-- this row will not appear when printing -->
      
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="printInvoice()"><i class="fa fa print"></i> Print</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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
                    @isset($order)
                        <input type="text" name="_method" value="PUT">
                        <input type="text" id="orderId" value="{{ $order->id }}">
                    @endisset
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


@endsection
