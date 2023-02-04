<?php

//---------------------------------------------------------
//有赞即时到帐支付后台回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------

require_once("../init.php");
require_once(BANK_ROOT."Mchpay/_conf.php");

@header('Content-Type: text/html; charset=UTF-8');
$data = $_POST;
/**
 * 判断密钥是否合法，若合法则返回成功标识
 */
if($mch_key != $data['Mch_key']){
    exit('监控码错误');
}else{
    echo '请求成功';
}
/**
 * 根据 type 来识别消息事件类型，具体的 type 值以文档为准，此处仅是示例
 */
if ($data['status'] == "TRADE_SUCCESS") {
    $srow = $DB->get_row("SELECT * FROM `uomg_pay` WHERE trade_no='".$data['tradeid']."' order by trade_no desc limit 1 for update");
    if ($data['price'] < $srow['money']) {
        exit('{"code":-1,"msg":"订单金额不正确！"}');
    }
    if($srow && $srow['status'] == 0){
        echo "1";
        $DB->query("update `uomg_pay` set `status` ='1' where `trade_no`='".$data['tradeid']."'");
        if($DB->affected()>=1){
            echo "2";
            $DB->query("update `uomg_pay` set `endtime` ='$date' where `trade_no`='{$data['tradeid']}'");
            $trade_data = array(
                'uid'       =>  $srow['uid']
                ,'num'      =>  $srow['money']
                ,'depict'   =>  $data['platform'].$srow['name']
                ,'type'     =>  'recharge'
            );
            echo "3";
            if (!Cost::query($trade_data)) {
                send_mail($mail['addressee'],'充值失败，请联系管理员！',$srow);
                exit('{"code":-1,"msg":"充值失败，请联系管理员！"}');
            }
            echo "4";
            //send_mail($mail['addressee'],'充值成功','充值帐号：'.$srow['uid'].'充值金额：'.$srow['money']);
        }
    }
}


?>