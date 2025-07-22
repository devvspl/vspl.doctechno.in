<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BaseModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getQuery($query = null)
    {
        $this->db->trans_start();
        try {
            if ($query === null) {
                $this->db->trans_complete();
                return null;
            }
            $result = $this->db->query($query);
            $this->db->trans_complete();
            return $result;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return null;
        }
    }

    public function getData($tbl_name, $cond = null, $order_columns = [], $order_direction = 'ASC', $lim = null)
    {
        $this->db->trans_start();
        try {
            if ($cond !== null) {
                $this->db->where($cond);
            }
            if (!empty($order_columns) && is_array($order_columns)) {
                foreach ($order_columns as $column) {
                    $this->db->order_by($column, $order_direction);
                }
            }
            if ($lim !== null) {
                $this->db->limit($lim);
            }
            $result = $this->db->get($tbl_name);
            $this->db->trans_complete();
            return $result;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return null;
        }
    }

    public function updateData($tbl_name, $data, $cond = null)
    {
        $this->db->trans_start();
        try {
            if ($cond !== null) {
                $this->db->where($cond);
                $result = $this->db->update($tbl_name, $data);
                $this->db->trans_complete();
                return $result;
            }
            $this->db->trans_complete();
            return -1;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return -1;
        }
    }

    public function insertData($tbl_name, $data)
    {
        $this->db->trans_start();
        try {
            $result = $this->db->insert($tbl_name, $data);
            $this->db->trans_complete();
            return $result;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return false;
        }
    }

    public function insertDataWithLastId($tbl_name, $data)
    {
        $this->db->trans_start();
        try {
            $result = $this->db->insert($tbl_name, $data);
            if ($result) {
                $insert_id = $this->db->insert_id();
                $this->db->trans_complete();
                return $insert_id;
            } else {
                $this->db->trans_complete();
                return false;
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return false;
        }
    }

    public function deleteData($tbl_name, $cond = null)
    {
        $this->db->trans_start();
        try {
            if ($cond !== null) {
                $this->db->where($cond);
            }
            $result = $this->db->delete($tbl_name);
            $this->db->trans_complete();
            return $result;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return false;
        }
    }

    public function likeData($tbl_name, $like = null)
    {
        $this->db->trans_start();
        try {
            if ($like !== null) {
                $this->db->like($like);
            }
            $result = $this->db->get($tbl_name);
            $this->db->trans_complete();
            return $result;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return null;
        }
    }

    public function getJoinData($table, $join = [], $where = [], $select = '*', $order_by = null)
    {
        $this->db->trans_start();

        try {
            $this->db->select($select);
            $this->db->from($table);

            
            if (!empty($join)) {
                $this->db->join($join['table'], $join['condition'], $join['type'] ?? 'left');
            }

            
            if (!empty($where)) {
                $this->db->where($where);
            }

            
            if (!empty($order_by)) {
                $this->db->order_by($order_by, 'DESC');
            }

            $result = $this->db->get();
            $this->db->trans_complete();

            return $result;

        } catch (Exception $e) {
            $this->db->trans_rollback();
            return null;
        }
    }

}