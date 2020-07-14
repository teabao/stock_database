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

function get_list($conn)
{
    if ($_POST['enable_query'] != 'true')
        return;

    $stock_type = $_POST['stock_type'];
    $day_from = $_POST['day_from'];
    $day_to = $_POST['day_to'];
    $q_name = $_POST['q_name'];
    $q_code = $_POST['q_code'];

    $q_name = preg_replace('/[\'\"\/\\\,\.\~\:\;]/', '', $q_name);
    $q_code = preg_replace('/[\'\"\/\\\,\.\~\:\;]/', '', $q_code);

    $sql = "
        select *
        from stock_information
        where (listing_date BETWEEN '$day_from' AND '$day_to')
        AND stock_code REGEXP '$q_code'
        AND chinese_name REGEXP '$q_name'";

    if ($stock_type != 'all')
        $sql .= " AND stock_type like '$stock_type'";
    $sql .= ";";


    //echo $sql . "<br>";
    //query_sql($conn, $sql);

    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        echo "<table><thead><tr>";
        for ($i = 0; $i < mysqli_num_fields($result); $i++) {
            $field_info = mysqli_fetch_field($result);
            echo "<th>$field_info->name</th>";
        }
        echo "</tr></thead>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr class='tr_click' onclick=\"document.location = 'index.php?stock_code=" . $row['stock_code'] . "'\">";
            foreach ($row as $key => $value)
                echo "<td>$value</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results <br>";
    }
}
