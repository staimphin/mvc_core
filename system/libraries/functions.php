<?php
/**
 * Called by Autoloader
 * @param $classname
 */
function systemLoader($classname)
{
    if (substr($classname, -10) == "Controller"){
        // Controller
        $path = CONTROLLER_PATH . "{$classname}.php";
    } elseif (substr($classname, -5) == "Model"){
        // Model
        $path =  MODEL_PATH . "{$classname}.php";
    } elseif (substr($classname, -6) == "Helper"){
        // Helpers
        $path =  HELPERS_PATH . "{$classname}.php";
    }  elseif (substr($classname, -6) == "Class"){
        // Class
        $path =  CLASS_PATH . "{$classname}.php";
    } else {
        // Other
        $path =  LIBRARY_PATH . "{$classname}.php";
    }

    if (file_exists($path)) {
        require_once $path;
    } else {
        require_once MVC_CORE_PATH . "ErrorController.php";
    }
}