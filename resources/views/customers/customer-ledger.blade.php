@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="row">
            <!-- Gross Amount -->
            <div class="col-lg-6 col-xs-6">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>0.00</h3>
                        <p>Opening Balance</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-cash"></i> <!-- Icon for Gross Amount -->
                    </div>
                    <a href="#" class="small-box-footer" style="color:#222D32""> <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Discount Amount -->
            <div class="col-lg-6 col-xs-6">
                <div class="small-box" style="background-color: {{ $closingBalance > 0 ? '#B13C2E' : '#d3d3d3' }};">
                    <div class="inner">
                        <h3>{{ number_format((float)($closingBalance ?? 0.00), 2) }}</h3>
                        <p>Closing Balance</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-pricetags"></i> <!-- Icon for Discount -->
                    </div>
                    <a href="#" class="small-box-footer" style="color:black">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>


        </div>

        <div class="box-header with-border">
            <h3 class="box-title custom-title">Customer Ledger</h3>
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

        <form method="GET" action="{{ route('customer-ledger.index') }}">
            <div class="form-group row">
                <!-- Date Range Filter -->
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

                <!-- Customer Dropdown -->
                <div class="col-md-4">
                    <label for="customer_id">Select Customer:</label>
                    <select name="customer_id" id="customer_id" class="form-control">
                        <option value=""></option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
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
            </div>
        </form>

        @if(!empty($ledgerData))
        <table class="table table-bordered table-striped mt-4">
        <thead>
            <tr>
                <th>Date</th>
                <th>Referance #</th>
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
                            @if ($entry['entry_type'] == 'Sale Order')
                                <i class="fa fa-shopping-cart"></i> Sale Order
                            @elseif ($entry['entry_type'] == 'Return')
                                <i class="fa fa-undo"></i> Return
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

<button id="generate-pdf" class="btn btn-primary mt-4">
    <i class="fa fa-file-pdf"></i> Generate PDF
</button>
    </div>

</div>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script>
  $(function () {
    // Initialize Select2 Elements
    $('.select2').select2();

    // Date range picker
    $('#daterange-btn').daterangepicker({
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



  $(document).ready(function () {

  $('#generatePdfBtn').on('click', function () {
        var selectedCustomerNames = $('#customerSelect').val();
        var selectedCityFilter = $('#cityFilter').val();
        var sortOption = $('#sortSelect').val();

        if (selectedCustomerNames && selectedCustomerNames.length > 0) {
            var token = $('meta[name="csrf-token"]').attr('content');
            showLoader();
            $.ajax({
                url: '{{ route('getCustomerBalances') }}',
                type: 'GET',
                data: {
                    _token: token,
                    customer_names: selectedCustomerNames,
                    city_filter: selectedCityFilter
                },
                success: function (response) {
                    $('#balancesTableContainer').show();

                    var currentTime = new Date();
                    var formattedTime = currentTime.toLocaleString();

                    var tableHtml = '<div class="table-responsive mt-4">';
                    tableHtml += '<h3 class="text-center mb-4"><strong>Customer Balance Report</strong></h3>';
                    tableHtml += '<p class="text-center mb-4">Report generated on: ' + formattedTime + '</p>';
                    tableHtml += '<table class="table table-bordered table-striped">';
                    tableHtml += '<thead class="thead-dark"><tr><th>Id</th><th>Customer Name</th><th>City</th><th>address</th><th>Balance</th></tr></thead><tbody>';

                    var rows = [];
                    var filterValue = $('#balanceFilter').val();
                    var counter = 1;
                    var totalBalance = 0;

                    for (var customerName in response) {
                        var customerData = response[customerName];
                        var balance = customerData.balance;
                        var city = customerData.city;
                        var address = customerData.address;

                        if ((filterValue !== '' && balance > filterValue) || filterValue === '') {
                            rows.push({
                                customerName: customerName.toUpperCase(),
                                city: city,
                                address: address,
                                balance: balance
                            });
                        }
                    }

                    if (sortOption === 'city') {
                        rows.sort((a, b) => a.city.localeCompare(b.city));
                    } else {
                        rows.sort((a, b) => a.customerName.localeCompare(b.customerName));
                    }

                    rows.forEach(row => {
                        tableHtml += '<tr>';
                        tableHtml += '<td style="border: 1px solid black;">' + counter + '</td>';
                        tableHtml += '<td style="border: 1px solid black;">' + row.customerName + '</td>';
                        tableHtml += '<td style="border: 1px solid black;">' + row.city + '</td>';
                        tableHtml += '<td style="border: 1px solid black;">' + row.address + '</td>';
                        tableHtml += '<td style="border: 1px solid black;"><b>' + row.balance.toLocaleString() + '</b></td>';
                        tableHtml += '</tr>';
                        counter++;
                        totalBalance += parseFloat(row.balance);
                    });

                    var formattedTotalBalance = totalBalance.toLocaleString();
                    tableHtml += '<tr><td colspan="4"><strong>Total Balance:</strong></td><td><strong>' + formattedTotalBalance + '</strong></td></tr>';
                    tableHtml += '</tbody></table></div>';

                    $('#balancesTableContainer').html(tableHtml);

                    var element = document.getElementById('balancesTableContainer');
                    html2pdf(element, {
                        margin: 15,
                        filename: 'customer_balance_report.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    });

                    setTimeout(function () {
                        $('#balancesTableContainer').hide();
                    }, 2000);

                    hideLoader();
                },
                error: function (error) {
                    hideLoader();
                    console.error('Error:', error);
                }
            });
        } else {
            hideLoader();
            alert('Please select at least one customer.');
        }
    });

    function showLoader() {
        $('#loader-container').removeClass('d-none');
    }

    function hideLoader() {
        $('#loader-container').addClass('d-none');
    }
});

    
</script>
@endsection
