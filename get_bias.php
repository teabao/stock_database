<?php
header('Content-Type: application/json; charset=utf-8');
require("connect.php");
$data = array();
$stock_code = $_GET['stock_code'];
$day_count = $_GET['day_count'];
$sql = "
SELECT  record_date 
       ,(close_price -MA)/MA AS BIAS
FROM 
( 
    with ids AS (
        SELECT  record_date 
                ,close_price 
                ,rank() OVER( ORDER BY record_date ) AS idx
        FROM $stock_code
    )
    SELECT  record_date 
        ,close_price 
        ,idx 
        ,(
                SELECT  AVG(tmp.close_price)
                FROM ids AS tmp
                WHERE tmp.idx BETWEEN main.idx-$day_count+1 AND main.idx 
        ) AS MA
    FROM ids AS main
) AS t;
";
//echo $sql;
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $tmp = array();
    foreach ($row as $key => $value) {
        if ($key == "record_date")
            $tmp[] = strtotime($value) * 1000;
        else if ($key == "BIAS")
            $tmp[] = round((float) $value, 3);
    }
    $data[] = $tmp;
}
echo json_encode($data);
