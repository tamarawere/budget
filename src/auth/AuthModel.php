<?php

declare(strict_types=1);

namespace Budget\Categories;

use Budget\Core\AppModel;
use DateTime;
use Exception;

class AuthModel
{
    private $usersTbl = 'users';
    private $id = 'category_id';
    private $model;


    public function __construct(AppModel $appModel)
    {
        $this->model = $appModel;
    }

    public function getAllUsers()
    {
        try {
            $sql = 'SELECT * FROM ' . $this->usersTbl;
            return $this->db->fetchAllAssociative($sql);
        } catch (\Exception $e) {
            $err = 'Users - Database Exception - ';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function getCategoryById(string $id)
    {
        try {
            $sql = "SELECT * FROM " . $this->table . " WHERE " . $this->id . " = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $id);
            return $stmt->execute()->fetchAllAssociative();
        } catch (\Exception $e) {
            $err = '<h3>Unable to get Category - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function addCategory(array $category)
    {
        try {
            // get user id for admin as default user
            $sql = "SELECT user_id FROM app_users WHERE user_name LIKE '%Admin%'";
            $result = $this->db->fetchAllAssociative($sql);

            $this->db->insert(
                $this->table,
                array(
                    'category_name' => $category['category_name'],
                    'category_desc' => $category['category_desc'],
                    'created_by' => $result[0]['user_id'],
                    'updated_by' => $result[0]['user_id']
                )
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Add Category - Database Exception</h3><br><br>';
            return $err . $e->getMessage();
        }
    }

    public function updateCategory(array $category)
    {
        $update = new DateTime('now');
        $updated_at = $update->format('Y-m-d H:i:s.u P');
        try {
            $this->db->update(
                $this->table,
                array(
                    'category_name' => $category['category_name'],
                    'category_desc' => $category['category_desc'],
                    'updated_at' => $updated_at,
                ),
                array($this->id => $category[$this->id])
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Update Category - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function deleteCategory(string $id)
    {
        try {
            $this->db->delete($this->table, array($this->id => $id));
        } catch (\Exception $e) {
            $err = '<h3>Unable to Delete Category - </h3>';
            throw new Exception($err . $e->getMessage());
        }
    }
}
