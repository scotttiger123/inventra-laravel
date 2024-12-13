@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="row">
            <!-- Gross Amount -->
            <div class="col-lg-6 col-xs-6">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3> {{ $currencySymbol }} {{ number_format($openingBalance, 2) }} </h3>
                        <p>Opening Balance</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-cash"></i> <!-- Icon for Gross Amount -->
                    </div>
                    <a href="#" class="small-box-footer" style="color:#222D32"> <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Closing Balance for Supplier -->
            <div class="col-lg-6 col-xs-6">
                <div class="small-box" style="background-color: {{ $closingBalance > 0 ? '#B13C2E' : '#008548' }};">
                    <div class="inner">
                        <h3>{{ $currencySymbol }} {{ number_format((float)($closingBalance ?? 0.00), 2) }}</h3>
                        <p>Closing Balance</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-pricetags"></i> <!-- Icon for Discount -->
                    </div>
                    <a href="#" class="small-box-footer" style="color:black">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Supplier Ledger</h3>
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

        <form method="GET" action="{{ route('supplier-ledger.index') }}">
            <div class="form-group row">
                <!-- Date Range Filter -->
                <div class="col-md-2">
                    <label>Date range:</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-default" id="daterange-btn-supplier">
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

                <!-- Supplier Dropdown -->
                <div class="col-md-4">
                    <label for="supplier_id">Select Supplier:</label>
                    <select name="supplier_id" id="supplier_id" class="form-control">
                        <option value=""></option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success mt-4" style="margin-top:24px">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button id="generate-pdf-supplier" class="btn btn-secondry mt-4">  <i class="fa fa-file"></i> PDF</button>
                    <button id="print-pdf-supplier" class="btn btn-secondry mt-4"><i class="fa fa-print"></i> Print</button>
                </div> 
            </div>
        </form>
        
        

        <!-- Supplier-specific Ledger Data -->
        @if(!empty($ledgerData))
        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reference #</th>
                    <th>Payment Amount</th>
                    <th>Total Order Amount</th>
                    <th>Balance</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ledgerData as $entry)
                    <tr>
                        <td>{{ $entry['date'] }}</td>
                        <td>{{ $entry['order_number'] }}</td>
                        <td>
                            @if($entry['payment_amount'])
                                {{ $currencySymbol . ' ' . number_format($entry['payment_amount'], 2) }}
                                <span class="badge 
                                    {{ $entry['payment_type'] === 'credit' ? 'badge-badge-success' : 
                                        ($entry['payment_type'] === 'debit' ? 'badge-badge-danger' : 'badge-info') }}">
                                    @if($entry['payment_type'] === 'credit')
                                        Payment In (Credit +)
                                    @elseif($entry['payment_type'] === 'debit')
                                        Payment Out (Debit -)
                                    @else
                                        {{ ucfirst($entry['payment_type']) }}
                                    @endif
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $entry['total_amount'] ? $currencySymbol . ' ' . number_format($entry['total_amount'], 2) : '-' }}</td>
                        <td>{{ $entry['balance'] }}</td>
                        <td>
                            <span>
                                @if ($entry['entry_type'] == 'Purchase Order')
                                    <i class="fa fa-shopping-cart"></i> Purchase Order
                                @elseif ($entry['entry_type'] == 'Return')
                                    <i class="fa fa-undo"></i> Return Purchase
                                @elseif ($entry['entry_type'] == 'Payment')
                                    <i class="fa fa-credit-card"></i> Payment    
                                @endif 
                            </span>
                        </td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr class="total-row">
                        <td colspan="4" class="text-right"><strong>Total Balance</strong></td>
                        <td colspan="2" class="text-left">
                            {{ $currencySymbol . ' ' . number_format((float)($closingBalance ?? 0.00), 2) }}
                        </td>
                    </tr>
                </tfoot>
            </tbody>
        </table>
        @else
            <p class="text-center mt-4">No ledger entries found for the selected filters.</p>
        @endif
    </div>
