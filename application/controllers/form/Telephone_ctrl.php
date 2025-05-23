<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Telephone_ctrl extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function Save_Telephone_Bill() {
        $submit = $this->input->post('submit');  // This will check if the action is 'submit' or 'draft'
    
        $scan_id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
    
        $Bill_Date = $this->input->post('Bill_Date');
        $Invoice_No = $this->input->post('Invoice_No');
        $BIller_Name = $this->input->post('BIller_Name');
        $Phone_No = $this->input->post('Phone_No');
        $Period = $this->input->post('Period');
        $Taxable_Value = $this->input->post('Taxable_Value');
        $CGST = $this->input->post('CGST');
        $SGST = $this->input->post('SGST');
        $IGST = $this->input->post('IGST');
        $Amount_Due = $this->input->post('Amount_Due');
        $Amout_Outstanding = $this->input->post('Amout_Outstanding');
        $Lst_Payment_Date = $this->input->post('Lst_Payment_Date');
        $Remark = $this->input->post('Remark');
    
        // Prepare the data to be inserted or updated
        $data = array(
            'scan_id' => $scan_id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'BillDate' => $Bill_Date,
            'FromName' => $BIller_Name,
            'File_No' => $Invoice_No,
            'Period' => $Period,
            'MobileNo' => $Phone_No,
            'SubTotal' => $Taxable_Value,
            'CGST_Amount' => $CGST,
            'SGST_Amount' => $SGST,
            'GST_IGST_Amount' => $IGST,
            'Total_Amount' => $Amount_Due,
            'Grand_Total' => $Amout_Outstanding,
            'DueDate' => $Lst_Payment_Date,
            'Remark' => $Remark,
            'group_id' => $this->session->userdata('group_id'),
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
        );
    
        // Start the transaction
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
    
        if ($this->customlib->check_punchfile($scan_id) == true) {
            // Update existing record
            $this->db->where('scan_id', $scan_id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $scan_id)->get('punchfile')->row()->FileID;
    
            // Update the sub_punchfile record
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array(
                'Amount' => '-' . $Amout_Outstanding, 
                'Comment' => $Remark
            ));
    
            // Handle the 'submit' or 'draft' action
            if ($submit) {
                $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", array(
                    'is_rejected' => 'N', 
                    'reject_date' => NULL, 
                    'has_edit_permission' => 'N', 
                    'finance_punch_action_status' => 'N'  // Set finance_punch to 'N' when submitting
                ));
            } else {
                $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", array(
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
                'Amount' => '-' . $Amout_Outstanding, 
                'Comment' => $Remark
            ));
    
            // Handle the 'submit' or 'draft' action after insertion
            if ($submit) {
                $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", array(
                    'is_rejected' => 'N', 
                    'reject_date' => NULL, 
                    'has_edit_permission' => 'N', 
                    'finance_punch_action_status' => 'N'  // Set finance_punch to 'N' when submitting
                ));
            } else {
                $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", array(
                    'is_rejected' => 'N', 
                    'reject_date' => NULL, 
                    'has_edit_permission' => 'Y',  // Allow editing for draft
                  
                ));
            }
        }
    
        $this->customlib->update_file_path($scan_id);
    
        // Complete the transaction
        $this->db->trans_complete();
    
        // Handle transaction success or failure
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something Went Wrong</div>');
            redirect('punch');
        } else {
            if ($submit) {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Telephone Bill submitted successfully</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Telephone Bill saved as draft</div>');
            }
        }
    
        // Redirect based on whether it's submitted or saved as a draft
        redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
    }
    
    public function save_phone_fax()
    {
        $scan_id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);

        $Bill_Date   = $this->input->post('Bill_Date');
        $Payment_Mode   = $this->input->post('Payment_Mode');
        $Due_Date   = $this->input->post('Due_Date');
        $Billing_Cycle   = $this->input->post('Billing_Cycle');
        $Billing_Person   = $this->input->post('Billing_Person');
        $Billing_Address   = $this->input->post('Billing_Address');
        $Mobile_Service   = $this->input->post('Mobile_Service');
        $Mobile_No   = $this->input->post('Mobile_No');
        $Tarrif_Plan   = $this->input->post('Tarrif_Plan');
        $Previous_Balance   = $this->input->post('Previous_Balance');
        $Charges = $this->input->post('Charges');
        $Last_Payment_Detail = $this->input->post('Last_Payment_Detail');
        $Remark = $this->input->post('Remark');
        $data = array(
            'scan_id' => $scan_id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'BillDate' => $Bill_Date,
            'Total_Amount' => $Charges,
            'NatureOfPayment' => $Payment_Mode,
            'DueDate' => $Due_Date,
            'BillingCycle' => $Billing_Cycle,
            'Related_Person' => $Billing_Person,
            'Related_Address' => $Billing_Address,
            'File_Type' => $Mobile_Service,
            'MobileNo' => $Mobile_No,
            'TariffPlan' => $Tarrif_Plan,
            'PreviousBalance' => $Previous_Balance,
            'LastPayement' => $Last_Payment_Detail,
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

            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Charges, 'Comment' => $Remark));
            $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
        } else {
            //Insert New Record
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Charges, 'Comment' => $Remark));
        }

        $this->customlib->update_file_path($scan_id);

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
