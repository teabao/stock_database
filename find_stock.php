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
        <h1>類股查詢</h1>
        <form class="filter" action="/find_stock.php" method="POST">
            <input type="hidden" id="enable_query" name="enable_query" value="true">
            <table>
                <tr>
                    <td><label for="stock_type">*分類 </label></td>
                    <td>
                        <select name="stock_type" id="stock_type">
                            <option value='all'>所有產業別</option>
                            <?php
                            get_type($conn);
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="day_from">*上市日期</label></td>
                    <td><input type="date" id="day_from" name="day_from" value="1900-01-05" required></td>
                    <td>~</td>
                    <td><input type="date" id="day_to" name="day_to" value="2020-05-14" required></td>
                </tr>
                <tr>
                    <td><label for="q_name">名字</label></td>
                    <td><input type="text" id="q_name" name="q_name" style="width:150px;" maxlength="20"></td>
                    <td></td>
                    <td>可使用 Regular Expression</td>
                </tr>
                <tr>
                    <td><label for="q_code">代號</label></td>
                    <td><input type="text" id="q_code" name="q_code" style="width:150px;" maxlength="20"></td>
                    <td></td>
                    <td>可使用 Regular Expression</td>
                </tr>
            </table>
            <br>
            <input type="submit">
        </form>
        <br>
        <?php get_list($conn) ?>
    </main>



</body>

</html>