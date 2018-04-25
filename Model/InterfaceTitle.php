<?php

namespace LA\Model;

/**
 * Interface InterfaceTitle
 * @package LA\Model
 * Если класс реализует этот интерфейс, то он должен иметь:
 * - Метод getTitle(), который возвращает заголовок для объекта.
 */
interface InterfaceTitle {
    public function getTitle();
}