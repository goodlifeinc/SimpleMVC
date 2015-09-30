<?php
namespace Framework;

use Framework\Config\ApplicationConfig;
use Framework\Controllers;
use Framework\Core\Database;

session_start();

require_once 'Autoloader.php';
Autoloader::init();

$scriptName = explode('/', $_SERVER['SCRIPT_NAME']);
$requestUri = explode('/', $_SERVER['REQUEST_URI']);
if(count($requestUri) > count($scriptName)) {
    $controllerIndex = 0;
    foreach ($scriptName as $k => $v) {
        if ($v == 'index.php') {
            $controllerIndex = $k;
            break;
        }
    }
    $actionIndex = $controllerIndex + 1;
    $controller = $requestUri[$controllerIndex];
    $action = $requestUri[$actionIndex];
    $params = [];
    for($i=$actionIndex + 1; $i<count($requestUri); $i++) {
        array_push($params, $requestUri[$i]);
    }
}
elseif (count($requestUri) == count($scriptName) && empty($requestUri[count($requestUri) - 1])) {
    $action = '';
}
else {
    $action = 'notfound';
}

$defaultController = ApplicationConfig::DEFAULT_CONTROLLER;
$defaultAction = ApplicationConfig::DEFAULT_ACTION;

$controller = isset($controller) && !empty($controller) ? $controller  : $defaultController;
$action = isset($action) && !empty($action)  ? $action : $defaultAction;
$params = isset($params) && !empty($params) ? $params : null;

$controllerClassName = '\\Framework\\Controllers\\' . ucfirst($controller). 'Controller';

Database::setInstance(
    ApplicationConfig::DB_INSTANCE,
    ApplicationConfig::DB_DRIVER,
    ApplicationConfig::DB_USER,
    ApplicationConfig::DB_PASS,
    ApplicationConfig::DB_NAME,
    ApplicationConfig::DB_HOST
);

try {
    $app = new $controllerClassName($controller, $action, $params);
}
catch (\Exception $e) {
    $controllerClassName = '\\Framework\\Controllers\\' . ucfirst('home'). 'Controller';
    $app = new $controllerClassName('Home', 'notFound');
}