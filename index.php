<?php
// stock project website

# $conn : mysql連線變數
require("connect.php");
require("function.php");
require("view_func.php");
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
    <title>Title</title>
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
    <script>
        set_chart("<?php echo $_GET['stock_code'] . "tw"; ?>");
    </script>


</head>


<body>
    <div class="topnav">
        <a class="active" href="#home">Home</a>
        <a href="#news">News</a>
        <a href="#contact">Contact</a>
        <a href="#about">About</a>
    </div>
    <div class="sidenav">
        <?php
        get_side_bar($conn);
        ?>
    </div>
    <div class="right-side">
        <a href='#contact'>$value</a>
    </div>
    <main>
        <div class='line-chart-container' class="chart">
            <div class='stock-code'>
                <!--?php
                echo $_GET['stock_code'];
                ?-->
            </div>
            <div id='line-chart'>
            </div>
        </div>
        <form class="filter" action="/" method="POST">
            <input type="hidden" id="stock_code" name="stock_code" value="<?php echo $_GET['stock_code']; ?>">

            <label for="day_from">From:</label>
            <input type="time" id="day_from" name="day_from">
            <label for="day_to">To:</label>
            <input type="time" id="day_to" name="day_to">
            <br>
            <div>
                <input type="radio" id="successive" name="successive" value="successive">
                <label for="successive">連續</label>
                <input type="number" id="day_count" name="day_count" value="1">
                <label for="day_count">天</label>
            </div>

            <br>
            <!--input class="button" type="button" value="fuck"-->
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

        </form>
        <button id="newCondition" onclick="newCondition()">Add Condition</button>








        <!-- test field-->
        <div id="demo"></div>
        <div>write you sql here (for test)</div>
        <form action="/" method="POST">
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
        </div>
    </main>



</body>

</html>