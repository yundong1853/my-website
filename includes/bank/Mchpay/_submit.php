<?php
require_once("../init.php");
require_once(BANK_ROOT."Mchpay/_conf.php");
if (empty($conf['Mchpay_pid'])) {
	sysmsg('该商户未进行配置！');
}

if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'Alipay')!==false){
	$pay_url = $bank_url.http_build_query(array('prv'=>'11','chn'=>'01','mchid'=>$mch_id,'amt'=>$row['money'],'msg'=>$orderid,'weixinbank'=>$bank_url));
	echo "<script>window.location.href='$pay_url';</script>";
}else{
	echo "<script>window.location.href='./Mchpay/pay.php?trade_no={$orderid}&type={$type}';</script>";
}
?>