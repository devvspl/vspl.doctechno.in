<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Scan_model extends MY_Model {
    protected $year_id; 
    public function __construct() {
        parent::__construct();
        $this->load->database();
         $this->year_id = $this->session->userdata('year_id');
    }
    public function get_doctype_list() {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('dt.file_type,dt.alias')->from('`user_permission` u')->join('permission p', 'p.permission_id = u.permission_id')->join('master_doctype dt', 'dt.alias = p.permission_name')->where(['u.user_id' => $user_id])->order_by('dt.file_type', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function get_my_lastest_scan() {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('master_work_location', "master_work_location.location_id = y{$this->year_id}_scan_file.location_id", 'left');
        $this->db->where('scanned_by', $user_id);
        $this->db->where('is_file_punched', 'N');
        $this->db->where('is_scan_resend', 'N');
        $this->db->order_by('scan_id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function get_my_lastest_temp_scan() {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('master_work_location', "master_work_location.location_id = y{$this->year_id}_scan_file.location_id", 'left');
        $this->db->where('temp_scan_by', $user_id);
        $this->db->where('is_scan_complete', 'N');
        $this->db->where('is_temp_scan_rejected', 'N');
        $this->db->order_by('scan_id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function get_my_scanned_files() {
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('scanned_by', $user_id);
        $this->db->where('is_file_punched', 'Y');
        $this->db->where('scan_date', date('Y-m-d'));
        $this->db->order_by('scan_id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function search_scanned_files($from_date, $to_date) {
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('scanned_by', $user_id);
        $this->db->where('is_file_punched', 'Y');
        $this->db->where('scan_date >=', $from_date);
        $this->db->where('scan_date <=', $to_date);
        $this->db->order_by('scan_id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function get_myscan_list($user_id) {
        
        $this->db->select('s.scan_id,s.document_name ,s.File,s.file_extension,s.file_path,s.Final_Submit,s.File_Punched,s.scan_date');
        $this->db->from("y{$this->year_id}_scan_file s");
        $this->db->where('scanned_by', $user_id);
        $this->db->where('is_file_punched', 'N');
        $this->db->order_by('scan_id', 'desc');
        $result = $this->db->get()->result_array();
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $final[$key] = $value;
                $final[$key]['support_file'] = $this->get_support_file($value['scan_id']);
            }
            return $final;
        } else {
            return [];
        }
    }
    function get_myscan_punched_list($user_id) {
        
        $this->db->select('s.scan_id,s.Doc_Type,s.DocType_Id,s.document_name ,s.File,s.file_extension,s.file_path,s.Final_Submit,s.File_Punched,Punch_Date');
        $this->db->from("y{$this->year_id}_scan_file s");
        $this->db->where('scanned_by', $user_id);
        $this->db->where('is_file_punched', 'Y');
        $this->db->order_by('scan_id', 'desc');
        $result = $this->db->get()->result_array();
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $final[$key] = $value;
                $final[$key]['support_file'] = $this->get_support_file($value['scan_id']);
            }
            return $final;
        } else {
            return [];
        }
    }
    function get_support_file($scan_id) {
        $this->db->select('*');
        $this->db->from('support_file');
        $this->db->where('scan_id', $scan_id);
        $result = $this->db->get()->result_array();
        return $result;
    }
    function get_scan_rejected_count() {
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('scanned_by', $user_id);
        $this->db->where('is_scan_resend', 'Y');
        $query = $this->db->get();
        return $query->num_rows();
    }
    function get_temp_scan_rejected_count() {
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('temp_scan_by', $user_id);
        $this->db->where('is_temp_scan_rejected', 'Y');
        $query = $this->db->get();
        return $query->num_rows();
    }
    function scan_rejected_list() {
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('scanned_by', $user_id);
        $this->db->where('is_scan_resend', 'Y');
        $this->db->order_by('scan_id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function temp_scan_rejected_list() {
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('temp_scan_by', $user_id);
        $this->db->where('is_temp_scan_rejected', 'Y');
        $this->db->order_by('scan_id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function get_temp_y1_scan_file_count() {
        $group_id = $this->session->userdata('group_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('group_id', $group_id);
        $this->db->where('is_temp_scan', 'Y');
        $this->db->where('is_scan_complete', 'N');
        $this->db->where('is_temp_scan_rejected', 'N');
        $query = $this->db->get();
        return $query->num_rows();
    }
    function get_bill_rejected_count() {
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('bill_approval_status', 'R');
        $this->db->where('is_deleted', 'N');
        $this->db->where("((is_temp_scan = 'Y' AND Temp_Scan_By = $user_id) OR (is_temp_scan IS NULL AND scanned_by = $user_id))");
        $query = $this->db->get();
        return $query->num_rows();
    }
    function temp_scan_list_for_naming() {
        $group_id = $this->session->userdata('group_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('master_work_location', "master_work_location.location_id = y{$this->year_id}_scan_file.location_id", 'left');
        $this->db->where('group_id', $group_id);
        $this->db->where('is_temp_scan', 'Y');
        $this->db->where('is_scan_complete', 'N');
        $this->db->where('is_temp_scan_rejected', 'N');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function edit_bill_approver_list() {
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('scanned_by', $user_id);
        $this->db->or_where('temp_scan_by', $user_id);
        $this->db->where('location_id IS NOT NULL');
        $this->db->where('bill_approval_status', 'N');
        $this->db->order_by('scan_id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function bill_rejected_list() {
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('bill_approval_status', 'R');
        $this->db->where('is_deleted', 'N');
        $this->db->where("((is_temp_scan = 'Y' AND Temp_Scan_By = $user_id) OR (is_temp_scan IS NULL AND scanned_by = $user_id))");
        $result = $this->db->get()->result_array();
        return $result;
    }
    function bill_trashed_list() {
        $user_id = $this->session->userdata('user_id');
        
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('bill_approval_status', 'R');
        $this->db->where('is_deleted', 'Y');
        $this->db->where("((is_temp_scan = 'Y' AND Temp_Scan_By = $user_id) OR (is_temp_scan IS NULL AND scanned_by = $user_id))");
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function all_trashed_bill_lists() {
        
        $this->db->select('sf.*, CONCAT(u.first_name, " ", u.last_name) AS deleted_by_name');
        $this->db->from("y{$this->year_id}_scan_file sf");
        $this->db->join('users u', 'sf.Deleted_By = u.user_id', 'inner');
        $this->db->where('sf.is_deleted', 'Y');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_document_type() {
        $this->db->select('type_id, file_type');
        $this->db->where('status', 'A');
        $query = $this->db->get('master_doctype');
        return $query->result_array();
    }
    public function get_firm_list() {
        $this->db->select('master_firm.*');
        $this->db->from('master_firm');
        $this->db->where('master_firm.is_deleted', 'N');
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function get_department_list() {
        $this->db->select('master_department.*');
        $this->db->from('master_department');
        $this->db->where('master_department.is_deleted', 'N');
        $this->db->where('master_department.status', 'A');
        $result = $this->db->get()->result_array();
        return $result;
    }
}
