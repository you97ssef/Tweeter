<?php

namespace mf\router;

use \mf\auth\AbstractAuthentification;

class Router extends AbstractRouter
{
    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        if (!isset($_SESSION['access_level'])) {
            $_SESSION['access_level'] = AbstractAuthentification::ACCESS_LEVEL_NONE;
        }

        $currentPath = $this->http_req->path_info;
        if (array_key_exists($currentPath, parent::$routes) && (parent::$routes[$currentPath][2] === AbstractAuthentification::ACCESS_LEVEL_NONE || parent::$routes[$currentPath][2] <= $_SESSION['access_level'])) {
            $controller = new parent::$routes[$currentPath][0]();
            $methode = parent::$routes[$currentPath][1];
            $controller->$methode();
        } else {
            $defaultUrl = parent::$aliases["default"];
            $controller = new parent::$routes[$defaultUrl][0]();
            $methode = parent::$routes[$defaultUrl][1];
            $controller->$methode();
        }
    }

    public static function executeRoute($alias)
    {
        $currentPath = $alias;
        if (array_key_exists($currentPath, parent::$routes)) {
            $controller = new parent::$routes[$currentPath][0]();
            $methode = parent::$routes[$currentPath][1];
            $controller->$methode();
        } else {
            $defaultUrl = parent::$aliases["default"];
            $controller = new parent::$routes[$defaultUrl][0]();
            $methode = parent::$routes[$defaultUrl][1];
            $controller->$methode();
        }
    }

    public function urlFor($route_name, $param_list = [])
    {
        $lien = $this->http_req->script_name . parent::$aliases[$route_name];
        if (count($param_list)) {
            $lien .= "?";
            foreach ($param_list as $param) {
                if ($param_list[0] === $param)
                    $lien .= $param[0] . "=" . $param[1];
                else
                    $lien .= "&amp;" . $param[0] . "=" . $param[1];
            }
        }

        //$lien .= http_build_query($param_list, '', '&amp;');

        return $lien;
    }

    public function setDefaultRoute($url)
    {
        parent::$aliases['default'] = $url;
    }

    public function addRoute($name, $url, $ctrl, $mth, $access_lvl)
    {
        parent::$routes[$url] = [$ctrl, $mth, $access_lvl];
        parent::$aliases[$name] = $url;
    }
}
