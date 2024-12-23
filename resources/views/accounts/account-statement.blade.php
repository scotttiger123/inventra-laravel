@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="row">
            <!-- Total Credits -->
            <div class="col-md-6">
                <div class="small-box">
                    <div class="inner">
                        <h3> {{ $currencySymbol }}{{ number_format($totalCredit, 2) }}</h3> <!-- Display currency symbol -->
                        <p>Total Credits</p>
                    </div>
                    <div class="icon" style="color:#008548">
                        <i class="ion ion-plus"></i>
                    </div>
                </div>
            </div>
            <!-- Total Debits -->
            <div class="col-md-6">
                <div class="small-box">
                    <div class="inner">
                        <h3> {{ $currencySymbol }}{{ number_format($totalDebit, 2) }}</h3> <!-- Display currency symbol -->
                        <p>Total Debits</p>
                    </div>
                    <div class="icon" style="color:#d32f2f">
                        <i class="ion ion-minus"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-header with-border">
            <h3 class="box-title custom-title">Account Statement</h3>
                    </div>
        <div style="float: right;">
            <div class="d-flex justify-content-end mt-4">
                <button id="generate-pdf" class="btn btn-secondary mt-4">  <i class="fa fa-file"></i> PDF</button>
                <button id="print-pdf" class="btn btn-secondary mt-4"><i class="fa fa-print"></i> Print</button>
            </div>  
        </div>     

        <div class="form-group row">
        <div class="col-md-2">
        <form method="GET" action="{{ route('accountStatement.index') }}">
            <label for="account_id">Select an Account:</label>
            <select name="account_id" id="account_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Choose an Account --</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}" 
                        {{ request('account_id') == $account->id ? 'selected' : '' }}>
                        {{ $account->name }}
                    </option>
                @endforeach
            </select>
        </form>
        </div>
        </div>

        <!-- Account Statement Section -->
        @if($selectedAccount)
            <h3>Statement for {{ $selectedAccount->name }}</h3>

            <!-- Display Initial Balance -->
            <div class="row">
                <div class="col-md-6">
                    <div class="small-box">
                        <div class="inner">
                            <h3>{{ $currencySymbol }}{{ number_format($initialBalance, 2) }} </h3> <!-- Display currency symbol -->
                            <p>Initial Balance</p>
                        </div>
                        <div class="icon" style="color:#00796b">
                            <i class="ion ion-cash"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Table -->
            <table id="account-listings" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Payment ID</th>
                        
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Balance</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @php
                        $balance = $initialBalance; // Start balance with the initial balance
                    @endphp
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->payment_date->format('Y-m-d') }}</td>
                            <td>{{ $transaction->id ?? 'N/A' }}</td> <!-- Display Payment ID -->
                            <td>
                                @if($transaction->payment_type === 'credit')
                                {{ $currencySymbol }} {{ number_format($transaction->amount, 2) }} 
                                    @php
                                        $balance += $transaction->amount; // Add to balance if credit
                                    @endphp
                                @endif
                            </td>
                            <td>
                                @if($transaction->payment_type === 'debit')
                                {{ $currencySymbol }} {{ number_format($transaction->amount, 2) }} 
                                    @php
                                        $balance -= $transaction->amount; // Subtract from balance if debit
                                    @endphp
                                @endif
                            </td>
                            <td>{{ $currencySymbol }} {{ number_format($balance, 2) }} </td> 
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No transactions found for this account.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <p>Please select an account to view the statement.</p>
        @endif
    </div>
