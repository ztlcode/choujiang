<?php 
class model_db_shop
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
        $res = $db->insert('t_shop', $data);
        return $res;
    }

    public static function update($goodsId, $data)
    {
        $db  = self::initDb();
        $res = $db->update('t_shop', ['pk_goods'=>$goodsId], $data);
        return $res;
    }
}