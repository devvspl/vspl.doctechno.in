<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Bank_ctrl extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scan_model');
    }
    public function create() {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Bank_Name = $this->input->post('Bank_Name');
        $CompanyId = $this->input->post('Company');
        $Comapny = $this->customlib->getCompanyNameById($CompanyId);
        $Account_Name = $this->input->post('Account_Name');
        $Account_No = $this->input->post('Account_No');
        $Perid = $this->input->post('Period');
        $Financial_Year = $this->input->post('Financial_Year');
        $Remark = $this->input->post('Remark');
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'FromName' => $Bank_Name, 'ToName' => $Comapny, 'BankName' => $Bank_Name, 'Company' => $Comapny, 'CompanyId' => $CompanyId, 'BankAccountNo' => $Account_No, 'CustomerName' => $Account_Name, 'Related_Person' => $Account_Name, 'PeriodDuration' => $Perid, 'FinYear' => $Financial_Year, 'Remark' => $Remark, 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Bank Statement added successfully</div>');
            redirect('punch');
        }
    }
    public function save_bank_loan_paper() {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Bank_Name = $this->input->post('Bank_Name');
        $CompanyId = $this->input->post('Company');
        $Comapny = $this->customlib->getCompanyNameById($CompanyId);
        $Branch = $this->input->post('Branch');
        $Sanction_Date = $this->input->post('Sanction_Date');
        $Sanction_Amount = $this->input->post('Sanction_Amount');
        $Period = $this->input->post('Period');
        $Due_Date = $this->input->post('Due_Date');
        $Renewal_Date = $this->input->post('Renewal_Date');
        $Type_Doc = $this->input->post('Type_Doc');
        $Paper_Submitted = $this->input->post('Paper_Submitted');
        $Remark = $this->input->post('Remark');
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'FromName' => $Comapny, 'ToName' => $Bank_Name, 'Company' => $Comapny, 'CompanyID' => $CompanyId, 'BankName' => $Bank_Name, 'BankAddress' => $Branch, 'BillDate' => $Sanction_Date, 'Total_Amount' => $Sanction_Amount, 'Period' => $Period, 'DueDate' => $Due_Date, 'RenewalDate' => $Renewal_Date, 'File_Type' => $Type_Doc, 'PaperSubmitted' => $Paper_Submitted, 'Remark' => $Remark, 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile($Scan_Id) == true) {
            //Update Existing Record
            $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Sanction_Amount, 'Comment' => $Remark));
            $this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
        } else {
            //Insert New Record
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Sanction_Amount, 'Comment' => $Remark));
        }
        $this->customlib->update_file_path($Scan_Id);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
            redirect('punch');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Bank Loan Paper added successfully</div>');
            redirect('punch');
        }
    }
    public function save_cash()
    {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Type = $this->input->post('Type');
        $Date = $this->input->post('Date');
        $Bank_Name = $this->input->post('Bank_Name');
        $Branch = $this->input->post('Branch');
        $Account_No = $this->input->post('Account_No');
        $Beneficiary_Name = $this->input->post('Beneficiary_Name');
        $Amount = $this->input->post('Amount');
        $Remark = $this->input->post('Remark');
        $submit = $this->input->post('submit'); // '1' for Submit, empty/0 for Draft
    
        $data = array(
            'scan_id' => $Scan_Id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'File_Type' => $Type,
            'BillDate' => $Date,
            'BankName' => $Bank_Name,
            'BankAddress' => $Branch,
            'BankAccountNo' => $Account_No,
            'Related_Person' => $Beneficiary_Name,
            'Total_Amount' => $Amount,
            'Remark' => $Remark,
            'group_id' => $this->session->userdata('group_id'),
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
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
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Cash Deposit/Withdrawal submitted successfully</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Saved as draft</div>');
            }
        }
    
        redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
    }
    
    public function Save_RTGS_NEFT() {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Type = $this->input->post('Type');
        $Date = $this->input->post('Date');
        $Bank_Name = $this->input->post('Bank_Name');
        $Branch = $this->input->post('Branch');
        $Account_No = $this->input->post('Account_No');
        $IFSC_Code = $this->input->post('IFSC_Code');
        $Beneficiary_Name = $this->input->post('Beneficiary_Name');
        $Amount = $this->input->post('Amount');
        $Remark = $this->input->post('Remark');
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'File_Type' => $Type, 'BillDate' => $Date, 'BankName' => $Bank_Name, 'BankAddress' => $Branch, 'BankAccountNo' => $Account_No, 'BankIfscCode' => $IFSC_Code, 'Related_Person' => $Beneficiary_Name, 'Total_Amount' => $Amount, 'Remark' => $Remark, 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">RTGS/NEFT Record added successfully</div>');
            redirect('punch');
        }
    }
    // public function save_cash_voucher()
    // {
    //     $Scan_Id = $this->input->post('scan_id');
    //     $DocTypeId = $this->input->post('DocTypeId');
    //     $DocType = $this->customlib->getDocType($DocTypeId);
    // 	$CompanyID = $this->input->post('CompanyID');
    // 	$Comapny = $this->customlib->getCompanyNameById($CompanyID);
    //     $Voucher_No = $this->input->post('Voucher_No');
    //     $Voucher_Date = $this->input->post('Voucher_Date');
    //     $Payee = $this->input->post('Payee');
    //     $Payer = $this->input->post('Payer');
    //     $Particular = $this->input->post('Particular');
    //     $Loc_Name = $this->input->post('location_id');
    //     $Amount = $this->input->post('Amount');
    //     $Remark = $this->input->post('Remark');
    //     $data = array(
    //         'scan_id' => $Scan_Id,
    //         'DocType' => $DocType,
    //         'DocTypeId' => $DocTypeId,
    //         'File_No' => $Voucher_No,
    // 		'CompanyID' => $CompanyID,
    // 		'Company' => $Comapny,
    //         'BillDate' => $Voucher_Date,
    //         'Related_Person' => $Payee,
    //         'AgentName'=>$Payer,
    //         'Loc_Name' => $Loc_Name,
    //         'FileName' => $Particular,
    //         'Total_Amount' => $Amount,
    //         'Remark' => $Remark,
    //         'group_id' => $this->session->userdata('group_id'),
    //         'Created_By' => $this->session->userdata('user_id'),
    //         'Created_Date' => date('Y-m-d H:i:s'),
    //     );
    //     $this->db->trans_start();
    //     $this->db->trans_strict(FALSE);
    //     if ($this->customlib->check_punchfile($Scan_Id) == true) {
    //         //Update Existing Record
    //         $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
    //         $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
    //         $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
    //         $this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
    //     } else {
    //         //Insert New Record
    //         $this->db->insert('punchfile', $data);
    //         $insert_id = $this->db->insert_id();
    //         $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Amount, 'Comment' => $Remark));
    //     }
    //     $this->customlib->update_file_path($Scan_Id);
    //     $this->db->trans_complete();
    //     if ($this->db->trans_status() === FALSE) {
    //         $this->db->trans_rollback();
    //         $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
    //         redirect('punch');
    //     } else {
    //         $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Cash Voucher added successfully</div>');
    //         redirect('punch');
    //     }
    // }
    public function save_cash_voucher() {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $CompanyID = $this->input->post('CompanyID');
        $Comapny = $this->customlib->getCompanyNameById($CompanyID);
        $Voucher_No = $this->input->post('Voucher_No');
        $Voucher_Date = $this->input->post('Voucher_Date');
        $Payee = $this->input->post('Payee');
        $Payer = $this->input->post('Payer');
        $Particular = $this->input->post('Particular');
        $Loc_Name = $this->input->post('location_id');
        $Amount = $this->input->post('Amount');
        $Remark = $this->input->post('Remark');
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'finance_total_Amount' => $Amount, 'DocTypeId' => $DocTypeId, 'File_No' => $Voucher_No, 'CompanyID' => $CompanyID, 'Company' => $Comapny, 'BillDate' => $Voucher_Date, 'Related_Person' => $Payee, 'AgentName' => $Payer, 'Loc_Name' => $Loc_Name, 'FileName' => $Particular, 'Total_Amount' => $Amount, 'Remark' => $Remark, 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
        if ($this->session->userdata('group_id') == 16) {
            if ($this->customlib->check_punchfile($Scan_Id)) {
                $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
                $FileID = $this->db->select('FileID')->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
                $this->db->where('FileID', $FileID)->update('sub_punchfile', ['Amount' => '-' . $Amount, 'Comment' => $Remark]);
                $this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", ['is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N']);
                $query = $this->db->where("Scan_Id", $Scan_Id)->update("y{$this->year_id}_scan_file", ['finance_punch_action_status' => 'N', 'punched_by' => $this->session->userdata("user_id"), 'punched_date' => date('Y-m-d H:i:s') ]);
            } else {
                $this->db->insert('punchfile', $data);
                $insert_id = $this->db->insert_id();
                $this->db->insert('sub_punchfile', ['FileID' => $insert_id, 'Amount' => '-' . $Amount, 'Comment' => $Remark]);
                $query = $this->db->where("Scan_Id", $Scan_Id)->update("y{$this->year_id}_scan_file", ['finance_punch_action_status' => 'N', 'punched_by' => $this->session->userdata("user_id"), 'punched_date' => date('Y-m-d H:i:s') ]);
            }
            if ($query) {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Cash Voucher ' . ($this->customlib->check_punchfile($Scan_Id) ? 'updated' : 'added') . ' successfully</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something went wrong</div>');
            }
            redirect('punch');
        } else {
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
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Cash Voucher added successfully</div>');
                redirect('punch');
            }
        }
    }
    public function save_cheque() {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Bank_Name = $this->input->post('Bank_Name');
        $Branch_Name = $this->input->post('Branch_Name');
        $IFSC_Code = $this->input->post('IFSC_Code');
        $Account_No = $this->input->post('Account_No');
        $Cheque_No = $this->input->post('Cheque_No');
        $Cheque_Date = $this->input->post('Cheque_Date');
        $Payee = $this->input->post('Payee');
        $Amount = $this->input->post('Cheque_Amount');
        $Remark = $this->input->post('Remark');
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'BankName' => $Bank_Name, 'BankAddress' => $Branch_Name, 'BankIfscCode' => $IFSC_Code, 'BankAccountNo' => $Account_No, 'ChequeNo' => $Cheque_No, 'File_Date' => $Cheque_Date, 'Related_Person' => $Payee, 'Total_Amount' => $Amount, 'Remark' => $Remark, 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Caheque added successfully</div>');
            redirect('punch');
        }
    }
    public function save_fixed_deposit() {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Bank_Name = $this->input->post('Bank_Name');
        $Deposit_Amount = $this->input->post('Deposit_Amount');
        $Interest = $this->input->post('Interest');
        $Account_No = $this->input->post('Account_No');
        $Maturity_Amount = $this->input->post('Maturity_Amount');
        $Start_Date = $this->input->post('Start_Date');
        $End_Date = $this->input->post('End_Date');
        $Period = $this->input->post('Period');
        $Remark = $this->input->post('Remark');
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'BankName' => $Bank_Name, 'DepositAccNo' => $Account_No, 'MaturityAmount' => $Maturity_Amount, 'FromDateTime' => $Start_Date, 'ToDateTime' => $End_Date, 'Period' => $Period, 'Total_Amount' => $Deposit_Amount, 'RateOfInterest' => $Interest, 'Remark' => $Remark, 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile($Scan_Id) == true) {
            //Update Existing Record
            $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Deposit_Amount, 'Comment' => $Remark));
            $this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
        } else {
            //Insert New Record
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Deposit_Amount, 'Comment' => $Remark));
        }
        $this->customlib->update_file_path($Scan_Id);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
            redirect('punch');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Fixed Deposit added successfully</div>');
            redirect('punch');
        }
    }
    public function save_cash_receipt() {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $CompanyID = $this->input->post('CompanyID');
        $Comapny = $this->customlib->getCompanyNameById($CompanyID);
        $Receipt_No = $this->input->post('Receipt_No');
        $Receipt_Date = $this->input->post('Receipt_Date');
        $Receiver = $this->input->post('Receiver');
        $ReceivedFrom = $this->input->post('ReceivedFrom');
        $Particular = $this->input->post('Particular');
        $Loc_Name = $this->input->post('location_id');
        $Amount = $this->input->post('Amount');
        $Remark = $this->input->post('Remark');
        $data = array('scan_id' => $Scan_Id, 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'File_No' => $Receipt_No, 'BillDate' => $Receipt_Date, 'CompanyID' => $CompanyID, 'Company' => $Comapny, 'Related_Person' => $Receiver, 'FromName' => $ReceivedFrom, 'FileName' => $Particular, 'Loc_Name' => $Loc_Name, 'Total_Amount' => $Amount, 'Remark' => $Remark, 'group_id' => $this->session->userdata('group_id'), 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Cash Receipt added successfully</div>');
            redirect('punch');
        }
    }
}
