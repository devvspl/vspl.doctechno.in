<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Cashpayment_New_ctrl extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Scan_model");
    }
	public function create()
	{
		// Collect input data
		$document_number = $this->input->post("document_number");
		$Scan_Id = $this->input->post("Scan_Id");
		$DocTypeId = $this->input->post("DocTypeId");
		$BillDate = $this->input->post("BillDate");
		$File_No = $this->input->post("bill_no");
		$pucnh_date = $this->input->post("pucnh_date");
		$business_entity_id = $this->input->post("business_entity_id");
		$narration = $this->input->post("narration");
		$main_remark = $this->input->post("main_remark");
		$Total_Amount_main = $this->input->post("Total_Amount");
		$account_group = $this->input->post("account_group");
		$account = $this->input->post("account");
		$favouring = $this->input->post("favouring");
	
		// Collect array values
		$cost_center_id = $this->input->post("cost_center_id[]");
		$location_id = $this->input->post("location_id[]");
		$category_id = $this->input->post("category_id[]");
		$crop_id = $this->input->post("crop_id[]");
		$activity_id = $this->input->post("activity_id[]");
		$state_id = $this->input->post("state_id[]");
		$region_id = $this->input->post("region_id[]");
		$DepartmentID = $this->input->post("DepartmentID[]");
		$ptm_category = $this->input->post("ptm_category[]");
		$business_unit_id = $this->input->post("business_unit_id[]");
		$account_group_items = $this->input->post("account_group_items[]");
		$Account_id_item = $this->input->post("Account_id_item[]");
		$Total_Amount_item = $this->input->post("Total_Amount_item[]");
		$ReferenceNo = $this->input->post("ReferenceNo[]");
		$Remark = $this->input->post("Remark[]");
	
		// TDS Section
		$tdsApplicable = $this->input->post("tdsApplicable");
		$TDS_JV_no = $this->input->post("TDS_JV_no");
		$TDS_section = $this->input->post("TDS_section");
		$TDS_percentage = $this->input->post("TDS_percentage");
		$TDS_amount = $this->input->post("TDS_amount");
	
		$DocType = $this->customlib->getDocType($DocTypeId);
	
		// Data to be inserted or updated in `punchfile`
		$data = [
			"Scan_Id" => $Scan_Id,
			"DocTypeId" => $DocTypeId,
			"DocType" => $DocType,
			"BillDate" => $BillDate,
			"File_No" => $File_No,
			"file_punch_date" => $pucnh_date,
			"business_entity_id" => $business_entity_id,
			"narration" => $narration,
			"Remark" => $main_remark,
			"document_number" => $document_number,
			"Total_Amount" => $Total_Amount_main,
			"account_group" =>$account_group,
			"account" =>$account,
			"favouring" => $favouring,
			"tdsApplicable" => $tdsApplicable,
			"TDS_JV_no" => $TDS_JV_no,
			"Section" => $TDS_section,
			"TDS_section" => $TDS_section,
			"TDS_percentage" => $TDS_percentage,
			"TDS_amount" => $TDS_amount,
			"Group_Id" => $this->session->userdata("group_id"),
			"Created_By" => $this->session->userdata("user_id"),
			"Created_Date" => date("Y-m-d H:i:s"),
		];
	
		$this->db->trans_start();
		$this->db->trans_strict(false);
	
		if ($this->customlib->check_punchfile($Scan_Id)) {
			// Update Existing Record in `punchfile`
			$this->db->where("Scan_Id", $Scan_Id)->update("punchfile", $data);
			$FileID = $this->db->where("Scan_Id", $Scan_Id)->get("punchfile")->row()->FileID;
	
			$this->db->where("FileID", $FileID)->update("sub_punchfile", [
				"Amount" => "-" . $Total_Amount_main,
				"Comment" => $main_remark,
			]);
	
			// Clear previous entries for the journal
			$this->db->where("Scan_Id", $Scan_Id)->delete("cash_payment_new_items");
	
			// Insert updated journal entries
			for ($i = 0; $i < count($DepartmentID); $i++) {
				$json_data = [
					'Scan_Id' => $Scan_Id,
					'cost_center_id' => $cost_center_id[$i],
					'location_id' => $location_id[$i],
					'category_id' => $category_id[$i],
					'crop_id' => $crop_id[$i],
					'activity_id' => $activity_id[$i],
					'state_id' => $state_id[$i],
					'region_id' => $region_id[$i],
					'DepartmentID' => $DepartmentID[$i],
					'ptm_category' => $ptm_category[$i],
					'business_unit_id' => $business_unit_id[$i],
					'account_group_items' => $account_group_items[$i],
					'Account_id_item' => $Account_id_item[$i],
					'Total_Amount_item' => $Total_Amount_item[$i],
					'ReferenceNo' => $ReferenceNo[$i],
					'Remark' => $Remark[$i],
					"Created_By" => $this->session->userdata("user_id"),
				];
	
				$this->db->insert('cash_payment_new_items', $json_data);
			}
	
			$this->db->where("Scan_Id", $Scan_Id)->update("scan_file", [
				"Is_Rejected" => "N",
				"Reject_Date" => null,
				"Edit_Permission" => "N",
			]);
		} else {
			// Insert new record
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
	
			// Insert into `sub_punchfile`
			$this->db->insert("sub_punchfile", [
				"FileID" => $insert_id,
				"Amount" => "-" . $Total_Amount_main,
				"Comment" => $main_remark,
			]);
	
			// Insert journal entries
			for ($i = 0; $i < count($DepartmentID); $i++) {
				$json_data = [
					'Scan_Id' => $Scan_Id,
					'cost_center_id' => $cost_center_id[$i],
					'location_id' => $location_id[$i],
					'category_id' => $category_id[$i],
					'crop_id' => $crop_id[$i],
					'activity_id' => $activity_id[$i],
					'state_id' => $state_id[$i],
					'region_id' => $region_id[$i],
					'DepartmentID' => $DepartmentID[$i],
					'ptm_category' => $ptm_category[$i],
					'business_unit_id' => $business_unit_id[$i],
					'account_group_items' => $account_group_items[$i],
					'Account_id_item' => $Account_id_item[$i],
					'Total_Amount_item' => $Total_Amount_item[$i],
					'ReferenceNo' => $ReferenceNo[$i],
					'Remark' => $Remark[$i],
					"Created_By" => $this->session->userdata("user_id"),
				];
	
				$this->db->insert('cash_payment_new_items', $json_data);
			}
		}
	
		$this->customlib->update_file_path($Scan_Id);
		$this->db->trans_complete();
	
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			$this->session->set_flashdata("message", '<div class="alert alert-danger text-left">Something Went Wrong</div>');
			redirect("punch");
		} else {
			$this->session->set_flashdata("message", '<div class="alert alert-success text-left">Journal Entry added successfully</div>');
			redirect("punch");
		}
	}
	
}
