<?php 

class openapi_login extends openapi_base
{
    public function pageIndex()
    {
        $mobile  = !empty($this->params['mobile']) ? $this->params['mobile'] : '';
        $openId  = !empty($this->params['openId']) ? $this->params['openId'] : '';
        if(empty($mobile) || empty($openId)) return $this->fail('缺少参数', -1);

        $userInfo = model_server_user::getUserByMobile($mobile);
        if(empty($userInfo) || !empty($userInfo['code'])){
            $data = [
                'name'   => $mobile,
                'mobile' => $mobile,
                'puid'   => $openId
            ];
            $res = model_server_user::add($data);
            if(empty($res) || !empty($res['code'])) return $this->fail('登录失败', -1);
            $userInfo = model_server_user::getUserByMobile($mobile);
        }

        $data = [
            'userId' => $userInfo['data']['pk_user'],
            'mobile' => $userInfo['data']['mobile'],
        ];
        
        return $this->ok($data);  
    }

    public function pageGetMobile(){
        $iv    = !empty($this->params['iv']) ? $this->params['iv'] : '';
        $code  = !empty($this->params['code']) ? $this->params['code'] : '';
        $encryptedData  = !empty($this->params['encryptedData']) ? $this->params['encryptedData'] : '';
        if(empty($iv) || empty($code) || empty($encryptedData)){
            return $this->fail('缺少参数', -1);
        }
        $iv = urldecode($iv);

        $config = SConfig::getConfig(ROOT_CONFIG.'/weixin.conf', 'choujiang');
        $appid  = $config->appid;
		$secret = $config->secret;
		$url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";
		$res = SJson::decode(SHttp::get($url),true);
        if(empty($res)) return $this->fail('授权失败', -1);
        
        //解密用户信息
		$isOk = utility_weixin::decryptData($appid, $res['session_key'], $encryptedData, $iv, $result);
        if($isOk === true){
            $result['openId'] = $res['openid'];
            return $this->ok($result);
        }

        return $this->fail('授权失败',-1);
    }

}