<?php
function get_side_bar($conn)
{
    $sql_side = "select stock_code from stock_name;";
    $result_side = mysqli_query($conn, $sql_side);
    while ($row = mysqli_fetch_assoc($result_side))
        foreach ($row as $key => $value)
            echo "<a href='?stock_code=$value'>$value</a>";
}

function get_stock_code($conn)
{
    echo $_GET['stock_code'];
}
