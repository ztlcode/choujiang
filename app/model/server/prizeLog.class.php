<?php 
class model_server_prizeLog
{
    public static function add($params)
    {
	    $result = model_db_prizeLog::getVar($params['userId']);
	    $var = !empty($result) ? $result['var'] : 0;
        $data = [
            'fk_goods' => !empty($params['goodsId']) ? $params['goodsId'] : 0,
            'fk_user'  => $params['userId'],
            'fk_shop'  => !empty($params['shopId']) ? $params['shopId'] : 0,
            'create_time' => date('Y-m-d H:i:s', time()),
	        'var' => $var + 1
        ];
        $res = model_db_prizeLog::add($data);
        if($res === false) return ['code'=>-1,'msg'=>'添加失败'];

        return ['code'=>0,'msg'=>'添加成功'];
    }
  
    public static function update($prizeId,$params)
    {
        $data = [
            'fk_goods' => $params['goodsId'],
	        'status'   => 1
        ];
        $res = model_db_prizeLog::update($prizeId,$data);
        if($res === false) return ['code'=>-1,'msg'=>'修改失败'];

        return ['code'=>0,'msg'=>'修改成功'];
    }


    public static function getNum($userId)
    {
        $res = model_db_prizeLog::getNum($userId);
        if(empty($res) || empty($res->items)){
            return ['code'=>-1,'msg'=>'获取数据失败'];
        } 

        $data = !empty($res->items[0]['num']) ? $res->items[0]['num'] : 0;
        return ['code'=>0,'msg'=>'success','data'=>['num'=>$data]];
    }

    public static function getInfoByUid($userId,$var=0)
    {
        $res = model_db_prizeLog::getInfoByUid($userId,$var);
        if(empty($res)){
            return ['code'=>-1,'msg'=>'获取数据失败'];
        } 

        return ['code'=>0,'msg'=>'success','data'=>$res];
    }

    public static function DoPrize($orderId, $userId, $shopId, $price)
    {
        $config = SConfig::getConfig(ROOT_CONFIG.'/openapi.conf', 'choujiang');
        $data = ['userId'=>$userId,'shopId'=>$shopId];
        $unitPrice = $config->unit_price;
        $number = 10;//ceil($price/$unitPrice);
        for($i=1; $i<=$number; $i++){
            model_server_prizeLog::add($data);
        }
        
        model_server_order::update($orderId, ['prizeStatus'=>1]);
        return ['code'=>0,'msg'=>'success'];
    }

}
