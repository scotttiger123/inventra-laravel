@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="row">
            <!-- Sales -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($sales, 2) }}</h3>
                        <p>Sales</p>
                    </div>
                    <div class="icon" style="color:#333;">
                        <i class="ion ion-cash"></i>
                    </div>
                    <a href="#" class="small-box-footer" style="color:#555;">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Purchases -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($totalPurchases, 2) }}</h3>
                        <p>Purchases</p>
                    </div>
                    <div class="icon" style="color:#333;">
                        <i class="ion ion-pricetag"></i>
                    </div>
                    <a href="#" class="small-box-footer" style="color:#555;">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Paid -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($paid, 2) }}</h3>
                        <p>Paid</p>
                    </div>
                    <div class="icon" style="color:#333;">
                        <i class="ion ion-checkmark"></i>
                    </div>
                    <a href="#" class="small-box-footer" style="color:#555;">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Amount Due -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($amountDue, 2) }}</h3>
                        <p>Amount Due</p>
                    </div>
                    <div class="icon" style="color:#333;">
                        <i class="ion ion-clock"></i>
                    </div>
                    <a href="#" class="small-box-footer" style="color:#555;">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        
        <!-- AmCharts Chart -->
        <div class="row">
            <div class="col-lg-12">
                <div id="chart-container" style="width:100%; height:600px; margin-top:20px;"></div>
 
            </div>
        </div>
    </div>
</div>


<!-- Highcharts and amCharts Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script>
    // Fetch dynamic data (this can be from an API or backend data)
    const dynamicData = [
        { month: "January", sales: 15000, revenue: 25000, orders: 120 },
        { month: "February", sales: 18000, revenue: 27000, orders: 140 },
        { month: "March", sales: 20000, revenue: 30000, orders: 160 },
        { month: "April", sales: 22000, revenue: 32000, orders: 180 },
        { month: "May", sales: 24000, revenue: 35000, orders: 200 },
        { month: "June", sales: 26000, revenue: 40000, orders: 220 }
    ];

    // Create the 3D Bar Chart
    am4core.ready(function() {
        am4core.useTheme(am4themes_animated);

        var chart = am4core.create("chart-container", am4charts.XYChart3D);
        chart.data = dynamicData;

        // X-Axis (Months)
        var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "month";
        categoryAxis.renderer.minGridDistance = 20;

        // Y-Axis (Values)
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Values";

        // Create a function to generate series dynamically based on data fields
        function createSeries(field, name, color) {
            var series = chart.series.push(new am4charts.ColumnSeries3D());
            series.dataFields.categoryX = "month";
            series.dataFields.valueY = field;
            series.name = name;
            series.columns.template.fill = am4core.color(color);
            series.columns.template.stroke = am4core.color(color);
            series.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";
            return series;
        }

        // Adding series for sales, revenue, and orders
        createSeries("sales", "Sales", "#00000");
        createSeries("revenue", "Revenue", "#00a65a");
        createSeries("orders", "Orders", "#FFC107");

        // Add a cursor
        chart.cursor = new am4charts.XYCursor();

        // Add a legend
        chart.legend = new am4charts.Legend();
        chart.legend.position = "top";

        // Add a title to the chart
        var title = chart.titles.create();
        title.text = "Monthly Sales, Revenue, and Orders";
        title.fontSize = 20;
        title.marginBottom = 20;

        // Add padding to the chart
        chart.padding(40, 40, 40, 40);
    });
</script>
@endsection
