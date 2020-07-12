SELECT  record_date 
       ,(close_price -MA5)/MA5 AS BIAS
FROM 
( 
    with ids AS (
        SELECT  record_date 
                ,close_price 
                ,rank() OVER( ORDER BY record_date ) AS idx
        FROM 0050tw 
    )
	SELECT  record_date 
	       ,close_price 
	       ,idx 
	       ,(
                SELECT  AVG(tmp.close_price)
                FROM ids AS tmp
                WHERE tmp.idx BETWEEN main.idx-4 AND main.idx 
            ) AS MA5 
	FROM ids AS main 
) AS t;