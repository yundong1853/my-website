<?php
    include('../includes/init.php');

?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>更改密码</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <link rel="icon" href="../assets/user/img/web/web.ico"/>
    <link rel="stylesheet" href="../assets/user/layui/css/layui.css"/>
    <link rel="stylesheet" href="../assets/user/css/app.css"/>
<body ontouchstart="">

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">

        <div class="layui-col-md2">
            <div class="layui-card">
                <div class="layui-card-header">个人设置</div>
                <div class="layui-card-body layui-btn-container">
                    <a class="layui-btn layui-btn-fluid layui-btn-sm layui-btn-normal" href="./user.php">账户简况</a>
                    <a class="layui-btn layui-btn-fluid layui-btn-sm layui-btn-normal" href="./pass.php">修改密码</a>
                    <a class="layui-btn layui-btn-fluid layui-btn-sm layui-btn-normal" href="./bill.php">收支明细</a>
                    <a class="layui-btn layui-btn-fluid layui-btn-sm layui-btn-normal" href="#">···</a>
                    <a class="layui-btn layui-btn-fluid layui-btn-sm layui-btn-normal" href="#">···</a>
                </div>
            </div>
        </div>
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">更改密码</div>
                <div class="layui-card-body layui-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">原密码</label>
                        <div class="layui-input-block">
                            <input type="password" name="oldPass" required  lay-verify="required" placeholder="请输入原密码" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">新密码</label>
                        <div class="layui-input-block">
                            <input type="password" name="newPass" required  lay-verify="required" placeholder="请输入新密码" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">再次输入</label>
                        <div class="layui-input-block">
                            <input type="password" name="conPass" required lay-verify="required" placeholder="请再次输入" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">确认</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="find" lay-skin="switch">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-radius layui-btn-sm layui-btn-normal" type="button" lay-submit lay-filter="formSubmit">更改密码</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header">头像</div>
                <div class="layui-card-body text-center">
                    <img src="https://q2.qlogo.cn/headimg_dl?spec=100&dst_uin=<?=$udata['qq'];?>" alt="header-img"><hr>
                    <button class="layui-btn layui-btn-sm layui-btn-radius" onclick="layer.msg('自动获取QQ头像，无需修改！')">更换</button>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript" src="../assets/user/layui/layui.js"></script>
<script type="text/javascript">
    layui.config({base: '../assets/user/js/'}).use(['form','layer','cookie'],function(){
        var $ = layui.$
        , form = layui.form
        , layer = layui.layer
        , cookie = layui.cookie;

        //监听提交
        form.on('submit(formSubmit)', function(data){
          var field = data.field;
          if(!field.oldPass)    return layer.msg('原密码不能为空');
          if(!field.newPass)    return layer.msg('新密码不能为空');
          if(!field.conPass)    return layer.msg('确认密码不能为空');
          if(!field.find)       return layer.msg('请确认是否修改密码');
          if(field.newPass !== field.conPass)   return layer.msg('两次密码输入不一致');

          layer.msg('请稍候！', { icon: 16 ,shade: 0.01,time: 2000000});
          $.ajax({
              url: 'ajax.php?method=editpass',
              type: 'post',
              dataType: 'json',
              data: field,
              success:function(msg){
                var strJson = JSON.stringify(msg) 
                var data = $.parseJSON(strJson);
                if (data.res == 1) {
                    layer.msg('修改成功，请重新登录！', {
                      offset: '15px'
                      ,icon: 1
                      ,time: 2000
                    }, function(){
                      $.cookie('user_token', '',{path:'/'});
                      top.location.pathname = '/jump/user/login.php'; 
                    });
                }else{
                    $('#verifycode').attr('src','ajax.php?method=verifycode&'+Math.random());
                    layer.msg(data.msg);
                }
                console.log(data)
              },
              error:function(error){
                layer.alert("服务器超时！！");
              }
          });
            return false;
        });
    });
</script>
</body>
</html>