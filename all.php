<?php
require("connect.php");
//(stock_code,record_date,open_price,high_price,low_price,close_price,adjust_price,volume)
$insert = "INSERT INTO all_stock 
        VALUES ("; // );

$sql = "select * from ";

$result_all = mysqli_query($conn, "select stock_code from stock_name;");

$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");

while ($row_all = mysqli_fetch_row($result_all)) {
    foreach ($row_all as $key_all => $value_all) {
        //echo "$value_all";
        $sql = "select * from " . $value_all . "tw";
        $result = mysqli_query($conn, $sql);
        echo "$value_all <br>";
        while ($row = mysqli_fetch_row($result)) {
            $insert_sql = $insert . "'" . $value_all  . "tw'";
            foreach ($row as $key => $value) {
                //echo "$value ";
                if ($key == "record_date")
                    $insert_sql .= ",'" . $value . "'";
                else
                    $insert_sql .= "," . $value;
            }
            $insert_sql .= ");";
            fwrite($myfile, $insert_sql);
            //echo "$insert_sql<br>";
            //$result_insert = mysqli_query($conn, $insert_sql);
        }
    }
}

fclose($myfile);
