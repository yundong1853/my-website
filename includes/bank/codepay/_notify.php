<?php
/* *
 * 码支付异步通知页面
 */

require_once("../init.php");
require_once(BANK_ROOT."codepay/_conf.php");
ksort($_POST); //排序post参数
reset($_POST); //内部指针指向数组中的第一个元素
$sign = '';
foreach ($_POST AS $key => $val) {
    if ($val == '') continue;
    if ($key != 'sign') {
        if ($sign != '') {
            $sign .= "&";
            $urls .= "&";
        }
        $sign .= "$key=$val"; //拼接为url参数形式
        $urls .= "$key=" . urlencode($val); //拼接为url参数形式
    }
}
if ($conf['alipay_api'] != 'codepay' && $conf['qqpay_api'] != 'codepay' && $conf['wxpay_api'] != 'codepay') {
    exit('fail');
} elseif (!$_POST['pay_no'] || md5($sign . $codepay_config['key']) != $_POST['sign']) { //不合法的数据 KEY密钥为你的密钥
    exit('fail');
} else {
    //合法的数据
    $out_trade_no = daddslashes($_POST['param']);
    //交易号
    $trade_no = daddslashes($_POST['pay_no']);
    //金额
    $money = daddslashes($_POST['money']);
    //支付方式
    if ($_POST['type'] == '1') {
        $type = '支付宝';
    }elseif ($_POST['type'] == '2') {
        $type = 'QQ钱包';
    }elseif ($_POST['type'] == '3') {
        $type = '微信支付';
    }
    $srow=$DB->get_row("SELECT * FROM uomg_pay WHERE trade_no='{$out_trade_no}' limit 1 for update");
    if($srow['status'] == 0 && $srow['money'] <= $money){
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
            }
            exit('ok');
        }
    }
    exit('success');
}

?>