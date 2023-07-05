<?php
$studentname=$_POST['studentname'];
$oldteachername=$_POST['oldteachername'];
$newteachername=$_POST['newteachername'];


require("./controller/mysql_connect.php");
$sql_update="update s_t set teachername='$newteachername',state='1' where teachername='$oldteachername'";
$ret = mysqli_query($conn, $sql_update);
mysqli_close($conn);
?>