<?php

declare(strict_types=1);

namespace Budget\ItemGroups;

use Budget\Core\AppModel;
use DateTime;
use Exception;

class ItemGroupsModel extends AppModel
{
    private $table = 'app_item_groups';
    private $view = 'item_group_view';
    private $id = 'group_id';

    public function getAllItemGroups(): ?array
    {
        try {
            $sql = 'SELECT * FROM ' . $this->view;
            $allItemGroups = $this->db->fetchAllAssociative($sql);
        } catch (\Exception $e) {
            $err = '<h3>Unable to get Items Groups - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
        return $allItemGroups;
    }

    public function getItemGroupById(string $id): ?array
    {
        try {
            $sql = "SELECT * FROM " . $this->view . " WHERE " . $this->id . " = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $id);
            return $stmt->execute()->fetchAllAssociative();
        } catch (\Exception $e) {
            $err = '<h3>Unable to get Item Group - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function addItemGroup(array $itemGroup)
    {
        try {
            // get user id for admin as default user
            $sql = "SELECT user_id FROM app_users WHERE user_name LIKE '%Admin%'";
            $result = $this->db->fetchAllAssociative($sql);

            $this->db->insert(
                $this->table,
                array(
                    'group_name' => $itemGroup['group_name'],
                    'group_category' => $itemGroup['group_category'],
                    'group_desc' => $itemGroup['group_desc'],
                    'created_by' => $result[0]['user_id'],
                    'updated_by' => $result[0]['user_id']
                )
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Add Item - Database Exception</h3><br><br>';
            return $err . $e->getMessage();
        }
    }

    public function updateItemGroup(array $itemGroup)
    {
        $update = new DateTime('now');
        $updated_at = $update->format('Y-m-d H:i:s.u P');
        try {
            $this->db->update(
                $this->table,
                array(
                    'group_name' => $itemGroup['group_name'],
                    'group_category' => $itemGroup['group_category'],
                    'group_desc' => $itemGroup['group_desc'],
                    'updated_at' => $updated_at,
                ),
                array($this->id => $itemGroup[$this->id])
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Update Item Group - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function deleteItemGroup(string $id)
    {
        try {
            $this->db->delete($this->table, array($this->id => $id));
        } catch (\Exception $e) {
            $err = '<h3>Unable to Delete Item Group - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }
}
