# stock_database
This is a project about stock website.  

# table describe


### stock_information
> 紀錄每個類股的資料(不含ETF)  
> 參考 https://isin.twse.com.tw/isin/C_public.jsp?strMode=2

| Field        | Type        | Null | Key | Default | Extra |
|--------------|-------------|------|-----|---------|-------|
| stock_code   | varchar(15) | NO   | PRI | NULL    |       |
| chinese_name | varchar(40) | YES  |     | NULL    |       |
| ISIN_code    | varchar(15) | YES  |     | NULL    |       |
| listing_date | varchar(15) | YES  |     | NULL    |       |
| stock_type   | varchar(40) | YES  |     | NULL    |       |


### all_stock
> 記錄所有個股資料

| Field        | Type     | Null | Key | Default | Extra |
|--------------|----------|------|-----|---------|-------|
| stock_code   | char(15) | NO   | PRI | NULL    |       |
| record_date  | char(15) | NO   | PRI | NULL    |       |
| open_price   | float    | YES  |     | NULL    |       |
| high_price   | float    | YES  |     | NULL    |       |
| low_price    | float    | YES  |     | NULL    |       |
| close_price  | float    | YES  |     | NULL    |       |
| adjust_price | float    | YES  |     | NULL    |       |
| volume       | int(11)  | YES  |     | NULL    |       |
  
### stock_code+tw
> 像是 0050tw，代表該股歷史每一天的資料

| Field        | Type     | Null | Key | Default | Extra |
|--------------|----------|------|-----|---------|-------|
| record_date  | char(15) | NO   | PRI | NULL    |       |
| open_price   | float    | YES  |     | NULL    |       |
| high_price   | float    | YES  |     | NULL    |       |
| low_price    | float    | YES  |     | NULL    |       |
| close_price  | float    | YES  |     | NULL    |       |
| adjust_price | float    | YES  |     | NULL    |       |
| volume       | int(11)  | YES  |     | NULL    |       |

### stock_msg

| Field      | Type        | Null | Key | Default | Extra |
|------------|-------------|------|-----|---------|-------|
| stock_code | varchar(15) | NO   | PRI | NULL    |       |
| idx        | int(11)     | NO   | PRI | NULL    |       |
| nickname   | varchar(25) | YES  |     | NULL    |       |
| msg        | varchar(45) | YES  |     | NULL    |       |
| timing     | varchar(45) | YES  |     | NULL    |       |