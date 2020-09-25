<?php 
class model_server_order
{
    public static function add($params)
    {
        $data = [
            'fk_user' => $params['userId'],
            'price'   => $params['price'] * 100,
            'out_trade_id' => $params['outTradeId'],
            'create_time'  => date('Y-m-d H:i:s', time()),
            'expiration_time' => date('Y-m-d H:i:s',time()+3600),
        ];
        $res = model_db_order::add($data);
        if($res === false) return ['code'=>-1,'msg'=>'添加失败'];

        return ['code'=>0,'msg'=>'添加成功','data'=>['orderId'=>$res]];
    }

    public static function update($orderId,$params)
    {
        $data = [];
        if(!empty($params['prizeStatus'])){
            $data['prize_status'] = 1;
        }

        $res = model_db_order::update($orderId, $data);
        if($res === false) return ['code'=>-1,'msg'=>'操作失败'];

        return ['code'=>0,'msg'=>'操作成功'];
    }

    public static function updateByOutTradeNo($outTradeNo, $params)
    {
        $data = [
            'transaction_no' => $params['transactionNo'],
            'status' => $params['status']
        ];
        $res = model_db_order::updateByOutTradeNo($outTradeNo, $data);
        if($res === false) return ['code'=>-1,'msg'=>'操作失败'];

        return ['code'=>0,'msg'=>'操作成功'];
    }

    public static function getOrderByOutTradeNo($outTradeNo)
    {
        $res = model_db_order::getOrderByOutTradeNo($outTradeNo);
        if(empty($res)) return ['code'=>-1,'res'=>'获取数据失败'];

        return ['code'=>0,'msg'=>'success','data'=>$res];
    }

    public static function createOutTradeId($userId)
    {
        return md5($userId."+".time()."+".rand(0,999999));
    }
}