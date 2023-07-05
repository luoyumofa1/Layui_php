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
    <style type="text/css">
        .laytable-cell-1-0-0 .layui-icon 
        {
         margin-top: 5px !important;
        }
    </style>
</head>

<body>

    <div class="demoTable">
        搜索老师名:
        <div class="layui-inline">
            <input class="layui-input" name="id" id="demoReload" autocomplete="off">
        </div>
        <button class="layui-btn" data-type="reload">搜索</button>
    </div>
    <table class="layui-hide" id="test" lay-filter="test"></table>

    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="getCheckData">对所选内容全部同意</button>
            <button class="layui-btn layui-btn-sm" lay-event="getCheckLength">获取选中数目</button>
            <button class="layui-btn layui-btn-sm" lay-event="isAll">验证是否全选</button>
        </div>
    </script>


    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="accept">同意</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="change">修改</a>
    </script>




    <script>
        layui.use(['table', 'layer'], function() {
            var table = layui.table;
            var layer = layui.layer;
            var $ = layui.$ //重点处,layui有内置jquery模块
            table.render({
                elem: '#test',
                url: './manage_check_student_json.php' /*tpa=https://www.layui.site/test/table/demo1.json*/ ,
                toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
                    ,
                defaultToolbar: ['exports', 'print', { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
                    title: '提示',
                    layEvent: 'LAYTABLE_TIPS',
                    icon: 'layui-icon-tips'
                }],
                title: '用户数据表',
                cols: [
                    [{
                        type: 'checkbox',
                        width: 50,
                    }, {
                        field: 'studentname',
                        title: '学生名',
                        width: 120,

                    }, {
                        field: 'teachername',
                        title: '老师名',
                        width: 300,
                    }, {
                        fixed: 'right',
                        title: '操作',
                        toolbar: '#barDemo',
                        width: 150
                    }]
                ]
                ,  
                id: 'testReload'
                ,page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
                    layout: ['count', 'prev', 'next','page',  'limit', 'skip'] //自定义分页布局 
                    ,limit:10
                    ,limits: [10, 20, 30]
                }
            });

            //头工具栏事件
            table.on('toolbar(test)', function(obj) {
                var checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case 'getCheckData':
                        var list = [];
                        var data = checkStatus.data;
                        for (var i = 0; i < data.length; i++) {
                            var a1 = {};
                            a1.studentname = data[i].studentname;
                            a1.teachername = data[i].teachername;
                            list.push(a1);
                        }
                        if (data.length > 0) {
                            $.post("manage_check_all.php", {
                                    "data": JSON.stringify(list),
                                },
                                function(data, status) {
                                    layer.msg("批处理成功!", {
                                        time: 2000
                                    }, function() {
                                        window.location.href = "./manage_check_student.php";
                                        layer.close(index);
                                    });

                                });
                        } else {
                            layer.msg("您未选择任何东西,请先选择选项", {
                                time: 1000
                            });
                        }
                        break;
                    case 'getCheckLength':
                        var data = checkStatus.data;
                        layer.msg('选中了：' + data.length + ' 个');
                        break;
                    case 'isAll':
                        layer.msg(checkStatus.isAll ? '全选' : '未全选');
                        break;

                        //自定义头工具栏右侧图标 - 提示
                    case 'LAYTABLE_TIPS':
                        layer.alert('这是一个系统,希望没有bug');
                        break;
                };
            });

            //监听行工具事件
            table.on('tool(test)', function(obj) {
                var data = obj.data;
                var $ = layui.$ //重点处,layui有内置jquery模块
                var layer = layui.layer;
                var laytpl = layui.laytpl;
                if (obj.event === "accept") {
                    $.post("manage_check_student_accept.php", {
                            studentname: data.studentname,
                            teachername: data.teachername,
                        },
                        function(data, status) {
                            layer.msg("success", {
                                time: 1000,
                                end: function() {
                                    window.location.href = "./manage_check_student.php";
                                    layer.close(index);
                                }
                            });
                        });
                }
                if (obj.event === "change") {
                    var tpldata = {
                        "studentname": data.studentname,
                        "oldteachername": data.teachername,
                        "newteachername": data.teachername,
                    };

                    //获取id为tpl-user的noscript标签的html内容,
                    //不能使用innerHtml 使用innerHTML会转义
                    laytpl(document.getElementById('tpl-user').innerText)
                        .render(tpldata, function(html) {
                            //渲染完成后直接打开
                            layer.open({
                                type: 1,
                                shade: false,
                                area: ['80%', '80%'],
                                title: false, //不显示标题
                                content: html,

                            });
                        });

                }


            });

            active = {
                reload: function() {
                    var demoReload = $('#demoReload');
                    var index = layer.msg('查询中，请稍等...', {
                        icon: 16,
                        time: false,
                        shade: 0
                    });
                    setTimeout(function() {
                        //执行重载
                        table.reload('testReload', {
                            page: {
                                curr: 1 //重新从第 1 页开始
                            },
                            where: {

                                teachername: demoReload.val(),

                            }
                        });
                        layer.close(index);
                    }, 800);

                }
            };

            $('.demoTable .layui-btn').on('click', function() {
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

        });
    </script>


    <noscript id="tpl-user">
        <!-- style 尽量不要影响到页面其他元素,使用#userinfo范围限定 -->
        <style type="text/css">
            #userinfo .layui-form-label.layui-required:after {
                content: "*";
                color: red;
                position: absolute;
                top: 10px;
                right: 5px;
            }
        </style>
        <div class="layui-card" id="userinfo">
            <div class="layui-card-header">信息修改框</div>
            <div class="layui-card-body">
                <form class="layui-form" action="" lay-filter="info">
                    <div class="layui-form-item">
                        <label class="layui-form-label layui-required">学生</label>
                        <div class="layui-input-block">
                            <input type="text" name="studentname" lay-verify="required" lay-reqtext="用户名是必填项，岂能为空？" placeholder="请输入" autocomplete="off" class="layui-input" disabled="disabled">
                        </div>
                    </div>
                    <div class="layui-form-item" style="display:none">
                        <label class="layui-form-label layui-required">待改老师</label>
                        <div class="layui-input-block">
                            <input type="text" name="oldteachername" lay-verify="required" lay-reqtext="用户名是必填项，岂能为空？" placeholder="请输入" autocomplete="off" class="layui-input" disabled="disabled">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">单行选择框</label>
                        <div class="layui-input-block">
                            <select name="newteachername" lay-filter="aihao">
                                <option value="王老师">王老师</option>
                                <option value="李老师">李老师</option>
                                <option value="赵老师">赵老师</option>
                                <option value="孙老师">孙老师</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button type="submit" class="layui-btn tt" lay-submit="" lay-filter="demo1">立即提交</button>
                            <!-- <button type="reset" class="layui-btn layui-btn-primary">重置</button> -->
                        </div>
                    </div>
                </form>
                <!-- 下方script标签中不能使用单行注释语句,否则laytpl编译解析会报错-->
                <!-- 语句之间也要严格用分号隔开,因为laytpl编译时,那些换行都被去掉了,导致代码连在一起 -->
                <script>
                    layui.use(['jquery', 'form'], function() {
                        var $ = layui.$;
                        var form = layui.form,
                            layer = layui.layer;
                        form.val("info", {
                            "studentname": "{{d.studentname}}",
                            "oldteachername": "{{d.oldteachername}}",
                            "newteachername": "{{d.newteachername}}",
                        });

                        form.on('submit(demo1)', function(data) {

                            $.post("manage_check_student_change.php", {
                                    "studentname": data.field.studentname,
                                    "oldteachername": data.field.oldteachername,
                                    "newteachername": data.field.newteachername,
                                },
                                function(data, status) {
                                    layer.msg("成功!", {
                                        time: 1000,
                                        end: function() {
                                            location.reload(true);
                                            layer.closeAll();
                                        }
                                    });

                                });
                            /*  layer.close(index); */

                            return false;
                        });
                        /** 要使用render,复杂表单才能显示 */
                        form.render();
                    });
                </script>
            </div>
        </div>
    </noscript>
</body>

</html>