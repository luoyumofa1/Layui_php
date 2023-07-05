<?php
header("Content-Type: text/html;charset=utf-8");
if (!session_id()) session_start();
$address=$_POST['address'];
$username=$_POST['studentname'];
$teaname=$_SESSION['username'];
class people{
    var $username;
    var $key1;
    var $key2;
    var $key3;
}
$student=new people();//被匹配的学生的信息
$teacher=array();//数据库中所有老师的信息
$limittea=array();//被筛选出来的老师
$sumresult=array();//初次匹配结果
$result=array();//最终筛选结果


//找到s_t与student表中已经为次学生分配过的老师和学生自己导师，防止重复分配
require("./controller/mysql_connect.php");
$sql_select = "SELECT teachername FROM s_t WHERE studentname = '$username'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
while($row = mysqli_fetch_array($ret))
{
   array_push($limittea,$row['teachername']);
}
$sql_select = "SELECT teacher FROM student WHERE username = '$username'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
while($row = mysqli_fetch_array($ret))
{
   array_push($limittea,$row['teacher']);
}
mysqli_close($conn);


//删除原来数据
require("./controller/mysql_connect.php");
$sql_select = "delete from s_t where studentname='$username' and teachername='$teaname'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
mysqli_close($conn);

//进行关于学生论文的匹配机制
require("./controller/mysql_connect.php");
$sql_select = "SELECT username,key1,key2,key3 FROM student WHERE username = '$username'"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
while($row = mysqli_fetch_array($ret))
{
    $student->username=$row['username'];
    $student->key1=$row['key1'];
    $student->key2=$row['key2'];
    $student->key3=$row['key3'];
}
mysqli_close($conn);


//取出所有数据库中所有老师的信息并存放在array中
require("./controller/mysql_connect.php");
$sql_select = "SELECT username,key1,key2,key3 FROM teacher"; //准备sql语句
$ret = mysqli_query($conn, $sql_select);//执行SQL语句
while($row = mysqli_fetch_array($ret))
{
    $tea=new people();
    $tea->username=$row['username'];
    $tea->key1=$row['key1'];
    $tea->key2=$row['key2'];
    $tea->key3=$row['key3'];
    array_push($teacher,$tea);
}
mysqli_close($conn);


//进行关键词匹配并提取出符合条件的老师
for($i=0;$i<count($teacher);$i++)
{
       $num=0;
       if($student->key1)
       {
            if($teacher[$i]->key1===$student->key1)
            $num++;
            if($teacher[$i]->key2===$student->key1)
            $num++;
            if($teacher[$i]->key3===$student->key1)
            $num++;
       }
       if($student->key2)
       {
            if($teacher[$i]->key1===$student->key2)
            $num++;
            if($teacher[$i]->key2===$student->key2)
            $num++;
            if($teacher[$i]->key3===$student->key2)
            $num++;
       }
       if($student->key3)
       {
            if($teacher[$i]->key1===$student->key3)
            $num++;
            if($teacher[$i]->key2===$student->key3)
            $num++;
            if($teacher[$i]->key3===$student->key3)
            $num++;
       }
       if($num>=1)
       {
          array_push($sumresult,$teacher[$i]->username);
       }
       $num=0;
}

/* //去除取得元素中自己导师的名字
$a=array("red","green","blue");
$index=array_search("red",$a);
echo $index;
unset($a[$index]);
array_splice($a,$index,1);
echo "\r\n";
echo count($a); */

//清空$result数组
if(count($result)===0)
;
else 
{
    array_splice($result,0,count($result));//当被清除数组为0时可能会出现一些异常
}

//对字符串数组去重
 $limittea = array_flip(array_flip($limittea));  
 sort($limittea); //排序  


//将影响因素从总数中去除
for($i=0;$i<count($limittea);$i++)
{
    $index=array_search($limittea[$i],$sumresult);
    array_splice($sumresult,$index,1);
}
//硕2博3
while(count($result)!==1)
{
    $index=rand(0,count($sumresult)-1);
    array_push($result,$sumresult[$index]);
    array_splice($sumresult,$index,1);
}
//将检查得到的结果进行数据库的写入
require("./controller/mysql_connect.php");
if (!$conn)
{
    die("连接错误: " . mysqli_connect_error());
}
mysqli_query($conn, 'set names utf8');//进行编码统一
for($i=0;$i<count($result);$i++)
{
    $teaname=$result[$i];
  
        $time=mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y"));
        $sql_insert="insert into s_t (studentname,teachername,address,time,state) values ('$username','$teaname','$address','$time','0')";
        mysqli_query($conn, $sql_insert);//执行SQL语句   
}
mysqli_close($conn);
?>
