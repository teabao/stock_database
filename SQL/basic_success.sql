WITH ids AS (
    SELECT  *, row_number() OVER( ORDER BY record_date ) AS idx
    FROM 0050tw 
), 
condition_ids AS (
    SELECT  *, idx- row_number() OVER( ORDER BY idx ) AS diff
    FROM ids
    WHERE ( record_date BETWEEN '2008-01-01' AND '2020-05-14' ) 
        AND open_price > 20 
        AND close_price > 61 
), 
condition_count AS ( 
    SELECT  COUNT(*) AS cnt, diff
    FROM condition_ids AS cid
    GROUP BY diff
    HAVING cnt>=3 
)
SELECT *
FROM condition_count AS a
JOIN condition_ids AS b
ON a.diff=b.diff 