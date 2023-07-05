<?php
header("Content-Type: text/html;charset=utf-8");
$studentname=$_POST['studentname'];
$teachername=$_POST['teachername'];

//删除文件
require("./controller/mysql_connect.php");
$sql_select = "select address from s_bc where studentname='$studentname' and teachername='$teachername'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
$row = mysqli_fetch_array($ret);
$path=$row['address'];
if (unlink($path)) {
    echo "The file was deleted successfully.", "\n";
 }
 else{
    exit();
    echo "The specified file could not be deleted. Please try again.", "\n";
 }
mysqli_close($conn);


// //删除t_accept中的数据
require("./controller/mysql_connect.php");
$sql_delect = "delete from s_bc where studentname='$studentname' and teachername='$teachername'"; //准备sql语句
$ret = mysqli_query($conn, $sql_delect); //执行SQL语句
mysqli_close($conn);



