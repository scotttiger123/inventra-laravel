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
                    <a href="#" class="small-box-footer" style="color:black">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Discount Amount -->
            <div class="col-lg-6 col-xs-6">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>0.00</h3>
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
                        <option value="">All Customers</option>
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
                <th>Order Number</th>
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
                    <td>{{ $entry['payment_amount'] ? $currencySymbol . ' ' . number_format($entry['payment_amount'], 2) : '-' }}</td>
                    <td>{{ $entry['total_amount'] ? $currencySymbol . ' ' . number_format($entry['total_amount'], 2) : '-' }}</td>
                    <td>{{ $entry['balance'] }}</td>
                    <td>{{ $entry['entry_type'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-center mt-4">No ledger entries found for the selected filters.</p>
@endif


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
</script>
@endsection
