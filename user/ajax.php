<?php
/**
 * 
**/
include("../includes/init.php");
$method=isset($_GET['method'])?$_GET['method']:null;
switch ($method) {
    case 'logout':
        setcookie("user_token", "", time() - 604800);
        showjson(array('res' => 1,'msg' => '已注销本次登录！'));
        break;
    case 'login':
        if (!getParam('username') || !getParam('password') || !getParam('pin')) {
            showjson(array('res' => 5,'msg' => '参数不能为空！'));
        }
        if(!VerifyCode::check(getParam('pin')))   {
            unset($_SESSION['verifycode']);
            showjson(array('res' => 5,'msg' => '验证码不正确！'));
        }
        $username = getParam('username');
        $password = md5(SYS_KEY.md5(getParam('password')));
        $row = $DB->get_row("select * from `uomg_user` where username='".$username."' and password='".$password."';");
        if ($row['status'] == 1){
            $session=md5($username.$password.$password_hash.$_SERVER['HTTP_USER_AGENT']);
            $token=authcode("{$username}\t{$session}", 'ENCODE', SYS_KEY);
            setcookie("user_token", $token, time() + 604800,'/');
            @header('Content-Type: text/json; charset=UTF-8');
            unset($_SESSION['verifycode'],$username,$password,$_GET,$_POST);
            showjson(array('res' => 1,'msg' => '欢迎归来！'));
        }elseif ($row['status'] == 2) {
            showjson(array('res' => 5,'msg' => '账户已禁用，联系管理员！'));
        }else {
            showjson(array('res' => 5,'msg' => '账号密码错误！'));
        }
        break;
    case 'register':
        if (!getParam('username') || !getParam('password') || !getParam('repass') || !getParam('qq') || !getParam('pin')) {
            showjson(array('res' => 5,'msg' => '参数不能为空！'));
        }
        if(getParam('password') != getParam('repass')) {
            showjson(array('res' => 5,'msg' => '两次密码不一致！'));
        }
        if(!VerifyCode::check(getParam('pin')))   {
            unset($_SESSION['verifycode']);
            showjson(array('res' => 5,'msg' => '验证码不正确！'));
        }
        if ($DB->get_row("select * from `uomg_user` where username='".getParam('username')."';")) {
            showjson(array('res' => 5,'msg' => '该帐号已存在！'));
        }
        $password = md5(SYS_KEY.md5(getParam('password')));
        $sql = "insert into `uomg_user` (username,password,qq,money,register_time,register_ip,login_time,login_ip) value 
        ('".getParam('username')."','".$password."','".getParam('qq')."',0,'".$date."','".real_ip()."','".$date."','".real_ip()."');";

        if($DB->query($sql)) showjson(array('res' => 1,'msg' => '注册成功！'));
        else showjson(array('res' => 5,'msg' => '注册失败！'));
        break;
    case 'editpass':
        if ($user_login == 0) {
            showjson(array('res' => 5,'msg' => '登录失效！'));
        }
        if (!getParam('oldPass') || !getParam('newPass') || !getParam('conPass')) {
            showjson(array('res' => 5,'msg' => '参数不能为空！'));
        }
        if(getParam('newPass') != getParam('conPass')) {
            showjson(array('res' => 5,'msg' => '两次密码不一致！'));
        }
        if(getParam('find') != 'on') {
            showjson(array('res' => 5,'msg' => '请确认是否修改密码！'));
        }
        $username = $udata['username'];
        $oldPass = md5(SYS_KEY.md5(getParam('oldPass')));

        if (!$DB->get_row("select * from `uomg_user` where username='".$username."' and password='".$oldPass."';")){
            showjson(array('res' => 5,'msg' => '原密码错误！'));
        }

        $newPass = md5(SYS_KEY.md5(getParam('newPass')));
        $sql = "update `uomg_user` set password = '".$newPass."' where username = '".$username."';";
        if($DB->query($sql)) showjson(array('res' => 1,'msg' => '修改成功，请重新登录！'));
        else showjson(array('res' => 5,'msg' => '修改失败，请联系客服！'));

        showjson($_POST);
        break;
    case 'findpass':
        $username = getParam('username');
        $qq = $_SESSION['findpwd_qq'];
        if (!getParam('username')) {
            showjson(array('res' => 5,'msg' => '账号不能为空！'));
        }
        if(isset($qq)){
            $row=$DB->get_row("SELECT * FROM uomg_user WHERE qq='".$qq."' and username='".$username."' limit 1");
            unset($_SESSION['findpwd_qq']);
            $newPass = md5(SYS_KEY.md5($qq));
            if($row['qq']){
                $DB->query("update `uomg_user` set password = '".$newPass."' where qq='".$qq."';");
                showjson(array('res' => 1,'msg' => '修改成功，新密码为ＱＱ号！!'));
            }else{
                showjson(array('res' => 5,'msg' => '当前QQ不存在，请重新注册！!'));
            }
        }else{
            showjson(array('res' => 5,'msg' => '验证失败，请重新扫码!','uin'=>$qq));
        }
        break;
    case 'editinfo':
        if ($user_login == 0) {
            showjson(array('res' => 5,'msg' => '登录失效！'));
        }
        $username = $udata['username'];
        if (!getParam('qq')) {
            showjson(array('res' => 5,'msg' => '绑定QQ不能为空！'));
        }
        $sql = "update `uomg_user` set qq = '".getParam('qq')."' where username = '".$username."';";
        if($DB->query($sql)) showjson(array('res' => 1,'msg' => '修改成功，请重新登录！'));
        else showjson(array('res' => 5,'msg' => '修改失败，请联系客服！'));
        break;
    case 'verifycode':
        verifycode::get(rand(0, 9),rand(0, 9));
        break;
    case 'recharge':
        if ($user_login == 0) {
            showjson(array('res' => 5,'msg' => '登录失效！'));
        }
        $money = sprintf("%.2f",getParam('money'));
        $name = getParam('name');
        $clientip = real_ip();
        $trade_no = date("YmdHis").$udata['id'].rand(11,99);

        if(!is_numeric($money)) showjson(array('res'=> 5,'msg'=>'输入金额错误！'));

        $sql="insert into `uomg_pay` (`trade_no`,`uid`,`name`,`money`,`ip`,`addtime`,`status`) values ('".$trade_no."','".$udata['id']."','".$name."','".$money."','".$clientip."','".$date."','0')";
        if($DB->query($sql)){
            showjson(array('res'=> 1,'msg'=>'提交订单成功！','trade_no'=>$trade_no,'money'=>$money,'name'=>$name));
        }else{
            showjson(array('res'=> 5,'msg'=>'提交订单失败！'.$DB->error()));
        }
        break;
    case 'authadd':
        if ($user_login == 0) {
            showjson(array('res' => 5,'msg' => '登录失效！'));
        }
        if (!getParam('domain')) {
            showjson(array('res' => 5,'msg' => '参数不能为空！'));
        }
        if (!getParam('iosqq') || !getParam('iosvx') || !getParam('anqq') || !getParam('anvx') || !getParam('other') || !getParam('alipay')) {
            showjson(array('res' => 5,'msg' => '请选择跳转模板！'));
        }
        if (!is_numeric(getParam('time'))) {
            showjson(array('res' => 5,'msg' => '授权时间错误！'));
        }
        if (strpos(getParam('domain'), '/') > 0) {
            showjson(array('res' => 5,'msg' => '请输入域名，例如：www.qq.com'));
        }
        if (substr(getParam('domain'),0,2) == '*.') {
            $type = '泛域名授权';
            $money = $conf['fee_f'.getParam('time')];
        }else{
            $type = '单域名授权';
            $money = $conf['fee_d'.getParam('time')];
        }
        $money = sprintf("%.2f",$money);
        if (getParam('check') != 1) {
            showjson(array('res' => 1,'domain' => getParam('domain'),'time' => getParam('time'),'type' => $type,'money' => $money));
        }
        if ($udata['money'] - $money < 0) {
            showjson(array('res' => 5,'msg' => '账户余额不足！'));
        }
        if ($DB->get_row("select * from `uomg_auth` where domain='".getParam('domain')."';")) {
            showjson(array('res' => 5,'msg' => '该授权已存在！'));
        }
        $trade_data = array(
            'uid'       =>  $udata['id']
            ,'num'      =>  $money
            ,'depict'   =>  $type.getParam('domain')
            ,'type'     =>  'consume'
        );
        if (!Cost::query($trade_data)) {
            showjson(array('res' => 5,'msg' => '扣费失败，请联系管理员！'));
        }

        $sql = "insert into `uomg_auth` (uid,domain,ios_qq,ios_vx,an_qq,an_vx,other,alipay,add_time,end_time,status) value 
        ('".$udata['id']."','".getParam('domain')."','".getParam('iosqq')."','".getParam('iosvx')."','".getParam('anqq')."','".getParam('anvx')."','".getParam('other')."','".getParam('alipay')."','".$date."',DATE_ADD('$date', INTERVAL '".getParam('time')."' DAY ),".$conf['auth_examine'].");";
        if($DB->query($sql)){
            if ($conf['mail_tips'] == 1) {
                send_mail(
                    $mail['addressee']
                    ,$conf['name'].'添加授权提醒'
                    ,'站点：'.$conf['siteurl'].'<br/>'.getParam('domain').'已添加授权'
                );
            }
            showjson(array('res'=> 1,'msg'=>'添加授权成功！'));
        }else{
            showjson(array('res'=> 5,'msg'=>'添加授权失败！'));
        }
        break;
    case 'authren':
        if ($user_login == 0) {
            showjson(array('res' => 5,'msg' => '登录失效！'));
        }
        if (!getParam('id') || !getParam('time')) {
            showjson(array('res' => 5,'msg' => '参数不能为空！'));
        }
        if (!is_numeric(getParam('time'))) {
            showjson(array('res' => 5,'msg' => '授权时间错误！'));
        }
        $row = $DB->get_row("select * from `uomg_auth` where id='".getParam('id')."' and uid='".$udata['id']."';");
        if (!$row) {
            showjson(array('res' => 5,'msg' => '您账户下没有该授权！'));
        }
        if (substr(getParam('domain'),0,2) == '*.') {
            $type = '泛域名续费';
            $money = $conf['fee_f'.getParam('time')];
        }else{
            $type = '单域名续费';
            $money = $conf['fee_d'.getParam('time')];
        }
        $money = sprintf("%.2f",$money);
        if ($row['end_time'] >= $date) {
            $time = date("Y-m-d h:i:s",strtotime($row['end_time'].' +'.getParam('time').' day'));
        }else{
            $time = date("Y-m-d h:i:s",strtotime($date.' +'.getParam('time').' day'));;
        }
        if (getParam('check') != 1) {
            showjson(array('res' => 1,'domain' => $row['domain'],'time' => $time,'type' => $type,'money' => $money));
        }
        if ($udata['money'] - $money < 0) {
            showjson(array('res' => 5,'msg' => '账户余额不足！'));
        }
        $trade_data = array(
            'uid'       =>  $udata['id']
            ,'num'      =>  $money
            ,'depict'   =>  $type.getParam('domain')
            ,'type'     =>  'consume'
        );
        if (!Cost::query($trade_data)) {
            showjson(array('res' => 5,'msg' => '扣费失败，请联系管理员！'));
        }
        $sql = "update `uomg_auth` set `end_time`='".$time."' where id='".getParam('id')."';";
        if($DB->query($sql)){
            showjson(array('res'=> 1,'msg'=>'续费授权成功！'));
        }else{
            showjson(array('res'=> 5,'msg'=>'续费授权失败！'));
        }
        break;
    case 'authedit':
        if ($user_login == 0) {
            showjson(array('res' => 5,'msg' => '登录失效！'));
        }
        if (!getParam('id') || !getParam('domain')) {
            showjson(array('res' => 5,'msg' => '参数不能为空！'));
        }
        if (!getParam('iosqq') || !getParam('iosvx') || !getParam('anqq') || !getParam('anvx') || !getParam('other') || !getParam('alipay')) {
            showjson(array('res' => 5,'msg' => '请选择跳转模板！'));
        }
        $row = $DB->get_row("select * from `uomg_auth` where id='".getParam('id')."' and uid='".$udata['id']."';");
        if (!$row) {
            showjson(array('res' => 5,'msg' => '您账户下没有该授权！'));
        }
        if ($row['domain'] != getParam('domain')) {
            if ($DB->get_row("select * from `uomg_auth` where domain='".getParam('domain')."';")) {
                showjson(array('res' => 5,'msg' => '该授权已存在！'));
            }
        }
        if (strpos(getParam('domain'), '*.') !== strpos($row['domain'], '*.')) {
            showjson(array('res' => 5,'msg' => '非同等级授权！'));
        }
        $sql = "update `uomg_auth` set `domain`='".getParam('domain')."',`ios_qq`='".getParam('iosqq')."',`ios_vx`='".getParam('iosvx')."',`an_qq`='".getParam('anqq')."',`an_vx`='".getParam('anvx')."',`other`='".getParam('other')."',`alipay`='".getParam('alipay')."' where id='".getParam('id')."';";
        if($DB->query($sql)){
            if ($conf['mail_tips'] == 1) {
                send_mail(
                    $mail['addressee']
                    ,$conf['name'].'修改授权提醒'
                    ,'站点：'.$conf['siteurl'].'<br/>'.getParam('domain').'已修改授权'
                );
            }
            showjson(array('res'=> 1,'msg'=>'修改授权成功！'));
        }else{
            showjson(array('res'=> 5,'msg'=>'修改授权失败！'));
        }
        break;
    case 'reportedit':
        if ($user_login == 0) {
            showjson(array('res' => 5,'msg' => '登录失效！'));
        }
        if (!getParam('id') || !getParam('url')) {
            showjson(array('res' => 5,'msg' => '参数不能为空！'));
        }
        $row = $DB->get_row("select * from `uomg_report` where id='".getParam('id')."' and aid='".$udata['id']."';");
        if (!$row) {
            showjson(array('res' => 5,'msg' => '您账户下没有该授权！'));
        }
        $sql = "update `uomg_report` set `url`='".getParam('url')."',`title`='".getParam('title')."' where id='".getParam('id')."';";
        if($DB->query($sql)){
            showjson(array('res'=> 1,'msg'=>'修改成功！'));
        }else{
            showjson(array('res'=> 5,'msg'=>'修改失败！'));
        }
        break;
    case 'Settlement':
        if ($user_login == 0) {
            showjson(array('res' => 5,'msg' => '登录失效！'));
        }
        if (!getParam('val') || !getParam('desc')) {
            showjson(array('res' => 5,'msg' => '参数不能为空！'));
        }
        if (getParam('desc') == '解除域名拉黑') {
            $sql = '`uomg_auth` where domain=';
            $money = $conf['fee_cdo'];
        }elseif (getParam('desc') == '解除ＩＰ拉黑') {
            $sql = '`uomg_iptable` where ip=';
            $money = $conf['fee_cip'];
        }else{
            showjson(array('res' => 5,'msg' => '操作异常！'));
        }
        $money = sprintf("%.2f",$money);
        if (getParam('check') != 1) {
            showjson(array('res' => 1,'val' => getParam('val'),'desc' => getParam('desc'),'money' => $money));
        }
        if (!$DB->get_row("select * from ".$sql."'".getParam('val')."';")) {
            showjson(array('res' => 5,'msg' => '该值不存在！'));
        }
        if ($udata['money'] - $money < 0) {
            showjson(array('res' => 5,'msg' => '账户余额不足！'));
        }
 
        $trade_data = array(
            'uid'       =>  $udata['id']
            ,'num'      =>  $money
            ,'depict'   =>  getParam('desc').getParam('val')
            ,'type'     =>  'consume'
        );
        if (!Cost::query($trade_data)) {
            showjson(array('res' => 5,'msg' => '扣费失败，请联系管理员！'));
        }
        $sql = "DELETE FROM ".$sql."'".getParam('val')."';";
        if($DB->query($sql)){
            showjson(array('res'=> 1,'msg'=>'支付成功！'));
        }else{
            showjson(array('res'=> 5,'msg'=>'支付失败！'));
        }
        break;
    case 'variable':
        if ($user_login == 0) {
            showjson(array('res' => 5,'msg' => '登录失效！'));
        }
        break;
    default:
        showjson(array('res' => 5,'msg' => '参数错误！'));
        break;
}
function showjson($arr){
    //ob_end_clean();
    header('Content-type: application/json');
    exit(json_encode($arr));
}