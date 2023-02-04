<?php
/**
 * 微信公众号类库
 * $WeChat = new WeChat;
 * echo $WeChat->long2short('http://www.aeink.com');
*/

class WeChat
{
	private $Token;
	private $AppID;
	private $AppSecret;
	
	function __construct($AppID,$AppSecret)
	{
		$wxconf = include (SYSTEM_ROOT.'/wxconf.php');
		if ($wxconf) {
			$this->AppID 		= $wxconf['AppID'];
			$this->AppSecret 	= $wxconf['AppSecret'];
			$this->token 		= $wxconf['token'];
			$this->expire_time 	= $wxconf['expire_time'];
		}else{
			$this->AppID = $AppID;
			$this->AppSecret = $AppSecret;
		}

		$query = http_build_query(array(
			'grant_type'=>	'client_credential'
			,'appid'	=>	$this->AppID
			,'secret'	=>	$this->AppSecret
		));
		if ($this->expire_time < time()) {
			$this->token = $this->getAccessToken('https://api.weixin.qq.com/cgi-bin/token?'.$query);
		}

	}
	public function long2short($longurl)
	{
		$post = array(
			'action'	=>	'long2short'
			,'long_url'	=>	$longurl
		);
		$data = json_encode($post);
		$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token=".$this->token;
		
		$rel = $this->httpPost($url,$data);
		$arr = json_decode($rel,true);

		return $arr['short_url']?$arr['short_url']:false;
	}
	private function getAccessToken($url) {
	    //access_token 应该全局存储与更新，以下代码以写入到文件中做示例
		$output = $this->httpGet($url);
		$res = (array)json_decode($output);
		if (!empty($res['errmsg'])) exit($res['errmsg']);

	    $newConfig = '<?php 
	    return array(
	      "AppID" => "'.$this->AppID.'",
	      "AppSecret" => "'.$this->AppSecret.'",
	      "token" => "'.$res['access_token'].'",
	      "expire_time" => "'.(time() + 7000).'",
	    );';

	    @file_put_contents(SYSTEM_ROOT.'/wxconf.php', $newConfig);

	    return $res['access_token'];
	}
	private function httpGet($url) {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($curl, CURLOPT_URL, $url);
	 
	    $res = curl_exec($curl);
	    curl_close($curl);
	    return $res;
	}
	private function httpPost($url,$data){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}
}