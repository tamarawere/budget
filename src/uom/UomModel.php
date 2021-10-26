<?php

declare(strict_types=1);

namespace Budget\UOM;

use Budget\Core\AppModel;

use Exception;
use Ramsey\Uuid\Uuid;

class UomModel
{
    private $uomTbl = 'app_uom';
    private $model;

    public function __construct(AppModel $model)
    {
        $this->model = $model;
    }

    public function getAllUoms(): ?array
    {
        try {

            $result = $this->model->getByParams($this->uomTbl);

            if ($result !== false) {
                return $result;
            }
            return [];
        } catch (Exception $e) {
            return $GLOBALS['err'] . $e->getMessage();
        }
    }

    public function getUomById(string $id): ?array
    {
        try {
            $args = [
                'fields' => [
                    'uom_id' => $id
                ]
            ];

            $result = $this->model->getByParams($this->uomTbl, $args);

            if ($result !== false) {
                return $result;
            }
            return [];
        } catch (Exception $e) {
            return $GLOBALS['err'] . $e->getMessage();
        }
    }

    public function addUom(array $uom)
    {
        try {

            $newUom = [
                'uom_id' => Uuid::uuid4(),
                'uom_name' => $uom['uom_name'],
                'uom_abbrv' => $uom['uom_abbrv'],
                'uom_desc' => $uom['uom_desc'],
                'uom_status' => $uom['uom_status'],
                'created_at' => $this->model->now,
                'updated_at' => $this->model->now,
            ];
            
            $result = $this->model->add($this->uomTbl, $newUom);

            if ($result > 0) {
                return $result;
            }
            return false;
        } catch (Exception $e) {
            return $GLOBALS['err'] . $e->getMessage();
        }
    }

    public function updateUom(array $uom, $uomId)
    {
        try {
            $updateUom = [
                'uom_name' => $uom['uomName'],
                'uom_abbrv' => $uom['uomAbbrv'],
                'uom_desc' => $uom['uomDesc'],
                'uom_status' => $uom['uomStatus'],
                'updated_at' => $this->model->now,
            ];

            $condition = [
                'uom_id' => $uomId
            ];

            $result = $this->model->updateByParams($this->uomTbl, $updateUom, $condition);

            if ($result > 0) {
                return $this->getUomById($uomId);
            }
            return false;
        } catch (Exception $e) {
            return $GLOBALS['err'] . $e->getMessage();
        }
    }

    public function deleteUom(string $id)
    {
        try {
            $this->db->delete($this->table, array($this->id => $id));
        } catch (Exception $e) {
            return $GLOBALS['err'] . $e->getMessage();
        }
    }
}
