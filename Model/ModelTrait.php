<?php

namespace LA\Model;

trait ModelTrait
{

    protected $id = 0;


    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function save()
    {
        \LA\DB\ActiveRecord::saveModelObj($this);

        $reflect = new \ReflectionClass($this);

        $class_name = $reflect->getName();
        $class_name = Helper::globalizeClassName($class_name);

        \LA\Factory::removeFromCacheByNameAndArgs($class_name, array($this->getID()));

    }

    public function load($id)
    {
        return \LA\DB\ActiveRecord::loadModelObj($this, $id);
    }

    public function remove()
    {
        \LA\DB\ActiveRecord::loadModelObj($this, $this->getID());

        $reflect = new \ReflectionClass($this);

        $class_name = $reflect->getName();

        \LA\DB\ActiveRecord::removeModelObj($this, $this->getID());
        \LA\Factory::removeFromCacheByNameAndArgs($class_name, array($this->getID()));

    }


    public static function getAllIds($order_by = null, $limit = null)
    {

        $sql = "SELECT id FROM " . self::DB_TABLE_NAME;

        $args = [];

        if ($order_by) {
            $sql .= " " . $order_by;
        }

        if ($limit) {
            $sql .= " LIMIT " . $limit;
        }


        return \LA\DB\DBWrapper::getColomn(self::DB_ID, $sql, $args);

    }


    public static function getBy($where_fields, $order_by = null)
    {


        $sql = "SELECT id FROM " . self::DB_TABLE_NAME . " WHERE 1=1 ";
        $params = [];


        foreach ($where_fields as $key => $value) {

            $sql .= "AND " . $key . " = ? ";
            array_push($params, $value);

        }


        if ($order_by) {
            $sql .= " " . $order_by;
        }

        return \LA\DB\DBWrapper::getColomn(self::DB_ID, $sql, $params);

    }


}
