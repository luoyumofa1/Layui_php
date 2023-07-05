<?php
header("Content-Type: text/html;charset=utf-8");
header("Access-Control-Allow-Origin: *"); //解决跨域
header('Access-Control-Allow-Methods:post'); // 响应类型
if (!session_id()) session_start();
$studentname = $_SESSION['username'];
$month = date('Ym', time()); //获取年月
define('BASE_PATH', str_replace('\\', '/', realpath(dirname(__FILE__) . '/')) . "/");
$dir = BASE_PATH . "upload/";
$num1 = 0;
$num2 = 0;
$num3 = 0;
$degree;
$uploadnum;
//初始化返回数组
$arr = array(
    'code' => '', //返回状态
    'msg' => '', //提示消息
    'data' => array( //文件链接
        'src' => $dir . $_FILES["file"]["name"]
    ),
);


//检测当学生存在大改时不进行后续步骤
// require("./controller/mysql_connect.php");
// mysqli_query($conn, 'set names utf8'); //进行编码统一
// $sql_select = "SELECT username FROM s_bc WHERE username = '$studentname'"; //执行SQL语句
// $ret = mysqli_query($conn, $sql_select);
// if (!$ret) {
//     printf("Error: %s\n", mysqli_error($con));
//     exit();
// }
// $row = mysqli_fetch_array($ret);
// if (!empty($row['studentname'])) {
//     $arr['msg'] ='大改';
//     echo json_encode($arr);
//     exit();
// }
// mysqli_close($conn);

//读取学生信息中的三种状态的数量
require("./controller/mysql_connect.php");
$sql_select = "SELECT state1,state2,state3,degree,username,uploadnum FROM student WHERE username = '$studentname'"; //执行SQL语句
$ret = mysqli_query($conn, $sql_select);
if (!$ret) {
   // printf("Error: %s\n", mysqli_error($con));
    $arr['msg']=mysqli_error($con);
    echo json_encode($arr);
    exit();
}
$row = mysqli_fetch_array($ret);
if (!empty($row['username'])) {
    $num1 = $row['state1'];
    $num2 = $row['state2'];
    $num3 = $row['state3'];
    $degree = $row['degree'];
    $uploadnum = $row['uploadnum'];
}
mysqli_close($conn);


if($uploadnum==2)
{
    $arr['msg']='你已经达到最大提交次数,请勿重复提交，耐心等待老师批改或者联系管理员进行处理';
    echo json_encode($arr);
    exit();

}

$file_info = $_FILES['file']; //前端传过来的文件
$file_error = $file_info['error'];
if (!is_dir($dir)) //判断目录是否存在
{
    mkdir($dir, 0777, true); //如果目录不存在则创建目录
};
$file = $dir . $_FILES["file"]["name"];

if (!file_exists($file)) {
    if ($file_error == 0) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $dir.$_FILES["file"]["name"])) {
            $arr['msg'] = "上传成功,请耐心等待老师回执";
            //更新上传次数数据
            $n=$uploadnum+1;
            require("./controller/mysql_connect.php");
            $sql_update="update student set uploadnum='$n' where username='$studentname'";
            $ret = mysqli_query($conn, $sql_update);
            mysqli_close($conn);
        } else {
            $arr['msg'] = "上传失败";
        }
    } else {
        switch ($file_error) {
            case 1:
                $arr['msg'] = '上传文件超过了PHP配置文件中upload_max_filesize选项的值';
                break;
            case 2:
                $arr['msg'] = '超过了表单max_file_size限制的大小';
                break;
            case 3:
                $arr['msg'] = '文件部分被上传';
                break;
            case 4:
                $arr['msg'] = '没有选择上传文件';
                break;
            case 6:
                $arr['msg'] = '没有找到临时文件';
                break;
            case 7:
            case 8:
                $arr['msg'] = '系统错误';
                break;
        }
    }
} else {
    //move_uploaded_file($_FILES["file"]["tmp_name"],$dir. $_FILES["file"]["name"]);
    //重复上传会覆盖上次的文件
    if ($degree == '硕士研究生') {
        if ($num1 + $num2 + $num3 < 2) {
            $arr['msg'] = '文件已存在,请耐心等待老师回执';
        }
        if ($num1 + $num2 + $num3 == 2) {
            if ($num1 == 2) {
                $arr['msg'] = '你的论文已被成功批阅,请耐心等待答辩';
            }
            if ($num1 == 1 && $num2 == 1) {
                //在这里进行文件的状态覆盖s_t,t_s等表
                $n=$uploadnum+1;
                require("./controller/mysql_connect.php");
                //更新每个学生的上传次数
                $sql_update="update student set uploadnum='$n' where username='$studentname'";
                $ret = mysqli_query($conn, $sql_update);
                //覆盖掉之前的记录
                $sql_update="update t_s set state='1' where studentname='$studentname'";
                $ret = mysqli_query($conn, $sql_update);
                $sql_update="update s_sc set state='1' where studentname='$studentname'";
                $ret = mysqli_query($conn, $sql_update);
                $sql_update="update s_bc set state='1' where studentname='$studentname'";
                $ret = mysqli_query($conn, $sql_update);
                //更新学生表中的三个状态
                $sql_update="update student set state1='0',state2='0',state3='0' where username='$studentname'";
                $ret = mysqli_query($conn, $sql_update);
                //获取小改的老师
                $sql_select = "SELECT teachername FROM s_sc WHERE studentname = '$studentname'"; //执行SQL语句
                $ret = mysqli_query($conn, $sql_select);
                $row = mysqli_fetch_array($ret);
                $arr['code']=$row['teachername'];
                mysqli_close($conn);

                //文件重新上传
                $time=mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y"));
                $filename=$dir.date("his")."-".$_FILES["file"]["name"];
                move_uploaded_file($_FILES["file"]["tmp_name"],$filename);
                $arr['data']['src']=$filename;
                $arr['msg'] = '你的论文已被重新提交';
            }
            if($num3==1||$num3==2)
            {
                require("./controller/mysql_connect.php");
                //更新每个学生的上传次数
                $sql_update="update t_s set state='1' where studentname='$studentname'";
                $ret = mysqli_query($conn, $sql_update);
                $sql_update="update s_sc set state='1' where studentname='$studentname'";
                $ret = mysqli_query($conn, $sql_update);
                $sql_update="update s_bc set state='1' where studentname='$studentname'";
                $ret = mysqli_query($conn, $sql_update);
                mysqli_close($conn);
                $arr['msg'] = '你的论文含有大改，请联系老师';
            }
        }
    }
    if ($degree == '博士研究生') {
        if ($num1 + $num2 + $num3 < 3) {
            $arr['msg'] = '文件已存在,请耐心等待老师回执';
        }
        if ($num1 + $num2 + $num3 === 3) {
        }
    }
}

echo json_encode($arr);
