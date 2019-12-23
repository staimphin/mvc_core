<?php
/**
 * Class System
 * Framework core component
 * This is master a kind of controller
 * initialize the framework
 */
Class System
{

    public static function run()
    {
        try {
            self::init();
            self::autoload();
            self::routing();
        } catch (Exception $e) {
            self::errors($e->getMessage());
        }
    }
    /**
     * Set core constants and vars
     *
     */
	public static function init()
	{
	    //Director separator
        define('SEPARATOR','/');
        //Project folder name
        define('BASE_FOLDER',str_replace('index.php','', $_SERVER['SCRIPT_NAME']));
        define('ROOT', $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER);
        define('WEB_ROOT','/'.BASE_FOLDER);

        //set path
        //System ROOT path:
        define('SYSTEM_PATH', ROOT.'system/');
        define('MVC_CORE_PATH', SYSTEM_PATH.'mvc_core/');
        define('HELPERS_PATH', SYSTEM_PATH.'helpers/');
        define('LIBRARY_PATH', SYSTEM_PATH.'libraries/');
        define('BASE_VIEW_PATH', SYSTEM_PATH.'base_view/');
        define('LOG_PATH', SYSTEM_PATH.'logs/');
        define('CLASS_PATH', LIBRARY_PATH.'class/');

        //Application related path:
        define('APP_PATH', ROOT.'app/');
        define('HTML', ROOT.'html/');
        define('CONFIG_PATH', APP_PATH.'config/');
        define('MODEL_PATH', APP_PATH.'models/');
        define('CONTROLLER_PATH', APP_PATH.'controllers/');

        //View related
        define('VIEW_PATH',APP_PATH.'views/');
        define('LAYOUT_PATH',APP_PATH.'layouts/');
        define('ASSETS_PATH',HTML.'assets/');
        //use layout instead?
        define('VIEW_COMMON_PATH',HTML.'common/');

        //set url
        //System URL
        define('BASE_URL',$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/'.WEB_ROOT);
        define('ASSETS_URL',BASE_URL.'html/assets/');

        //Text / view related
        define('EOL','<br>'.PHP_EOL);
        define('NO_SCRIPT_WARNING','No script found');

        global $config;
        session_start();

        include SYSTEM_PATH . "database/Database.php";
        include MVC_CORE_PATH . "Core.php";
        include MVC_CORE_PATH . "Controller.php";
        include MVC_CORE_PATH . "Model.php";
        include MVC_CORE_PATH . "View.php";
        include HELPERS_PATH . "Helpers.php";
        //include configs vars
        include CONFIG_PATH . "constants.php";
        include CONFIG_PATH . "config.php";

        //settings
        date_default_timezone_set($config['timezone']);
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', $config['system_debug_mode']);

        include LIBRARY_PATH . "functions.php";
	}

	public static function autoload()
    {
        spl_autoload_register('systemLoader');
    }

    /**
     * Basic routing
     */
	public static function routing()
    {
	    $router = new router();

        $routing_data = $router->getRoute();
        list ($controller, $action, $params) = $routing_data;

        $page = new $controller();
        $page->$action($params);

    }

    /**
     *
     */
    public static function errors($errors)
    {
        $page = new ErrorController();
        $page->index($errors);
    }
}