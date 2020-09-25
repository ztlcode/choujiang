<?php 
class model_db_user 
{
    public static function initDb()
    {
        $db = new SDb();
        $db->useConfig('db_choujiang');
        return $db;
    }

    public static function add($data)
    {
        $db  = self::initDb();
        $res = $db->insert('t_user', $data);
        return $res;
    }

    public static function getUserByMobile($mobile)
    {
        $db  = self::initDb();
        $res = $db->selectOne('t_user', ['mobile'=>$mobile,'status'=>1]);
        return $res;
    }

    public static function getUserById($userId)
    {
        $db  = self::initDb();
        $res = $db->selectOne('t_user', ['pk_user'=>$userId,'status'=>1]);
        return $res;
    }

    public static function getUserByParternerId($parternerId, $mobile)
    {
        $db  = self::initDb();
        $res = $db->selectOne('t_user', ['parterner_id'=>$parternerId,'mobile'=>$mobile]);
        return $res;
    }
}