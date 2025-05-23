<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Miscellaneous_ctrl extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scan_model');
    }
    public function create() {
        $submit = $this->input->post('submit');  // This will check if the action is 'submit' or 'draft'
    
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $File_Date = $this->input->post('File_Date');
        $VoucherNo = $this->input->post('VoucherNo');
        $Voucher_Date = $this->input->post('Voucher_Date');
        $Particular = $this->input->post('Particular');
        $Amount = $this->input->post('Amount');
        $CompanyId = $this->input->post('Company');
        $Comapny = $this->customlib->getCompanyNameById($CompanyId);
        $VendorID = $this->input->post('Vendor');
        $Vendor = $this->customlib->getCompanyNameById($VendorID);
        $Location = $this->input->post('location_id');
        $Remark = $this->input->post('Remark');
        
        // Prepare the data to be inserted or updated
        $data = array(
            'scan_id' => $Scan_Id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'File_Date' => $File_Date,
            'File_No' => $VoucherNo,
            'RegPurDate' => $Voucher_Date,
            'TotalAmount' => $Amount,
            'Additional_Exposure' => $Particular,
            'Company' => $Comapny,
            'CompanyID' => $CompanyId,
            'Vendor' => $Vendor,
            'VendorID' => $VendorID,
            'location_id' => $Location,
            'Remark' => $Remark,
            'group_id' => $this->session->userdata('group_id'),
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
        );
        
        // Start the transaction
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
    
        if ($this->customlib->check_punchfile2($Scan_Id) == true) {
            // Update the existing record
            $this->db->where('scan_id', $Scan_Id)->update('punchfile2', $data);
    
            // Handle the 'submit' or 'draft' action
            if ($submit) {
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array(
                    'is_rejected' => 'N', 
                    'reject_date' => NULL, 
                    'has_edit_permission' => 'N', 
                    'finance_punch_action_status' => 'N'  // Set finance_punch to 'N' when submitting
                ));
            } else {
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array(
                    'is_rejected' => 'N', 
                    'reject_date' => NULL, 
                    'has_edit_permission' => 'Y',  // Allow editing for draft
                   
                ));
            }
    
        } else {
            // Insert new record
            $this->db->insert('punchfile2', $data);
            
            // Handle the 'submit' or 'draft' action after insertion
            if ($submit) {
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array(
                    'is_rejected' => 'N', 
                    'reject_date' => NULL, 
                    'has_edit_permission' => 'N', 
                    'finance_punch_action_status' => 'N'  // Set finance_punch to 'N' when submitting
                ));
            } else {
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array(
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
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Miscellaneous Record submitted successfully</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Miscellaneous Record saved as draft</div>');
            }
        }
    
        // Redirect based on whether it's submitted or saved as a draft
        redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
    }
    
    public function save_rst_ofd() {
        $Scan_Id = $this->input->post('scan_id');
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
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'File_Type' => $Crop, 'BillDate' => $Date, 'CropDetails' => $Crop_Detail, 'MealsAmount' => $Trial_Op_Exp_Amount, 'HallTent_Amount' => $Fertilizer_Amount, 'Gift_Amount' => $Consumable_Amount, 'OthCharge_Amount' => $Miscellaneous_Amount, 'Total_Amount' => $Amount, 'Remark' => $Remark, 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile($Scan_Id) == true) {
            $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
            $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
        } else {
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
    public function save_postage_courier() {
        $Scan_Id = $this->input->post('scan_id');
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
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'BillDate' => $Booking_Date, 'File_No' => $Docket_No, 'AgentName' => $Provider_Name, 'FromName' => $Sender_Name, 'ToName' => $Receiver_Name, 'Loc_Add' => $Sender_Address, 'Related_Address' => $Receiver_Address, 'Total_Amount' => $Weight_Charged, 'Remark' => $Remark, 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile($Scan_Id) == true) {
            $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Weight_Charged, 'Comment' => $Remark));
            $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
        } else {
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
    public function Save_Meals() {
        $submit = $this->input->post('submit');  // This will check if the action is 'submit' or 'draft'
    
        $Scan_Id = $this->input->post('scan_id');
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
        $Location = $this->input->post('location_id');
        
        $data = array(
            'scan_id' => $Scan_Id,
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
            'group_id' => $this->session->userdata('group_id'),
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
            'Loc_Name' => $Location,
        );
        
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
    
        if ($this->customlib->check_punchfile($Scan_Id) == true) {
            // Update Existing Record
            $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
    
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
            
            // Update scan file status based on submit or draft
            if ($submit) {
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array(
                    'is_rejected' => 'N', 
                    'reject_date' => NULL, 
                    'has_edit_permission' => 'N', 
                    'finance_punch_action_status' => 'N'  // Set finance_punch to 'N' when submitting
                ));
            } else {
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array(
                    'is_rejected' => 'N', 
                    'reject_date' => NULL, 
                    'has_edit_permission' => 'Y',  // Allow editing for draft
                 
                ));
            }
    
        } else {
            // Insert New Record
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Amount, 'Comment' => $Remark));
            
            // Set scan file status based on submit or draft
            if ($submit) {
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array(
                    'is_rejected' => 'N', 
                    'reject_date' => NULL, 
                    'has_edit_permission' => 'N', 
                    'finance_punch_action_status' => 'N'  // Set finance_punch to 'N' when submitting
                ));
            } else {
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array(
                    'is_rejected' => 'N', 
                    'reject_date' => NULL, 
                    'has_edit_permission' => 'Y',  // Allow editing for draft
                  
                ));
            }
        }
    
        $this->customlib->update_file_path($Scan_Id);
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something Went Wrong</div>');
        } else {
            if ($submit) {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Meals Record submitted successfully</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Meals Record saved as draft</div>');
            }
        }
        
        redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
    }
    
    
    public function save_lodging() {
        $submit = $this->input->post('submit');
        $data = ['scan_id' => $this->input->post('scan_id'), 'DocType' => $this->customlib->getDocType($this->input->post('DocTypeId')), 'DocTypeId' => $this->input->post('DocTypeId'), 'File_No' => $this->input->post('Bill_No'), 'BillDate' => $this->input->post('Bill_Date'), 'CompanyID' => $this->input->post('Billing_Name'), 'Company' => $this->customlib->getCompanyNameById($this->input->post('Billing_Name')), 'Related_Address' => $this->input->post('Billing_Address'), 'Hotel' => $this->input->post('Hotel'), 'Hotel_Name' => $this->customlib->getHotelNameById($this->input->post('Hotel')), 'Hotel_Address' => $this->input->post('Hotel_Address'), 'Particular' => $this->input->post('Billing_Instruction'), 'RegNo' => $this->input->post('Booking_Id'), 'FromDateTime' => $this->input->post('Arrival_Date'), 'ToDateTime' => $this->input->post('Departure_Date'), 'Period' => $this->input->post('Duration'), 'ReferenceNo' => $this->input->post('No_Room'), 'TravelClass' => $this->input->post('Room_Type'), 'TariffPlan' => $this->input->post('Room_Rate'), 'Loc_Name' => $this->input->post('Meal'), 'SubTotal' => $this->input->post('Amount'), 'OthCharge_Amount' => $this->input->post('Other_Charge'), 'Total_Discount' => $this->input->post('Discount'), 'GSTIN' => $this->input->post('Gst'), 'Grand_Total' => $this->input->post('Grand_Total'), 'Remark' => $this->input->post('Remark'), 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'), 'Loc_Name' => $this->input->post('location_id'), ];
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        $isExistingRecord = $this->customlib->check_punchfile($data['scan_id']);
        if ($isExistingRecord) {
            $this->db->where('scan_id', $data['scan_id'])->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $data['scan_id'])->get('punchfile')->row()->FileID;
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $data['Grand_Total'], 'Comment' => $data['Remark']));
            $this->db->where('scan_id', $data['scan_id'])->delete('lodging_employee');
            $array = array();
            for ($i = 0;$i < count($this->input->post('Employee'));$i++) {
                $array[$i] = array('scan_id' => $data['scan_id'], 'emp_id' => $this->input->post('Employee') [$i], 'emp_code' => $this->input->post('EmpCode') [$i], 'emp_name' => $this->customlib->getEmployeeNameById($this->input->post('Employee') [$i]),);
            }
            $this->db->insert_batch('lodging_employee', $array);
            if ($submit) {
                $this->db->where('scan_id', $data['scan_id'])->update('y{$this->year_id}_scan_file', array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N', 'finance_punch_action_status' => 'N'));
            } else {
                $this->db->where('scan_id', $data['scan_id'])->update('y{$this->year_id}_scan_file', array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'Y'));
            }
        } else {
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $data['Grand_Total'], 'Comment' => $data['Remark']));
            $array = array();
            for ($i = 0;$i < count($this->input->post('Employee'));$i++) {
                $array[$i] = array('scan_id' => $data['scan_id'], 'emp_id' => $this->input->post('Employee') [$i], 'emp_code' => $this->input->post('EmpCode') [$i], 'emp_name' => $this->customlib->getEmployeeNameById($this->input->post('Employee') [$i]),);
            }
            $this->db->insert_batch('lodging_employee', $array);
            if ($submit) {
                $this->db->where('scan_id', $data['scan_id'])->update('y{$this->year_id}_scan_file', array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N', 'finance_punch_action_status' => 'N'));
            } else {
                $this->db->where('scan_id', $data['scan_id'])->update('y{$this->year_id}_scan_file', array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'Y'));
            }
        }
        $this->customlib->update_file_path($data['scan_id']);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something Went Wrong</div>');
        } else {
            if ($submit) {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Lodging Bill submitted successfully</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Saved as draft</div>');
            }
        }
        redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
    }
    public function Save_Dealer_Meeting() {
        $Scan_Id = $this->input->post('scan_id');
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
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'BillDate' => $Bill_Date, 'File_Type' => $Crop, 'CropDetails' => $Crop_Detail, 'MealsAmount' => $Meals, 'HallTent_Amount' => $Tent, 'Gift_Amount' => $Gift, 'AVTent_Amount' => $AV, 'OthCharge_Amount' => $Other, 'Total_Amount' => $Amount, 'Remark' => $Remark, 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile($Scan_Id) == true) {
            $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
            $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
        } else {
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
    public function Save_Electricity() {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $submit = $this->input->post('submit');
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'Related_Person' => $this->input->post('Biller_Name'), 'ReferenceNo' => $this->input->post('BP_No'), 'Period' => $this->input->post('Period'), 'MeterNumber' => $this->input->post('Meter_No'), 'BillDate' => $this->input->post('Bill_Date'), 'File_No' => $this->input->post('Bill_No'), 'LastDateOfPayment' => $this->input->post('Last_Date'), 'PreviousReading' => $this->input->post('Previous_Reading'), 'CurrentReading' => $this->input->post('Current_Reading'), 'UnitsConsumed' => $this->input->post('Unit_Consumed'), 'NatureOfPayment' => $this->input->post('Payment_Mode'), 'Total_Amount' => $this->input->post('Bill_Amount'), 'Payment_Amount' => $this->input->post('Payment_Amount'), 'Remark' => $this->input->post('Remark'), 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'), 'Loc_Name' => $this->input->post('location_id'), 'PremiumDate' => $this->input->post('PaymentDate'),);
        $Amount = $data['Total_Amount'];
        $Remark = $data['Remark'];
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile($Scan_Id)) {
            $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
            if ($submit) {
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N', 'finance_punch_action_status' => 'N'));
            }
        } else {
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Amount, 'Comment' => $Remark));
            if ($submit) {
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N', 'finance_punch_action_status' => 'N'));
            }
        }
        $this->customlib->update_file_path($Scan_Id);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something Went Wrong</div>');
        } else {
            if ($submit) {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Electricity Bill submitted successfully</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Saved as draft</div>');
            }
        }
        redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
    }
    public function Save_FD_FV() {
        $Scan_Id = $this->input->post('scan_id');
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
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'BillDate' => $Bill_Date, 'File_Type' => $Vegetable, 'NoOfFarmers' => $No_Farmer, 'Dealers_TradePartners' => $DTP, 'HiredVehicle_Amount' => $HVC, 'AVTent_Amount' => $AVT, 'Snacks_Amount' => $SNK, 'OthCharge_Amount' => $Other, 'Total_Amount' => $Amount, 'Remark' => $Remark, 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile($Scan_Id) == true) {
            $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
            $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
        } else {
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
    function getLodgingEmployee() {
        $Scan_Id = $this->input->post('scan_id');
        $result = $this->db->select('*')->from('lodging_employee')->where('scan_id', $Scan_Id)->get()->result_array();
        if (!empty($result)) {
            echo json_encode(array('status' => 200, 'data' => $result));
        } else {
            echo json_encode(array('status' => 400, 'msg' => 'No Record Found'));
        }
    }
}
