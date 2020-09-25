<?php 
class mgr_base
{
    protected function imgURL($filename)
    {
        if(empty($filename)) return '';
        $conf = SConfig::getConfig(ROOT_CONFIG.'/services.conf', 'api');
        $url = $conf->api.'/'.$filename;
        return $url;
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

    protected static function mobile($mobile)
    {        
        if(preg_match('/^1[3456789][0-9]{9}$/',$mobile)){
            return true;
        }
        
        return false;
	}

}