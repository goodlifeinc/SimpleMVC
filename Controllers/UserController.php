<?php

namespace Framework\Controllers;


use Framework\Models\User;
use Framework\ViewModels\BaseViewModel;
use Framework\ViewModels\User\ErrorHandlerViewModel;

class UserController extends BaseController
{
    protected function onInit() {
        $action = $this->action;
        self::$action();
    }

    public function index() {
        $viewModel = new BaseViewModel();
        $viewModel->title = 'User index';
        $viewModel->baseUrl = $this->baseUrl;
        $this->renderView($viewModel, 'home/index');
    }

    private function initLogin($username, $passowrd) {
        $userModel = new User(null, null, null, $this->repositories->getUserRepo());

        $userId = $userModel->login($username, $passowrd);
        $_SESSION['user_id'] = $userId;
        $this->redirect();
    }

    public function register() {
        $viewModel = new ErrorHandlerViewModel();
        $viewModel->baseUrl = $this->baseUrl;
        if (isset($_POST['username'], $_POST['password'])) {
            try {
                $user = $_POST['username'];
                $pass = $_POST['password'];

                $userModel = new User(null, null, null, $this->repositories->getUserRepo());
                $userModel->register($user, $pass);

                $this->initLogin($user, $pass);

            } catch (\Exception $e) {
                $viewModel->error = $e->getMessage();
                $this->renderView($viewModel);
            }
        }

        $this->renderView($viewModel);
    }

    public function login() {
        $viewModel = new ErrorHandlerViewModel();

        $viewModel->baseUrl = $this->baseUrl;
        if (isset($_POST['username'], $_POST['password'])) {
            try {
                $user = $_POST['username'];
                $pass = $_POST['password'];

                $this->initLogin($user, $pass);

            } catch (\Exception $e) {
                $viewModel->error = $e->getMessage();
                $this->renderView($viewModel);
            }
        }

        return $this->renderView($viewModel);
    }

    public function profile() {
        $viewModel = new ErrorHandlerViewModel();
        $viewModel->baseUrl = $this->baseUrl;

        if($this->getLoggedUserId()) {
            $userModel = new User(null, null, null, $this->repositories->getUserRepo());
            $user = $userModel->getInfo($this->getLoggedUserId());
            $viewModel->user = new User($user['username'], $user['password'], $user['id']);

            if(isset($_POST['edit'])) {
                $username = $_POST['username'];
                $pass = $_POST['password'];
                $confirm = $_POST['confirm'];
                if($pass != $confirm) {
                    $viewModel->error = 'Password does not match';
                    $this->renderView($viewModel);
                }
                else {
                    $newUser = new User($username, $pass, $this->getLoggedUserId());
                    $result = $this->repositories->getUserRepo()->edit($newUser);
                    if ($result) {
                        $viewModel->user = $newUser;
                        $viewModel->success = 'Profile successfully edited';
                        $this->renderView($viewModel);
                    }
                }
            }

            $this->renderView($viewModel);
        }

        $this->renderView($viewModel);
    }

    public function logout() {
        session_destroy();
        $this->redirect();
    }
}