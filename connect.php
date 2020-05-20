<?php
#ini_set('display_errors', 'off');    # 關閉錯誤輸出
error_reporting(E_ALL & ~E_NOTICE); # 設定輸出錯誤類型

// Date in the past
#header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
#header("Cache-Control: no-cache");
#header("Pragma: no-cache");
#header("Content-type:text/html;charset=UTF-8;");
#setlocale(LC_ALL, 'en_US.UTF-8');

$server = "localhost";
$dbname =  "stock";
$user = "root";
$password = "fucku";

$conn = new mysqli("localhost", "root", "fucku", "stock");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$conn->set_charset("utf8");

#$conn = new \PDO("mysql:host=$server;dbname=$database;", "$user", "$password", array(
#    PDO::MYSQL_ATTR_LOCAL_INFILE => true,
#));
