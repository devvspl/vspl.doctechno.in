<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdminModel extends CI_Model
{
    public function get_account_count($search = '', $group = '')
    {
        $this->db->from('master_account_ledger');
        if (!empty($search)) {
            $this->db->like('account_name', $search);
            // Optionally add other searchable columns
            // $this->db->or_like('ledger_type', $search);
            // $this->db->or_like('focus_code', $search);
        }
        if (!empty($group)) {
            $this->db->where('account_group', $group);
        }
        return $this->db->count_all_results();
    }

    public function get_account_list($limit, $offset, $search = '', $group = '')
    {
        $this->db->select('id, account_name, ledger_type, focus_code, account_group');
        $this->db->from('master_account_ledger');
        if (!empty($search)) {
            $this->db->like('account_name', $search);
            $this->db->or_like('focus_code', $search);
        }
        if (!empty($group)) {
            $this->db->where('account_group', $group);
        }
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_accounts($search = '', $group = '')
    {
        $this->db->select('id, account_name, ledger_type, focus_code, account_group');
        $this->db->from('master_account_ledger');
        if (!empty($search)) {
            $this->db->like('account_name', $search);
            $this->db->or_like('focus_code', $search);
        }
        if (!empty($group)) {
            $this->db->where('account_group', $group);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
}