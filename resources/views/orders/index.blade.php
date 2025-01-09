@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
                <div class="row">
                <!-- Gross Amount -->
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-grey">
                        <div class="inner">
                            <h3>{{ $currencySymbol . number_format($totalGrossAmount, 2) }}</h3>
                            <p>Gross Amount</p>
                        </div>
                        <div class="icon" style="color:#222D32">
                            <i class="ion ion-cash"></i> <!-- Icon for Gross Amount -->
                        </div>
                        
                    </div>
                </div>

                <!-- Discount Amount -->
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-grey">
                        <div class="inner">
                            <h3>{{ $currencySymbol . number_format($totalOrderDiscount, 2) }}</h3>
                            <p>Discount</p>
                        </div>
                        <div class="icon" style="color:#222D32">
                            <i class="ion ion-pricetags"></i> <!-- Icon for Discount -->
                        </div>
                        
                    </div>
                </div>

                <!-- Paid Amount -->
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-grey">
                        <div class="inner">
                            <h3>{{ $currencySymbol . number_format($totalPaid, 2) }}</h3>
                            <p>Paid</p>
                        </div>
                        <div class="icon" style="color:#222D32">
                            <i class="ion ion-calculator"></i> <!-- Icon for Paid Amount -->
                        </div>
                        
                    </div>
                </div>

                <!-- Amount Due -->
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box" style="background-color:#008548">
                        <div class="inner">
                            <h3>{{ $currencySymbol . number_format($totalAmountDue, 2) }}</h3>
                            <p>Amount Due</p>
                        </div>
                        <div class="icon" style="color:#222D32">
                            <i class="ion ion-card"></i> <!-- Icon for Amount Due -->
                        </div>
                        
                    </div>
                </div>
            </div>
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Order Listings</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <form method="GET" action="{{ route('orders.index') }}">
            <div class="form-group row">
                <div class="col-md-2">
                    <label>Date range:</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-default" id="daterange-btn">
                            <span>
                                <i class="fa fa-calendar"></i> 
                                {{ request('start_date') && request('end_date') 
                                    ? request('start_date') . ' - ' . request('end_date') 
                                    : 'Date range picker' }}
                            </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                    <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date', '') }}">
                    <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date', '') }}">
                </div>

                <div class="col-md-2">
                    <label for="amount_filter">Filter by Amount:</label>
                    <select name="remaining_amount_filter" id="remaining_amount_filter" class="form-control">
                        <option value="" {{ request('remaining_amount_filter') == '' ? 'selected' : '' }}>Select Filter</option>
                        <option value="due" {{ request('remaining_amount_filter') == 'due' ? 'selected' : '' }}>Amount Due</option>
                        <option value="paid" {{ request('remaining_amount_filter') == 'paid' ? 'selected' : '' }}>Amount Paid</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-success mt-4" style="margin-top:24px">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </form>
                    

        <div class="text-right">
            <a href="{{ route('orders.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Order
            </a>
        </div>

        <!-- Orders Listings Table -->
        <table id="order-listings" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Status</th>
                <th>Reference</th> 
                <th>Date</th>
                <th>Customer</th>
                <th>Gross Amount</th>
                <th>Order Discount</th>
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
        <tr @if($order->status_name === 'Return') class="text-danger" @endif>
            <td >{{ $order->status_name }}</td>
            
            <td><a href="javascript:void(0);" onclick="getInvoiceDetails('{{ $order->custom_order_id }}')">{{ $order->custom_order_id }}</a></td> <!-- Display Custom Order ID -->
  
            <td>{{ $order->order_date }}</td>
            <td>{{ $order->customer ? $order->customer->name : 'N/A' }}</td>
            <td>{{ $order->grossAmount }}</td>
            <td>
                @if($order->discount_type === '%')
                    {{ $order->discount_amount }} %
                @else
                    {{ $order->discount_amount }} 
                @endif
            </td>
            <td>{{ $order->tax_rate ? $order->tax_rate : '' }}</td>
            <td>{{ $order->other_charges }}</td>
            <td>{{ $order->netTotal }}</td>
            <td>{{ $order->ordersPaidAmount }}</td>
            

            <td class="text-center">
                @if($order->status_name === 'Return')
                    <span class="badge 
                        @if($order->remainingAmount > 0) 
                            badge-badge-danger
                        @elseif($order->remainingAmount == 0) 
                            badge-badge-success 
                        @else 
                            badge-badge-danger 
                        @endif">
                        @if($order->remainingAmount > 0)
                            Refund Due ({{ $currencySymbol . number_format($order->remainingAmount, 2) }})
                        @elseif($order->remainingAmount == 0)
                            Refund Settled
                        @else
                            Over-refunded ({{ $currencySymbol . number_format(abs($order->remainingAmount), 2) }})
                        @endif
                    </span>
                @else
                    <span class="badge 
                        @if($order->remainingAmount > 0) 
                            badge-badge-danger
                        @elseif($order->remainingAmount == 0) 
                            badge-badge-success 
                        @else 
                            badge-badge-danger 
                        @endif">
                        @if($order->remainingAmount > 0) 
                            Due ({{ $currencySymbol . number_format($order->remainingAmount, 2) }})
                        @elseif($order->remainingAmount == 0)
                            Paid
                        @else
                            Overpaid ({{ $currencySymbol . number_format(abs($order->remainingAmount), 2) }})
                        @endif
                    </span>
                @endif
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
                                                data-toggle="modal"
                                                data-target="#paymentModal"
                                                onclick="preparePayment('{{ $order->custom_order_id }}')">
                                                <i class="fa fa-plus"></i> Add Payment
                                        </button>
                                        <button 
                                                class="custom-dropdown-item" 
                                                type="button" 
                                                data-toggle="modal"
                                                data-target="#viewPaymentsModal"
                                                onclick="prepareViewPayments('{{ $order->custom_order_id }}')">
                                                <i class="fa fa-eye"></i> View Payment
                                        </button>

                                        <button 
                                            class="custom-dropdown-item" 
                                            type="button" 
                                            onclick="getSaleDataForSharing('{{ $order->custom_order_id }}', 'share')">
                                            <i class="fa fa-whatsapp"></i> WhatsApp
                                        </button>
                                            <!-- Print Button -->
                                        <button 
                                            class="custom-dropdown-item" 
                                            type="button" 
                                            onclick="getSaleDataForSharing('{{ $order->custom_order_id }}', 'print')">
                                            <i class="fa fa-print"></i> Print
                                        </button>
                                        <form id="deleteForm-{{ $order->custom_order_id }}" 
                                            action="{{ route('orders.destroy', $order->custom_order_id) }}" 
                                            method="POST" 
                                            class="custom-dropdown-item delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteOrder({{ $order->custom_order_id }})" class="delete-btn btn btn-danger">
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
                            <strong> To </strong>
                            <address>
                                <strong id="invoiceToName">N/A</strong><br>
                                <span id="invoiceToAddress">N/A</span><br>
                                Phone: <span id="invoiceToPhone">N/A</span><br>
                                Email: <span id="invoiceToEmail">N/A</span>
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

                        <div class="col-sm-4 invoice-col">
                            <!-- <b>Invoice #<span id="invoiceNumber">N/A</span></b><br> -->
                            <br>
                            <b>Inv #:</b> <span id="orderId">N/A</span><br>
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
                                        <th>Bill Discount</th>
                                        <td id="discountAmount">0.00</td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">Order Tax</th>
                                        <td id="taxRate">0.00</td>
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
            <strong> To </strong>
                <address>
                    <strong id="customerName-watsapp">N/A</strong><br>
                    <span id="customerAddress-watsapp">N/A</span><br>
                    Phone: <span id="customerPhone-watsapp">N/A</span><br>
                    Email: <span id="customerEmail-watsapp">N/A</span>
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                <strong>Company Infor</strong>
                <address>
                    <strong id="companyName-watsapp">Company Name</strong><br>
                    <span id="companyAddress-watsapp">123 Main Street, City, Country</span><br>
                    Phone: <span id="companyPhone-watsapp">+123456789</span><br>
                    Email: <span id="companyEmail-watsapp">info@company.com</span>
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



