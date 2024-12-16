@extends('layouts.app')

@section('content')
<div class="content-wrapper">

    <div class="form-border">

        <!-- Total Summary Section -->
        <div class="row">
            @php
                $totalPayments = $totalPayments;  // Total number of payments
                $totalAmount = $totalAmount;  // Total sum of payments
                $totalCountAmount = $totalCountAmount;  // New total count amount
            @endphp

            <!-- Total Payments Box -->
            <div class="col-lg-6 col-xs-">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $totalPayments }}</h3>
                        <p>Expenses</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-list"></i>
                    </div>
                </div>
            </div>

            <!-- Total Count Amount Box (New) -->
            <div class="col-lg-6 col-xs-12">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ number_format($totalCountAmount, 2) }}</h3> <!-- Format the total count amount -->
                        <p>Total Expenses Amount</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-ios-calculator"></i>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Payments History Table Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Expenses History</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
        </div>

        <form method="GET" action="{{ route('expenses.index') }}">
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
                                    
                    <div class="col-md-4">
                        <label for="payment_head_id">Expense Category:</label>
                        <select name="payment_head_id" id="payment_head_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach ($paymentHeads as $paymentHead)
                                <option value="{{ $paymentHead->id }}" 
                                    {{ request('payment_head_id') == $paymentHead->id ? 'selected' : '' }}>
                                    {{ $paymentHead->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                <!-- Submit Button -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success mt-4" style="margin-top:24px">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
                </form>
                
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="d-flex justify-content-end mt-4">
                            <button id="generate-pdf" class="btn btn-secondry mt-4">  <i class="fa fa-file"></i> PDF</button>
                            <button id="print-pdf" class="btn btn-secondry mt-4"><i class="fa fa-print"></i> Print</button>
                        </div>
                    </div>                        
                
            </div>

        <!-- Payments Listings Table -->
        <table id="expenses-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Note</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $expenses)
                    <tr>
                        <td>{{ $expenses->payment_date }}</td>
                        <td>{{ $expenses->paymentHead->name ?? 'N/A' }}</td>
                        <td>{{ number_format($expenses->amount, 2) }}</td> <!-- Format amount -->
                        <td>{{ $expenses->payment_type }}</td>
                        <td>{{ $expenses->note }}</td>
                        <td>
                            <div class="custom-dropdown text-center">
                                <button class="custom-dropdown-toggle" type="button">
                                    Actions <i class="fa fa-caret-down"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <!-- Edit expenses -->
                                    <a href="{{ route('expenses.edit', $expenses->id) }}" class="custom-dropdown-item">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete expenses -->
                                    <form id="deleteForm-{{ $expenses->id }}" 
                                          action="{{ route('expenses.destroy', $expenses->id) }}" 
                                          method="POST" 
                                          class="custom-dropdown-item delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteExpense({{ $expenses->id }})" class="btn btn-danger">
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
<div id="balancesTableContainer"></div>
<script src="../../bower_components/jquery/dist/jquery.min.js"></script>
<script>
    
    $(function () {
        $('#expenses-listings').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            'order': [[0, 'desc']] // Sorts by first column (Payment Date) in descending order
        });
});


function confirmDeleteExpense(id) {
        if (confirm('Are you sure you want to delete this expense head?')) {
            document.getElementById('deleteForm-' + id).submit();
        }
    }

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

    var paymentHeadId = $('#payment_head_id').val();
    var startDate = $('#start_date_id').val();
    var endDate = $('#end_date_id').val();
    var token = $('meta[name="csrf-token"]').attr('content');

    $('#loader').show();

    $.ajax({
        url: '{{ route('expenses.indexPDF') }}', // Update with your route
        type: 'GET',
        data: {
            _token: token,
            payment_head_id: paymentHeadId,
            start_date: startDate,
            end_date: endDate,
        },
        success: function (response) {
            $('#loader').hide();
            console.log(response);

            if (response.payments && response.payments.length > 0) {
                let payments = response.payments;
                let totalAmount = response.totalAmount || 0;
                let totalCountAmount = response.totalCountAmount || 0;
                let totalPayments = response.totalPayments || 0;

                var formattedTime = new Date().toLocaleString();

                var tableHtml = '<div id="pdf-content" class="table-responsive mt-4">';
                tableHtml += '<h3 class="text-center mb-4"><strong>Expense Report</strong></h3>';
                tableHtml += '<p class="text-center mb-4">Report generated on: ' + formattedTime + '</p>';
                tableHtml += '<table class="table table-bordered table-striped">';
                tableHtml += '<thead class="thead-dark">';
                tableHtml += '<tr>';
                tableHtml += '<th>Type</th>';
                tableHtml += '<th>Category</th>';
                tableHtml += '<th>Amount</th>';
                tableHtml += '<th>Note</th>';
                tableHtml += '</tr>';
                tableHtml += '</thead>';
                tableHtml += '<tbody>';

                payments.forEach(payment => {
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + (payment.payable_type || '-') + '</td>';
                    tableHtml += '<td>' + (payment.payment_head.name || '-') + '</td>';
                    tableHtml += '<td>' + (payment.amount || '0.00') + '</td>';
                    tableHtml += '<td>' + (payment.note || 'Pending') + '</td>';
                    tableHtml += '</tr>';
                });

                tableHtml += '</tbody>';
                tableHtml += '</table>';
                tableHtml += '<p class="text-end"><strong>Total Amount: ' + totalAmount + '</strong></p>';
                tableHtml += '<p class="text-end"><strong>Total Count Amount: ' + totalCountAmount + '</strong></p>';
                tableHtml += '<p class="text-end"><strong>Total Payments: ' + totalPayments + '</strong></p>';
                tableHtml += '</div>';

                if (action === 'pdf') {
                    $('#balancesTableContainer').html(tableHtml);
                    var element = document.getElementById('balancesTableContainer');
                    html2pdf(element, {
                        margin: 15,
                        filename: 'expense_report.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    });

                    setTimeout(function () {
                        $('#balancesTableContainer').hide();
                    }, 2000);
                } else if (action === 'print') {
                    var printWindow = window.open('', '', 'height=600,width=800');
                    printWindow.document.write('<html><head><title>Expense Report</title>');
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
