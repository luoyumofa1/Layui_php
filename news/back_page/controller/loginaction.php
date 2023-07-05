<?php
header("Content-Type: text/html;charset=utf-8");
// $Id:$ //声明变量
$username = isset($_POST['username']) ? $_POST['username'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";
$power=isset($_POST['rank']) ? $_POST['rank'] : "";
if (!session_id()) session_start();
$_SESSION['username']=$username;
$_SESSION['power']=$power;
if (!empty($username) && !empty($password)) { //建立连接
    require("./mysql_connect.php");
    $sql_select = "SELECT username,password FROM $power WHERE username = '$username' AND password = '$password'"; //执行SQL语句
    $ret = mysqli_query($conn, $sql_select);
    if (!$ret) 
    {
    printf("Error: %s\n", mysqli_error($con));
    exit();
    }
    $row = mysqli_fetch_array($ret); //判断用户名或密码是否正确
    if ($username === $row['username'] && $password === $row['password'])
    { 
        echo "success";
        mysqli_close($conn);
    }
    else
    {

        echo "false";
        mysqli_close($conn);
        //用户名或密码错误，赋值err为1
        //header("Location:./login.php?err=1&we=");
    }
} else { //用户名或密码为空，赋值err为2
    //header("Location:./login.php?err=2");

    echo "false";
    mysqli_close($conn); 
}
