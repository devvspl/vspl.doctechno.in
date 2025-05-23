<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vehicle_ctrl extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Scan_model');
	}

	public function create()
	{
		$Scan_Id = $this->input->post('scan_id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);

		$Vehicle_No = $this->input->post('Vehicle_No');
		$Vehicle_Type = $this->input->post('Vehicle_Type');
		$Vehicle_Company = $this->input->post('Vehicle_Company');
		$Registered = $this->input->post('Registered');
		$Registration_Date = $this->input->post('Registration_Date');
		$Clearance_Date = $this->input->post('Clearance_Date');
		$Custody_Name = $this->input->post('Custody_Name');
		$Hypothecation = $this->input->post('Hypothecation');
		$Remark = $this->input->post('Remark');

		$data = array(
			'scan_id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'VehicleNo' => $Vehicle_No,
			'VehicleType' => $Vehicle_Type,
			'VehicleCompany' => $Vehicle_Company,
			'Registered' => $Registered,
			'RegPurDate' => $Registration_Date,
			'ClearanceDate' => $Clearance_Date,
			'CustomerName' => $Custody_Name,
			'Hypothecation' => $Hypothecation,
			'Remark' => $Remark,
			'group_id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile2($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('scan_id', $Scan_Id)->update('punchfile2', $data);
			$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
		} else {
			//Insert New Record
			$this->db->insert('punchfile2', $data);
		}

		$this->customlib->update_file_path($Scan_Id);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
			redirect('punch');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Certificate added successfully</div>');
			redirect('punch');
		}
	}

	public function save_two_four_wheel_exp()
	{


		$post = $this->input->post();
		$submit = isset($post['submit']) ? true : false;
	
		$Scan_Id = $post['scan_id'];
		$DocTypeId = $post['DocTypeId'];
		$DocType = $this->customlib->getDocType($DocTypeId);
		$EmployeeID = $post['Employee'];
		$Emp_Code = $post['Emp_Code'];
		$Employee_Name = $this->customlib->getEmployeeNameById($EmployeeID);
	
		$data = array(
			'scan_id' => $Scan_Id,
			'group_id' => $this->session->userdata('group_id'),
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'BillDate' => $post['Bill_Date'],
			'EmployeeID' => $EmployeeID,
			'EmployeeCode' => $Emp_Code,
			'Employee_Name' => $Employee_Name,
			'VehicleRegNo' => $post['Vehicle_No'],
			'Vehicle_Type' => $post['Vehicle_Type'],
			'VehicleRs_PerKM' => $post['Rate'],
			'Loc_Name' => $post['location_id'],
			'TotalRunKM' => $post['Total_KM'],
			'Total_Amount' => $post['Total_Amount'],
			'Grand_Total' => $post['Grand_Total'],
			'Total_Discount' => $post['Total_Discount'],
			'Remark' => $post['Remark'],
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);
	
		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
	
		if ($this->customlib->check_punchfile($Scan_Id)) {
			// Update
			$this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->select('FileID')->where('scan_id', $Scan_Id)->get('punchfile')->row('FileID');
			$this->db->where('FileID', $FileID)->update('sub_punchfile', array(
				'Amount' => '-' . $post['Grand_Total'],
				'Comment' => $post['Remark']
			));
			$this->db->where('scan_id', $Scan_Id)->delete('vehicle_traveling');
		} else {
			// Insert
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
			$this->db->insert('sub_punchfile', array(
				'FileID' => $insert_id,
				'Amount' => '-' . $post['Grand_Total'],
				'Comment' => $post['Remark']
			));
		}
	
		// Insert vehicle entries
		$vehicle_entries = [];
		for ($i = 0; $i < count($post['Dist_Opening']); $i++) {
			$vehicle_entries[] = array(
				'scan_id' => $Scan_Id,
				'DistTraOpen' => $post['Dist_Opening'][$i],
				'DistTraClose' => $post['Dist_Closing'][$i],
				'Totalkm' => $post['Km'][$i],
				'FilledTAmt' => $post['Amount'][$i],
			);
		}
		if (!empty($vehicle_entries)) {
			$this->db->insert_batch('vehicle_traveling', $vehicle_entries);
		}
	
		// If it's a submission, update y{$this->year_id}_scan_file to finalize
		if ($submit) {
			$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
				'is_rejected' => 'N',
				'reject_date' => NULL,
				'has_edit_permission' => 'N',
				'finance_punch_action_status' => 'N'
			));
			$this->customlib->update_file_path($Scan_Id);
		}
	
		$this->db->trans_complete();
	
		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something Went Wrong</div>');
		} else {
			if ($submit) {
				$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Two/Four Wheeler Fare submitted successfully</div>');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Saved as draft</div>');
			}
		}
	
		redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
	}

	public function getTwoFourWheelerRecord()
	{
		$Scan_Id = $this->input->post('scan_id');
		$result = $this->db->select('*')->from('vehicle_traveling')->where('scan_id', $Scan_Id)->get()->result_array();

		if (!empty($result)) {
			echo json_encode(array('status' => 200, 'data' => $result));
		} else {
			echo json_encode(array('status' => 400, 'msg' => 'No Record Found'));
		}
	}

	public function Save_Air_Bus_Fare()
	{
		$Scan_Id = $this->input->post('scan_id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$Travel_Mode = $this->input->post('Travel_Mode');
		$TrainBusName = $this->input->post('TrainBusName');
		$Quota = $this->input->post('Quota');
		$Class = $this->input->post('Class');
		$Booking_Date = $this->input->post('Booking_Date');
		$Journey_Date = $this->input->post('Journey_Date');
		$Journey_From = $this->input->post('Journey_From');
		$Journey_Upto = $this->input->post('Journey_Upto');
		$Passenger = $this->input->post('Passenger');
		$Booking_Status = $this->input->post('Booking_Status');
		$Travel_Insurance = $this->input->post('Travel_Insurance');
		$Amount = $this->input->post('Amount');
		$Remark = $this->input->post('Remark');
		$data = array(
			'scan_id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'TravelMode' => $Travel_Mode,
			'FileName' => $TrainBusName,
			'TravelQuota' => $Quota,
			'TravelClass' => $Class,
			'BookingDate' => $Booking_Date,
			'FromDateTime' => $Journey_Date,
			'FromName' => $Journey_From,
			'ToName' => $Journey_Upto,
			'PassengerDetail' => $Passenger,
			'BookingStatus' => $Booking_Status,
			'TravelInsurance' => $Travel_Insurance,
			'Total_Amount' => $Amount,
			'Remark' => $Remark,
			'group_id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
			$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
		} else {
			//Insert New Record
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
			$this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Amount, 'Comment' => $Remark));
		}

		$this->customlib->update_file_path($Scan_Id);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
			redirect('punch');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Air Bus Train Fare added successfully</div>');
			redirect('punch');
		}
	}

	public function Save_Vehicle_Maintenance() {
		$submit = $this->input->post('submit');  // This will check if the action is 'submit' or 'draft'
	
		$Scan_Id = $this->input->post('scan_id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$VendorId = $this->input->post('Vendor_Name');
		$VendorName = $this->customlib->getCompanyNameById($VendorId);
		$Billing_To = $this->input->post('Billing_To');
		$BillingName = $this->customlib->getCompanyNameById($Billing_To);
		$Bill_Date = $this->input->post('Bill_Date');
		$Bill_No = $this->input->post('InvoiceNo');
		$Work_Location = $this->input->post('Work_Location');
		$VehicleRegNo = $this->input->post('VehicleRegNo');
		$Sub_Total = $this->input->post('Sub_Total');
		$Total = $this->input->post('Total');
		$Total_Discount = $this->input->post('Total_Discount');
		$Grand_Total = $this->input->post('Grand_Total');
		$GST = $this->input->post('GST');
		$SGST = $this->input->post('SGST');
		$IGST = $this->input->post('IGST');
		$Particular = $this->input->post('Particular');
		$HSN = $this->input->post('HSN');
		$Qty = $this->input->post('Qty');
		$Unit = $this->input->post('Unit');
		$MRP = $this->input->post('MRP');
		$Discount = $this->input->post('Discount');
		$Price = $this->input->post('Price');
		$Amount = $this->input->post('Amount');
		$TAmount = $this->input->post('TAmount');
		$Remark = $this->input->post('Remark');
	
		// Prepare the data to be inserted or updated
		$data = array(
			'scan_id' => $Scan_Id,
			'group_id' => $this->session->userdata('group_id'),
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'From_ID' => $VendorId,
			'FromName' => $VendorName,
			'To_ID' => $Billing_To,
			'ToName' => $BillingName,
			'Company' => $BillingName,
			'CompanyID' => $Billing_To,
			'File_No' => $Bill_No,
			'BillDate' => $Bill_Date,
			'Loc_Name' => $Work_Location,
			'VehicleRegNo' => $VehicleRegNo,
			'SubTotal' => $Sub_Total,
			'Total_Amount' => $Total,
			'Grand_Total' => $Grand_Total,
			'Total_Discount' => $Total_Discount,
			'Remark' => $Remark,
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);
	
		// Start the transaction
		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
	
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			// Update existing record
			$this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
	
			// Update the sub_punchfile record
			$this->db->where('FileID', $FileID)->update('sub_punchfile', array(
				'Amount' => '-' . $Grand_Total,
				'Comment' => $Remark
			));
	
			// Handle the 'submit' or 'draft' action
			if ($submit) {
				$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
					'is_rejected' => 'N',
					'reject_date' => NULL,
					'has_edit_permission' => 'N',  // Disable edit on submit
					'finance_punch_action_status' => 'N'  // Set finance_punch to 'N' when submitting
				));
			} else {
				$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
					'is_rejected' => 'N',
					'reject_date' => NULL,
					'has_edit_permission' => 'Y',  // Allow editing for draft
				));
			}
	
		} else {
			// Insert new record
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
	
			// Insert into sub_punchfile
			$this->db->insert('sub_punchfile', array(
				'FileID' => $insert_id,
				'Amount' => '-' . $Grand_Total,
				'Comment' => $Remark
			));
	
			// Handle the 'submit' or 'draft' action after insertion
			if ($submit) {
				$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
					'is_rejected' => 'N',
					'reject_date' => NULL,
					'has_edit_permission' => 'N',  // Disable edit on submit
					'finance_punch_action_status' => 'N'  // Set finance_punch to 'N' when submitting
				));
			} else {
				$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
					'is_rejected' => 'N',
					'reject_date' => NULL,
					'has_edit_permission' => 'Y',  // Allow editing for draft
				));
			}
		}
	
		$this->customlib->update_file_path($Scan_Id);
	
		// Complete the transaction
		$this->db->trans_complete();
	
		// Handle transaction success or failure
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something Went Wrong</div>');
			redirect('punch');
		} else {
			if ($submit) {
				$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Vehicle Maintenance submitted successfully</div>');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Vehicle Maintenance saved as draft</div>');
			}
		}
	
		// Redirect based on whether it's submitted or saved as a draft
		redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
	}
	
	function getVehicleMaintenanceItem()
	{
		$Scan_Id = $this->input->post('scan_id');

		$result = $this->db->select('*')->from('labour_payment_detail')->where('scan_id', $Scan_Id)->get()->result_array();
		if (!empty($result)) {
			echo json_encode(array('status' => 200, 'data' => $result));
		} else {
			echo json_encode(array('status' => 400, 'msg' => 'No Record Found'));
		}
	}

	public function Save_Vehicle_Fule() {

		$submit = $this->input->post('submit');  // This will check if the action is 'submit' or 'draft'
	
		$Scan_Id = $this->input->post('scan_id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$VendorId = $this->input->post('Vendor_Name');
		$VendorName = $this->customlib->getCompanyNameById($VendorId);
		$Billing_To = $this->input->post('Billing_To');
		$BillingName = $this->customlib->getCompanyNameById($Billing_To);
		$Dealer_Code = $this->input->post('Dealer_Code');
		$InvoiceNo = $this->input->post('InvoiceNo');
		$Bill_Date = $this->input->post('Bill_Date');
		$Due_Date = $this->input->post('Due_Date');
		$Work_Location = $this->input->post('Work_Location');
		$VehicleNo = $this->input->post('VehicleNo');
		$Description = $this->input->post('Description');
		$Liter = $this->input->post('Liter');
		$Rate = $this->input->post('Rate');
		$Amount = $this->input->post('Amount');
		$Remark = $this->input->post('Remark');
		$Total_Discount = $this->input->post('Total_Discount');
		$Grand_Total = $this->input->post('Grand_Total');
	
		// Prepare the data to be inserted or updated
		$data = array(
			'scan_id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'FileName' => $Description,
			'From_ID' => $VendorId,
			'FromName' => $VendorName,
			'To_ID' => $Billing_To,
			'ToName' => $BillingName,
			'CompanyID' => $Billing_To,
			'Company' => $BillingName,
			'BSRCode' => $Dealer_Code,
			'File_No' => $InvoiceNo,
			'BillDate' => $Bill_Date,
			'DueDate' => $Due_Date,
			'Loc_Name' => $Work_Location,
			'VehicleRegNo' => $VehicleNo,
			'MeterNumber' => $Liter,
			'TariffPlan' => $Rate,
			'Total_Amount' => $Amount,
			'Grand_Total' => $Grand_Total,
			'Total_Discount' => $Total_Discount,
			'Remark' => $Remark,
			'group_id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);
	
		// Start the transaction
		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
	
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			// Update existing record
			$this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
	
			// Update the sub_punchfile record
			$this->db->where('FileID', $FileID)->update('sub_punchfile', array(
				'Amount' => '-' . $Grand_Total, 
				'Comment' => $Remark
			));
	
			// Handle the 'submit' or 'draft' action
			if ($submit) {
				$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
					'is_rejected' => 'N', 
					'reject_date' => NULL, 
					'has_edit_permission' => 'N', 
					'finance_punch_action_status' => 'N'  // Set finance_punch to 'N' when submitting
				));
			} else {
				$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
					'is_rejected' => 'N', 
					'reject_date' => NULL, 
					'has_edit_permission' => 'Y',  // Allow editing for draft
				
				));
			}
	
		} else {
			// Insert new record
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
	
			// Insert into sub_punchfile
			$this->db->insert('sub_punchfile', array(
				'FileID' => $insert_id, 
				'Amount' => '-' . $Grand_Total, 
				'Comment' => $Remark
			));
	
			// Handle the 'submit' or 'draft' action after insertion
			if ($submit) {
				$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
					'is_rejected' => 'N', 
					'reject_date' => NULL, 
					'has_edit_permission' => 'N', 
					'finance_punch_action_status' => 'N'  // Set finance_punch to 'N' when submitting
				));
			} else {
				$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
					'is_rejected' => 'N', 
					'reject_date' => NULL, 
					'has_edit_permission' => 'Y',  // Allow editing for draft
				
				));
			}
		}
	
		$this->customlib->update_file_path($Scan_Id);
	
		// Complete the transaction
		$this->db->trans_complete();
	
		// Handle transaction success or failure
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something Went Wrong</div>');
			redirect('punch');
		} else {
			if ($submit) {
				$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Vehicle Fuel Bill submitted successfully</div>');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Vehicle Fuel Bill saved as draft</div>');
			}
		}
	
		// Redirect based on whether it's submitted or saved as a draft
		redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
	}
	


	public function save_local_conveyance()
	{
		$Scan_Id = $this->input->post('scan_id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$submit = $this->input->post('submit');
	
		$Travel_Mode = $this->input->post('Travel_Mode');
		$EmployeeID = $this->input->post('Employee');
		$Emp_Code = $this->input->post('Emp_Code');
		$Employee_Name = $this->customlib->getEmployeeNameById($EmployeeID);
		$Vehicle_No = $this->input->post('Vehicle_No');
		$Month = $this->input->post('Month');
		$Month_Name = $this->customlib->getMonthName($Month);
		$Cal_By = $this->input->post('cal_by');
		$Per_KM_Rate = $this->input->post('Per_KM_Rate');
		$Fixed_Amount = $this->input->post('Fixed_Amount');
		$Date = $this->input->post('Date');
		$Dist_Opening = $this->input->post('Dist_Opening');
		$Dist_Closing = $this->input->post('Dist_Closing');
		$Km = $this->input->post('Km');
		$Amount = $this->input->post('Amount');
		$Total_KM = $this->input->post('Total_KM');
		$Total_Amount = $this->input->post('Total_Amount');
		$Remark = $this->input->post('Remark');
		$Location = $this->input->post('location_id');
	
		$data = array(
			'scan_id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'TravelMode' => $Travel_Mode,
			'EmployeeID' => $EmployeeID,
			'EmployeeCode' => $Emp_Code,
			'Employee_Name' => $Employee_Name,
			'VehicleRegNo' => $Vehicle_No,
			'Month' => $Month,
			'MonthName' => $Month_Name,
			'Cal_By' => $Cal_By,
			'VehicleRs_PerKM' => $Per_KM_Rate,
			'HiredVehicle_Amount' => $Fixed_Amount,
			'TotalRunKM' => $Total_KM,
			'Total_Amount' => $Total_Amount,
			'Remark' => $Remark,
			'group_id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
			'Loc_Name' => $Location,
		);
	
		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
	
		if ($this->customlib->check_punchfile($Scan_Id)) {
			// Update
			$this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
	
			$this->db->where('FileID', $FileID)->update('sub_punchfile', array(
				'Amount' => '-' . $Total_Amount,
				'Comment' => $Remark
			));
	
			if ($submit) {
				$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
					'is_rejected' => 'N',
					'reject_date' => NULL,
					'has_edit_permission' => 'N',
					'finance_punch_action_status' => 'N'
				));
			}
	
			$this->db->where('scan_id', $Scan_Id)->delete('vehicle_traveling');
		} else {
			// Insert
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
	
			$this->db->insert('sub_punchfile', array(
				'FileID' => $insert_id,
				'Amount' => '-' . $Total_Amount,
				'Comment' => $Remark
			));
	
			if ($submit) {
				$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
					'is_rejected' => 'N',
					'reject_date' => NULL,
					'has_edit_permission' => 'N',
					'finance_punch_action_status' => 'N'
				));
			}
		}
	
		// Common for both insert/update
		$travel_data = array();
		for ($i = 0; $i < count($Date); $i++) {
			$travel_data[] = array(
				'scan_id' => $Scan_Id,
				'JourneyStartDt' => date('Y-m-d', strtotime($Date[$i])),
				'DistTraOpen' => $Dist_Opening[$i],
				'DistTraClose' => $Dist_Closing[$i],
				'Totalkm' => $Km[$i],
				'FilledTAmt' => $Amount[$i],
			);
		}
		$this->db->insert_batch('vehicle_traveling', $travel_data);
	
		$this->customlib->update_file_path($Scan_Id);
	
		$this->db->trans_complete();
	
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something Went Wrong</div>');
		} else {
			if ($submit) {
				$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Local Conveyance Bill submitted successfully</div>');
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Saved as draft</div>');
			}
		}
	
		redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
	}
	

	public function save_jeep_campaign()
	{
		$Scan_Id = $this->input->post('scan_id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$Date = $this->input->post('Date');
		$Vegetable = $this->input->post('Vegetable');
		$HVC = $this->input->post('HVC');
		$AV = $this->input->post('AV');
		$Other = $this->input->post('Other');
		$Amount = $this->input->post('Total_Amount');
		$Remark = $this->input->post('Remark');
		$data = array(
			'scan_id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'BillDate' => $Date,
			'CropDetails' => $Vegetable,
			'AVTent_Amount' => $AV,
			'HiredVehicle_Amount' => $HVC,
			'OthCharge_Amount' => $Other,
			'Total_Amount' => $Amount,
			'Remark' => $Remark,
			'group_id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
			$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
		} else {
			//Insert New Record
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
			$this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Amount, 'Comment' => $Remark));
		}

		$this->customlib->update_file_path($Scan_Id);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
			redirect('punch');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Jeep Campaign Bill added successfully</div>');
			redirect('punch');
		}
	}

	public function save_hired_vehicle()
{
    $Scan_Id = $this->input->post('scan_id');
    $DocTypeId = $this->input->post('DocTypeId');
    $DocType = $this->customlib->getDocType($DocTypeId);
    $submit = $this->input->post('submit');

    $AgencyID = $this->input->post('Agency_Name');
    $Agency_Name = $this->customlib->getCompanyNameById($AgencyID);
    $Agency_Address = $this->input->post('Agency_Address');

    $BillingID = $this->input->post('Billing_Name');
    $Billing_Name = $this->customlib->getCompanyNameById($BillingID);
    $Billing_Address = $this->input->post('Billing_Address');

    $EmployeeID = $this->input->post('Employee');
    $Emp_Code = $this->input->post('Emp_Code');
    $Employee_Name = $this->customlib->getEmployeeNameById($EmployeeID);

    $Amount = $this->input->post('Total_Amount');
    $Remark = $this->input->post('Remark');

    $data = array(
        'scan_id' => $Scan_Id,
        'DocType' => $DocType,
        'DocTypeId' => $DocTypeId,
        'From_ID' => $AgencyID,
        'FromName' => $Agency_Name,
        'AgencyAddress' => $Agency_Address,
        'To_ID' => $BillingID,
        'ToName' => $Billing_Name,
        'Related_Address' => $Billing_Address,
        'EmployeeID' => $EmployeeID,
        'EmployeeCode' => $Emp_Code,
        'Employee_Name' => $Employee_Name,
        'VehicleRegNo' => $this->input->post('Vehicle_No'),
        'File_No' => $this->input->post('Invoice_No'),
        'File_Date' => $this->input->post('Invoice_Date'),
        'VehicleRs_PerKM' => $this->input->post('Per_KM_Rate'),
        'FromDateTime' => $this->input->post('Journey_Start'),
        'ToDateTime' => $this->input->post('Journey_End'),
        'OpeningKM' => $this->input->post('Opening_Reading'),
        'ClosingKM' => $this->input->post('Closing_Reading'),
        'TotalRunKM' => $this->input->post('Total_KM'),
        'OthCharge_Amount' => $this->input->post('Other_Charge'),
        'Total_Amount' => $Amount,
        'Remark' => $Remark,
        'group_id' => $this->session->userdata('group_id'),
        'Created_By' => $this->session->userdata('user_id'),
        'Created_Date' => date('Y-m-d H:i:s'),
        'Loc_Name' => $this->input->post('location_id'),
    );

    $this->db->trans_start();
    $this->db->trans_strict(FALSE);

    if ($this->customlib->check_punchfile($Scan_Id)) {
        $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
        $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;

        $this->db->where('FileID', $FileID)->update('sub_punchfile', array(
            'Amount' => '-' . $Amount,
            'Comment' => $Remark
        ));

        if ($submit) {
            $this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
                'is_rejected' => 'N',
                'reject_date' => NULL,
                'has_edit_permission' => 'N',
                'finance_punch_action_status' => 'N'
            ));
        }
    } else {
        $this->db->insert('punchfile', $data);
        $insert_id = $this->db->insert_id();

        $this->db->insert('sub_punchfile', array(
            'FileID' => $insert_id,
            'Amount' => '-' . $Amount,
            'Comment' => $Remark
        ));

        if ($submit) {
            $this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array(
                'is_rejected' => 'N',
                'reject_date' => NULL,
                'has_edit_permission' => 'N',
                'finance_punch_action_status' => 'N'
            ));
        }
    }

    $this->customlib->update_file_path($Scan_Id);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something Went Wrong</div>');
    } else {
        if ($submit) {
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Hired Vehicle Bill submitted successfully</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Saved as draft</div>');
        }
    }

    redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
}


	public function save_machine_operation()
	{

		$Scan_Id = $this->input->post('scan_id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$CompanyID = $this->input->post('CompanyID');
		$Company_Name = $this->customlib->getCompanyNameById($CompanyID);
		$Related_Address = $this->input->post('Related_Address');
		$To_ID = $this->input->post('To_ID');
		$ToName = $this->customlib->getCompanyNameById($To_ID);
		$AgencyAddress = $this->input->post('AgencyAddress');
		$VehicleRegNo = $this->input->post('VehicleRegNo');
		$Vehicle_Type = $this->input->post('Vehicle_Type');
		$Location = $this->input->post('location_id');
		$Invoice_Date = $this->input->post('Invoice_Date');
		$Particular = $this->input->post('Particular');
		$Hour = $this->input->post('Hour');
		$Trip = $this->input->post('Trip');
		$Rate = $this->input->post('Rate');
		$Total_Amount = $this->input->post('Total_Amount');
		$Remark = $this->input->post('Remark');
		$data = array(
			'scan_id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'Company' => $Company_Name,
			'CompanyID' => $CompanyID,
			'Related_Address' => $Related_Address,
			'To_ID' => $To_ID,
			'ToName' => $ToName,
			'AgencyAddress' => $AgencyAddress,
			'VehicleRegNo' => $VehicleRegNo,
			'Vehicle_Type' => $Vehicle_Type,
			'Loc_Name' => $Location,
			'BillDate' => $Invoice_Date,
			'Particular' => $Particular,
			'Period' => $Hour,
			'TotalRunKM' => $Trip,
			'RateOfInterest' => $Rate,
			'Total_Amount' => $Total_Amount,
			'Remark' => $Remark,
			'group_id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Total_Amount, 'Comment' => $Remark));
			$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
		} else {
			//Insert New Record
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
			$this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Total_Amount, 'Comment' => $Remark));
		}

		$this->customlib->update_file_path($Scan_Id);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
			redirect('punch');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Machine Operation Bill added successfully</div>');
			redirect('punch');
		}
	}

	public function Save_Air()
	{
		$Scan_Id = $this->input->post('scan_id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$Agent_Name = $this->input->post('Agent_Name');
		$PNR_Number = $this->input->post('PNR_Number');
		$Booking_Date = $this->input->post('Booking_Date');
		$Journey_Date = $this->input->post('Journey_Date');
		$Airline = $this->input->post('Airline');
		$Ticket_Number = $this->input->post('Ticket_Number');
		$Journey_From = $this->input->post('Journey_From');
		$Journey_To = $this->input->post('Journey_To');
		$Travel_Class = $this->input->post('Travel_Class');
		$EmployeeID = $this->input->post('Employee');
		//	$Emp_Code = $this->input->post('Emp_Code');
		//$Employee_Name = $this->customlib->getEmployeeNameById($EmployeeID);
		$Passenger_Details = $this->input->post('Passenger_Details');
		$Base_Fare = $this->input->post('Base_Fare');
		$GST = $this->input->post('GST');
		$Surcharge = $this->input->post('Surcharge');
		$Cute_Charge = $this->input->post('Cute_Charge');
		$Extra_Luggage = $this->input->post('Extra_Luggage');
		$Other = $this->input->post('Other');
		$Total_Amount = $this->input->post('Total_Amount');
		$Remark = $this->input->post('Remark');
		$Location = $this->input->post('location_id');
		$data = array(
			'scan_id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'TravelMode' => 'Air',
			'AgentName' => $Agent_Name,
			'ServiceNo' => $PNR_Number,
			'BookingDate' => $Booking_Date,
			'FromDateTime' => $Journey_Date,
			'Airline' => $Airline,
			'File_No' => $Ticket_Number,
			'TripStarted' => $Journey_From,
			'TripEnded' => $Journey_To,
			'TravelClass' => $Travel_Class,
			//'EmployeeID' => $EmployeeID,
			//'EmployeeCode' => $Emp_Code,
			//'Employee_Name' => $Employee_Name,
			'PassengerDetail' => $Passenger_Details,
			'Base_Fare' => $Base_Fare,
			'GSTIN' => $GST,
			'Surcharge' => $Surcharge,
			'Cute_Charge' => $Cute_Charge,
			'Extra_Luggage' => $Extra_Luggage,
			'OthCharge_Amount' => $Other,
			'Total_Amount' => $Total_Amount,
			'Remark' => $Remark,
			'group_id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
			'Loc_Name'=>$Location,
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Total_Amount, 'Comment' => $Remark));
			$this->db->where('scan_id', $data['scan_id'])->delete('lodging_employee');
			$array = array();
			for ($i = 0; $i < count($this->input->post('Employee')); $i++) {
				$array[$i] = array(
					'scan_id' => $data['scan_id'],
					'emp_id' => $this->input->post('Employee')[$i],
					'emp_code' => $this->input->post('EmpCode')[$i],
					'emp_name' => $this->customlib->getEmployeeNameById($this->input->post('Employee')[$i]),

				);
			}
			$this->db->insert_batch('lodging_employee', $array);
			$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
		} else {
			//Insert New Record
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
			$this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Total_Amount, 'Comment' => $Remark));
			$array = array();
			for ($i = 0; $i < count($this->input->post('Employee')); $i++) {
				$array[$i] = array(
					'scan_id' => $data['scan_id'],
					'emp_id' => $this->input->post('Employee')[$i],
					'emp_code' => $this->input->post('EmpCode')[$i],
					'emp_name' => $this->customlib->getEmployeeNameById($this->input->post('Employee')[$i]),

				);
			}
			$this->db->insert_batch('lodging_employee', $array);
		}

		$this->customlib->update_file_path($Scan_Id);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
			redirect('punch');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Air Fare added successfully</div>');
			redirect('punch');
		}
	}

	public function Save_Rail()
	{
		$Scan_Id = $this->input->post('scan_id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$Train_Number = $this->input->post('Train_Number');
		$Agent_Name = $this->input->post('Agent_Name');
		$PNR_Number = $this->input->post('PNR_Number');
		$Booking_Date = $this->input->post('Booking_Date');
		$Journey_Date = $this->input->post('Journey_Date');
		$Booking_Id = $this->input->post('Booking_Id');
		$Transaction_Id = $this->input->post('Transaction_Id');
		$Journey_From = $this->input->post('Journey_From');
		$Journey_To = $this->input->post('Journey_To');
		$Travel_Class = $this->input->post('Travel_Class');
		$Travel_Quota = $this->input->post('Travel_Quota');
		//$EmployeeID = $this->input->post('Employee');
		//$Emp_Code = $this->input->post('Emp_Code');
		//	$Employee_Name = $this->customlib->getEmployeeNameById($EmployeeID);
		$Passenger_Details = $this->input->post('Passenger_Details');
		$Base_Fare = $this->input->post('Base_Fare');
		$GST = $this->input->post('GST');
		$Surcharge = $this->input->post('Surcharge');
		$Other = $this->input->post('Other');
		$Total_Amount = $this->input->post('Total_Amount');
		$Remark = $this->input->post('Remark');
		$Location = $this->input->post('location_id');
		$data = array(
			'scan_id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'TravelMode' => 'Rail',
			'File_No' => $Train_Number,
			'AgentName' => $Agent_Name,
			'ServiceNo' => $PNR_Number,
			'BookingDate' => $Booking_Date,
			'FromDateTime' => $Journey_Date,
			'FDRNo' => $Booking_Id,
			'RegNo' => $Transaction_Id,
			'TripStarted' => $Journey_From,
			'TripEnded' => $Journey_To,
			'TravelClass' => $Travel_Class,
			'TravelQuota' => $Travel_Quota,
			//'EmployeeID' => $EmployeeID,
			//'EmployeeCode' => $Emp_Code,
			//'Employee_Name' => $Employee_Name,
			'PassengerDetail' => $Passenger_Details,
			'Base_Fare' => $Base_Fare,
			'GSTIN' => $GST,
			'Surcharge' => $Surcharge,
			'OthCharge_Amount' => $Other,
			'Total_Amount' => $Total_Amount,
			'Remark' => $Remark,
			'group_id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
			'Loc_Name'=>$Location,
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Total_Amount, 'Comment' => $Remark));
			$this->db->where('scan_id', $data['scan_id'])->delete('lodging_employee');
			$array = array();
			for ($i = 0; $i < count($this->input->post('Employee')); $i++) {
				$array[$i] = array(
					'scan_id' => $data['scan_id'],
					'emp_id' => $this->input->post('Employee')[$i],
					'emp_code' => $this->input->post('EmpCode')[$i],
					'emp_name' => $this->customlib->getEmployeeNameById($this->input->post('Employee')[$i]),

				);
			}
			$this->db->insert_batch('lodging_employee', $array);
			$this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
		} else {
			//Insert New Record
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
			$this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Total_Amount, 'Comment' => $Remark));
			$array = array();
			for ($i = 0; $i < count($this->input->post('Employee')); $i++) {
				$array[$i] = array(
					'scan_id' => $data['scan_id'],
					'emp_id' => $this->input->post('Employee')[$i],
					'emp_code' => $this->input->post('EmpCode')[$i],
					'emp_name' => $this->customlib->getEmployeeNameById($this->input->post('Employee')[$i]),

				);
			}
			$this->db->insert_batch('lodging_employee', $array);
		}

		$this->customlib->update_file_path($Scan_Id);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
			redirect('punch');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Rail Fare added successfully</div>');
			redirect('punch');
		}
	}

	public function Save_Bus()
	{
		$data = [
			'scan_id' => $this->input->post('scan_id'),
			'DocType' => $this->customlib->getDocType($this->input->post('DocTypeId')),
			'DocTypeId' => $this->input->post('DocTypeId'),
			'TravelMode' => 'Bus',
			'File_No' => $this->input->post('Invoice_No'),
			'BillDate' => $this->input->post('Invoice_Date'),
			'AgentName' => $this->input->post('Agent_Name'),
			'FDRNo' => $this->input->post('Booking_Id'),
			'BookingDate' => $this->input->post('Booking_Date'),
			'ServiceNo' => $this->input->post('Ticket_Number'),
			'TravelClass' => $this->input->post('Bus_Type'),
			'EmployeeID' => $this->input->post('Employee'),
			'EmployeeCode' => $this->input->post('Emp_Code'),
			'Employee_Name' => $this->customlib->getEmployeeNameById($this->input->post('Employee')),
			'PassengerDetail' => $this->input->post('Passenger_Details'),
			'Base_Fare' => $this->input->post('Base_Fare'),
			'GSTIN' => $this->input->post('GST'),
			'Surcharge' => $this->input->post('Surcharge'),
			'OthCharge_Amount' => $this->input->post('Other'),
			'Total_Amount' => $this->input->post('Total_Amount'),
			'Remark' => $this->input->post('Remark'),
			'group_id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
			'Loc_Name' => $this->input->post('location_id'),
		];

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		$isExistingRecord = $this->customlib->check_punchfile($data['scan_id']);
		if ($isExistingRecord) {
			$this->db->where('scan_id', $data['scan_id'])->update('punchfile', $data);
			$fileID = $this->db->where('scan_id', $data['scan_id'])->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $fileID)->update('sub_punchfile', ['Amount' => '-' . $data['Total_Amount'], 'Comment' => $data['Remark']]);
			$this->db->where('scan_id', $data['scan_id'])->update("y{$this->year_id}_scan_file", ['is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N']);
		} else {
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
			$this->db->insert('sub_punchfile', ['FileID' => $insert_id, 'Amount' => '-' . $data['Total_Amount'], 'Comment' => $data['Remark']]);
		}
		$this->customlib->update_file_path($data['scan_id']);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$message = '<div class="alert alert-danger text-left">Something Went Wrong</div>';
		} else {
			$message = '<div class="alert alert-success text-left">Bus Fare added successfully</div>';
		}
		$this->session->set_flashdata('message', $message);
		redirect('punch');
	}
}
