<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Finance_Punch extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model("Punch_model");
    }

    private function logged_in()
    {
        if (!$this->session->userdata("authenticated")) {
            redirect("/");
        }
    }

    public function index()
    {
        $this->session->set_userdata("top_menu", "punch_master");
        $this->session->set_userdata("sub_menu", "punch");
        $this->data["main"] = "punch/finance_punchfile";
        $this->data["scanfile_list"] = $this->Punch_model->vspl_get_file_for_punch();
        $this->data["my_doctype_list"] = $this->Punch_model->get_my_permissioned_doctype_list();
        $this->load->view("layout/template", $this->data);
    }

    public function file_entry()
    {
        $this->session->set_userdata("top_menu", "punch_master");
        $this->session->set_userdata("sub_menu", "punch");
        $this->data["main"] = "punch/_punch";
        $this->load->view("layout/template", $this->data);
    }

    public function my_punched_file($i = null)
    {
        $this->session->set_userdata("top_menu", "punch_master");
        $this->session->set_userdata("sub_menu", "my_punched_file");

        if ($i == null) {
            $this->form_validation->set_rules("from_date", "Punch From Date", "trim|required");
            $this->form_validation->set_rules("to_date", "Punch To Date", "trim|required");

            if ($this->form_validation->run() == true) {
                $from_date = $this->input->post("from_date");
                $to_date = $this->input->post("to_date");
                $punch_file_list = $this->Punch_model->search_punched_file($from_date, $to_date);
            } else {
                $punch_file_list = $this->Punch_model->get_my_punched_file();
            }

            $this->data["my_punched_files"] = $punch_file_list;
            $this->data["main"] = "punch/my_punched_file";
            $this->load->view("layout/template", $this->data);
        } else {
            $this->data["my_punched_files"] = $this->Punch_model->get_my_punched_file_all();
            $this->data["main"] = "punch/my_punched_file";
            $this->load->view("layout/template", $this->data);
        }
    }

    function rejected_punch()
    {
        $this->session->set_userdata("top_menu", "punch_master");
        $this->session->set_userdata("sub_menu", "");
        $this->data["rejected_punch_list"] = $this->Punch_model->get_rejected_punch_list();
        $this->data["main"] = "punch/rejected_punch";
        $this->load->view("layout/template", $this->data);
    }

    function resend_scan($scan_id)
    {
        $user_id = $this->session->userdata("user_id");
        $Reject_Remark = $this->input->post("Remark");
        $this->db->where("scan_id", $scan_id);
        $result = $this->db->update("y{$this->year_id}_scan_file", [
            "is_scan_resend" => "Y",
            "Scan_Resend_By" => $user_id,
            "Scan_Resend_Remark" => $Reject_Remark,
            "Scan_Resend_Date" => date("Y-m-d"),
        ]);
        if ($result) {
            echo json_encode([
                "status" => "200",
                "message" => "File Resend Successfully.",
            ]);
        } else {
            echo json_encode([
                "status" => "400",
                "message" => "Something went wrong. Please try again.",
            ]);
        }
    }

    function changeDocType()
    {
        $scan_id = $this->input->post("scan_id");
        $doc_type_id = $this->input->post("doc_type_id");
        $Doc_Type = $this->customlib->getDocType($doc_type_id);
        $this->db->where("scan_id", $scan_id);
        $query = $this->db->update("y{$this->year_id}_scan_file", [
            "doc_type_id" => $doc_type_id,
            "Doc_Type" => $Doc_Type,
        ]);
        if ($query) {
            echo json_encode(["status" => 200]);
        } else {
            echo json_encode(["status" => 400]);
        }
    }

    function getSupportFile()
    {
        $scan_id = $this->input->post("scan_id");
        $this->db->select("*");
        $this->db->from("support_file");
        $this->db->where("scan_id", $scan_id);
        $query = $this->db->get();
        $result = $query->result();
        echo json_encode(["data" => $result, "status" => 200]);
    }

    function getFileList()
    {
        $Company = $this->input->post("Company");
        $this->db->select("file_name");
        $this->db->from("master_file");
        $this->db->where("company_id", $Company);
        $query = $this->db->get();
        $result = $query->result();
        if (!empty($result)) {
            echo json_encode(["data" => $result, "status" => 200]);
        } else {
            echo json_encode(["data" => [], "status" => 400]);
        }
    }
    function getDepartmentList()
    {
        $Company = $this->input->post("Company");
        $this->db->select("department_id,department_name");
        $this->db->from("master_department");
        $this->db->where("company_id", $Company);
        $query = $this->db->get();
        $result = $query->result();
        if (!empty($result)) {
            echo json_encode(["data" => $result, "status" => 200]);
        } else {
            echo json_encode(["data" => [], "status" => 400]);
        }
    }

    public function edit_doc_name()
    {
        $scan_id = $this->input->post("scan_id");
        $DocName = $this->input->post("DocName");
        $this->db->where("scan_id", $scan_id);
        $query = $this->db->update("y{$this->year_id}_scan_file", ["document_name " => $DocName]);

        if ($query) {
            echo json_encode([
                "status" => "200",
                "message" => "File Name Update Successfully.",
            ]);
        } else {
            echo json_encode([
                "status" => "400",
                "message" => "Something went wrong. Please try again.",
            ]);
        }
    }
    public function fetchRegions()
    {
        $state_id = $this->input->post("state_id");

        $regions = $this->db
            ->where("status", "A")
            ->where("is_deleted", "N")
            ->where("state_id", $state_id)
            ->get("master_region")
            ->result_array();

        echo json_encode($regions);
    }

    public function fetchCrops()
    {
        $category_id = $this->input->post("category_id");

        $crops = $this->db
            ->where("status", "A")
            ->where("is_deleted", "N")
            ->where("crop_category_id", $category_id)
            ->get("master_crop")
            ->result_array();

        echo json_encode($crops);
    }
    public function fetchAccount()
    {
        $category_id = $this->input->post("account_id");

        $query = $this->db->where("account_group", $category_id)->get("master_account");
        $accounts = $query->result_array();

        echo json_encode($accounts);
    }
}
