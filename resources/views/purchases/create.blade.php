@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div id="success-message" class="alert alert-success" style="display: none;"></div>
        <div id="error-message" class="alert alert-danger" style="display: none;"></div>
        <form id="purchaseForm" action="{{ route('purchases.store') }}" method="POST">
            @csrf
            <div class="box-body">
                <div class="row" >
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box compact-info-box">
                            <span class="info-box-icon" style="border-radius: 5px;!important" ><i class="ion ion-pricetag"></i></span>
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
                            <span class="info-box-number" id ="paid_amount_label" >0.00</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box compact-info-box">
                        <span class="info-box-icon" style="border-radius: 5px;!important" ><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Remaining Amount</span>
                            <span class="info-box-number" id ="balance_label" >0.00</span>
                            <input type = 'hidden' name="balance" id="balance_id" type="number" class="form-control" readonly></h3>
                        </div>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <!-- Purchase Date -->
                            <div class="col-md-2">
                                <div class="input-group" style="width: 100%;">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="datetime-local" name="purchase_date" id = "purchase-date-input" class="form-control myInput" style="width: 100%;" tabindex="1">
                                </div>
                            </div>
                            <!-- Vendor Selection -->
                            <div class="col-md-2">
                                <div class="input-group" style="width: 100%;"> 
                                    <input type="text" list="vendor-names" style="width: 100%;" name="vendor_name" class="form-control myInput" placeholder="Select Vendor" tabindex="1" id="vendor-name-input">
                                    <datalist id="vendor-names">
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->name }}" data-id="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endforeach
                                    </datalist>
                                    <input type="hidden" name="vendor_id" id="vendor-id">
                                </div>
                            </div>

                            
                                    <div class="col-md-2">
                                        <div class="input-group" style="width: 100%;">
                                            
                                            <div class="input-group-addon" onclick="getPurchaseInvoiceDetails()" >
                                                <i class="fa fa-search"></i>
                                            </div>
                                            <input type="text" list="purchaseOrderList" name="custom_purchase_order_id" class="form-control myInput" placeholder="Purchase Id.">

                                            
                                            <div onclick="getPurchaseForEdit()" class="input-group-addon" data-toggle="modal">
                                                <i class="fa fa-edit"></i>
                                            </div>
                                        </div>

                                        
                                    </div>
                                        <div class="col-md-2">       
                                            <input type="text" list="warehouse-names" style="width: 100%;" name="warehouse_name" class="form-control myInput" placeholder="Select Warehouse" tabindex="1" id="warehouse-name-input">
                                                <datalist id="warehouse-names">
                                                    @foreach($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->name }}" data-id="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </datalist>
                                            <input type="hidden" name="warehouse_id" id="warehouse-id">
                                        </div>    

                                        <div class="col-md-2">
                                                <input 
                                                    type="text" 
                                                    list="orderStatusList" 
                                                    style="width: 100%;" 
                                                    name="purchase_status" 
                                                    class="form-control myInput" 
                                                    placeholder="Select Status" 
                                                    tabindex="1" 
                                                    id="purchase-status-input" 
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

                        <!-- Product Selection -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group" style="width: 100%;"> 
                                    <div class="input-group-addon" data-toggle="modal" data-target="#CreateNewProductModal">
                                        <i class="fa fa-barcode"></i>
                                    </div>
                                    <input type="text" list="product_name" style="width: 100%;" name="product" onKeydown="Javascript: if (event.keyCode==13) addItemToPurchase();" class="form-control myInput" placeholder="Product Code" tabindex="1" id="product-input">
                                    <datalist id="product_name">
                                        @foreach($products as $product)
                                            <option value="{{ $product->product_code }}" data-id="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>

                            <!-- Hidden Product ID -->
                            <input type="hidden" id="product-id" name="product_id">

                            <!-- Quantity -->
                            <div class="col-sm-1">
                                <input type="number" class="form-control myInput" name="qty" value="" placeholder="Quantity" id="qty_id" tabindex="2" onKeydown="Javascript: if (event.keyCode==13) addItemToPurchase();">
                            </div>
                            
                            <!-- Unit Price -->
                            <div class="col-xs-1">
                                <input type="number" id="price_id" class="form-control myInput" tabindex="3" name="price" placeholder="Unit Price" onKeydown="Javascript: if (event.keyCode==13) addItemToPurchase();">
                            </div>

                            <!-- Discount -->
                            <div class="col-xs-1">
                                <input type="number" class="form-control myInput" id="discount_value" placeholder="Discount" tabindex="4" onKeydown="Javascript: if (event.keyCode==13) addItemToPurchase();">
                            </div>
                            <div class="col-xs-1" >
                                <select id="discount_type" class="form-control myInput" tabindex="5" onKeydown="Javascript: if (event.keyCode==13) addItemToOrder();">
                                    <option value="percentage">%</option>
                                    <option value="flat">Flat</option>
                                </select>
                            </div>

                            <!-- UOM (Unit of Measure) -->
                            <div class="col-xs-1">
                                <input type="text" list="UOMList" class="form-control myInput" id="uom-name-input" name="unit" value="" tabindex="6" placeholder="UOM" onkeydown="Javascript: if (event.keyCode==13) addItemToPurchase();">
                                <datalist id="UOMList">
                                    @foreach($uoms as $uom)
                                        <option value="{{ $uom->abbreviation }}" data-id="{{ $uom->id }}">{{ $uom->name }} ({{ $uom->abbreviation }})</option>
                                    @endforeach
                                </datalist>
                                <input type="hidden" name="uom_id" id="uom-id">
                            </div>
                            <div class="col-sm-1">
                                <button type="button" class="btn btn-info" onclick="addItemToPurchase()">Add Product</button>
                            
                            </div>    
                            <div class="col-sm-1" id = 'divSubmitPurchase'>
                                <button id="submitPurchase" class="btn btn-success" type="button">Submit Purchase Order</button>
                            </div> 
                            <div class="col-xs-1">
                                <button id="updatePurchaseOrder" style = 'display:none' class="btn btn-warning" type="button">
                                        updateOrder
                                    <i id="loader" style = 'display:none' class="fa fa-refresh fa-spin"></i>
                                </button>
                            </div> 
                            <div class="col-sm-1">
                                <button type="button" style = 'display:none' id="cancelPurchaseOrder" class="btn btn-secondary">Cancel</button>  
                            </div> 
                        </div>

                        <!-- Notes and Remarks -->
                        <div class="row">
                                    
                            <div class="col-xs-4">
                                <div class="input-group" style="width: 100%;"> 
                                    <div class="input-group-addon" data-toggle="modal" >
                                        <i class="fa fa-tags"></i> 
                                    </div>
                                    <input type="text" id="purchase_note" name="purchase_note" tabindex="7" placeholder="Purchase Notes / Remarks" class="form-control myInput">
                                </div>    
                            </div>
                            
                        </div>

                        <meta name="csrf-token" content="{{ csrf_token() }}">
                    </div>

                    <!-- Purchase Items Table -->
                    <div class="col-xs-12">
                        <div class="scrollable-table-container" style="height: 400px; overflow-y: auto;">
                            <table class="table table-bordered table-striped" id="purchaseItemsTable" style="margin-top: 10px; background-color: #f4f4f4">
                                <thead>
                                    <tr>
                                        <th>Product Code/Name</th>
                                        <th style="width:150px">Quantity</th>
                                        <th>Uom</th>
                                        <th>Rate</th>
                                        <th>Discount (%)</th>
                                        <th>Net Rate</th>
                                        <th>Amount</th>
                                        <th>Warehouse</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="purchase-items-table-body">
                                    <!-- Dynamic rows will be added here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                        <!-- Financials Section (Gross Amount, Discount, etc.) -->
                        <div class="table-container" >
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="small-box" >
                                        <div class="inner">
                                            <h3>
                                             <select name="tax_rate" id="tax_rate" class="form-control">
                                                <option value="" selected>Order Tax</option>
                                                        @foreach($taxes as $tax)
                                                            <option value="{{ $tax->rate }}">{{ $tax->name }} ({{ $tax->rate }})</option>
                                                        @endforeach
                                                </select>
                                            </h3>   
                                            <p>TAX(%)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12" >
                                    <div class="small-box" style = 'height:96px'>
                                        <div class="inner" >
                                            <h3><input type="number"  name = 'order_discount' id="order_discount_id" value="0" class="form-control" tabindex="8" placeholder="Order Discount "></h3>
                                            <label >
                                                <input type="radio" name="order_discount_type" id = "flat_discount_radio" class="flat-red" value='flat' checked> Flat 
                                            </label> &nbsp;&nbsp;&nbsp;&nbsp;
                                            <label>
                                                <input type="radio" name="order_discount_type" id="percentage_discount_radio" class="flat-red" value="percentage"> Percentage 
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3><input type = 'number' value ="0" name="other_charges" value="" id = 'other_charges_id' class="form-control" tabindex="9" ></h3>
                                            <p>OTHER CHARGES</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="small-box">
                                        <div class="inner">
                                            <h3><input name="paid_amount" id="paid_amount_id" type="number" value = "0" class="form-control" tabindex="10" ></h3>
                                            <p>PAID AMT</p>
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

<!-- Modal for Invoice View -->
<div id="purchaseInvoiceModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Invoice Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body  canva-section" id="invoiceContent">
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

                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            To
                            <address>
                                <strong id="supplierName">N/A</strong><br>
                                <span id="supplierAddress">N/A</span><br>
                                Phone: <span id="supplierPhone">N/A</span><br>
                                Email: <span id="supplierEmail">N/A</span>
                            </address>
                        </div>

                        <div class="col-sm-4 offset-sm-8 invoice-col">
                            <br>
                            <b>Purchase #:</b> <span id="purchaseOrderId">N/A</span><br>
                            
                        </div>
                    </div>

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
                                <tbody id="purchaseInvoiceItems">
                                    <!-- Items will be dynamically injected here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <p class="lead">Note:</p>
                            <p class="text-muted well well-sm no-shadow" id="purchaseNote" style="margin-top: 10px;">
                            </p>
                        </div>

                        <div class="col-xs-6">
                            <p class="lead">Amount Due : <span id="amountDue">N/A</span></p>
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

            <div class="modal-footer">
                
                <button type="button" class="btn btn-success " onclick="printInvoice()"><i class="fa fa-print"></i> Print</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
