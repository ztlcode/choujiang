<?php 
class model_db_prizeLog
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
        $res = $db->insert('t_prize_goods_log', $data);
        return $res;
    }

    public static function update($prizeLogId, $data)
    {
        $db  = self::initDb();
        $res = $db->update('t_prize_goods_log', ['pk_prize'=>$prizeLogId], $data);
        return $res;
    }

    public static function getNum($userId)
    {
        $db  = self::initDb();
        $item= ['count(*) as num'];
        $res = $db->select('t_prize_goods_log',['fk_user'=>$userId,'status'=>0],$item);
        return $res;
    }

    public static function getVar($userId)
    {
        $db = self::initDb();
        $res = $db->selectOne('t_prize_goods_log', ['fk_user'=>$userId], '', '', ['var'=>'desc']);
	    return $res;
    }

    public static function getInfoByUid($userId,$var)
    {
        $db = self::initDb();
	    $where = ['fk_user'=>$userId,'status'=>0];
	    if(!empty($var)) $where['var'] = $var;
        $res = $db->selectOne('t_prize_goods_log', $where);
	    return $res;
    }
}
