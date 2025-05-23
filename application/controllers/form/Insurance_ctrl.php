<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Insurance_ctrl extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scan_model');
    }

    public function save_insurance_policy()
    {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $submit = $this->input->post('submit');
    
        $Insurance_Type = $this->input->post('Insurance_Type');
        $Insurance_Company = $this->input->post('Insurance_Company');
        $Policy_Number = $this->input->post('Policy_Number');
        $Policy_Date = $this->input->post('Policy_Date');
        $From_Date = $this->input->post('From_Date');
        $To_Date = $this->input->post('To_Date');
        $Vehicle_No = $this->input->post('Vehicle_No');
        $Location = $this->input->post('location_id');
        $Amount = $this->input->post('Premium_Amount');
        $Remark = $this->input->post('Remark');
    
        $data = array(
            'scan_id' => $Scan_Id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'File_Type' => $Insurance_Type,
            'AgentName' => $Insurance_Company,
            'File_No' => $Policy_Number,
            'File_Date' => $Policy_Date,
            'FromDateTime' => $From_Date,
            'ToDateTime' => $To_Date,
            'VehicleRegNo' => $Vehicle_No,
            'Loc_Name' => $Location,
            'Total_Amount' => $Amount,
            'Remark' => $Remark,
            'group_id' => $this->session->userdata('group_id'),
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
        );
    
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
    
        if ($this->customlib->check_punchfile($Scan_Id)) {
            // Update Existing Record
            $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
    
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array(
                'Amount' => '-' . $Amount,
                'Comment' => $Remark
            ));
    
            if ($submit) {
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array(
                    'is_rejected' => 'N',
                    'reject_date' => NULL,
                    'has_edit_permission' => 'N',
                    'finance_punch_action_status' => 'N'
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
                $this->db->where('scan_id', $Scan_Id)->update('y{$this->year_id}_scan_file', array(
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
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something Went Wrong</div>');
        } else {
            if ($submit) {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Insurance Policy submitted successfully</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Saved as draft</div>');
            }
        }
    
        redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
    }
    
    public function save_insurance_document()
    {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Policy_Holder_Name   = $this->input->post('Policy_Holder_Name');
        $Policy_Number   = $this->input->post('Policy_Number');
        $Policy_Type   = $this->input->post('Policy_Type');
        $Policy_Date   = $this->input->post('Policy_Date');
        $Nominee   = $this->input->post('Nominee');
        $Sum_Assured   = $this->input->post('Sum_Assured');
        $Premium_Date   = $this->input->post('Premium_Date');
        $Maturity_Date   = $this->input->post('Maturity_Date');
        $Period   = $this->input->post('Period');
        $Due_Date   = $this->input->post('Due_Date');
        $Coverage   = $this->input->post('Coverage');
        $Vehicle_No   = $this->input->post('Vehicle_No');
        $Amount = $this->input->post('Premium_Amount');
        $Agent_Branch = $this->input->post('Agent_Branch');
        $Insured_Details = $this->input->post('Insured_Details');
        $Remark = $this->input->post('Remark');
        $data = array(
            'scan_id' => $Scan_Id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'Related_Person' => $Policy_Holder_Name,
            'File_No' => $Policy_Number,
            'File_Date' => $Policy_Date,
            'File_Type' => $Policy_Type,
            'NomineeDetails' => $Nominee,
            'SumAssured' => $Sum_Assured,
            'PremiumDate' => $Premium_Date,
            'MaturityDate' => $Maturity_Date,
            'Period' => $Period,
            'DueDate' => $Due_Date,
            'Coverage' => $Coverage,
            'VehicleRegNo' => $Vehicle_No,
            'AgentName' => $Agent_Branch,
            'PassengerDetail' => $Insured_Details,
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Local Conveyance Bill added successfully</div>');
            redirect('punch');
        }
    }
}
