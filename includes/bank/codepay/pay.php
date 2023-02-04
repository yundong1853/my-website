<?php
require_once("../init.php");
@header('Content-Type: text/html; charset=UTF-8');

$trade_no=isset($_GET['trade_no'])?daddslashes($_GET['trade_no']):exit('No orderid!');
if(!is_numeric($trade_no))exit('订单号不符合要求!');

$type_id=isset($_GET['type_id'])?daddslashes($_GET['type_id']):exit('No type!');
$sitename=$conf['name'];

$row=$DB->get_row("SELECT * FROM uomg_pay WHERE trade_no='{$trade_no}' limit 1");
if(!$row)sysmsg('该订单号不存在，请返回来源地重新发起请求！');

switch ((int)$type_id) {
    case 1:
        $type = 'alipay';
        $typeName = '支付宝';
        $payurl = '<a href="'.$qr.'">立即启动支付宝APP</a>';
        break;
    case 2:
        $type = 'qqpay';
        $typeName = 'QQ';
        break;
    default:
        $type = 'wxpay';
        $typeName = '微信';
}

require_once(BANK_ROOT."codepay/_conf.php");


if($_SESSION[$trade_no.'_'.$type]){
	$result = $_SESSION[$trade_no.'_'.$type];
}else{
    $data = array(
        "id" => $codepay_config['id'],  //平台ID号
        "type" => $type,                //支付方式
        "price" => $row['money'],       //原价
        "pay_id" => $row['uid'],        //可以是用户ID,站内商户订单号,用户名
        "param" => $trade_no,           //自定义参数
        //"https" => 1,                 //启用HTTPS
        "act" => $codepay_config['act'],                    //是否启用免挂机模式
        "outTime" => $codepay_config['outTime'],            //二维码超时设置
        "page" => $codepay_config['page'],                  //付款页面展示方式
        "return_url" => $siteurl.'codepay/_return.php',     //付款后附带加密参数跳转到该页面
        "notify_url" => $siteurl.'codepay/_notify.php',     //付款后通知该页面处理业务
        "style" => $codepay_config['style'],                //付款页面风格
        "user_ip" => real_ip(),                             //用户IP
        "out_trade_no" => $trade_no,                        //单号去重复
        "createTime" => time(),                             //服务器时间
        "qrcode_url" => $codepay_config['qrcode_url'],      //本地化二维码
        "chart" => strtolower('utf-8')                      //字符编码方式
        //其他业务参数根据在线开发文档，添加参数.文档地址:https://codepay.fateqq.com/apiword/
        //如"参数名"=>"参数值"
    );
    $result = create_link($data,$codepay_config['key']);

    $_SESSION[$trade_no.'_'.$type] = $result;
}
//传给网页JS去执行
$user_data = array(
    "return_url" => 'codepay/_return.php',
    "type" => $type,
    "outTime" => $codepay_config["outTime"],
    "codePay_id" => $codepay_config["id"],
    "out_trade_no" => $trade_no,
    "price" => $row['money'],
    'money'=>$row['money'],
    'order_id'=>$trade_no,
    "subject"=>$row['name']
); 

$user_data["qrcode_url"] = $codepay_config["qrcode_url"];
//中间那log 默认为8秒后隐藏
//改为自己的替换img目录下的use_开头的图片 你要保证你的二维码遮挡不会影响扫码
//二维码容错率决定你能遮挡多少部分
$user_data["logShowTime"] = $user_data["qrcode_url"]?1:8*1000;


$codepay_json = get_curl($result['url']);

if(empty($codepay_json)){
    $data['call'] = "callback";
    $data['page'] = "3";
    $result = create_link($data,$codepay_config['key']);
    $codepay_html ='<script src="'.$result['url'].'"></script>';
}else{
    $codepay_data = json_decode($codepay_json,true);
    $qr = $codepay_data ? $codepay_data['qrcode'] : '';
    $user_data["money"] = $codepay_data&&$codepay_data['money'] ? $codepay_data['money'] : $price;
    $codepay_html = "<script>callback({$codepay_json})</script>";
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
<div class="order" style="color:red;font-size:16px">请务必规定时间内支付下面显示的金额</div>
<div class="amount">￥<?=$row['money']?></div>
<div class="qr-image" id="qrcode">
    <img src="<?=$qr?>">
</div>
 <div class="time-item" id="msg">
     <h1>二维码过期时间</h1>
     <strong id="hour_show">0时</strong>
     <strong id="minute_show">0分</strong>
     <strong id="second_show">0秒</strong>
 </div>
<div class="tps_btn"></div>
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
    var user_data = <?php echo json_encode($user_data);?>
</script>

<script>
    if(navigator.userAgent.indexOf("Windows")<=0){
        if ($type_id ==1) {
            var payurl = '<a href="<?=$qr;?>">立即启动支付宝APP</a>';
        }else if($type_id ==2) {
            var u = btoa('http://open.qzone.qq.com/url_check?url='+encodeURIComponent(window.location.href));
            var payurl = '<a href="mqqapi://forward/url?version=1&src_type=web&url_prefix='+u+'">立即启动支付宝APP</a>';
        }else{
            var payurl = '<a href="weixin://scanqrcode">保存收款码后，前往微信付款</a>';
        }
        $('.tps_btn').html(payurl);
    }
	/*var code_url = '<?=$qr?>';
    var qrcode = new QRCode("qrcode", {
        text: code_url,
        width: 230,
        height: 230,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });*/
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
            data: {type: "<?=$row['type']?>", trade_no: "<?=$row['trade_no']?>"}, //post数据
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
    function timer(intDiff) {
        var i = 0;
        myTimer = window.setInterval(function () {
            i++;
            var day = 0,
                hour = 0,
                minute = 0,
                second = 0;//时间默认值
            if (intDiff > 0) {
                day = Math.floor(intDiff / (60 * 60 * 24));
                hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
            }
            if (minute <= 9) minute = '0' + minute;
            if (second <= 9) second = '0' + second;
            $('#hour_show').html('<s id="h"></s>' + hour + '时');
            $('#minute_show').html('<s></s>' + minute + '分');
            $('#second_show').html('<s></s>' + second + '秒');
            if (hour <= 0 && minute <= 0 && second <= 0) {
                qrcode_timeout()
                clearInterval(myTimer);

            }
            intDiff--;
        }, 1000);
    }
    $(function () {
        timer(user_data.outTime || 360);
    });
    window.onload = loadmsg();
</script>
</body>
</html>