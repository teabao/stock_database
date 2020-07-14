<?php
// stock project website

# $conn : mysql連線變數
require("connect.php");
require("function.php");
require("view_func.php");
require("basic_query.php");
require("msg.php");
#$conn->close();


if ($_GET['stock_code'] == "")
    header("Location: /?stock_code=0050");


//==========================================================
?>

<!DOCTYPE html>
<html>

<head>
    <title>Stock 股票分析</title>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" type="text/css" href="/index.css">
    <link rel="stylesheet" type="text/css" href="/environment.css">
    <link rel="stylesheet" type="text/css" href="/filter.css">

    <script src="/index.js"></script>
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com.cn/highcharts/themes/dark-unica.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="chart_data.js"></script>


</head>


<body>
    <div class="topnav">
        <a class="active" href="index.php">技術分析</a>
        <a href="find_stock.php">類股查詢</a>
        <a href="about.php">關於</a>
    </div>
    <div class="sidenav">
        <table class="side_table">

            <!--tr onclick="document.location = '?stock_code=0050'">
                <td>0050</td>
                <td>元大台灣50</td>
            </tr-->
            <?php get_side_bar($conn); ?>

        </table>


    </div>
    <div class="right-side">
        <h3 class="smallTitle">Query Result</h3>
        <hr>
        <h4 class="smallTitle">您選擇的條件是:</h4>
        <div>
            <?php
            if ($_POST['query_type'] == "successive")
                echo "<h4 class='q_type'>連續" . $_POST['successive_day_count'] . "天以上滿足</h4>";
            else if ($_POST['query_type'] == "mean")
                echo "<h4 class='q_type'>" . $_POST['mean_day_count'] . "天內平均滿足</h4>";

            for ($conditionNum = 0; $_POST["condition$conditionNum"] == "true"; $conditionNum++) {
                $conditionVariable = $_POST["conditionVariable$conditionNum"];
                $conditionCompare = $_POST["conditionCompare$conditionNum"];
                $conditionValue = $_POST["conditionValue$conditionNum"];
                $variableMap = array(
                    "open_price"    => "開盤價",
                    "close_price"   => "收盤價",
                    "high_price"    => "最高價",
                    "low_price"     => "最低價",
                    "volume"        => "成交量",
                );
                echo $variableMap[$conditionVariable] . " $conditionCompare $conditionValue <br>";
            }
            ?>
        </div>
        <hr>
        <h4 class="smallTitle   ">符合的日期:<br>
            <h5>(圖表中的藍色標記)</h5>
        </h4>
        <div id="query_result_date">
            <script>
                var stockCode = "<?php echo $_GET['stock_code'] . "tw"; ?>";
                var basic_query_result = <?php basic_query($conn); ?>;
                set_chart(stockCode, basic_query_result);
                set_ma(stockCode, basic_query_result);
                set_bias(stockCode, basic_query_result);
                set_rsv(stockCode, basic_query_result);
                set_kd(stockCode, basic_query_result);

                let query_result_date = document.getElementById("query_result_date");
                basic_query_result.forEach(element => {
                    query_result_date.innerHTML += `${element[0]} ~ ${element[1]}<br>`;
                });
            </script>
        </div>
    </div>
    <main>
        <div class='line-chart-container chart-container'>
            <div class='stock-code'>
                <!--?php
                echo $_GET['stock_code'];
                ?-->
            </div>
            <div id='line-chart' class="chart"></div>
        </div>
        <div class='ma-chart-container chart-container'>
            <div class='stock-code'>
            </div>
            <div id='ma-chart' class="chart"></div>
        </div>
        <div class='bias-chart-container chart-container'>
            <div class='stock-code'>
            </div>
            <div id='bias-chart' class="chart"></div>
        </div>
        <div class='rsv-chart-container chart-container'>
            <div class='stock-code'>
            </div>
            <div id='rsv-chart' class="chart"></div>
        </div>
        <div class='kd-chart-container chart-container'>
            <div class='stock-code'>
            </div>
            <div id='kd-chart' class="chart"></div>
        </div>

        <div class="query_title">個股趨勢查詢</div>
        <button id="newCondition" onclick="newCondition()">Add Condition</button>
        <form class="filter" action="/?stock_code=<?php echo $_GET['stock_code']; ?>" method="POST">
            <input type="hidden" id="enable_basic_query" name="enable_basic_query" value="true">
            <input type="hidden" id="stock_code" name="stock_code" value="<?php echo $_GET['stock_code']; ?>">
            <br>
            <label for="day_from">From:</label>
            <input type="date" id="day_from" name="day_from" value="2008-01-05" required>
            <label for="day_to">To:</label>
            <input type="date" id="day_to" name="day_to" value="2020-05-14" required>
            <br>
            <div>
                <input type="radio" id="successive" name="query_type" value="successive" checked required>
                <label for="successive">連續滿足條件</label>
                <input type="number" id="successive_day_count" name="successive_day_count" value="1">
                <label for="successive_day_count">天</label>
            </div>
            <div>
                <input type="radio" id="mean" name="query_type" value="mean" required>
                <label for="mean">平均滿足條件</label>
                <input type="number" id="mean_day_count" name="mean_day_count" value="1">
                <label for="mean_day_count">天</label>
            </div>

            <br>

            <div id="conditionArea"></div>

            <!--div class="condition">
                <select id="conditionVariable" name="conditionVariable">
                    <option value="open">開盤</option>
                    <option value="close">收盤</option>
                    <option value="high">高價</option>
                    <option value="low">低價</option>
                    <option value="volumn">成交量</option>
                </select>
                <select id="conditionCompare" name="conditionCompare">
                    <option value=">">大於</option>
                    <option value=">=">大於等於</option>
                    <option value="<">小於</option>
                    <option value="<=">小於等於</option>
                    <option value="==">等於</option>
                    <option value="!=">不等於</option>
                </select>
                <input type="number" id="conditionValue" name="conditionValue" value="0">
            </div-->
            <input type="submit">
        </form>





        <hr class="down">

        <div class="query_title">留言板</div>

        <table class='msg_table' RULES=ROWS>
            <tr>
                <td class="m_name"><b>暱稱</b></td>
                <td class="m_text"><b>留言</b></td>
                <td class="m_time"><b>時間</b></td>
            </tr>
            <?php check_msg($conn);
            show_msg($conn); ?>

        </table>

        <br>

        <form action="/?stock_code=<?php echo $_GET['stock_code']; ?>" method="POST">
            <input type="hidden" id="new_msg" name="new_msg" value="true">
            <label for="msg_name">暱稱</label>
            <input class="msg" type="text" id="msg_name" name="msg_name" maxlength="10">
            <label for="msg_text">留言</label>
            <input class="msg" type="text" id="msg_text" name="msg_text" maxlength="20">
            <input type="submit">
        </form>

        <br><br><br><br>


        <!-- test field-->
        <!--div id="demo"></div>
        <div>write you sql here (for test)</div>
        <form action="/?stock_code=<?php echo $_GET['stock_code']; ?>" method="POST">
            <textarea id="sql" name="sql" rows="5" cols="50"></textarea><br>
            <input type="submit">
        </form>
        <div>your query :</div>
        <div id='query_result_table'>
            <?php
            $sql = $_POST['sql'];
            echo "<b id='sql'>$sql</b><br><br>";
            echo "query result:<br>";
            query_sql($conn, $sql);
            ?>
        </div-->
    </main>



</body>

</html>