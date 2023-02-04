<?php
require_once(BANK_ROOT."epay/_conf.php");
require_once(BANK_ROOT."epay/libs/submit.class.php");
$parameter = array(
    "pid"           => trim($alipay_config['partner']),
    "type"          => $type,
    "notify_url"    => $siteurl.'epay/_notify.php',
    "return_url"    => $siteurl.'epay/_return.php',
    "out_trade_no"  => $orderid,
    "name"          => $row['name'],
    "money"         => $row['money'],
    "sitename"      => $conf['name']
);
//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter,"POST", "正在跳转");
echo $html_text;
?>