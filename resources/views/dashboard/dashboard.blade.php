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
            <div class="col-lg-7">
                <div id="chart-container" style="width:100%; height:600px; margin-top:20px;"></div>
            </div>
            <div class="whity" style="margin-top:-2rem;padding:10px 3rem; width:10%;background-color:white; position:absolute;"> </div>
       <div class="col-lg-5">
              <h3 id="chart-heading">Lowest Stock Product</h3>
                <div id="lowStockProduct" style="width:100%; height:600px; margin-top:30px;"></div>
                <div class="whity" style="margin-top:-48rem;padding:10px 3rem; width:10%;background-color:white; position:absolute;"> </div>
            </div>
        </div>   
        <div class="row" style="padding-left:7rem">    
            <div class="col-lg-7">
                <div class="whity" style="margin-top:-10rem;padding:10px 3rem; width:20%; height: 65px; background-color:white; position:absolute; margin-left:-7rem;"> </div>
                <h3 id="chart-heading">Top Selling Product</h3>
                <div id="chartdivStock2" style="width:100%; height:600px; margin-top:30px;"></div>
 
            </div>
             <div class="whity" style="margin-top:-3rem; margin-left:60rem; padding:10px 3rem; width:20%;background-color:white; position:absolute;"> </div>
            <div class="col-lg-5">
                 <div class="whity" style="margin-top:-4rem;padding:10px 3rem; width:20%;background-color:white; position:absolute;"> </div>
                <h3 id="chart-heading">chartdivGaug</h3>
                <div id="chartdivGaug" style="width:100%; height:600px; margin-top:30px;"></div>
            </div>
        </div>
         <div class="whity" style="margin-top:-3rem; padding:10px 3rem; width:80%;height:30px; position:absolute; background-color:white;"></div> 
        <div class="row" style="padding-left:7rem;">
            <div class="col-lg-12">
                <h3 id="chart-heading">Product Inventory</h3>
                <div id="chartDivStock" style="width:100%; height:600px; margin-top:30px;"></div>
 
            </div>
        </div>
        <div class="whity" style="margin-top:-2rem; margin-left:2px; padding:10px 2rem; width:150px; background-color:white; position:absolute;"> </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>

<script>




