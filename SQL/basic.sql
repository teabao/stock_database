with ids AS (
    SELECT *
        ,rank() OVER( ORDER BY record_date ) AS idx
    FROM 0050tw 
),
condition_ids AS (
    SELECT *,rank() OVER( ORDER BY record_date ) AS con_idx
    FROM ids
    WHERE  ( record_date BETWEEN '2008-01-01' and '2020-05-14' ) 
    AND open_price > 20 
    AND close_price > 61
),
condition_count AS(
    SELECT con_idx-idx as diff, count(*)
    FROM condition_ids AS main
    group by diff
)
SELECT * from condition_count
