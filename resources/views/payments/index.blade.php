@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
    <div class="row">
    <!-- Customer Credit and Debit -->
    <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-grey">
            <div class="inner">
                <h3>{{ number_format($totalCredit, 2) }}</h3>
                <p> Credit (  + payment in )</p>
            </div>
            <div class="icon" style="color:#222D32">
                <i class="fa fa-dollar"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-12">
        <div class="small-box bg-grey">
            <div class="inner">
                <h3>{{ number_format($totalDebit, 2) }}</h3>
                <p> Debit ( - payment out )</p>
            </div>
            <div class="icon" style="color:#222D32">
                <i class="fa fa-dollar"></i>
            </div>
        </div>
    </div>
</div>

        <div class="box-header with-border">
            <h3 class="box-title custom-title">Payment Listings</h3>
            @if(session('success'))
                <div class="alert alert-success" id="successMessage">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" id="errorMessage">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Button to Add a New Payment -->
        <div class="text-right">
            <a href="{{ route('payments.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Add Payment
            </a>
        </div>

        <!-- Payments Listings Table -->
        <table id="payment-listings"class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Payable</th>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr id="paymentRow-{{ $payment->id }}">
                    <!-- Payment Method with badge class for Credit or Debit -->
                    
                    <td>
                        @if($payment->payment_date)
                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') }}
                        @else
                            N/A
                        @endif
                    </td>

                    
                    <!-- Payment Method with badge class for Credit or Debit -->
                    <td>
                            {{ ucfirst($payment->payable_name) }}
                    </td>


                    <!-- Payment Method with badge class for Credit or Debit -->
                    
                    <td>
                        {{ $payment->amount }}
                    </td>

                    <!-- Payment Type with custom badge-badge-success class -->
                    <td>
                        <span class="badge {{ $payment->payment_type === 'credit' ? 'badge-badge-success' : 'badge-badge-danger' }}">
                            @if($payment->payment_type === 'credit')
                                Payment In (Credit + )
                            @elseif($payment->payment_type === 'debit')
                                Payment Out (Debit - )
                            @else
                                {{ ucfirst($payment->payment_type) }}
                            @endif
                        </span>
                    </td>

                    <td>
                        
                            {{ ucfirst($payment->status) }}
                        
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
                                        data-target="#viewPaymentModal"
                                        data-id="{{ $payment->id }}"
                                        data-payable_name="{{ $payment->payable_name }}"
                                        data-amount="{{ $payment->amount }}"
                                        data-status="{{ $payment->status }}"
                                        data-payment_type="{{ $payment->payment_type }}"
                                        data-payment_method="{{ $payment->payment_method }}"
                                        data-note="{{ $payment->note }}"
                                        data-payment_date="{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d-M-Y') : 'N/A' }}"

                                    >
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button 
                                        class="custom-dropdown-item" 
                                        type="button" 
                                        onclick="getPaymentDataForSharing('{{ $payment->id }}', 'share')">
                                        <i class="fa fa-whatsapp"></i> WhatsApp
                                    </button>
                                        <!-- Print Button -->
                                    <button 
                                        class="custom-dropdown-item" 
                                        type="button" 
                                        onclick="getPaymentDataForSharing('{{ $payment->id }}', 'print')">
                                        <i class="fa fa-print"></i> Print
                                    </button>
                                <!-- Edit Payment Option -->
                                <a href="{{ route('payments.edit', $payment->id) }}" class="custom-dropdown-item">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                <!-- Delete Payment Option -->
                                <form id="deleteForm-{{ $payment->id }}" action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="custom-dropdown-item delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDeletePayment({{ $payment->id }})" class="delete-btn btn btn-danger">
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
<!-- Modal Structure -->
<div class="modal fade" id="viewPaymentModal" tabindex="-1" role="dialog" aria-labelledby="viewPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Receipt Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Receipt Card -->
                <div class="receipt-card" id="receipt-card">
                    <div class="receipt-header">
                        <h1 class="receipt-title">Payment Receipt</h1>
                        <div class="receipt-date" id="modal-payment-date"></div>
                                                
                    </div>
                    
                    <div class="amount-section" id="amount-section">
                        <div class="amount-label" id ="modal-amount-label" ></div>
                        <div class="amount" id="modal-amount"></div>
                        <div class="doller-icon" >
                            <img id="currency-icon" src="" alt="Currency" width="80" height="80" /> 
                    
                        </div>
                    </div>
                    <!-- Note Section -->
                    <div class="note-section">
                         <span id="modal-note"></span>
                    </div>
                    <div class="store-section">
                        <div class="store-name" id="modal-payable-name"></div>
                        <img class="logo" src="../../dist/img/logo.png" alt="inventra" width="100" height="100" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-success" onclick="printReceipt()"><i class="fa fa print"></i> Print</button>
                
            </div>
        </div>
    </div>