<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Add Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Proceed with your payment for Reference ID: <span id="orderIdModal"></span></p>
                <div class="form-group">
                    <label for="payment_date">Payment Date</label>
                    <input type="date" class="form-control" id="payment_date" placeholder="Select payment date">
                </div>    
                <!-- Payment Fields -->
                <div class="form-group">
                    <label for="receivedAmount">Received Amount</label>
                    <input type="number" class="form-control" id="receivedAmount" placeholder="Enter received amount">
                </div>
                
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control myInput" id  = 'payment_method'>
                            @foreach ($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}" {{ old('payment_method') == $paymentMethod->name ? 'selected' : '' }}>
                                    {{ $paymentMethod->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <span class="validation-msg text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                
 
                    <div class="form-group">
                        <label for="bankAccountList">Account</label>
                        <select class="form-control" id="bankAccountList">
                            @foreach ($accounts as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" id="remarks" rows="3" placeholder="Enter any remarks"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button onclick = 'savePayment()' id="savePaymentButton" type="button" class="btn btn-primary">Save Payment</button>
            </div>

        </div>
    </div>
</div>

<!-- View Payments Modal -->
<div class="modal fade" id="viewPaymentsModal" tabindex="-1" role="dialog" aria-labelledby="viewPaymentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPaymentsModalLabel">View Payments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Payments for Reference ID: <span id="viewOrderIdModal"></span></p>
                <div id="paymentDetails">
                    <p>Loading payments...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <!-- jQuery 3 -->
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
         $('#start_date').val(start.format('YYYY-MM-DD')); // Start date
            $('#end_date').val(end.format('YYYY-MM-DD'));     // End date
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

   
   
  })
</script>
<script> 

function savePayment() {
    
    const orderId = $('#orderIdModal').text();  

    $('#loader').show();
    var receivedAmount = document.getElementById('receivedAmount').value;
    var accountId = document.getElementById('bankAccountList').value;
    var remarks = document.getElementById('remarks').value;
    var paymentDate = document.getElementById('payment_date').value;
    var paymentMethod = document.getElementById('payment_method').value;
    
    
    if (receivedAmount && accountId && paymentDate) {
        
        var formData = new FormData();
        
        formData.append('payment_head', 'customer'); 
        formData.append('payable_type', 'customer'); 
        formData.append('payment_type', 'credit'); 
        formData.append('payable_id', orderId); 
        formData.append('invoice_id', orderId); 
        formData.append('payment_date', paymentDate); 
        formData.append('amount', receivedAmount); 
        formData.append('account_id', accountId);
        formData.append('note', remarks); 
        formData.append('payment_method', paymentMethod);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content')); 

        
        fetch('{{ route('payments.storeUsingSale') }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest', 
            },
            body: formData
        })
        .then(response => {
            console.log('Response:', response); 
            if (!response.ok) {
                throw new Error('Network response was not ok');
                $('#loader').hide();
            }
            return response.json(); 
        })
        .then(data => {
            console.log('Parsed Data:', data); 
            if (data.success) {
                $('#loader').hide();
                $('#paymentModal').modal('hide'); 
                location.reload(); 
            } else {
                $('#loader').hide();
                alert('Failed to save payment. ' + data.message);
            }
        })
        .catch(error => {
            $('#loader').hide();
            console.error('Error:', error);
            alert('An error occurred while saving the payment. Please try again.');
        });
    } else {
        $('#loader').hide();
        alert('Please fill in all required fields.');
    }
}



function prepareViewPayments(orderId) {
    
    $('#viewOrderIdModal').text(orderId);

    
    const paymentDetailsContainer = $('#paymentDetails');
    paymentDetailsContainer.html('<p>Loading payments...</p>');

    
    fetch(`/payments/view/${orderId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest', 
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch payment details.');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                
                let content = `<table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Payment Date</th>
                            <th>Amount</th>
                            <th>Account</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>`;
                data.payments.forEach(payment => {
                    content += `
                        <tr>
                            <td>${payment.payment_date}</td>
                            <td>${payment.amount}</td>
                            <td>${payment.account_name || 'N/A'}</td>
                            <td>${payment.note || 'N/A'}</td>
                        </tr>`;
                });
                content += `</tbody></table>`;
                paymentDetailsContainer.html(content);
            } else {
                paymentDetailsContainer.html('<p>No payments found for this order.</p>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            paymentDetailsContainer.html('<p>An error occurred while fetching payment details.</p>');
        });
}


    function preparePayment(orderId) {
        alert(orderId);
        $('#orderIdModal').text(orderId);
    }
</script>
@endsection
