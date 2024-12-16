@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <!-- Total Product Records -->
        <div class="row">
            <!-- Total Quantity Sold Box -->
            <div class="col-lg-6 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalQuantitySold }}</h3>
                        <p>Product Records</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-box"></i>
                    </div>
                </div>
            </div>
            <!-- Total Revenue Box -->
            <div class="col-lg-6 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ number_format($totalRevenue, 2) }}</h3>
                        <p>Total Revenue</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-cart"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box Header -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Product Sold Report</h3>
        </div>

        
        <form method="GET" action="{{ route('reports.product-sold-report') }}">
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
                    <input type="hidden" name="start_date" id="start_date_id" value="{{ request('start_date', '') }}">
                    <input type="hidden" name="end_date" id="end_date_id" value="{{ request('end_date', '') }}">
                </div>

                <!-- Customer Dropdown -->
                <div class="col-md-4">
                    <label for="customer_id">Select Warehouse:</label>
                    <select name="warehouse_id" id="warehouse_id" class="form-control">
                        <option value="">Select Warehouse</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" 
                                {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}
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
                </form>
                <div class="d-flex justify-content-end mt-4">
                    <button id="generate-pdf" class="btn btn-secondry mt-4">  <i class="fa fa-file"></i> PDF</button>
                    <button id="print-pdf" class="btn btn-secondry mt-4"><i class="fa fa-print"></i> Print</button>
                </div>                    
            </div>

        <!-- Product Listings Table -->
        <table id="product-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Order Reference Id</th>
                    <th>Customer </th>
                    <th>Product</th>
                    <th>Quantity Sold</th>
                    <th>Price Sold</th>
                    <th>Total Revenue</th>
                    <th>Warehouse</th>
                    <th>Sale Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    @foreach ($product->orderItems as $orderItem)
                        <tr>
                            <td>{{ $orderItem->custom_order_id }}</td>
                            <td>{{ $orderItem->order->customer->name }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $orderItem->quantity }}</td>
                            <td>{{ $orderItem->unit_price }}</td>
                            <td>{{ $orderItem->quantity * $orderItem->unit_price }}</td>
                            <td>
                                @if ($orderItem->warehouse)
                                    {{ $orderItem->warehouse->name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($orderItem->order->order_date)->format('d-m-Y') }}</td>
           
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="balancesTableContainer"></div>
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
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
        $('#start_date_id').val(start.format('YYYY-MM-DD'));
        $('#end_date_id').val(end.format('YYYY-MM-DD'));
    });
  });



  $(document).ready(function () {


  function generateReport(e, action) {
    e.preventDefault();

    var warehouseId = $('#warehouse_id').val();
    var startDate = $('#start_date_id').val();
    var endDate = $('#end_date_id').val();
    var token = $('meta[name="csrf-token"]').attr('content');

    $('#loader').show();

    $.ajax({
        url: '{{ route('product-sold-report-pdf') }}', // Update with your route
        type: 'GET',
        data: {
            _token: token,
            warehouse_id: warehouseId,
            start_date: startDate,
            end_date: endDate,
        },
        success: function (response) {
            $('#loader').hide();
            console.log(response);

            if (response.products && response.products.length > 0) {
                let products = response.products;
                let totalQuantitySold = response.totalQuantitySold;
                let totalRevenue = response.totalRevenue;

                var formattedTime = new Date().toLocaleString();

                var tableHtml = '<div id="pdf-content" class="table-responsive mt-4">';
                tableHtml += '<h3 class="text-center mb-4"><strong>Product Sold Report</strong></h3>';
                tableHtml += '<p class="text-center mb-4">Report generated on: ' + formattedTime + '</p>';
                tableHtml += '<table class="table table-bordered table-striped">';
                tableHtml += '<thead class="thead-dark">';
                tableHtml += '<tr>';
                tableHtml += '<th>Product Name</th>';
                tableHtml += '<th>Order ID</th>';
                tableHtml += '<th>Customer</th>';
                tableHtml += '<th>warehouse</th>';
                tableHtml += '<th>Quantity Sold</th>';
                tableHtml += '<th>Unit Price</th>';
                tableHtml += '<th>Total Revenue</th>';
                tableHtml += '</tr>';
                tableHtml += '</thead>';
                tableHtml += '<tbody>';

                products.forEach(product => {
                    product.order_items.forEach(orderItem => {
                        tableHtml += '<tr>';
                        tableHtml += '<td>' + (product.product_name || '') + '</td>';
                        tableHtml += '<td>' + (orderItem.custom_order_id || '-') + '</td>';
                        tableHtml += '<td>' + (orderItem.order.customer.name || '-') + '</td>';
                        tableHtml += '<td>' + (orderItem.warehouse.name || '-') + '</td>';
                        tableHtml += '<td>' + (orderItem.quantity || '-') + '</td>';
                        tableHtml += '<td>' + (orderItem.unit_price || '-') + '</td>';
                        tableHtml += '<td>' + (orderItem.quantity * orderItem.unit_price || '-') + '</td>';
                        tableHtml += '</tr>';
                    });
                });

                tableHtml += '</tbody>';
                tableHtml += '</table>';
                tableHtml += '<p class="text-end"><strong>Total Quantity Sold: ' + totalQuantitySold + '</strong></p>';
                tableHtml += '<p class="text-end"><strong>Total Revenue: ' + totalRevenue + '</strong></p>';
                tableHtml += '</div>';

                if (action === 'pdf') {
                    $('#balancesTableContainer').html(tableHtml);
                    var element = document.getElementById('balancesTableContainer');
                    html2pdf(element, {
                        margin: 15,
                        filename: 'product_sold_report.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    });

                    setTimeout(function () {
                        $('#balancesTableContainer').hide();
                    }, 2000);
                } else if (action === 'print') {
                    var printWindow = window.open('', '', 'height=600,width=800');
                    printWindow.document.write('<html><head><title>Product Sold Report</title>');
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
                }
            } else {
                alert('No data found for the selected filters.');
            }
        },
        error: function (error) {
            $('#loader').hide();
            console.error('Error:', error);
            alert('Failed to generate the report.');
        }
    });
}

// Bind the click event for the generate PDF button
$('#generate-pdf').on('click', function (e) {
    generateReport(e, 'pdf');
});

// Bind the click event for the print button
$('#print-pdf').on('click', function (e) {
    generateReport(e, 'print');
});

});


</script>
@endsection
