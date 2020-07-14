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
			FROM 0050tw 
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
)
SELECT  record_date,
        RSV, 
        idx,
        LAG(kLine,1) OVER( ORDER BY idx ) lastK,
        ( lastK*2/3 + RSV/3 ) AS kLine
        
FROM rsvTable AS main2;

