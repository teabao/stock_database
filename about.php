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
        <a class="active" href="index.php">技術分析</a>
        <a href="find_stock.php">類股查詢</a>
        <a href="about.php">關於</a>
    </div>
    <main>
        <h1>關於</h1>

    </main>



</body>

</html>