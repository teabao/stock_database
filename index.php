<?php
// stock project website

# $conn : mysql連線變數
require("connect.php");
require("function.php");
require("view_func.php");
#$conn->close();



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