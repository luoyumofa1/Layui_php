<?php
header("Content-Type: text/html;charset=utf-8");
if (!session_id()) session_start();
$address=$_POST['address'];
$username=$_POST['studentname'];
$teaname=$_SESSION['username'];


//删除原来数据
require("./controller/mysql_connect.php");
$sql_select = "delete from s_t where studentname='$username' and teachername='$teaname'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
mysqli_close($conn);


//插入数据
require("./controller/mysql_connect.php");
$time=mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y"));
$sql_select = "insert into t_accept (studentname,teachername,address,time) values ('$username','$teaname','$address','$time')"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
mysqli_close($conn);
?>