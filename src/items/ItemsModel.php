<?php

declare(strict_types=1);

namespace Budget\Items;

use Budget\Core\AppModel;
use DateTime;
use Exception;

class ItemsModel extends AppModel
{
    private $table = 'app_items';
    private $view = 'item_master_view';
    private $id = 'item_id';

    public function getAllItems(): ?array
    {
        try {
            $sql = 'SELECT * FROM ' . $this->view;
            $allItems = $this->db->fetchAllAssociative($sql);
        } catch (\Exception $e) {
            $err = '<h3>Unable to get Items - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
        return $allItems;
    }

    public function getItemById(string $id): ?array
    {
        try {
            $sql = "SELECT * FROM " . $this->view . " WHERE " . $this->id . " = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $id);
            return $stmt->execute()->fetchAllAssociative();
        } catch (\Exception $e) {
            $err = '<h3>Unable to get Item - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function addItem(array $item)
    {
        try {
            $sql = "SELECT user_id FROM app_users WHERE user_name LIKE '%Admin%'";
            $result = $this->db->fetchAllAssociative($sql);

            return $this->db->insert(
                $this->table,
                array(
                    'item_name' => $item['item_name'],
                    'item_view_name' => $item['item_view_name'],
                    'item_group' => $item['item_group'],
                    'item_desc' => $item['item_desc'],
                    'item_is_package' => $item['item_is_package'],
                    'item_units_per_package' => floatval($item['item_units_per_package']),
                    'item_packaging_uom' => $item['item_packaging_uom'],
                    'item_quantity' => floatval($item['item_quantity']),
                    'item_uom' => $item['item_uom'],
                    'created_by' => $result[0]['user_id'],
                    'updated_by' => $result[0]['user_id']
                )
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Add Item - Database Exception</h3><br><br>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function updateItem(array $item)
    {
        $update = new DateTime('now');
        $updated_at = $update->format('Y-m-d H:i:s.u P');
        try {
            $this->db->update(
                $this->table,
                array(
                    'item_name' => $item['item_name'],
                    'item_view_name' => $item['item_view_name'],
                    'item_group' => $item['item_group'],
                    'item_desc' => $item['item_desc'],
                    'item_is_package' => $item['item_is_package'],
                    'item_units_per_package' => $item['item_units_per_package'],
                    'item_packaging_uom' => $item['item_packaging_uom'],
                    'item_weight' => $item['item_weight'],
                    'item_quantity' => $item['item_quantity'],
                    'item_age' => $item['item_age'],
                    'item_shape' => $item['item_shape'],
                    'item_color' => $item['item_color'],
                    'item_origin' => $item['item_origin'],
                    'item_material' => $item['item_material'],
                    'item_purpose' => $item['item_purpose'],
                    'item_uom' => $item['item_uom'],
                    'updated_at' => $updated_at,
                ),
                array($this->id => $item[$this->id])
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Update Item - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function deleteItem(string $id)
    {
        try {
            $this->db->delete($this->table, array($this->id => $id));
        } catch (\Exception $e) {
            $err = '<h3>Unable to Delete Item - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }
}
