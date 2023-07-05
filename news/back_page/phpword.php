<?php
require_once 'bootstrap.php';

use PhpOffice\PhpWord\TemplateProcessor;

define('BASE_PATH', str_replace('\\', '/', realpath(dirname(__FILE__) . '/')) . "/");

if (!session_id()) session_start();
$studentname = $_POST['studentname'];
$teachername = $_SESSION['username'];
$rank = $_POST['rank'];
$contents = $_POST['contents'];
$contents=str_replace("\n","<w:br/>    ",$contents);
// 根据模板生成用户信息word
$name = date("his") . " " . $teachername . "-" . $studentname;//防止命名冲突
$templateProcessor = new TemplateProcessor("./word/工程硕士论文预审意见表.docx");
$templateProcessor->setValue('user', $studentname);
$templateProcessor->setValue('contents', $contents);
$templateProcessor->setValue('cin', '');
$templateProcessor->saveAs("./word/" . $name . ".docx");


// //删除t_accept中的数据
require("./controller/mysql_connect.php");
$sql_select = "delete from t_accept where studentname='$studentname' and teachername='$teachername'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select); //执行SQL语句
mysqli_close($conn);


// //判断s_bc数据库读取数据是否为空
// require("./controller/mysql_connect.php");
// mysqli_query($conn, 'set names utf8'); //进行编码统一
// $sql_select = "SELECT studentname FROM s_bc WHERE studentname = '$studentname'"; //执行SQL语句
// $ret = mysqli_query($conn, $sql_select);
// if (!$ret) {
//     printf("Error: %s\n", mysqli_error($con));
//     exit();
// }
// $row = mysqli_fetch_array($ret);
// if (!empty($row['studentname'])) {
//     exit();
// }
// mysqli_close($conn);
$num1;
$num2;
$num3;
require("./controller/mysql_connect.php");
$sql_select = "SELECT state1,state2,state3 FROM student WHERE username = '$studentname'"; //执行SQL语句
$ret = mysqli_query($conn, $sql_select);
$row = mysqli_fetch_array($ret);
$num1 = $row['state1'];
$num2 = $row['state2'];
$num3 = $row['state3'];
mysqli_close($conn);



//将学生信息进行数据库的录入
$path = BASE_PATH . "word/" . $name . ".docx";
//通过
if ($rank === "0") {
    require("./controller/mysql_connect.php");
    if (!$conn) {
        die("连接错误: " . mysqli_connect_error());
    }
    $n=$num1+1;
    mysqli_query($conn, 'set names utf8'); //进行编码统一
    $sql_insert = "insert into t_s (studentname,teachername,address,rank,state) values ('$studentname','$teachername','$path','通过','0')";
    mysqli_query($conn, $sql_insert); //执行SQL语句
    $sql_update="update student set state1='$n' where username='$studentname'";
    $ret = mysqli_query($conn, $sql_update);
    mysqli_close($conn);
}
//小改
if ($rank === "1") {
    $n=$num2+1;
    require("./controller/mysql_connect.php");
    $sql_insert = "insert into s_sc (studentname,teachername,address,rank,state) values ('$studentname','$teachername','$path','小改','0')";
    mysqli_query($conn, $sql_insert); //执行SQL语句
    $sql_update="update student set state2='$n' where username='$studentname'";
    $ret = mysqli_query($conn, $sql_update);
    mysqli_close($conn);
}
//大改
if ($rank === "2") {
    require("./controller/mysql_connect.php");
    if (!$conn) {
        die("连接错误: " . mysqli_connect_error());
    }
    $n=$num3+1;
    mysqli_query($conn, 'set names utf8'); //进行编码统一
    $sql_insert = "insert into s_bc (studentname,teachername,address,rank,state) values ('$studentname','$teachername','$path','大改','0')";
    mysqli_query($conn, $sql_insert); //执行SQL语句
    $sql_update="update student set state3='$n' where username='$studentname'";
    $ret = mysqli_query($conn, $sql_update);
    // //删除之前所有有关该学生的记录
    // $sql_select = "delete from s_t where studentname='$studentname'"; //准备sql语句
    // $ret = mysqli_query($conn, $sql_select); //执行SQL语句
    // $sql_select = "delete from t_accept where studentname='$studentname'"; //准备sql语句
    // $ret = mysqli_query($conn, $sql_select); //执行SQL语句
    // $sql_select = "delete from t_s where studentname='$studentname'"; //准备sql语句
    // $ret = mysqli_query($conn, $sql_select); //执行SQL语句
    mysqli_close($conn);
}
