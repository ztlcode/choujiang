<?php 
class model_db_file 
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
        $res = $db->insert('t_weedfs_file', $data);
        return $res;
    }
}