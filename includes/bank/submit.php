<?php
require 'init.php';
session_start();
@header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>正在为您跳转到支付页面，请稍候...</title>
    <style type="text/css">
        body {margin:0;padding:0;}
        p {position:absolute;
            left:50%;top:50%;
            width:330px;height:30px;
            margin:-35px 0 0 -160px;
            padding:20px;font:bold 14px/30px "宋体", Arial;
            background:#f9fafc url(../assets/load.gif) no-repeat 20px 26px;
            text-indent:22px;border:1px solid #c5d0dc;}
        #waiting {font-family:Arial;}
    </style>
<script>
function open_without_referrer(link){
document.body.appendChild(document.createElement('iframe')).src='javascript:"<script>top.location.replace(\''+link+'\')<\/script>"';
}
</script>
</head>
<body>
<?php
$type=isset($_GET['type'])?daddslashes($_GET['type']):exit('No type!');

$orderid=isset($_GET['orderid'])?daddslashes($_GET['orderid']):exit('No orderid!');

if(!is_numeric($orderid))exit('订单号不符合要求!');

$row=$DB->get_row("SELECT * FROM uomg_pay WHERE trade_no='{$orderid}' limit 1");

if(!$row['trade_no'])exit('该订单号不存在，请返回来源地重新发起请求！');
if($row['money']=='0')exit('订单金额不合法');
if($row['status']>=1)exit('该订单已支付完成，请<a href="/">返回重新生成订单</a>');

$DB->query("update `uomg_pay` set `type` ='$type' where `trade_no`='{$orderid}'");
if($type=='alipay'){
	if (!$conf['alipay_api']) exit('支付接口关闭！');
	require (BANK_ROOT.$conf['alipay_api'].'/_submit.php');
}elseif($type=='tenpay'){
	if (!$conf['tenpay_api']) exit('支付接口关闭！');
	require (BANK_ROOT.$conf['tenpay_api'].'/_submit.php');
}elseif($type=='wxpay'){
	if (!$conf['wxpay_api']) exit('支付接口关闭！');
	require (BANK_ROOT.$conf['wxpay_api'].'/_submit.php');
}elseif($type=='qqpay'){
	if (!$conf['qqpay_api']) exit('支付接口关闭！');
	require (BANK_ROOT.$conf['qqpay_api'].'/_submit.php');
}

?>
<p>正在为您跳转到支付页面，请稍候...</p>
</body>
</html>