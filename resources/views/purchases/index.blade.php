@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    
    <div class="form-border">
    
    
    <div class="row">
            @php
                $totalGrossAmount = 0;
                $totalDiscount = 0;
                $totalPaid = 0;
                $totalDue = 0;
            @endphp

            <!-- Calculate Totals -->
            @foreach($purchases as $purchase)
                @php
                    $totalGrossAmount += $purchase->grossAmount;
                    $totalDiscount += ($purchase->discount_type === '%') 
                        ? ($purchase->grossAmount * $purchase->purchaseDiscount / 100) 
                        : $purchase->purchaseDiscount;
                    $totalPaid += $purchase->paid;
                    $totalDue += max($purchase->remainingAmount, 0); // Only add positive due amounts
                @endphp
            @endforeach

            <!-- Display Calculated Totals -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ number_format($totalGrossAmount, 2) }}</h3>
                        <p>Gross Amount</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-cash"></i> <!-- Updated Icon -->
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ number_format($totalDiscount, 2) }}</h3>
                        <p>Discount</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-pricetag"></i> <!-- Updated Icon -->
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ number_format($totalPaid, 2) }}</h3>
                        <p>Paid</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-checkmark"></i> <!-- Updated Icon -->
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box"  style = 'background-color:#B13C2E'>
                    <div class="inner" >
                        <h3>{{ number_format($totalDue, 2) }}</h3>
                        <p>Amount Due</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-clock"></i> <!-- Updated Icon -->
                    </div>
                    
                </div>
            </div>
        </div>


        <div class="box-header with-border">
            <h3 class="box-title custom-title">Purchase Orders</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Purchase -->
        <div class="text-right">
            <a href="{{ route('purchases.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Purchase
            </a>
        </div>

        <!-- Purchases Listings Table -->
        <table id="purchase-listings" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Purchase Order ID</th>
                <th>Purchase Date</th>
                <th>Vendor</th>
                <th>Gross Amount</th>
                <th>Discount</th>
                <th>Tax (%) </th>
                <th>Other Charges</th>
                <th>Net Total</th>
                <th>Paid</th>
                <th>Status</th>
                <th>Amount Due</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($purchases as $purchase)
                <tr>
                    <td><a  href="javascript:void(0);" onclick="getPurchaseInvoiceDetails('{{ $purchase->custom_purchase_id }}')">{{ $purchase->custom_purchase_id }}</a></td>
                    <td>{{ $purchase->purchase_date }}</td>
                    <td>{{ $purchase->supplier->name ? $purchase->supplier->name : 'N/A' }}</td>
                    <td>{{ $purchase->grossAmount }}</td>
                    <td>{{ $purchase->discount_type === '%' ? $purchase->purchaseDiscount . $purchase->discount_type : $purchase->purchaseDiscount }}</td>
                    <td>{{ $purchase->tax_rate ? $purchase->tax_rate : '' }}</td>
                    <td>{{ $purchase->other_charges }}</td>
                    <td>{{ $purchase->netTotal }}</td>
                    <td>{{ $purchase->paid }}</td>
                    <td>{{ $purchase->status }}</td>
                    <td class="text-center">
                        <span class="badge 
                            @if($purchase->remainingAmount > 0) 
                                badge-badge-danger
                            @elseif($purchase->remainingAmount <= 1) 
                                badge-badge-success 
                            @else 
                                badge-badge-danger 
                            @endif">
                            @if($purchase->remainingAmount > 0) 
                                Due ({{ $purchase->remainingAmount }})
                            @elseif($purchase->remainingAmount == 0)
                                Paid
                            @else
                                Overpaid ({{ $purchase->remainingAmount }})
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
                                    data-target="#purchaseInvoiceModal"
                                    onclick="getPurchaseInvoiceDetails('{{ $purchase->custom_purchase_id }}')"
                                >
                                    <i class="fa fa-eye"></i> View Purchase
                                </button>
                                <!-- WhatsApp Share Button -->
                                    <button 
                                        class="custom-dropdown-item" 
                                        type="button" 
                                        onclick="getPurchaseDataForSharing('{{ $purchase->custom_purchase_id }}', 'share')">
                                        <i class="fa fa-whatsapp"></i> WhatsApp
                                    </button>

                                    <!-- Print Button -->
                                    <button 
                                        class="custom-dropdown-item" 
                                        type="button" 
                                        onclick="getPurchaseDataForSharing('{{ $purchase->custom_purchase_id }}', 'print')">
                                        <i class="fa fa-print"></i> Print
                                    </button>

                                    <form id="deleteForm-{{ $purchase->custom_purchase_id }}" 
                                        action="{{ route('purchase.destroy', $purchase->custom_purchase_id) }}" 
                                        method="POST" 
                                        class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeletePurchase({{ $purchase->custom_purchase_id }})" class="delete-btn btn btn-danger">
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
                        <div class="col-sm-4 invoice-col">
                            <strong>From</strong>
                            <address>
                                <strong id="companyName">Company Name</strong><br>
                                <span id="companyAddress">123 Main Street, City, Country</span><br>
                                Phone: <span id="companyPhone">+123456789</span><br>
                                Email: <span id="companyEmail">info@company.com</span>
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



            <div class="modal-body  canva-section-watsapp" style = 'display:none'>
                <section class="invoice" >
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
                            <strong>To</strong>
                            <address>
                                <strong id="supplierName-watsapp">N/A</strong><br>
                                <span id="supplierAddress-watsapp">N/A</span><br>
                                Phone: <span id="supplierPhone-watsapp">N/A</span><br>
                                Email: <span id="supplierEmail-watsapp">N/A</span>
                            </address>
                        </div>
                        <div class="col-sm-4 invoice-col">
                            <strong>Company Info</strong>
                            <address>
                                <strong id="companyName-watsapp">Company Name</strong><br>
                                <span id="companyAddress-watsapp">123 Main Street, City, Country</span><br>
                                Phone: <span id="companyPhone-watsapp">+123456789</span><br>
                                Email: <span id="companyEmail-watsapp">info@company.com</span>
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
                                        <th>Uom</th>
                                        <th>Rate</th>
                                        <th>Discount</th>
                                        <th>Net Rate</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="purchaseInvoiceItems-watsapp">
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
                                        <td id="subtotalAmount-watsapp">$0.00</td>
                                    </tr>
                                    <tr>
                                        <th>Discount</th>
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
