<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Bill_approver_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        $this->db->insert('users', $data);
        $message = "New User " . $data['first_name'] . " Created By " . $_SESSION['name'];
        $record_id = $this->db->insert_id();
        $action = "Create";
        $this->log($message, $record_id, $action);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function get_user_list($id = null)
    {
        if ($id != null) {
            $this->db->where('user_id', $id);
            $result = $this->db->get('users')->row_array();
            return $result;
        } else {

            $this->db->select('u.*');
            $this->db->from('users u');
            $this->db->where('u.status', 'A');
            $this->db->where('u.user_id !=', 1);
            $this->db->where('u.role =', 'bill_approver');
            $result = $this->db->get()->result_array();
            return $result;
        }
    }

    public function get_company()
    {
        $this->db->select('firm_id, firm_type, firm_name');
        $this->db->from('master_firm');
        $this->db->where('status', 'A');
        $this->db->where('is_deleted', 'N');
        $this->db->where('firm_type', 'Company');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function delete($id)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $this->db->where('user_id', $id);
        $this->db->update('users', array('status' => 'D', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        $message = DELETE_RECORD_CONSTANT . " On  users id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function update($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $this->db->where('user_id', $data['user_id']);
        $this->db->update('users', $data);
        $message = UPDATE_RECORD_CONSTANT . " On  users id " . $data['user_id'];
        $action = "Update";
        $record_id = $data['user_id'];
        $this->log($message, $record_id, $action);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }
    
    public function get_departments_by_company_ids($company_ids) {
        $this->db->where_in('company_id', $company_ids);
        $query = $this->db->get('master_department');
        return $query->result_array();
    }
    

   
}
