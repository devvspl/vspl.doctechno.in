<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subsidy_ctrl extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function create()
    {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);

        $Application_Date   = $this->input->post('Application_Date');
        $Received_Date   = $this->input->post('Received_Date');
        $Institution_Name   = $this->input->post('Institution_Name');
        $CompanyId = $this->input->post('Company');
        $Comapny = $this->customlib->getCompanyNameById($CompanyId);
        $Benefit_Type   = $this->input->post('Benefit_Type');
        $Bank_Name   = $this->input->post('Bank_Name');
        $Branch_Name   = $this->input->post('Branch_Name');
        $IFSC_Code   = $this->input->post('IFSC_Code');
        $Bank_Account_No   = $this->input->post('Bank_Account_No');
        $Amount  = $this->input->post('Amount');
        $Remark = $this->input->post('Remark');
        $data = array(
            'scan_id' => $Scan_Id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'FromDateTime' => $Application_Date,
            'ToDateTime' => $Received_Date,
            'Loc_Name' => $Institution_Name,
            'Company' => $Comapny,
            'CompanyID' => $CompanyId,
            'File_Type' => $Benefit_Type,
            'BankName' => $Bank_Name,
            'BankAddress' => $Branch_Name,
            'BankIfscCode' => $IFSC_Code,
            'BankAccountNo' => $Bank_Account_No,
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
            $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Telephone Bill added successfully</div>');
            redirect('punch');
        }
    }
}
