"use strict";
 

var options = {
    series: [{
        name: "Desktops",
        data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
    }],
    chart: {
        height: 350,
        type: 'line',
        zoom: {
            enabled: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'straight'
    },
    title: {
        text: 'Reportes de Ventas',
        align: 'left'
    },
    grid: {
        row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
        },
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
    }
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();

var ssssasasasa = 0;
var arraycantidad_ventas = [];
var arraycantidad_ventas1 = [];
var ventas = [];
var messss = [];

function totalventas() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: APP_URL + '/dashboard/default/total-ventas',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

        success: function (response) {

            var produce = []

            $(response.data).each(function(i, v) {
                //push values as x & y
                produce.push({
                    "x": v.mesletra,
                    "y": v.cantidad_ventas
                })

            })

            var options = {
                series: [{
                    name: 'cantidad_ventas',
                    type: 'column',
                    data: produce,
                }, {
                    name: 'cantidad_ventas',
                    type: 'column',
                    data: produce,
                }, {
                    name: 'cantidad_ventas',
                    type: 'line',
                    data: produce,
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    stacked: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: [1, 1, 4]
                },
                title: {
                    text: 'Reporte De Ventas',
                    align: 'left',
                    offsetX: 110
                },
                xaxis: {

                },
                yaxis: [
                    {
                        axisTicks: {
                            show: true,
                        },
                        axisBorder: {
                            show: true,
                            color: '#008FFB'
                        },
                        labels: {
                            style: {
                                colors: '#008FFB',
                            }
                        },
                        title: {
                            text: "Income (thousand crores)",
                            style: {
                                color: '#008FFB',
                            }
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    {
                        seriesName: 'Income',
                        opposite: true,
                        axisTicks: {
                            show: true,
                        },
                        axisBorder: {
                            show: true,
                            color: '#00E396'
                        },
                        labels: {
                            style: {
                                colors: '#00E396',
                            }
                        },
                        title: {
                            text: "Operating Cashflow (thousand crores)",
                            style: {
                                color: '#00E396',
                            }
                        },
                    },
                    {
                        seriesName: 'Revenue',
                        opposite: true,
                        axisTicks: {
                            show: true,
                        },
                        axisBorder: {
                            show: true,
                            color: '#FEB019'
                        },
                        labels: {
                            style: {
                                colors: '#FEB019',
                            },
                        },
                        title: {
                            text: "Revenue (thousand crores)",
                            style: {
                                color: '#FEB019',
                            }
                        }
                    },
                ],
                tooltip: {
                    fixed: {
                        enabled: true,
                        position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
                        offsetY: 30,
                        offsetX: 60
                    },
                },
                legend: {
                    horizontalAlign: 'left',
                    offsetX: 40
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart1"), options);
            chart.render();

        }


    });
}


var cantidadppatendido = 0;
var cantidadgentregado = 0;
var cantidadgrecogido = 0;
let cantidadppendiente = 0;


function pendienteped() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: APP_URL + '/dashboard/default/contarpedidospen',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

        success: function (response) {
            cantidadppendiente = response["cantidadpe"];

            if (cantidadppendiente > 0) {
                var spark2 = {

                    chart: {
                        id: 'sparkline1',
                        type: 'line',
                        height: 100,
                        sparkline: {
                            enabled: true
                        },
                        group: 'sparklines'
                    },
                    series: [{
                        name: 'purple',
                        data: [25, 66, 41, 59, 25, 44, 12, 36, 9, 21]
                    }],
                    stroke: {
                        curve: 'smooth'
                    },
                    markers: {
                        size: 0
                    },
                    tooltip: {
                        fixed: {
                            enabled: true,
                            position: 'right'
                        },
                        x: {
                            show: false
                        }
                    },
                    title: {
                        text: cantidadppendiente + ' Pedidos Pendientes',
                        style: {
                            fontSize: '20px'
                        }
                    },
                    colors: ['#734CEA']
                }
                new ApexCharts(document.querySelector("#spark2"), spark2).render();
            }

        }
    });
}

function ateped() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: APP_URL + '/dashboard/default/contarpedidosate',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

        success: function (response) {
            cantidadppatendido = response["cantidadpe"];

            if (cantidadppatendido > 0) {
                var spark1 = {

                    chart: {
                        id: 'sparkline1',
                        type: 'line',
                        height: 100,
                        sparkline: {
                            enabled: true
                        },
                        group: 'sparklines'
                    },
                    series: [{
                        name: 'purple',
                        data: [12, 66, 41, 59, 25, 44, 12, 36, 9, 21]
                    }],
                    stroke: {
                        curve: 'smooth'
                    },
                    markers: {
                        size: 0
                    },
                    tooltip: {
                        fixed: {
                            enabled: true,
                            position: 'right'
                        },
                        x: {
                            show: false
                        }
                    },
                    title: {
                        text: cantidadppatendido + ' Pedidos Atendidos',
                        style: {
                            fontSize: '20px'
                        }
                    },
                    colors: ['#4c4e6f']
                }
                new ApexCharts(document.querySelector("#spark1"), spark1).render();
            }

        }
    });
}


