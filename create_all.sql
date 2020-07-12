CREATE TABLE all_stock(
    stock_code CHAR(15) NOT NULL,
    record_date CHAR(15) NOT NULL,
    open_price FLOAT,
    high_price FLOAT,
    low_price FLOAT,
    close_price FLOAT,
    adjust_price FLOAT,
    volume INT,
    PRIMARY KEY (record_date,stock_code)
);

CREATE TABLE stock_types(
    stock_type_id INT NOT NULL,
    stock_type varchar(40) NOT NULL,
    PRIMARY KEY (stock_type_id)
);