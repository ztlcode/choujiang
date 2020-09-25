<?php 
class model_server_shop
{
    public static function add($params)
    {
        $data = [
            'shop_name' => $params['shopName'],
            'mobile'    => $params['mobile'],
            'thumb'     => !empty($params['img']) ? $params['img'] : '',
            'user_name' => $params['userName'],
            'address'   => !empty($params['address']) ? $params['address'] : '',
            'create_time' => date('Y-m-d H:i:s')
        ];
        $res = model_db_shop::add($data);
        if($res === false) return ['code'=>-1,'msg'=>'添加失败'];

        return ['code'=>0,'msg'=>'添加成功'];
    }
}