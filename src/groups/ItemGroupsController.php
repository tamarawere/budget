<?php

declare(strict_types=1);

namespace Budget\ItemGroups;

use Budget\Core\AppController;
use Budget\ItemGroups\ItemGroupsModel;

class ItemGroupsController extends AppController
{
    private $itemGroupsModel, $responseBody;

    public function __construct()
    {
        parent::__construct();
        $this->itemGroupsModel = new ItemGroupsModel();
        $this->responseBody = $this->appResponse->getBody();
    }

    public function addItemGroup()
    {
        if (empty($this->appRequest->getParsedBody())) {
            $itemGroupDetails = json_decode(file_get_contents("php://input"));
        } else {
            $itemGroupDetails = $this->appRequest->getParsedBody();
        }

        if (!empty($itemGroupDetails)) {
            try {
                $this->itemGroupsModel->addItemGroup((array)$itemGroupDetails);
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
                ->withStatus(400, 'no item-group data received');
        }
    }

    public function getAllItemGroups()
    {
        $itemGroups = $this->itemGroupsModel->getAllItemGroups();

        $itemGroupsArray = [];
        foreach ($itemGroups as $group) {
            array_push($itemGroupsArray, $group);
        }
        $result = json_encode($itemGroupsArray);
        $this->responseBody->write($result);

        return $this->appResponse->withBody($this->responseBody);
    }

    public function getItemGroupById(array $id)
    {
        $itemGroup = $this->itemGroupsModel->getItemGroupById($id['id']);
        $result = json_encode($itemGroup);
        $this->responseBody->write($result);
        return $this->appResponse->withBody($this->responseBody);
    }

    public function updateItemGroup(array $id)
    {
        if (empty($this->appRequest->getParsedBody())) {
            $itemGroupDetails = json_decode(file_get_contents("php://input"));
        } else {
            $itemGroupDetails = $this->appRequest->getParsedBody();
        }

        $itemGroup['group_name'] = $itemGroupDetails['group_name'];
        $itemGroup['group_desc'] = $itemGroupDetails['group_desc'];
        $itemGroup['group_category'] = $itemGroupDetails['group_category'];

        try {
            $itemGroup['group_id'] = $id['id'];
            $this->itemGroupsModel->updateItemGroup($itemGroup);
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

    public function deleteItemGroup(array $id)
    {
        try {
            $this->itemGroupsModel->deleteItemGroup($id['id']);
            $this->responseBody->write('Item deleted successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse->withBody($this->responseBody);
        }
    }
}
