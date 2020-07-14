<?php
function check_msg($conn)
{
    if ($_POST['new_msg'] != "true")
        return;

    $stock_code = $_GET['stock_code'];
    $name = $_POST['msg_name'];
    $text = $_POST['msg_text'];

    $name = preg_replace('/[\'\"\/\\\,\.\~\:\;]/', '', $name);
    $text = preg_replace('/[\'\"\/\\\,\.\~\:\;]/', '', $text);

    $time = date("M,d,Y H:i:s");
    //echo  $stock_code . " " . $name . " " . $text . " " . $time;

    $result_tmp = mysqli_query($conn, "select max(idx) as max_value from stock_msg where stock_code like '$stock_code';");

    $row = mysqli_fetch_assoc($result_tmp);

    //echo  "select max(idx) as max_value from stock_msg where stock_code like '$stock_code';";
    //echo $row['max_value'];
    $new_idx = 1;
    if ($row['max_value'] != "")
        $new_idx = intval($row['max_value']) + 1;



    $sql = "INSERT INTO stock_msg
            VALUES ('$stock_code',$new_idx,'$name','$text','$time');";

    echo $sql;
    mysqli_query($conn, $sql);
}

function show_msg($conn)
{
    $stock_code = $_GET['stock_code'];
    $sql = "select nickname as m_name,msg as m_text,timing as m_time from stock_msg where stock_code like '$stock_code';";

    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($row as $key => $value)
            echo "<td class='$key'>$value</td>";
        echo "</tr>";
    }
}