function Cantidadgpen() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: APP_URL + '/dashboard/default/contar-total-guia-recogido',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

        success: function (response) {
            cantidadgrecogido = response["cantidadpe"];

            if (cantidadgrecogido > 0) {
                var spark3 = {
                    chart: {
                        id: 'sparkline1',
                        type: 'line',
                        height: 100,
                        sparkline: {
                            enabled: true
                        },
                        group: 'sparklines'
                    },
                    series: [{
                        name: 'purple',
                        data: [25, 66, 41, 59, 25, 44, 12, 36, 9, 21]
                    }],
                    stroke: {
                        curve: 'smooth'
                    },
                    markers: {
                        size: 0
                    },
                    tooltip: {
                        fixed: {
                            enabled: true,
                            position: 'right'
                        },
                        x: {
                            show: false
                        }
                    },
                    title: {
                        text: cantidadgrecogido + ' Guias Recogidas',
                        style: {
                            fontSize: '20px'
                        }
                    },
                    colors: ['#734CEA']
                }
                new ApexCharts(document.querySelector("#spark3"), spark3).render();
            }

        }
    });
}


function Cantidadgentregado() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: APP_URL + '/dashboard/default/contar-total-guia-entregado',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

        success: function (response) {
            cantidadgentregado = response["cantidadpe"];

            if (cantidadgentregado > 0) {
                var spark4 = {
                    chart: {
                        id: 'sparkline1',
                        type: 'line',
                        height: 100,
                        sparkline: {
                            enabled: true
                        },
                        group: 'sparklines'
                    },
                    series: [{
                        name: 'purple',
                        data: [25, 66, 41, 59, 25, 44, 12, 36, 9, 21]
                    }],
                    stroke: {
                        curve: 'smooth'
                    },
                    markers: {
                        size: 0
                    },
                    tooltip: {
                        fixed: {
                            enabled: true,
                            position: 'right'
                        },
                        x: {
                            show: false
                        }
                    },
                    title: {
                        text: cantidadgentregado + ' Guias Entregadas',
                        style: {
                            fontSize: '20px'
                        }
                    },
                    colors: ['#734CEA']
                }
                new ApexCharts(document.querySelector("#spark4"), spark4).render();
            }

        }
    });
}


$(document).ready(function () {
    pendienteped(),
        ateped(),
        Cantidadgentregado(),
        Cantidadgpen(),
        totalventas()


    am4core.ready(function() {

// Themes begin
        am4core.useTheme(am4themes_animated);
// Themes end

        var chart = am4core.create("chartdiv", am4charts.XYChart);
        chart.padding(40, 40, 40, 40);

        var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.dataFields.category = "network";
        categoryAxis.renderer.minGridDistance = 1;
        categoryAxis.renderer.inversed = true;
        categoryAxis.renderer.grid.template.disabled = true;

        var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
        valueAxis.min = 0;

        var series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.categoryY = "network";
        series.dataFields.valueX = "MAU";
        series.tooltipText = "{valueX.value}"
        series.columns.template.strokeOpacity = 0;
        series.columns.template.column.cornerRadiusBottomRight = 5;
        series.columns.template.column.cornerRadiusTopRight = 5;

        var labelBullet = series.bullets.push(new am4charts.LabelBullet())
        labelBullet.label.horizontalCenter = "left";
        labelBullet.label.dx = 10;
        labelBullet.label.text = "{values.valueX.workingValue.formatNumber('#.0as')}";
        labelBullet.locationX = 1;

// as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
        series.columns.template.adapter.add("fill", function(fill, target){
            return chart.colors.getIndex(target.dataItem.index);
        });

        categoryAxis.sortBySeries = series;
        chart.data = [
            {
                "network": "Facebook",
                "MAU": 2255250000
            },
            {
                "network": "Google+",
                "MAU": 430000000
            },
            {
                "network": "Instagram",
                "MAU": 1000000000
            },
            {
                "network": "Pinterest",
                "MAU": 246500000
            },
            {
                "network": "Reddit",
                "MAU": 355000000
            },
            {
                "network": "TikTok",
                "MAU": 500000000
            },
            {
                "network": "Tumblr",
                "MAU": 624000000
            },
            {
                "network": "Twitter",
                "MAU": 329500000
            },
            {
                "network": "WeChat",
                "MAU": 1000000000
            },
            {
                "network": "Weibo",
                "MAU": 431000000
            },
            {
                "network": "Whatsapp",
                "MAU": 1433333333
            },
            {
                "network": "YouTube",
                "MAU": 1900000000
            }
        ]



    }); // end am4core.ready()

});