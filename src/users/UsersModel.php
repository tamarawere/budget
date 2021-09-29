<?php

declare(strict_types=1);

namespace Budget\Users;

use Budget\Core\AppModel;
use DateTime;
use Exception;

class UsersModel extends AppModel
{
    private $table = 'app_users';
    private $id = 'user_id';

    public function getAllUsers(): ?array
    {
        try {
            $sql = 'SELECT * FROM ' . $this->table;
            $allUsers = $this->db->fetchAllAssociative($sql);
        } catch (Exception $e) {
            $err = 'Unable to get all Users - Database Exception - ';
            throw new Exception($err . $e->getMessage());
        }
        return $allUsers;
    }

    public function getUserById(string $id): ?array
    {
        try {
            $sql = "SELECT * FROM " . $this->table . " WHERE " . $this->id . " = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $id);
            return $stmt->execute()->fetchAllAssociative();
        } catch (\Exception $e) {
            $err = 'Unable to get User - Database Exception - ';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function addUser(array $user)
    {
        try {
            $this->db->insert(
                $this->table,
                array(
                    'username' => $user['username'],
                    'fullname' => $user['fullname'],
                    'email' => $user['email'],
                    'password' => $user['password'],
                    'status' => 'ative',
                )
            );
        } catch (Exception $e) {
            $err = 'Unable to Add User - Database Exception - ';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function updateUser(array $user)
    {
        $update = new DateTime('now');
        $updated_at = $update->format('Y-m-d H:i:s.u P');
        try {
            $this->db->update(
                $this->table,
                array(
                    'user_name' => $user['user_name'],
                    'full_name' => $user['full_name'],
                    'updated_at' => $updated_at,
                ),
                array($this->id => $user[$this->id])
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Update User - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function deleteUser(string $id)
    {
        try {
            $this->db->delete($this->table, array($this->id => $id));
        } catch (\Exception $e) {
            $err = '<h3>Unable to Delete User - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }
}
