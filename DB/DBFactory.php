<?php

namespace LA\DB;

class DBFactory
{

    /**
     * @return \PDO
     */
    public static function getDBConn($db_name)
    {
        static $connects = array();

        if (isset($connects[$db_name])) {
            return $connects[$db_name];
        }

        $conf = \Config::$db;
        $conf = $conf[$db_name];

        if (!isset($conf)) {
            throw new \Exception("Неверный идентификатор БД");
        }

        $options = array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            //\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" // Работает через раз
        );

        try {
            $connects[$db_name] = new \PDO($conf['dsn'], $conf['user'], $conf['pass'], $options);
            $connects[$db_name]->query("SET NAMES utf8");
            return $connects[$db_name];
        } catch (\PDOException $e) {
            $connects[$db_name]->error = $e->getMessage();
        }
    }
}
