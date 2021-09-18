<?php

declare(strict_types=1);

namespace Budget\UOM;

use Budget\Core\AppController;

class UomController extends AppController
{
    private $uomModel, $responseBody;

    public function __construct()
    {
        parent::__construct();
        $this->uomModel = new UomModel();
        $this->responseBody = $this->appResponse->getBody();
    }

    public function addUom()
    {
        if (empty($this->appRequest->getParsedBody())) {
            $uomDetails = json_decode(file_get_contents("php://input"));
        } else {
            $uomDetails = $this->appRequest->getParsedBody();
        }

        if (!empty($uomDetails)) {
            try {
                $this->uomModel->addUom((array)$uomDetails);
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

    public function getAllUoms()
    {
        $uomArray = [];

        try {
            $uoms = $this->uomModel->getAllUoms();
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'DATABASE EXCEPTION');
        }

        foreach ($uoms as $unit) {
            array_push($uomArray, $unit);
        }
        $result = json_encode($uomArray);
        $this->responseBody->write($result);

        return $this->appResponse->withBody($this->responseBody);
    }

    public function getUomById(array $id)
    {
        $uom = $this->uomModel->getUomById($id['id']);
        $result = json_encode($uom);
        $this->responseBody->write($result);
        return $this->appResponse->withBody($this->responseBody);
    }

    public function updateUom(array $id)
    {
        if (empty($this->appRequest->getParsedBody())) {
            $uomDetails = json_decode(file_get_contents("php://input"));
        } else {
            $uomDetails = $this->appRequest->getParsedBody();
        }
        // print_r($categoryDetails->category_name);

        $uom['uom_name'] = $uomDetails->uom_name;
        $uom['uom_abbrv'] = $uomDetails->uom_abbrv;

        try {
            $uom['uom_id'] = $id['id'];
            $this->uomModel->updateUom($uom);
            $this->responseBody->write('Unit updated successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'DATABASE EXCEPTION');
        }
    }

    public function deleteUom(array $id)
    {
        try {
            $this->uomModel->deleteUom($id['id']);
            $this->responseBody->write('Unit Deleted successfully');
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
