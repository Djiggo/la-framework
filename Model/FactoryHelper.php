<?php

namespace LA\Model;

class FactoryHelper
{
    public static function factory($class_name, $id_to_load, $exception_if_not_loaded = true)
    {
        $obj = \LA\Factory::getFromCache($class_name, [$id_to_load]);

        if ($exception_if_not_loaded) {
            if (!$obj) {
                throw new \Exception("Object not loaded");
            }
        }

        return $obj;
    }
}
