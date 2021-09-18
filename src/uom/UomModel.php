<?php

declare(strict_types=1);

namespace Budget\UOM;

use Budget\Core\AppModel;
use DateTime;
use Exception;

class UomModel extends AppModel
{
    private $table = 'app_uom';
    private $id = 'uom_id';

    public function getAllUoms(): ?array
    {
        try {
            $sql = 'SELECT * FROM ' . $this->table;
            $allUoms = $this->db->fetchAllAssociative($sql);
        } catch (\Exception $e) {
            $err = '<h3>Unable to get all Units - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
        return $allUoms;
    }

    public function getUomById(string $id): ?array
    {
        try {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE uom_id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $id);
            return $stmt->execute()->fetchAllAssociative();
        } catch (\Exception $e) {
            $err = '<h3>Unable to get Unit - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function addUom(array $uom)
    {
        try {
            // get user id for admin as default user
            $sql = "SELECT user_id FROM app_users WHERE user_name LIKE '%Admin%'";
            $result = $this->db->fetchAllAssociative($sql);

            $this->db->insert(
                $this->table,
                array(
                    'uom_name' => $uom['uom_name'],
                    'uom_abbrv' => $uom['uom_abbrv'],
                    'created_by' => $result[0]['user_id'],
                    'updated_by' => $result[0]['user_id']
                )
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to add Unit - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function updateUom(array $uom)
    {
        $update = new DateTime('now');
        $updated_at = $update->format('Y-m-d H:i:s.u P');
        try {
            $this->db->update(
                $this->table,
                array(
                    'uom_name' => $uom['uom_name'],
                    'uom_abbrv' => $uom['uom_abbrv'],
                    'updated_at' => $updated_at,
                ),
                array($this->id => $uom[$this->id])
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Update Category - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function deleteUom(string $id)
    {
        try {
            $this->db->delete($this->table, array($this->id => $id));
        } catch (\Exception $e) {
            $err = '<h3>Unable to Delete Unit - </h3>';
            throw new Exception($err . $e->getMessage());
        }
    }
}
