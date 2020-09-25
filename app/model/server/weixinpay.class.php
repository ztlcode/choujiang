<?php
class model_server_weixinpay
{
    protected $openid;
    protected $outTradeId;
    protected $body;
    protected $totalFee;
    protected $spbillCreateIp;

    public function __construct($openid,$outTradeId,$body,$totalFee,$spbillCreateIp) 
    {    
        $config = SConfig::getConfig(ROOT_CONFIG.'/weixin.conf', 'choujiang');
        $this->appid  = $config->appid;
        $this->openid = $openid;
        $this->mch_id = $config->mchId;
        $this->key = $config->md5key;
        $this->out_trade_no = $outTradeId;
        $this->body = $body;
        $this->total_fee = $totalFee;
        $this->notify_url = "https://yaojiang8.cn/openapi/order/notify";
        $this->spbill_create_ip = $spbillCreateIp;
    }

    public function pay() 
    {
        $return = $this->weixinapp();
        return $return;
    }

    private function weixinapp() 
    {
        $unifiedorder = $this->unifiedorder();
        if($unifiedorder['return_code'] == 'FAIL') {
            return ['code'=>-1,'msg'=>$unifiedorder['return_msg']];
        }
        $params = array(
            'appId' => $this->appid,                                
            'timeStamp' => '' . time() . '',                        
            'nonceStr' => utility_weixin::createNoncestr(),                 
            'package' => 'prepay_id=' . $unifiedorder['prepay_id'], 
            'signType' => 'MD5'                                    
        );
        $params['paySign'] = $this->getSign($params);

        return ['code'=>0,'msg'=>$params];
    }

    private function unifiedorder() {

        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $params = array(
            'appid' => $this->appid,
            'body' => $this->body,
            'mch_id' => $this->mch_id,
            'nonce_str' => utility_weixin::createNoncestr(),
            'notify_url' => $this->notify_url,
            'openid' => $this->openid,
            'out_trade_no'=> $this->out_trade_no,
            'spbill_create_ip' => $this->spbill_create_ip,
            'total_fee' => floatval(($this->total_fee) * 100), 
            'trade_type' => 'JSAPI'                            
        );
        $params['sign'] = $this->getSign($params);

        $xmlData =  utility_weixin::arrayToXml($params);
        $return  =  utility_weixin::xmlToArray($this->postXmlCurl($xmlData, $url, 60));
        return $return;
    }

    private function getSign($obj) 
    {
        foreach ($obj as $k => $v) {
            $params[$k] = $v;
        }
        
        ksort($Parameters);
        $string = utility_weixin::formatBizQueryParaMap($params, false);
        $string = $string . "&key=" . $this->key;
        $string = md5($string);
        return strtoupper($string); 
    }

    private function postXmlCurl($xml, $url, $second = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        set_time_limit(0);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new WxPayException("curl出错，错误码:$error");
        }
    }
}
