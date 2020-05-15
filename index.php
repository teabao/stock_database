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
    <link rel="stylesheet" type="text/css" href="/index.css">

</head>

<body>
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


</body>

</html>