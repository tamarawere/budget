<?php

declare(strict_types=1);

namespace Budget\LotUnits;

use Budget\Core\AppModel;
use DateTime;
use Exception;

class LotUnitsModel extends AppModel
{
    private $table = 'app_lot_uom';
    private $id = 'unit_id';

    public function getAllLotUnits(): ?array
    {
        try {
            $sql = 'SELECT * FROM ' . $this->table;
            $allLotUnits = $this->db->fetchAllAssociative($sql);
        } catch (\Exception $e) {
            $err = '<h3>Unable to get all LotUnits - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
        return $allLotUnits;
    }

    public function getLotUnitById(string $id): ?array
    {
        try {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE unit_id = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $id);
            return $stmt->execute()->fetchAllAssociative();
        } catch (\Exception $e) {
            $err = '<h3>Unable to get Lot Unit - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function addLotUnit(array $lotUnit)
    {
        try {
            // get user id for admin as default user
            $sql = "SELECT user_id FROM app_users WHERE user_name LIKE '%Admin%'";
            $result = $this->db->fetchAllAssociative($sql);

            $this->db->insert(
                $this->table,
                array(
                    'unit_name' => $lotUnit['unit_name'],
                    'unit_abbrv' => $lotUnit['unit_abbrv'],
                    'created_by' => $result[0]['user_id'],
                    'updated_by' => $result[0]['user_id']
                )
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to add Lot Unit - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function updateLotUnit(array $lotUnit)
    {
        $update = new DateTime('now');
        $updated_at = $update->format('Y-m-d H:i:s.u P');
        try {
            $this->db->update(
                $this->table,
                array(
                    'unit_name' => $lotUnit['unit_name'],
                    'unit_abbrv' => $lotUnit['unit_abbrv'],
                    'updated_at' => $updated_at,
                ),
                array($this->id => $lotUnit[$this->id])
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Update Lot Unit - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function deleteLotUnit(string $id)
    {
        try {
            $this->db->delete($this->table, array($this->id => $id));
        } catch (\Exception $e) {
            $err = '<h3>Unable to Delete Lot Unit - </h3>';
            throw new Exception($err . $e->getMessage());
        }
    }
}
