<?php
defined("BASEPATH") or exit("No direct script access allowed");
class ExtractorController extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->logged_in();
        $this->load->model("Extract_model");
    }
    private function logged_in() {
        if (!$this->session->userdata("authenticated")) {
            redirect("/");
        }
    }
    public function feilds_mapping() {
        $api_list = $this->Extract_model->getApiList();
        $this->data["api_list"] = $api_list;
        $this->data["main"] = "extract/feilds_mapping";
        $this->load->view("layout/template", $this->data);
    }
    public function classification() {
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
    public function processed() {
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
    public function changeRequestList() {
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
    public function changeRequest() {
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
    public function approveChangeRequest() {
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
    public function getDetails() {
        $scanId = $this->input->post("scan_id");
        $data["document"] = $this->Extract_model->getDocumentDetails($scanId);
        $data["docTypes"] = $this->Extract_model->getDocTypes();
        $this->load->view("extract/details_ajax", $data);
    }
    public function addToQueue() {
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
    public function getQueueList() {
        $this->data['queues'] = $this->Extract_model->getQueueList();
        $this->data["main"] = "extract/queue_list";
        $this->load->view("layout/template", $this->data);
    }
    public function processQueue() {
        $pendingItems = $this->Extract_model->getAllPendingQueueItems();
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
            }
            catch(Exception $e) {
                $this->Extract_model->updateQueueStatus($queue->id, 'failed', 'error', $e->getMessage());
                $failCount++;
                $errors[] = "ID {$queue->id}: " . $e->getMessage();
            }
        }
        echo json_encode(["status" => $failCount > 0 ? "partial" : "success", "message" => "Queue processing completed. Success: $successCount, Failed: $failCount", "errors" => $errors]);
    }
    public function extractDetails() {
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
            echo json_encode(["status" => "error", "message" => "Failed to add to queue."]);
        }
    }
    public function removeFromQueue() {
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
    public function getFieldDetails($has_items_feild) {
        $doctype_id = $this->input->post("doctype_id");
        $columns = $this->Extract_model->getFieldDetails($doctype_id, $has_items_feild);
        echo json_encode($columns);
    }
    public function getPunchTableColumns() {
        $table = $this->input->post("table");
        $columns = $this->Extract_model->getTableColumns($table);
        echo json_encode($columns);
    }
    public function getAllTables() {
        $tables = $this->Extract_model->getAllTables();
        echo json_encode($tables);
    }
    public function getTableColumns() {
        $table = $this->input->post("table");
        $columns = $this->Extract_model->getTableColumns($table);
        echo json_encode($columns);
    }
    public function saveFieldMappings() {
        $doctype_id = $this->input->post("doctype_id");
        $has_items_feild = $this->input->post("has_items_feild");
        $fieldMappings = $this->input->post("fieldMappings");
        $result = $this->Extract_model->saveFieldMappings($doctype_id, $has_items_feild, $fieldMappings);
        echo json_encode($result);
    }
    public function get_company_options() {
        $search_value = $this->input->post('search_value') ??'';
        $selected_id = $this->input->post('selected_id') ??'';
        $table = 'master_firm';
        $add_condition = "firm_type='Company'";
        $get_columns = ['firm_name', 'firm_id', 'address'];
        $items = $this->Extract_model->get_filtered_list($table, $search_value, 'firm_name', 'firm_id', $get_columns, $add_condition);
        $options = '<option value="">Select</option>';
        $highest_similarity = 0;
        $highest_similarity_firm_id = '';
        $has_selected = false;
        foreach ($items as $item) {
            $firm_name = htmlspecialchars($item['firm_name']??'');
            $firm_id = htmlspecialchars($item['firm_id']??'');
            $address = htmlspecialchars($item['address']??'');
            $similarity = $item['similarity']??0;
            $option_text = $firm_name;
            if ($similarity > 0) {
                $option_text.= " ($similarity%)";
            }
            if ($similarity > $highest_similarity) {
                $highest_similarity = $similarity;
                $highest_similarity_firm_id = $firm_id;
            }
            $selected = ($firm_id == $selected_id && !empty($selected_id)) ? 'selected' : '';
            if ($selected) {
                $has_selected = true;
            }
            $options.= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
        }
        if (!$has_selected && $highest_similarity_firm_id) {
            $options = '<option value="">Select</option>';
            foreach ($items as $item) {
                $firm_name = htmlspecialchars($item['firm_name']??'');
                $firm_id = htmlspecialchars($item['firm_id']??'');
                $address = htmlspecialchars($item['address']??'');
                $similarity = $item['similarity']??0;
                $option_text = $firm_name;
                if ($similarity > 0) {
                    $option_text.= " ($similarity%)";
                }
                $selected = ($firm_id == $highest_similarity_firm_id) ? 'selected' : '';
                $options.= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
            }
        }
        echo json_encode(['options' => $options]);
    }
    public function get_hotel_options() {
        $search_value = $this->input->post('search_value') ??'';
        $selected_id = $this->input->post('selected_id') ??'';
        $table = 'master_firm';
        $add_condition = "firm_type='Vendor'";
        $get_columns = ['firm_name', 'firm_id', 'address'];
        $items = $this->Extract_model->get_filtered_list($table, $search_value, 'firm_name', 'firm_id', $get_columns, $add_condition);
        $options = '<option value="">Select</option>';
        $highest_similarity = 0;
        $highest_similarity_firm_id = '';
        $has_selected = false;
        foreach ($items as $item) {
            $firm_name = htmlspecialchars($item['firm_name']??'');
            $firm_id = htmlspecialchars($item['firm_id']??'');
            $address = htmlspecialchars($item['address']??'');
            $similarity = $item['similarity']??0;
            $option_text = $firm_name;
            if ($similarity > 0) {
                $option_text.= " ($similarity%)";
            }
            if ($similarity > $highest_similarity) {
                $highest_similarity = $similarity;
                $highest_similarity_firm_id = $firm_id;
            }
            $selected = ($firm_id == $selected_id && !empty($selected_id)) ? 'selected' : '';
            if ($selected) {
                $has_selected = true;
            }
            $options.= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
        }
        if (!$has_selected && $highest_similarity_firm_id) {
            $options = '<option value="">Select</option>';
            foreach ($items as $item) {
                $firm_name = htmlspecialchars($item['firm_name']??'');
                $firm_id = htmlspecialchars($item['firm_id']??'');
                $address = htmlspecialchars($item['address']??'');
                $similarity = $item['similarity']??0;
                $option_text = $firm_name;
                if ($similarity > 0) {
                    $option_text.= " ($similarity%)";
                }
                $selected = ($firm_id == $highest_similarity_firm_id) ? 'selected' : '';
                $options.= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
            }
        }
        echo json_encode(['options' => $options]);
    }
    public function get_vendor_options() {
        $search_value = $this->input->post('search_value') ??'';
        $selected_id = $this->input->post('selected_id') ??'';
        $table = 'master_firm';
        $add_condition = "firm_type='Vendor'";
        $get_columns = ['firm_name', 'firm_id', 'address'];
        $items = $this->Extract_model->get_filtered_list($table, $search_value, 'firm_name', 'firm_id', $get_columns, $add_condition);
        $options = '<option value="">Select</option>';
        $highest_similarity = 0;
        $highest_similarity_firm_id = '';
        $has_selected = false;
        foreach ($items as $item) {
            $firm_name = htmlspecialchars($item['firm_name']??'');
            $firm_id = htmlspecialchars($item['firm_id']??'');
            $address = htmlspecialchars($item['address']??'');
            $similarity = $item['similarity']??0;
            $option_text = $firm_name;
            if ($similarity > 0) {
                $option_text.= " ($similarity%)";
            }
            if ($similarity > $highest_similarity) {
                $highest_similarity = $similarity;
                $highest_similarity_firm_id = $firm_id;
            }
            $selected = ($firm_id == $selected_id && !empty($selected_id)) ? 'selected' : '';
            if ($selected) {
                $has_selected = true;
            }
            $options.= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
        }
        if (!$has_selected && $highest_similarity_firm_id) {
            $options = '<option value="">Select</option>';
            foreach ($items as $item) {
                $firm_name = htmlspecialchars($item['firm_name']??'');
                $firm_id = htmlspecialchars($item['firm_id']??'');
                $address = htmlspecialchars($item['address']??'');
                $similarity = $item['similarity']??0;
                $option_text = $firm_name;
                if ($similarity > 0) {
                    $option_text.= " ($similarity%)";
                }
                $selected = ($firm_id == $highest_similarity_firm_id) ? 'selected' : '';
                $options.= "<option value=\"$firm_id\" data-address=\"$address\" $selected>$option_text</option>";
            }
        }
        echo json_encode(['options' => $options]);
    }
    public function get_location_options() {
        $search_value = $this->input->post('search_value') ??'';
        $selected_id = $this->input->post('selected_id') ??'';
        $table = 'master_work_location';
        $add_condition = "status='A' AND is_deleted='N'";
        $get_columns = ['location_name', 'location_id'];
        $items = $this->Extract_model->get_filtered_list($table, $search_value, 'location_name', 'location_name', $get_columns, $add_condition);
        $options = '<option value="">Select</option>';
        $highest_similarity = 0;
        $highest_similarity_location_name = '';
        $has_selected = false;
        foreach ($items as $item) {
            $location_name = htmlspecialchars($item['location_name']??'');
            $similarity = $item['similarity']??0;
            $option_text = $location_name;
            if ($similarity > 0) {
                $option_text.= " ($similarity%)";
            }
            if ($similarity > $highest_similarity) {
                $highest_similarity = $similarity;
                $highest_similarity_location_name = $location_name;
            }
            $selected = ($location_name == $selected_id && !empty($selected_id)) ? 'selected' : '';
            if ($selected) {
                $has_selected = true;
            }
            $options.= "<option value=\"$location_name\" $selected>$option_text</option>";
        }
        if (!$has_selected && $highest_similarity_location_name) {
            $options = '<option value="">Select</option>';
            foreach ($items as $item) {
                $location_name = htmlspecialchars($item['location_name']??'');
                $similarity = $item['similarity']??0;
                $option_text = $location_name;
                if ($similarity > 0) {
                    $option_text.= " ($similarity%)";
                }
                $selected = ($location_name == $highest_similarity_location_name) ? 'selected' : '';
                $options.= "<option value=\"$location_name\" $selected>$option_text</option>";
            }
        }
        echo json_encode(['options' => $options]);
    }
}
