<?php
header('Content-Type: application/json; charset=utf-8');
require("connect.php");
$data = array();
$stock_code = $_GET['stock_code'];
$sql = "
with rsvTable AS(
	SELECT  record_date 
			,close_price 
			,idx 
			,NinedayMax 
			,NinedayMin 
			,((close_price-NinedayMin)/ (NinedayMax-NinedayMin) )*100 AS RSV
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
	)AS t 
),
k_tmp AS (
    SELECT  record_date 
       ,RSV 
       ,idx 
       ,(
            SELECT SUM( tmp2.RSV *(1/3)*POW(2/3,main2.idx-tmp2.idx) )
            FROM rsvTable AS tmp2
            WHERE tmp2.idx BETWEEN main2.idx-8 AND main2.idx 
		)AS kLine
    FROM rsvTable AS main2
)
SELECT record_date,
       RSV,
       idx,
       kLine,
       (
            SELECT SUM( tmp3.kLine *(1/3)*POW(2/3,main3.idx-tmp3.idx) )
			FROM k_tmp AS tmp3
			WHERE tmp3.idx BETWEEN main3.idx-8 AND main3.idx 
       ) AS dLine
FROM k_tmp as main3;
";
//echo $sql;
$result = mysqli_query($conn, $sql);

$data1 = array();
$data2 = array();

while ($row = mysqli_fetch_assoc($result)) {
    $tmp1 = array();
    $tmp2 = array();
    foreach ($row as $key => $value) {
        if ($key == "record_date") {
            $tmp1[] = strtotime($value) * 1000;
            $tmp2[] = strtotime($value) * 1000;
        } else if ($key == "kLine")
            $tmp1[] = round((float) $value, 3);
        else if ($key == "dLine")
            $tmp2[] = round((float) $value, 3);
    }
    $data1[] = $tmp1;
    $data2[] = $tmp2;
}
$data[] = $data1;
$data[] = $data2;
echo json_encode($data);
