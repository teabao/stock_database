CREATE TABLE $$$var$$$(
    record_date CHAR(15) NOT NULL,
    open_price FLOAT NOT NULL,
    high_price FLOAT NOT NULL,
    low_price FLOAT NOT NULL,
    close_price FLOAT NOT NULL,
    adjust_price FLOAT NOT NULL,
    volume INT NOT NULL,
    PRIMARY KEY (record_date)
);

load data local infile 'history_data/'
into table champ
fields terminated by ','
ENCLOSED BY '"'
lines terminated by '\r\n'
ignore 1 lines;
