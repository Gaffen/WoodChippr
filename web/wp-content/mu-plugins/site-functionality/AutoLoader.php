<?php

define('WOODCHIPPR_CLASSES_BASE_PATH', realpath(dirname(__FILE__)));

function woodchippr_autoload($class)
{
    $filename = WOODCHIPPR_CLASSES_BASE_PATH . str_replace("\\", '/', str_replace('WoodChippr', '', $class));

    if (file_exists($filename . ".php")) {
        include($filename . ".php");
        if (class_exists($class)) {
            return true;
        }
    } elseif (file_exists($filename . "/index.php")) {
        include($filename . "/index.php");
        if (class_exists($class)) {
            return true;
        }
    }
    return false;
}
spl_autoload_register('woodchippr_autoload');
