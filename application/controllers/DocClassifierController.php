<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DocClassifierController extends CI_Controller
{
    protected $year_id;
    function __construct()
    {
        parent::__construct();
        $this->load->model("Extract_model");
        $this->year_id = $this->session->userdata('year_id') ?? ($this->db->select('id')->from('financial_years')->where('is_current', 1)->get()->row()->id ?? null);
    }
    public function classification()
    {
        if (!getRoutePermission("classification")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $group_id = $this->input->get("group_id");
        $location_id = $this->input->get("location_id");
        $this->data["groups"] = $this->Extract_model->getGroups();
        $this->data["locations"] = $this->Extract_model->getLocations();
        $this->data["documents"] = $this->Extract_model->getClassificationList($group_id, $location_id);
        $this->data["main"] = "doc_classifier/classification";
        $this->load->view("layout/template", $this->data);
    }
    public function processed()
    {
        if (!getRoutePermission("processed")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $group_id = $this->input->get('group_id');
        $location_id = $this->input->get('location_id');
        $doc_type_id = $this->input->get('doc_type_id');
        $department_id = $this->input->get('department_id');
        $sub_department_id = $this->input->get('sub_department_id');
        $this->data["groups"] = $this->Extract_model->getGroups();
        $this->data["locations"] = $this->Extract_model->getLocations();
        $this->data["docTypes"] = $this->Extract_model->getDocTypes();
        $this->data["departments"] = $this->Extract_model->getDepartments();
        $this->data["subdepartments"] = $this->Extract_model->getSubdepartments($department_id);
        $this->data["documents"] = $this->Extract_model->getProcessedList($group_id, $location_id, $doc_type_id, $department_id, $sub_department_id);
        $this->data["main"] = "doc_classifier/processed";
        $this->load->view("layout/template", $this->data);
    }
    public function classifications_rejected()
    {
        if (!getRoutePermission("classifications_rejected")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $group_id = $this->input->get("group_id");
        $location_id = $this->input->get("location_id");
        $this->data["groups"] = $this->Extract_model->getGroups();
        $this->data["locations"] = $this->Extract_model->getLocations();
        $this->data["documents"] = $this->Extract_model->getclassificationsRejectedList($group_id, $location_id);
        $this->data["main"] = "doc_classifier/classifications_rejected";
        $this->load->view("layout/template", $this->data);
    }
    public function rejected_scans()
    {
        if (!getRoutePermission("rejected_scans")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->data["documents"] = $this->Extract_model->getScanRejectedScanAdminList();
        $this->data["main"] = "doc_classifier/scans_rejected";
        $this->load->view("layout/template", $this->data);
    }
    public function document_received()
    {
        if (!getRoutePermission("document_received")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $group_id = $this->input->get('group_id');
        $location_id = $this->input->get('location_id');
        $doc_type_id = $this->input->get('doc_type_id');
        $department_id = $this->input->get('department_id');
        $sub_department_id = $this->input->get('sub_department_id');
        $status = $this->input->get('status');
        $this->data["groups"] = $this->Extract_model->getGroups();
        $this->data["locations"] = $this->Extract_model->getLocations();
        $this->data["docTypes"] = $this->Extract_model->getDocTypes();
        $this->data["departments"] = $this->Extract_model->getDepartments();
        $this->data["subdepartments"] = $this->Extract_model->getSubdepartments($department_id);
        $this->data["documents"] = $this->Extract_model->getProcessedList($group_id, $location_id, $doc_type_id, $department_id, $sub_department_id, $status);
        $this->data['main'] = 'doc_classifier/doc_verification';
        $this->load->view('layout/template', $this->data);
    }
    public function scan_document_details()
    {
        $scanId = $this->input->post("scan_id");
        $data["document"] = $this->Extract_model->getDocumentDetails($scanId);
        $this->load->view("doc_classifier/document_details", $data);
    }
    public function update_received_status()
    {
        $scanId = $this->input->post("scan_id");
        $receivedDate = $this->input->post("received_date");
        $data = ['document_received_date' => $receivedDate, 'is_document_verified' => 'Y', 'verified_by' => $this->session->userdata('user_id'), 'verified_date' => date('Y-m-d')];
        if ($scanId && $receivedDate) {
            $this->db->where('scan_id', $scanId)->update("y{$this->year_id}_scan_file", $data);
            echo json_encode(["status" => "success", "message" => "Document status updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid input."]);
        }
    }
    public function scan_counts_by_date()
    {
        $user_id = $this->session->userdata('user_id');
        $group_id = $_SESSION['group_id'];
        $selected_date = $this->input->post('selected_date');
        if (!$user_id || !$group_id || !$this->year_id || !$selected_date) {
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }
        $table = "y{$this->year_id}_scan_file";
        $this->db->from($table);
        $this->db->where('document_name !=', '');
        $this->db->where('is_classified', 'Y');
        $this->db->where('classified_by', $user_id);
        $this->db->where('group_id', $group_id);
        $this->db->where('DATE(classified_date)', $selected_date);
        $classified = $this->db->count_all_results();
        $this->db->from($table);
        $this->db->where('document_name !=', '');
        $this->db->where('is_classified', 'Y');
        $this->db->where('classified_by', $user_id);
        $this->db->where('bill_approval_status', 'R');
        $this->db->where('group_id', $group_id);
        $this->db->where('DATE(classified_date)', $selected_date);
        $classified_rejected = $this->db->count_all_results();
        $this->db->from($table);
        $this->db->where('document_name !=', '');
        $this->db->where('temp_scan_rejected_by', $user_id);
        $this->db->where('is_temp_scan_rejected', 'Y');
        $this->db->where('group_id', $group_id);
        $this->db->where('DATE(temp_scan_reject_date)', $selected_date);
        $rejected = $this->db->count_all_results();
        $this->db->from($table);
        $this->db->where('document_name !=', '');
        $this->db->where('is_classified', 'Y');
        $this->db->where('classified_by', $user_id);
        $this->db->where('is_document_verified', 'Y');
        $this->db->where('group_id', $group_id);
        $this->db->where('DATE(verified_date)', $selected_date);
        $document_verified_count = $this->db->count_all_results();
        $response = ['classified_by_me' => $classified, 'classified_rejected' => $classified_rejected, 'scan_rejected_by_me' => $rejected, 'document_verified_count' => $document_verified_count,];
        echo json_encode($response);
    }
    public function reject_scanned_bill($scan_id)
    {
        $user_id = $this->session->userdata('user_id');
        $remark = trim($this->input->post('Remark'));
        if (empty($scan_id) || empty($user_id) || empty($remark)) {
            echo json_encode(['status' => 400, 'message' => 'Invalid input. Please check scan ID and remark.',]);
            return;
        }
        $data = ['is_temp_scan_rejected' => 'Y', 'temp_scan_rejected_by' => $user_id, 'temp_scan_reject_remark' => $remark, 'temp_scan_reject_date' => date('Y-m-d'),];
        $table = 'y' . $this->year_id . '_scan_file';
        $result = $this->BaseModel->updateData($table, $data, ['scan_id' => $scan_id]);
        if ($result) {
            echo json_encode(['status' => 200, 'message' => 'Bill rejected successfully.',]);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Something went wrong. Please try again.',]);
        }
    }
}
