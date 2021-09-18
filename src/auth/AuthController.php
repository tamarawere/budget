<?php

declare(strict_types=1);

namespace Budget\Categories;

use Budget\Core\AppController;

class AuthController extends AppController
{
    private $categoriesModel, $responseBody;

    public function __construct()
    {
        parent::__construct();
        $this->categoriesModel = new CategoriesModel();
        $this->responseBody = $this->appResponse->getBody();
    }

    public function addCategory()
    {
        if (empty($this->appRequest->getParsedBody())) {
            $categoryDetails = json_decode(file_get_contents("php://input"));
        } else {
            $categoryDetails = $this->appRequest->getParsedBody();
        }

        if (!empty($categoryDetails)) {
            try {
                $this->categoriesModel->addCategory((array)$categoryDetails);
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
                ->withStatus(400, 'no category data received');
        }
    }

    public function getAllCategories()
    {
        $categories = $this->categoriesModel->getAllCategories();

        $catArray = [];
        foreach ($categories as $cat) {
            array_push($catArray, $cat);
        }
        $result = json_encode($catArray);
        $this->responseBody->write($result);

        return $this->appResponse->withBody($this->responseBody);
    }

    public function getCategoryById(array $id)
    {
        $category = $this->categoriesModel->getCategoryById($id['id']);
        $result = json_encode($category);
        $this->responseBody->write($result);
        return $this->appResponse->withBody($this->responseBody);
    }

    /**
     * Update Details About A Category.
     * Uses the ID to fetch
     */
    public function updateCategory(array $id)
    {
        if (empty($this->appRequest->getParsedBody())) {
            $categoryDetails = json_decode(file_get_contents("php://input"));
        } else {
            $categoryDetails = $this->appRequest->getParsedBody();
        }
        // print_r($categoryDetails->category_name);

        $category['category_name'] = $categoryDetails->category_name;
        $category['category_desc'] = $categoryDetails->category_desc;

        try {
            $category['category_id'] = $id['id'];
            $this->categoriesModel->updateCategory($category);
            $this->responseBody->write('category updated successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse
                ->withBody($this->responseBody)
                ->withHeader('Content-Type', 'text/plain')
                ->withStatus(400, 'DATABASE EXCEPTION');
        }
    }

    public function deleteCategory(array $id)
    {
        try {
            $this->categoriesModel->deleteCategory($id['id']);
            $this->responseBody->write('category deleted successfully');
            return $this->appResponse->withBody($this->responseBody);
        } catch (\Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse->withBody($this->responseBody);
        }
    }
}
