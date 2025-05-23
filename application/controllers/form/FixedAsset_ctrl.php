<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FixedAsset_ctrl extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scan_model');
    }

    public function create()
    {

        $scan_id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Bill_Date = $this->input->post('Bill_Date');
        $Bill_No = $this->input->post('Bill_No');
        $CompanyId = $this->input->post('Company');
        $Comapny = $this->customlib->getCompanyNameById($CompanyId);
        $DepartmentId = $this->input->post('Department');
        $Department = $this->customlib->getDepatmentNameById($DepartmentId);
        $From = $this->input->post('From');
        $FromName = $this->customlib->getCompanyNameById($From);
        $To = $this->input->post('To');
        $ToNmae = $this->customlib->getCompanyNameById($To);
        $Work_Location = $this->input->post('Work_Location');
        $Ledger = $this->input->post('Ledger');
        $File = $this->input->post('file_name');
      
        $Sub_Total = $this->input->post('Sub_Total');
        $Total = $this->input->post('Total');
        $Total_Discount = $this->input->post('Total_Discount');
        $Grand_Total = $this->input->post('Grand_Total');
       
        $GST = $this->input->post('GST');
        $SGST = $this->input->post('SGST');
        $IGST = $this->input->post('IGST');
        $Cess = $this->input->post('Cess');
        $TCS = $this->input->post('TCS');
        $Particular = $this->input->post('Particular');
        $HSN = $this->input->post('HSN');
        $Qty = $this->input->post('Qty');
      
        $MRP = $this->input->post('MRP');
        $Discount = $this->input->post('Discount');
        $Price = $this->input->post('Price');
        $Amount = $this->input->post('Amount');
        $TAmount = $this->input->post('TAmount');
        $Remark = $this->input->post('Remark');

        $data = array(
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'BillDate' => $Bill_Date,
            'File_No' => $Bill_No,
            'Company' => $Comapny,
            'CompanyID' => $CompanyId,
            'Department' => $Department,
            'DepartmentID' => $DepartmentId,
            'From_ID' => $From,
            'FromName' => $FromName,
            'To_ID' => $To,
            'ToName' => $ToNmae,
            'Loc_Name' => $Work_Location,
            'Ledger' => $Ledger,
            'FileName' => $File,
           
            'SubTotal' => $Sub_Total,
            'Total_Amount' => $Total,
            'Grand_Total' => $Grand_Total,
            'Total_Discount' => $Total_Discount,
          
            'TCS' => $TCS,
            'Remark' => $Remark,
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
        );
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile($scan_id) == true) {
            //Update
            $this->db->where('scan_id', $scan_id)->update('punchfile', $data);

            $FileID = $this->db->where('scan_id', $scan_id)->get('punchfile')->row()->FileID;
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Grand_Total, 'Comment' => $Remark));
            $this->db->where('scan_id', $scan_id)->delete('invoice_detail');
            $array = array();
            for ($i = 0; $i < count($Particular); $i++) {
                $array[$i] = array(
                    'scan_id' => $scan_id,
                    'Particular' => $Particular[$i],
                    'HSN' => $HSN[$i],
                    'Qty' => $Qty[$i],
                   
                    'MRP' => $MRP[$i],
                    'Discount' => $Discount[$i],
                    'Price' => $Price[$i],
                    'Amount' => $Amount[$i],
                    'GST' => $GST[$i],
                    'SGST' => $SGST[$i],
                    'IGST' => $IGST[$i],
                    'Cess' => $Cess[$i],
                    'Total_Amount' => $TAmount[$i],

                );
            }
            $this->db->insert_batch('invoice_detail', $array);
            $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
        } else {
            //Insert
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Grand_Total, 'Comment' => $Remark));
            $array = array();
            for ($i = 0; $i < count($Particular); $i++) {
                $array[$i] = array(
                    'scan_id' => $scan_id,
                    'scan_id' => $scan_id,
                    'Particular' => $Particular[$i],
                    'HSN' => $HSN[$i],
                    'Qty' => $Qty[$i],
                   
                    'MRP' => $MRP[$i],
                    'Discount' => $Discount[$i],
                    'Price' => $Price[$i],
                    'Amount' => $Amount[$i],
                    'GST' => $GST[$i],
                    'SGST' => $SGST[$i],
                    'IGST' => $IGST[$i],
                    'Cess' => $Cess[$i],
                    'Total_Amount' => $TAmount[$i],
                );
            }
            $this->db->insert_batch('invoice_detail', $array);
        }


        $this->customlib->update_file_path($scan_id);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
            redirect('punch');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Fixed Asset added successfully</div>');
            redirect('punch');
        }
    }

    function getInvoiceItem(){
        $scan_id = $this->input->post('scan_id');
       
        $result = $this->db->select('*')->from('invoice_detail')->where('scan_id', $scan_id)->get()->result_array();
        if (!empty($result)) {
            echo json_encode(array('status' => 200, 'data' => $result));
        } else {
            echo json_encode(array('status' => 400, 'msg' => 'No Record Found'));
        }
    }

    

    
}