</div>
<div id = "accountStatementTableContainer"></div>
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
            'order': [[0, 'desc']] // Sort by Date in descending order
        });
    });

    $(document).ready(function () {

        function generateReport(e, action) {
    e.preventDefault();

    var token = $('meta[name="csrf-token"]').attr('content');
    var accountId = $('#account_id').val();
    $('#loader').show();

    $.ajax({
        url: '{{ route('accountStatement.indexJSON', '') }}/' + accountId,
        type: 'GET',
        data: {
            _token: token,
            account_id: accountId,
        },
        success: function (response) {
            $('#loader').hide();
            if (response.transactions && response.transactions.length > 0) {
                let transactions = response.transactions;
                let selectedAccount = response.selectedAccount || {};
                let totalCredits = Number(response.totalCredits) || 0; // Ensure it's a number
                let totalDebits = Number(response.totalDebits) || 0; // Ensure it's a number
                let initialBalance = Number(selectedAccount.initial_balance) || 0; // Ensure it's a number
                let totalBalance = Number(response.totalBalance) || initialBalance; // Ensure it's a number
                let currencySymbol = response.currencySymbol || '$'; // Assuming currency symbol is returned from the backend
                let balance = initialBalance; // Start with initial balance

                var formattedTime = new Date().toLocaleString();

                var tableHtml = '<div id="pdf-content" class="table-responsive mt-4">';
                tableHtml += '<h3 class="text-center mb-4"><strong>Account Statement</strong></h3>';
                tableHtml += '<p class="text-center mb-4">Report generated on: ' + formattedTime + '</p>';

                // Account Information (selected account details)
                tableHtml += '<h4><strong>Account Details:</strong></h4>';
                tableHtml += '<p><strong>Account No:</strong> ' + (selectedAccount.account_no || '-') + '</p>';
                tableHtml += '<p><strong>Account Name:</strong> ' + (selectedAccount.name || '-') + '</p>';
                tableHtml += '<p><strong>Initial Balance:</strong> ' + currencySymbol + ' ' + initialBalance.toFixed(2) + '</p>';
                tableHtml += '<p><strong>Total Credits:</strong> ' + currencySymbol + ' ' + totalCredits.toFixed(2) + '</p>';
                tableHtml += '<p><strong>Total Debits:</strong> ' + currencySymbol + ' ' + totalDebits.toFixed(2) + '</p>';
                tableHtml += '<p><strong>Total Balance:</strong> ' + currencySymbol + ' ' + totalBalance.toFixed(2) + '</p>';

                // Transactions Table
                tableHtml += '<h4 class="mt-4"><strong>Transaction History:</strong></h4>';
                tableHtml += '<table class="table table-bordered table-striped">';
                tableHtml += '<thead class="thead-dark">';
                tableHtml += '<tr>';
                tableHtml += '<th>Payment Date</th>';
                tableHtml += '<th>Payment ID</th>';
                tableHtml += '<th>Credit</th>';
                tableHtml += '<th>Debit</th>';
                tableHtml += '<th>Balance</th>';
                tableHtml += '</tr>';
                tableHtml += '</thead>';
                tableHtml += '<tbody>';

                transactions.forEach(transaction => {
                    // Display transaction date and ID
                    tableHtml += '<tr>';
                    tableHtml += '<td>' + new Date(transaction.payment_date).toLocaleDateString() + '</td>';
                    tableHtml += '<td>' + (transaction.id || 'N/A') + '</td>';

                    // Handling Credit and Debit transactions
                    if (transaction.payment_type === 'credit') {
                        tableHtml += '<td>' + currencySymbol + ' ' + Number(transaction.amount).toFixed(2) + '</td>';
                        balance += parseFloat(transaction.amount); // Update balance on credit
                        tableHtml += '<td>-</td>';
                    } else {
                        tableHtml += '<td>-</td>';
                        tableHtml += '<td>' + currencySymbol + ' ' + Number(transaction.amount).toFixed(2) + '</td>';
                        balance -= parseFloat(transaction.amount); // Update balance on debit
                    }

                    tableHtml += '<td>' + currencySymbol + ' ' + balance.toFixed(2) + '</td>'; // Show current balance
                    tableHtml += '</tr>';
                });

                tableHtml += '</tbody>';
                tableHtml += '</table>';

                // Total Summary
                tableHtml += '<p class="text-end"><strong>Total Credits: ' + currencySymbol + ' ' + totalCredits.toFixed(2) + '</strong></p>';
                tableHtml += '<p class="text-end"><strong>Total Debits: ' + currencySymbol + ' ' + totalDebits.toFixed(2) + '</strong></p>';
                tableHtml += '<p class="text-end"><strong>Total Balance: ' + currencySymbol + ' ' + totalBalance.toFixed(2) + '</strong></p>';
                tableHtml += '</div>';

                // If action is 'pdf', generate PDF
                if (action === 'pdf') {
                    // Clone the HTML content to avoid modifying the original
                    var clonedContent = $(tableHtml).clone();

                    // Create a temporary container to hold the cloned content
                    var tempContainer = $('<div>').append(clonedContent);
                    var element = tempContainer[0]; // Get the DOM element of the cloned content

                    // Generate PDF from the cloned content
                    html2pdf(element, {
                        margin: 15,
                        filename: 'Account_Statement.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    });

                } else if (action === 'print') {
                    var printWindow = window.open('', '', 'height=600,width=800');
                    printWindow.document.write('<html><head><title>Account Statement</title>');
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
                alert('No transactions found for the selected account.');
            }
        },
        error: function (error) {
            $('#loader').hide();
            console.error('Error:', error);
            alert('Failed to generate the report.');
        }
    });
}

$('#generate-pdf').on('click', function (e) {
    generateReport(e, 'pdf');
});

$('#print-pdf').on('click', function (e) {
    generateReport(e, 'print');
});

});

</script>
@endsection
