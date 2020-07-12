SELECT  record_date 
       ,close_price
       ,NinedayMax 
       ,NinedayMin
       ,((close_price-NinedayMin)/ (NinedayMax-NinedayMin) )*100 AS RSV
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
				SELECT  MAX(tmp.close_price)
				FROM ids AS tmp
				WHERE tmp.idx BETWEEN main.idx-8 AND main.idx
			) AS NinedayMax, 
			( 
				SELECT  MIN(tmp.close_price)
				FROM ids AS tmp
				WHERE tmp.idx BETWEEN main.idx-8 AND main.idx 
			) AS NinedayMin 
	FROM ids AS main 
)AS t;