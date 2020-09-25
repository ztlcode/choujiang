<?php
class model_server_file
{
    public static function add($params)
    {
        $data = [
            'fid'     => $params['fid'],
            'size'    => $params['size'],
            'name'    => $params['name'],
            'status'  => 1,
            'fk_user' => $params['uid']
        ];
        $res = model_db_file::add($data);
        if($res === false) return ['code'=>-1,'msg'=>'添加失败'];

        return ['code'=>0,'msg'=>'添加成功'];
    }
}