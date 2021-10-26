<?php

declare(strict_types=1);

namespace Budget\Core;

use DateTime;
use Doctrine\DBAL\DriverManager;
use Exception;

class AppModel
{
    private $db;
    public $now;
    public $user_id;

    public function __construct()
    {
        $this->db = $this->setDB();
        $this->now = date("Y-m-d H:i:s", time());
    }

    public function getDB()
    {
        return $this->db;
    }

    public function setDB()
    {
        try {
            return DriverManager::getConnection($GLOBALS['config']['db']);
        } catch (\Exception $e) {
            echo 'Get Connection - Database Exception';
            print_r($e->getMessage());
            die('raised');
        }
    }

    public function getAll($table)
    {
        try {
            $sql = 'SELECT * FROM ' . $table;
            return $this->db->fetchAllAssociative($sql);
        } catch (Exception $e) {
            $err = 'Unable to get all items - Database Exception - ';
            throw new Exception($err . $e->getMessage());
        }
    }

    /**
     * Searches using the SQL wild card character
     */
    public function searchByField(string $table, string $field, $value)
    {
        try {
            $sql = "SELECT * FROM " . $table . " WHERE " . $field . " LIKE '%" . $value . "%'";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->executeQuery()->fetchAllAssociative();

            if (isset($result)) {
                return $result;
            }
            return false;
        } catch (Exception $e) {
            $err = 'Unable to get items - Database Exception - ';
            throw new Exception($err . $e->getMessage());
        }
    }


    public function add(string $table, array $data)
    {
        try {
            return $this->db->insert($table, $data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteByParams(string $table, $conditions): int
    {
        try {
            return $this->db->delete($table, $conditions);
        } catch (Exception $e) {
            $err = 'Unable to Delete Item - Database Exception - ';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function getByParams(string $table, array $params = [])
    {
        try {

            $fields = [];
            $attributes = (isset($params['attributes'])) ? $params['attributes'] : "*";

            $sql = "SELECT $attributes FROM " . $table;

            if (isset($params['fields']) && !empty($params['fields'])) {
                foreach ($params['fields'] as $key => $value) {

                    if ($value == null) {

                        $fields[] = $key . " IS NULL ";
                    } else {

                        $fields[] = $key . " = :" . $key;
                    }
                }
            }




            if (isset($fields) && !empty($fields)) {
                $sql .= " WHERE " . implode(" AND ", $fields);
            }

            if (isset($params['order'])) {
                foreach ($params['order'] as $key => $value) {
                    $sql .= " ORDER BY $key $value";
                }
            }

            if (isset($params['limit'])) {
                $sql .= " LIMIT " . $params['limit'];
            }

            $stmt = $this->db->prepare($sql);

            if (isset($fields) && !empty($fields)) {
                foreach ($params['fields'] as $key => $value) {

                    if (!(empty($value) && $value !== '0')) {
                        $stmt->bindValue($key, $value);
                    }
                }
            }


            $result = $stmt->executeQuery()->fetchAllAssociative();

            if (isset($result)) {
                if (count($result) > 1) {
                    return $result;
                } elseif (count($result) == 1) {
                    if (isset($result[0])) {
                        return $result[0];
                    }
                    return $result;
                } else {
                    return $result;
                }
            }
            return false;
        } catch (Exception $e) {
            $err = 'Unable to get items - Database Exception';

            print_r(['the except' => $err . $e->getMessage()]);
            die;
            throw new Exception($err . $e->getMessage());
        }
    }

    public function updateByParams(string $table, array $update_data, array $condition_data)
    {

        try {
            return $this->db->update(
                $table,
                $update_data,
                $condition_data
            );
        } catch (Exception $e) {
            $err = 'Unable to Update Item - Database Exception - ';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function getBySql(string $sql, array $params)
    {
        try {

            $stmt = $this->db->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $result = $stmt->executeQuery()->fetchAllAssociative();
            if (isset($result)) {
                return $result;
            }
            return false;
        } catch (Exception $e) {
            $err = '<h3>Unable to get items - Database Exception</h3>';
            throw new Exception($err . $e->getMessage());
        }
    }

    public function getUuid()
    {
        # code...
    }

    public function prepareLoggerData($data, $fields)
    {

        $logged_data = [];

        foreach ($fields as $field) {

            if (isset($data[$field])) {

                $logged_data[$field] = $data[$field];
            }
        }

        return $logged_data;
    }
}
