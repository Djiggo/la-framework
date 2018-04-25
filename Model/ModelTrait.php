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

}