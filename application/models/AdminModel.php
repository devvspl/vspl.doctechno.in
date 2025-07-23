<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AdminModel extends CI_Model
{
    public function get_ledger_count($search = '', $group = '')
    {
        $this->db->from('master_account_ledger');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('account_name', $search);
            $this->db->or_like('focus_code', $search);
            $this->db->group_end();
        }
        if (!empty($group)) {
            $this->db->where('account_group', $group);
        }
        return $this->db->count_all_results();
    }
    public function get_ledger_list($limit, $offset, $search = '', $group = '')
    {
        $this->db->select('id, account_name, ledger_type, focus_code, account_group');
        $this->db->from('master_account_ledger');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('account_name', $search);
            $this->db->or_like('focus_code', $search);
            $this->db->group_end();
        }

        if (!empty($group)) {
            $this->db->where('account_group', $group);
        }

        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_sub_ledger_count($search = '', $group = '')
    {
        $this->db->from('master_cost_center');
        $this->db->join('master_account_ledger', 'master_account_ledger.id = master_cost_center.parent_id', 'LEFT');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('master_cost_center.name', $search);
            $this->db->or_like('master_cost_center.focus_code', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }
    public function get_sub_ledger_list($limit, $offset, $search = '', $group = '')
    {
        $this->db->select('master_cost_center.id, master_cost_center.name, master_cost_center.parent_id, master_cost_center.focus_code, master_cost_center.status, master_account_ledger.account_name AS ledger_name');
        $this->db->from('master_cost_center');
        $this->db->join('master_account_ledger', 'master_account_ledger.id = master_cost_center.parent_id', 'LEFT');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('master_cost_center.name', $search);
            $this->db->or_like('master_cost_center.focus_code', $search);
            $this->db->group_end();
        }
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }
}
