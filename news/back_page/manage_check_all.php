<?php
$data=$_POST['data'];
$data=json_decode($data);

require("./controller/mysql_connect.php");
for($i=0;$i<count($data);$i++)
{
    $studentname=$data[$i]->studentname;
    $teachername=$data[$i]->teachername;
    $sql_update="update s_t set state='1' where studentname='$studentname' and teachername='$teachername'";
    $ret = mysqli_query($conn, $sql_update);
}
mysqli_close($conn);

?>