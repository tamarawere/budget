<?php

declare(strict_types=1);

namespace Budget\Categories;

use Budget\Core\AppController;
use Exception;

class CategoriesController
{
    private $model, $controller;

    public function __construct(AppController $controller, CategoriesModel $model)
    {
        $this->model = $model;
        $this->controller = $controller;
    }

    public function index()
    {
        try {
            $data = [
                'header' => 'Category DashBoard',
            ];
            return $this->controller->setResponse('cat_home', $data);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }


    /**
     * ==========================================================================
     * -------------SHOW PAGES FUNCTIONS
     * ==========================================================================
     */

    public function showCategoryHomePage()
    {
        try {
            $data = [
                'header' => 'Category DashBoard',
            ];
            return $this->controller->setResponse('cat_home', $data);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function showAllCategoriesPage()
    {
        try {
            $data = [
                'header' => 'Category DashBoard',
            ];
            return $this->controller->setResponse('all_cats', $data);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function showAddCategoryPage()
    {
        try {
            $data = [
                'header' => 'Category DashBoard',
                'catList' => $this->getAllCategories()
            ];
            return $this->controller->setResponse('add_cat', $data);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function showEditCategoryPage($id)
    {
        try {
            $data = [
                'catDetails' => $this->getCategoryById($id),
                'catList' => $this->getAllCategories(),
                'header' => 'WanHeda'
            ];

            return $this->controller->setResponse('edit_cat', $data);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function showCategoryDetailsPage()
    {
        try {
            $data = [
                'header' => 'Category DashBoard',
            ];
            return $this->controller->setResponse('edit_cat', $data);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * ==========================================================================
     * -------------ADD DATA FUNCTION
     * ==========================================================================
     */
    public function addCategory()
    {
        
        $catDetails = $this->controller->getPostData();

        if (!empty($catDetails)) {

            try {
                
                $result = $this->model->addCategory($catDetails);
                
                return $this->controller->setRedirect('categories/'.$result['category_id']);
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

    public function getAllCategories()
    {
        try {
            return $this->model->getAllCategories();
        } catch (Exception $e) {
            throw new Exception($GLOBALS['err'] . $e->getMessage(), 1);
        }
    }

    public function getCategoryById(array $id)
    {
        try {
            return $this->model->getCategoryById($id['catId']);
        } catch (Exception $e) {
            throw new Exception($GLOBALS['err'] . $e->getMessage(), 1);
        }
        
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
