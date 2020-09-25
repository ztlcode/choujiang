<?php 
class openapi_order extends openapi_base
{
    public function pagePay()
    {
        $userId = !empty($this->params['userId']) ? $this->params['userId'] : '';
        $openid = !empty($this->params['openid']) ? $this->params['openid'] : '';
        $body   = !empty($this->params['body']) ? $this->params['body'] : '';
        $totalFee   = !empty($this->params['price']) ? $this->params['price'] : 0;
        $outTradeNo = model_server_order::createOutTradeId($userId);

        $spbillCreateIp = $_SERVER['REMOTE_ADDR'];
        $weixinPay = new model_server_weixinpay($openid,$outTradeNo,$body,$totalFee,$spbillCreateIp);
        $result = $weixinPay->pay();

        if(!empty($result['code'])) {
            return $this->fail(-1, $result['msg']);
        }
        $data = [
            'fk_user'=> $userId,
            'price'  => $totalFee,
            'outTradeId' => $outTradeNo
        ];
        $result = model_server_order::add($data);
        if(!empty($result['code'])) return $this->fail(-1, '支付失败');

        return $this->ok($result);
    }

    public function pageNotify()
    {
        $xml = file_get_contents("php://input", "r");
        $data= utility_weixin::xmlToArray($xml);
        if(($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS')){
            
            $order = model_server_order::getOrderByOutTradeNo($data['out_trade_no']);
            if(empty($order['code'])){
                //更新订单状态
                $params = ['transactionNo'=>$data['transaction_no'],'status'=>2];
                $orderRes = model_server_order::updateByOutTradeNo($data['out_trade_no'],$params);

                if(empty($orderRes['code'])){
                    //生成摇奖次数
                    model_server_prizeLog::DoPrize($order['pk_order'], $order['fk_user'], $order['fk_shop'], $order['price']);
                    $result = true;
                }else{
                    $result = false;
                }
                
            }else{
                $result = false;
            }
        }

        if ($result) {  
            $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';  
        }else{  
            $str='<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';  
        }

        echo $str;  
        return $result; 
    }
}