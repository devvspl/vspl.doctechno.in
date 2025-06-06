<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Challan_ctrl extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scan_model');
    }

    public function save_challan()
    {
        $scan_id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Bill_Date   = $this->input->post('Bill_Date');
        $Challan_No   = $this->input->post('Challan_No');
        $Purpose   = $this->input->post('Purpose');
        $Period   = $this->input->post('Period');
        $Bank_Name   = $this->input->post('Bank_Name');
        $Ref_No   = $this->input->post('Ref_No');
        $BSR_Code   = $this->input->post('BSR_Code');
        $Amount = $this->input->post('Amount');
        $Remark = $this->input->post('Remark');
        $data = array(
            'scan_id' => $scan_id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'BillDate' => $Bill_Date,
            'File_No' => $Challan_No,
            'ChallanPurpose' => $Purpose,
            'Period' => $Period,
            'BankName' => $Bank_Name,
            'ServiceNo' => $Ref_No,
            'BankBSRCode' => $BSR_Code,
            'Total_Amount' => $Amount,
            'Remark' => $Remark,
            'group_id' => $this->session->userdata('group_id'),
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
        );

        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile($scan_id) == true) {
            //Update Existing Record
            $this->db->where('scan_id', $scan_id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $scan_id)->get('punchfile')->row()->FileID;

            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amount, 'Comment' => $Remark));
            $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
        } else {
            //Insert New Record
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Amount, 'Comment' => $Remark));
        }

        $this->customlib->update_file_path($scan_id);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
            redirect('punch');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Challan added successfully</div>');
            redirect('punch');
        }
    }

    public function Save_GST_Challan()
    {
        $submit = $this->input->post('submit'); // Check if action is 'submit' or 'draft'
    
        $scan_id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
    
        $CPIN = $this->input->post('CPIN');
        $Deposit_Date = $this->input->post('Deposit_Date');
        $CIN = $this->input->post('CIN');
        $Bank_Name = $this->input->post('Bank_Name');
        $BRN = $this->input->post('BRN');
        $GSTIN = $this->input->post('GSTIN');
        $Email = $this->input->post('Email');
        $Mobile = $this->input->post('Mobile');
        $Company = $this->input->post('Company');
        $Address = $this->input->post('Address');
        $Remark = $this->input->post('Remark');
        $Amount = $this->input->post('Total_Amount');
    
        $Particular = $this->input->post('Particular');
        $Tax = $this->input->post('Tax');
        $Interest = $this->input->post('Interest');
        $Penalty = $this->input->post('Penalty');
        $Fees = $this->input->post('Fees');
        $Other = $this->input->post('Other');
        $Total = $this->input->post('Total');
    
        // Prepare data for punchfile
        $data = array(
            'scan_id' => $scan_id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'CPIN' => $CPIN,
            'File_Date' => $Deposit_Date,
            'CIN' => $CIN,
            'BankName' => $Bank_Name,
            'BankBSRCode' => $BRN,
            'GSTIN' => $GSTIN,
            'Email' => $Email,
            'MobileNo' => $Mobile,
            'Company' => $Company,
            'Related_Address' => $Address,
            'Total_Amount' => $Amount,
            'Remark' => $Remark,
            'group_id' => $this->session->userdata('group_id'),
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
        );
    
        // Start transaction
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
    
        if ($this->customlib->check_punchfile($scan_id)) {
            // Update punchfile
            $this->db->where('scan_id', $scan_id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $scan_id)->get('punchfile')->row()->FileID;
    
            // Update sub_punchfile
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array(
                'Amount' => '-' . $Amount,
                'Comment' => $Remark
            ));
    
            // Remove old details and insert new ones
            $this->db->where('scan_id', $scan_id)->delete('gst_challan_detail');
        } else {
            // Insert punchfile
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
    
            // Insert sub_punchfile
            $this->db->insert('sub_punchfile', array(
                'FileID' => $insert_id,
                'Amount' => '-' . $Amount,
                'Comment' => $Remark
            ));
        }
    
        // Prepare and insert GST challan details
        $details = array();
        for ($i = 0; $i < count($Particular); $i++) {
            $details[] = array(
                'scan_id' => $scan_id,
                'Particular' => $Particular[$i],
                'Tax' => $Tax[$i],
                'Interest' => $Interest[$i],
                'Penalty' => $Penalty[$i],
                'Fees' => $Fees[$i],
                'Other' => $Other[$i],
                'Total' => $Total[$i]
            );
        }
        $this->db->insert_batch('gst_challan_detail', $details);
    
        // Update y{$this->year_id}_scan_file for submit/draft
        $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", array(
            'is_rejected' => 'N',
            'reject_date' => NULL,
            'has_edit_permission' => $submit ? 'N' : 'Y',
            'finance_punch_action_status' => $submit ? 'N' : NULL
        ));
    
        $this->customlib->update_file_path($scan_id);
    
        // Complete transaction
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something went wrong</div>');
            redirect('punch');
        } else {
            $msg = $submit ? 'GST Challan submitted successfully' : 'GST Challan saved as draft';
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">' . $msg . '</div>');
            redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
        }
    }
    

    function getGstItem(){
        $scan_id = $this->input->post('scan_id');
       
        $result = $this->db->select('*')->from('gst_challan_detail')->where('scan_id', $scan_id)->get()->result_array();
        if (!empty($result)) {
            echo json_encode(array('status' => 200, 'data' => $result));
        } else {
            echo json_encode(array('status' => 400, 'msg' => 'No Record Found'));
        }
    }
}
