<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Account_model extends CI_Model {
    public function create($data) {
        $result = $this->db->insert('master_account_ledger', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function delete($id) {
        $this->db->where('account_id', $id);
        $result = $this->db->update('master_account_ledger', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function update($data) {
        $this->db->where('account_id', $data['account_id']);
        $result = $this->db->update('master_account_ledger', $data);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function count_filtered_accounts($group = null, $search = null) {
        $this->db->from('master_account_ledger');
        $this->db->where('is_deleted', 'N');
        if ($group) {
            $this->db->where('account_group', $group);
        }
        if ($search) {
            $this->db->group_start();
            $this->db->like('account_name', $search);
            $this->db->or_like('account_code', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }
    public function get_account_list($limit = null, $start = null, $group = null, $search = null) {
        $this->db->select('*');
        $this->db->from('master_account_ledger');
        $this->db->where('is_deleted', 'N');
        if ($group !== null && $group !== '') {
            $this->db->where('account_group', $group);
        }
        if ($search !== null && $search !== '') {
            $this->db->like('account_name', $search);
            $this->db->or_like('account_code', $search);
        }
        if ($limit !== null && $start !== null) {
            $this->db->limit($limit, $start);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function getGroupedData() {
        $this->db->select('account_group, COUNT(*) as total_accounts');
        $this->db->from('master_account_ledger');
        $this->db->where('is_deleted', 'N');
        $this->db->group_by('account_group');
        $this->db->order_by('account_group', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
