<?php

namespace Framework\Controllers;


use Framework\Repositories\UserRepository;
use Framework\ViewModels\BaseViewModel;

class HomeController extends BaseController
{
    public function onInit() {
        $this->title = 'Home | SimpleMVC PHP Framework';
        $action = $this->action;
        self::$action();
    }

    public function index() {
        $viewModel = new BaseViewModel();
        $viewModel->baseUrl = $this->baseUrl;

        $viewModel->title =  'Hello SimpleMVC PHP Framework';
        $this->renderView($viewModel);
    }

    public function notFound() {
        $viewModel = new BaseViewModel();
        $viewModel->baseUrl = $this->baseUrl;

        $server = explode("/", $_SERVER['REQUEST_URI']);
        if($server[count($server) - 1] != 'notfound') {
            $this->redirect('home', 'notfound');
        }
        $viewModel->error = 'Page not found';
        $this->renderView($viewModel);
    }
}