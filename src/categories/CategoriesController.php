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
                'header' => 'Categories - All Categories',
                'catList' => $this->getAllCategories()
            ];

            return $this->controller->setResponse('cat_all', $data);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function showAddCategoryPage()
    {
        try {
            $data = [
                'header' => 'Categories - Add New Category',
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
                'header' => 'Categories - Edit Category'
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
     * -------------GET DATA FUNCTIONS
     * ==========================================================================
     */
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
     * ==========================================================================
     * -------------ADD DATA FUNCTIONS
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


    /**
     * ==========================================================================
     * -------------UPDATE DATA FUNCTIONS
     * ==========================================================================
     */
    /**
     * Update Details About A Category.
     * Uses the ID to fetch
     */
    public function updateCategory(array $id)
    {
        try {
            $updateData = $this->controller->getPostData();

            $result = $this->model->updateCategory($updateData, $id['catId']);
            
            if ($result !== false) {
                return $this->controller->setRedirect('categories/'.$result['category_id']);
            } else {
                die('Category Not Updated');
            }
            
        } catch (Exception $e) {
            throw new Exception($GLOBALS['err'] . $e->getMessage(), 1);
        }
    }

    public function deleteCategory(array $id)
    {
        try {
            $result = $this->model->deleteCategory($id['catId']);

            if ($result !== false) {
                return $this->controller->setRedirect('/categories');
            } else {
                die('Category Not Updated');
            }
        } catch (Exception $e) {
            $this->responseBody->write($e->getMessage());
            return $this->appResponse->withBody($this->responseBody);
        }
    }
}
