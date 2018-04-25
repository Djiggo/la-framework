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
        try {
            $stmt = $dbh->prepare($query);
            $stmt->execute($params);
            $row = $stmt->fetch(\PDO::FETCH_NUM);
            return $row[0];
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getRow($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);
        try {
            $stmt = $dbh->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchObject();
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getColomn($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);
        try {
            $stmt = $dbh->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(\PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getRows($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);
        try {
            $stmt = $dbh->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(\PDO::FETCH_CLASS);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }


    /**
     * @return int
     * @throws \Exception
     */
    public static function insert($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);
        try {
            $stmt = $dbh->prepare($query);
            $stmt->execute($params);
            return $dbh->lastInsertId();
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return int
     * @throws \Exception
     */
    public static function remove($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);
        try {
            $stmt = $dbh->prepare($query);
            $stmt->execute($params);
            if (!$stmt->rowCount()) {
                throw  new \Exception('No row was not deleted');
            }
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function update($db_name, $query, $params = array())
    {
        $dbh = DBFactory::getDBConn($db_name);
        try {
            $stmt = $dbh->prepare($query);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
