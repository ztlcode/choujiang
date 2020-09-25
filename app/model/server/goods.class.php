<?php 
class model_server_goods
{
    public static function getList($page, $length, $orderBy=[])
    {
        $res = model_db_goods::getList($page, $length, $orderBy);
        if(empty($res) || empty($res->items)){
            return ['code'=>-1,'msg'=>'获取数据失败'];
        }

        return ['code'=>0,'msg'=>'success','data'=>$res];
    }

    public static function getInfo($goodsId)
    {
        $res = model_db_goods::getInfoById($goodsId);
        if(empty($res)) return ['code'=>-1,'msg'=>'获取数据失败'];

        return ['code'=>0,'msg'=>'success','data'=>$res];
    }

    public static function getPrizeGoods()
    {
        $res = model_db_goods::getPrizeGoods(1,9);
        if(empty($res)) return ['code'=>-1,'msg'=>'获取数据失败'];

        return ['code'=>0,'msg'=>'success','data'=>$res];
    }

    public static function add($params)
    {
        $data = [
            'name' => $params['name'],
            'price'=> $params['price'] * 100,
            'thumb'=> $params['img'],
            'pro'  => $params['pro']
        ];
        if(isset($params['isPrize'])){
            $data['is_prize'] = $params['isPrize'];
        }
        $res = model_db_goods::add($data);
        if($res === false) return ['code'=>-1,'msg'=>'添加失败'];

        return ['code'=>0,'msg'=>'添加成功'];
    }
}