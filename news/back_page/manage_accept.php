<?php
require("dependencies.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table class="layui-hide" id="test" lay-filter="test"></table>

    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="getCheckData">获取选中行数据</button>
            <button class="layui-btn layui-btn-sm" lay-event="getCheckLength">获取选中数目</button>
            <button class="layui-btn layui-btn-sm" lay-event="isAll">验证是否全选</button>
        </div>
    </script>

    <script type="text/html" id="barDemo">
        <!-- <a class="layui-btn layui-btn-xs" lay-event="delete">删除</a> -->
        <!-- <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="download">下载文件</a> -->
    </script>




    <script>
        layui.use(['table', 'layer'], function() {
            var table = layui.table;
            var layer = layui.layer;
            var $ = layui.$ //重点处,layui有内置jquery模块
            table.render({
                elem: '#test',
                url: 'manage_accept_json.php',/*tpa=https://www.layui.site/test/table/demo1.json*/ 
                toolbar: '#' ,//开启头部工具栏，并为其绑定左侧模板
                defaultToolbar: ['filter', 'exports', 'print', { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
                    title: '提示',
                    layEvent: 'LAYTABLE_TIPS',
                    icon: 'layui-icon-tips'
                }],
                title: '用户数据',
                cols: [
                    [{
                        field: 'studentname',
                        title: '学生名',
                        width: 120,

                    }, {
                        field: 'teachername',
                        title: '老师名',
                        width: 120,
                    }
                    , {
                        field: 'address',
                        title: '文件名',
                        width: 300,
                    }  , {
                        field: 'time',
                        title: '时间(h)',
                        width: 300,
                    }
                ]
                ]
            });

            //头工具栏事件
            /*   table.on('toolbar(test)', function(obj){
                var checkStatus = table.checkStatus(obj.config.id);
                switch(obj.event){
                  case 'getCheckData':
                    var data = checkStatus.data;
                    layer.alert(JSON.stringify(data));
                  break;
                  case 'getCheckLength':
                    var data = checkStatus.data;
                    layer.msg('选中了：'+ data.length + ' 个');
                  break;
                  case 'isAll':
                    layer.msg(checkStatus.isAll ? '全选': '未全选');
                  break;
                  
                  //自定义头工具栏右侧图标 - 提示
                  case 'LAYTABLE_TIPS':
                    layer.alert('这是工具栏右侧自定义的一个图标按钮');
                  break;
                };
              }); */

            //监听行工具事件
            table.on('tool(test)', function(obj) {
                var data = obj.data;
                var $ = layui.$ //重点处,layui有内置jquery模块
                var layer = layui.layer;
                var laytpl = layui.laytpl;

            

            });
        });
    </script>




</body>

</html>