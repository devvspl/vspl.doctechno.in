<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Scan_model extends MY_Model
{
    protected $year_id;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->year_id = $this->session->userdata('year_id') ?? ($this->db->select('id')->from('financial_years')->where('is_current', 1)->get()->row()->id ?? null);
    }
    public function get_doctype_list()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('dt.file_type,dt.alias')->from('`user_permission` u')->join('permission p', 'p.permission_id = u.permission_id')->join('master_doctype dt', 'dt.alias = p.permission_name')->where(['u.user_id' => $user_id])->order_by('dt.file_type', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function get_my_lastest_scan()
    {
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
    function get_my_lastest_temp_scan($status = null, $document_name = null, $from_date = null, $to_date = null, $page = 1, $per_page = 10)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('temp_scan_by', $user_id);
        $this->db->where('temp_scan_date !=', '0000-00-00');
        if ($status === 'submitted') {
            $this->db->where(['is_final_submitted' => 'Y', 'is_deleted' => 'N']);
        } elseif ($status === 'pending') {
            $this->db->where(['is_final_submitted' => 'N', 'is_deleted' => 'N']);
        } elseif ($status === 'rejected') {
            $this->db->where(['is_temp_scan_rejected' => 'Y', 'is_deleted' => 'N']);
        } elseif ($status === 'deleted') {
            $this->db->where('is_deleted', 'Y');
        }
        if ($document_name) {
            $this->db->like('document_name', $document_name);
        }
        if ($from_date) {
            $this->db->where('DATE(temp_scan_date) >=', date('Y-m-d', strtotime($from_date)));
        }
        if ($to_date) {
            $this->db->where('DATE(temp_scan_date) <=', date('Y-m-d', strtotime($to_date)));
        }
        $this->db->order_by('scan_id', 'desc');
        $this->db->limit($per_page, ($page - 1) * $per_page);
        return $this->db->get()->result_array();
    }
    function get_my_lastest_temp_scan_count($status = null, $document_name = null, $from_date = null, $to_date = null)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('temp_scan_by', $user_id);
        $this->db->where('temp_scan_date !=', '0000-00-00');
        if ($status === 'submitted') {
            $this->db->where(['is_final_submitted' => 'Y', 'is_deleted' => 'N']);
        } elseif ($status === 'pending') {
            $this->db->where(['is_final_submitted' => 'N', 'is_deleted' => 'N']);
        } elseif ($status === 'rejected') {
            $this->db->where(['is_temp_scan_rejected' => 'Y', 'is_deleted' => 'N']);
        } elseif ($status === 'deleted') {
            $this->db->where('is_deleted', 'Y');
        }
        if ($document_name) {
            $this->db->like('document_name', $document_name);
        }
        if ($from_date) {
            $this->db->where('DATE(temp_scan_date) >=', date('Y-m-d', strtotime($from_date)));
        }
        if ($to_date) {
            $this->db->where('DATE(temp_scan_date) <=', date('Y-m-d', strtotime($to_date)));
        }
        return $this->db->count_all_results();
    }
    function get_my_scanned_files()
    {
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
    function search_scanned_files($from_date, $to_date)
    {
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
    function get_myscan_list($user_id)
    {
        $this->db->select('s.scan_id,s.document_name ,s.File,s.file_extension,s.file_path,s.is_final_submitted,s.is_file_punched,s.scan_date');
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
    function get_myscan_punched_list($user_id)
    {
        $this->db->select('s.scan_id,s.Doc_Type,s.doc_type_id,s.document_name ,s.File,s.file_extension,s.file_path,s.is_final_submitted,s.is_file_punched,punched_date');
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
    function get_support_file($scan_id)
    {
        $this->db->select('*');
        $this->db->from('support_file');
        $this->db->where('scan_id', $scan_id);
        $result = $this->db->get()->result_array();
        return $result;
    }
    function get_scan_rejected_count()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('scanned_by', $user_id);
        $this->db->where('is_scan_resend', 'Y');
        $query = $this->db->get();
        return $query->num_rows();
    }
    function get_temp_scan_rejected_count()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('temp_scan_by', $user_id);
        $this->db->where('is_temp_scan_rejected', 'Y');
        $query = $this->db->get();
        return $query->num_rows();
    }
    function scan_rejected_list()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('scanned_by', $user_id);
        $this->db->where('is_scan_resend', 'Y');
        $this->db->order_by('scan_id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function temp_scan_rejected_list()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('temp_scan_by', $user_id);
        $this->db->where('is_temp_scan_rejected', 'Y');
        $this->db->order_by('scan_id', 'desc');
        $result = $this->db->get()->result_array();
        return $result;
    }
    function get_temp_y1_scan_file_count()
    {
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
    function get_bill_rejected_count()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('bill_approval_status', 'R');
        $this->db->where('is_deleted', 'N');
        $this->db->where("((is_temp_scan = 'Y' AND Temp_Scan_By = $user_id) OR (is_temp_scan IS NULL AND scanned_by = $user_id))");
        $query = $this->db->get();
        return $query->num_rows();
    }
    function temp_scan_list_for_naming()
    {
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
    function edit_bill_approver_list()
    {
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
    function bill_rejected_list()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('bill_approval_status', 'R');
        $this->db->where('is_deleted', 'N');
        $this->db->where("((is_temp_scan = 'Y' AND Temp_Scan_By = $user_id) OR (is_temp_scan IS NULL AND scanned_by = $user_id))");
        $result = $this->db->get()->result_array();
        return $result;
    }
    function bill_trashed_list()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('bill_approval_status', 'R');
        $this->db->where('is_deleted', 'Y');
        $this->db->where("((is_temp_scan = 'Y' AND Temp_Scan_By = $user_id) OR (is_temp_scan IS NULL AND scanned_by = $user_id))");
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function all_trashed_bill_lists()
    {
        $this->db->select('sf.*, CONCAT(u.first_name, " ", u.last_name) AS deleted_by_name');
        $this->db->from("y{$this->year_id}_scan_file sf");
        $this->db->join('users u', 'sf.Deleted_By = u.user_id', 'inner');
        $this->db->where('sf.is_deleted', 'Y');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_document_type()
    {
        $this->db->select('type_id, file_type');
        $this->db->where('status', 'A');
        $query = $this->db->get('master_doctype');
        return $query->result_array();
    }
    public function get_firm_list()
    {
        $this->db->select('master_firm.*');
        $this->db->from('master_firm');
        $this->db->where('master_firm.is_deleted', 'N');
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function get_department_list()
    {
        $this->db->select('master_department.*');
        $this->db->from('master_department');
        $this->db->where('master_department.is_deleted', 'N');
        $this->db->where('master_department.status', 'A');
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function get_user_dashboard_counts($user_id, $group_id, $year_id)
    {
        $table = "y{$year_id}_scan_file";
        return ['total_scans' => $this->db->where('temp_scan_by', $user_id)->where('temp_scan_date !=', '0000-00-00')->where('group_id', $group_id)->count_all_results($table), 'final_submitted' => $this->db->where('temp_scan_by', $user_id)->where('temp_scan_date !=', '0000-00-00')->where('is_final_submitted', 'Y')->where('group_id', $group_id)->count_all_results($table), 'pending_submission' => $this->db->where('temp_scan_by', $user_id)->where('temp_scan_date !=', '0000-00-00')->where('is_final_submitted', 'N')->where('group_id', $group_id)->count_all_results($table), 'rejected_scans' => $this->db->where('temp_scan_by', $user_id)->where('temp_scan_date !=', '0000-00-00')->where('is_temp_scan_rejected', 'Y')->where('group_id', $group_id)->count_all_results($table), 'deleted_scans' => $this->db->where('temp_scan_by', $user_id)->where('temp_scan_date !=', '0000-00-00')->where('is_deleted', 'Y')->where('group_id', $group_id)->count_all_results($table),];
    }
    public function get_scan_admin_dashboard_counts($user_id, $group_id, $year_id)
    {
        $table = "y{$year_id}_scan_file";
        $queuedScanIds = array_column($this->db->select('scan_id')->where('status', 'pending')->get('tbl_queues')->result_array(), 'scan_id');
        $this->db->from($table);
        $this->db->where('document_name !=', '');
        $this->db->where('is_classified', 'Y');
        $this->db->where('classified_by', $user_id);
        $this->db->where('group_id', $group_id);
        $classified = $this->db->count_all_results();
        $this->db->from($table);
        $this->db->where("document_name !=", "");
        $this->db->where("temp_scan_rejected_by", $this->session->userdata('user_id'));
        $this->db->where("is_temp_scan_rejected", "Y");
        $rejected = $this->db->count_all_results();
        $this->db->from($table);
        $this->db->where('document_name !=', '');
        $this->db->where('is_classified', 'Y');
        $this->db->where('classified_by', $user_id);
        $this->db->where('bill_approval_status', 'R');
        $this->db->where('group_id', $group_id);
        $classified_rejected = $this->db->count_all_results();
        $this->db->from($table);
        $this->db->where('document_name !=', '');
        $this->db->where('is_classified', 'Y');
        $this->db->where('classified_by', $user_id);
        $this->db->where('is_document_verified', 'Y');
        $this->db->where('group_id', $group_id);
        $document_verified_count = $this->db->count_all_results();
        $this->db->from($table);
        $this->db->where('document_name !=', '');
        $this->db->where('is_classified', 'Y');
        $this->db->where('classified_by', $user_id);
        $this->db->where('is_document_verified', 'N');
        $this->db->where('group_id', $group_id);
        $document_not_verified_count = $this->db->count_all_results();
        return ['classified_by_me' => $classified, 'scan_rejected_by_me' => $rejected, 'classified_rejected' => $classified_rejected, 'document_verified_count' => $document_verified_count, 'document_not_verified_count' => $document_not_verified_count, 'pending_for_classification' => $this->getClassificationListCount(),];
    }
    public function getClassificationListCount($group_id = null, $location_id = null)
    {
        $table = "y{$this->year_id}_scan_file";
        $user_permitted_depts = array_column($this->db->select('permission_value as department_id')->from('tbl_user_permissions')->where('user_id', $this->session->userdata('user_id'))->where('permission_type', 'Department')->get()->result_array(), 'department_id');
        $user_permitted_locs = array_column($this->db->select('permission_value as location_id')->from('tbl_user_permissions')->where('user_id', $this->session->userdata('user_id'))->where('permission_type', 'Location')->get()->result_array(), 'location_id');
        $scanner_dept_perms = $this->db->select('permission_value AS department_id, user_id AS temp_scan_by')->from('tbl_user_permissions')->where('permission_type', 'Department')->get()->result_array();
        $scanner_loc_perms = $this->db->select('permission_value AS location_id, user_id AS temp_scan_by')->from('tbl_user_permissions')->where('permission_type', 'Location')->get()->result_array();
        $permitted_temp_scan_by = [];
        foreach ($scanner_dept_perms as $scanner_dept) {
            if (in_array($scanner_dept['department_id'], $user_permitted_depts)) {
                $permitted_temp_scan_by[$scanner_dept['temp_scan_by']] = true;
            }
        }
        foreach ($scanner_loc_perms as $scanner_loc) {
            if (in_array($scanner_loc['location_id'], $user_permitted_locs)) {
                $permitted_temp_scan_by[$scanner_loc['temp_scan_by']] = true;
            }
        }
        $permitted_temp_scan_by = array_keys($permitted_temp_scan_by);
        $queuedScanIds = array_column($this->db->select('scan_id')->where('status', 'pending')->get('tbl_queues')->result_array(), 'scan_id');
        $this->db->from($table . " s");
        $this->db->join("master_group g", "g.group_id = s.group_id", "left");
        $this->db->join("users ba", "ba.user_id = s.bill_approver_id", "left");
        $this->db->join("users sb", "sb.user_id = s.Temp_Scan_By", "left");
        $conditions = ["s.document_name !=" => "", "s.extract_status" => "P", "s.is_final_submitted" => "Y", "s.is_temp_scan_rejected" => "N", "s.group_id" => '16',];
        $this->db->where($conditions);
        if (!empty($permitted_temp_scan_by)) {
            $this->db->where_in("s.Temp_Scan_By", $permitted_temp_scan_by);
        } else {
            return 0;
        }
        if (!empty($queuedScanIds)) {
            $this->db->where_not_in("s.scan_id", $queuedScanIds);
        }
        if (!empty($group_id)) {
            $this->db->where("s.group_id", $group_id);
        }
        if (!empty($location_id)) {
            $this->db->where("s.location_id", $location_id);
        }
        return $this->db->count_all_results();
    }
    public function get_scan_summary_by_user($year_id)
    {
        $table = "y{$year_id}_scan_file";
        $user_permitted_depts = array_column($this->db->select('permission_value as department_id')->from('tbl_user_permissions')->where('user_id', $this->session->userdata('user_id'))->where('permission_type', 'Department')->get()->result_array(), 'department_id');
        $user_permitted_locs = array_column($this->db->select('permission_value as location_id')->from('tbl_user_permissions')->where('user_id', $this->session->userdata('user_id'))->where('permission_type', 'Location')->get()->result_array(), 'location_id');
        $scanner_dept_perms = $this->db->select('permission_value AS department_id, user_id AS temp_scan_by')->from('tbl_user_permissions')->where('permission_type', 'Department')->get()->result_array();
        $scanner_loc_perms = $this->db->select('permission_value AS location_id, user_id AS temp_scan_by')->from('tbl_user_permissions')->where('permission_type', 'Location')->get()->result_array();
        $permitted_temp_scan_by = [];
        foreach ($scanner_dept_perms as $scanner_dept) {
            if (in_array($scanner_dept['department_id'], $user_permitted_depts)) {
                $permitted_temp_scan_by[$scanner_dept['temp_scan_by']] = true;
            }
        }
        foreach ($scanner_loc_perms as $scanner_loc) {
            if (in_array($scanner_loc['location_id'], $user_permitted_locs)) {
                $permitted_temp_scan_by[$scanner_loc['temp_scan_by']] = true;
            }
        }
        $permitted_temp_scan_by = array_keys($permitted_temp_scan_by);
        if (empty($permitted_temp_scan_by)) {
            return [];
        }
        $this->db->select("
        CONCAT(users.first_name, ' ', users.last_name) AS scanned_by,
        COUNT(*) AS total_scan,
        SUM(CASE WHEN {$table}.is_final_submitted = 'Y' THEN 1 ELSE 0 END) AS final_submitted_count,
        SUM(CASE WHEN {$table}.is_final_submitted = 'N' THEN 1 ELSE 0 END) AS not_final_submitted_count");
        $this->db->from($table);
        $this->db->join("users", "users.user_id = {$table}.temp_scan_by", "left");
        $this->db->where_in("{$table}.temp_scan_by", $permitted_temp_scan_by);
        $this->db->group_by("{$table}.temp_scan_by");
        $result = $this->db->get()->result_array();
        return $result;
    }
    public function getBillApprovalSummaryByDepartment($approver_id)
    {
        $sql = "
        SELECT 
            cd.department_name,
            SUM(CASE WHEN s.bill_approval_status = 'N' THEN 1 ELSE 0 END) AS total_pending,
            SUM(CASE WHEN s.bill_approval_status = 'Y' THEN 1 ELSE 0 END) AS total_approved,
            SUM(CASE WHEN s.bill_approval_status = 'R' THEN 1 ELSE 0 END) AS total_rejected,
            COUNT(*) AS total_count
        FROM y{$this->year_id}_scan_file s
        LEFT JOIN core_department cd ON s.department_id = cd.api_id
        WHERE s.bill_approver_id = ?
        AND s.bill_approval_status IN ('Y', 'R', 'N')
        GROUP BY s.department_id
    ";
        return $this->db->query($sql, [$approver_id])->result();
    }
    public function get_punch_counts($year_id, $user_id)
    {
        $table = "y{$year_id}_scan_file";
        $sql1 = "
        SELECT COUNT(*) as total_count
        FROM {$table}
        WHERE group_id IN ('16')
          AND extract_status = 'Y'
          AND is_file_punched = 'N'
          AND bill_approval_status = 'Y'
          AND is_temp_scan_rejected = 'N'
          AND is_deleted = 'N'";
        $total_result = $this->db->query($sql1)->row_array();
        $sql2 = "
        SELECT COUNT(*) as user_count
        FROM {$table}
        WHERE group_id IN ('16')
          AND is_file_punched = 'Y'
          AND punched_by = ?";
        $user_result = $this->db->query($sql2, [$user_id])->row_array();
        return ['total_count' => (int) ($total_result['total_count'] ?? 0), 'user_count' => (int) ($user_result['user_count'] ?? 0),];
    }
}
