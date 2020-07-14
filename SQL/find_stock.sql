select *
from stock_information
where (listing_date BETWEEN '1900-01-01' AND '2020-05-14')
AND stock_code REGEXP "23"
AND chinese_name REGEXP "台積"
AND stock_type like "水泥工業";