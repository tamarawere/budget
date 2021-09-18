<?php

declare(strict_types=1);

namespace Budget\Core;

use Exception;
use SessionHandlerInterface;

class SessionHandler implements SessionHandlerInterface
{
    /**
     * Provides the abstraction for basic
     * CRUD database operations
     */
    private $model = null;

    /**
     * The database table used 
     * to save session data
     */
    private $table = 'app_sessions';

    /**
     * the name of the id column or 
     * primary key in the session table
     */
    private $id = 'session_id';

    public function __construct(AppModel $model)
    {
        try {
            $this->model = $model;
        } catch (Exception $e) {
            $err = 'Unable to Open Session Database Connection - Database Exception - ';
            return $err . $e->getMessage();
        }
    }

    public function open($savePath, $sessionName)
    {
        if (!is_null($this->model)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function read($session_id)
    {
        try {
            $args = [
                'fields' => [
                    'session_id' => $session_id
                ]
            ];

            $sess_id = $this->model->getByParams($this->table, $args);

            if (isset($sess_id)) {
                return $sess_id['session_data'];
            }
            return '';
        } catch (\Exception $e) {
            $err = 'Session Database Exception - ';
            return $err . $e->getMessage();
        }
    }

    public function write($session_id, $session_data = null)
    {
        try {

            if (empty($session_data)) {
                $data = 'empty';
            } else {
                $data = $session_data;
            }

            $sess_data = [
                'session_id' => $session_id,
                'session_data' => $data,
                'deleted' => '0',
                'created_at' => $this->model->now,
                'updated_at' => $this->model->now
            ];

            $args = [
                'fields' => $sess_data
            ];

            /**
             * Retrieve an old session
             */
            $old_sess = $this->model->getByParams($this->table, $args);

            /**
             * check if an old session exists
             */
            if (isset($old_sess) && is_iterable($old_sess)) {
                // if session exists, destroy it
                $this->destroy($session_id);
            }
            $result = $this->model->add($this->table, $sess_data);
            return TRUE;
        } catch (Exception $e) {
            $err = 'Session Database Exception - ';
            throw new Exception($err . $e->getMessage() . ' - ' . $session_data);
        }
    }

    public function destroy($session_id)
    {
        try {
            $data = [
                'deleted' => '1',
                'updated_at' => $this->model->now
            ];

            $condition = [
                'session_id' => $session_id
            ];
            $affected_rows = $this->model->updateByParams($this->table, $data, $condition);
            return true;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function gc($maxlifetime)
    {
        $conn = $this->model->getDB();
        $past = time() - $maxlifetime;

        try {
            $sql = "DELETE FROM $this->table WHERE `created_at` < ?";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $past);
            $result = $stmt->executeQuery();
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
        return true;
    }

    public function close()
    {
        return TRUE;
    }
}
