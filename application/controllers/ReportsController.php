<?php
defined("BASEPATH") or exit("No direct script access allowed");
class ReportsController extends CI_Controller
{
    protected $year_id;
    function __construct()
    {
        parent::__construct();
        $this->year_id = $this->session->userdata('year_id') ?? ($this->db->select('id')->from('financial_years')->where('is_current', 1)->get()->row()->id ?? null);
    }
    public function scanned_files()
    {
        if (!getRoutePermission("scanned_files")) {
            show_error('You do not have permission to access this page.', 403);
        }
        if ($this->input->is_ajax_request()) {
            $user_id = $this->input->get('temp_scan_by', TRUE);
            $from_date = $this->input->get('from_date', TRUE);
            $to_date = $this->input->get('to_date', TRUE);
            $draw = intval($this->input->get('draw', TRUE));
            $start = intval($this->input->get('start', TRUE));
            $length = intval($this->input->get('length', TRUE));
            $search_value = $this->input->get('search')['value'] ?? '';
            $order = $this->input->get('order')[0] ?? ['column' => 0, 'dir' => 'asc'];
            $column_index = $this->input->get('columns')[$order['column']]['data'] ?? 'scan_id';
            $order_dir = $order['dir'] ?? 'asc';
            $columns = ['scan_id' => 'y.scan_id', 'file_name' => 'y.file_name', 'document_name' => 'y.document_name', 'file_path' => 'y.file_path', 'temp_scan_by' => 'y.temp_scan_by', 'temp_scan_date' => 'y.temp_scan_date', 'full_name' => 'full_name'];
            $order_column = $columns[$column_index] ?? 'y.scan_id';
            $this->db->select("y.scan_id, y.file_name, y.document_name, y.file_path, y.temp_scan_by, y.temp_scan_date, CONCAT(u.first_name, ' ', u.last_name) AS full_name");
            $this->db->from("y{$this->year_id}_scan_file y");
            $this->db->join('users u', 'u.user_id = y.temp_scan_by', 'left');
            $this->db->where('y.is_final_submitted', 'Y');
            $this->db->where('y.is_deleted', 'N');
            $this->db->where('y.temp_scan_date !=', '0000-00-00');
            if ($user_id) {
                $this->db->where('y.temp_scan_by', $user_id);
            }
            if ($from_date) {
                $this->db->where('DATE(y.temp_scan_date) >=', date('Y-m-d', strtotime($from_date)));
            }
            if ($to_date) {
                $this->db->where('DATE(y.temp_scan_date) <=', date('Y-m-d', strtotime($to_date)));
            }
            if (!empty($search_value)) {
                $this->db->group_start();
                $this->db->like('y.file_name', $search_value);
                $this->db->or_like('y.document_name', $search_value);
                $this->db->or_like("CONCAT(u.first_name, ' ', u.last_name)", $search_value);
                $this->db->group_end();
            }
            $filtered_query = clone $this->db;
            $filtered_count = $filtered_query->count_all_results();
            $this->db->reset_query();
            $this->db->select("y.scan_id, y.file_name, y.document_name, y.file_path, y.temp_scan_by, y.temp_scan_date, CONCAT(u.first_name, ' ', u.last_name) AS full_name");
            $this->db->from("y{$this->year_id}_scan_file y");
            $this->db->join('users u', 'u.user_id = y.temp_scan_by', 'left');
            $this->db->where('y.is_final_submitted', 'Y');
            $this->db->where('y.is_deleted', 'N');
            $this->db->where('y.temp_scan_date !=', '0000-00-00');
            if ($user_id) {
                $this->db->where('y.temp_scan_by', $user_id);
            }
            if ($from_date) {
                $this->db->where('DATE(y.temp_scan_date) >=', date('Y-m-d', strtotime($from_date)));
            }
            if ($to_date) {
                $this->db->where('DATE(y.temp_scan_date) <=', date('Y-m-d', strtotime($to_date)));
            }
            if (!empty($search_value)) {
                $this->db->group_start();
                $this->db->like('y.file_name', $search_value);
                $this->db->or_like('y.document_name', $search_value);
                $this->db->or_like("CONCAT(u.first_name, ' ', u.last_name)", $search_value);
                $this->db->group_end();
            }
            $this->db->order_by($order_column, $order_dir);
            if ($length != -1) {
                $this->db->limit($length, $start);
            }
            $query = $this->db->get();
            $data = $query->result_array();
            $this->db->select('COUNT(*) as total');
            $this->db->from("y{$this->year_id}_scan_file y");
            $this->db->where('y.is_final_submitted', 'Y');
            $this->db->where('y.is_deleted', 'N');
            $this->db->where('y.temp_scan_date !=', '0000-00-00');
            $total_records = $this->db->get()->row()->total;
            $output = ["draw" => $draw, "recordsTotal" => $total_records, "recordsFiltered" => $filtered_count, "data" => $data];
            echo json_encode($output);
            exit;
        } else {
            $join = ['table' => 'users', 'condition' => 'users.user_id = tbl_user_permissions.user_id', 'type' => 'left'];
            $where = ['tbl_user_permissions.permission_value' => 9, 'tbl_user_permissions.permission_type' => 'Permission', 'users.role_id !=' => 0];
            $select = "tbl_user_permissions.user_id, CONCAT(users.first_name, ' ', users.last_name) AS full_name";
            $data["scanner_users"] = $this->BaseModel->getJoinData('tbl_user_permissions', $join, $where, $select)->result_array();
            $data["main"] = "reports/scanned_files";
            $this->load->view("layout/template", $data);
        }
    }
    public function export_scanned_files()
    {
        $user_id = $this->input->get('temp_scan_by', TRUE);
        $from_date = $this->input->get('from_date', TRUE);
        $to_date = $this->input->get('to_date', TRUE);
        $search_value = $this->input->get('search', TRUE);

        $this->load->library('csvexport');

        $this->db->select("y.scan_id, y.file_name, y.document_name, y.file_path, CONCAT(u.first_name, ' ', u.last_name) AS full_name, y.temp_scan_date");
        $this->db->from("y{$this->year_id}_scan_file y");
        $this->db->join('users u', 'u.user_id = y.temp_scan_by', 'left');
        $this->db->where(['y.is_final_submitted' => 'Y', 'y.is_deleted' => 'N']);
        $this->db->where('y.temp_scan_date !=', '0000-00-00');

        if ($user_id) {
            $this->db->where('y.temp_scan_by', $user_id);
        }
        if ($from_date) {
            $this->db->where('DATE(y.temp_scan_date) >=', date('Y-m-d', strtotime($from_date)));
        }
        if ($to_date) {
            $this->db->where('DATE(y.temp_scan_date) <=', date('Y-m-d', strtotime($to_date)));
        }
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('y.file_name', $search_value);
            $this->db->or_like('y.document_name', $search_value);
            $this->db->or_like("CONCAT(u.first_name, ' ', u.last_name)", $search_value);
            $this->db->group_end();
        }

        $query = $this->db->get();
        $data = $query->result_array();

        $headers = ['Scan ID', 'File Name', 'Document Name', 'File Path', 'Scanned By', 'Scan Date'];
        $csv_data = [];

        foreach ($data as $row) {
            $csv_data[] = [
                $row['scan_id'],
                $row['file_name'],
                $row['document_name'],
                $row['file_path'],
                $row['full_name'],
                $row['temp_scan_date']
            ];
        }

        $this->csvexport->export("Scanned_Files_" . date("Ymd") . ".csv", $headers, $csv_data);
    }

}
