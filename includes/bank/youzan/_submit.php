<?php
require_once("../init.php");
require_once(BANK_ROOT."youzan/GetToken.php");
require_once(BANK_ROOT."youzan/lib/YZTokenClient.php");

if($_SESSION[$orderid.'_'.$type]){
	$result = $_SESSION[$orderid.'_'.$type];
}else{
	$token = youzanGetToken();
	$client = new YZTokenClient($token);

	$method = 'youzan.pay.qrcode.create';//要调用的api名称
	$api_version = '3.0.0';//要调用的api版本号
	$name = $row['name'];
	
	$my_params = [
	    'qr_name' => $name,
	    'qr_price' => $row['money']*100,
	    'qr_type' => 'QR_TYPE_DYNAMIC',
	];

	$result = $client->post($method, $api_version, $my_params);
	$_SESSION[$orderid.'_'.$type] = $result;
}

if($result['response']['qr_url']){
	$code_url = $result['response']['qr_url'];
	$qr_id = $result['response']['qr_id'];
	$DB->query("update `uomg_pay` set `buyer` ='$qr_id' where `trade_no`='$orderid'");
}else{
	sysmsg('下单失败！原因：'.$result['error_response']['msg']);
}
echo "<script>window.location.href='youzan/pay.php?type={$type}&trade_no={$orderid}';</script>";

?>