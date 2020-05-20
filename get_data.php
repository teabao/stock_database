<?php
header('Content-Type: application/json; charset=utf-8');
require("connect.php");
$data = array();
$stock_code = $_GET['stock_code'];
$result = mysqli_query($conn, "select * from $stock_code;");
while ($row = mysqli_fetch_assoc($result)) {
    $tmp = array();
    foreach ($row as $key => $value) {
        if ($key == "record_date")
            $tmp[] = strtotime($value) * 1000;
        else if ($key == "volume")
            $tmp[] = (int) $value;
        else
            $tmp[] = (float) $value;
    }
    $data[] = $tmp;
}
$obj = (object) array();
//echo $stock_code;
$stock_code_num = substr($stock_code, 0, strlen($stock_code) - 2);
$result_2 = mysqli_query($conn, "select chinese_name from stock_name where stock_code = '$stock_code_num';");
while ($rows = mysqli_fetch_assoc($result_2)) {
    $obj->chinese_name = $rows["chinese_name"];
}


$obj->code = 1;
$obj->data = $data;
echo json_encode($obj);
