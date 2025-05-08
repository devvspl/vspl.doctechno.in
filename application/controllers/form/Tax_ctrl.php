<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tax_ctrl extends CI_Controller
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
        $Institution_Name = $this->input->post('Institution_Name');
        $CompanyId = $this->input->post('Company');
        $Company = $this->customlib->getCompanyNameById($CompanyId);
        $FormNo_Type = $this->input->post('FormNo_Type');
        $Certificate_Issue_Date = $this->input->post('Certificate_Issue_Date');
        $Valid_Upto = $this->input->post('Valid_Upto');
        $Remark = $this->input->post('Remark');

        $data = array(
            'Scan_Id' => $Scan_Id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,

            'Company' => $Company,
            'CompanyID' => $CompanyId,
            'PartyName' => $Institution_Name,
            'File_Type' => $FormNo_Type,
            'FromDateTime' => $Certificate_Issue_Date,
            'ToDateTime' => $Valid_Upto,
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Tax Credit Document added successfully</div>');
            redirect('punch');
        }
    }

    public function Save_IT_Return()
    {
        $Scan_Id = $this->input->post('Scan_Id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $CompanyId = $this->input->post('Company');
        $Comapny = $this->customlib->getCompanyNameById($CompanyId);
        $Financial_Year   = $this->input->post('Financial_Year');
        $Assessment_Year   = $this->input->post('Assessment_Year');
        $Filling_Date   = $this->input->post('Filling_Date');
        $Acknowledge_No   = $this->input->post('Acknowledge_No');
        $Amount = $this->input->post('Amount');
        $Remark = $this->input->post('Remark');
        $data = array(
            'Scan_Id' => $Scan_Id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'Company' => $Comapny,
            'CompanyID' => $CompanyId,
            'Financial_Year' => $Financial_Year,
            'BillYear' => $Assessment_Year,
            'File_Date' => $Filling_Date,
            'File_No' => $Acknowledge_No,
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">IT Return added successfully</div>');
            redirect('punch');
        }
    }

    public function Save_Income_Tax_TDS()
    {
        $Scan_Id = $this->input->post('Scan_Id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $submit = $this->input->post('submit');
    
        $CompanyId = $this->input->post('Company');
        $Comapny = $this->customlib->getCompanyNameById($CompanyId);
        $Section = $this->input->post('Section');
        $Payment_Nature = $this->input->post('Payment_Nature');
        $BSR_Code = $this->input->post('BSR_Code');
        $Challan_No = $this->input->post('Challan_No');
        $Challan_Date = $this->input->post('Challan_Date');
        $Ref_No = $this->input->post('Ref_No');
        $Bank_Name = $this->input->post('Bank_Name');
        $Assessment_Year = $this->input->post('Assessment_Year');
        $Amount = $this->input->post('Amount');
        $Remark = $this->input->post('Remark');
    
        $data = array(
            'Scan_Id' => $Scan_Id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'Company' => $Comapny,
            'CompanyID' => $CompanyId,
            'Financial_Year' => $Assessment_Year,
            'Section' => $Section,
            'BSRCode' => $BSR_Code,
            'NatureOfPayment' => $Payment_Nature,
            'File_No' => $Challan_No,
            'File_Date' => $Challan_Date,
            'ReferenceNo' => $Ref_No,
            'BankName' => $Bank_Name,
            'Total_Amount' => $Amount,
            'Remark' => $Remark,
            'Group_Id' => $this->session->userdata('group_id'),
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
        );
    
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
    
        if ($this->customlib->check_punchfile($Scan_Id)) {
            // Update Existing Record
            $this->db->where('Scan_Id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('Scan_Id', $Scan_Id)->get('punchfile')->row()->FileID;
    
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array(
                'Amount' => '-' . $Amount,
                'Comment' => $Remark
            ));
    
            if ($submit) {
                $this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array(
                    'Is_Rejected' => 'N',
                    'Reject_Date' => NULL,
                    'Edit_Permission' => 'N',
                    'finance_punch' => 'N'
                ));
            }
        } else {
            // Insert New Record
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
    
            $this->db->insert('sub_punchfile', array(
                'FileID' => $insert_id,
                'Amount' => '-' . $Amount,
                'Comment' => $Remark
            ));
    
            if ($submit) {
                $this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array(
                    'Is_Rejected' => 'N',
                    'Reject_Date' => NULL,
                    'Edit_Permission' => 'N',
                    'finance_punch' => 'N'
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
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Income Tax TDS submitted successfully</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Saved as draft</div>');
            }
        }
    
        redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
    }
    
}
