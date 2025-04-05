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
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile($Scan_Id) == true) {
            //Update
            $this->db->where('Scan_Id', $Scan_Id)->update('punchfile', $data);

            $FileID = $this->db->where('Scan_Id', $Scan_Id)->get('punchfile')->row()->FileID;
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Total_Amount, 'Comment' => $Remark));
            $this->db->where('Scan_Id', $Scan_Id)->delete('labour_payment_detail');
            $array = array();
            for ($i = 0; $i < count($Head); $i++) {
                $array[$i] = array(
                    'Scan_Id' => $Scan_Id,
                    'Head' => $Head[$i],
                    'Amount' => $Amount[$i],
                );
            }
            $this->db->insert_batch('labour_payment_detail', $array);
            $this->db->where('Scan_Id', $Scan_Id)->update('scan_file', array('Is_Rejected' => 'N', 'Reject_Date' => NULL, 'Edit_Permission' => 'N'));
        } else {
            //Insert
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Total_Amount, 'Comment' => $Remark));
            $array = array();
            for ($i = 0; $i < count($Head); $i++) {
                $array[$i] = array(
                    'Scan_Id' => $Scan_Id,
                    'Head' => $Head[$i],
                    'Amount' => $Amount[$i],
                );
            }
            $this->db->insert_batch('labour_payment_detail', $array);
        }


        $this->customlib->update_file_path($Scan_Id);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
            redirect('punch');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Labour Payment added successfully</div>');
            redirect('punch');
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
