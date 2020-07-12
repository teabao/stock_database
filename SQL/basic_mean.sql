    with ids AS (
        SELECT  *
                ,rank() OVER( ORDER BY record_date ) AS idx
        FROM 0050tw as source
    )
    SELECT *
    FROM(
        SELECT *
                ,(
                    SELECT AVG(tmp.close_price)
                    FROM ids AS tmp
                    WHERE (tmp.idx BETWEEN main.idx-4 AND main.idx )
                ) AS ma
        FROM ids AS main
        WHERE ( main.record_date BETWEEN '2008-01-07' AND '2020-05-14' ) 
    ) as q
    WHERE ma > 90