</div>


    <div class="modal-body canva-section-watsapp" style = "display:none">
        <div class="receipt-card-share" >
            <div class="receipt-header">
                <h1 class="receipt-title">Payment Receipt</h1>
                <div class="receipt-date" id="modal-payment-date-receipt-watsapp"></div>
                                        
            </div>
            
            <div class="amount-section" id="amount-section">
                <div class="amount-label" id ="modal-amount-label-receipt" ></div>
                <div class="amount" id="modal-amount-watsapp"></div>
                <div class="doller-icon" >
                    <img id="currency-icon-watsapp-receipt" src="" alt="Currency" width="80" height="80" /> 
                </div>
            </div>
            <!-- Note Section -->
            <div class="note-section">
                <strong></strong> <span id="modal-note-receipt-watsapp"></span>
            </div>
            <div class="store-section">
                <div class="store-name" id="modal-payable-name-receipt"></div>
                <img class="logo" src="../../dist/img/logo.png" alt="inventra" width="100" height="100" />
            </div>
        </div>
    </div>    



<!-- Custom Styles for Receipt Card -->
<style>
    .modal-amount-label-receipt { 

        text-transform: uppercase; 
    }   
    .receipt-card-share{
        background-color: white;
        width: 500%;
        max-width: 400px;
        border-radius: 16px;
        border: 1px solid black;
        padding: 24px;
        margin: auto;
        font-family: Arial, sans-serif;
    }
    .receipt-card {
        background-color: white;
        width: 500%;
        max-width: 400px;
        border-radius: 16px;
        border: 1px solid black;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 24px;
        margin: auto;
        font-family: Arial, sans-serif;
    }

    .receipt-header {
        margin-bottom: 20px;
        text-align: center;
    }

    .receipt-title {
        font-size: 28px;
        font-weight: bold;
        color: #333;
        margin: 0;
    }

    .receipt-date {
        color: #666;
        font-size: 16px;
        margin-top: 8px;
    }

    .amount-section {
        background: #000000;
        border-radius: 12px;
        padding: 20px;
        color: white;
        position: relative;
        margin: 20px 0;
       
    }

    .amount-label {
        font-size: 18px;
        margin-bottom: 8px;
        text-transform: uppercase; 
    }

    .amount {
        font-size: 32px;
        font-weight: bold;
        color:white;
    }

    .doller-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 170px;
        height: 100px;
        display: flex;
        align-items: right;
        justify-content: right;
    }

    .store-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .store-name {
        font-size: 18px;
        color: #000;
        text-transform: capitalize; 

    }

    .logo {
        height: 30px;
    }

    /* Align the print and WhatsApp buttons */
    .modal-footer .d-flex {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }

    .modal-footer .btn {
        width: 48%;
    }
    .store-section img{
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0;
    }

  
</style>

