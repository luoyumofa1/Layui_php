<?php
require("dependencies.php");
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>upload模块快速使用</title>

</head>

<body>

  <!-- <button type="button" class="layui-btn" id="test1">
  <i class="layui-icon">&#xe67c;</i>上传文件
</button> -->

  <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
    <legend>拖拽上传</legend>
  </fieldset>

  <div class="layui-upload-drag" id="test10">
    <i class="layui-icon"></i>
    <p>点击上传，或将文件拖拽到此处</p>
    <div class="layui-hide" id="uploadDemoView">
      <hr>
      <img src="" alt="上传成功后渲染" style="max-width: 196px">
    </div>
  </div>

  <script>
    layui.use('upload', function() {
      var upload = layui.upload;
      var layer = layui.layer;
      var $ = layui.$;
      //执行实例
      var uploadInst = upload.render({
        elem: '#test10' //绑定元素 
        ,url: './student_upload_file.php' //上传接口
        ,accept: 'file' //普通文件
        ,exts: 'jpg|png|7z|txt|pdf' //允许上传文件类型
        ,size: 1024 * 100 //所传文件的大小
        ,done: function(res) {
          if(res.msg==='上传成功,请耐心等待老师回执')
          {
            $.post("s_t_mysql.php", {
              "articleaddress": res.data.src,
            },
            function(data, status) {
              console.log(status);
              /*   layer.closeAll(); */
            });
          }
          //二次上传的代码
         if(res.msg==='你的论文已被重新提交')
         {
          $.post("s_sc_mysql.php", {
              "articleaddress": res.data.src,
              "teachername":res.code,
            },
            function(data, status) {
              console.log(status);
              /*   layer.closeAll(); */
            });
         }

          layer.msg(res.msg);
        },
        error: function() {
          //请求异常回调
          layer.msg("回调异常");
        }
      });
    });
  </script>
</body>

</html>