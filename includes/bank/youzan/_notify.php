<?php

//---------------------------------------------------------
//有赞即时到帐支付后台回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once("../init.php");
require_once(BANK_ROOT."youzan/_conf.php");
require_once(BANK_ROOT."youzan/GetToken.php");
require_once(BANK_ROOT."youzan/lib/YZTokenClient.php");
@header('Content-Type: text/html; charset=UTF-8');
$json = file_get_contents('php://input');
$data = json_decode($json, true);

/**
 * 判断消息是否合法，若合法则返回成功标识
 */
$msg = $data['msg'];
$sign = md5($client_id.$msg.$client_secret);
if($sign != $data['sign']){
    exit('{"code":-1,"msg":"sign error"}');
}else{
    $result = array("code"=>0,"msg"=>"success") ;
}
echo json_encode($result);
/**
 * 根据 type 来识别消息事件类型，具体的 type 值以文档为准，此处仅是示例
 */
if ($data['status'] == 'TRADE_PAID') {
	/**
	 * msg内容经过 urlencode 编码，需进行解码
	 */
	$msg = json_decode(urldecode($msg),true);
	$order_data = array(
		'qr_id' => $msg['qr_info']['qr_id']
		,'price' => $msg['full_order_info']['pay_info']['payment']
		,'time' => $msg['full_order_info']['order_info']['success_time']
		,'status' => $msg['full_order_info']['order_info']['status']
		,'platform' => $msg['full_order_info']['source_info']['source']['platform']
    );
    $platform = array(
    	'wx'				=>	'微信'
		,'merchant_3rd'		=>	'商家自有app'
		,'buyer_v'			=>	'买家版'
		,'browser'			=>	'系统浏览器'
		,'alipay'			=>	'支付宝'
		,'qq'				=>	'腾讯QQ'
		,'wb'				=>	'微博'
		,'other'			=>	'其他'
    );
    if ($order_data['status'] == "TRADE_SUCCESS") {
    	$srow = $DB->get_row("SELECT * FROM `uomg_pay` WHERE buyer='".$order_data['qr_id']."' order by trade_no desc limit 1 for update");
    	if ($order_data['price'] < $srow['money']) {
    		exit('{"code":-1,"msg":"订单金额不正确！"}');
    	}
    	if($srow && $srow['status'] == 0){
    		$DB->query("update `uomg_pay` set `status` ='1' where `trade_no`='".$srow['trade_no']."'");
    		if($DB->affected()>=1){
    		    $DB->query("update `uomg_pay` set `endtime` ='$date' where `trade_no`='{$srow['trade_no']}'");
    			$trade_data = array(
    		        'uid'       =>  $srow['uid']
    		        ,'num'      =>  $srow['money']
    		        ,'depict'   =>  $platform[$order_data['platform']].$srow['name']
    		        ,'type'     =>  'recharge'
    		    );
    		    if (!Cost::query($trade_data)) {
    		    	send_mail($mail['addressee'],'充值失败，请联系管理员！',$srow);
    		        exit('{"code":-1,"msg":"充值失败，请联系管理员！"}');
    		    }
    		    //send_mail($mail['addressee'],'充值成功','充值帐号：'.$srow['uid'].'充值金额：'.$srow['money']);
    		}
    	}
    }
}

?>