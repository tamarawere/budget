<?php

declare(strict_types=1);

namespace Budget\Categories;

use Budget\Core\AppModel;
use DateTime;
use Exception;
use Ramsey\Uuid\Nonstandard\Uuid;

class CategoriesModel extends AppModel
{
    private $catTbl = 'app_categories';
    private $model;

    public function __construct(AppModel $model)
    {
        $this->model = $model;
    }

    public function getAllCategories()
    {
        try {

            $data = [];

            $results = $this->model->getByParams($this->catTbl);


            if ($results !== false) {
                if (isset($results['category_id'])) {
                    if (!is_null($results['category_parent']) || !empty($results['category_parent'])) {
                        $results['parent'] = $this->getCategoryById($results['category_parent']);
                    } else {
                        $results['parent'] = '';
                    }
                    return [$results];
                } else {

                    foreach ($results as $result) {
                        if (!is_null($result['category_parent']) || !empty($result['category_parent'])) {
                            $result['parent'] = $this->getCategoryById($result['category_parent']);
                        } else {
                            $result['parent'] = '';
                        }
                        $data[] = $result;
                    }
                }
                return $data;
            }
        } catch (Exception $e) {
            return $GLOBALS['err'] . $e->getMessage();
        }
    }

    public function getCategoryById(string $id)
    {
        try {

            $args = [
                'fields' => [
                    'category_id' => $id
                ]
            ];

            $result = $this->model->getByParams($this->catTbl, $args);

            if ($result !== false) {
                return $result;
            }
        } catch (Exception $e) {
            throw new Exception($GLOBALS['err'] . $e->getMessage(), 1);
        }
    }

    public function addCategory(array $category)
    {
        try {

            $newCat = [
                'category_id' => Uuid::uuid4(),
                'category_name' => $category['catName'],
                'category_desc' => $category['catDesc'],
                'category_parent' => $category['catParent'],
                'status' => $category['catStatus'],
                'created_by' => '7d669076-d175-4d16-a11c-42224167b9a6',
                'created_at' => $this->model->now,
                'updated_at' => $this->model->now,
            ];

            $result = $this->model->add($this->catTbl, $newCat);

            if ($result == 1) {
                return $newCat;
            } else {
                die('somethings funny');
            }
        } catch (Exception $e) {

            die($GLOBALS['err'] . $e->getMessage());
            return $GLOBALS['err'] . $e->getMessage();
        }
    }

    public function updateCategory(array $category)
    {
        $update = new DateTime('now');
        $updated_at = $update->format('Y-m-d H:i:s.u P');
        try {
            $this->db->update(
                $this->table,
                array(
                    'category_name' => $category['category_name'],
                    'category_desc' => $category['category_desc'],
                    'updated_at' => $updated_at,
                ),
                array($this->id => $category[$this->id])
            );
        } catch (\Exception $e) {
            $err = '<h3>Unable to Update Category - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function deleteCategory(string $id)
    {
        try {
            $this->db->delete($this->table, array($this->id => $id));
        } catch (\Exception $e) {
            $err = '<h3>Unable to Delete Category - </h3>';
            throw new Exception($err . $e->getMessage());
        }
    }
}
