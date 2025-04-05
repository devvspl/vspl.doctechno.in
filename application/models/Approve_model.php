<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Approve_model extends MY_Model {
    function pending_for_approval() {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('File_Punched', 'Y');
        $this->db->where('File_Approved', 'N');
        $this->db->where('Is_Rejected', 'N');
        if ($_SESSION['role'] != 'super_approver') {
            $this->db->where_in('Group_Id', $group_id);
        }
        $this->db->order_by('Scan_Id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_my_approved_file($limit, $start, $group = '', $Doctype = '', $search = '', $from_date = '', $to_date = '') {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->join('master_group', 'master_group.group_id = scan_file.Group_Id', 'left');
        $this->db->where('File_Approved', 'Y');
        $this->db->where('Approve_By', $user_id);
        if ($group !== '') {
            $this->db->where('scan_file.Group_Id', $group);
        }
		if ($Doctype !== '') {
            $this->db->where('scan_file.DocType_Id', $Doctype);
        }
        if ($from_date !== '' && $to_date !== '') {
            $this->db->where('Approve_Date >=', $from_date);
            $this->db->where('Approve_Date <=', $to_date);
        }
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('Document_Name', $search);
            $this->db->or_like('Approve_By', $search);
            $this->db->or_like('Approve_Date', $search);
            $this->db->or_like('File', $search);
            $this->db->group_end();
        }
        $this->db->order_by('Approve_Date', 'desc');
        $this->db->order_by('Scan_Id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function count_filtered_records($search = '', $from_date = '', $to_date = '', $group = '', $Doctype = '') {
        $user_id = $this->session->userdata('user_id');
        $this->db->from('scan_file');
        $this->db->join('master_group', 'master_group.group_id = scan_file.Group_Id', 'left');
        $this->db->where('File_Approved', 'Y');
        $this->db->where('Approve_By', $user_id);
        if ($group !== '') {
            $this->db->where('scan_file.Group_Id', $group);
        }
		if ($Doctype !== '') {
            $this->db->where('scan_file.DocType_Id', $Doctype);
        }
        if ($from_date !== '' && $to_date !== '') {
            $this->db->where('Approve_Date >=', $from_date);
            $this->db->where('Approve_Date <=', $to_date);
        }
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('Document_Name', $search);
            $this->db->or_like('Approve_By', $search);
            $this->db->or_like('Approve_Date', $search);
            $this->db->or_like('File', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }
    function get_my_approved_file_all() {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->join('master_group', 'master_group.group_id = scan_file.Group_Id', 'left');
        $this->db->where('File_Approved', 'Y');
        $this->db->where('Approve_By', $user_id);
        $this->db->order_by('Scan_Id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
    function search_approved_file($from_date, $to_date) {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->join('master_group', 'master_group.group_id = scan_file.Group_Id', 'left');
        $this->db->where('File_Punched', 'Y');
        $this->db->where('File_Approved', 'Y');
        $this->db->where_in('Approve_By', $user_id);
        $this->db->where('date(Approve_Date) >=', $from_date);
        $this->db->where('date(Approve_Date) <=', $to_date);
        $this->db->order_by('Scan_Id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_total_approved_by_me() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Approve_By', $user_id);
        $this->db->where('File_Approved', 'Y');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    function get_rejected_by_me() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Approve_By', $user_id);
        $this->db->where('File_Approved', 'N');
        $this->db->where('Is_Rejected', 'Y');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    function get_pending_for_approval_count() {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('File_Punched', 'Y');
        $this->db->where('File_Approved', 'N');
        $this->db->where('Is_Rejected', 'N');
        if ($_SESSION['role'] != 'super_approver') {
            $this->db->where_in('Group_Id', $group_id);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }
    function get_total_rejected() {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('File_Punched', 'Y');
        $this->db->where('File_Approved', 'N');
        $this->db->where('Is_Rejected', 'Y');
        $this->db->where_in('Group_Id', $group_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function rejected_by_me() {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from('scan_file');
        $this->db->where('File_Approved', 'N');
        $this->db->where('Is_Rejected', 'Y');
        $this->db->where('Approve_By', $user_id);
        $this->db->order_by('Scan_Id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }
}
