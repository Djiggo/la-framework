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

    public function getAllIds()
    {

        $sql = "SELECT id FROM " . self::DB_TABLE_NAME;

        return \LA\DB\DBWrapper::getColomn(self::DB_ID, $sql);

    }


    public function getBy($where_fields)
    {


        $sql = "SELECT id FROM " . self::DB_TABLE_NAME . " WHERE 1=1 AND ";
        $params = [];


        foreach ($where_fields as $key => $value) {

            $sql .= $key . " = ?";
            array_push($params, $where_fields);

        }

        return \LA\DB\DBWrapper::getColomn(self::DB_ID, $sql, $params);

    }


}