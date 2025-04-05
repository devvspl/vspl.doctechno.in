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
        $Scan_Id = $this->input->post('Scan_Id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Insurance_Type   = $this->input->post('Insurance_Type');
        $Insurance_Company   = $this->input->post('Insurance_Company');
        $Policy_Number   = $this->input->post('Policy_Number');
        $Policy_Date   = $this->input->post('Policy_Date');
        $From_Date   = $this->input->post('From_Date');
        $To_Date   = $this->input->post('To_Date');
        $Vehicle_No   = $this->input->post('Vehicle_No');
        $Location   = $this->input->post('Location');
        $Amount = $this->input->post('Premium_Amount');
        $Remark = $this->input->post('Remark');
        $data = array(
            'Scan_Id' => $Scan_Id,
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Local Conveyance Bill added successfully</div>');
            redirect('punch');
        }
    }
    public function save_insurance_document()
    {
        $Scan_Id = $this->input->post('Scan_Id');
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
            'Scan_Id' => $Scan_Id,
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Local Conveyance Bill added successfully</div>');
            redirect('punch');
        }
    }
}
