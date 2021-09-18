<?php

declare(strict_types=1);

namespace Budget\LotUnits;

use Budget\Core\AppController;

class LotUnitsController extends AppController
{
    private $lotUnitsModel, $responseBody;

    public function __construct()
    {
        parent::__construct();
        $this->lotUnitsModel = new LotUnitsModel();
        $this->responseBody = $this->appResponse->getBody();
    }

    public function addLotUnit()
    {
        if (empty($this->appRequest->getParsedBody())) {
            $lotUnitDetails = json_decode(file_get_contents("php://input"));
        } else {
            $lotUnitDetails = $this->appRequest->getParsedBody();
        }

        if (!empty($lotUnitDetails)) {
            try {
                $this->lotUnitsModel->addLotUnit((array)$lotUnitDetails);
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
                ->withStatus(400, 'no unit data received');
        }
    }

    public function getAllLotUnits()
    {
        $lotUnitArray = [];

        try {
            $lotUnits = $this->lotUnitsModel->getAllLotUnits();
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'DATABASE EXCEPTION');
        }

        foreach ($lotUnits as $lotUnit) {
            array_push($lotUnitArray, $lotUnit);
        }
        $result = json_encode($lotUnitArray);
        $this->responseBody->write($result);

        return $this->appResponse->withBody($this->responseBody);
    }

    public function getLotUnitById(array $id)
    {
        $lotUnit = $this->lotUnitsModel->getLotUnitById($id['id']);
        $result = json_encode($lotUnit);
        $this->responseBody->write($result);
        return $this->appResponse->withBody($this->responseBody);
    }

    public function updateLotUnit(array $id)
    {
        if (empty($this->appRequest->getParsedBody())) {
            $lotUnitDetails = json_decode(file_get_contents("php://input"));
        } else {
            $lotUnitDetails = $this->appRequest->getParsedBody();
        }
        // print_r($categoryDetails->category_name);

        $lotUnit['unit_name'] = $lotUnitDetails->unit_name;
        $lotUnit['unit_abbrv'] = $lotUnitDetails->unit_abbrv;

        try {
            $lotUnit['unit_id'] = $id['id'];
            $this->lotUnitsModel->updateLotUnit($lotUnit);
            $this->responseBody->write('Lot updated successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'DATABASE EXCEPTION');
        }
    }

    public function deleteLotUnit(array $id)
    {
        try {
            $this->lotUnitsModel->deleteLotUnit($id['id']);
            $this->responseBody->write('LotUnit Deleted successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'DATABASE EXCEPTION');
        }
    }
}