</div>
<div id = "balancesTableContainer"> </div>
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script>
  $(function () {
    // Initialize Select2 Elements
    $('.select2').select2();

    // Date range picker
    $('#daterange-btn-supplier').daterangepicker({
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
    }, function (start, end) {
        
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        $('#start_date').val(start.format('YYYY-MM-DD'));
        $('#end_date').val(end.format('YYYY-MM-DD'));
    });
  });

  
  
  $('#print-pdf-supplier').on('click', function (e) {
    e.preventDefault();
    var selectedCustomerId = $('#supplier_id').val();
    
    if (selectedCustomerId) {
        var token = $('meta[name="csrf-token"]').attr('content');
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();

        $('#loader').show();

        $.ajax({
            url: '{{ route('supplier-ledger-pdf') }}',
            type: 'GET',
            data: {
                _token: token,
                supplier_id: selectedCustomerId,
                start_date: startDate,
                end_date: endDate,
            },
            success: function (response) {
                // Hide the loader when the request is successful
                $('#loader').hide();
                console.log(response);
                // Check if the 'success' flag exists and is true
                if (response.success) {
                    
                    let ledgerData = response.ledger_data;
                    let closingBalance = response.closing_balance;
                    let currencySymbol = response.currency_symbol;
                    let supplierName  = response.supplierName;
                    let openingBalance = response.opening_balance;
                    let logoUrl = response.logoUrl;
                    var formattedTime = new Date().toLocaleString();

                    var tableHtml = '<div id="pdf-content" class="table-responsive mt-4">';
                    tableHtml += '<div class="text-center mb-4">';
                    tableHtml += '<img src="' + logoUrl + '" alt="Logo" style="max-width: 150px;">';
                    tableHtml += '</div>';
                    tableHtml += '<h3 class="text-center mb-4"><strong>Customer Ledger Report</strong></h3>';
                    tableHtml += '<h5 class="text-center mb-2">Supplier: ' + supplierName + '</h5>';
                    tableHtml += '<p class="text-center mb-4">Report generated on: ' + formattedTime + '</p>';
                    tableHtml += '<p class="text-center mb-4"><strong>Opening Balance: ' + openingBalance + '</strong></p>';
                    tableHtml += '<table class="table table-bordered table-striped">';
                    tableHtml += '<thead class="thead-dark">';
                    tableHtml += '<tr>';
                    tableHtml += '<th>Date</th>';
                    tableHtml += '<th>Ref #</th>';
                    tableHtml += '<th>Type</th>';
                    tableHtml += '<th>Payment Amount</th>';
                    tableHtml += '<th>Total Amount</th>';
                    tableHtml += '<th>Balance</th>';
                    tableHtml += '</tr>';
                    tableHtml += '</thead>';
                    tableHtml += '<tbody>';

                    ledgerData.forEach(entry => {
                        tableHtml += '<tr>';
                        tableHtml += '<td>' + (entry.date ? entry.date : '') + '</td>';
                        tableHtml += '<td>' + entry.order_number + '</td>';
                        tableHtml += '<td>' + entry.entry_type + '</td>';
                        tableHtml += '<td>' + (entry.payment_amount || '-') + '</td>';
                        tableHtml += '<td>' + (entry.total_amount || '-') + '</td>';
                        tableHtml += '<td>' + entry.balance + '</td>';
                        tableHtml += '</tr>';
                    });

                    tableHtml += '</tbody>';
                    tableHtml += '</table>';
                    tableHtml += '<p class="text-end"><strong>Closing Balance: ' + closingBalance + '</strong></p>';
                    tableHtml += '</div>';

                    var printWindow = window.open('', '', 'height=600,width=800');
                    printWindow.document.write('<html><head><title>Print</title>');
                    printWindow.document.write('<style>');
                    printWindow.document.write('body { font-family: Arial, sans-serif; margin: 0; padding: 0; }');
                    printWindow.document.write('.table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }');
                    printWindow.document.write('.table th, .table td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }');
                    printWindow.document.write('.text-center { text-align: center; }');
                    printWindow.document.write('.text-end { text-align: right; }');
                    printWindow.document.write('.thead-dark { background-color: #343a40; color: #fff; }');
                    printWindow.document.write('</style>');
                    printWindow.document.write('</head><body>');
                    printWindow.document.write(tableHtml);
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    printWindow.print();

                    setTimeout(function () {
                        $('#balancesTableContainer').hide();
                    }, 2000);

                } else {
                    // If 'success' is false or missing, alert the error message
                    alert('Error: ' + (response.message || 'Something went wrong.'));
                }
            },
            error: function (error) {
                // Hide the loader in case of error
                $('#loader').hide();
                console.error('Error:', error);
                alert('Failed to generate the ledger report.');
            }
        });
    } else {
        alert('Please select a customer to generate the ledger report.');
    }
});


