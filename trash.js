$(function () {
    var options = {
        chart: {
            renderTo: 'container1'
        },
        rangeSelector: {
            selected: 1
        },

        series: [{
            data: GOOGL
        }]
    }

    var chart1 = new Highcharts.StockChart($.extend(true, {}, options, {
        chart: {
            renderTo: 'container1'
        },
        xAxis: {
            events: {
                setExtremes: function (e) {
                    if (!chart2)
                        return;

                    chart2.xAxis[0].setExtremes(e.min, e.max);
                }
            }
        },
        series: [{
            data: GOOGL
        }]
    }));


    var chart2 = new Highcharts.StockChart($.extend(true, {}, options, {
        chart: {
            renderTo: 'container2'
        },
        navigator: {
            enabled: false
        },
        scrollbar: {
            enabled: false
        },
        rangeSelector: {
            enabled: false
        },
        series: [{
            data: ADBE
        }]
    }));
});