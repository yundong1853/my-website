<?php

require_once("../init.php");
require_once(BANK_ROOT."codepay/_conf.php");

if ($type == 'wxpay') {
	$typeName = '微信';
    $type_id = 3;
} else if ($type == 'qqpay' || $type == 'tenpay') {
	$type = 'qqpay';
	$typeName = 'QQ';
    $type_id = 2;
} else {
	$typeName = '支付宝';
    $type_id = 1;
}
if($_SESSION[$orderid.'_'.$type]){
	$result = $_SESSION[$orderid.'_'.$type];
}else{
	$data = array(
	    "id" => $codepay_config['id'],	//平台ID号
	    "type" => $type,				//支付方式
	    "price" => $row['money'],		//原价
	    "pay_id" => $row['uid'], 		//可以是用户ID,站内商户订单号,用户名
	    "param" => $orderid,			//自定义参数
		//"https" => 1,					//启用HTTPS
	    "act" => $codepay_config['act'],					//是否启用免挂机模式
	    "outTime" => $codepay_config['outTime'],			//二维码超时设置
	    "page" => $codepay_config['page'],					//付款页面展示方式
	    "return_url" => $siteurl.'codepay/_return.php',		//付款后附带加密参数跳转到该页面
	    "notify_url" => $siteurl.'codepay/_notify.php',		//付款后通知该页面处理业务
	    "style" => $codepay_config['style'],				//付款页面风格
	    "user_ip" => real_ip(),								//用户IP
	    "out_trade_no" => $orderid,							//单号去重复
	    "createTime" => time(),								//服务器时间
	    "qrcode_url" => $codepay_config['qrcode_url'],		//本地化二维码
	    "chart" => strtolower('utf-8')						//字符编码方式
	    //其他业务参数根据在线开发文档，添加参数.文档地址:https://codepay.fateqq.com/apiword/
	    //如"参数名"=>"参数值"
	);
	$result = create_link($data,$codepay_config['key']);

	$_SESSION[$orderid.'_'.$type] = $result;
}

echo "<script>window.location.href='codepay/pay.php?type_id={$type_id}&trade_no={$orderid}';</script>";

?>