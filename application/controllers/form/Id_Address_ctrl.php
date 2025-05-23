<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Id_Address_ctrl extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scan_model');
    }

    public function create()
    {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);

        $Document_Type = $this->input->post('Document_Type');
        $Person = $this->input->post('Person');
        $Document_Number = $this->input->post('Document_Number');
        $Valid_From = $this->input->post('Valid_From');
        $Valid_Upto = $this->input->post('Valid_Upto');
        $Remark = $this->input->post('Remark');

        $data = array(
            'scan_id' => $Scan_Id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'File_Type' => $Document_Type,
            'File_No' => $Document_Number,
            'Related_Person' => $Person,
            'ValidFrom' => $Valid_From,
            'Validto' => $Valid_Upto,
            'Remark' => $Remark,
            'group_id' => $this->session->userdata('group_id'),
            'Created_By' => $this->session->userdata('user_id'),
            'Created_Date' => date('Y-m-d H:i:s'),
        );

        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile2($Scan_Id) == true) {
            //Update Existing Record
            $this->db->where('scan_id', $Scan_Id)->update('punchfile2', $data);
            $this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N','reject_date'=>NULL,'has_edit_permission'=>'N'));
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">ID/Address Proof added successfully</div>');
            redirect('punch');
        }
    }
}
