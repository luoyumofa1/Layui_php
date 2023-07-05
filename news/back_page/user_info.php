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
  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
  <!-- <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a> -->
</script>
              
          

 
<script>
layui.use(['table','layer'], function(){
  var table = layui.table;
  var layer = layui.layer;
  var $ = layui.$ //重点处,layui有内置jquery模块
  table.render({
    elem: '#test'
    ,url:'./info_josn.php'/*tpa=https://www.layui.site/test/table/demo1.json*/
     ,toolbar: '#' //开启头部工具栏，并为其绑定左侧模板
    ,defaultToolbar: ['filter', 'exports', 'print', { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
      title: '提示'
      ,layEvent: 'LAYTABLE_TIPS'
      ,icon: 'layui-icon-tips'
    }]
    ,title: '用户数据表'
    ,cols: [[
      {field:'username', title:'用户名', width:120, edit: 'text'}
      ,{field:'password', title:'密码', width:80, edit: 'text'}
      ,{field:'key1', title:'关键词1', width:100}
      ,{field:'key2', title:'关键词2', width:100}
      ,{field:'key3', title:'关键词3', width:100}
      ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:150}
    ]]
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
  table.on('tool(test)', function(obj){
    var data = obj.data;
    var $ = layui.$ //重点处,layui有内置jquery模块
    var layer = layui.layer;
    var laytpl=layui.laytpl;
    if(obj.event === 'edit'){
/*       layer.open({
                   type: 1,
                        shade: false,
                        area: ['80%', '50%'],
                        title: false, //不显示标题
                        content: document.getElementById('tpl-user').innerText,//获取id为tpl-user的noscript标签的html内容,
                    });  */
             var tpldata = {
                            "username": data.username,
                            "password": data.password,
                            "key1": data.key1,
                            "key2": data.key2,
                            "key3": data.key3,
                    };
                    console.log(tpldata);
                    //获取id为tpl-user的noscript标签的html内容,
                    //不能使用innerHtml 使用innerHTML会转义
                    laytpl(document.getElementById('tpl-user').innerText)
                    .render(tpldata,function(html){
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
});
</script>

<noscript id="tpl-user" >
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
                            <label class="layui-form-label layui-required">用户名</label>
                            <div class="layui-input-block">
                                <input type="text" name="username" lay-verify="required" lay-reqtext="用户名是必填项，岂能为空？" placeholder="请输入"
                                 autocomplete="off" class="layui-input" disabled="disabled" >
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">密码</label>
                            <div class="layui-input-block">
                            <input type="text" name="password" class="layui-input" placeholder="请输入">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">关键词1</label>
                            <div class="layui-input-block">
                            <input type="text" name="key1" class="layui-input"  placeholder="请输入">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">关键词2</label>
                            <div class="layui-input-block">
                            <input type="text" name="key2" class="layui-input"  placeholder="请输入">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">关键词3</label>
                            <div class="layui-input-block">
                            <input type="text" name="key3" class="layui-input"  placeholder="请输入">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button type="submit" class="layui-btn tt" lay-submit="" lay-filter="demo1">立即提交</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                    </form>
                    <!-- 下方script标签中不能使用单行注释语句,否则laytpl编译解析会报错-->
                    <!-- 语句之间也要严格用分号隔开,因为laytpl编译时,那些换行都被去掉了,导致代码连在一起 -->
                    <script>
                        layui.use(['jquery','form'], function() { 
                          var $=layui.$;                       
                            var form = layui.form,layer = layui.layer;
                            form.val("info",{ 
                                "username": "{{d.username}}",
                                "password": "{{d.password}}",
                                "key1": "{{d.key1}}",
                                "key2": "{{d.key2}}",
                                "key3": "{{d.key3}}",
                            });
                         
                            form.on('submit(demo1)', function(data) {
                                /*
                                layer.alert(JSON.stringify(data.field), {
                                    title: '最终的提交信息'
                                });
                                */
                               
                       
                               var postData = {
                            "username1": data.field.username,
                            "password1": data.field.password,
                            "key11": data.field.key1,
                            "key21": data.field.key2,
                            "key31": data.field.key3,
                                  };
                                $.post("update_mysql.php", 
                                {
                                 "username": data.field.username,
                                  "password": data.field.password,
                                   "key1": data.field.key1,
                                   "key2": data.field.key2,
                                   "key3": data.field.key3,
                                  },
                                  function(data,status){
                                    layer.msg("成功!", {
                                        time: 500, 
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