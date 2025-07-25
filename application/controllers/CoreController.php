<?php
defined("BASEPATH") or exit("No direct script access allowed");
class CoreController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->helper("file");
    }
    private function logged_in()
    {
        if (!$this->session->userdata("authenticated")) {
            redirect("/");
        }
    }
    private function make_api_request($url, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    public function sync_apis()
    {
        $query = $this->db->get("core_api_list");
        $api_list = $query->result_array();
        if (empty($api_list)) {
            return $this->output->set_content_type("application/json")->set_output(json_encode(['status' => 'error', 'message' => 'No APIs found to sync.']));
        }
        $synced_data = [];
        foreach ($api_list as $api) {
            $result = $this->sync_api_data($api['api_end_point'], "core_" . $api['table_name']);
            if ($result && isset($result['status']) && $result['status'] === 'success') {
                $synced_data[] = $result['message'];
            }
        }
        if (empty($synced_data)) {
            return $this->output->set_content_type("application/json")->set_output(json_encode(['status' => 'error', 'message' => 'No data was synced.',]));
        }
        return $this->output->set_content_type("application/json")->set_output(json_encode(['status' => 'success', 'message' => 'All APIs synced successfully.', 'synced_data' => $synced_data]));
    }
    public function core_apis()
    {
        if (!$this->db->table_exists("core_api_list")) {
            $this->data["main"] = "core_apis";
            $this->data["api_list"] = [];
            $this->load->view("layout/template", $this->data);
            return;
        }
        $query = $this->db->get("core_api_list");
        $data["main"] = "super_admin/core_apis";
        $data["api_list"] = $query->result_array();
        $this->load->view("layout/template", $data);
    }
    public function fetch_apis()
    {
        $api_url = "https://core.vnrin.in/api/project/apis";
        $headers = ["api-key: kKvc4n6jT2aaD2E3Ptvj6CxmDnRJdY6B", "Content-Type: application/json"];
        $response = $this->make_api_request($api_url, $headers);
        $api_data = json_decode($response, true);
        if (!empty($api_data['api_list'])) {
            foreach ($api_data['api_list'] as &$api) {
                $api['api_id'] = $api['id'];
                unset($api['id']);
            }
            $this->sync_api_list($api_data['api_list']);
            echo json_encode(['status' => 'success', 'message' => 'API list updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No API data found']);
        }
    }
    private function sync_api_list($api_list)
    {
        $columns = array_keys($api_list[0]);
        $this->createOrUpdateTable('core_api_list', $columns);
        foreach ($api_list as $api) {
            $this->db->where('api_id', $api['api_id']);
            $query = $this->db->get('core_api_list');
            if ($query->num_rows() > 0) {
                $this->db->where('api_id', $api['api_id']);
                $this->db->update('core_api_list', $api);
            } else {
                $this->db->insert('core_api_list', $api);
            }
        }
    }
    public function sync_single_api()
    {
        $input_data = json_decode(file_get_contents("php://input"), true);
        $api_end_point = $input_data["api_end_point"] ?? null;
        $table_name = !empty($input_data["table_name"]) ? "core_" . $input_data["table_name"] : null;
        $params = $input_data["params"] ?? [];
        if (!$api_end_point || !$table_name) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid API data.']);
            return;
        }
        if (!empty($params) && is_array($params)) {
            $api_end_point .= '?' . http_build_query($params);
        }
        $this->sync_api_data($api_end_point, $table_name);
    }
    private function sync_api_data($api_end_point, $table_name)
    {
        $api_url = "https://core.vnrin.in/api/" . $api_end_point;
        $headers = ["api-key: kKvc4n6jT2aaD2E3Ptvj6CxmDnRJdY6B", "Content-Type: application/json"];
        $response = $this->make_api_request($api_url, $headers);
        if (!$response) {
            echo json_encode(['status' => 'error', 'message' => "API request failed for $api_end_point."]);
            return;
        }
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['status' => 'error', 'message' => "Invalid JSON response for $api_end_point: " . json_last_error_msg()]);
            return;
        }
        if (!empty($data["list"]) && is_array($data["list"])) {
            $api_ids = [];
            $processed_data = [];
            foreach ($data["list"] as $row) {
                $new_row = $row;
                if (!isset($new_row['api_id'])) {
                    $new_row['api_id'] = $new_row['id'];
                    unset($new_row['id']);
                }
                $processed_data[$new_row['api_id']] = $new_row;
                $api_ids[$new_row['api_id']] = $new_row['api_id'];
            }
            $processed_data = array_values($processed_data);
            $api_ids = array_values($api_ids);
            $columns = array_keys($processed_data[0]);
            $this->createOrUpdateTable($table_name, $columns);
            foreach ($processed_data as $row) {
                $this->db->where('api_id', $row['api_id']);
                $this->db->delete($table_name);
                if ($this->db->error()['code'] !== 0) {
                    log_message('error', "Delete failed for api_id {$row['api_id']} in $table_name: " . json_encode($this->db->error()));
                }
                $this->db->insert($table_name, $row);
                if ($this->db->affected_rows() === 0) {
                    log_message('error', "Insert failed for api_id {$row['api_id']} in $table_name: " . json_encode($this->db->error()));
                }
            }
            if (!empty($api_ids)) {
                $this->db->where_not_in('api_id', $api_ids);
                $this->db->delete($table_name);
                if ($this->db->error()['code'] !== 0) {
                    log_message('error', "Delete failed for $table_name: " . json_encode($this->db->error()));
                }
            }
            echo json_encode(['status' => 'success', 'message' => "Data synced successfully for $api_end_point."]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "No data found for $api_end_point."]);
        }
    }
    private function createOrUpdateTable($table_name, $columns)
    {
        if ($this->db->table_exists($table_name)) {
            $existing_columns = $this->db->list_fields($table_name);
            $new_columns = array_diff($columns, $existing_columns);
            foreach ($new_columns as $column) {
                if ($column !== 'id') {
                    $this->db->query("ALTER TABLE `$table_name` ADD `$column` TEXT NULL");
                    if ($this->db->error()['code'] !== 0) {
                        log_message('error', "Failed to add column $column to $table_name: " . json_encode($this->db->error()));
                    }
                }
            }
            if (!in_array('api_id', $existing_columns)) {
                $this->db->query("ALTER TABLE `$table_name` ADD `api_id` INT NOT NULL");
                if ($this->db->error()['code'] !== 0) {
                    log_message('error', "Failed to add api_id column to $table_name: " . json_encode($this->db->error()));
                }
            }
        } else {
            $query = "CREATE TABLE `$table_name` (
            id INT AUTO_INCREMENT PRIMARY KEY, 
            api_id INT NOT NULL, ";
            foreach ($columns as $column) {
                if ($column !== 'id' && $column !== 'api_id') {
                    $query .= "`$column` TEXT NULL, ";
                }
            }
            $query = rtrim($query, ', ') . ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $this->db->query($query);
            if ($this->db->error()['code'] !== 0) {
                log_message('error', "Failed to create table $table_name: " . json_encode($this->db->error()));
            }
        }
    }
    public function get_api_data()
    {
        $table_name = $this->input->post("table_name");
        if (!$table_name) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid table name.']);
            return;
        }
        $table_name = "core_" . $table_name;
        if (!$this->db->table_exists($table_name)) {
            echo json_encode(['status' => 'error', 'message' => 'Table not found.']);
            return;
        }
        $query = $this->db->get($table_name);
        echo json_encode(['status' => 'success', 'data' => $query->result_array()]);
    }
    public function empty_table()
    {
        $table_name = "core_" . $this->input->post('table_name');
        if ($this->db->table_exists($table_name)) {
            $this->db->empty_table($table_name);
            echo json_encode(['status' => 'success', 'message' => 'Table data emptied successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Table not found.']);
        }
    }
    public function drop_table()
    {
        $table_name = "core_" . $this->input->post('table_name');
        if ($this->db->table_exists($table_name)) {
            $this->db->query("DROP TABLE `$table_name`");
            $this->db->where('table_name', $table_name);
            $this->db->delete('core_api_list');
            echo json_encode(['status' => 'success', 'message' => 'API table and entry deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Table not found.']);
        }
    }
    public function update_api_data()
    {
        $this->output->set_content_type('application/json');
        $table_name = $this->input->post('table_name');
        $id = $this->input->post('id');
        $column = $this->input->post('column');
        $value = $this->input->post('value');
        if (empty($table_name) || empty($id) || empty($column)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }
        $this->db->where('id', $id);
        $update_data = [$column => $value];
        $result = $this->db->update("core_" . $table_name, $update_data);
        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
        }
    }
}
