<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mediclaim_ctrl extends CI_Controller
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

        $Date = $this->input->post('Date');
        $Problem = $this->input->post('Problem');
        $Company = $this->input->post('Company');
        $Policy_Holder = $this->input->post('Policy_Holder');
        $Period = $this->input->post('Period');
        $Hospital = $this->input->post('Hospital');
        $Doctor = $this->input->post('Doctor');
        $Medicine = $this->input->post('Medicine');
        $Remedy = $this->input->post('Remedy');
        $Treatment = $this->input->post('Treatment');


        $Remark = $this->input->post('Remark');

        $data = array(
            'scan_id' => $scan_id,
            'DocType' => $DocType,
            'DocTypeId' => $DocTypeId,
            'File_Date' => $Date,
            'ProblemIssue' => $Problem,
            'Company' => $Company,
            'Related_Person' => $Policy_Holder,
            'PeriodDuration' => $Period,
            'Hospital' => $Hospital,
            'Doctor' => $Doctor,
            'Medicine' => $Medicine,
            'Remedy' => $Remedy,
            'TreatmentTaken' => $Treatment,

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
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">PF / ESIC Record added successfully</div>');
            redirect('punch');
        }
    }
}
