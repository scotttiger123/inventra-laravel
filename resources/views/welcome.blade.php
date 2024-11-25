@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="row">
            <!-- Sales -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $sales }}</h3>
                        <p>Sales</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-cash"></i>
                    </div>
                    <a href="#" class="small-box-footer" style="color:black">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Purchases -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $purchases }}</h3>
                        <p>Purchases</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-pricetag"></i>
                    </div>
                    <a href="#" class="small-box-footer" style="color:black">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Paid -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-grey">
                    <div class="inner">
                        <h3>{{ $paid }}</h3>
                        <p>Paid</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-checkmark"></i>
                    </div>
                    <a href="#" class="small-box-footer" style="color:black">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Amount Due -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $amount_due }}</h3>
                        <p>Amount Due</p>
                    </div>
                    <div class="icon" style="color:#222D32">
                        <i class="ion ion-clock"></i>
                    </div>
                    <a href="#" class="small-box-footer" style="color:black"><i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>   
</div>
@endsection
