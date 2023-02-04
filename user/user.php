<?php
    include('../includes/init.php');

?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>个人中心</title>
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
                <div class="layui-card-header">个人信息</div>
                <div class="layui-card-body layui-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">昵称</label>
                        <div class="layui-input-block">
                            <input type="text" name="username" required  lay-verify="required" readonly="readonly" class="layui-input" value="<?=$udata['username'];?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">ＱＱ</label>
                        <div class="layui-input-inline">
                            <input type="text" name="qq" required lay-verify="required" placeholder="请输入QQ号" autocomplete="off" class="layui-input" value="<?=$udata['qq'];?>">
                        </div>
                        <div class="layui-form-mid layui-word-aux">绑定QQ号</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">账户余额</label>
                        <div class="layui-input-inline">
                            <input type="text" name="money" readonly="readonly" class="layui-input" value="<?=$udata['money'];?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">登录ＩＰ</label>
                        <div class="layui-input-block">
                            <input type="text" name="loginip" required lay-verify="required" readonly="readonly" class="layui-input" value="<?=$udata['login_ip'];?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">登录时间</label>
                        <div class="layui-input-inline">
                            <input type="text" name="logintime" required lay-verify="required" readonly="readonly" class="layui-input" value="<?=$udata['login_time'];?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">注册ＩＰ</label>
                        <div class="layui-input-block">
                            <input type="text" name="regip" required lay-verify="required" readonly="readonly" class="layui-input" value="<?=$udata['register_ip'];?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">注册时间</label>
                        <div class="layui-input-block">
                            <input type="text" name="regtime" required lay-verify="required" readonly="readonly" class="layui-input" value="<?=$udata['register_time'];?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-radius layui-btn-sm layui-btn-normal" type="button" lay-submit lay-filter="formSubmit">立即提交</button>
                            <button class="layui-btn layui-btn-radius layui-btn-sm" type="reset">重置</button>
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
          if(!field.qq)    return layer.msg('绑定QQ不能为空！');

          layer.msg('请稍候！', { icon: 16 ,shade: 0.01,time: 2000000});
          $.ajax({
              url: 'ajax.php?method=editinfo',
              type: 'post',
              dataType: 'json',
              data: field,
              success:function(msg){
                var strJson = JSON.stringify(msg) 
                var data = $.parseJSON(strJson);
                if (data.res == 1) {
                    layer.msg('修改成功！');
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