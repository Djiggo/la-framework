<?php

namespace LA;

class Memcache
{

    public static function getConnection()
    {
        static $memcache = null;

        if ($memcache) {
            return $memcache;
        }


        $conf = \Config::$cache;

        if (!array_key_exists("memcache", $conf)) {
            return;
        }

        $conf = \Config::$cache["memcache"];

        $memcache = new \Memcache;
        $memcache->connect($conf["host"], $conf["port"]);
        return $memcache;
    }

    public static function getVarByKey($key)
    {
        $memcache = self::getConnection();

        if (!$memcache)
            return;

        $value = @$memcache->get($key);

        $value = unserialize($value);

        return $value;
    }

    public static function setVarByKey($key, $value, $lifetime = null)
    {
        if ($lifetime == 0) {
            return '';
        }
        $memcache = self::getConnection();

        if (!$memcache)
            return;

        $conf = \Config::$cache["memcache"];
        $value = serialize($value);

        if ($lifetime === null) {
            $lifetime = $conf['lifetime'];
        }
        $key = "inst2" . $key;
        $var_key = @$memcache->set($key, $value, false, $lifetime);

        return $var_key;
    }

    public static function deleteVarByKey($key)
    {
        $memcache = self::getConnection();

        if (!$memcache) {
            return;
        }

        @$memcache->set($key, null, false, 1);

        $memcache->delete($key);

        self::getVarByKey($key);


        return true;

    }

}
