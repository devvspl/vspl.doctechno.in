<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BaseModel extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getQuery($query = null) {
        try {
            if ($query === null) {
                return null;
            } else {
                return $this->db->query($query);
            }
        }
        catch(Exception $e) {
            return null;
        }
    }

    public function getData($tbl_name, $cond = null, $order_columns = [], $order_direction = 'ASC', $lim = null) {
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
            return $this->db->get($tbl_name);
        }
        catch(Exception $e) {
            return null;
        }
    }

    public function updateData($tbl_name, $data, $cond = null) {
        try {
            if ($cond !== null) {
                $this->db->where($cond);
                return $this->db->update($tbl_name, $data);
            } else {
                return -1;
            }
        }
        catch(Exception $e) {
            return -1;
        }
    }

    public function insertData($tbl_name, $data) {
        try {
            return $this->db->insert($tbl_name, $data);
        }
        catch(Exception $e) {
            return false;
        }
    }

    public function deleteData($tbl_name, $cond = null) {
        try {
            if ($cond !== null) {
                $this->db->where($cond);
            }
            return $this->db->delete($tbl_name);
        }
        catch(Exception $e) {
            return false;
        }
    }

    public function likeData($tbl_name, $like = null) {
        try {
            if ($like !== null) {
                $this->db->like($like);
            }
            return $this->db->get($tbl_name);
        }
        catch(Exception $e) {
            return null;
        }
    }

    public function getJoinData($tbl1, $tbl2, $joinCond, $order = null) {
        try {
            $this->db->select('*,' . $tbl1 . '.Id as ID');
            $this->db->from($tbl1);
            $this->db->join($tbl2, $joinCond);
            if ($order !== null) {
                $this->db->order_by($order, 'DESC');
            }
            return $this->db->get();
        }
        catch(Exception $e) {
            return null;
        }
    }
}