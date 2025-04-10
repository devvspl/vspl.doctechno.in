<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Scan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Scan_model');
    }
    private function logged_in()
    {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    public function index()
    {
        $this->session->set_userdata('top_menu', 'scan_master');
        $this->session->set_userdata('sub_menu', 'scan');
        $this->data['main'] = 'scan/scanfile';
        $this->data['my_lastest_scan'] = $this->Scan_model->get_my_lastest_scan();
        $this->data['locationlist'] = $this->customlib->getWorkLocationList();
        $this->data['bill_approver_list'] = $this->customlib->getBillApproverList();
        $this->load->view('layout/template', $this->data);
    }
    public function temp_scan()
    {
        $this->session->set_userdata('top_menu', 'scan_master');
        $this->session->set_userdata('sub_menu', 'scan');
        $this->data['main'] = 'scan/temp_scanfile';
        $this->data['locationlist'] = $this->customlib->getWorkLocationList();
        $this->data['my_lastest_scan'] = $this->Scan_model->get_my_lastest_temp_scan();
        $this->data['bill_approver_list'] = $this->customlib->getBillApproverList();
        $this->load->view('layout/template', $this->data);
    }
    public function myscannedfiles()
    {
        $this->session->set_userdata('top_menu', 'scan_master');
        $this->session->set_userdata('sub_menu', 'myscannedfiles');
        $this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
        $this->form_validation->set_rules('to_date', 'To Date', 'trim|required');
        if ($this->form_validation->run() == true) {
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
            $scan_file_list = $this->Scan_model->search_scanned_files($from_date, $to_date);
        } else {
            $scan_file_list = $this->Scan_model->get_my_scanned_files();
        }
        $this->data['main'] = 'scan/myscannedfiles';
        $this->data['my_scanned_files'] = $scan_file_list;
        $this->load->view('layout/template', $this->data);
    }
    public function upload_main()
    {
        $document_name = '';
        if ($this->input->post('document_name') != '' || $this->input->post('document_name') != null) {
            $document_name = $this->input->post('document_name');
        }
        $Scan_By = $this->session->userdata('user_id');
        $document_name = $document_name;
        $location = $this->input->post('location');
        $bill_approver = $this->input->post('bill_approver');
        $file = $_FILES['main_file']['name'];
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        $year = date('Y');
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['max_size'] = 8192;
        $var_temp_name = time() . '.' . $file_ext;
        $config['file_name'] = $var_temp_name;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('main_file')) {
            $error = ['error' => $this->upload->display_errors()];
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error['error'] . '</div>');
            redirect('scan');
        } else {
            $this->db->insert('scan_file', [
                'Group_Id' => $this->session->userdata('group_id'),
                'Scan_By' => $Scan_By,
                'Document_name' => $document_name,
                'Location' => $location,
                'Bill_Approver' => $bill_approver,
                'File' => $var_temp_name,
                'File_Ext' => $file_ext,
                'File_Location' => base_url() . 'uploads/temp/' . $var_temp_name,
                'File_Location1' => 'uploads/temp/' . $var_temp_name,
                'Year' => $year,
                'Scan_Date' => date('Y-m-d H:i:s'),
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
            redirect('scan/upload_supporting/' . $this->db->insert_id());
        }
    }
    // public function temp_upload_main() {
    //     $Temp_Scan_By = $this->session->userdata('user_id');
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
    //         $error = ['error' => $this->upload->display_errors() ];
    //         $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error['error'] . '</div>');
    //         redirect('temp_scan');
    //     } else {
    //         $data = ['Group_Id' => $this->session->userdata('group_id'), 'Location' => $location, 'Bill_Approver' => $bill_approver, 'Temp_Scan_By' => $Temp_Scan_By, 'Temp_Scan' => 'Y', 'Scan_Complete' => 'N', 'File' => $var_temp_name, 'File_Ext' => $file_ext, 'File_Location' => base_url() . 'uploads/temp/' . $var_temp_name, 'File_Location1' => 'uploads/temp/' . $var_temp_name, 'Year' => $year, 'Temp_Scan_Date' => date('Y-m-d H:i:s'), ];
    //         $query = $this->db->insert('scan_file', $data);
    //         $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
    //         redirect('scan/temp_upload_supporting/' . $this->db->insert_id());
    //     }
    // }
    public function temp_upload_main()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('location', 'Location', 'required|trim');
        $this->form_validation->set_rules('bill_approver', 'Bill Approver', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . validation_errors() . '</div>');
            redirect('temp_scan');
        } else {
            $Temp_Scan_By = $this->session->userdata('user_id');
            $location = $this->input->post('location');
            $bill_approver = $this->input->post('bill_approver');
            $file = $_FILES['main_file']['name'];
            $file_ext = pathinfo($file, PATHINFO_EXTENSION);
            $year = date('Y');

            $config['upload_path'] = './uploads/temp/';
            $config['allowed_types'] = 'jpg|png|jpeg|pdf';
            $config['max_size'] = 8192;
            $var_temp_name = time() . '.' . $file_ext;
            $config['file_name'] = $var_temp_name;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('main_file')) {
                $error = ['error' => $this->upload->display_errors()];
                $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error['error'] . '</div>');
                redirect('temp_scan');
            } else {
                $data = [
                    'Group_Id' => $this->session->userdata('group_id'),
                    'Location' => $location,
                    'Bill_Approver' => $bill_approver,
                    'Temp_Scan_By' => $Temp_Scan_By,
                    'Temp_Scan' => 'Y',
                    'Scan_Complete' => 'N',
                    'File' => $var_temp_name,
                    'File_Ext' => $file_ext,
                    'File_Location' => base_url() . 'uploads/temp/' . $var_temp_name,
                    'File_Location1' => 'uploads/temp/' . $var_temp_name,
                    'Year' => $year,
                    'Temp_Scan_Date' => date('Y-m-d H:i:s'),
                ];

                $query = $this->db->insert('scan_file', $data);
                $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
                redirect('scan/temp_upload_supporting/' . $this->db->insert_id());
            }
        }
    }

    public function upload_supporting($id)
    {
        $this->data['id'] = $id;
        $this->data['main'] = 'scan/upload_supporting';
        $this->load->view('layout/template', $this->data);
    }
    public function temp_upload_supporting($id)
    {
        $this->data['id'] = $id;
        $this->data['main'] = 'scan/temp_upload_supporting';
        $this->load->view('layout/template', $this->data);
    }
    public function edit_scan($id)
    {
        $this->data['id'] = $id;
        $this->data['main'] = 'scan/edit_scan';
        $this->load->view('layout/template', $this->data);
    }
    public function upload_support()
    {
        $Scan_Id = $this->input->post('scan_id');
        $file = $_FILES['support_file']['name'];
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        $year = date('Y');
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['max_size'] = 8192;
        $var_temp_name = time() . '.' . $file_ext;
        $config['file_name'] = $var_temp_name;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('support_file')) {
            $error = ['error' => $this->upload->display_errors()];
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error['error'] . '</div>');
            if ($_SESSION['role'] != 'super_scan') {
                redirect('scan/upload_supporting/' . $Scan_Id);
            } else {
                redirect('/');
            }
        } else {
            $query = $this->db->insert('support_file', [
                'Scan_Id' => $Scan_Id,
                'File' => $var_temp_name,
                'File_Ext' => $file_ext,
                'File_Location' => base_url() . 'uploads/temp/' . $var_temp_name,
                'File_Location1' => 'uploads/temp/' . $var_temp_name,
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
            if ($_SESSION['role'] != 'super_scan') {
                redirect('scan/upload_supporting/' . $Scan_Id);
            } else {
                redirect('scan/upload_supporting/' . $Scan_Id);
            }
        }
    }
    public function temp_upload_support()
    {
        $Scan_Id = $this->input->post('scan_id');
        $file = $_FILES['support_file']['name'];
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        $year = date('Y');
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['max_size'] = 8192;
        $var_temp_name = time() . '.' . $file_ext;
        $config['file_name'] = $var_temp_name;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('support_file')) {
            $error = ['error' => $this->upload->display_errors()];
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error['error'] . '</div>');
            redirect('scan/temp_upload_supporting/' . $Scan_Id);
        } else {
            $query = $this->db->insert('support_file', [
                'Scan_Id' => $Scan_Id,
                'File' => $var_temp_name,
                'File_Ext' => $file_ext,
                'File_Location' => base_url() . 'uploads/temp/' . $var_temp_name,
                'File_Location1' => 'uploads/temp/' . $var_temp_name,
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
            redirect('scan/temp_upload_supporting/' . $Scan_Id);
        }
    }
    public function delete_all()
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $scan_id = $this->input->post('scan_id');
        $this->db->delete('support_file', ['scan_id' => $scan_id]);
        $this->db->delete('scan_file', ['scan_id' => $scan_id]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 400]);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 200]);
        }
    }
    public function delete_file()
    {
        $id = $this->input->post('id');
        $this->db->delete('support_file', ['Support_Id' => $id]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 400]);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 200]);
        }
    }
    public function final_submit()
    {
        $scan_id = $this->input->post('scan_id');
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $this->db->where('Scan_Id', $scan_id);
        $this->db->update('scan_file', ['Final_Submit' => 'Y']);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 400]);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 200]);
        }
    }
    function final_submit_after_edit()
    {
        $scan_id = $this->input->post('scan_id');
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $this->db->where('Scan_Id', $scan_id);
        $this->db->update('scan_file', ['Scan_Resend' => 'N']);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 400]);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 200]);
        }
    }
    public function scan_rejected_list()
    {
        $this->data['scan_rejected_list'] = $this->Scan_model->scan_rejected_list();
        $this->data['main'] = 'scan/scan_rejected_list';
        $this->load->view('layout/template', $this->data);
    }
    public function temp_scan_rejected_list()
    {
        $this->data['temp_scan_rejected_list'] = $this->Scan_model->temp_scan_rejected_list();
        $this->data['main'] = 'scan/temp_scan_rejected_list';
        $this->load->view('layout/template', $this->data);
    }
    public function replace_file()
    {
        $scan_id = $this->input->post('scan_id');
        $file = $_FILES['image']['name'];
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['max_size'] = 8192;
        $var_temp_name = time() . '.' . $file_ext;
        $config['file_name'] = $var_temp_name;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('image')) {
            echo json_encode(['status' => 400, 'error' => $this->upload->display_errors()]);
        } else {
            $query = $this->db
                ->where('Scan_Id', $scan_id)
                ->update('scan_file', ['File' => $var_temp_name, 'File_Ext' => $file_ext, 'File_Location' => base_url() . 'uploads/temp/' . $var_temp_name, 'File_Location1' => 'uploads/temp/' . $var_temp_name]);
            if ($query) {
                echo json_encode(['status' => 200]);
            } else {
                echo json_encode(['status' => 400, 'error' => 'Something went wrong']);
            }
        }
    }
    public function temp_scan_list_for_naming()
    {
        $this->data['temp_scan_list_for_naming'] = $this->Scan_model->temp_scan_list_for_naming();
        $this->data['main'] = 'scan/temp_scan_list_for_naming';
        $this->load->view('layout/template', $this->data);
    }
    public function naming_file($id)
    {
        $this->data['main'] = 'scan/name_scan_file';
        // New Code
        $this->data['locationlist'] = $this->customlib->getWorkLocationList();
        $this->data['doctypeList'] = $this->db
            ->where(['status' => 'A', 'is_deleted' => 'N'])
            ->get('master_temp_doctype')
            ->result_array();
        $this->data['firmList'] = $this->Scan_model->get_firm_list();
        $this->data['departmentList'] = $this->Scan_model->get_department_list();
        $group_id =
            $this->db
                ->where('Scan_Id', $id)
                ->get('scan_file')
                ->row()->Group_Id ?? null;
        $bill_approver_id =
            $this->db
                ->where('Scan_Id', $id)
                ->get('scan_file')
                ->row()->Bill_Approver ?? null;

        $this->data['group_id'] = $group_id;
        $allApprovers = $this->customlib->getBillApproverList();
        $approversByLocation = [];
        $approversByDepartment = [];
        if ($group_id == 16 && $bill_approver_id) {
            $this->db->select('user_id, department_id');
            $this->db->where('user_id', $bill_approver_id);
            $departments = $this->db->get('users')->row()->department_id;
            $getdepartments = explode(',', $departments);
            $this->data['getdepartments'] = $getdepartments[0];

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
        } elseif ($group_id == 16) {
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
    // public function update_document_name($id)
    // {
    // 	$user_id = $this->session->userdata('user_id');
    // 	$Document_Name = $this->input->post('Document_Name');
    // 	$this->db->where('Scan_Id', $id);
    // 	$result = $this->db->update('scan_file', array('Scan_Complete' => 'Y', 'Scan_By' => $user_id, 'Document_Name' => $Document_Name, 'Scan_Date' => date('Y-m-d H:i:s'), 'Final_Submit' => 'Y'));
    // 	if ($result) {
    // 		echo json_encode(array('status' => '200', 'message' => 'Save Successfully.'));
    // 	} else {
    // 		echo json_encode(array('status' => '400', 'message' => 'Something went wrong. Please try again.'));
    // 	}
    // }
    public function reformat_date($date)
    {
        $date_parts = explode('/', $date);
        if (count($date_parts) == 3) {
            return $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
        }
        return $date;
    }
    public function update_document_name($id)
    {
        $user_id = $this->session->userdata('user_id');
        $Document_Name = $this->input->post('document_name');
        $group_id = $this->input->post('group_id');
        $location = $this->input->post('location');
        $scan_doctype_id = $this->input->post('doctype_id');
        $department_id = $this->input->post('department_id');
        $firm_id = $this->input->post('firm_id');
        $bill_voucher_date_1 = $this->input->post('bill_voucher_date');
        $bill_voucher_date = $this->reformat_date($bill_voucher_date_1);
        $bill_no_voucher_no = $this->input->post('bill_no_voucher_no');
        $data = [
            'Scan_By' => $user_id,
            'Scan_Complete' => 'Y',
            'Document_Name' => $Document_Name,
            'Scan_Date' => date('Y-m-d H:i:s'),
            'Final_Submit' => 'Y',
            'Location' => $location,
            'scan_doctype_id' => $scan_doctype_id,
            'department_id' => $department_id,
            'firm_id' => $firm_id,
            'bill_voucher_date' => $bill_voucher_date,
            'bill_no_voucher_no' => $bill_no_voucher_no,
        ];
        $result = $this->db->where('Scan_Id', $id)->update('scan_file', $data);
        if ($result) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Save Successfully</div>');
            redirect('super_scan_naming_list/' . $group_id);
        } else {
            $this->session->set_flashdata('error', '<div class="alert alert-danger">Something went wrong. Please try again.</div>');
            redirect('naming_file/' . $id);
        }
    }
    public function reject_temp_scan($id)
    {
        $user_id = $this->session->userdata('user_id');
        $remark = $this->input->post('remark');
        $this->db->where('Scan_Id', $id);
        $result = $this->db->update('scan_file', ['temp_scan_reject' => 'Y', 'temp_scan_reject_remark' => $remark, 'temp_scan_reject_date' => date('Y-m-d'), 'temp_scan_reject_by' => $user_id]);
        if ($result) {
            echo json_encode(['status' => '200']);
        } else {
            echo json_encode(['status' => '400']);
        }
    }
    public function edit_bill_approver()
    {
        $this->data['list'] = $this->Scan_model->edit_bill_approver_list();
        $this->data['bill_approver_list'] = $this->customlib->getBillApproverList();
        $this->data['main'] = 'scan/edit_bill_approver';
        $this->load->view('layout/template', $this->data);
    }
    public function changeBillApprover()
    {
        $Scan_Id = $this->input->post('Scan_Id');
        $Bill_Approver = $this->input->post('Bill_Approver');
        $this->db->where('Scan_Id', $Scan_Id);
        $query = $this->db->update('scan_file', ['Bill_Approver' => $Bill_Approver]);
        if ($query) {
            echo json_encode(['status' => 200]);
        } else {
            echo json_encode(['status' => 400]);
        }
    }
    function bill_rejected()
    {
        $this->data['list'] = $this->Scan_model->bill_rejected_list();
        $this->data['bill_approver_list'] = $this->customlib->getBillApproverList();
        $this->data['main'] = 'scan/bill_rejected';
        $this->load->view('layout/template', $this->data);
    }
    function bill_trashed()
    {
        $this->data['list'] = $this->Scan_model->bill_trashed_list();
        $this->data['bill_approver_list'] = $this->customlib->getBillApproverList();
        $this->data['main'] = 'scan/bill_trashed';
        $this->load->view('layout/template', $this->data);
    }
    function all_trashed_bill()
    {
        $this->data['list'] = $this->Scan_model->all_trashed_bill_lists();
        $this->data['bill_approver_list'] = $this->customlib->getBillApproverList();
        $this->data['main'] = 'scan/all_trashed_bill';
        $this->load->view('layout/template', $this->data);
    }
    public function resend_scan_bill()
    {
        $Scan_Id = $this->input->post('Scan_Id');
        $this->db->where('Scan_Id', $Scan_Id);
        $query = $this->db->update('scan_file', ['Bill_Approved' => 'N', 'Bill_Approver_Date' => null]);
        if ($query) {
            echo json_encode(['status' => 200]);
        } else {
            echo json_encode(['status' => 400]);
        }
    }
    public function trash_scan_bill()
    {
        $Scan_Id = $this->input->post('Scan_Id');
        // Basic input validation
        if (empty($Scan_Id)) {
            echo json_encode(['status' => 400, 'message' => 'Scan ID is required']);
            return;
        }
        $this->db->where('Scan_Id', $Scan_Id);
        // Check if the record exists before updating
        $this->db->select('Scan_Id');
        $query = $this->db->get('scan_file');
        if ($query->num_rows() == 0) {
            echo json_encode(['status' => 404, 'message' => 'Scan not found']);
            return;
        }
        $data = ['Is_Deleted' => 'Y', 'Delete_Date' => date('Y-m-d H:i:s'), 'Deleted_By' => $this->session->userdata('user_id')];
        $this->db->where('Scan_Id', $Scan_Id);
        $query = $this->db->update('scan_file', $data);
        if ($query) {
            echo json_encode(['status' => 200, 'message' => 'Scan trashed successfully']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Error trashing scan']);
        }
    }
    public function get_bill_approvers()
    {
        $location_id = $this->input->post('location_id');
        $Group_Id = $this->session->userdata('group_id');
        if ($Group_Id == 16) {
            $this->db->select('user_id, first_name, last_name');
            $this->db->from('users');
            $this->db->where("FIND_IN_SET('2790', firm_id) >", 0);
            $this->db->where('role', 'bill_approver');
            $approvers = $this->db->get()->result_array();
            echo json_encode($approvers);
        } else {
            if ($location_id) {
                $this->db->select('user_id, first_name, last_name');
                $this->db->from('users');
                $this->db->where("FIND_IN_SET('$location_id', location_id) >", 0);
                $this->db->where('role', 'bill_approver');
                $approvers = $this->db->get()->result_array();
                echo json_encode($approvers);
            } else {
                echo json_encode([]);
            }
        }
    }
}
