<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Scan extends CI_Controller
{
    protected $year_id;
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Scan_model');
        $this->year_id = $this->session->userdata('year_id');
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
        $scanned_by = $this->session->userdata('user_id');
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
            $this->db->insert("y{$this->year_id}_scan_file", ['group_id' => $this->session->userdata('group_id'), 'scanned_by' => $scanned_by, 'Document_name' => $document_name, 'location_id' => $location, 'bill_approver_id' => $bill_approver, 'file_name' => $var_temp_name, 'file_extension' => $file_ext, 'file_path' => base_url() . 'uploads/temp/' . $var_temp_name, 'secondary_file_path' => 'uploads/temp/' . $var_temp_name, 'year' => $year, 'scan_date' => date('Y-m-d H:i:s'),]);
            $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
            redirect('scan/upload_supporting/' . $this->db->insert_id());
        }
    }
    public function temp_upload_main()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('location', 'location_id', 'required|trim');
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
            $config['upload_path'] = './Uploads/temp/';
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
                $this->db->trans_start();
                $data = ['group_id' => $this->session->userdata('group_id'), 'location_id' => $location, 'bill_approver_id' => $bill_approver, 'temp_scan_by' => $Temp_Scan_By, 'is_temp_scan' => 'Y', 'is_scan_complete' => 'N', 'file_name' => $var_temp_name, 'file_extension' => $file_ext, 'file_path' => base_url() . 'Uploads/temp/' . $var_temp_name, 'secondary_file_path' => 'Uploads/temp/' . $var_temp_name, 'year' => $year, 'temp_scan_date' => date('Y-m-d H:i:s')];
                $this->db->insert("y{$this->year_id}_scan_file", $data);
                $insert_id = $this->db->insert_id();
                $file_org_name = preg_replace('/[^A-Za-z0-9\s\-]/', '', pathinfo($file, PATHINFO_FILENAME));
                $file_org_name = str_replace(' ', '_', $file_org_name);
                $formatted_date = date('d_My_His');
                $document_name = $insert_id . '_' . ucfirst($file_org_name) . '_' . $formatted_date;
                $this->db->where('scan_id', $insert_id);
                $this->db->update("y{$this->year_id}_scan_file", ['document_name' => $document_name]);
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to upload file due to database error</div>');
                    redirect('temp_scan');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
                    redirect('scan/temp_upload_supporting/' . $insert_id);
                }
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
        $year_id = $this->session->userdata('year_id');
        $main_file = $this->db->where('scan_id', $id)->get("y{$year_id}_scan_file")->row();
        $supporting_files = $this->db->where('scan_id', $id)->get('support_file')->result();
        $this->data['scan_id'] = $id;
        $this->data['main_file'] = $main_file;
        $this->data['supporting_files'] = $supporting_files;
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
        $scan_id = $this->input->post('scan_id');
        $file = $_FILES['support_file']['name'];
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        $year = date('Y');
        $config['upload_path'] = './Uploads/temp/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['max_size'] = 8192;
        $var_temp_name = time() . '.' . $file_ext;
        $config['file_name'] = $var_temp_name;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('support_file')) {
            $error = ['error' => $this->upload->display_errors()];
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error['error'] . '</div>');
            if ($_SESSION['role'] != 'super_scan') {
                redirect('scan/upload_supporting/' . $scan_id);
            } else {
                redirect('/');
            }
        } else {
            $this->db->trans_start();
            $data = ['scan_id' => $scan_id, 'file_name' => $var_temp_name, 'file_extension' => $file_ext, 'file_path' => base_url() . 'Uploads/temp/' . $var_temp_name, 'secondary_file_path' => 'Uploads/temp/' . $var_temp_name];
            $this->db->insert('support_file', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to upload file due to database error</div>');
                if ($_SESSION['role'] != 'super_scan') {
                    redirect('scan/upload_supporting/' . $scan_id);
                } else {
                    redirect('/');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
                redirect('scan/upload_supporting/' . $scan_id);
            }
        }
    }
    public function temp_upload_support()
    {
        $scan_id = $this->input->post('scan_id');
        $file = $_FILES['support_file']['name'];
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        $year = date('Y');
        $config['upload_path'] = './Uploads/temp/';
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['max_size'] = 8192;
        $var_temp_name = time() . '.' . $file_ext;
        $config['file_name'] = $var_temp_name;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('support_file')) {
            $error = ['error' => $this->upload->display_errors()];
            $this->session->set_flashdata('message', '<div class="alert alert-danger">' . $error['error'] . '</div>');
            redirect('scan/temp_upload_supporting/' . $scan_id);
        } else {
            $this->db->trans_start();
            $data = ['scan_id' => $scan_id, 'file_name' => $var_temp_name, 'file_extension' => $file_ext, 'file_path' => base_url() . 'Uploads/temp/' . $var_temp_name, 'secondary_file_path' => 'Uploads/temp/' . $var_temp_name];
            $this->db->insert('support_file', $data);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to upload file due to database error</div>');
                redirect('scan/temp_upload_supporting/' . $scan_id);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success">File Uploaded Successfully</div>');
                redirect('scan/temp_upload_supporting/' . $scan_id);
            }
        }
    }
    public function delete_all()
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $scan_id = $this->input->post('scan_id');
        $this->db->delete('support_file', ['scan_id' => $scan_id]);
        $this->db->delete("y{$this->year_id}_scan_file", ['scan_id' => $scan_id]);
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
        $this->db->where('scan_id', $scan_id);
        $this->db->update("y{$this->year_id}_scan_file", ['is_final_submitted' => 'Y']);
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
        $this->db->where('scan_id', $scan_id);
        $this->db->update("y{$this->year_id}_scan_file", ['is_scan_resend' => 'N']);
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
            $query = $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", ['file_name' => $var_temp_name, 'file_extension' => $file_ext, 'file_path' => base_url() . 'uploads/temp/' . $var_temp_name, 'secondary_file_path' => 'uploads/temp/' . $var_temp_name]);
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
        $this->data['locationlist'] = $this->customlib->getWorkLocationList();
        $this->data['doctypeList'] = $this->db->where(['status' => 'A', 'is_deleted' => 'N'])->get('master_temp_doctype')->result_array();
        $this->data['firmList'] = $this->Scan_model->get_firm_list();
        $this->data['departmentList'] = $this->Scan_model->get_department_list();
        $group_id = $this->db->where('scan_id', $id)->get("y{$this->year_id}_scan_file")->row()->group_id ?? null;
        $bill_approver_id = $this->db->where('scan_id', $id)->get("y{$this->year_id}_scan_file")->row()->bill_approver_id ?? null;
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
        $document_name  = $this->input->post('document_name');
        $group_id = $this->input->post('group_id');
        $location = $this->input->post('location');
        $scan_doctype_id = $this->input->post('doctype_id');
        $department_id = $this->input->post('department_id');
        $firm_id = $this->input->post('firm_id');
        $bill_voucher_date_1 = $this->input->post('bill_voucher_date');
        $bill_voucher_date = $this->reformat_date($bill_voucher_date_1);
        $bill_no_voucher_no = $this->input->post('bill_no_voucher_no');
        $data = ['scanned_by' => $user_id, 'is_scan_complete' => 'Y', 'document_name' => $document_name , 'scan_date' => date('Y-m-d H:i:s'), 'is_final_submitted' => 'Y', 'location_id' => $location, 'scan_doctype_id' => $scan_doctype_id, 'department_id' => $department_id, 'firm_id' => $firm_id, 'bill_voucher_date' => $bill_voucher_date, 'bill_no_voucher_no' => $bill_no_voucher_no,];
        $result = $this->db->where('scan_id', $id)->update("y{$this->year_id}_scan_file", $data);
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
        $this->db->where('scan_id', $id);
        $result = $this->db->update("y{$this->year_id}_scan_file", ['is_temp_scan_rejected' => 'Y', 'temp_scan_reject_remark' => $remark, 'temp_scan_reject_date' => date('Y-m-d'), 'temp_scan_rejected_by' => $user_id]);
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
        $scan_id = $this->input->post('scan_id');
        $bill_approver_id = $this->input->post('bill_approver_id');
        $this->db->where('scan_id', $scan_id);
        $query = $this->db->update("y{$this->year_id}_scan_file", ['bill_approver_id' => $bill_approver_id]);
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
        $scan_id = $this->input->post('scan_id');
        $this->db->where('scan_id', $scan_id);
        $query = $this->db->update("y{$this->year_id}_scan_file", ['bill_approval_status' => 'N', 'bill_approved_date' => null]);
        if ($query) {
            echo json_encode(['status' => 200]);
        } else {
            echo json_encode(['status' => 400]);
        }
    }
    public function trash_scan_bill()
    {
        $scan_id = $this->input->post('scan_id');
        if (empty($scan_id)) {
            echo json_encode(['status' => 400, 'message' => 'Scan ID is required']);
            return;
        }
        $this->db->where('scan_id', $scan_id);
        $this->db->select('scan_id');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        if ($query->num_rows() == 0) {
            echo json_encode(['status' => 404, 'message' => 'Scan not found']);
            return;
        }
        $data = ['is_deleted' => 'Y', 'deleted_date' => date('Y-m-d H:i:s'), 'deleted_by' => $this->session->userdata('user_id')];
        $this->db->where('scan_id', $scan_id);
        $query = $this->db->update("y{$this->year_id}_scan_file", $data);
        if ($query) {
            echo json_encode(['status' => 200, 'message' => 'Scan trashed successfully']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Error trashing scan']);
        }
    }
    public function get_bill_approvers()
    {
        $location_id = $this->input->post('location_id');
        $group_id = $this->session->userdata('group_id');
        if ($group_id == 16) {
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
