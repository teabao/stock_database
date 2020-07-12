<?php
// stock project website

# $conn : mysql連線變數
require("connect.php");
require("function.php");
require("view_func.php");
require("basic_query.php");
require("find_stock_function.php");
#$conn->close();

/*
if ($_GET['stock_code'] == "")
    header("Location: /?stock_code=0055");
*/

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
        <a class="active" href="find_stock.php">類股查詢</a>
        <a href="#news">News</a>
        <a href="#contact">Contact</a>
        <a href="#about">About</a>
    </div>
    <main>
        <h1>類股查詢</h1>
        <form class="filter" action="/find_stock.php" method="POST">
            <input type="hidden" id="enable_basic_query" name="enable_basic_query" value="true">
            <table>
                <tr>
                    <td><label for="stock_type">分類 </label></td>
                    <td>
                        <select name="stock_type" id="stock_type">
                            <?php
                            get_type($conn);
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="day_from">上市日期</label></td>
                    <td><input type="date" id="day_from" name="day_from" value="1900-01-05" required></td>
                    <td>~</td>
                    <td><input type="date" id="day_to" name="day_to" value="2020-05-14" required></td>
                </tr>
            </table>
            <input type="number" id="successive_day_count" name="successive_day_count" value="1" style="width: 150px;">
            <label for="successive_day_count">天之內滿足以下條件</label>
            <div id="conditionArea"></div>
            <br>
            <input type="submit">
        </form>
        <button id="newCondition" onclick="newCondition()">Add Condition</button>
    </main>



</body>

</html>