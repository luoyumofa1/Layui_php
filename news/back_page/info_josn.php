<?php
if (!session_id()) session_start();
// 生成一个PHP数组
$username=$_SESSION['username'];
$pow=$_SESSION['power'];
$data = array();
$arr=array();
require("./controller/mysql_connect.php");
$sql_select = "SELECT username,password,key1,key2,key3 FROM $pow WHERE username = '$username'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
$num = mysqli_num_rows($ret);//获得符合查询条件的总数
$data['code']=0;
$data['msg']='';
$data['count']=$num;
$i=0;
while($row = mysqli_fetch_array($ret))
{
    $arr[$i] = array('username'=>$row['username'],'password'=>$row['password'],'key1'=>$row['key1'],'key2'=>$row['key2'],'key3'=>$row['key3']);
}
mysqli_close($conn);
$data['data']=$arr;
echo json_encode($data);
//$data[1] = array('2','何开','iteye.com');
// 把PHP数组转成JSON字符串
// $json_string = json_encode($data);
// // // 写入文件
// file_put_contents('test.json', $json_string);
// ?>