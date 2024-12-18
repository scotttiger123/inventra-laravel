@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">

    <form method="GET" action="{{ route('profit.loss') }}">
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
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success mt-4" style="margin-top:24px">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                </div>
            </div>   
        </form>

        <div class="row">

            <!-- Sales Box -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;">{{$currencySymbol }}{{ $saleTotalNetAmount}}</h3>
                        <p style="color: white;">Sales</p>
                    </div>
                    <div class="icon" style="color: #008548;"> <!-- Green -->
                        <i class="ion ion-ios-cart"></i>
                    </div>
                </div>
            </div>

            <!-- Sales Return Box -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;">{{$currencySymbol }}{{ $saleReturnTotalNetAmount}}</h3>
                        <p style="color: white;">Sales Return</p>
                    </div>
                    <div class="icon" style="color: #b13c2e;"> <!-- Red -->
                        <i class="ion ion-ios-undo"></i>
                    </div>
                </div>
            </div>
            <!-- Purchase Box -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey"> 
                    <div class="inner">
                        <h3 style="color: white;">{{$currencySymbol }}{{ $purchaseTotalNetAmount}}</h3>
                        <p style="color: white;">Purchases</p>
                    </div>
                    <div class="icon" style="color: #008548;"> <!-- Red -->
                        <i class="ion ion-cube"></i>
                    </div>
                </div>
            </div>


            <!-- Purchase Return Box -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;">{{$currencySymbol }}{{ $purchaseReturnTotalNetAmount}}</h3>
                        <p style="color: white;">Purchase Return</p>
                    </div>
                    <div class="icon" style="color: #b13c2e;"> <!-- Green -->
                        <i class="ion ion-ios-redo"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- Income and Expense Section -->
        <div class="row">

            <!-- Income Box -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;">   {{$currencySymbol }}{{ $totalIncome}} </h3>
                        <p style="color: white;">Income</p>
                    </div>
                    <div class="icon" style="color: #008548;"> <!-- Green -->
                        <i class="ion ion-cash"></i>
                    </div>
                </div>
            </div>

            <!-- Expense Box -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;"> {{$currencySymbol }}{{ $totalExpense}} </h3>
                        <p style="color: white;">Expenses</p>
                    </div>
                    <div class="icon" style="color: #b13c2e;"> <!-- Red -->
                        <i class="ion ion-card"></i>
                    </div>
                </div>
            </div>

            <!-- Revenue Average Profit -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;"> {{$currencySymbol }}{{ $totalNetProfit}} </h3>
                        <p style="color: white;">Revenue Avg. Profit</p>
                    </div>
                    <div class="icon" style="color: #008548;"> <!-- Green -->
                        <i class="ion ion-arrow-graph-up-left"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;">{{ round(($totalNetProfit / $saleTotalNetAmount) * 100, 2) }}%</h3>
                        <p style="color: white;">Profit Margin</p>
                    </div>
                    <div class="icon" style="color: #008548;"> <!-- Green -->
                        <i class="ion ion-ios-pie"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- Payments Section -->
        <div class="row">

            <!-- Payments Received -->
            <div class="col-lg-4 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;">{{$currencySymbol }}{{ $totalCredit}}</h3>
                        <p style="color: white;">Payments Received</p>
                    </div>
                    <div class="icon" style="color: #008548;"> <!-- Green -->
                        <i class="ion ion-ios-checkmark"></i>
                    </div>
                </div>
            </div>

            <!-- Payments Sent -->
            <div class="col-lg-4 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;">{{$currencySymbol }}{{ $totalDebit}}</h3>
                        <p style="color: white;">Payments Sent</p>
                    </div>
                    <div class="icon" style="color: #b13c2e;"> <!-- Red -->
                        <i class="ion ion-ios-close"></i>
                    </div>
                </div>
            </div>

            <!-- Payments Net -->
            <div class="col-lg-4 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                    <h3 style="color: white;">{{ $currencySymbol }}{{ $totalCredit - $totalDebit }}</h3>

                        <p style="color: white;">Payments Net</p>
                    </div>
                    <div class="icon" style="color: #008548;"> <!-- Neutral -->
                        <i class="ion ion-ios-calculator"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

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
  
</script>
@endsection
