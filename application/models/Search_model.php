<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Search_model extends MY_Model {
    public function __construct() {
        $this->load->database();
        $this->load->helper('url');
    }
    public function get_search_with_filter_data($company_wise, $vendor_wise, $from_date, $to_date, $work_location, $ledger_wise, $document_wise) {
        $this->db->select('y{$this->year_id}_scan_file.scan_id,document_name ,FromName,ToName,BillDate,File_No,Remark,punchfile.Created_Date,Total_Amount,Grand_Total,DocTypeId,Loc_Name,group_name,BookingDate,ServiceNo');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('punchfile', 'punchfile.scan_id = y{$this->year_id}_scan_file.scan_id', 'left');
        $this->db->join('master_group', 'master_group.group_id = y{$this->year_id}_scan_file.Group_Id', 'left');
        $this->db->where('is_file_approved', 'Y');
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
            approved_date,
            bill_approver_id,
            bill_approved_date,
            temp_scan_date,
            Temp_Scan_By,
            scanned_by,
            scan_date,
            Punch_By,
            punched_date,
            is_file_punched,
            y{$this->year_id}_scan_file.scan_id,
            document_name ,
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
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('punchfile', 'punchfile.scan_id = y{$this->year_id}_scan_file.scan_id', 'left');
        $this->db->join('master_group', 'master_group.group_id = y{$this->year_id}_scan_file.Group_Id', 'left');
        $this->db->join('users pab', 'y{$this->year_id}_scan_file.Approve_By = pab.user_id', 'left');
        $this->db->join('users bab', 'y{$this->year_id}_scan_file.bill_approver_id = bab.user_id', 'left');
        $this->db->where('y{$this->year_id}_scan_file.is_file_punched', 'Y');
        $this->db->where('y{$this->year_id}_scan_file.is_deleted', 'N');
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
        $this->db->select('y{$this->year_id}_scan_file.scan_id,document_name ,FromName,ToName,BillDate,File_No,Remark,punchfile.Created_Date,Total_Amount,Grand_Total,DocTypeId');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('punchfile', 'punchfile.scan_id = y{$this->year_id}_scan_file.scan_id', 'left');
        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'user') {
            $group_id = $this->session->userdata('group_id');
            $this->db->where('punchfile.Group_Id', $group_id);
        }
        $this->db->where('is_file_approved', 'Y');
        $this->db->like('document_name', $search);
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
        $this->db->from("y{$this->year_id}_scan_file");
        if (!empty($group)) {
            $this->db->where('group_id', $group);
        }
        if (!empty($from_date)) {
            $this->db->where('scan_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('scan_date <=', $to_date);
        }
        if (!empty($search_key)) {
            $this->db->group_start()->like('document_name', $search_key)->or_like('file_name', $search_key)->group_end();
        }
        if (!empty($doc_type)) {
            $this->db->where('doc_type', $doc_type);
        }
        if (!empty($location)) {
            $this->db->where('location_id', $location);
        }
        return $this->db->get()->result_array();
    }
}
