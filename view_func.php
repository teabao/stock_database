<?php
function get_side_bar($conn)
{
    $sql_side = "select stock_code,chinese_name from stock_name;";
    $result_side = mysqli_query($conn, $sql_side);
    while ($row = mysqli_fetch_assoc($result_side))
        foreach ($row as $key => $value) {
            if ($key == "stock_code")
                echo "<tr onclick=\"document.location = '?stock_code=$value'\">
                <td>$value</td>";
            else
                echo "<td>$value</td></tr>";
        }
}

function get_stock_code($conn)
{
    echo $_GET['stock_code'];
}
