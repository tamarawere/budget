<?php

declare(strict_types=1);

namespace Budget\Users;

use Budget\Core\AppController;

class UsersController extends AppController
{
    private $usersModel, $responseBody;

    public function __construct()
    {
        parent::__construct();
        $this->usersModel = new UsersModel();
        $this->responseBody = $this->appResponse->getBody();
    }

    public function addUser()
    {
        if (empty($this->appRequest->getParsedBody())) {
            $userDetails = json_decode(file_get_contents("php://input"));
        } else {
            $userDetails = $this->appRequest->getParsedBody();
        }

        if (!empty($userDetails)) {
            try {
                $this->usersModel->addUser((array)$userDetails);
                $this->responseBody->write('everything ok');
                return $this->appResponse->withBody($this->responseBody);
            } catch (\Exception $e) {
                $this->responseBody->write($e->getMessage());
                return $this->appResponse->withBody($this->responseBody);
            }
        } else {
            $this->responseBody->write('have to write somethin');
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'no user data received');
        }
    }

    public function getAllUsers()
    {
        $users = $this->usersModel->getAllUsers();

        $usersArray = [];
        foreach ($users as $user) {
            array_push($usersArray, $user);
        }
        $result = json_encode($usersArray);
        $this->responseBody->write($result);

        return $this->appResponse->withBody($this->responseBody);
    }

    public function getUserById(array $id)
    {
        $user = $this->usersModel->getUserById($id['id']);
        $result = json_encode($user);
        $this->responseBody->write($result);
        return $this->appResponse->withBody($this->responseBody);
    }

    public function updateUser(array $id)
    {
        if (empty($this->appRequest->getParsedBody())) {
            $userDetails = json_decode(file_get_contents("php://input"));
        } else {
            $userDetails = $this->appRequest->getParsedBody();
        }
        // print_r($categoryDetails->category_name);

        $user['user_name'] = $userDetails->user_name;
        $user['full_name'] = $userDetails->full_name;

        try {
            $user['user_id'] = $id['id'];
            $this->usersModel->updateUser($user);
            $this->responseBody->write('user updated successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'DATABASE EXCEPTION');
        }
    }

    public function deleteUser(array $id)
    {
        try {
            $this->usersModel->deleteUser($id['id']);
            $this->responseBody->write('user deleted successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse->withBody($this->responseBody);
        }
    }
}
