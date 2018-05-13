<?php

namespace LA;

class View
{

    public static function render($file, $params = array())
    {
        extract($params, EXTR_SKIP);
        ob_start();

        include realpath($file);

        $contents = ob_get_contents();                                    // Get the contents of the buffer
        ob_end_clean();

        return $contents;
    }

    /**
     * Берет из debug_backtrace путь к вызывающему файлу и подключает шаблон относительно этого пути.
     * Это сделано для сокращения путей к шаблонам в коде.
     * @param $template_file string путь к шаблону относительно папки, в которой лежит вызывающий файл. Например: templates/page.tpl.php
     * @param array $variables ассоциативный массив переменных, которые будут переданы в шаблон
     * @return string
     */
    public static function renderLocaltemplate($template_file, $variables = array())
    {

        $cb_arr = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
        $caller_obj = array_shift($cb_arr);

        $caller_path = $caller_obj['file'];
        $caller_path_arr = pathinfo($caller_path);

        $caller_dir = str_replace(dirname(__DIR__) . DIRECTORY_SEPARATOR, '', $caller_path_arr['dirname']);

        $full_template_path = $caller_dir . DIRECTORY_SEPARATOR . $template_file;

        return self::render($full_template_path, $variables);
    }
}
