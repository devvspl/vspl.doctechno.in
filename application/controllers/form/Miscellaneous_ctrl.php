<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Miscellaneous_ctrl extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Scan_model');
	}

	public function create()
	{
		$Scan_Id = $this->input->post('Scan_Id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);

		$File_Date = $this->input->post('File_Date');
		$VoucherNo = $this->input->post('VoucherNo');
		$Voucher_Date = $this->input->post('Voucher_Date');
		//$Payee = $this->input->post('Payee');
		$Particular = $this->input->post('Particular');
		$Amount = $this->input->post('Amount');
		$CompanyId = $this->input->post('Company');
		$Comapny = $this->customlib->getCompanyNameById($CompanyId);
		$VendorID = $this->input->post('Vendor');
		$Vendor = $this->customlib->getCompanyNameById($VendorID);
		$Location = $this->input->post('Location');
		$Remark = $this->input->post('Remark');

		$data = array(
			'Scan_Id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'File_Date' => $File_Date,
			'File_No' => $VoucherNo,
			'RegPurDate' => $Voucher_Date,
			// 'Related_Person' => $Payee,
			'TotalAmount' => $Amount,
			'Additional_Exposure' => $Particular,
			'Company' => $Comapny,
			'CompanyID' => $CompanyId,
			'Vendor' => $Vendor,
			'VendorID' => $VendorID,
			'Location' => $Location,
			'Remark' => $Remark,
			'Group_Id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile2($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('Scan_Id', $Scan_Id)->update('punchfile2', $data);
			$this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array('Is_Rejected' => 'N', 'Reject_Date' => NULL, 'Edit_Permission' => 'N'));
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
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Miscellaneous Record added successfully</div>');
			redirect('punch');
		}
	}

	public function save_rst_ofd()
	{

		$Scan_Id = $this->input->post('Scan_Id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$Crop = $this->input->post('Crop');
		$Date = $this->input->post('Date');
		$Crop_Detail = $this->input->post('Crop_Detail');
		$Trial_Op_Exp_Amount = $this->input->post('Trial_Op_Exp_Amount');
		$Fertilizer_Amount = $this->input->post('Fertilizer_Amount');
		$Consumable_Amount = $this->input->post('Consumable_Amount');
		$Miscellaneous_Amount = $this->input->post('Miscellaneous_Amount');
		$Amount = $this->input->post('Total_Amount');
		$Remark = $this->input->post('Remark');
		$data = array(
			'Scan_Id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'File_Type' => $Crop,
			'BillDate' => $Date,
			'CropDetails' => $Crop_Detail,
			'MealsAmount' => $Trial_Op_Exp_Amount,
			'HallTent_Amount' => $Fertilizer_Amount,
			'Gift_Amount' => $Consumable_Amount,
			'OthCharge_Amount' => $Miscellaneous_Amount,
			'Total_Amount' => $Amount,
			'Remark' => $Remark,
			'Group_Id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('Scan_Id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('Scan_Id', $Scan_Id)->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
			$this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array('Is_Rejected' => 'N', 'Reject_Date' => NULL, 'Edit_Permission' => 'N'));
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
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">RST/OFD Record added successfully</div>');
			redirect('punch');
		}
	}

	public function save_postage_courier()
	{

		$Scan_Id = $this->input->post('Scan_Id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);

		$Booking_Date = $this->input->post('Booking_Date');
		$Docket_No = $this->input->post('Docket_No');
		$Weight_Charged = $this->input->post('Weight_Charged');
		$Provider_Name = $this->input->post('Provider_Name');
		$Sender_Name = $this->input->post('Sender_Name');
		$Receiver_Name = $this->input->post('Receiver_Name');
		$Sender_Address = $this->input->post('Sender_Address');
		$Receiver_Address = $this->input->post('Receiver_Address');
		$Remark = $this->input->post('Remark');
		$data = array(
			'Scan_Id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'BillDate' => $Booking_Date,
			'File_No' => $Docket_No,
			'AgentName' => $Provider_Name,
			'FromName' => $Sender_Name,
			'ToName' => $Receiver_Name,
			'Loc_Add' => $Sender_Address,
			'Related_Address' => $Receiver_Address,
			'Total_Amount' => $Weight_Charged,
			'Remark' => $Remark,
			'Group_Id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('Scan_Id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('Scan_Id', $Scan_Id)->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Weight_Charged, 'Comment' => $Remark));
			$this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array('Is_Rejected' => 'N', 'Reject_Date' => NULL, 'Edit_Permission' => 'N'));
		} else {
			//Insert New Record
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
			$this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Weight_Charged, 'Comment' => $Remark));
		}

		$this->customlib->update_file_path($Scan_Id);

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
			redirect('punch');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Postage Courier added successfully</div>');
			redirect('punch');
		}
	}

	public function Save_Meals()
	{
		$Scan_Id = $this->input->post('Scan_Id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$EmployeeID = $this->input->post('Employee');
		$Emp_Code = $this->input->post('Emp_Code');
		$Employee_Name = $this->customlib->getEmployeeNameById($EmployeeID);
		$Date = $this->input->post('Date');
		$InvoiceNo = $this->input->post('InvoiceNo');
		$Hotel = $this->input->post('Hotel');
		$Hotel_Address = $this->input->post('Hotel_Address');
		$Detail = $this->input->post('Detail');
		$Amount = $this->input->post('Amount');
		$Remark = $this->input->post('Remark');
		$Location = $this->input->post('Location');
		$data = array(
			'Scan_Id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'File_No' => $InvoiceNo,
			'FileName' => $Detail,
			'EmployeeID' => $EmployeeID,
			'EmployeeCode' => $Emp_Code,
			'Employee_Name' => $Employee_Name,
			'BillDate' => $Date,
			'Total_Amount' => $Amount,
			'Hotel_Name' => $Hotel,
			'Hotel_Address' => $Hotel_Address,
			'Remark' => $Remark,
			'Group_Id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
			'Loc_Name' => $Location,
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('Scan_Id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('Scan_Id', $Scan_Id)->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
			$this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array('Is_Rejected' => 'N', 'Reject_Date' => NULL, 'Edit_Permission' => 'N'));
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
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Meals Record added successfully</div>');
			redirect('punch');
		}
	}

	public function save_lodging()
	{


		$data = [
			'Scan_Id' => $this->input->post('Scan_Id'),
			'DocType' => $this->customlib->getDocType($this->input->post('DocTypeId')),
			'DocTypeId' => $this->input->post('DocTypeId'),
			'File_No' => $this->input->post('Bill_No'),
			'BillDate' => $this->input->post('Bill_Date'),
			'CompanyID' => $this->input->post('Billing_Name'),
			'Company' => $this->customlib->getCompanyNameById($this->input->post('Billing_Name')),
			'Related_Address' => $this->input->post('Billing_Address'),
			'Hotel' => $this->input->post('Hotel'),
			'Hotel_Name' => $this->customlib->getHotelNameById($this->input->post('Hotel')),
			'Hotel_Address' => $this->input->post('Hotel_Address'),
			'Particular' => $this->input->post('Billing_Instruction'),
			'RegNo' => $this->input->post('Booking_Id'),
			'FromDateTime' => $this->input->post('Arrival_Date'),
			'ToDateTime' => $this->input->post('Departure_Date'),
			'Period' => $this->input->post('Duration'),
			'ReferenceNo' => $this->input->post('No_Room'),
			'TravelClass' => $this->input->post('Room_Type'),
			'TariffPlan' => $this->input->post('Room_Rate'),
			'Loc_Name' => $this->input->post('Meal'),
			'SubTotal' => $this->input->post('Amount'),
			'OthCharge_Amount' => $this->input->post('Other_Charge'),
			'Total_Discount' => $this->input->post('Discount'),
			'GSTIN' => $this->input->post('Gst'),
			'Grand_Total' => $this->input->post('Grand_Total'),
			'Remark' => $this->input->post('Remark'),
			'Group_Id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
			'Loc_Name' => $this->input->post('Location'),
		];

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		$isExistingRecord = $this->customlib->check_punchfile($data['Scan_Id']);
		if ($isExistingRecord) {
			//Update Existing Record
			$this->db->where('Scan_Id', $data['Scan_Id'])->update('punchfile', $data);
			$FileID = $this->db->where('Scan_Id', $data['Scan_Id'])->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $data['Grand_Total'], 'Comment' => $data['Remark']));
			$this->db->where('Scan_Id', $data['Scan_Id'])->delete('lodging_employee');
			$array = array();
			for ($i = 0; $i < count($this->input->post('Employee')); $i++) {
				$array[$i] = array(
					'Scan_Id' => $data['Scan_Id'],
					'emp_id' => $this->input->post('Employee')[$i],
					'emp_code' => $this->input->post('EmpCode')[$i],
					'emp_name' => $this->customlib->getEmployeeNameById($this->input->post('Employee')[$i]),

				);
			}
			$this->db->insert_batch('lodging_employee', $array);
			$this->db->where('Scan_Id', $data['Scan_Id'])->update('scan_file', array('Is_Rejected' => 'N', 'Reject_Date' => NULL, 'Edit_Permission' => 'N'));
		} else {
			//Insert New Record
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
			$this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $data['Grand_Total'], 'Comment' => $data['Remark']));
			$array = array();
			for ($i = 0; $i < count($this->input->post('Employee')); $i++) {
				$array[$i] = array(
					'Scan_Id' => $data['Scan_Id'],
					'emp_id' => $this->input->post('Employee')[$i],
					'emp_code' => $this->input->post('EmpCode')[$i],
					'emp_name' => $this->customlib->getEmployeeNameById($this->input->post('Employee')[$i]),

				);
			}
			$this->db->insert_batch('lodging_employee', $array);
		}
		$this->customlib->update_file_path($data['Scan_Id']);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$message = '<div class="alert alert-danger text-left">Something Went Wrong</div>';
		} else {
			$message = '<div class="alert alert-success text-left">Lodging Record added successfully</div>';
		}
		$this->session->set_flashdata('message', $message);
		redirect('punch');
	}

	public function Save_Dealer_Meeting()
	{

		$Scan_Id = $this->input->post('Scan_Id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$Bill_Date = $this->input->post('Bill_Date');
		$Crop = $this->input->post('Crop');
		$Crop_Detail = $this->input->post('Crop_Detail');
		$Meals = $this->input->post('Meals');
		$Tent = $this->input->post('Tent');
		$Gift = $this->input->post('Gift');
		$AV = $this->input->post('AV');
		$Other = $this->input->post('Other');
		$Amount = $this->input->post('Total_Amount');
		$Remark = $this->input->post('Remark');
		$data = array(
			'Scan_Id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'BillDate' => $Bill_Date,
			'File_Type' => $Crop,
			'CropDetails' => $Crop_Detail,
			'MealsAmount' => $Meals,
			'HallTent_Amount' => $Tent,
			'Gift_Amount' => $Gift,
			'AVTent_Amount' => $AV,
			'OthCharge_Amount' => $Other,
			'Total_Amount' => $Amount,
			'Remark' => $Remark,
			'Group_Id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('Scan_Id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('Scan_Id', $Scan_Id)->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
			$this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array('Is_Rejected' => 'N', 'Reject_Date' => NULL, 'Edit_Permission' => 'N'));
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
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Dealer Meeting Record added successfully</div>');
			redirect('punch');
		}
	}

	public function Save_Electricity()
	{

		$Scan_Id = $this->input->post('Scan_Id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$Biller_Name = $this->input->post('Biller_Name');
		$BP_No = $this->input->post('BP_No');
		$Period = $this->input->post('Period');
		$Meter_No = $this->input->post('Meter_No');
		$Bill_Date = $this->input->post('Bill_Date');
		$Bill_No = $this->input->post('Bill_No');
		$Previous_Reading = $this->input->post('Previous_Reading');
		$Current_Reading = $this->input->post('Current_Reading');
		$Unit_Consumed = $this->input->post('Unit_Consumed');
		$Last_Date = $this->input->post('Last_Date');
		$Payment_Mode = $this->input->post('Payment_Mode');
		$Amount = $this->input->post('Bill_Amount');
		$Payment_Amount = $this->input->post('Payment_Amount');
		$Remark = $this->input->post('Remark');
		$data = array(
			'Scan_Id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'Related_Person' => $Biller_Name,
			'ReferenceNo' => $BP_No,
			'Period' => $Period,
			'MeterNumber' => $Meter_No,
			'BillDate' => $Bill_Date,
			'File_No' => $Bill_No,
			'LastDateOfPayment' => $Last_Date,
			'PreviousReading' => $Previous_Reading,
			'CurrentReading' => $Current_Reading,
			'UnitsConsumed' => $Unit_Consumed,
			'NatureOfPayment' => $Payment_Mode,
			'Total_Amount' => $Amount,
			'Payment_Amount' => $Payment_Amount,
			'Remark' => $Remark,
			'Group_Id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
			'Loc_Name' => $this->input->post('Location'),
			'PremiumDate' => $this->input->post('PaymentDate'),
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('Scan_Id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('Scan_Id', $Scan_Id)->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
			$this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array('Is_Rejected' => 'N', 'Reject_Date' => NULL, 'Edit_Permission' => 'N'));
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
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">Electricity Bill added successfully</div>');
			redirect('punch');
		}
	}

	public function Save_FD_FV()
	{

		$Scan_Id = $this->input->post('Scan_Id');
		$DocTypeId = $this->input->post('DocTypeId');
		$DocType = $this->customlib->getDocType($DocTypeId);
		$Bill_Date = $this->input->post('Bill_Date');
		$Vegetable = $this->input->post('Vegetable');
		$No_Farmer = $this->input->post('No_Farmer');
		$DTP = $this->input->post('DTP');
		$HVC = $this->input->post('HVC');
		$AVT = $this->input->post('AVT');
		$SNK = $this->input->post('SNK');
		$Other = $this->input->post('Other');
		$Amount = $this->input->post('Total_Amount');
		$Remark = $this->input->post('Remark');
		$data = array(
			'Scan_Id' => $Scan_Id,
			'DocType' => $DocType,
			'DocTypeId' => $DocTypeId,
			'BillDate' => $Bill_Date,
			'File_Type' => $Vegetable,
			'NoOfFarmers' => $No_Farmer,
			'Dealers_TradePartners' => $DTP,
			'HiredVehicle_Amount' => $HVC,
			'AVTent_Amount' => $AVT,
			'Snacks_Amount' => $SNK,
			'OthCharge_Amount' => $Other,
			'Total_Amount' => $Amount,
			'Remark' => $Remark,
			'Group_Id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		);

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		if ($this->customlib->check_punchfile($Scan_Id) == true) {
			//Update Existing Record
			$this->db->where('Scan_Id', $Scan_Id)->update('punchfile', $data);
			$FileID = $this->db->where('Scan_Id', $Scan_Id)->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
			$this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array('Is_Rejected' => 'N', 'Reject_Date' => NULL, 'Edit_Permission' => 'N'));
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
			$this->session->set_flashdata('message', '<div class="alert alert-success text-left">FD_FV added successfully</div>');
			redirect('punch');
		}
	}

	function getLodgingEmployee()
	{
		$Scan_Id = $this->input->post('Scan_Id');

		$result = $this->db->select('*')->from('lodging_employee')->where('Scan_Id', $Scan_Id)->get()->result_array();
		if (!empty($result)) {
			echo json_encode(array('status' => 200, 'data' => $result));
		} else {
			echo json_encode(array('status' => 400, 'msg' => 'No Record Found'));
		}
	}
}
