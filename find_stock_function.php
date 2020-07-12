<?php
function get_type($conn)
{
    $sql_side = "select DISTINCT stock_type from stock_information";
    $result_side = mysqli_query($conn, $sql_side);
    while ($row = mysqli_fetch_assoc($result_side))
        foreach ($row as $key => $value) {
            echo "<option value='$value'>$value</option>";
        }
}
