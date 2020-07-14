<?php
header('Content-Type: application/json; charset=utf-8');
require("connect.php");
$data = array();
$stock_code = $_GET['stock_code'];
$sql = "
SELECT  record_date 
       ,close_price
       ,NinedayMax 
       ,NinedayMin
       ,((close_price- NinedayMin)/ (NinedayMax-NinedayMin) )*100 AS RSV
FROM 
( 
	with ids AS (
		SELECT  record_date 
				,close_price
				,high_price
				,low_price 
				,rank() OVER( ORDER BY record_date ) AS idx
		FROM $stock_code 
	)
	SELECT  record_date 
	       ,close_price 
	       ,idx 
	       ,(
				SELECT  MAX(tmp.high_price)
				FROM ids AS tmp
				WHERE tmp.idx BETWEEN main.idx-8 AND main.idx 
			) AS NinedayMax, 
			( 
				SELECT  MIN(tmp.low_price)
				FROM ids AS tmp
				WHERE tmp.idx BETWEEN main.idx-8 AND main.idx 
			) AS NinedayMin 
	FROM ids AS main 
)AS t;
";
//echo $sql;
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $tmp = array();
    foreach ($row as $key => $value) {
        if ($key == "record_date")
            $tmp[] = strtotime($value) * 1000;
        else if ($key == "RSV")
            $tmp[] = round((float) $value, 3);
    }
    $data[] = $tmp;
}
echo json_encode($data);
