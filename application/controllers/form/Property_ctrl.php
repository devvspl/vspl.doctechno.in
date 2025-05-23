<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Property_ctrl extends CI_Controller
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

        $Purchase_Date = $this->input->post('Purchase_Date');
        $Seller = $this->input->post('Seller');
        $Purchaser = $this->input->post('Purchaser');
        $Purchase_Value = $this->input->post('Purchase_Value');
        $Market_Value = $this->input->post('Market_Value');
        $Additional_Payment = $this->input->post('Additional_Payment');
        $Location = $this->input->post('location_id');
        $Area = $this->input->post('Area');
        $KH_No = $this->input->post('KH_No');
        $PH_No = $this->input->post('PH_No');
        $Unit = $this->input->post('Unit');
        $RNM = $this->input->post('RNM');
        $Rin_Pushtika = $this->input->post('Rin_Pushtika');
        $New_Khasra = $this->input->post('New_Khasra');
        $Stamp_Duty = $this->input->post('Stamp_Duty');
        $Diversion_Paper = $this->input->post('Diversion_Paper');
        $Map_Approval_Detail = $this->input->post('Map_Approval_Detail');
        $Additional_Exposure = $this->input->post('Additional_Exposure');
        $Remark = $this->input->post('Remark');

        $data = array(
            'scan_id' => $scan_id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,

            'File_Date' => $Purchase_Date,
            'FromName' => $Seller,
            'ToName' => $Purchaser,
            'TotalAmount' => $Purchase_Value,
            'MarketValue' => $Market_Value,
            'ExtraCharge' => $Additional_Payment,
            'FileLoc' => $Location,
            'TotalArea' => $Area,
            'KHNo' => $KH_No,
            'PHNo' => $PH_No,
            'Unit' => $Unit,
            'RNM_Ward' => $RNM,
            'RinPushtikaNo' => $Rin_Pushtika,
            'KhasraNo' => $New_Khasra,
            'Stamp_Duty' => $Stamp_Duty,
            'Diversion_Paper' => $Diversion_Paper,
            'Map_Approval' => $Map_Approval_Detail,
            'Additional_Exposure' => $Additional_Exposure,
            'Remark' => $Remark,
            'group_id' => $this->session->userdata('group_id'),
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
        );

        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile2($scan_id) == true) {
            //Update Existing Record
            $this->db->where('scan_id', $scan_id)->update('punchfile2', $data);
            $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
        } else {
            //Insert New Record
            $this->db->insert('punchfile2', $data);
        }

        $this->customlib->update_file_path($scan_id);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
            redirect('punch');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Property Record added successfully</div>');
            redirect('punch');
        }
    }

    public function save_lease_rent()
    {
        $scan_id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Lessor_Name   = $this->input->post('Lessor_Name');
        $Lessee_Name   = $this->input->post('Lessee_Name');
        $Property_Address   = $this->input->post('Property_Address');
        $Property_Area   = $this->input->post('Property_Area');
        $Other_Specification  = $this->input->post('Other_Specification');
        $Lease_Start_Period   = $this->input->post('Lease_Start_Period');
        $Lease_End_Period   = $this->input->post('Lease_End_Period');
        $Payment_Frequency   = $this->input->post('Payment_Frequency');
        $Taxable_Value   = $this->input->post('Taxable_Value');
        $CGST   = $this->input->post('CGST');
        $SGST   = $this->input->post('SGST');
        $IGST   = $this->input->post('IGST');
        $Amount = $this->input->post('Total_Amount');
        $Remark = $this->input->post('Remark');
        $data = array(
            'scan_id' => $scan_id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'FromName' => $Lessor_Name,
            'ToName' => $Lessee_Name,
            'Loc_Add' => $Property_Address,
            'PropertyArea' => $Property_Area,
            'OtherSpecif' => $Other_Specification,
            'FromDateTime' => $Lease_Start_Period,
            'ToDateTime' => $Lease_End_Period,
            'BillingCycle' => $Payment_Frequency,
            'SubTotal' => $Taxable_Value,
            'CGST_Amount' => $CGST,
            'SGST_Amount' => $SGST,
            'GST_IGST_Amount' => $IGST,
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Lease Rent added successfully</div>');
            redirect('punch');
        }
    }
}
