<?php
#申请地址:http://t.cn/RuDwUxC

#码支付接口配置
$codepay_config['id'] = $conf['codepay_pid'];
$codepay_config['key'] = $conf['codepay_key'];

//字符编码格式 目前支持 gbk GB2312 或 utf-8 保证跟文档编码一致 建议使用utf-8
$codepay_config['chart'] = strtolower('utf-8');

//是否启用免挂机模式 1为启用. 未开通请勿更改否则资金无法及时到账
$codepay_config['act'] = "0"; //认证版则开启 一般情况都为0


/**订单支付页面显示方式
 * 1: GET框架云端支付 (简单 兼容性强 自动升级 1分钟可集成)
 * 2: POST表单到云端支付 (简单 兼容性强 自动升级)
 * 3：自定义开发模式 (默认 复杂 需要一定开发能力 手动升级 html/codepay_diy_order.php修改收银台代码)
 * 4：高级模式(复杂 需要较强的开发能力 手动升级 html/codepay_supper_order.php修改收银台代码)
 */
$codepay_config['page'] = 4; //支付页面展示方式

//支付页面风格样式 仅针对$codepay_config['page'] 参数为 1或2 才会有用。
$codepay_config['style'] = 1; //暂时保留的功能 后期会生效 留意官网发布的风格编号


//二维码超时设置  单位：秒
$codepay_config['outTime'] = 300;//360秒=6分钟 最小值60  不建议太长 否则会影响其他人支付

//最低金额限制
$codepay_config['min'] = 0.01;

//$codepay_config["qrcode_url"] = "./codepay/qrcode.php"; //使用本地二维码

$codepay_config['pay_type'] = 1;

function create_link($params,$codepay_key,$host=""){
    ksort($params); //重新排序$data数组
    reset($params); //内部指针指向数组中的第一个元素
    $sign = '';
    $urls = '';
    foreach ($params AS $key => $val) {
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

    $key = md5($sign . $codepay_key);//替换为自己的密钥
    $query = $urls . '&sign=' . $key; //创建订单所需的参数
    $apiHost=$host?$host:"http://api2.fateqq.com:52888/creat_order/?";
    $url = $apiHost.$query; //支付页面
    return array("url"=>$url,"query"=>$query,"sign"=>$sign,"param"=>$urls);
}