<?php
/**
 * API接口
**/
define('TXPROTECT', false);
include("./includes/common.php");
$method = isset($_GET['method'])?$_GET['method']:null;
switch ($method) {
    case 'vx_report':
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        header("Content-Type: text/html; charset=utf-8");
        echo '<script src="/assets/plugin/jump/vxtool.js"></script>';
        break;
    case 'm_report':
        include TEMPLATE.'tips/m_report.html';
        break;
    case 'm_report_success':
        if ($conf['user_report_close'] == 1) {
            $_SESSION['user_report_close'] = 'close';
        }
        include TEMPLATE.'tips/m_report_success.html';
        break;
    default:
        $json = array('code' => 5,'msg' => '参数错误！');
        exit(json_encode($json,320));
        break;
}