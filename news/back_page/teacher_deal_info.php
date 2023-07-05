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
        .laytable-cell-1-0-0 .layui-icon {
            margin-top: 5px !important;
        }
    </style>
</head>

<body>

    <div class="demoTable">
        搜索ID:
        <div class="layui-inline">
            <input class="layui-input" name="id" id="demoReload" autocomplete="off">
        </div>
        <button class="layui-btn" data-type="reload">搜索</button>
    </div>


    <table class="layui-hide" id="test" lay-filter="test"></table>
    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="getCheckData">获取选中行数据</button>
            <button class="layui-btn layui-btn-sm" lay-event="getCheckLength">获取选中数目</button>
            <button class="layui-btn layui-btn-sm" lay-event="isAll">验证是否全选</button>
        </div>
    </script>



    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="assess">评价</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="download">下载文件</a>
    </script>




    <script>
        layui.use(['table', 'layer', 'element', 'laypage'], function() {
            var laypage = layui.laypage;
            var element = layui.element;
            var table = layui.table;
            var layer = layui.layer;
            var $ = layui.$ //重点处,layui有内置jquery模块
            table.render({
                elem: '#test',
                url: './teacher_dealinfo_json.php' /*tpa=https://www.layui.site/test/table/demo1.json*/ ,
                toolbar: '#toolbarDemo', //开启头部工具栏，并为其绑定左侧模板
                defaultToolbar: ['filter', 'exports', 'print', { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
                    title: '提示',
                    layEvent: 'LAYTABLE_TIPS',
                    icon: 'layui-icon-tips'
                }],
                title: '用户数据表',
                cols: [
                    [{
                        type: 'checkbox'
                    }, {
                        field: 'studentname',
                        title: '学生',
                        width: 120,
                    }, {
                        field: 'address',
                        title: '文件名',
                        width: 300,
                        edit: 'text'
                    }, {
                        fixed: 'right',
                        title: '操作',
                        toolbar: '#barDemo',
                        width: 150
                    }]
                ],
                id: 'testReload',
                page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
                    layout: ['count', 'prev', 'next', 'page', 'limit', 'skip'] //自定义分页布局 
                        ,
                    limit: 3,
                    limits: [3, 4, 6]
                },
                done: function(obj, first) {


                }

            });

            //头工具栏事件
            table.on('toolbar(test)', function(obj) {
                var checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case 'getCheckData':
                        var data = checkStatus.data;
                        for (var i = 0; i < data.length; i++) {
                            console.log(data[i].address);
                        }
                        // layer.alert(JSON.stringify(data));
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
                        layer.alert('这是工具栏右侧自定义的一个图标按钮');
                        break;
                };
            });

            //监听行工具事件
            table.on('tool(test)', function(obj) {
                var data = obj.data;
                var $ = layui.$ //重点处,layui有内置jquery模块
                var layer = layui.layer;
                var laytpl = layui.laytpl;
                if (obj.event === 'assess') {
                    //渲染完成后直接打开
                    var tpldata = {
                        "studentname": data.studentname,
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
                if (obj.event === "download") {
                    window.location.href = "teacher_file_download.php?filename=" + data.address;
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
                                studentname: demoReload.val(),
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
                    <div class="layui-form-item" style="display:none">
                        <!-- 进行上述元素的隐藏 -->
                        <label class="layui-form-label layui-required">学生</label>
                        <div class="layui-input-block">
                            <input type="text" name="username" lay-verify="required" lay-reqtext="用户名是必填项，岂能为空？" placeholder="请输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">评价等级</label>
                        <div class="layui-input-block">
                            <select name="interest" lay-filter="aihao">
                                <!-- <option value=""></option> -->
                                <option value="0" selected="">通过</option>
                                <option value="1">小改</option>
                                <option value="2">大改</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">老师评语</label>
                        <div class="layui-input-block">
                            <textarea placeholder="请输入内容" name="content" class="layui-textarea" lay-verify="required" lay-reqtext="评语是必填项，岂能为空？"></textarea>
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
                            "username": "{{d.studentname}}",
                        });
                        form.on('submit(demo1)', function(data) {

                              console.log(data.field.content);
                             $.post("phpword.php", {
                                    studentname: data.field.username,
                                    rank: data.field.interest,
                                    contents: data.field.content
                                },
                                function(data, status) {
                                    /*  第一种方法
                                     alert(12)
                                     window.location.href = "teacher_deal_info.php";
                                     window.event.returnValue=false; */


                                       layer.msg("成功!", {
                                        time: 1000,
                                        end: function() {
                                            window.location.href = "teacher_deal_info.php";
                                            layer.close(index);
                                        }
                                    });
                                }); 
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