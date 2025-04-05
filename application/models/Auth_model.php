<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Auth_model extends CI_Model {
    function login($data) {
        $this->db->select('*');
        $result = $this->db->get_where('users u', array('u.username' => $data['identity'], 'u.password' => $data['password'], 'u.status' => 'A'))->result_array();
        if (count($result) > 0) {
            return $result;
        }
        return null;
    }
    function changepass($data) {
        $this->db->select('*');
        $result = $this->db->get_where('users u', array('u.user_id' => $data['user_id'], 'u.password' => $data['current_pass']))->result_array();
        if (count($result) > 0) {
            $this->db->where('user_id', $data['user_id']);
            $this->db->update('users', array('password' => $data['new_pass']));
            return true;
        }
        return false;
    }
    public function get_user_permission($user_id) {
        $user_id = $user_id;
        $u_list = $this->db->select('permission_name')->join('permission', 'permission.permission_id=user_permission.permission_id')->get_where('user_permission', array('user_id' => $user_id, 'permission.category' => 'Access'))->result_array();
        $p_list = $this->db->select('permission_name')->get_where('permission', array('category' => 'Access'))->result_array();
        $permission_list = array();
        foreach ($p_list as $p) {
            $permission_list[] = $p['permission_name'];
        }
        $user_permission_list = array();
        foreach ($u_list as $u) {
            $user_permission_list[] = $u['permission_name'];
        }
        $permission = array();
        foreach ($permission_list as $list) {
            $permission[$list] = 'N';
            foreach ($user_permission_list as $up) {
                if ($list == $up) {
                    $permission[$list] = 'Y';
                    break;
                }
            }
        }
        if (count($permission) > 0) return $permission;
        else return null;
    }
	function get_financial_years() {
		$this->db->select('id, label');
		$this->db->order_by('id', 'desc');
		$result = $this->db->get('financial_years')->result_array();
		return count($result) ? $result : null;
	}
}