document.addEventListener("DOMContentLoaded", function () {







    am5.ready(function() {

var root = am5.Root.new("chartdivStock2");

root.setThemes([am5themes_Animated.new(root)]);

var topSellingProducts = @json($topSellingProducts);

var data = topSellingProducts.map(function (product, index) {
    if (product && product.product_name && product.total_sold !== undefined) {
        return {
            name: product.product_name,  // Safely access product_name
            steps: parseInt(product.total_sold, 10),
            pictureSettings: {
                src: product.image_path ? `{{ asset('storage/') }}/${product.image_path}` : "{{ asset('dist/img/no-product-img.png') }}"
            }
        };
    } else {
        console.error("Invalid product data at index:", index, product);  // Log invalid product data
        return null;  
    }
}).filter(Boolean);  




var chart = root.container.children.push(
am5xy.XYChart.new(root, {
panX: false,
panY: false,
wheelX: "none",
wheelY: "none",
paddingBottom: 50,
paddingTop: 40,
paddingLeft: 0,
paddingRight: 0
})
);

var xRenderer = am5xy.AxisRendererX.new(root, {
minorGridEnabled: true,
minGridDistance: 60
});
xRenderer.grid.template.set("visible", false);

var xAxis = chart.xAxes.push(
am5xy.CategoryAxis.new(root, {
paddingTop: 40,
categoryField: "name",
renderer: xRenderer
})
);

var yRenderer = am5xy.AxisRendererY.new(root, {});
yRenderer.grid.template.set("strokeDasharray", [3]);

var yAxis = chart.yAxes.push(
am5xy.ValueAxis.new(root, {
min: 0,
renderer: yRenderer
})
);

var series = chart.series.push(
am5xy.ColumnSeries.new(root, {
name: "Steps",
xAxis: xAxis,
yAxis: yAxis,
valueYField: "steps",
categoryXField: "name",
sequencedInterpolation: true,
calculateAggregates: true,
maskBullets: false,
tooltip: am5.Tooltip.new(root, {
dy: -30,
pointerOrientation: "vertical",
labelText: "{valueY}"
})
})
);

series.columns.template.setAll({
strokeOpacity: 0,
cornerRadiusBR: 10,
cornerRadiusTR: 10,
cornerRadiusBL: 10,
cornerRadiusTL: 10,
maxWidth: 50,
fillOpacity: 0.8
});

var currentlyHovered;
series.columns.template.events.on("pointerover", function(e) {
handleHover(e.target.dataItem);
});

series.columns.template.events.on("pointerout", function(e) {
handleOut();
});

function handleHover(dataItem) {
if (dataItem && currentlyHovered != dataItem) {
handleOut();
currentlyHovered = dataItem;
var bullet = dataItem.bullets[0];
bullet.animate({
key: "locationY",
to: 1,
duration: 600,
easing: am5.ease.out(am5.ease.cubic)
});
}
}

function handleOut() {
if (currentlyHovered) {
var bullet = currentlyHovered.bullets[0];
bullet.animate({
key: "locationY",
to: 0,
duration: 600,
easing: am5.ease.out(am5.ease.cubic)
});
}
}

var circleTemplate = am5.Template.new({});

series.bullets.push(function(root, series, dataItem) {
var bulletContainer = am5.Container.new(root, {});
var circle = bulletContainer.children.push(
am5.Circle.new(
root,
{
  radius: 34
},
circleTemplate
)
);

var maskCircle = bulletContainer.children.push(
am5.Circle.new(root, { radius: 27 })
);

var imageContainer = bulletContainer.children.push(
am5.Container.new(root, {
mask: maskCircle
})
);

var image = imageContainer.children.push(
am5.Picture.new(root, {
templateField: "pictureSettings",
centerX: am5.p50,
centerY: am5.p50,
width: 60,
height: 60
})
);

return am5.Bullet.new(root, {
locationY: 0,
sprite: bulletContainer
});
});

series.set("heatRules", [
{
dataField: "valueY",
min: am5.color(0x00a65a),   // Strong green for the lowest value
max: am5.color(0x006400),   // Dark green for the highest value (stronger color)
target: series.columns.template,
key: "fill"
},
{
dataField: "valueY",
min: am5.color(0x00a65a),   // Strong green for the lowest value
max: am5.color(0x006400),   // Dark green for the highest value (stronger color)
target: circleTemplate,
key: "fill"
}
]);

series.data.setAll(data);
xAxis.data.setAll(data);

var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
cursor.lineX.set("visible", false);
cursor.lineY.set("visible", false);

cursor.events.on("cursormoved", function() {
var dataItem = series.get("tooltip").dataItem;
if (dataItem) {
handleHover(dataItem);
} else {
handleOut();
}
});

series.appear();
chart.appear(1000, 100);
});



















    am5.ready(function () { 
       
        var productStock = @json($productStock);
        console.log("lowStockProduct",productStock);
        const productsWithStock = productStock.filter(product => product.current_stock > 0);

        const lowestStockProducts = productsWithStock.sort((a, b) => a.current_stock - b.current_stock).slice(0, 5);

        const chartData = lowestStockProducts.map(product => ({
            category: product.product_name,
            value: product.current_stock
        }));

        var root = am5.Root.new("lowStockProduct");

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


    am5.ready(function() {
    var root = am5.Root.new("chartdivGaug");

    root.setThemes([am5themes_Animated.new(root)]);

    var chart = root.container.children.push(
        am5percent.PieChart.new(root, {
            startAngle: 160,
            endAngle: 380
        })
    );

    var series0 = chart.series.push(
        am5percent.PieSeries.new(root, {
            valueField: "litres",
            categoryField: "country",
            startAngle: 160,
            endAngle: 380,
            radius: am5.percent(70),
            innerRadius: am5.percent(65)
        })
    );

    var colorSet = am5.ColorSet.new(root, {
        colors: [am5.color(0x00a65a)],
        step: 2,
        passOptions: {
            lightness: 0.2,
            hue: 0
        }
    });

    series0.set("colors", colorSet);

    series0.ticks.template.set("forceHidden", true);
    series0.labels.template.set("forceHidden", true);

    var series1 = chart.series.push(
        am5percent.PieSeries.new(root, {
            startAngle: 160,
            endAngle: 380,
            valueField: "bottles",
            innerRadius: am5.percent(80),
            categoryField: "country"
        })
    );

    series1.set("colors", colorSet);

    series1.ticks.template.set("forceHidden", true);
    series1.labels.template.set("forceHidden", true);

    var label = chart.seriesContainer.children.push(
        am5.Label.new(root, {
            textAlign: "center",
            centerY: am5.p100,
            centerX: am5.p50,
            text: "[fontSize:18px]total[/]:\n[bold fontSize:30px]1647.9[/]"
        })
    );

    var data = [
        { country: "Lithuania", litres: 501.9, bottles: 1500 },
        { country: "Czech Republic", litres: 301.9, bottles: 990 },
        { country: "Ireland", litres: 201.1, bottles: 785 },
        { country: "Germany", litres: 165.8, bottles: 255 },
        { country: "Australia", litres: 139.9, bottles: 452 },
    ];

    series0.data.setAll(data);
    series1.data.setAll(data);

    // Add slight separation by pulling the slices
    series0.slices.template.setAll({
        pull: 0.05  // This value determines the separation (increase/decrease to adjust)
    });

    // Show tooltip on the largest arc on load
    series0.events.once("datavalidated", function() {
        let maxValue = Math.max(...data.map(item => item.litres)); // Find max value
        let maxIndex = data.findIndex(item => item.litres === maxValue); // Find max index
        let maxSlice = series0.slices.getIndex(maxIndex); // Get the corresponding slice

        if (maxSlice) {
            maxSlice.showTooltip();
        }
    });
});

    







am5.ready(function() {
    var root = am5.Root.new("chartDivStock");

    root.setThemes([am5themes_Animated.new(root)]);

    var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panX",
        wheelY: "zoomX",
        scrollbarX: am5.Scrollbar.new(root, { orientation: "horizontal" }),
        scrollbarY: am5.Scrollbar.new(root, { orientation: "vertical" }),
        pinchZoomX: true,
        paddingLeft: 0
    }));

    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
    cursor.lineY.set("visible", false);

    var xRenderer = am5xy.AxisRendererX.new(root, {
        minGridDistance: 15,
        minorGridEnabled: true
    });

    xRenderer.labels.template.setAll({
        rotation: -90,
        centerY: am5.p50,
        centerX: 0,
        fill: am5.color(0x00000)
    });

    xRenderer.grid.template.setAll({
        visible: false
    });

    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
        maxDeviation: 0.3,
        categoryField: "category",
        renderer: xRenderer,
        tooltip: am5.Tooltip.new(root, {})
    }));

    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        maxDeviation: 0.3,
        renderer: am5xy.AxisRendererY.new(root, {})
    }));

    var series = chart.series.push(am5xy.ColumnSeries.new(root, {
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "current_stock",
        categoryXField: "category",
        adjustBulletPosition: false,
        tooltip: am5.Tooltip.new(root, {
            labelText: "{valueY}"
        })
    }));

    series.columns.template.setAll({
        width: 0.5,
        fill: am5.color(0x00a65a),
        stroke: am5.color(0x00a65a),
        strokeWidth: 2
    });

    series.bullets.push(function() {
        return am5.Bullet.new(root, {
            locationY: 1,
            sprite: am5.Circle.new(root, {
                radius: 5,
                fill: am5.color(0x00000)
            }),
            stroke: am5.color(0x00000),
            strokeWidth: 3
        });
    });

    var data = @json($productStock);

    var chartData = data.map(function(item) {
        return {
            category: item.product_name,
            current_stock: item.current_stock
        };
    });

    chartData.sort(function(a, b) {
        return b.current_stock - a.current_stock;
    });

    if (chartData.length > 0) {
        xAxis.data.setAll(chartData);
        series.data.setAll(chartData);
    } else {
        console.error("No data available for the chart");
    }

    series.appear(1000);
    chart.appear(1000, 100);
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
