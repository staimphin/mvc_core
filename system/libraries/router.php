<?php

/**
 * Class router
 * Framework core component
 * route the requested page
 */
Class router {

    public function __construct()
    {
    }
    /**
     * Basic routing
     *
     * url pattern: Controller/ function /arg
     *
     */
    public function getRoute()
    {
        $request_url = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : $_SERVER['REQUEST_URI'];
        $url = explode(SEPARATOR, $request_url);
        // Remove base url
        $url = array_splice($url, 2);
        $args = null;

        $request_controller_name = ucfirst((!empty($url[0]) ? $url[0] : HOME)).'Controller';
        $action = (!empty($url[1]) ? $url[1] : 'index');
        $params = array_splice($url, 2);
        return array(
             $request_controller_name,
             $action,
             $params,
             $_REQUEST
        );
    }

}