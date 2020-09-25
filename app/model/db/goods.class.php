<?php 
class model_db_goods
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
        $res = $db->insert('t_goods', $data);
        return $res;
    }

    public static function update($goodsId, $data)
    {
        $db  = self::initDb();
        $res = $db->update('t_goods', ['pk_goods'=>$goodsId], $data);
        return $res;
    }

    public static function getList($page, $length, $orderBy=[])
    {
        $db = self::initDb();
        $db->setPage($page);
        $db->setLimit($length);
        $res = $db->select('t_goods','','','',$orderBy);
        return $res;
    }

    public static function getInfoById($goodsId)
    {
        $db  = self::initDb();
        $res = $db->selectOne('t_goods', ['pk_goods'=>$goodsId]);
        return $res;
    }

    public static function getPrizeGoods($page, $length)
    {
        $db = self::initDb();
        $db->setPage($page);
        $db->setLimit($length);
        $res = $db->select('t_goods', ['is_prize'=>1,'status'=>1]);
        return $res;
    }
}