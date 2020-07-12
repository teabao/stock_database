<?php
function basic_query($conn)
{
    if ($_POST['enable_basic_query'] != "true") {
        echo "[]";
        return;
    }

    $stock_code = $_POST['stock_code'];
    $day_from = $_POST['day_from'];
    $day_to = $_POST['day_to'];
    $query_type = $_POST['query_type'];
    $successive_day_count = $_POST['successive_day_count'];
    $mean_day_count = $_POST['mean_day_count'];
    $query_type = $_POST['query_type'];
    //echo $stock_code . "<br>";
    //echo $day_from . "<br>";
    //echo $day_to . "<br>";
    //echo $query_type . "<br>";

    $sql = "";

    if ($query_type == "successive") {
        $sql = "
        with ids AS (
            SELECT *, row_number() OVER( ORDER BY record_date ) AS idx
            from " . $stock_code . "tw 
        ),
        condition_ids as (
            SELECT *,idx- row_number() OVER( ORDER BY idx ) AS diff
            from ids
            where ( record_date BETWEEN '$day_from' and '$day_to' ) ";

        for ($conditionNum = 0; $_POST["condition$conditionNum"] == "true"; $conditionNum++) {
            $conditionVariable = $_POST["conditionVariable$conditionNum"];
            $conditionCompare = $_POST["conditionCompare$conditionNum"];
            $conditionValue = $_POST["conditionValue$conditionNum"];
            //echo "<br>" . "new " . $conditionNum . "<br>" . $conditionVariable . "<br>" . $conditionCompare . "<br>" . $conditionValue . "<br>";
            $sql .= " AND $conditionVariable $conditionCompare $conditionValue ";
        }

        $sql .=
            ")
        SELECT  MAX(record_date) as max_date,MIN(record_date) as min_date,COUNT(*) AS cnt, diff
        FROM condition_ids AS cid
        GROUP BY diff
        HAVING cnt>=$successive_day_count 
    ";
    } else if ($query_type == "mean") {
        $variables = "";
        for ($conditionNum = 0; $_POST["condition$conditionNum"] == "true"; $conditionNum++) {
            $conditionVariable = $_POST["conditionVariable$conditionNum"];
            $variables .= ",$conditionVariable ";
        }


        $sql = "with ids AS (
                    SELECT  *
                            ,rank() OVER( ORDER BY record_date ) AS idx
                    FROM " . $stock_code . "tw 
                ),
                condition_result as
                (
                    SELECT  * 
                        ,idx-rank() OVER( ORDER BY record_date ) AS diff
                    FROM 
                    (
                        SELECT  * ";
        //                            ,(
        //                                SELECT AVG(tmp.close_price)
        //                                FROM ids AS tmp
        //                                WHERE (tmp.idx BETWEEN main.idx-$successive_day_count+1 AND main.idx )
        //                            ) AS ma

        for ($conditionNum = 0; $_POST["condition$conditionNum"] == "true"; $conditionNum++) {
            $conditionVariable = $_POST["conditionVariable$conditionNum"];
            $sql .= "
                ,(
                    SELECT AVG(tmp$conditionNum.$conditionVariable)
                    FROM ids AS tmp$conditionNum
                    WHERE (tmp$conditionNum.idx BETWEEN main.idx-$mean_day_count+1 AND main.idx )
                ) AS condition$conditionNum
            ";
        }


        $sql .=    "FROM ids AS main
                    WHERE ( main.record_date BETWEEN '$day_from' and '$day_to' ) 
                ) as q
                WHERE 1=1 ";
        //                WHERE ma > 90";

        for ($conditionNum = 0; $_POST["condition$conditionNum"] == "true"; $conditionNum++) {
            $conditionVariable = $_POST["conditionVariable$conditionNum"];
            $conditionCompare = $_POST["conditionCompare$conditionNum"];
            $conditionValue = $_POST["conditionValue$conditionNum"];
            $sql .= " AND condition$conditionNum $conditionCompare $conditionValue ";
        }

        $sql .= " )
                SELECT MAX(record_date) as max_date,MIN(record_date) as min_date,diff
                FROM condition_result as a
                group by diff ";
    }

    //echo $sql . "<br>";

    //query_sql($conn, $sql);
    $returns = "";
    $result = mysqli_query($conn, $sql);
    $isFirst = true;
    while ($row = mysqli_fetch_row($result)) {
        if ($isFirst) {
            $isFirst = false;
            $returns .= "['" . $row[1] . "','" . $row[0] . "']";
        } else {
            $returns .= ",['" . $row[1] . "','" . $row[0] . "']";
        }
    }
    echo "[$returns]";
    echo "/*$sql*/";
}
