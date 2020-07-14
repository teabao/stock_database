with ids AS (
    SELECT  *
            ,rank() OVER( ORDER BY record_date ) AS idx
    FROM 0050tw as source
),
condition_result as
(
    SELECT  * 
        ,idx-rank() OVER( ORDER BY record_date ) AS diff
    FROM 
    (
        SELECT  *
                ,(
                    SELECT  AVG(tmp.close_price)
                    FROM ids AS tmp
                    WHERE (tmp.idx BETWEEN main.idx-4 AND main.idx ) 
                ) AS ma 
        FROM ids AS main
        WHERE ( main.record_date BETWEEN '2008-01-07' AND '2020-05-14' )  
    ) AS q
    WHERE ma > 70
)   
SELECT MAX(record_date) AS max_date
    ,MIN(record_date) AS min_date,diff
FROM condition_result AS a
group by diff