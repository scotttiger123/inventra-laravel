@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="form-border">
        <div class="row">
            <!-- Sales -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($saleTotalNetAmount, 2) }}</h3>
                        <p>Sales</p>
                    </div>
                    <div class="icon" style="color:#333;">
                        <i class="ion ion-cash"></i>
                    </div>
                    
                </div>
            </div>

            <!-- Purchases -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($purchaseTotalNetAmount, 2) }}</h3>
                        <p>Purchases</p>
                    </div>
                    <div class="icon" style="color:#333;">
                        <i class="ion ion-pricetag"></i>
                    </div>
                    
                </div>
            </div>

            <!-- Paid -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($totalCredit, 2) }}</h3>
                        <p>Credit</p>
                    </div>
                    <div class="icon" style="color:#333;">
                        <i class="ion ion-checkmark"></i>
                    </div>
                    
                </div>
            </div>

            <!-- Amount Due -->
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ number_format($totalDebit, 2) }}</h3>
                        <p>Debit</p>
                    </div>
                    <div class="icon" style="color:#333;">
                        <i class="ion ion-clock"></i>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div id="chart-container" style="width:100%; height:600px; margin-top:20px;"></div>
 
            </div>
            <div class="col-lg-6">
              <h3 id="chart-heading">Lowest Stock Product</h3>
                <div id="chartdiv" style="width:100%; height:600px; margin-top:20px;"></div>
            </div>
            <div class="col-lg-6">
              <h3 id="chart-heading">Top Selling Product</h3>
                <div id="chartTopSelling" style="width:100%; height:600px; margin-top:20px;"></div>
            </div>
        </div>
        
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>


















document.addEventListener("DOMContentLoaded", function () {












  


  am5.ready(function () {
    var topSellingProducts = @json($topSellingProducts);

    const topProducts = topSellingProducts.sort((a, b) => b.total_sold - a.total_sold).slice(0, 5);

    const chartData = topProducts.map(product => ({
        category: product.product_name,
        value: product.total_sold
    }));

    var root = am5.Root.new("chartTopSelling");

    root.setThemes([am5themes_Animated.new(root)]);
    root.container.set("layout", root.verticalLayout);

    var chartContainer = root.container.children.push(
        am5.Container.new(root, {
            layout: root.horizontalLayout,
            width: am5.p100,
            height: am5.p100
        })
    );

    var chart = chartContainer.children.push(
        am5percent.PieChart.new(root, {
            endAngle: 270,
            innerRadius: am5.percent(60)
        })
    );

    var series = chart.series.push(
        am5percent.PieSeries.new(root, {
            valueField: "value",
            categoryField: "category",
            endAngle: 270,
            alignLabels: false
        })
    );

    series.children.push(
        am5.Label.new(root, {
            centerX: am5.percent(50),
            centerY: am5.percent(50),
            text: "{valueSum}",
            populateText: true,
            fontSize: "1.5em"
        })
    );

    series.slices.template.setAll({
        cornerRadius: 8
    });

    series.states.create("hidden", {
        endAngle: -90
    });

    series.labels.template.setAll({
        textType: "circular",
        text: "{category}: {value}"
    });

    series.data.setAll(chartData);

    series.slices.template.adapters.add("tooltipText", function (tooltipText, target) {
        return target.dataItem.get("category") + ": " + target.dataItem.get("value") + " units sold";
    });

    var colors = ["#3357FF", "#F39C12", "#8E44AD", "#27AE60", "#E74C3C"];
    series.slices.template.adapters.add("fill", function (fill, target) {
        var index = target.dataItem.index;
        return am5.color(colors[index % colors.length]);
    });

    var legend = root.container.children.push(
        am5.Legend.new(root, {
            x: am5.percent(50),
            centerX: am5.percent(50),
            y: am5.percent(0)
        })
    );

    legend.data.setAll(series.dataItems);

    series.appear(1000, 100);
});










  
    am5.ready(function () {
        var productStock = @json($productStock);

        const productsWithStock = productStock.filter(product => product.current_stock > 0);

        const lowestStockProducts = productsWithStock.sort((a, b) => a.current_stock - b.current_stock).slice(0, 5);

        const chartData = lowestStockProducts.map(product => ({
            category: product.product_name,
            value: product.current_stock
        }));

        var root = am5.Root.new("chartdiv");

        root.setThemes([am5themes_Animated.new(root)]);
        root.container.set("layout", root.verticalLayout);

        var chartContainer = root.container.children.push(
            am5.Container.new(root, {
                layout: root.horizontalLayout,
                width: am5.p100,
                height: am5.p100
            })
        );

        var chart = chartContainer.children.push(
            am5percent.PieChart.new(root, {
                endAngle: 270,
                innerRadius: am5.percent(60)
            })
        );

        var series = chart.series.push(
            am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category",
                endAngle: 270,
                alignLabels: false
            })
        );

        series.children.push(
            am5.Label.new(root, {
                centerX: am5.percent(50),
                centerY: am5.percent(50),
                text: "{valueSum}",
                populateText: true,
                fontSize: "1.5em"
            })
        );

        series.slices.template.setAll({
            cornerRadius: 8
        });

        series.states.create("hidden", {
            endAngle: -90
        });

        series.labels.template.setAll({
            textType: "circular",
            text: "{category}: {value}"
        });

        series.data.setAll(chartData);

        series.slices.template.adapters.add("tooltipText", function (tooltipText, target) {
            return target.dataItem.get("category") + ": " + target.dataItem.get("value") + " units";
        });

        // Set custom colors for each slice
        var colors = ["#fffff", "#fffffqq", "#3357FF", "#F39C12", "#8E44AD"];
        series.slices.template.adapters.add("fill", function (fill, target) {
            var index = target.dataItem.index;
            return am5.color(colors[index % colors.length]); // Loop over colors if more than 5 slices
        });

        var legend = root.container.children.push(
            am5.Legend.new(root, {
                x: am5.percent(50),
                centerX: am5.percent(50),
                y: am5.percent(0) // Position the legend at the top
            })
        );

        legend.data.setAll(series.dataItems);

        series.appear(1000, 100);
    });
});




am4core.ready(function() {
    am4core.useTheme(am4themes_animated);

    var dynamicData = @json($formattedDailyPayments);
    var lastTenDaysData = dynamicData.slice(-30);
    var chart = am4core.create("chart-container", am4charts.XYChart3D);
    chart.data = lastTenDaysData;

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "date";
    categoryAxis.renderer.minGridDistance = 20;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.title.text = "Amount";

    function createSeries(field, name, color) {
        var series = chart.series.push(new am4charts.ColumnSeries3D());
        series.dataFields.categoryX = "date";
        series.dataFields.valueY = field;
        series.name = name;
        series.columns.template.fill = am4core.color(color);
        series.columns.template.stroke = am4core.color(color);
        series.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";
        return series;
    }

    
    createSeries("totalCredit", "Credit", "#00a65a"); // Dark Green
    createSeries("totalDebit", "Debit", "#d5e8d4");   // Light Yellow


    chart.cursor = new am4charts.XYCursor();

    chart.legend = new am4charts.Legend();
    chart.legend.position = "top";

    var title = chart.titles.create();
    title.text = "Months Credit & Debit";
    title.fontSize = 20;
    title.marginBottom = 20;

    chart.padding(40, 40, 40, 40);
});

</script>
@endsection
