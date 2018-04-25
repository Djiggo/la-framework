<?php

namespace LA\Model;

/**
 * Interface InterfaceSave
 * @package LA\Model
 */
interface InterfaceModel
{

    public function __construct();

    public function load($id);

    public function getId();

}