<?php
if (!session_id()) session_start();
$pow=$_SESSION['power'];
$username=$_POST['username'];
$password=$_POST['password'];
$key1=$_POST['key1'];
$key2=$_POST['key2'];
$key3=$_POST['key3'];
require("./controller/mysql_connect.php");
$sql_update="update ".$pow." set password='$password',key1='$key1',key2='$key2',key3='$key3' where username='$username'";
$ret = mysqli_query($conn, $sql_update);
mysqli_close($conn);
echo true;
?>