<?php

namespace LA\Model;


/**
 * Interface InterfaceRemove
 * @package LA\Model
 * Если класс реализует этот интерфейс, то он должен иметь:
 * - Метод delete(), который удаляет данные объекта в базе. Поведение метода при наличии зависимых объектов пока не регламентировано.
 */
interface InterfaceRemove {
    public function remove();
}