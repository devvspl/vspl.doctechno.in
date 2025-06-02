<?php
defined("BASEPATH") or exit("No direct script access allowed");
class ExtractorController extends CI_Controller
{
    protected $year_id;
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->model("Extract_model");
        $this->year_id = $this->session->userdata("year_id");
    }
    private function logged_in()
    {
        if (!$this->session->userdata("authenticated")) {
            redirect("/");
        }
    }
    public function update_path()
    {
        // Load database
        $this->load->database();

        // Get all records from y1_scan_file
        $query = $this->db->select('scan_id, file_path, secondary_file_path')
            ->get('y1_scan_file');

        // Check if records exist
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                // Initialize update data array
                $update_data = array();

                // Update file_path if it contains 'Uploads'
                if (!empty($row->file_path)) {
                    $new_file_path = str_replace('Uploads', 'uploads', $row->file_path);
                    if ($new_file_path !== $row->file_path) {
                        $update_data['file_path'] = $new_file_path;
                    }
                }

                // Update secondary_file_path if it contains 'Uploads'
                if (!empty($row->secondary_file_path)) {
                    $new_secondary_path = str_replace('Uploads', 'uploads', $row->secondary_file_path);
                    if ($new_secondary_path !== $row->secondary_file_path) {
                        $update_data['secondary_file_path'] = $new_secondary_path;
                    }
                }

                // Perform update if there are changes
                if (!empty($update_data)) {
                    $this->db->where('scan_id', $row->scan_id)
                        ->update('y1_scan_file', $update_data);
                }
            }

            return true; // Return true if process completed
        }

        return false; // Return false if no records found
    }
    public function feilds_mapping()
    {
        $api_list = $this->Extract_model->getApiList();
        $this->data["api_list"] = $api_list;
        $this->data["main"] = "extract/feilds_mapping";
        $this->load->view("layout/template", $this->data);
    }
    public function classification()
    {
        $this->session->set_userdata("top_menu", "classification");
        $this->session->set_userdata("sub_menu", "classification");
        $group_id = $this->input->get("group_id");
        $location_id = $this->input->get("location_id");
        $this->data["groups"] = $this->Extract_model->getGroups();
        $this->data["locations"] = $this->Extract_model->getLocations();
        $this->data["documents"] = $this->Extract_model->getClassificationList($group_id, $location_id);
        $this->data["main"] = "extract/classification";
        $this->load->view("layout/template", $this->data);
    }
    public function processed()
    {
        $this->session->set_userdata("top_menu", "processed");
        $this->session->set_userdata("sub_menu", "processed");
        $group_id = $this->input->get("group_id");
        $location_id = $this->input->get("location_id");
        $this->data["groups"] = $this->Extract_model->getGroups();
        $this->data["locations"] = $this->Extract_model->getLocations();
        $this->data["documents"] = $this->Extract_model->getProcessedList($group_id, $location_id);
        $this->data["main"] = "extract/processed";
        $this->load->view("layout/template", $this->data);
    }
    public function changeRequestList()
    {
        $this->session->set_userdata("top_menu", "change-request");
        $this->session->set_userdata("sub_menu", "change-request");
        $group_id = $this->input->get("group_id");
        $location_id = $this->input->get("location_id");
        $this->data["groups"] = $this->Extract_model->getGroups();
        $this->data["locations"] = $this->Extract_model->getLocations();
        $this->data["documents"] = $this->Extract_model->getChangeRequestList($group_id, $location_id);
        $this->data["main"] = "extract/change-request";
        $this->load->view("layout/template", $this->data);
    }
    public function changeRequest()
    {
        $scan_id = $this->input->post('scan_id');
        if (empty($scan_id)) {
            echo json_encode(["status" => "error", "message" => "Invalid Scan ID"]);
            return;
        }
        $result = $this->Extract_model->updateDocumentStatus($scan_id, 'C');
        if ($result) {
            echo json_encode(["status" => "success", "message" => "Approved Change Request"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update record"]);
        }
    }
    public function approveChangeRequest()
    {
        $scan_id = $this->input->post('scan_id');
        if (empty($scan_id)) {
            echo json_encode(["status" => "error", "message" => "Invalid Scan ID"]);
            return;
        }
        $result = $this->Extract_model->updateDocumentStatus($scan_id, 'N');
        if ($result) {
            echo json_encode(["status" => "success", "message" => "Change Request Submitted"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update record"]);
        }
    }
    public function getDetails()
    {
        $scanId = $this->input->post("scan_id");
        $data["document"] = $this->Extract_model->getDocumentDetails($scanId);
        $data["docTypes"] = $this->Extract_model->getDocTypes();
        $data["departments"] = $this->Extract_model->getDepartments();
        $data["locations"] = $this->Extract_model->getLocations();
        $this->load->view("extract/details_ajax", $data);
    }
    public function getSubdepartments()
    {
        $department_id = $this->input->post("department_id");
        $subdepartments = $this->Extract_model->getSubdepartments($department_id);
        echo json_encode($subdepartments);
    }
    public function getBillApprovers()
    {
        $department_id = $this->input->post("department_id");
        $approvers = $this->Extract_model->getBillApprovers($department_id);
        echo json_encode($approvers);
    }
    public function addToQueue()
    {
        $scanId = $this->input->post("scan_id");
        $typeId = $this->input->post("type_id");
        if (!$scanId || !$typeId) {
            echo json_encode(["status" => "error", "message" => "Invalid request parameters."]);
            return;
        }
        $result = $this->Extract_model->addToQueue($scanId, $typeId);
        if ($result) {
            echo json_encode(["status" => "success", "message" => "Added to queue successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "This file is already in queue."]);
        }
    }
    public function getQueueList()
    {
        $this->data['queues'] = $this->Extract_model->getQueueList();
        $this->data["main"] = "extract/queue_list";
        $this->load->view("layout/template", $this->data);
    }
    public function processQueue()
    {
        $queue_id = $this->input->post('queue_id');
        $pendingItems = $this->Extract_model->getAllPendingQueueItems($queue_id);
        if (empty($pendingItems)) {
            echo json_encode(["status" => "success", "message" => "No pending items in queue."]);
            return;
        }
        $successCount = 0;
        $failCount = 0;
        $errors = [];
        foreach ($pendingItems as $queue) {
            try {
                $endpoint = $this->Extract_model->getApiEndpoint($queue->type_id);
                if (!$endpoint) {
                    throw new Exception("API endpoint not found.");
                }
                $fileUrl = $this->Extract_model->getFileLocation($queue->scan_id);
                if (!$fileUrl) {
                    throw new Exception("File not found.");
                }
                $apiResponse = $this->Extract_model->callExternalApi($endpoint, $fileUrl);
                if ($apiResponse["statusCode"] !== 200 || empty($apiResponse["data"])) {
                    throw new Exception("API call failed.");
                }
                $saveStatus = $this->Extract_model->storeExtractedData($queue->type_id, $queue->scan_id, $apiResponse["data"]);
                if (!$saveStatus) {
                    throw new Exception("Failed to store data.");
                }
                $this->Extract_model->updateQueueStatus($queue->id, 'completed', 'success');
                $successCount++;
            } catch (Exception $e) {
                $this->Extract_model->updateQueueStatus($queue->id, 'failed', 'error', $e->getMessage());
                $failCount++;
                $errors[] = "ID {$queue->id}: " . $e->getMessage();
            }
        }
        echo json_encode(["status" => $failCount > 0 ? "partial" : "success", "message" => "Queue processing completed. Success: $successCount, Failed: $failCount", "errors" => $errors]);
    }
    public function extractDetails()
    {
        $scanId = $this->input->post("scan_id");
        $typeId = $this->input->post("type_id");
        if (empty($scanId) || empty($typeId)) {
            echo json_encode(["status" => "error", "message" => "Invalid request parameters: scan_id and type_id are required."]);
            return;
        }
        $data = ['doc_type_id' => $typeId, 'department_id' => $this->input->post("department"), 'sub_department_id' => $this->input->post("subdepartment"), 'bill_approver_id' => $this->input->post("bill_approver"), 'location_id' => $this->input->post("location")];
        $updateResult = $this->Extract_model->updateDocument($scanId, $data);
        if (!$updateResult) {
            echo json_encode(["status" => "error", "message" => "Failed to update document details."]);
            return;
        }
        $queueResult = $this->Extract_model->addToQueue($scanId, $typeId);
        if ($queueResult) {
            echo json_encode(["status" => "success", "message" => "Document updated and added to queue successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Document updated, but failed to add to queue."]);
        }
    }
    public function removeFromQueue()
    {
        $queueId = $this->input->post('queue_id');
        if (empty($queueId)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid queue ID']);
            return;
        }
        $result = $this->Extract_model->removeFromQueue($queueId);
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Item removed from queue successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to remove item from queue']);
        }
    }
    public function getFieldDetails($has_items_feild)
    {
        $doctype_id = $this->input->post("doctype_id");
        $columns = $this->Extract_model->getFieldDetails($doctype_id, $has_items_feild);
        echo json_encode($columns);
        exit;
    }
    public function getPunchTableColumns()
    {
        $table = $this->input->post("table");
        $columns = $this->Extract_model->getTableColumnsList($table);
        echo json_encode($columns);
        exit;
    }
    public function getAllTables()
    {
        $punchOnly = $this->input->get('punchOnly');
        $tables = $this->Extract_model->getAllTablesList($punchOnly);
        echo json_encode($tables);
        exit;
    }
    public function getTableColumns()
    {
        $table = $this->input->post("table");
        $columns = $this->Extract_model->getTableColumnsList($table);
        echo json_encode($columns);
    }
    public function saveFieldMappings()
    {
        $doctype_id = $this->input->post("doctype_id");
        $has_items_feild = $this->input->post("has_items_feild");
        $fieldMappingsRaw = $this->input->post("fieldMappings");
        $fieldMappings = is_string($fieldMappingsRaw) ? json_decode($fieldMappingsRaw, true) : $fieldMappingsRaw;
        if ($has_items_feild == "N") {
            $table = "y{$this->year_id}_punchdata_{$doctype_id}";
        } else {
            $table = "y{$this->year_id}_punchdata_{$doctype_id}_details";
        }
        $result = $this->Extract_model->saveFieldMappingsValue($doctype_id, $has_items_feild, $fieldMappings, $table);
        echo json_encode($result);
    }
    public function get_company_options()
    {
        $search_value = $this->input->post('search_value') ?? '';
        $selected_id = $this->input->post('selected_id') ?? '';
        $table = 'master_firm';
        $add_condition = "firm_type='Company'";
        $get_columns = ['firm_name', 'firm_id', 'address'];
        $items = $this->Extract_model->get_filtered_list($table, $search_value, 'firm_name', 'firm_id', $get_columns, $add_condition);
        $options = '<option value="">Select</option>';
        $highest_similarity = 0;
        $highest_similarity_firm_id = '';
        $has_selected = false;
        foreach ($items as $item) {
            $firm_name = htmlspecialchars($item['firm_name'] ?? '');
            $firm_id = htmlspecialchars($item['firm_id'] ?? '');
            $address = htmlspecialchars($item['address'] ?? '');
            $similarity = $item['similarity'] ?? 0;
            $option_text = $firm_name;
            if ($similarity > 0) {
                $option_text .= " ($similarity%)";
            }
            if ($similarity > $highest_similarity) {
                $highest_similarity = $similarity;
                $highest_similarity_firm_id = $firm_id;
            }
            $selected = ($firm_id == $selected_id && !empty($selected_id)) ? 'selected' : '';
            if ($selected) {
                $has_selected = true;
            }
            $options .= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
        }
        if (!$has_selected && $highest_similarity_firm_id) {
            $options = '<option value="">Select</option>';
            foreach ($items as $item) {
                $firm_name = htmlspecialchars($item['firm_name'] ?? '');
                $firm_id = htmlspecialchars($item['firm_id'] ?? '');
                $address = htmlspecialchars($item['address'] ?? '');
                $similarity = $item['similarity'] ?? 0;
                $option_text = $firm_name;
                if ($similarity > 0) {
                    $option_text .= " ($similarity%)";
                }
                $selected = ($firm_id == $highest_similarity_firm_id) ? 'selected' : '';
                $options .= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
            }
        }
        echo json_encode(['options' => $options]);
    }
    public function get_hotel_options()
    {
        $search_value = $this->input->post('search_value') ?? '';
        $selected_id = $this->input->post('selected_id') ?? '';
        $table = 'master_firm';
        $add_condition = "firm_type='Vendor'";
        $get_columns = ['firm_name', 'firm_id', 'address'];
        $items = $this->Extract_model->get_filtered_list($table, $search_value, 'firm_name', 'firm_id', $get_columns, $add_condition);
        $options = '<option value="">Select</option>';
        $highest_similarity = 0;
        $highest_similarity_firm_id = '';
        $has_selected = false;
        foreach ($items as $item) {
            $firm_name = htmlspecialchars($item['firm_name'] ?? '');
            $firm_id = htmlspecialchars($item['firm_id'] ?? '');
            $address = htmlspecialchars($item['address'] ?? '');
            $similarity = $item['similarity'] ?? 0;
            $option_text = $firm_name;
            if ($similarity > 0) {
                $option_text .= " ($similarity%)";
            }
            if ($similarity > $highest_similarity) {
                $highest_similarity = $similarity;
                $highest_similarity_firm_id = $firm_id;
            }
            $selected = ($firm_id == $selected_id && !empty($selected_id)) ? 'selected' : '';
            if ($selected) {
                $has_selected = true;
            }
            $options .= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
        }
        if (!$has_selected && $highest_similarity_firm_id) {
            $options = '<option value="">Select</option>';
            foreach ($items as $item) {
                $firm_name = htmlspecialchars($item['firm_name'] ?? '');
                $firm_id = htmlspecialchars($item['firm_id'] ?? '');
                $address = htmlspecialchars($item['address'] ?? '');
                $similarity = $item['similarity'] ?? 0;
                $option_text = $firm_name;
                if ($similarity > 0) {
                    $option_text .= " ($similarity%)";
                }
                $selected = ($firm_id == $highest_similarity_firm_id) ? 'selected' : '';
                $options .= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
            }
        }
        echo json_encode(['options' => $options]);
    }
    public function get_vendor_options()
    {
        $search_value = $this->input->post('search_value') ?? '';
        $selected_id = $this->input->post('selected_id') ?? '';
        $table = 'master_firm';
        $add_condition = "firm_type='Vendor'";
        $get_columns = ['firm_name', 'firm_id', 'address'];
        $items = $this->Extract_model->get_filtered_list($table, $search_value, 'firm_name', 'firm_id', $get_columns, $add_condition);
        $options = '<option value="">Select</option>';
        $highest_similarity = 0;
        $highest_similarity_firm_id = '';
        $has_selected = false;
        foreach ($items as $item) {
            $firm_name = htmlspecialchars($item['firm_name'] ?? '');
            $firm_id = htmlspecialchars($item['firm_id'] ?? '');
            $address = htmlspecialchars($item['address'] ?? '');
            $similarity = $item['similarity'] ?? 0;
            $option_text = $firm_name;
            if ($similarity > 0) {
                $option_text .= " ($similarity%)";
            }
            if ($similarity > $highest_similarity) {
                $highest_similarity = $similarity;
                $highest_similarity_firm_id = $firm_id;
            }
            $selected = ($firm_id == $selected_id && !empty($selected_id)) ? 'selected' : '';
            if ($selected) {
                $has_selected = true;
            }
            $options .= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
        }
        if (!$has_selected && $highest_similarity_firm_id) {
            $options = '<option value="">Select</option>';
            foreach ($items as $item) {
                $firm_name = htmlspecialchars($item['firm_name'] ?? '');
                $firm_id = htmlspecialchars($item['firm_id'] ?? '');
                $address = htmlspecialchars($item['address'] ?? '');
                $similarity = $item['similarity'] ?? 0;
                $option_text = $firm_name;
                if ($similarity > 0) {
                    $option_text .= " ($similarity%)";
                }
                $selected = ($firm_id == $highest_similarity_firm_id) ? 'selected' : '';
                $options .= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
            }
        }
        echo json_encode(['options' => $options]);
    }
    public function get_location_options()
    {
        $search_value = $this->input->post('search_value') ?? '';
        $selected_id = $this->input->post('selected_id') ?? '';
        $table = 'master_work_location';
        $add_condition = "status='A' AND is_deleted='N'";
        $get_columns = ['location_name', 'location_id'];
        $items = $this->Extract_model->get_filtered_list($table, $search_value, 'location_name', 'location_name', $get_columns, $add_condition);
        $options = '<option value="">Select</option>';
        $highest_similarity = 0;
        $highest_similarity_location_name = '';
        $has_selected = false;
        foreach ($items as $item) {
            $location_name = htmlspecialchars($item['location_name'] ?? '');
            $similarity = $item['similarity'] ?? 0;
            $option_text = $location_name;
            if ($similarity > 0) {
                $option_text .= " ($similarity%)";
            }
            if ($similarity > $highest_similarity) {
                $highest_similarity = $similarity;
                $highest_similarity_location_name = $location_name;
            }
            $selected = ($location_name == $selected_id && !empty($selected_id)) ? 'selected' : '';
            if ($selected) {
                $has_selected = true;
            }
            $options .= "<option value=\"$location_name\" $selected>$option_text</option>";
        }
        if (!$has_selected && $highest_similarity_location_name) {
            $options = '<option value="">Select</option>';
            foreach ($items as $item) {
                $location_name = htmlspecialchars($item['location_name'] ?? '');
                $similarity = $item['similarity'] ?? 0;
                $option_text = $location_name;
                if ($similarity > 0) {
                    $option_text .= " ($similarity%)";
                }
                $selected = ($location_name == $highest_similarity_location_name) ? 'selected' : '';
                $options .= "<option value=\"$location_name\" $selected>$option_text</option>";
            }
        }
        echo json_encode(['options' => $options]);
    }
}
