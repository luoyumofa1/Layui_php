<?php
// 启动session服务
if (!session_id()) session_start();

// if (isset($_GET['target'])) {
//     if ($_GET['target'] == 'login') {
//         // 用户登录成功, 保存session信息
//         $_SESSION['username'] = $_GET['username'];
//         $_SESSION['id'] = $_GET['id'];
//         $_SESSION['head_pic'] = $_GET['head_pic'];
//         $_SESSION['user_type'] = $_GET['user_type'];
//     }
// }

// 登录拦截
if (!(isset($_SESSION['username']))) {
    header("Location: ../login.php");
}

// 用户权限拦截
// if (isset($_SESSION['user_type'])) {
//     if ($_SESSION['user_type'] != 0) {
//         // 普通用户不能进入到后台界面
//         header("Location: ../front_page/index_article.php?msg=not_allow");
//     }
// }
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.1, maximum-scale=2.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php
    require("dependencies.php");
    ?>
    <title>后台系统</title>
    <style>
        #menu-list dl dd a {
            padding: 0 30px;
        }
    </style>
</head>
<body>
<div class="layui-layout layui-layout-admin">

    <div class="layui-header">
        <a>
            <div class="layui-logo layui-hide-xs layui-bg-black">文章分发评阅系统</div>
        </a>
        <!-- 头部区域（可配合layui 已有的水平导航） -->
        <ul class="layui-nav layui-layout-left">
            <!-- 移动端显示 -->
            <li
                    class="layui-nav-item layui-show-xs-inline-block layui-hide-sm"
                    lay-header-event="menuLeft"
            >
                <i class="layui-icon layui-icon-spread-left"></i>
            </li>
        </ul>
        
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item layui-hide layui-show-md-inline-block">
                <a href="javascript:;">
                    <?php
                    // 输出用户名
                    if (isset($_SESSION['username'])) {
                        $name = $_SESSION['username'];
                        echo $name;
                    } else {
                        echo "默认用户";
                    }
                    ?>
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;">个人账号</a></dd>
                    <dd><a href="./controller/logout.inc.php">退出</a></dd>
                </dl>
            </li>
            <!-- <li class="layui-nav-item" lay-header-event="menuRight" lay-unselect>
                <a href="javascript:;">
                    <i class="layui-icon layui-icon-more-vertical"></i>
                </a>
            </li> -->
        </ul>
    </div>


    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul id="menu-list" class="layui-nav layui-nav-tree"lay-shrink="all" lay-filter="test">
                <li class="layui-nav-item layui-nav-itemed">
                    <a href="./user_info.php" target="right">
                        <i class="layui-icon layui-icon-user" style="font-size: 24px;"></i>
                        个人信息</a>
                </li>
                <li class="layui-nav-item">
                    <a href="./student_upload_file_form.php" target="right">
                        <i class="layui-icon layui-icon-form" style="font-size: 24px;"></i>
                        文章上传</a>
                </li>
                <li class="layui-nav-item">
                    <a href="./student_deal_info.php" target="right">
                        <i class="layui-icon layui-icon-form" style="font-size: 24px;"></i>
                        老师回执
                        <span class="layui-badge">
                        <?php
                        $num=0;             
                        $username=$_SESSION['username']; 
                        require("./controller/mysql_connect.php");
                        $sql_select = "SELECT studentname FROM t_s WHERE studentname = '$username' AND state='0'"; //准备sql语句
                        $ret = mysqli_query($conn, $sql_select);//执行SQL语句
                        $num += mysqli_num_rows($ret);//获得符合查询条件的总数

                        mysqli_query($conn, 'set names utf8');//进行编码统一
                        $sql_select = "SELECT studentname FROM s_sc WHERE studentname = '$username' AND state='0'"; //准备sql语句
                        $ret = mysqli_query($conn, $sql_select);//执行SQL语句
                        $num += mysqli_num_rows($ret);//获得符合查询条件的总数

                        mysqli_query($conn, 'set names utf8');//进行编码统一
                        $sql_select = "SELECT studentname FROM s_bc WHERE studentname = '$username' AND state='0'"; //准备sql语句
                        $ret = mysqli_query($conn, $sql_select);//执行SQL语句
                        $num += mysqli_num_rows($ret);//获得符合查询条件的总数
                        mysqli_close($conn);
                        
                        echo $num;
                        ?>
                        </span></a>
                </li>

            </ul>
        </div>
    </div>

    <div class="layui-body">
    <iframe scrolling="auto" rameborder="0" src="user_info.php" name="right" width="100%" height="100%"></iframe>
    </div>