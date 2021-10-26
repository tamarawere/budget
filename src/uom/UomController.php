<?php

declare(strict_types=1);

namespace Budget\UOM;

use Budget\Core\AppController;

use Exception;

class UomController 
{
    private $model, $controller;

    /**
     * Tiltes for twig templates
     */
    private $titles = [];

    public function __construct(AppController $controller, UomModel $model)
    {
        $this->model = $model;
        $this->controller = $controller;

        $this->titles = $this->twigTitles();
    }

    private function twigTitles()
    {
        return [
            'mod' => 'UNITS OF MEASURE',
            'subMod' => 'UNITS DASHBOARD'
        ];
    }

    /**
     * ==========================================================================
     * -------------SHOW PAGES FUNCTIONS
     * ==========================================================================
     */

    public function showUomHomePage()
    {
        try {
            $data = [
                'title' => $this->titles,
            ];
            return $this->controller->setResponse('uom_home', $data);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function showAllUomsPage()
    {
        try {
            $title = $this->titles;
            $title['subMod'] = 'All Units';

            $data = [
                'title' => $title,
                'uomList' => $this->getAllUoms()
            ];

            return $this->controller->setResponse('uom_all', $data);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function showAddUomPage()
    {
        try {
            $title = $this->titles;
            $title['subMod'] = 'Add Unit';

            $data = [
                'title' => $title,
                'uomList' => $this->getAllUoms()
            ];

            return $this->controller->setResponse('uom_add', $data);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * ==========================================================================
     * -------------GET DATA FUNCTIONS
     * ==========================================================================
     */
    

    public function getAllUoms()
    {
        try {
            return $this->model->getAllUoms();
        } catch (Exception $e) {
            throw new Exception($GLOBALS['err'] . $e->getMessage(), 1);
        }
    }

    public function getUomById(array $id)
    {
        try {
            return $this->model->getUomById($id['uomId']);
        } catch (Exception $e) {
            throw new Exception($GLOBALS['err'] . $e->getMessage(), 1);
        }
    }

    public function addUom()
    {
        $newUom = $this->controller->getPostData();

        if (!empty($newUom)) {

            try {
                
                $result = $this->model->addUom($newUom);
                
                return $this->controller->setRedirect('uom/'.$result['uom_id']);
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            $this->responseBody->write('have to write somethin');
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'no category data received');
        }
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
