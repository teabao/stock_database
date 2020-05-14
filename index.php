<!DOCTYPE html>
<html>

<head>
    <title>Title</title>
    <meta charset="UTF-8">
    <?php
    // stock project website

    // Date in the past
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");
    header("Content-type:text/html;charset=UTF-8;");
    setlocale(LC_ALL, 'en_US.UTF-8');


    $conn = new mysqli("localhost", "root", "fucku", "stock");
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
    $conn->set_charset("utf8");


    #file_handle($conn);
    query_sql($conn, "select * from stock_name;");

    $conn->close();



    function query_sql($conn, $sql)
    {
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo $row["stock_code"] . " " . $row["chinese_name"] . "<br>";
            }
        } else {
            echo "0 results";
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
    ?>
</head>

<body>

</body>

</html>