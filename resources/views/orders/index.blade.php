@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Order Listings</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Order -->
        <div class="text-right">
            <a href="{{ route('orders.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Order
            </a>
        </div>

        <!-- Orders Listings Table -->
        <table id="order-listings" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Sale Id.</th> <!-- Add Order ID header -->
                <th>Order Date</th>
                <th>Customer</th>
                <th>Gross Amount</th>
                <th>Order Discount</th>
                <th>Discount Type</th> <!-- Add Discount Type header -->
                <th>Tax (%) </th>
                <th>Order Other Charges</th>
                <th>Net Total</th>
                <th>Paid</th>
                <th>Amount Due</th>
                <th>Actions</th>
            </tr>

            </thead>
            <tbody>
    @foreach($orders as $order)
        <tr>
            
            <td><a href="javascript:void(0);" onclick="getInvoiceDetails('{{ $order->custom_order_id }}')">{{ $order->custom_order_id }}</a></td> <!-- Display Custom Order ID -->
  
            <td>{{ $order->order_date }}</td>
            <td>{{ $order->customer ? $order->customer->name : 'N/A' }}</td>
            <td>{{ $order->grossAmount }}</td>
            <td>{{ $order->orderDiscount }}</td>
            <td>{{ $order->discount_type }}</td>
            <td>{{ $order->tax_rate ? $order->tax_rate . '%' : '' }}</td>
            <td>{{ $order->other_charges }}</td>
            <td>{{ $order->netTotal }}</td>
            <td>{{ $order->paid }}</td>
            <td class="text-center">
        <span class="badge 
            @if($order->remainingAmount > 0) 
                badge-badge-danger
            @elseif($order->remainingAmount <= 1) 
                badge-badge-success 
            @else 
                badge-badge-danger 
            @endif">
            @if($order->remainingAmount > 0) 
                Due ({{ $order->remainingAmount }})
            @elseif($order->remainingAmount == 0)
                Paid
            @else
                Overpaid ({{ $order->remainingAmount }})
            @endif
        </span>
    </td>
         <td>
        <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- View Payment Option -->
                                        <button 
                                            class="custom-dropdown-item"
                                            type="button"
                                            data-toggle="modal"
                                            data-target="#invoiceModal"
                                            onclick="getInvoiceDetails('{{ $order->custom_order_id }}')"
                                        >
                                            <i class="fa fa-eye"></i> View Invoice
                                        </button>

                                        <button 
                                            class="custom-dropdown-item" 
                                            type="button" 
                                            onclick="getSaleDataForSharing('{{ $order->custom_order_id }}', 'share')">
                                            <i class="fa fa-whatsapp"></i> WhatsApp
                                        </button>

                                    <!-- Edit Payment Option -->
                                    <a href="{{ route('order.edit', $order->custom_order_id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                </a>
                        </div>
                </div>
            </td>
        </tr>
    @endforeach
</tbody>
        </table>
    </div>
</div>

<!-- Modal for Viewing Order Details -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
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
                            <!-- <b>Invoice #<span id="invoiceNumber">N/A</span></b><br> -->
                            <br>
                            <b>Order Id:</b> <span id="orderId">N/A</span><br>
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
                <button type="button" class="btn btn-success" onclick="sendReceiptOnWhatsApp()">
                    <i class="fa fa-whatsapp"></i> Send on WhatsApp
                </button>
                <button type="button" class="btn btn-success" onclick="printInvoice()"><i class="fa fa print"></i> Print</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<div class="modal-body canva-section-watsapp" style="display:none">
    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <div class="logo-img-invoice">
                        <img src="{{ asset('dist/img/logo.png') }}" alt="Inventra Logo">
                        <small class="date-inv">Date: <span id="invoiceDate-watsapp">N/A</span></small>
                    </div>
                </h2>
            </div>
        </div>

        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong id="customerName-watsapp">N/A</strong><br>
                    <span id="customerAddress-watsapp">N/A</span><br>
                    Phone: <span id="customerPhone-watsapp">N/A</span><br>
                    Email: <span id="customerEmail-watsapp">N/A</span>
                </address>
            </div>

            <div class="col-sm-4 offset-sm-8 invoice-col">
                <br>
                <b>Sale #:</b> <span id="saleOrderId-watsapp">N/A</span><br>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>UOM</th>
                            <th>Rate</th>
                            <th>Discount</th>
                            <th>Net Rate</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody id="saleInvoiceItems-watsapp">
                        <!-- Items will be dynamically injected here -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <p class="lead">Note:</p>
                <p class="text-muted well well-sm no-shadow" id="saleNote-watsapp" style="margin-top: 10px;">
                </p>
            </div>

            <div class="col-xs-6">
                <p class="lead">Amount Due: <span id="amountDue-watsapp">N/A</span></p>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Subtotal:</th>
                            <td id="subtotalAmount-watsapp">$0.00</td>
                        </tr>
                        <tr>
                            <th>Discount:</th>
                            <td id="discountAmount-watsapp">0.00</td>
                        </tr>
                        <tr>
                            <th>Other Charges:</th>
                            <td id="otherCharges-watsapp">0.00</td>
                        </tr>
                        <tr>
                            <th>Paid:</th>
                            <td id="paidAmount-watsapp">0.00</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>



@endsection
