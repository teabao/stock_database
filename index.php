<?php
// stock project website

# $conn : mysql連線變數
require("connect.php");
require("function.php");
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
        $sql_side = "select stock_code from stock_name;";
        $result_side = mysqli_query($conn, $sql_side);
        while ($row = mysqli_fetch_assoc($result_side))
            foreach ($row as $key => $value)
                echo "<a href='#contact'>$value</a>";
        ?>
    </div>
    <div class="right-side">
        <a href='#contact'>$value</a>
    </div>
    <main>
        <div class='line-chart'>折線圖</div>
        <div>write you sql here</div>
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