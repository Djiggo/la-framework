<?php

namespace LA;

class Routing
{

    private static $current_controller;

    public static function init()
    {
        session_start();
    }


    /**
     * Экшен должен вернуть false, если не смог обработать запрос.
     * @param string $pattern Регулярное выражение
     * @param string $handler Метод
     * @param string $method
     * @param null $cache_lifetime
     */
    public static function route($pattern, $handler, $method = "GET|POST", $cache_lifetime = null)
    {
        static $routed = false;

        if ($routed) {
            return;
        }
        $methods = explode("|", $method);
        if (!in_array($_SERVER['REQUEST_METHOD'], $methods)) {
            return;
        }
        $matches = array();


        $request_uri = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '';
        $parts = explode('?', $request_uri);
        $uri = $parts[0];

        preg_match("|^" . $pattern . "$|i", $uri, $matches);
        if ($matches) {
            $namespace_func = explode("->", $handler);
            unset($matches[0]);

            $args = array();
            foreach ($matches as $arg_value) {
                $args[] = urldecode($arg_value);
            }


            if (substr(end($args), 0, 1) == "?") {
                array_pop($args);
            }

            if ($_SERVER['REQUEST_METHOD'] != "POST") {
                $cached = \LA\Memcache::getVarByKey("page_" . $uri);
                if ($cached) {
                    $output = unserialize($cached);
                    echo $output;
                    $routed = true;
                    return;
                }
            }

            $args = array_values($args);
            self::$current_controller = array("name" => $handler, "args" => $args);

            ob_start();

            if (count($namespace_func) == 2) {
                $obj = new $namespace_func[0]();
                $callback_status = call_user_func_array(array($obj, $namespace_func[1]), $args);
            } else {
                $callback_status = call_user_func_array($handler, $args);
            }

            if ($callback_status === false) {
                return;
            }

            $routed = true;

            $output = ob_get_clean();

            if ($_SERVER['REQUEST_METHOD'] != "POST") {
                \LA\Memcache::setVarByKey("page_" . $uri, serialize($output), $cache_lifetime);
            }

            echo $output;
        }
    }

    /**
     * @param $pattern
     * @param callable $callable
     * @param int|null $cache_lifetime
     */
    public static function match($pattern, $callable, $cache_lifetime = null)
    {
        // TODO

        self::route($pattern, $callable[0] . "->" . $callable[1], "GET|POST", $cache_lifetime);
    }

    public static function getCurrentControllerWithArgs($with_action = true)
    {
        if ($with_action) {
            return self::$current_controller;
        }
        $controller = self::$current_controller;
        $name_arr = explode("->", $controller['name']);
        return array('name' => $name_arr[0], 'args' => $controller['args']);
    }

    /**
     * Отправляет статус 404 и загружает шаблон с ошибкой
     */
    public static function e404()
    {
        header("HTTP/1.0 404 Not Found");
    }


}
