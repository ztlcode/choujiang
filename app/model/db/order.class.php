<?php 
class model_db_order
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
        $res = $db->insert('t_order', $data);
        return $res;
    }

    public static function update($orderId, $data)
    {
        $db  = self::initDb();
        $res = $db->update('t_order', ['pk_order'=>$orderId], $data);
        return $res;
    }

    public static function updateByOutTradeNo($outTradeNo, $data)
    {
        $db  = self::initDb();
        $res = $db->update('t_order', ['out_trade_id'=>$outTradeNo], $data);
        return $res;
    }

    public static function getOrderByOutTradeNo($outTradeNo,$status=0)
    {
        $db  = self::initDb();
        $res = $db->selectOne('t_order', ['out_trade_no'=>$outTradeNo,'status'=>$status]);
        return $res;
    }
}