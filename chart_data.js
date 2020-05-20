function set_chart(stock_code) {
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
                selected: 1,
                inputDateFormat: '%Y-%m-%d'
            },
            title: {
                text: stock_code_num + " " + obj.chinese_name
            },
            xAxis: {
                dateTimeLabelFormats: {
                    millisecond: '%H:%M:%S.%L',
                    second: '%H:%M:%S',
                    minute: '%H:%M',
                    hour: '%H:%M',
                    day: '%m-%d',
                    week: '%m-%d',
                    month: '%y-%m',
                    year: '%Y'
                }
            },
            tooltip: {
                split: false,
                shared: true,
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