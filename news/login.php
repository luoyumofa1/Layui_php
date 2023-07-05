<!DOCTYPE html>
<html>
<head>
    <title>登录</title>
    <link rel="stylesheet" href="./back_page/css/login.css" type="text/css">
    <meta name="content-type"; charset="UTF-8">
    <link rel="stylesheet" href="layui/css/layui.css"/>
</head>
<body>
<div id="bigBox">
        <h1>文章分发评阅系统</h1>
        <!-- <form id="loginform" action="loginaction.php" method="post"> -->
            <div class="inputBox">
                    <div class="inputText">
                        <input type="text" id="name" name="username" placeholder="Username" value="">
                    </div>
                <div class="inputText">
                   <input type="password" id="password" name="password" placeholder="Password" value="">
                </div>
                <br >
                <input type="radio" id="power" name="power" value="student" checked style="color: white">学生
                <input type="radio" id="power" name="power" value="teacher" style="color: white">教师
                <input type="radio" id="power" name="power" value="manager" style="color: white">管理员
                <div style="color: white;font-size: 12px" >
                </div>
              
            </div>
           <div>
               <input type="submit" id="login" name="login" value="登录" class="loginButton m-left">
               <input type="reset" id="reset" name="reset" value="重置" class="loginButton">
           </div>
</div>
</div>
<!-- </form> -->
<script src="layui/layui.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="./back_page/static/js/jquery.min.js"></script>

<script>
   $("#re").click(function(){
  var ranklist=document.getElementsByName("power");
  var Rank;
  for(i=0;i<ranklist.length;i++)
  {
    if(ranklist[i].checked)
    {
       Rank=ranklist[i].value;
       alert(Rank);
    }
  }
  });

   $("#login").click(function(){
    var ranklist=document.getElementsByName("power");
    var Rank;
  for(i=0;i<ranklist.length;i++)
  {
    if(ranklist[i].checked)
    {
       Rank=ranklist[i].value;
    }
  }
    $.post("./back_page/controller/loginaction.php",
  {
    username:document.getElementById('name').value,
    password:document.getElementById('password').value,
    rank:Rank
  },
  function(data,status){
  var ranklist=document.getElementsByName("power");
  var Rank;
  for(i=0;i<ranklist.length;i++)
  {
    if(ranklist[i].checked)
    {
       Rank=ranklist[i].value;
    }
  }
console.log(data);
      if(data==="success")
      {
        if(Rank==='student')
        window.location.href="./back_page/student_form.php";
        if(Rank==='teacher')
        window.location.href="./back_page/teacher_form.php";
        if(Rank==='manager')
        window.location.href="./back_page/manage_form.php";
      }
    else{
            layui.layer.msg('账号或密码错误！',{
                icon:1,
                offset: '10%'
            }) 
          
    }
  });
});
</script>

</body>
</html>

