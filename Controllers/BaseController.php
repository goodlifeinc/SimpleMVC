<?php
namespace Framework\Controllers;


use Framework\Repositories\Repositories;

abstract class BaseController
{
    protected $controller;
    protected $action;
    protected $params;
    protected $repo;
    protected $baseUrl;

    public function __construct($controller, $action, $params = null) {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
        $this->repositories = new Repositories();
        $this->baseUrl = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
        if(!method_exists($this, $action)) {
            $this->redirect('home', 'notfound');
        }
        else {
            $this->onInit();
        }
    }

    protected function onInit() {}

    public function renderView($model, $viewName = null) {

        $header = 'Views/header_guest.php';
        if($this->isLoggedIn()) {
            $header = 'Views/header.php';
        }

        require_once $header;

        $view = $viewName ? $viewName : $this->controller
            . '/' . $this->action;

        require_once '/Views/' . $view . '.php';

        require_once '/Views/footer.php';
    }

    protected function redirect(
        $controller = null, $action = null, $params = [])
    {
        $uri = $controller ? $controller . '/' : '';
        $uri .= $action ? $action : '';
        if (!empty($params)) {
            $uri .= '/';
            foreach($params as $param) {
                $uri .= $param . '/';
            }
        }
        header('Location: ' . str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) . $uri);
        die;
    }

    public function getLoggedUserId() {
        if($this->isLoggedIn()) {
            return $_SESSION['user_id'];
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}