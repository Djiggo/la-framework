<?php

namespace LA;

class Factory
{
    static $obj_cache = array();

    public static function getFromCache($class_name, $args)
    {
        $cache_key = self::getCacheKey($class_name, $args);

        if (isset(self::$obj_cache[$cache_key])) {
            return self::$obj_cache[$cache_key];
        } else {

            $cached = \LA\Memcache::getVarByKey($cache_key);

            if ($cached) {
                return unserialize($cached);
            }

            $model_obj = @new $class_name;

            if ($model_obj instanceof \LA\Model\InterfaceModel) {

                $id = $args[0];

                $is_loaded = $model_obj->load($id);

                if (!$is_loaded) {
                    return null;
                }

                self::$obj_cache[$cache_key] = $model_obj;
            }


            \LA\Memcache::setVarByKey($cache_key, serialize(self::$obj_cache[$cache_key]));

            return self::$obj_cache[$cache_key];
        }
    }

    protected static function getCacheKey($class_name, $args)
    {
        $transfer_key = md5(serialize($args));

        $class_name = \LA\Model\Helper::globalizeClassName($class_name);

        return "object_" . $class_name . "_" . $transfer_key;

    }

    public static function removeFromCacheByNameAndArgs($class_name, $args)
    {
        $cache_key = self::getCacheKey($class_name, $args);
        unset(self::$obj_cache[$cache_key]);

        \LA\Memcache::deleteVarByKey($cache_key);

    }

}
