<?php
$a = date("2020/1/4");
//echo date("2020/1/4");
require("connect.php");
require("function.php");
//create_information($conn);
//echo date("Y-m-d", strtotime("2020/1/4"));
$sql = "
select stock_code,listing_date from stock_information;
";
//echo $sql;
$result = mysqli_query($conn, $sql);
//$myfile = fopen("in.txt", "w") or die("Unable to open file!");
while ($row = mysqli_fetch_assoc($result)) {
    $s = "update stock_information set listing_date='";
    $s2 = "";
    foreach ($row as $key => $value) {
        if ($key == "listing_date") {
            $s .= date("Y-m-d", strtotime($value)) . "' ";
        } else if ($key == "stock_code") {
            $s2 .= " where stock_code='$value" . "';";
        }
    }
    $s .= $s2;
    echo $s . "<br>";

    $result2 = mysqli_query($conn, $s);
}

//fclose($myfile);
