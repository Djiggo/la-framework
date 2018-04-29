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

        $memcache_servers = \Config::$cache["memcache"];

        if (\Config::$local_site) {
            if (!class_exists(\Memcache::class)) {
                return;
            }
        }

        $memcache = new \Memcache;
        foreach ($memcache_servers as $memcache_server) {

            list($host, $port) = explode(':', $memcache_server);

            $result = $memcache->addServer($host, $port, true, 1);

            if (!$result) {
                throw new \Exception("Can't connect to memcache");
            }

            $memcache->setCompressThreshold(5000, 0.2);
        }

        return $memcache;
    }

    public static function getVarByKey($key)
    {
        $memcache = self::getConnection();

        if (!$memcache) {
            return;
        }

        $full_key = self::getFullKey($key);

        $value = @$memcache->get($full_key);

        $value = unserialize($value);

        return $value;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function getFullKey($key)
    {
        $full_key = '123-cache-' . $key;
        $full_key = md5($full_key);

        return $full_key;
    }


    public static function setVarByKey($key, $value, $lifetime = null)
    {

        return;
        // GET ONLY!
        // TODO


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
        $full_key = self::getFullKey($key);

        $var_key = @$memcache->set($full_key, $value, MEMCACHE_COMPRESSED, $lifetime);

        return $var_key;
    }

    public static function deleteVarByKey($key)
    {
        return;
        // GET ONLY!
        // TODO

        $memcache = self::getConnection();

        if (!$memcache) {
            return;
        }

        $full_key = self::getFullKey($key);

        @$memcache->set($full_key, null, false, 1);

        $memcache->delete($full_key);

        return true;

    }

}
