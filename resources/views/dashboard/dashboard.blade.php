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
                    <a href="#" class="small-box-footer" style="color:#555;">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                    <a href="#" class="small-box-footer" style="color:#555;">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                    <a href="#" class="small-box-footer" style="color:#555;">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                    <a href="#" class="small-box-footer" style="color:#555;">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div id="chartdiv" style="width:100%; height:600px; margin-top:20px;"></div>
 
            </div>
        </div>
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
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>
  
  am5.ready(function() {
  
  // Create root element
  var root = am5.Root.new("chartdiv");

  // Set themes
  root.setThemes([am5themes_Animated.new(root)]);
  root.container.set("layout", root.verticalLayout);

  // Create container to hold charts
  var chartContainer = root.container.children.push(am5.Container.new(root, {
    layout: root.horizontalLayout,
    width: am5.p100,
    height: am5.p100
  }));

  // Create the 1st chart (Top Sales)
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

  // Add a label to display the total sum of values without percentage symbol
  series.children.push(am5.Label.new(root, {
    centerX: am5.percent(50),
    centerY: am5.percent(50),
    text: "Top Sales: {valueSum}",
    populateText: true,
    fontSize: "1.5em"
  }));

  series.slices.template.setAll({
    cornerRadius: 8
  });

  series.states.create("hidden", {
    endAngle: -90
  });

  // Disable percentage for labels inside the pie chart
  series.labels.template.setAll({
    textType: "circular",
    text: "{category}: {value}" // Use raw value, without percentage symbol
  });

  // Create the 2nd chart (Low Stock)
  var chart2 = chartContainer.children.push(
    am5percent.PieChart.new(root, {
      endAngle: 270,
      innerRadius: am5.percent(60)
    })
  );

  var series2 = chart2.series.push(
    am5percent.PieSeries.new(root, {
      valueField: "value",
      categoryField: "category",
      endAngle: 270,
      alignLabels: false,
      tooltip: am5.Tooltip.new(root, {
        labelText: "{category}: {value} units" // Showing value without percentage in tooltip
      })
    })
  );

  // Add a label to display the total sum of values without percentage symbol
  series2.children.push(am5.Label.new(root, {
    centerX: am5.percent(50),
    centerY: am5.percent(50),
    text: "Low Stock: {valueSum}",
    populateText: true,
    fontSize: "1.5em"
  }));

  series2.slices.template.setAll({
    cornerRadius: 8
  });

  series2.states.create("hidden", {
    endAngle: -90
  });

  // Disable percentage for labels inside the pie chart
  series2.labels.template.setAll({
    textType: "circular",
    text: "{category}: {value}" // Use raw value, without percentage symbol
  });

  series2.labels.template.setAll({
    textType: "circular"
  });

  // Set data for Top Sales
  series.data.setAll([
    { category: "UltraClean Vacuum", value: 850 },
    { category: "SmartWatch Pro X", value: 720 },
    { category: "EcoBlend Juicer", value: 630 },
    { category: "Headphones", value: 600 },
    { category: "MaxPower Drill", value: 550 }
  ]);

  // Set data for Low Stock
  series2.data.setAll([
    { category: "EcoBlend Juicer", value: 30 },
    { category: "SmartWatch Pro X", value: 25 },
    { category: "TurboCharge Powerbank", value: 20 },
    { category: "MaxPower Drill", value: 15 },
    { category: "FlexiFit Yoga Mat", value: 2 }
  ]);

  // Remove percentage symbol by formatting value directly
  series.slices.template.adapters.add("tooltipText", function(tooltipText, target) {
    return target.dataItem.get("category") + ": " + target.dataItem.get("value") + " units"; // Display value without percentage
  });

  series2.slices.template.adapters.add("tooltipText", function(tooltipText, target) {
    return target.dataItem.get("category") + ": " + target.dataItem.get("value") + " units"; // Display value without percentage
  });

  function getSlice(dataItem, series) {
    var otherSlice;
    am5.array.each(series.dataItems, function(di) {
      if (di.get("category") === dataItem.get("category")) {
        otherSlice = di.get("slice");
      }
    });

    return otherSlice;
  }

  // Create legend
  var legend = root.container.children.push(am5.Legend.new(root, {
    x: am5.percent(50),
    centerX: am5.percent(50)
  }));

  legend.itemContainers.template.events.on("pointerover", function(ev) {
    var dataItem = ev.target.dataItem.dataContext;
    var slice = getSlice(dataItem, series2);
    slice.hover();
  });

  legend.itemContainers.template.events.on("pointerout", function(ev) {
    var dataItem = ev.target.dataItem.dataContext;
    var slice = getSlice(dataItem, series2);
    slice.unhover();
  });

  legend.itemContainers.template.on("disabled", function(disabled, target) {
    var dataItem = target.dataItem.dataContext;
    var slice = getSlice(dataItem, series2);
    if (disabled) {
      series2.hideDataItem(slice.dataItem);
    } else {
      series2.showDataItem(slice.dataItem);
    }
  });

  legend.data.setAll(series.dataItems);

  series.appear(1000, 100);

}); // end am5.ready()

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
