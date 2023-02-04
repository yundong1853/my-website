<?php
function youzanGetToken(){
	$data = json_decode(@file_get_contents("access_token.json"), true);
	if (!$data || $data['expire_time'] < time()-60) {
		require_once __DIR__ . '/_conf.php';
		require_once __DIR__ . '/lib/YZGetTokenClient.php';
		$token = new YZGetTokenClient( $client_id , $client_secret );
		$type = 'self';
		$keys['kdt_id'] = $youzan_id;
		$res = $token->get_token( $type , $keys );
		if($access_token = $res['access_token']){
			$data['expire_time'] = time() + $res['expires_in'];
			$data['access_token'] = $access_token;
			$fp = fopen("access_token.json", "w");
			fwrite($fp, json_encode($data));
			fclose($fp);
		}else{
			sysmsg('获取access_token失败：['.$res['error'].']'.$res['error_description']);
		}
	}else{
		$access_token = $data['access_token'];
	}
	return $access_token;
}