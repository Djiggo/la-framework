<?php

namespace LA\Model;

trait FactoryTrait
{

    /**
     * Возвращает имя класса модели.
     * @return string
     */
    static public function getMyClassName()
    {
        $class_name = get_called_class();
        return $class_name;
    }

    /**
     * @param $id_to_load
     * @param bool $exception_if_not_loaded
     * @return $this
     */
    static public function factory($id_to_load, $exception_if_not_loaded = true)
    {
        $class_name = self::getMyClassName();
        return FactoryHelper::factory($class_name, $id_to_load, $exception_if_not_loaded);
    }

}