<?php
require_once("../init.php");
require_once(BANK_ROOT."Mchpay/_conf.php");
@header('Content-Type: text/html; charset=UTF-8');

$trade_no=isset($_GET['trade_no'])?daddslashes($_GET['trade_no']):exit('No orderid!');
if(!is_numeric($trade_no))exit('订单号不符合要求!');

$type=isset($_GET['type'])?daddslashes($_GET['type']):exit('No type!');
$sitename=$conf['name'];

$row=$DB->get_row("SELECT * FROM uomg_pay WHERE trade_no='{$trade_no}' limit 1");
if(!$row)sysmsg('该订单号不存在，请返回来源地重新发起请求！');

$code_url = $bank_url.http_build_query(array('prv'=>'11','chn'=>'01','mchid'=>$mch_id,'amt'=>$row['money'],'msg'=>$trade_no,'weixinbank'=>$bank_url));
if ($type == 'wxpay') {
    $typeName = '微信';
}elseif ($type == 'qqpay' || $type == 'tenpay') {
    $type = 'qqpay';
    $typeName = 'QQ';
}else{
    $typeName = '支付宝';
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="zh-CN" />
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta name="author" content="uomgjump" />
<meta name="robots" content="noindex, nofollow">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<title><?=$typeName;?>扫码支付 - <?=$sitename?></title>
<link href="/assets/pay/css/<?=$type;?>.css" rel="stylesheet" media="screen">

</head>
<body>
<div class="body">
<h1 class="mod-title">
<span class="ico-log"></span><span class="text"><?=$typeName;?>扫码支付</span>
</h1>
<div class="mod-ct">
<div class="order">
</div>
<div class="amount">￥<?=$row['money']?></div>
<div class="qr-image" id="qrcode">
</div>
 
<div class="detail" id="orderDetail">
<dl class="detail-ct" style="display: none;">
<dt>商家</dt>
<dd id="storeName"><?=$sitename?></dd>
<dt>购买物品</dt>
<dd id="productName"><?=$row['name']?></dd>
<dt>商户订单号</dt>
<dd id="billId"><?=$row['trade_no']?></dd>
<dt>创建时间</dt>
<dd id="createTime"><?=$row['addtime']?></dd>
</dl>
<a href="javascript:void(0)" class="arrow"><i class="ico-arrow"></i></a>
</div>
<div class="tip">
<span class="dec dec-left"></span>
<span class="dec dec-right"></span>
<div class="ico-scan"></div>
<div class="tip-text">
<p>请使用<?=$typeName;?>扫一扫</p>
<p>扫描二维码完成支付</p>
</div>
</div>
<div class="tip-text">
</div>
</div>
<div class="foot">
<div class="inner">
<p>手机用户可保存上方二维码到手机中</p>
<p>在<?=$typeName;?>扫一扫中选择“相册”即可</p>
</div>
</div>
</div>
<script src="/assets/pay/js/qrcode.min.js"></script>
<script src="/assets/pay/js/qcloud_util.js"></script>
<script src="/assets/layer/layer.js"></script>
<script>
	var code_url = '<?=$code_url?>';
    var qrcode = new QRCode("qrcode", {
        text: code_url,
        width: 230,
        height: 230,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
    // 订单详情
    $('#orderDetail .arrow').click(function (event) {
        if ($('#orderDetail').hasClass('detail-open')) {
            $('#orderDetail .detail-ct').slideUp(500, function () {
                $('#orderDetail').removeClass('detail-open');
            });
        } else {
            $('#orderDetail .detail-ct').slideDown(500, function () {
                $('#orderDetail').addClass('detail-open');
            });
        }
    });
    // 检查是否支付完成
    function loadmsg() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "../getshop.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {type: "wxpay", trade_no: "<?=$row['trade_no']?>"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
					layer.msg('支付成功，正在跳转中...', {icon: 16,shade: 0.01,time: 15000});
					setTimeout(window.location.href=data.backurl, 1000);
                }else{
                    setTimeout("loadmsg()", 4000);
                }
            },
            //Ajax请求超时，继续查询
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == "timeout") {
                    setTimeout("loadmsg()", 1000);
                } else { //异常
                    setTimeout("loadmsg()", 4000);
                }
            }
        });
    }
    window.onload = loadmsg();
</script>
</body>
</html>