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
        $postData = [
            'userId' => $userId,
            'price'  => $totalFee,
            'outTradeId' => $outTradeNo
        ];

        $res = model_server_order::add($postData);
        if(!empty($res['code'])) return $this->fail(-1, '支付失败');
	$data = [
        	"timeStamp" => $result['data']['timeStamp'],
        	"nonceStr"  => $result['data']['nonceStr'] ,
        	"package"   => $result['data']['package'],
        	"signType"  => $result['data']['signType'],
        	"paySign"   => $result['data']['paySign'],
		"orderId"   => $res['data']['orderId']
	];

        return $this->ok($data);
    }

    public function pageNotify()
    {
        $xml = file_get_contents("php://input", "r");
	
	error_log($xml."\n",3,"/var/www/html/choujiang/ztl.txt");
        $data= utility_weixin::xmlToArray($xml);
	error_log(var_export($data,true)."\n",3,"/var/www/html/choujiang/ztl.txt");

        if(($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS')){
            
            $order = model_server_order::getOrderByOutTradeNo($data['out_trade_no']);
	error_log(var_export($order,true)."\n",3,"/var/www/html/choujiang/ztl.txt");
            if(empty($order['code'])){
                //更新订单状态
                $params = ['transactionNo'=>$data['transaction_no'],'status'=>2];
                $orderRes = model_server_order::updateByOutTradeNo($data['out_trade_no'],$params);

	error_log(var_export($orderRes,true)."\n",3,"/var/www/html/choujiang/ztl.txt");
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