$('#generate-pdf-supplier').on('click', function (e) {
    e.preventDefault(); 
    var selectedSupplierId = $('#supplier_id').val();
    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();

    if (selectedSupplierId) {
        var token = $('meta[name="csrf-token"]').attr('content');
        $('#loader').show();

        $.ajax({
            url: '{{ route('supplier-ledger-pdf') }}',
            type: 'GET',
            data: {
                _token: token,
                supplier_id: selectedSupplierId,
                start_date: startDate,
                end_date: endDate
            },
            success: function (response) {
                
                if (response.success) {
                    let ledgerData = response.ledger_data;
                    let closingBalance = response.closing_balance;
                    let openingBalance = response.opening_balance;
                    let currencySymbol = response.currency_symbol;
                    let supplierName = response.supplierName;
                    let logoUrl = response.logoUrl;
                    var formattedTime = new Date().toLocaleString();

                    var tableHtml = '<div class="pdf-table-responsive mt-4">';
                    tableHtml += '<div class="pdf-header text-center mb-4">';
                    tableHtml += '<img src="' + logoUrl + '" alt="Logo" class="pdf-logo" style="max-width: 150px;">';
                    tableHtml += '</div>';
                    tableHtml += '<h3 class="text-center mb-4"><strong>Supplier Ledger Report</strong></h3>';
                    tableHtml += '<h5 class="text-center mb-2">Supplier Name: ' + supplierName + '</h5>';
                    tableHtml += '<p class="text-center mb-4">Report generated on: ' + formattedTime + '</p>';
                    tableHtml += '<p class="text-center mb-4"><strong>Opening Balance: ' + openingBalance + '</strong></p>';

                    tableHtml += '<table class="pdf-table table-bordered table-striped">';
                    tableHtml += '<thead class="pdf-thead-dark">';
                    tableHtml += '<tr>';
                    tableHtml += '<th>Date</th>';
                    tableHtml += '<th>Order Number</th>';
                    tableHtml += '<th>Type</th>';
                    tableHtml += '<th>Payment Amount</th>';
                    tableHtml += '<th>Total Amount</th>';
                    tableHtml += '<th>Balance</th>';
                    tableHtml += '</tr>';
                    tableHtml += '</thead>';
                    tableHtml += '<tbody>';

                    // Loop through the data to generate rows
                    ledgerData.forEach(entry => {
                        tableHtml += '<tr>';
                        tableHtml += '<td>' + (entry.date ? entry.date : '') + '</td>';
                        tableHtml += '<td>' + entry.order_number + '</td>';
                        tableHtml += '<td>' + entry.entry_type + '</td>';
                        tableHtml += '<td>' + (entry.payment_amount || '-') + '</td>';
                        tableHtml += '<td>' + (entry.total_amount || '-') + '</td>';
                        tableHtml += '<td>' + entry.balance + '</td>';
                        tableHtml += '</tr>';
                    });

                    tableHtml += '</tbody>';
                    tableHtml += '</table>';
                    tableHtml += '<p class="text-end pdf-footer"><strong>Closing Balance: ' + closingBalance + '</strong></p>';
                    tableHtml += '</div>';

                    $('#balancesTableContainer').html(tableHtml);

                    var element = document.getElementById('balancesTableContainer');
                    html2pdf(element, {
                        margin: 15,
                        filename: 'supplier_ledger_report.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    });

                    setTimeout(function () {
                        $('#balancesTableContainer').hide();
                    }, 2000);
                } else {
                    alert('Error: ' + response.message);
                }

                $('#loader').hide();
            },
            error: function (error) {
                $('#loader').hide();
                console.error('Error:', error);
                alert('Failed to generate the ledger report.');
            }
        });
    } else {
        alert('Please select a supplier to generate the ledger report.');
    }
});


</script>
@endsection
