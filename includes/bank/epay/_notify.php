<?php
/* *
 * 功能：彩虹易支付服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
require_once('../init.php');
require_once(BANK_ROOT.'epay/_conf.php');
require_once(BANK_ROOT.'epay/libs/notify.class.php');

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
    //商户订单号

    $out_trade_no = daddslashes($_GET['out_trade_no']);

    //支付宝交易号

    $trade_no = $_GET['trade_no'];

    //交易状态
    $trade_status = $_GET['trade_status'];

    //金额
    $money = $_GET['money'];

    //支付方式
    if ($_GET['type'] == 'alipay') {
        $type = '支付宝';
    }elseif ($_GET['type'] == 'tenpay') {
        $type = '财付通';
    }elseif ($_GET['type'] == 'wxpay') {
        $type = '微信支付';
    }elseif ($_GET['type'] == 'qqpay') {
        $type = 'qq钱包';
    }
    $srow=$DB->get_row("SELECT * FROM uomg_pay WHERE trade_no='{$out_trade_no}' limit 1 for update");

    if ($_GET['trade_status'] == 'TRADE_SUCCESS' && $srow['status'] == 0 && $srow['money'] <= $money) {
        //付款完成后，支付宝系统发送该交易状态通知
        $DB->query("update `uomg_pay` set `status` ='1' where `trade_no`='{$out_trade_no}'");
        if($DB->affected()>=1){
            $DB->query("update `uomg_pay` set `endtime` ='$date' where `trade_no`='{$out_trade_no}'");
        	$trade_data = array(
                'uid'       =>  $srow['uid']
                ,'num'      =>  $srow['money']
                ,'depict'   =>  $type.$srow['name']
                ,'type'     =>  'recharge'
            );
            if (!Cost::query($trade_data)) {
                send_mail($mail['addressee'],'充值失败，请联系管理员！',json_encode($trade_data));
                echo "充值失败，请联系管理员！";
            }
        }
    }

    echo "success";
}
else {
    //验证失败
    echo "fail";
}
?>