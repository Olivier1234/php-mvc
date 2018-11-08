<?php

$uri = $_SERVER['REQUEST_URI'];
$uri = ltrim($uri, '/');
$uriExploded = explode('?', $uri);
$routes = yaml_parse_file('config/routes.yml');

$uri = $uriExploded[0];
$query = $uriExploded[1];
$matched = false;
foreach ($routes as $name => $infos){
    if (preg_match('%^'.$uri.'$%', $infos['path'])) {
        $controllerPath = 'controllers/'.$infos['controller'].'.php';

        if(file_exists($controllerPath)){
            include($controllerPath);
            $obj = new $infos['controller'];
            if( method_exists($obj, $infos['action'] . 'Action')){
                $action = $infos['action'] . 'Action';
                $obj->$action();
                $matched = true;
                break;
            }
        }
    }
}

if (!$matched) {
    header('Location: 404');
}