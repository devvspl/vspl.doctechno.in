<?php
defined("BASEPATH") or exit("No direct script access allowed");
class VSPL_cash_voucher_ctrl extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Scan_model");
    }
    public function create()
    {
        if ($this->customlib->has_permission("Finance") == 1) {
            if ($this->input->post("submit")) {
                $Scan_Id = $this->input->post("Scan_Id");
                $this->db->where(["Scan_Id" => $Scan_Id]);
                $query = $this->db->update("y{$this->year_id}_scan_file", [
                    "finance_punch" => "Y",
                    "finance_punch_date" => date("Y-m-d"),
                    "finance_punched_by" => $this->session->userdata("user_id"),
                ]);
                $this->customlib->update_file_path($Scan_Id);
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata("message", '<div class="alert alert-danger text-left">Transaction Failed</div>');
                } else {
                    $this->db->trans_commit();
                    $this->session->set_flashdata("message", '<div class="alert alert-success text-left">Cash Voucher Saved Successfully</div>');
                }
                redirect("finance_punch");
            } else {
                $document_number = $this->input->post("document_number");
                $Scan_Id = $this->input->post("Scan_Id");
                $DocTypeId = $this->input->post("DocTypeId");
                $punch_date = date("Y-m-d");
                $business_entity_id = $this->input->post("business_entity_id");
                $narration = $this->input->post("narration");
                $favouring = $this->input->post("favouring");
                $account = $this->input->post("account");
                $headquarter_id = $this->input->post("headquarter_id");
                $finance_total_Amount = $this->input->post("finance_total_Amount");

                $DepartmentID = $this->input->post("DepartmentID[]") ?? [];
                $business_unit_id = $this->input->post("business_unit_id[]") ?? [];
                $cost_center_id = $this->input->post("cost_center_id[]") ?? [];
                $state_id = $this->input->post("state_id[]") ?? [];
                $region_id = $this->input->post("region_id[]") ?? [];
                $location_id = $this->input->post("location_id[]") ?? [];
                $category_id = $this->input->post("category_id[]") ?? [];
                $crop_id = $this->input->post("crop_id[]") ?? [];
                $activity_id = $this->input->post("activity_id[]") ?? [];
                $debit_ac = $this->input->post("debit_ac[]") ?? [];
                $debit_ac_id = $this->input->post("debit_ac_id[]") ?? [];
                $payment_term = $this->input->post("payment_term[]") ?? [];
                $Total_Amount = $this->input->post("Item_Total_Amount[]") ?? [];
                $TDS_Amount = $this->input->post("Item_TDS_Amount[]") ?? [];
                $ReferenceNo = $this->input->post("Item_ReferenceNo[]") ?? [];
                $Remark = $this->input->post("Item_Remark[]") ?? [];
                $tdsApplicable = $this->input->post("tdsApplicable");
                $TDS_JV_no = $this->input->post("TDS_JV_no");
                $TDS_section = $this->input->post("TDS_section");
                $TDS_percentage = $this->input->post("TDS_percentage");
                $TDS_amount = $this->input->post("TDS_amount");
                $data = [
                    "finance_punch_date" => $punch_date,
                    "business_entity_id" => $business_entity_id,
                    "narration" => $narration,
                    "account" => $account,
                    "headquarter_id" => $headquarter_id,
                    "favouring" => $favouring,
                    "document_number" => $document_number,
                    "finance_total_Amount" => $finance_total_Amount,
                    "tdsApplicable" => $tdsApplicable,
                    "TDS_JV_no" => $TDS_JV_no,
                    "TDS_section" => $TDS_section,
                    "TDS_percentage" => $TDS_percentage,
                    "TDS_amount" => $TDS_amount,
                    "finance_punched_by" => $this->session->userdata("user_id"),
                ];
                $this->db->where(["Scan_Id" => $Scan_Id]);
                $query = $this->db->update("punchfile", $data);
                if ($query) {
                    $this->db->where("Scan_Id", $Scan_Id);
                    $existing = $this->db->get("cash_voucher_items")->result();
                    if (!empty($existing)) {
                        $this->db->where("Scan_Id", $Scan_Id);
                        $this->db->delete("cash_voucher_items");
                    }
                    for ($i = 0; $i < count($DepartmentID); $i++) {
                        $json_data = [
                            "Scan_Id" => $Scan_Id,
                            "DepartmentID" => isset($DepartmentID[$i]) ? $DepartmentID[$i] : null,
                            "cost_center_id" => isset($cost_center_id[$i]) ? $cost_center_id[$i] : null,
                            "business_unit_id" => isset($business_unit_id[$i]) ? $business_unit_id[$i] : null,
                            "state_id" => isset($state_id[$i]) ? $state_id[$i] : null,
                            "region_id" => isset($region_id[$i]) ? $region_id[$i] : null,
                            "location_id" => isset($location_id[$i]) ? $location_id[$i] : null,
                            "category_id" => isset($category_id[$i]) ? $category_id[$i] : null,
                            "crop_id" => isset($crop_id[$i]) ? $crop_id[$i] : null,
                            "activity_id" => isset($activity_id[$i]) ? $activity_id[$i] : null,
                            "debit_ac" => isset($debit_ac[$i]) ? $debit_ac[$i] : null,
                            "debit_ac_id" => isset($debit_ac_id[$i]) ? $debit_ac_id[$i] : null,
                            "payment_term" => isset($payment_term[$i]) ? $payment_term[$i] : null,
                            "Total_Amount" => isset($Total_Amount[$i]) ? $Total_Amount[$i] : null,
                            "TDS_Amount" => isset($TDS_Amount[$i]) ? $TDS_Amount[$i] : null,
                            "ReferenceNo" => isset($ReferenceNo[$i]) ? $ReferenceNo[$i] : null,
                            "Remark" => isset($Remark[$i]) ? $Remark[$i] : null,
                            "Created_By" => $this->session->userdata("user_id"),
                        ];
                        $this->db->insert("cash_voucher_items", $json_data);
                    }
           
                       
                        $this->session->set_flashdata("message", '<div class="alert alert-success text-left">Cash Voucher Detail Saved Successfully.</div>');
                        redirect("vspl_file_entry/" . $Scan_Id . "/" . $DocTypeId);
                    
                } else {
                    $this->session->set_flashdata("message", '<div class="alert alert-danger text-left">Failed to update punchfile.</div>');
                    redirect("vspl_file_entry/" . $Scan_Id . "/" . $DocTypeId);
                }
                
            }
        }
    }
}
