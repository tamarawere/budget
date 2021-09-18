<?php

declare(strict_types=1);

namespace Budget\PaymentModes;

use Budget\Core\AppModel;
use DateTime;
use Exception;

class PaymentModesModel extends AppModel
{
    private $table = 'app_payment_modes';
    private $id = 'mode_id';

    public function getAllPaymentModes(): ?array
    {
        try {
            $sql = 'SELECT * FROM ' . $this->table;
            $allModes = $this->db->fetchAllAssociative($sql);
        } catch (\Exception $e) {
            $err = '<h3>Unable to get all Users - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
        return $allModes;
    }

    public function getPaymentModeById(string $id): ?array
    {
        try {
            $sql = "SELECT * FROM " . $this->table . " WHERE " . $this->id . " = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $id);
            return $stmt->execute()->fetchAllAssociative();
        } catch (\Exception $e) {
            $err = '<h3>Unable to get Payment Mode - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function addPaymentMode(array $mode)
    {
        try {
            // get user id for admin as default user
            $sql = "SELECT user_id FROM app_users WHERE user_name LIKE '%Admin%'";
            $result = $this->db->fetchAllAssociative($sql);

            $this->db->insert(
                $this->table,
                array(
                    'mode_name' => $mode['mode_name'],
                    'mode_desc' => $mode['mode_desc'],
                    'created_by' => $result[0]['user_id'],
                    'updated_by' => $result[0]['user_id']
                )
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Add Payment Mode - Database Exception</h3><br><br>';
            return $err . $e->getMessage();
        }
    }

    public function updatePaymentMode(array $mode)
    {
        $update = new DateTime('now');
        $updated_at = $update->format('Y-m-d H:i:s.u P');
        try {
            $this->db->update(
                $this->table,
                array(
                    'mode_name' => $mode['mode_name'],
                    'mode_desc' => $mode['mode_desc'],
                    'updated_at' => $updated_at,
                ),
                array($this->id => $mode[$this->id])
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Update Payment Mode - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function deletePaymentMode(string $id)
    {
        try {
            $this->db->delete($this->table, array($this->id => $id));
        } catch (\Exception $e) {
            $err = '<h3>Unable to Delete Payment Mode - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }
}
