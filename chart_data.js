const delta = 0, delta_min = 0, delta_max = 0;
var charts = [];
var syncdone = true;

var syncEvent = {
    afterSetExtremes: function (e) {
        if (!syncdone)
            return;
        //console.log(e)
        syncdone = false;
        charts.forEach(element => {
            //console.log(e.target.chart == element)
            //console.log(element);
            if (e.target.chart != element && !(element.xAxis[0].min == e.min && element.xAxis[0].max == e.max))
                element.xAxis[0].setExtremes(e.min, e.max);
        });
        syncdone = true;
    }
};

function query_result_band(dates) {
    var dates_plotBands = [];
    if (dates) {
        dates.forEach(element => {
            var obj_tmp = {
                from: new Date(element[0]).getTime() - delta_min - delta,
                to: new Date(element[1]).getTime() + delta_max - delta,
                color: 'rgba(68, 170, 213, 0.2)',
                label: {
                    text: ''
                }
            };
            dates_plotBands.push(obj_tmp);
        });
    }
    return dates_plotBands;
}

function create_bias(stock_code, seriesOptions, dates_plotBands) {
    var chart = Highcharts.stockChart('bias-chart', {
        chart: {
            backgroundColor: 'rgba(0,0,0,0)',
            zoomType: null
            // pinchType: null
        },
        rangeSelector: {
            selected: 4
        },
        xAxis: {
            plotBands: dates_plotBands,
            events: syncEvent
        },
        title: {
            text: "乖離率 Bias"
        },
        plotOptions: {
            series: {
                showInLegend: true
            }
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">BIAS{series.name}</span>: <b>{point.y}</b>',// ({point.change})<br/>
            valueDecimals: 2,
            followTouchMove: false,
            split: true
        },
        series: seriesOptions
    });

    charts.push(chart);
}

function set_bias(stock_code, dates = []) {
    var dates_plotBands = query_result_band(dates);

    var seriesOptions = [],
        seriesCounter = 0,
        names = ['5', '20', '60'];

    $.each(names, function (i, name) {
        $.getJSON(`get_bias.php?stock_code=${stock_code}&day_count=${name}`, function (data) {
            seriesOptions[i] = {
                name: name,
                data: data
            };
            seriesCounter += 1;
            if (seriesCounter === names.length) {
                create_bias(stock_code, seriesOptions, dates_plotBands);
            }
        });
    });
}

function create_ma(stock_code, seriesOptions, dates_plotBands) {
    var chart = Highcharts.stockChart('ma-chart', {
        chart: {
            backgroundColor: 'rgba(0,0,0,0)',
            zoomType: null
            // pinchType: null
        },
        rangeSelector: {
            selected: 4
        },
        xAxis: {
            plotBands: dates_plotBands,
            events: syncEvent
        },
        title: {
            text: "均線 Moving Average"
        },
        plotOptions: {
            series: {
                showInLegend: true
            }
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">MA{series.name}</span>: <b>{point.y}</b>',// ({point.change})<br/>
            valueDecimals: 2,
            followTouchMove: false,
            split: true
        },
        series: seriesOptions
    });

    charts.push(chart);
}

function set_ma(stock_code, dates = []) {
    var dates_plotBands = query_result_band(dates);

    var seriesOptions = [],
        seriesCounter = 0,
        names = ['5', '20', '60'];

    $.each(names, function (i, name) {
        //console.log('https://data.jianshukeji.com/jsonp?filename=json/' + name.toLowerCase() + '-c.json&callback=?');
        $.getJSON(`get_ma.php?stock_code=${stock_code}&day_count=${name}`, function (data) {
            seriesOptions[i] = {
                name: name,
                data: data
            };
            // As we're loading the data asynchronously, we don't know what order it will arrive. So
            // we keep a counter and create the chart when all the data is loaded.
            seriesCounter += 1;
            if (seriesCounter === names.length) {
                create_ma(stock_code, seriesOptions, dates_plotBands);
            }
        });
    });
}

function set_chart(stock_code, dates = []) {
    var dates_plotBands = query_result_band(dates);

    Highcharts.setOptions({
        lang: {
            rangeSelectorZoom: ''
        }
    });
    $.getJSON('get_data.php/?stock_code=' + stock_code, function (obj) {
        if (obj.code !== 1) {
            alert('Fail');
            return false;
        }
        let data = obj.data;
        let stock_code_num = stock_code.substring(0, stock_code.length - 2);

        //document.getElementById("demo").innerHTML = data.length;

        var ohlc = [],
            volume = [],
            dataLength = data.length,
            // set the allowed units for data grouping
            groupingUnits = [
                [
                    'week', // unit name
                    [1] // allowed multiples
                ],
                [
                    'month',
                    [1, 2, 3, 4, 6]
                ]
            ],
            i = 0;
        for (i; i < dataLength; i += 1) {
            ohlc.push([
                data[i][0], // the date
                data[i][1], // open
                data[i][2], // high
                data[i][3], // low
                data[i][4] // close
            ]);
            volume.push([
                data[i][0], // the date
                data[i][6] // the volume
            ]);
        }
        // create the chart
        var chart = Highcharts.stockChart('line-chart', {
            chart: {
                backgroundColor: 'rgba(0,0,0,0)',
                type: 'line'
            },
            rangeSelector: {
                selected: 4
                //inputDateFormat: '%Y-%m-%d'
            },
            title: {
                text: stock_code_num + " " + obj.chinese_name
            },
            xAxis: {
                /*
                dateTimeLabelFormats: {
                    millisecond: '%H:%M:%S.%L',
                    second: '%H:%M:%S',
                    minute: '%H:%M',
                    hour: '%H:%M',
                    day: '%m-%d',
                    week: '%m-%d',
                    month: '%y-%m',
                    year: '%Y'
                },*/
                plotBands: dates_plotBands,
                events: syncEvent
            },
            tooltip: {
                //split: false,
                //shared: true,
            },
            yAxis: [{
                labels: {
                    align: 'right',
                    x: -3
                },
                title: {
                    text: 'price'
                },
                height: '65%',
                resize: {
                    enabled: true
                },
                lineWidth: 2
            }, {
                labels: {
                    align: 'right',
                    x: -3
                },
                title: {
                    text: 'volumn'
                },
                top: '65%',
                height: '35%',
                offset: 0,
                lineWidth: 2
            }],
            series: [{
                type: 'candlestick',
                name: stock_code_num,
                color: 'green',
                lineColor: 'green',
                upColor: 'red',
                upLineColor: 'red',
                tooltip: {},
                navigatorOptions: {
                    color: Highcharts.getOptions().colors[0]
                },
                data: ohlc,
                dataGrouping: {
                    units: groupingUnits
                },
                id: 'sz'
            }, {
                type: 'column',
                data: volume,
                yAxis: 1,
                dataGrouping: {
                    units: groupingUnits
                }
            }]
        });
        charts.push(chart);
    });
}


/*// ! test

var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    //document.getElementById("demo").innerHTML = data.data[0]["record_date"];
                }
            };
            xmlhttp.open("GET", "get_data.php", true);
            xmlhttp.send();
*/