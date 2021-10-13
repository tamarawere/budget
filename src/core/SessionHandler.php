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
    private $login_sessions = 'app_login_sessions';
    private $old_sessions = 'app_old_sessions';

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
        } catch (Exception $e) {
            $err = 'Session Database Exception - ';
            return $err . $e->getMessage();
        }
    }

    public function write($session_id, $session_data = null)
    {
        try {

            $sess_data = [
                'session_id' => $session_id,
                'session_data' => $session_data,
                'deleted' => '0',
            ];

            $args = [
                'fields' => $sess_data
            ];

            /**
             * Retrieve an old session
             */
            $old_sess = $this->model->getByParams($this->table, $args);

            $sess_data['created_at'] = $this->model->now;
            $sess_data['updated_at'] = $this->model->now;

            print_r($old_sess); die;
            
            /**
             * check if an old session exists
             */
            if (!empty($old_sess)) {

                if (empty($old_sess['session_data'])) {
                    // if session exists, destroy it
                    $this->destroy($session_id);

                    // print_r(['empty_data'=>$sess_data]); die;
                    $result = $this->model->add($this->table, $sess_data);

                    session_decode($session_data);
                    die('dies-1');
                    return true;
                } else {
                    session_decode($old_sess['session_data']);

                    // print_r(['full_data'=>$sess_data]); die;
                    die('dies-2');
                    return TRUE;
                }
            }

            // print_r(['no_data'=>$sess_data]); die;
            $result = $this->model->add($this->table, $sess_data);

            die('dies-3');
            return TRUE;
        } catch (Exception $e) {
            $err = 'Session Database Exception - ';

            throw new Exception($err . $e->getMessage() . ' - ' . $session_data);
        }
    }

    public function destroy($session_id)
    {
        try {

            $args = [
                'fields' => [
                    'session_id' => $session_id
                ]
            ];

            $session = $this->model->getByParams($this->table, $args);

            if (!empty($session)) {
                $delete_data = [
                    'session_id' => $session_id,
                    'session_data' => $session['session_data'],
                    'deleted_at' => $this->model->now
                ];

                $result = $this->model->add($this->old_sessions, $delete_data);

                $condition = [
                    'session_id' => $session_id
                ];
                $affected_rows = $this->model->deleteByParams($this->table, $condition);
            }

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
