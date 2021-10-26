<?php

declare(strict_types=1);

namespace Budget\Items;

use Budget\Core\AppController;

class ItemsController extends AppController
{
    private $itemsModel, $responseBody;

    public function __construct()
    {
        parent::__construct();
        $this->itemsModel = new ItemsModel();
        $this->responseBody = $this->appResponse->getBody();
    }

    public function addItem()
    {

        if (empty($this->appRequest->getParsedBody())) {
            $itemDetails = json_decode(file_get_contents("php://input"));
        } else {
            $itemDetails = $this->appRequest->getParsedBody();
        }


        if (!empty($itemDetails)) {
            try {
                $itemDetails = (array)$itemDetails;

                if (is_array($itemDetails)) {
                    $vale = $this->itemsModel->addItem($itemDetails);
                    $this->responseBody->write('everything ok');
                    return $this->appResponse->withBody($this->responseBody);
                }
            } catch (\Exception $e) {
                $this->responseBody->write($e->getMessage());

                return $this->appResponse
                    ->withBody($this->responseBody)
                    ->withHeader('Content-Type', 'text/plain')
                    ->withStatus(500, 'Problem adding item to Database');
            }
        } else {
            $this->responseBody->write('have to write somethin');
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'no item data received');
        }
    }

    public function getAllItems()
    {
        $items = $this->itemsModel->getAllItems();

        $itemsArray = [];
        foreach ($items as $item) {
            array_push($itemsArray, $item);
        }
        $result = json_encode($itemsArray);
        $this->responseBody->write($result);

        return $this->appResponse->withBody($this->responseBody);
    }

    public function getItemById(array $id)
    {
        $item = $this->itemsModel->getItemById($id['id']);
        $result = json_encode($item);
        $this->responseBody->write($result);
        return $this->appResponse->withBody($this->responseBody);
    }

    public function updateItem(array $id)
    {
        if (empty($this->appRequest->getParsedBody())) {
            $itemDetails = json_decode(file_get_contents("php://input"));
        } else {
            $itemDetails = $this->appRequest->getParsedBody();
        }
        // print_r($categoryDetails->category_name);

        $item['item_name'] = $itemDetails->item_name;
        $item['item_view_name'] = $itemDetails->item_name;
        $item['item_group'] = $itemDetails->item_name;
        $item['item_desc'] = $itemDetails->item_name;
        $item['item_is_package'] = $itemDetails->item_name;
        $item['item_units_per_package'] = $itemDetails->item_name;
        $item['item_packaging_uom'] = $itemDetails->item_name;
        $item['item_quantity'] = $itemDetails->item_brand;
        $item['item_uom'] = $itemDetails->item_uom;

        try {
            $item['item_id'] = $id['id'];
            $this->itemsModel->updateItem($item);
            $this->responseBody->write('item updated successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'DATABASE EXCEPTION');
        }
    }

    public function deleteItem(array $id)
    {
        try {
            $this->itemsModel->deleteItem($id['id']);
            $this->responseBody->write('Item deleted successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse->withBody($this->responseBody);
        }
    }
}
