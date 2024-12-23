@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">

        <!-- Summary Boxes -->
        <div class="row">
            <!-- Total Credits -->
            <div class="col-md-4">
                <div class="small-box">
                    <div class="inner">
                        <h3>{{ number_format($totalCredits, 2) }}</h3>
                        <p>Total Credits</p>
                    </div>
                    <div class="icon" style="color:#008548">
                        <i class="ion ion-plus"></i>
                    </div>
                </div>
            </div>

            <!-- Total Debits -->
            <div class="col-md-4">
                <div class="small-box bg-danger" >
                    <div class="inner">
                        <h3>{{ number_format($totalDebits, 2) }}</h3>
                        <p>Total Debits</p>
                    </div>
                    <div class="icon" style="color:#B13C2E">
                        <i class="ion ion-minus"></i>
                    </div>
                </div>
            </div>

            <!-- Total Balance -->
            <div class="col-md-4">
                <div class="small-box {{ $totalBalance >= 0 ? 'bg-primary' : 'bg-warning' }}">
                    <div class="inner">
                        <h3>{{ number_format($totalBalance, 2) }}</h3>
                        <p>Total Balance</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-cash"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div class="box-header with-border">
            <h3 class="box-title custom-title">Account Balance Sheet</h3>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <div style="float: right;">
            <div class="d-flex justify-content-end mt-4">
                <button id="generate-pdf" class="btn btn-secondary mt-4">  <i class="fa fa-file"></i> PDF</button>
                <button id="print-pdf" class="btn btn-secondary mt-4"><i class="fa fa-print"></i> Print</button>
            </div>  
        </div>     

        <!-- Account Balance Sheet Table -->
        <table id="account-listings" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Account No</th>
                    <th>Name</th>
                    <th>Initial Balance</th>
                    <th>Total Credits</th>
                    <th>Total Debits</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                    @php
                        $accountCredits = $account->payments->where('payment_type', 'credit')->sum('amount');
                        $accountDebits = $account->payments->where('payment_type', 'debit')->sum('amount');
                        $accountBalance = $accountCredits - $accountDebits;
                    @endphp
                    <tr>
                        <td>{{ $account->account_no }}</td>
                        <td>{{ $account->name }}</td>
                        <td>{{ number_format($account->initial_balance, 2) }}</td>
                        <td>{{ number_format($accountCredits, 2) }}</td>
                        <td>{{ number_format($accountDebits, 2) }}</td>
                        <td>{{ number_format($account->calculated_balance, 2) }}</td>
                    </tr>
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
        $('#account-listings').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': true,
            'info': true,
            'autoWidth': true,
            'order': [[0, 'desc']] // Sorts by first column (Tax Date) in descending order
        });
    });

$(document).ready(function () {
    
 
        function generateReport(e, action) {
            e.preventDefault();  
      
            var token = $('meta[name="csrf-token"]').attr('content');

            $('#loader').show();

            $.ajax({
                url: '{{ route('balanceSheet.indexJSON') }}',
                type: 'GET',
                data: {
                    _token: token,
                },
                success: function (response) {
                    $('#loader').hide();
                    if (response.accounts && response.accounts.length > 0) {
                        let accounts = response.accounts;
                        let totalCredits = response.totalCredits || 0;
                        let totalDebits = response.totalDebits || 0;
                        let totalBalance = response.totalBalance || 0;

                        var formattedTime = new Date().toLocaleString();  // Format the current time

                        // Start constructing the table for account balance sheet
                        var tableHtml = '<div id="pdf-content" class="table-responsive mt-4">';
                        tableHtml += '<h3 class="text-center mb-4"><strong>Account Balance Sheet</strong></h3>';
                        tableHtml += '<p class="text-center mb-4">Report generated on: ' + formattedTime + '</p>';
                        tableHtml += '<table class="table table-bordered table-striped">';
                        tableHtml += '<thead class="thead-dark">';
                        tableHtml += '<tr>';
                        tableHtml += '<th>Account No</th>';
                        tableHtml += '<th>Name</th>';
                        tableHtml += '<th>Initial Balance</th>';
                        tableHtml += '<th>Total Credits</th>';
                        tableHtml += '<th>Total Debits</th>';
                        tableHtml += '<th>Balance</th>';
                        tableHtml += '</tr>';
                        tableHtml += '</thead>';
                        tableHtml += '<tbody>';

                        // Loop through each account and display the data in the table
                        accounts.forEach(account => {
                            tableHtml += '<tr>';
                            tableHtml += '<td>' + (account.account_no || '-') + '</td>';
                            tableHtml += '<td>' + (account.name || '-') + '</td>';
                            tableHtml += '<td>' + (account.initial_balance || '0.00') + '</td>';
                            tableHtml += '<td>' + (account.total_credits || '0.00') + '</td>';
                            tableHtml += '<td>' + (account.total_debits || '0.00') + '</td>';
                            tableHtml += '<td>' + (account.calculated_balance || '0.00') + '</td>';
                            tableHtml += '</tr>';
                        });

                        tableHtml += '</tbody>';
                        tableHtml += '</table>';
                        tableHtml += '<p class="text-end"><strong>Total Credits: ' + totalCredits + '</strong></p>';
                        tableHtml += '<p class="text-end"><strong>Total Debits: ' + totalDebits + '</strong></p>';
                        tableHtml += '<p class="text-end"><strong>Total Balance: ' + totalBalance + '</strong></p>';
                        tableHtml += '</div>';

                        // Check the action for PDF generation or print
                        if (action === 'pdf') {
                            $('#balancesTableContainer').html(tableHtml);  // Update the table container
                            var element = document.getElementById('balancesTableContainer');
                            html2pdf(element, {
                                margin: 15,
                                filename: 'Account_Balance_Sheet.pdf',
                                image: { type: 'jpeg', quality: 0.98 },
                                html2canvas: { scale: 2 },
                                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                            });

                            setTimeout(function () {
                                $('#balancesTableContainer').hide();  // Hide after PDF generation
                            }, 2000);
                        } else if (action === 'print') {
                            var printWindow = window.open('', '', 'height=600,width=800');
                            printWindow.document.write('<html><head><title>Account Balance Sheet</title>');
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
                        alert('No account data found.');
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
