<?php
$studentname=$_POST['studentname'];
$teachername=$_POST['teachername'];

//更新数据
require("./controller/mysql_connect.php");
$sql_update="update s_t set state='1' where studentname='$studentname' and teachername='$teachername'";
$ret = mysqli_query($conn, $sql_update);
mysqli_close($conn);
?>