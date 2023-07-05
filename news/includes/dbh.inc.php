<?php
header("content-type:text/html;charset=utf-8");

$serverName = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "news";

require("./controller/mysql_connect.php");

if (!$conn) {
    die("数据库连接失败" . mysqli_connect_error());
}

// else {
//     echo "数据库连接成功", "<br/>";
// }



