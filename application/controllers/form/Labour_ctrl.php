<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Labour_ctrl extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scan_model');
    }

    public function save()
    {
        $submit = $this->input->post('submit'); // 'submit' or 'draft'


    
        $Scan_Id = $this->input->post('Scan_Id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
    
        $Voucher_No = $this->input->post('Voucher_No');
        $Payment_Date = $this->input->post('Payment_Date');
        $Payee = $this->input->post('Payee');
        $Location = $this->input->post('Location');
        $Particular = $this->input->post('Particular');
        $Total_Amount = $this->input->post('Total_Amount');
        $From_Date = $this->input->post('From_Date');
        $To_Date = $this->input->post('To_Date');
        $Sub_Total = $this->input->post('Sub_Total');
        $Head = $this->input->post('Head');
        $Amount = $this->input->post('Amount');
        $Remark = $this->input->post('Remark');
    
        // Prepare data for punchfile
        $data = array(
            'Scan_Id' => $Scan_Id,
            'Group_Id' => $this->session->userdata('group_id'),
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'File_No' => $Voucher_No,
            'BillDate' => $Payment_Date,
            'Related_Person' => $Payee,
            'Loc_Name' => $Location,
            'FileName' => $Particular,
            'Total_Amount' => $Total_Amount,
            'FromDateTime' => $From_Date,
            'ToDateTime' => $To_Date,
            'SubTotal' => $Sub_Total,
            'Remark' => $Remark,
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
        );
    
        // Start transaction
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
    
        if ($this->customlib->check_punchfile($Scan_Id)) {
            // Update punchfile
            $this->db->where('Scan_Id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('Scan_Id', $Scan_Id)->get('punchfile')->row()->FileID;
    
            // Update sub_punchfile
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array(
                'Amount' => '-' . $Total_Amount,
                'Comment' => $Remark
            ));
    
            // Remove old details
            $this->db->where('Scan_Id', $Scan_Id)->delete('labour_payment_detail');
        } else {
            // Insert punchfile
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
    
            // Insert sub_punchfile
            $this->db->insert('sub_punchfile', array(
                'FileID' => $insert_id,
                'Amount' => '-' . $Total_Amount,
                'Comment' => $Remark
            ));
        }
    
        // Insert labour payment detail
        $details = array();
        for ($i = 0; $i < count($Head); $i++) {
            $details[] = array(
                'Scan_Id' => $Scan_Id,
                'Head' => $Head[$i],
                'Amount' => $Amount[$i]
            );
        }
        $this->db->insert_batch('labour_payment_detail', $details);
    
        // Update scan_file for submit/draft
        $this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array(
            'Is_Rejected' => 'N',
            'Reject_Date' => NULL,
            'Edit_Permission' => $submit ? 'N' : 'Y',
            'finance_punch' => $submit ? 'N' : NULL
        ));
    
        $this->customlib->update_file_path($Scan_Id);
    
        // Complete transaction
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something went wrong</div>');
            redirect('punch');
        } else {
            $msg = $submit ? 'Labour Payment submitted successfully' : 'Labour Payment saved as draft';
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">' . $msg . '</div>');
            redirect($submit ? 'punch' : $_SERVER['HTTP_REFERER']);
        }
    }
    

    public function getLabourRecord()
    {
        $Scan_Id = $this->input->post('Scan_Id');
        $result = $this->db->select('*')->from('labour_payment_detail')->where('Scan_Id', $Scan_Id)->get()->result_array();
        if (!empty($result)) {
            echo json_encode(array('status' => 200, 'data' => $result));
        } else {
            echo json_encode(array('status' => 400, 'msg' => 'No Record Found'));
        }
    }
}
