<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Telephone_ctrl extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    public function Save_Telephone_Bill()
    {
        $Scan_Id = $this->input->post('Scan_Id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);

        $Bill_Date   = $this->input->post('Bill_Date');
        $Invoice_No   = $this->input->post('Invoice_No');
        $BIller_Name   = $this->input->post('BIller_Name');
        $Phone_No   = $this->input->post('Phone_No');
        $Period   = $this->input->post('Period');
        $Taxable_Value   = $this->input->post('Taxable_Value');
        $CGST   = $this->input->post('CGST');
        $SGST   = $this->input->post('SGST');
        $IGST   = $this->input->post('IGST');
        $Amount_Due   = $this->input->post('Amount_Due');
        $Amout_Outstanding = $this->input->post('Amout_Outstanding');
        $Lst_Payment_Date = $this->input->post('Lst_Payment_Date');
        $Remark = $this->input->post('Remark');
        $data = array(
            'Scan_Id' => $Scan_Id,
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

            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Amout_Outstanding, 'Comment' => $Remark));
            $this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array('Is_Rejected' => 'N', 'Reject_Date' => NULL, 'Edit_Permission' => 'N'));
        } else {
            //Insert New Record
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Amout_Outstanding, 'Comment' => $Remark));
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
    public function save_phone_fax()
    {
        $Scan_Id = $this->input->post('Scan_Id');
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
            'Scan_Id' => $Scan_Id,
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

            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Charges, 'Comment' => $Remark));
            $this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array('Is_Rejected' => 'N', 'Reject_Date' => NULL, 'Edit_Permission' => 'N'));
        } else {
            //Insert New Record
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Charges, 'Comment' => $Remark));
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
