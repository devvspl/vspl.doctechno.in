<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Approve_model extends MY_Model {
    protected $year_id;
    public function __construct() {

        parent::__construct();
        $this->year_id = $this->session->userdata('year_id');
    }
    function pending_for_approval() {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('is_file_punched', 'Y');
        $this->db->where('is_file_approved', 'N');
        $this->db->where('is_rejected', 'N');
        if ($_SESSION['role'] != 'super_approver') {
            $this->db->where_in('group_id', $group_id);
        }
        $this->db->order_by('scan_id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_my_approved_file($limit, $start, $group = '', $Doctype = '', $search = '', $from_date = '', $to_date = '') {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('master_group', 'master_group.group_id = y{$this->year_id}_scan_file.Group_Id', 'left');
        $this->db->where('is_file_approved', 'Y');
        $this->db->where('approved_by', $user_id);
        if ($group !== '') {
            $this->db->where('y{$this->year_id}_scan_file.Group_Id', $group);
        }
		if ($Doctype !== '') {
            $this->db->where('y{$this->year_id}_scan_file.doc_type_id', $Doctype);
        }
        if ($from_date !== '' && $to_date !== '') {
            $this->db->where('Approve_Date >=', $from_date);
            $this->db->where('Approve_Date <=', $to_date);
        }
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('document_name', $search);
            $this->db->or_like('approved_by', $search);
            $this->db->or_like('approved_date', $search);
            $this->db->or_like('file_name', $search);
            $this->db->group_end();
        }
        $this->db->order_by('approved_date', 'desc');
        $this->db->order_by('scan_id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function count_filtered_records($search = '', $from_date = '', $to_date = '', $group = '', $Doctype = '') {
        $user_id = $this->session->userdata('user_id');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('master_group', 'master_group.group_id = y{$this->year_id}_scan_file.Group_Id', 'left');
        $this->db->where('is_file_approved', 'Y');
        $this->db->where('approved_by', $user_id);
        if ($group !== '') {
            $this->db->where('y{$this->year_id}_scan_file.Group_Id', $group);
        }
		if ($Doctype !== '') {
            $this->db->where('y{$this->year_id}_scan_file.doc_type_id', $Doctype);
        }
        if ($from_date !== '' && $to_date !== '') {
            $this->db->where('Approve_Date >=', $from_date);
            $this->db->where('Approve_Date <=', $to_date);
        }
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('document_name', $search);
            $this->db->or_like('approved_by', $search);
            $this->db->or_like('approved_date', $search);
            $this->db->or_like('file_name', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }
    function get_my_approved_file_all() {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('master_group', 'master_group.group_id = y{$this->year_id}_scan_file.Group_Id', 'left');
        $this->db->where('is_file_approved', 'Y');
        $this->db->where('approved_by', $user_id);
        $this->db->order_by('scan_id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
    function search_approved_file($from_date, $to_date) {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('master_group', 'master_group.group_id = y{$this->year_id}_scan_file.Group_Id', 'left');
        $this->db->where('is_file_punched', 'Y');
        $this->db->where('is_file_approved', 'Y');
        $this->db->where_in('approved_by', $user_id);
        $this->db->where('date(Approve_Date) >=', $from_date);
        $this->db->where('date(Approve_Date) <=', $to_date);
        $this->db->order_by('scan_id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_total_approved_by_me() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('approved_by', $user_id);
        $this->db->where('is_file_approved', 'Y');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function get_rejected_by_me() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('approved_by', $user_id);
        $this->db->where('is_file_approved', 'N');
        $this->db->where('is_rejected', 'Y');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function get_pending_for_approval_count() {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('is_file_punched', 'Y');
        $this->db->where('is_file_approved', 'N');
        $this->db->where('is_rejected', 'N');
        if ($_SESSION['role'] != 'super_approver') {
            $this->db->where_in('group_id', $group_id);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }
    function get_total_rejected() {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('is_file_punched', 'Y');
        $this->db->where('is_file_approved', 'N');
        $this->db->where('is_rejected', 'Y');
        $this->db->where_in('group_id', $group_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function rejected_by_me() {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('is_file_approved', 'N');
        $this->db->where('is_rejected', 'Y');
        $this->db->where('approved_by', $user_id);
        $this->db->order_by('scan_id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
}
