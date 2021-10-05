<?php

declare(strict_types=1);

namespace Budget\Auth;

use Budget\Core\AppController;
use Exception;
use Psr\Http\Message\ResponseInterface;

class AuthController
{
    private $controller;
    private $model;

    public function __construct(AppController $controller, AuthModel $auth_model)
    {
        $this->controller = $controller;
        $this->model = $auth_model;
    }

    public function showLoginPage(): ResponseInterface
    {
        return $this->controller->setResponse('login');
    }

    public function showRegisterPage(): ResponseInterface
    {
        return $this->controller->setResponse('register');
    }

    public function loginUser(): ResponseInterface
    {
        try {
            $user_details = $this->model->signInUser($this->controller->getPostData());

            //print_r($user_details); die;

            if (!isset($user_details) || $user_details === false) {
                $data = ['error' => 'Wrong Username/Password'];
                return $this->controller->setResponse('login', $data);
            }
            $this->setSession($user_details);

            $data = [];

            return $this->controller->setRedirect('');
        } catch (Exception $e) {
            $data = ['error' => 'Login Errors' . $e->getMessage()];

            return $this->controller->setErrorResponse('login', $data);
        }
    }

    public function changePassword()
    {
        try {
            $data = $this->controller->getPostData();

            // $this->controller->validate($data, [
            //     'current_password' => 'required',
            //     'new_password' => 'required'
            // ]);

            $this->model->changePassword($data, $this->controller->user_id);

            $data = [
                'success' => 'Password changed successfully'
            ];

            return $this->controller->setResponse('dash', $data);
        } catch (Exception $e) {
            $data = ['error' => 'Login Errors' . $e->getMessage()];

            return $this->controller->setErrorResponse('login', $data);
        }
    }

    private function setSession($user_data): void
    {
        $_SESSION["id"] = session_id();
        $_SESSION["userId"] = $user_data['user_id'];
        $_SESSION["isLoggedIn"] = true;
        $_SESSION["username"] = $user_data['username'];
        $_SESSION["hostname"] = $GLOBALS['config']['host'];
        return;
    }

    private function deleteSession()
    {
        $_SESSION = array();
        session_destroy();
        return;
    }

    public function logoutUser()
    {
        $this->deleteSession();
        return $this->controller->setRedirect('login');
    }
}
