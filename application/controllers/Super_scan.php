<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Super_scan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Scan_model');
        $this->load->model('Group_model');
    }

    private function logged_in()
    {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    public function index($id)
    {
        $sql = "SELECT group_name FROM `master_group` WHERE `group_id` = $id";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        $group_name = $result[0]['group_name'];

        $this->data['group_name'] = $group_name;
        $this->data['group_id'] = $id;
        $this->data['locationlist'] = $this->customlib->getWorkLocationList();
        $this->data['bill_approver_list'] = $this->customlib->getBillApproverList();
        $this->data['main'] = 'super_scan/super_scan';
        // New Scan Formate
        $this->data['locationlist'] = $this->customlib->getWorkLocationList();
        $this->data['doctypeList'] = $this->db->where(['status'=>'A', 'is_deleted'=>'N'])->get('master_temp_doctype')->result_array();
        $this->data['firmList'] = $this->Scan_model->get_firm_list();
        $this->data['departmentList'] = $this->Scan_model->get_department_list();
        $group_id = $id;
        $allApprovers = $this->customlib->getBillApproverList();
        $approversByLocation = [];
        $approversByDepartment = [];
        if ($group_id == 16) {
            foreach ($allApprovers as $approver) {
                if (!empty($approver['department_id'])) {
                    $departments = explode(',', $approver['department_id']);
                    foreach ($departments as $departmentId) {
                        if (!isset($approversByDepartment[$departmentId])) {
                            $approversByDepartment[$departmentId] = [];
                        }
                        $approversByDepartment[$departmentId][] = $approver;
                    }
                }
            }
        } else {
            foreach ($allApprovers as $approver) {
                if (!empty($approver['location_id'])) {
                    $locations = explode(',', $approver['location_id']);
                    foreach ($locations as $locationId) {
                        if (!isset($approversByLocation[$locationId])) {
                            $approversByLocation[$locationId] = [];
                        }
                        $approversByLocation[$locationId][] = $approver;
                    }
                }
            }
        }
        $this->data['approversByLocation'] = $approversByLocation;
        $this->data['approversByDepartment'] = $approversByDepartment;
        $this->load->view('layout/template', $this->data);
    }

    // public function upload_main()
    // {
    //     $document_name = '';
    //     if ($this->input->post('document_name') != '' || $this->input->post('document_name') != null) {
    //         $document_name = $this->input->post('document_name');
    //     }
    //     $Scan_By = $this->session->userdata('user_id');
    //     $document_name = $document_name;
    //     $group_id = $this->input->post('group_id');
    //     $location = $this->input->post('location');
    //     $bill_approver = $this->input->post('bill_approver');
    //     $file = $_FILES['main_file']['name'];
    //     $file_ext = pathinfo($file, PATHINFO_EXTENSION);
    //     $year = date('Y');
    //     $config['upload_path'] = './uploads/temp/';
    //     $config['allowed_types'] = 'jpg|png|jpeg|pdf';
    //     $config['max_size'] = 8192;
    //     $var_temp_name = time() . '.' . $file_ext;
    //     $config['file_name'] = $var_temp_name;

    //     $this->load->library('upload', $config);

    //     if (!$this->upload->do_upload('main_file')) {
    //         $error = ['error' => $this->upload->display_errors()];
    //         $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error['error'] . '</div>');
    //         redirect('super_scan/' . $group_id);
    //     } else {
    //         $this->db->insert('y{$this->year_id}_scan_file', [
    //             'group_id' => $group_id,
    //             'scanned_by' => $Scan_By,
    //             'Document_name' => $document_name,
    //             'location_id' => $location,
    //             'bill_approver_id' => $bill_approver,
    //             'file_name' => $var_temp_name,
    //             'file_extension' => $file_ext,
    //             'file_path' => base_url() . 'uploads/temp/' . $var_temp_name,
    //             'secondary_file_path' => 'uploads/temp/' . $var_temp_name,
    //             'year' => $year,
    //             'scan_date' => date('Y-m-d H:i:s'),
    //             'is_final_submitted' => 'Y',
    //         ]);
    //         $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
    //         redirect('super_scan/' . $group_id);
    //     }
    // }

	public function reformat_date($date) {
        $date_parts = explode('/', $date);
        if (count($date_parts) == 3) {
            return $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
        }
        return $date;
    }
	
    public function upload_main()
    {
        $document_name = $this->input->post('document_name');
        $scan_by = $this->session->userdata('user_id');
        $location = $this->input->post('location');
        $bill_approver = $this->input->post('bill_approver');
        $scan_doctype_id = $this->input->post('doctype_id');
        $department_id = $this->input->post('department_id');
        $firm_id = $this->input->post('firm_id');
        $bill_voucher_date = $this->input->post('bill_voucher_date');
        $bill_no_voucher_no = $this->input->post('bill_no_voucher_no');
        $file = $_FILES['main_file']['name'];
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        $year = date('Y');
		$Group_Id = $this->input->post('group_id');
        $config = ['upload_path' => './uploads/temp/', 'allowed_types' => 'jpg|png|jpeg|pdf', 'max_size' => 8192, 'file_name' => time() . '.' . $file_ext];
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('main_file')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error . '</div>');
            redirect('super_scan/'. $Group_Id);
        } else {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
            $bill_voucher_date = $this->reformat_date($bill_voucher_date);
            $data = [
                'group_id' => $Group_Id,
                'scanned_by' => $scan_by,
                'Document_name' => $document_name,
                'location_id' => $location,
                'scan_doctype_id' => $scan_doctype_id,
                'department_id' => $department_id,
                'firm_id' => $firm_id,
                'bill_voucher_date' => $bill_voucher_date,
                'bill_no_voucher_no' => $bill_no_voucher_no,
                'bill_approver_id' => $bill_approver,
                'file_name' => $file_name,
                'file_extension' => $file_ext,
                'file_path' => base_url('uploads/temp/' . $file_name),
                'secondary_file_path' => 'uploads/temp/' . $file_name,
                'year' => $year,
                'scan_date' => date('Y-m-d H:i:s'),
            ];
            $this->db->insert('y{$this->year_id}_scan_file', $data);
			$inserted_id = $this->db->insert_id();
            $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
            redirect('Scan/upload_supporting/' . $inserted_id);
        }
    }
	
    public function super_scan_rejected_list($id)
    {
        $this->db->select('*');
        $this->db->from('y{$this->year_id}_scan_file');
        $this->db->where('group_id', $id);
        $this->db->where('is_scan_resend', 'Y');
        $this->db->order_by('scan_id', 'desc');
        $this->data['scan_rejected_list'] = $this->db->get()->result_array();
        $this->data['main'] = 'scan/scan_rejected_list';
        $this->load->view('layout/template', $this->data);
    }

    public function super_scan_naming_list($id)
    {
        $group_id = $id;
        $this->db->select('*');
        $this->db->from('y{$this->year_id}_scan_file');
        $this->db->join('master_work_location', 'master_work_location.location_id = y{$this->year_id}_scan_file.location_id', 'left');
        $this->db->where('group_id', $group_id);
        $this->db->where('is_temp_scan', 'Y');
        $this->db->where('is_scan_complete', 'N');
        $this->db->where('is_temp_scan_rejected', 'N');
        $this->data['temp_scan_list_for_naming'] = $this->db->get()->result_array();

        $this->data['main'] = 'scan/temp_scan_list_for_naming';
        $this->load->view('layout/template', $this->data);
    }

    public function super_scan_verification_list($id)
    {
        $group_id = $id;
        $this->db->select('*');
        $this->db->from('y{$this->year_id}_scan_file');
        $this->db->where('group_id', $group_id);
        $this->db->where('is_temp_scan', 'Y');
        $this->db->where('is_scan_complete', 'Y');
        $this->db->where('is_temp_scan_rejected', 'N');
        $this->db->where('is_document_verified', 'N');
        $this->data['scan_list_for_verification'] = $this->db->get()->result_array();
        $this->data['main'] = 'super_scan/scan_list_for_verification';
        $this->load->view('layout/template', $this->data);
    }

    public function verify_document()
    {
        $Scan_Id = $this->input->post('scan_id');
        $user_id = $this->session->userdata('user_id');
        $this->db->where('scan_id', $Scan_Id);
        $query = $this->db->update('y{$this->year_id}_scan_file', ['is_document_verified' => 'Y', 'verified_by' => $user_id, 'verified_date' => date('Y-m-d')]);
        if ($query) {
            echo json_encode(['status' => 200]);
        } else {
            echo json_encode(['status' => 400]);
        }
    }

    public function verification()
    {
        $Group = $this->input->post('Group');
        $DocType_Id = $this->input->post('doc_type_id');
        $fromDate = $this->input->post('from_date');
        $toDate = $this->input->post('to_date');
        $this->db->select('*');
        $this->db->from('y{$this->year_id}_scan_file');
        $this->db->where('is_temp_scan', 'Y');
        $this->db->where('is_scan_complete', 'Y');
        $this->db->where('is_temp_scan_rejected', 'N');
        $this->db->where('is_document_verified', 'Y');

        if (!empty($Group)) {
            $this->db->where('group_id', $Group);
        }
        if (!empty($DocType_Id)) {
            $this->db->where('doc_type_id', $DocType_Id);
        }

        if (!empty($fromDate)) {
            $this->db->where('document_verified_date >=', $fromDate);
        }

        if (!empty($toDate)) {
            $this->db->where('document_verified_date <=', $toDate);
        }

        $this->data['scan_list_for_verification'] = $this->db->get()->result_array();

        $this->data['grouplist'] = $this->Group_model->get_group_list();
        $this->data['getFileType'] = $this->Group_model->get_filetype_list();
        $this->data['main'] = 'super_scan/verification_report';
        $this->load->view('layout/template', $this->data);
    }
	public function final_submit()
	{
		$scan_id = $this->input->post('scan_id');
		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		$this->db->where('scan_id', $scan_id);
		$this->db->update('y{$this->year_id}_scan_file', array('is_final_submitted' => 'Y'));

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array('status' => 400));
		} else {
			$this->db->trans_commit();
			echo json_encode(array('status' => 200));
		}
	}
}
