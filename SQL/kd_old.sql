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
    )AS t 
)
SELECT  record_date 
       ,RSV 
       ,idx 
       ,(
            SELECT  SUM( tmp2.RSV *(1/3)*POW(2/3,main2.idx-tmp2.idx) )
            FROM rsvTable AS tmp2
            WHERE tmp2.idx BETWEEN main2.idx-8 AND main2.idx 
        )AS kLine,
        ( 
            SELECT  SUM( IF (main2.idx=tmp3.idx ,tmp3.RSV *(1/9)*POW(2/3,main2.idx-tmp3.idx) ,tmp3.RSV *(2/9)*POW(2/3,main2.idx-tmp3.idx) ))
            FROM rsvTable AS tmp3
            WHERE tmp3.idx BETWEEN main2.idx-8 AND main2.idx 
        )AS dLine 
FROM rsvTable AS main2;