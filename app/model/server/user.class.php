<?php 
class model_server_user
{
    public static function add($params)
    {
        $data = [
            'name'        => $params['name'],
            'thumb'       => !empty($params['thumb']) ? $params['thumb'] : '',
            'mobile'      => $params['mobile'],
            'password'    => self::genPassword($params['password']),
            'create_time' => date('Y-m-d H:i:s'),
            'parterner_uid'=> $params['puid'],
        ];
        $res = model_db_user::add($data);
        if($res === false) {
            return ['code'=>-1,'msg'=>'添加失败'];
        }

        return ['code'=>0,'msg'=>'添加成功'];
    }

    public static function getUserByMobile($mobile)
    {
        $res = model_db_user::getUserByMobile($mobile);
        if(empty($res)) {
            return ['code'=>-1,'msg'=>'获取数据失败'];
        }

        return ['code'=>0,'msg'=>'success','data'=>$res];
    }

    private static function genPassword($password)
    {
        return md5($password."choujiang".md5($password));
    }
}