<script>

    function openPaymentModal(paymentData) {
        document.getElementById("modal-payment-date").textContent = paymentData.date;
        document.getElementById("modal-payable-name").textContent = paymentData.payable;
        document.getElementById("modal-amount").textContent = paymentData.amount;
        
        $('#viewPaymentModal').modal('show');
    }

    function printReceipt() {
    const modal = $('#viewPaymentModal'); 
    
    // Extract dynamic values from modal
    const payableName = modal.find('#modal-payable-name').text();
    const amount = modal.find('#modal-amount').text();
    const amountLabel = modal.find('#modal-amount-label').text();
    const paymentDate = modal.find('#modal-payment-date').text();
    const currencyIconSrc = modal.find('#currency-icon').attr('src');
    const note = modal.find('#modal-note').text(); 
    
    
    const paymentType = modal.find('#modal-payment-type').text();
    const amountSectionColor = (paymentType == 'credit') ? '#28a745' : (paymentType == 'debit') ? '#dc3545' : '#007bff'; // Green for credit, Red for debit, Blue for default

    
    const printWindow = window.open("", "_blank");

    printWindow.document.open();
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Receipt</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        background-color: #f9f9f9;
                    }
                    .receipt-card {
                        width: 400px;
                        background: white;
                        padding: 24px;
                        border-radius: 16px;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    }
                    .receipt-card h1 {
                        font-size: 24px;
                        text-align: center;
                        color: #333;
                    }
                    .receipt-date {
                        text-align: center;
                        font-size: 14px;
                        margin-bottom: 20px;
                    }
                    .amount-section {
                        
                        background-color: black;
                        padding: 20px;
                        border-radius: 12px;
                        color: white;
                        text-align: center;
                    }
                    .amount {
                        font-size: 36px;
                        font-weight: bold;
                    }
                    .amount-label {
                        text-transform: uppercase; 
                        font-size: 20px;
                        margin-bottom: 10px;
                    }
                    .doller-icon img {
                        width: 100px;
                        margin-top: 10px;
                    }
                    .store-section {
                        text-align: center;
                        margin-top: 20px;
                    }
                    .store-name {
                        font-size: 18px;
                        margin-bottom: 10px;
                    }
                    .logo {
                        width: 100px;
                        height: 100px;
                    }
                    .note-section {
                        font-size: 14px;
                        margin-top: 20px;
                        color: #333;
                    }
                    .receipt-footer {
                        text-align: center;
                        font-size: 14px;
                        color: #888;
                        margin-top: 20px;
                    }
                    
                    @media print {
                        body {
                            font-family: Arial, sans-serif !important;
                            background: white !important;
                            margin: 0 !important;
                        }
                            .amount-label {
                                text-transform: uppercase; 
                                font-size: 20px;
                                margin-bottom: 10px;
                            }
                        .receipt-card {
                            width: 100% !important;
                            max-width: 600px !important;    
                            height:400px !important;
                            padding: 20px !important;
                            .receipt-card {
                            border: 1px solid white;
                            border-radius: 15px;
                            padding: 1rem;
                            box-shadow: 0 4px 22px rgba(0, 0, 0, 0.2); 
                            box-shadow: none !important;
                            height:auto
                        }
                        .amount-section {
                            background: black;
                            color: white !important;
                            padding: 20px !important;
                            border-radius: 12px !important;
                            font-size: 24px !important;
                            display:flex;
                            align-items:center;
                            justify-content:space-between;
                        }
                            .col{
                            display:flex;
                            flex-direction: column;
                            }
                        .amount {
                            font-size: 36px !important;
                            font-weight: bold !important;
                        }
                        .doller-icon img {
                            display: block !important;
                            margin: 0 auto !important;
                            width: 150px !important;
                        }
                        .logo {
                            display: block !important;
                       
                            width: 100px !important;
                            height: 100px !important;
                        }
                        .note-section {
                            font-size: 14px !important;
                                 padding-inline: 1rem;
                          
                            color: #333 !important;
                        }
                            .store-section{
                               display:flex;
                            align-items:center;
                            justify-content:space-between;
                             padding-inline: 1rem;
                           }
                      
                    }
                </style>
            </head>
            <body onload="window.print(); window.close();">
                <div class="receipt-card">
                    <div class="receipt-header">
                        <h1 class="receipt-title">Payment Receipt</h1>
                        <div class="receipt-date">${paymentDate}</div>
                    </div>
                    <div class="amount-section">
                      <div class="col">
                        <div class="amount-label">${amountLabel}</div>
                        <div class="amount">${amount}</div>
                     </div>
                        <div class="doller-icon">
                            <img src="${currencyIconSrc}" alt="Currency" />
                        </div>
                    
                    </div>
                      <div class="note-section">
                        <strong>Note:</strong> ${note}
                    </div>
                    <div class="store-section">
                        <div class="store-name">${payableName}</div>
                        <img class="logo" src="../../dist/img/logo.png" alt="Store Logo" />
                    </div>
                  
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
}

</script>

@endsection
