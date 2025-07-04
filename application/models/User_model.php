<?php

use function PHPUnit\Framework\returnArgument;
defined('BASEPATH') or exit('No direct script access allowed');
class User_model extends MY_Model
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
            $this->db->select('u.*, r.role_name');
            $this->db->from('users u');
            $this->db->join('tbl_roles r', 'u.role_id = r.id', 'left');
            $this->db->where('u.user_id', $id);
            $result = $this->db->get()->row_array();
            return $result;
        } else {
            $this->db->select('u.*, r.role_name');
            $this->db->from('users u');
            $this->db->join('tbl_roles r', 'u.role_id = r.id', 'left');

            if ($this->session->userdata('role') == 'admin') {
                $this->db->where('u.created_by', $this->session->userdata('user_id'));
            }

            $this->db->where('u.status', 'A');
            $this->db->where('u.user_id !=', 1);

            $result = $this->db->get()->result_array();
            return $result;
        }
    }



    public function role_list()
    {

        return $this->db->where(['status' => 1])->get('tbl_roles')->result_array();
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
        $userId = $data['user_id'];
        unset($data['user_id']);
        $this->db->where('user_id', $userId);
        return $this->db->update('users', $data);
    }


    public function get_permission_list()
    {
        $this->db->from('permission');
        $result = $this->db->where('status', 'A')->get()->result_array();
        return $result;
    }


    public function user_permission_list($user_id)
    {
        return $this->db->where('user_id', $user_id)
            ->get('tbl_user_permissions')
            ->result_array();
    }

    public function set_permission($user_id, $permission_id)
    {

        $this->db->trans_begin();
        $this->db->where('user_id', $user_id)->delete('user_permission');
        foreach ($permission_id as $permission) {
            $this->db->insert('user_permission', array('user_id' => $user_id, 'permission_id' => $permission, 'created_by' => $this->session->userdata('user_id'), 'created_at' => date('Y-m-d H:i:s')));
        }
        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            return false;
        } else {

            $this->db->trans_commit();

            return true;
        }
    }

    public function getUserByGroup($group_id)
    {
        // $result = $this->db->get_where('users', array('group_id' => $group_id))->result_array();
        $result = $this->db->select('*')
            ->from('`users` u')
            ->join('user_permission up', 'up.user_id = u.user_id', 'LEFT')
            ->join('permission p', 'p.permission_id = up.permission_id', 'LEFT')
            ->where(array('u.group_id' => $group_id, 'u.role' => 'user', 'p.permission_id' => 2))
            ->get()->result_array();
        return $result;
    }
}
