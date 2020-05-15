<?php
function query_sql($conn, $sql)
{
    if (strlen($sql) == 0) {
        echo "no query command.<br>";
        return;
    }
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        echo "<table><thead><tr>";
        for ($i = 0; $i < mysqli_num_fields($result); $i++) {
            $field_info = mysqli_fetch_field($result);
            echo "<th>$field_info->name</th>";
        }
        echo "</tr></thead>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $key => $value)
                echo "<td>$value</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results <br>";
    }
}

function insert_to_database($conn, $stock_code, $stock_name)
{
    $sql = "INSERT INTO stock_name (stock_code, chinese_name)
            VALUES ('" . $stock_code . "','" . $stock_name . "');";
    $result = mysqli_query($conn, $sql);
    echo mysqli_error($conn);
}

function file_handle($conn)
{

    $filename = "STOCK_DAY_ALL.csv";

    $fp = fopen($filename, "r");

    $i = 0;
    $filesize = filesize($filename) + 1;

    if (!$fp) {
        echo "Load failed!<br>";
    } else {
        echo "Load successfully<br>";


        while (($data = __fgetcsv($fp, $filesize))) {
            if ($i > 0) {

                for ($x = 0; $x < 10; $x++) {
                    #$out = str_replace(',', '', $data[$x]);
                    #echo $out . " ";
                    #echo str_replace('^', "&nbsp", str_pad($out, 30, '^')) .  ' ';
                }
                #echo str_replace(',', '', $data[0]) . " " . str_replace(',', '', $data[1]);
                insert_to_database($conn, str_replace(',', '', $data[0]), str_replace(',', '', $data[1]));
                echo '<br>';
            }
            $i++;
        }
    }
}

// load csv file in UTF-8 format
function __fgetcsv(&$handle, $length = null, $d = ",", $e = '"')
{
    $d = preg_quote($d);
    $e = preg_quote($e);
    $_line = "";
    $eof = false;
    while ($eof != true) {
        $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
        $itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);
        if ($itemcnt % 2 == 0) {
            $eof = true;
        }
    }

    $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));

    $_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';
    preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
    $_csv_data = $_csv_matches[1];

    for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++) {
        $_csv_data[$_csv_i] = preg_replace("/^" . $e . "(.*)" . $e . "$/s", "$1", $_csv_data[$_csv_i]);
        $_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);
    }

    return empty($_line) ? false : $_csv_data;
}

function read_sql_file($filename)
{
    $lines = file($filename);
    // Loop through each line
    foreach ($lines as $line) {
        // Skip it if it's a comment
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;

        // Add this line to the current segment
        $templine .= $line;
        $var = "what";
        // If it has a semicolon at the end, it's the end of the query
        if (substr(trim($line), -1, 1) == ';') {
            echo $templine;
            // Perform the query
            //mysql_query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
            // Reset temp variable to empty
            $templine = '';
        }
    }
}

function reconstruct($conn)
{
    $sql = "drop database stock;";
    mysqli_query($conn, $sql);
    $sql = "CREATE DATABASE stock;";
    mysqli_query($conn, $sql);
    $sql = "use stock;";
    mysqli_query($conn, $sql);
    create_stock_name($conn);
    create_history($conn);
}

function create_stock_name($conn)
{
    $sql = "CREATE TABLE stock_name(
            stock_code VARCHAR(15) NOT NULL,
            chinese_name VARCHAR(40) NOT NULL,
            PRIMARY KEY (stock_code)); ";

    $r = mysqli_query($conn, $sql);

    $filename = "STOCK_DAY_ALL.csv";

    $fp = fopen($filename, "r");

    $i = 0;
    $filesize = filesize($filename) + 1;

    while (($data = __fgetcsv($fp, $filesize))) {
        if ($i > 0) {
            insert_to_database($conn, str_replace(',', '', $data[0]), str_replace(',', '', $data[1]));
        }
        $i++;
    }
}

function create_history($conn)
{
    $sql = "select stock_code from stock_name;";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        foreach ($row as $key => $value) {
            $stock_code = "$value" . "TW";
            $sql_new = "    CREATE TABLE $stock_code(
                            record_date CHAR(15) NOT NULL,
                            open_price FLOAT,
                            high_price FLOAT,
                            low_price FLOAT,
                            close_price FLOAT,
                            adjust_price FLOAT,
                            volume INT,
                            PRIMARY KEY (record_date));";
            #echo $sql_new;
            #$result_new = mysqli_query($conn, $sql_new);
            echo "create table $value <br>";
            query_sql($conn, $sql_new);

            $sql_new = "    load data local infile 'history_data/$value.csv'
                            into table $stock_code
                            fields terminated by ','
                            ENCLOSED BY ''
                            lines terminated by '\r\n'
                            ignore 1 lines;";

            #$result_new = mysqli_query($conn, $sql_new);
            echo "load data $value <br>";
            #query_sql($conn, $sql_new);

            $filename = "history_data/$value.csv";

            $fp = fopen($filename, "r");

            $i = 0;
            $filesize = filesize($filename) + 1;


            while (($data = __fgetcsv($fp, $filesize, ",", '"'))) {
                if ($i > 0) {
                    $sql = "INSERT INTO $stock_code (record_date,open_price,high_price,low_price,close_price,adjust_price,volume)
                            VALUES (";
                    #insert_to_database($conn, str_replace(',', '', $data[0]), str_replace(',', '', $data[1]));
                    for ($i = 0; $i < count($data); $i++) {
                        $sql .= "'$data[$i]'";
                        if ($i != count($data) - 1)
                            $sql .= ",";
                    }
                    $sql .= ");";

                    $result_x = mysqli_query($conn, $sql);
                }
                $i++;
            }
        }
    }
}
