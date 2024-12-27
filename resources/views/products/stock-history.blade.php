@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="col-lg-6 col-xs-6">
            <div class="small-box bg-grey">
                <div class="inner">
                    <h3>{{ $product->product_name }} </h3>
                    <p>Product </p>
                </div>
                <div class="icon" style="color:#222D32">
                    <i class="ion ion-ios-cart"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-6">
            <div class="small-box bg-grey">
                <div class="inner">
                    <h3>{{ $remainingStock  }}   {{ $product->uom->name ?? 'N/A' }} </h3>
                    <p>Stock </p>
                </div>
                <div class="icon" style="color:#222D32">
                    <i class="ion ion-ios-cart"></i>
                </div>
            </div>
        </div>

        <h3 class="box-title custom-title">Stock History</h3>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="stockHistoryTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="sales-history-tab" data-toggle="tab" href="#sales-history" role="tab" aria-controls="sales-history" aria-selected="true">Sales History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="purchase-history-tab" data-toggle="tab" href="#purchase-history" role="tab" aria-controls="purchase-history" aria-selected="false">Purchase History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sales-return-tab" data-toggle="tab" href="#sales-return" role="tab" aria-controls="sales-return" aria-selected="false">Sales Return</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="purchase-return-tab" data-toggle="tab" href="#purchase-return" role="tab" aria-controls="purchase-return" aria-selected="false">Purchase Return</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="transfer-history-tab" data-toggle="tab" href="#transfer-history" role="tab" aria-controls="transfer-history" aria-selected="false">Transfer</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-4" id="stockHistoryTabsContent">
            <!-- Sales History Tab -->
            <div class="tab-pane fade show active" id="sales-history" role="tabpanel" aria-labelledby="sales-history-tab">
                <h4>Sales History</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Quantity Sold</th>
                            <th>Sale Price</th>
                            <th>Total Amount</th>
                            <th>Exit Warehouse</th>
                            <th>Sale Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $salesTotalQty = 0; @endphp
                        @forelse($salesHistory as $sale)
                            <tr>
                                <td>{{ $sale->order->custom_order_id ?? 'N/A' }}</td>
                                <td>{{ $sale->quantity }}</td>
                                <td>{{ number_format($sale->unit_price, 2) }}</td>
                                <td>{{ number_format($sale->quantity * $sale->unit_price, 2) }}</td>
                                <td>{{ $sale->warehouse->name ?? 'N/A' }}</td>
                                <td>{{ $sale->order->order_date ?? 'N/A' }}</td>
                            </tr>
                            @php $salesTotalQty += $sale->quantity; @endphp
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No sales history available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" class="text-right">Total:</th>
                            <th>{{ $salesTotalQty }}</th>
                            <th colspan="4"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Purchase History Tab -->
            <div class="tab-pane fade" id="purchase-history" role="tabpanel" aria-labelledby="purchase-history-tab">
                <h4>Purchase History</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Purchase ID</th>
                            <th>Quantity Purchased</th>
                            <th>Purchase Price</th>
                            <th>Total Cost</th>
                            <th>Inward Warehouse</th>
                            <th>Purchase Date</th>
                        </tr>
                    </thead>
                        <tbody>
                        @php $purchaseTotalQty = 0; @endphp
                        @forelse($purchaseHistory as $purchase)
                            <tr>
                                <td>{{ $purchase->purchase->custom_purchase_id ?? 'N/A' }}</td>
                                <td>{{ $purchase->quantity }}</td>
                                <td>{{ number_format($purchase->rate, 2) }}</td>
                                <td>{{ number_format($purchase->quantity * $purchase->rate, 2) }}</td>
                                <td>{{ $purchase->warehouse->name ?? 'N/A' }}</td>
                                <td>{{ $purchase->purchase->purchase_date ?? 'N/A' }}</td>
                            </tr>
                            @php $purchaseTotalQty += $purchase->quantity; @endphp
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No purchase history available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="1" class="text-right">Total:</th>
                            <th>{{ $purchaseTotalQty }}</th>
                            <th colspan="4"></th>
                        </tr>
                    </tfoot>

                </table>
            </div>

            <!-- Sales Return Tab -->
            <div class="tab-pane fade" id="sales-return" role="tabpanel" aria-labelledby="sales-return-tab">
                <h4>Sales Return</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Return ID</th>
                            <th>Quantity Returned</th>
                            <th>Return Price</th>
                            <th>Total Return Amount</th>
                            <th>Warehouse</th>
                            <th>Return Date</th>
                        </tr>
                    </thead>
                       @php $salesReturnTotalQty = 0; @endphp
                        @forelse($salesReturnHistory as $return)
                            <tr>
                                <td>{{ $return->order->custom_order_id ?? 'N/A' }}</td>
                                <td>{{ $return->quantity }}</td>
                                <td>{{ number_format($return->unit_price, 2) }}</td>
                                <td>{{ number_format($return->quantity * $return->unit_price, 2) }}</td>
                                <td>{{ $return->warehouse->name ?? 'N/A' }}</td>
                                <td>{{ $return->order->order_date ?? 'N/A' }}</td>
                            </tr>
                            @php $salesReturnTotalQty += $return->quantity; @endphp
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No sales return history available.</td>
                            </tr>
                        @endforelse
                        <tfoot>
                            <tr>
                                <th colspan="1" class="text-right">Total:</th>
                                <th>{{ $salesReturnTotalQty }}</th>
                                <th colspan="4"></th>
                            </tr>
                        </tfoot>

                </table>
            </div>

            <!-- Purchase Return Tab -->
            <div class="tab-pane fade" id="purchase-return" role="tabpanel" aria-labelledby="purchase-return-tab">
                <h4>Purchase Return</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Return ID</th>
                            <th>Quantity Returned</th>
                            <th>Return Price</th>
                            <th>Total Return Cost</th>
                            <th>Warehouse</th>
                            <th>Return Date</th>
                        </tr>
                    </thead>
                    @php $transferTotalQty = 0; @endphp
                        @forelse($purchaseReturnHistory as $purchase)
                            <tr>
                                <td>{{ $purchase->purchase->custom_purchase_id ?? 'N/A' }}</td>
                                <td>{{ $purchase->quantity }}</td>
                                <td>{{ number_format($purchase->rate, 2) }}</td>
                                <td>{{ number_format($purchase->quantity * $purchase->rate, 2) }}</td>
                                <td>{{ $purchase->warehouse->name ?? 'N/A' }}</td>
                                <td>{{ $purchase->purchase->purchase_date ?? 'N/A' }}</td>
                            </tr>
                            @php $transferTotalQty += $purchase->quantity; @endphp
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No purchase history available.</td>
                            </tr>
                        @endforelse
                        <tfoot>
                            <tr>
                                <th colspan="1" class="text-right">Total:</th>
                                <th>{{ $transferTotalQty }}</th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>

                </table>
            </div>
            <div class="tab-pane fade" id="transfer-history" role="tabpanel" aria-labelledby="transfer-history-tab">
                <h4>Warehouse Transfer History</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Quantity Transferred</th>
                            <th>From Warehouse</th>
                            <th>To Warehouse</th>
                            <th>Transfer Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfersHistory as $transfer)
                            <tr>
                                <td>{{ $transfer->id }}</td>
                                <td>{{ $transfer->quantity }}</td>
                                <td>{{ $transfer->fromWarehouse->name ?? 'N/A' }}</td>
                                <td>{{ $transfer->toWarehouse->name ?? 'N/A' }}</td>
                                <td>{{ $transfer->created_at ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No transfer history available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('stock-report-view') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Product List
            </a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function() {
    $('#sales-history-tab').click();

    // Show Sales History when Sales History Tab is clicked
    $('#sales-history-tab').click(function() {
        $('#sales-history').addClass('show active');
        $('#purchase-history').removeClass('show active');
        $('#sales-return').removeClass('show active');
        $('#purchase-return').removeClass('show active');
        $('#transfer-history').removeClass('show active');
    });

    // Show Purchase History when Purchase History Tab is clicked
    $('#purchase-history-tab').click(function() {
        $('#purchase-history').addClass('show active');
        $('#sales-history').removeClass('show active');
        $('#sales-return').removeClass('show active');
        $('#purchase-return').removeClass('show active');
        $('#transfer-history').removeClass('show active');
    });

    // Show Sales Return when Sales Return Tab is clicked
    $('#sales-return-tab').click(function() {
        $('#sales-return').addClass('show active');
        $('#sales-history').removeClass('show active');
        $('#purchase-history').removeClass('show active');
        $('#purchase-return').removeClass('show active');
        $('#transfer-history').removeClass('show active');
    });

    // Show Purchase Return when Purchase Return Tab is clicked
    $('#purchase-return-tab').click(function() {
        $('#purchase-return').addClass('show active');
        $('#sales-history').removeClass('show active');
        $('#purchase-history').removeClass('show active');
        $('#sales-return').removeClass('show active');
        $('#transfer-history').removeClass('show active');
    });

    // Show Transfer History when Transfer History Tab is clicked
    $('#transfer-history-tab').click(function() {
        $('#transfer-history').addClass('show active');
        $('#sales-history').removeClass('show active');
        $('#purchase-history').removeClass('show active');
        $('#sales-return').removeClass('show active');
        $('#purchase-return').removeClass('show active');
    });
});
</script>

@endsection
