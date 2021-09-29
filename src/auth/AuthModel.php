<?php

declare(strict_types=1);

namespace Budget\Auth;

use Budget\Core\AppModel;
use DateTime;
use Exception;

class AuthModel
{
    private $model;
    private $table = 'users';

    public function __construct(AppModel $model)
    {
        $this->model = $model;
    }

    public function signInUser($credentials)
    {
        try {
            $params = [
                'fields' => [
                    'user_name' => $credentials['username']
                ]
            ];
            $user_details = $this->model->getByParams($this->table, $params);

            if (password_verify($credentials['password'], $user_details[0]['password'])) {
                /* The password is correct. */
                return $user_details[0];
            }
            return false;
        } catch (Exception $e) {
            $err = 'Unable to Sign User - Database Exception - ';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function getUserDetails($user_id)
    {
        try {
            $params = [
                'fields' => [
                    'user_id' => $user_id
                ]
            ];
            $user_details = $this->model->getByParams($this->table, $params);
            
            return $user_details[0];
        } catch (Exception $e) {
            $err = 'Unable to get user details - Database Exception - ';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function changePassword($data, $user_id)
    {
        $params = [
            'fields' => [
                'user_id' => $user_id
            ]
        ];
        $details = $this->model->getByParams($this->table, $params);

        if (!password_verify($data['current_password'], $details[0]['password'])) {
            throw new Exception("Password do not match");
        }

        $updateData = [
            'password' => password_hash($data['new_password'], PASSWORD_DEFAULT)
        ];

        return $this->model->updateByParams($this->table, $updateData, [
            'user_id' => $user_id
        ]);
    }
}
