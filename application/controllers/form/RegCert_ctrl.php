<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RegCert_ctrl extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Department_model');
    }

    public function create()
    {
        $scan_id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Certificate_Name = $this->input->post('Certificate_Name');
        $Certificate_No = $this->input->post('Certificate_No');
        $Certificate_Date = $this->input->post('Certificate_Date');
        $CompanyId = $this->input->post('Company');
        $Company = $this->customlib->getCompanyNameById($CompanyId);
       
        $DepartmentId = $this->input->post('Department');
        $Department = $this->customlib->getDepatmentNameById($DepartmentId);
        $Valid_From = $this->input->post('Valid_From');
        $Valid_Upto = $this->input->post('Valid_Upto');
        $Remark = $this->input->post('Remark');

        $data = array(
            'scan_id' => $scan_id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'Company' => $Company,
            'CompanyID' => $CompanyId,
            'Department' => $Department,
            'DepartmentID' => $DepartmentId,
            'CertiType' => $Certificate_Name,
            'CertiNo' => $Certificate_No,
            'File_Date' => $Certificate_Date,
            'ValidFrom' => $Valid_From,
            'Validto' => $Valid_Upto,
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
            $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N','reject_date'=>NULL,'has_edit_permission'=>'N'));
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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Tax Credit Document added successfully</div>');
            redirect('punch');
        }
    }

    public function getDepartment()
    {
        $CompanyId = $this->input->post('company_id');
        $data = $this->Department_model->get_department_by_company_id($CompanyId);
        echo json_encode($data);

    }
}
