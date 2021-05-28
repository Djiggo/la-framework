<?php

namespace LA\DB;

class DBWrapper extends \PDOStatement
{

    /**
     * @return string
     * @throws \Exception
     */
    public static function getVar($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);

        $stmt = $dbh->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_NUM);
        return $row[0];

    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getRow($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);

        $stmt = $dbh->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchObject();

    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getColomn($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);

        $stmt = $dbh->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);

    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getRows($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);

        $stmt = $dbh->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_CLASS);

    }


    /**
     * @return int
     * @throws \Exception
     */
    public static function insert($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);

        $stmt = $dbh->prepare($query);
        $stmt->execute($params);
        return $dbh->lastInsertId();

    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function remove($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);

        $stmt = $dbh->prepare($query);
        $stmt->execute($params);
        if (!$stmt->rowCount()) {
            throw  new \Exception('No row was not deleted');
        }

    }

    public static function update($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);

        $stmt = $dbh->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount();

    }

}
