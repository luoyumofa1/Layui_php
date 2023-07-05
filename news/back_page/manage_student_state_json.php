<?php
// 生成一个PHP数组
$data = array();
$arr=array();
require("./controller/mysql_connect.php");
$sql_select = "SELECT username,state1,state2,state3,degree FROM student"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
$num = mysqli_num_rows($ret);//获得符合查询条件的总数
$data['code']=0;
$data['msg']='';
$data['count']=$num;
$i=0;

while($row = mysqli_fetch_array($ret))
{
    $arr[$i] = array('studentname'=>$row['username'],'state1'=>$row['state1'],'state2'=>$row['state2'],'state3'=>$row['state3'],'degree'=>$row['degree']);
    $i++;
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