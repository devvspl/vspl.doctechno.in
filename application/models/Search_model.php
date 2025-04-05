<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Search_model extends MY_Model {
    public function __construct() {
        $this->load->database();
        $this->load->helper('url');
    }
    public function get_search_with_filter_data($company_wise, $vendor_wise, $from_date, $to_date, $work_location, $ledger_wise, $document_wise) {
        $this->db->select('scan_file.Scan_Id,Document_Name,FromName,ToName,BillDate,File_No,Remark,punchfile.Created_Date,Total_Amount,Grand_Total,DocTypeId,Loc_Name,group_name,BookingDate,ServiceNo');
        $this->db->from('scan_file');
        $this->db->join('punchfile', 'punchfile.Scan_Id = scan_file.Scan_Id', 'left');
        $this->db->join('master_group', 'master_group.group_id = scan_file.Group_Id', 'left');
        $this->db->where('File_Approved', 'Y');
        if ($company_wise != '') {
            $this->db->where('punchfile.CompanyID', $company_wise);
        }
        if ($vendor_wise != '') {
            $this->db->where('punchfile.From_ID', $vendor_wise)->or_where('punchfile.To_ID', $vendor_wise);
        }
        if ($document_wise != '') {
            $this->db->where('punchfile.DocTypeId', $document_wise);
        }
        if ($work_location != '') {
            $this->db->where('punchfile.Loc_Name', $work_location);
        }
        if ($ledger_wise != '') {
            $this->db->where('punchfile.Ledger', $ledger_wise);
        }
        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'user') {
            $group_id = $this->session->userdata('group_id');
            $this->db->where('punchfile.Group_Id', $group_id);
        }
        $end_date = '';
        if ($to_date != '') {
            $end_date = $to_date;
        } else {
            $end_date = date('Y-m-d');
        }
        if ($from_date != '') {
            $this->db->where('punchfile.Created_Date >=', $from_date);
            $this->db->where('punchfile.Created_Date <=', $end_date);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_search_with_filter_status_data($company_wise, $vendor_wise, $from_date, $to_date, $work_location, $ledger_wise, $document_wise) {
        $this->db->select('
            pab.first_name as Approve_By,
            bab.first_name as Bill_Approve_By,
            Approve_Date,
            Bill_Approver,
            Bill_Approver_Date,
            Temp_Scan_Date,
            Temp_Scan_By,
            Scan_By,
            Scan_Date,
            Punch_By,
            Punch_Date,
            File_Punched,
            scan_file.Scan_Id,
            Document_Name,
            FromName,
            ToName,
            BillDate,
            File_No,
            Remark,
            punchfile.Created_Date,
            Total_Amount,
            Grand_Total,
            DocTypeId,
            Loc_Name,
            group_name,
            BookingDate,
            ServiceNo
        ');
        $this->db->from('scan_file');
        $this->db->join('punchfile', 'punchfile.Scan_Id = scan_file.Scan_Id', 'left');
        $this->db->join('master_group', 'master_group.group_id = scan_file.Group_Id', 'left');
        $this->db->join('users pab', 'scan_file.Approve_By = pab.user_id', 'left');
        $this->db->join('users bab', 'scan_file.Bill_Approver = bab.user_id', 'left');
        $this->db->where('scan_file.File_Punched', 'Y');
        $this->db->where('scan_file.Is_Deleted', 'N');
        if (!empty($company_wise)) {
            $this->db->where('punchfile.CompanyID', $company_wise);
        }
        if (!empty($vendor_wise)) {
            $this->db->where('punchfile.From_ID', $vendor_wise)->or_where('punchfile.To_ID', $vendor_wise);
        }
        if (!empty($document_wise)) {
            $this->db->where('punchfile.DocTypeId', $document_wise);
        }
        if (!empty($work_location)) {
            $this->db->where('punchfile.Loc_Name', $work_location);
        }
        if (!empty($ledger_wise)) {
            $this->db->where('punchfile.Ledger', $ledger_wise);
        }
        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'user') {
            $group_id = $this->session->userdata('group_id');
            $this->db->where('punchfile.Group_Id', $group_id);
        }
        if (!empty($from_date)) {
            $from_date.= ' 00:00:00';
            $end_date = !empty($to_date) ? $to_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
            $this->db->where('punchfile.Created_Date >=', $from_date);
            $this->db->where('punchfile.Created_Date <=', $end_date);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    public function search_global($search) {
        $this->db->select('scan_file.Scan_Id,Document_Name,FromName,ToName,BillDate,File_No,Remark,punchfile.Created_Date,Total_Amount,Grand_Total,DocTypeId');
        $this->db->from('scan_file');
        $this->db->join('punchfile', 'punchfile.Scan_Id = scan_file.Scan_Id', 'left');
        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'user') {
            $group_id = $this->session->userdata('group_id');
            $this->db->where('punchfile.Group_Id', $group_id);
        }
        $this->db->where('File_Approved', 'Y');
        $this->db->like('Document_Name', $search);
        $this->db->or_like('FromName', $search);
        $this->db->or_like('ToName', $search);
        $this->db->or_like('BillDate', $search);
        $this->db->or_like('File_No', $search);
        $this->db->or_like('Loc_Name', $search);
        $this->db->or_like('Department', $search);
        $this->db->or_like('Ledger', $search);
        $this->db->or_like('Remark', $search);
        $this->db->or_like('punchfile.Created_Date', $search);
        $this->db->or_like('Total_Amount', $search);
        $this->db->or_like('Grand_Total', $search);
        $this->db->or_like('DocTypeId', $search);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_filtered_data($group, $from_date, $to_date, $search_key, $doc_type, $location) {
        $this->db->select('*');
        $this->db->from('scan_file');
        if (!empty($group)) {
            $this->db->where('Group_Id', $group);
        }
        if (!empty($from_date)) {
            $this->db->where('Scan_Date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('Scan_Date <=', $to_date);
        }
        if (!empty($search_key)) {
            $this->db->group_start()->like('Document_Name', $search_key)->or_like('File', $search_key)->group_end();
        }
        if (!empty($doc_type)) {
            $this->db->where('Doc_Type', $doc_type);
        }
        if (!empty($location)) {
            $this->db->where('Location', $location);
        }
        return $this->db->get()->result_array();
    }
}
