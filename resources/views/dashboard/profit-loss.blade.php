@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">

        <!-- Total Summary Section -->
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

            <!-- Purchase Box -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey"> 
                    <div class="inner">
                        <h3 style="color: white;">{{$currencySymbol }}{{ $purchaseTotalNetAmount}}</h3>
                        <p style="color: white;">Purchases</p>
                    </div>
                    <div class="icon" style="color: #b13c2e;"> <!-- Red -->
                        <i class="ion ion-ios-basket"></i>
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

            <!-- Purchase Return Box -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;">{{$currencySymbol }}{{ $purchaseReturnTotalNetAmount}}</h3>
                        <p style="color: white;">Purchase Return</p>
                    </div>
                    <div class="icon" style="color: #008548;"> <!-- Green -->
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
                        <h3 style="color: white;">$40,000.00</h3>
                        <p style="color: white;">Income</p>
                    </div>
                    <div class="icon" style="color: #008548;"> <!-- Green -->
                        <i class="ion ion-ios-cash"></i>
                    </div>
                </div>
            </div>

            <!-- Expense Box -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;">$20,000.00</h3>
                        <p style="color: white;">Expenses</p>
                    </div>
                    <div class="icon" style="color: #b13c2e;"> <!-- Red -->
                        <i class="ion ion-ios-wallet"></i>
                    </div>
                </div>
            </div>

            <!-- Revenue Average Profit -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;">$25,000.00</h3>
                        <p style="color: white;">Revenue Avg. Profit</p>
                    </div>
                    <div class="icon" style="color: #008548;"> <!-- Green -->
                        <i class="ion ion-ios-trending-up"></i>
                    </div>
                </div>
            </div>

            <!-- LIFO Profit -->
            <div class="col-lg-3 col-xs-12">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3 style="color: white;">$15,000.00</h3>
                        <p style="color: white;">LIFO Profit</p>
                    </div>
                    <div class="icon" style="color: #008548;"> <!-- Green -->
                        <i class="ion ion-ios-stats"></i>
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
                        <h3 style="color: white;">$50,000.00</h3>
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
                        <h3 style="color: white;">$30,000.00</h3>
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
                        <h3 style="color: white;">$20,000.00</h3>
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
@endsection
