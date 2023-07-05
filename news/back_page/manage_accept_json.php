<?php
// 生成一个PHP数组
$data = array();
$arr=array();
require("./controller/mysql_connect.php");
$sql_select = "SELECT studentname,teachername,address,time FROM s_t WHERE state='1'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
$num = mysqli_num_rows($ret);//获得符合查询条件的总数
$data['code']=0;
$data['msg']='';
$data['count']=$num;
$i=0;
$time1=mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y"));

while($row = mysqli_fetch_array($ret))
{
    $time=round(($time1-$row['time'])/3600,2);
    $arr[$i] = array('studentname'=>$row['studentname'],'teachername'=>$row['teachername'],'address'=>$row['address'],'time'=>$time);
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