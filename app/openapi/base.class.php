<?php 

class openapi_base
{
    public $app;
    public $params;
    public function __construct()
    {
        $request = SJson::decode(utility_net::getPostData(),true);
        $error = $this->checkHash($request);
        if($error !== true) {
            echo $error;exit;
        }
        $this->params = $request['params'];
    }

    private function checkHash($request)
    {
        if(empty($request))  return $this->fail('请求参数为空', 404);
        if(empty($request['appid'])) return $this->fail('appid不存在', 405);

        $this->app = $this->getApp($request['appid']);
        if($this->app === false) return $this->fail('appid错误', 406);
        
        if($this->appHash($request['params'], $this->app->appkey) != $request['apphash']){
            echo $this->appHash($request['params'], $this->app->appkey);
            return $this->fail('appkey错误', 407);
        }

        return true;
    }

    private function appHash($params, $key)
    {
        return md5($key.SJson::encode($params, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
    }

    private function getApp($appid)
    {
        $result = SConfig::getConfig(ROOT_CONFIG."/openapi.conf", $appid);
        return $result ? $result : false;
    }

    protected function response($code=200, $msg='success', $data=[])
    {
        $res = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];

        return json_encode($res, JSON_UNESCAPED_UNICODE); 
    }

    protected function ok($data=[], $code=200, $msg='success')
    {
        return $this->response($code, $msg, $data);
    }

    protected function fail($msg, $code=500)
    {
        return $this->response($code, $msg);
    }
}