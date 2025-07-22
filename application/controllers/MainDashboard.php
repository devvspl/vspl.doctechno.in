<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MainDashboard extends CI_Controller
{
    protected $year_id;

    public function __construct()
    {
        parent::__construct();
        $this->year_id = $this->session->userdata('year_id') ??
            ($this->db->select('id')->from('financial_years')->where('is_current', 1)->get()->row()->id ?? null);

        if (is_null($this->year_id)) {
            show_error('Financial year not configured.', 500, 'Configuration Error');
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $group_id = $this->session->userdata('group_id');

        $table = "y{$this->year_id}_scan_file";
        $data = [];

        $data['total_scans'] = $this->get_count($table, [
            'temp_scan_by' => $user_id,
            'temp_scan_date !=' => '0000-00-00',
            'group_id' => $group_id
        ]);

        $data['final_submitted'] = $this->get_count($table, [
            'temp_scan_by' => $user_id,
            'temp_scan_date !=' => '0000-00-00',
            'is_final_submitted' => 'Y',
            'is_deleted' => 'N',
            'group_id' => $group_id
        ]);

        $data['pending_submission'] = $this->get_count($table, [
            'temp_scan_by' => $user_id,
            'temp_scan_date !=' => '0000-00-00',
            'is_final_submitted' => 'N',
            'is_deleted' => 'N',
            'group_id' => $group_id
        ]);

        $data['rejected_scans'] = $this->get_count($table, [
            'temp_scan_by' => $user_id,
            'temp_scan_date !=' => '0000-00-00',
            'is_temp_scan_rejected' => 'Y',
            'is_deleted' => 'N',
            'group_id' => $group_id
        ]);

        $data['deleted_scans'] = $this->get_count($table, [
            'temp_scan_by' => $user_id,
            'temp_scan_date !=' => '0000-00-00',
            'is_deleted' => 'Y',
            'group_id' => $group_id
        ]);

        $data['classified_by_me'] = $this->get_count($table, [
            'document_name !=' => '',
            'is_classified' => 'Y',
            'classified_by' => $user_id,
            'group_id' => $group_id
        ]);

        $data['scan_rejected_by_me'] = $this->get_count($table, [
            'document_name !=' => '',
            'temp_scan_rejected_by' => $user_id,
            'is_temp_scan_rejected' => 'Y'
        ]);

        $data['classified_rejected'] = $this->get_count($table, [
            'document_name !=' => '',
            'is_classified' => 'Y',
            'classified_by' => $user_id,
            'bill_approval_status' => 'R',
            'group_id' => $group_id
        ]);

        $data['document_verified_count'] = $this->get_count($table, [
            'document_name !=' => '',
            'is_classified' => 'Y',
            'classified_by' => $user_id,
            'is_document_verified' => 'Y',
            'group_id' => $group_id
        ]);

        $data['document_not_verified_count'] = $this->get_count($table, [
            'document_name !=' => '',
            'is_classified' => 'Y',
            'classified_by' => $user_id,
            'is_document_verified' => 'N',
            'group_id' => $group_id
        ]);


        $data['total_count'] = $this->get_count($table, [
            'group_id' => '16',
            'extract_status' => 'Y',
            'is_file_punched' => 'N',
            'bill_approval_status' => 'Y',
            'is_temp_scan_rejected' => 'N',
            'is_deleted' => 'N'
        ]);

        $data['user_count'] = $this->get_count($table, [
            'group_id' => '16',
            'is_file_punched' => 'Y',
            'punched_by' => $user_id
        ]);


        $data['pending_for_classification'] = $this->get_classification_list_count();
        $data['scan_summary'] = $this->get_scan_summary_by_user($this->year_id);

        $data['main'] = 'main_dashboard';
        $this->load->view('layout/template', $data);
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
        return $this->db->get()->result_array();
    }

    private function get_count($table, $conditions)
    {
        $this->db->from($table)->where($conditions);
        return $this->db->count_all_results();
    }

    private function get_classification_list_count($group_id = null, $location_id = null)
    {
        $table = "y{$this->year_id}_scan_file";
        $user_id = $this->session->userdata('user_id');

        if (empty($user_id)) {
            return 0;
        }

        $user_permitted_depts = array_column(
            $this->db->select('permission_value AS department_id')
                ->from('tbl_user_permissions')
                ->where(['user_id' => $user_id, 'permission_type' => 'Department'])
                ->get()->result_array(),
            'department_id'
        );

        $user_permitted_locs = array_column(
            $this->db->select('permission_value AS location_id')
                ->from('tbl_user_permissions')
                ->where(['user_id' => $user_id, 'permission_type' => 'Location'])
                ->get()->result_array(),
            'location_id'
        );

        if (empty($user_permitted_depts) && empty($user_permitted_locs)) {
            return 0;
        }

        $scanner_dept_perms = $this->db->select('permission_value AS department_id, user_id AS temp_scan_by')
            ->from('tbl_user_permissions')
            ->where('permission_type', 'Department')
            ->get()->result_array();

        $scanner_loc_perms = $this->db->select('permission_value AS location_id, user_id AS temp_scan_by')
            ->from('tbl_user_permissions')
            ->where('permission_type', 'Location')
            ->get()->result_array();

        $permitted_temp_scan_by = [];
        foreach ($scanner_dept_perms as $perm) {
            if (in_array($perm['department_id'], $user_permitted_depts)) {
                $permitted_temp_scan_by[$perm['temp_scan_by']] = true;
            }
        }
        foreach ($scanner_loc_perms as $perm) {
            if (in_array($perm['location_id'], $user_permitted_locs)) {
                $permitted_temp_scan_by[$perm['temp_scan_by']] = true;
            }
        }
        $permitted_temp_scan_by = array_keys($permitted_temp_scan_by);

        if (empty($permitted_temp_scan_by)) {
            return 0;
        }

        $queued_scan_ids = array_column(
            $this->db->select('scan_id')
                ->from('tbl_queues')
                ->where('status', 'pending')
                ->get()->result_array(),
            'scan_id'
        );

        $this->db->from("$table s")
            ->join('master_group g', 'g.group_id = s.group_id', 'left')
            ->join('users ba', 'ba.user_id = s.bill_approver_id', 'left')
            ->join('users sb', 'sb.user_id = s.temp_scan_by', 'left')
            ->where([
                's.document_name !=' => '',
                's.extract_status' => 'P',
                's.is_final_submitted' => 'Y',
                's.is_temp_scan_rejected' => 'N',
                's.group_id' => $this->session->userdata('group_id')
            ])
            ->where_in('s.temp_scan_by', $permitted_temp_scan_by);

        if (!empty($queued_scan_ids)) {
            $this->db->where_not_in('s.scan_id', $queued_scan_ids);
        }

        if (!empty($group_id)) {
            $this->db->where('s.group_id', $group_id);
        }
        if (!empty($location_id)) {
            $this->db->where('s.location_id', $location_id);
        }

        return $this->db->count_all_results();
    }
}