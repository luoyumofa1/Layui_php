<?php
if (!session_id()) session_start();
// 生成一个PHP数组
$username=$_SESSION['username'];
$data = array();
$data['code']=0;
$data['msg']='';
$num=0;
$i=0;
$arr=array();
require("./controller/mysql_connect.php");
$sql_select = "SELECT teachername,address,rank FROM t_s WHERE studentname = '$username' AND state='0'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
$num += mysqli_num_rows($ret);//获得符合查询条件的总数
while($row = mysqli_fetch_array($ret))
{
    $var=explode("/",$row['address']);
    $arr[$i] = array('teachername'=>$row['teachername'],'address'=>$row['address'],'article'=>$var[count($var)-1],'rank'=>$row['rank']);
    $i++;
}

mysqli_query($conn, 'set names utf8');//进行编码统一
$sql_select = "SELECT teachername,address,rank FROM s_bc WHERE studentname = '$username' AND state='0'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
$num += mysqli_num_rows($ret);//获得符合查询条件的总数
while($row = mysqli_fetch_array($ret))
{
    $var=explode("/",$row['address']);
    $arr[$i] = array('teachername'=>$row['teachername'],'address'=>$row['address'],'article'=>$var[count($var)-1],'rank'=>$row['rank']);
    $i++;
}

mysqli_query($conn, 'set names utf8');//进行编码统一
$sql_select = "SELECT teachername,address,rank FROM s_sc WHERE studentname = '$username' AND state='0'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
$num += mysqli_num_rows($ret);//获得符合查询条件的总数
while($row = mysqli_fetch_array($ret))
{
    $var=explode("/",$row['address']);
    $arr[$i] = array('teachername'=>$row['teachername'],'address'=>$row['address'],'article'=>$var[count($var)-1],'rank'=>$row['rank']);
    $i++;
}

$data['count']=$num;
mysqli_close($conn);
$data['data']=$arr;
echo json_encode($data);
//$data[1] = array('2','何开','iteye.com');
// 把PHP数组转成JSON字符串
// $json_string = json_encode($data);
// // // 写入文件
// file_put_contents('test.json', $json_string);
// ?>