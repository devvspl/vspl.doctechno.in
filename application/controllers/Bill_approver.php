<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bill_approver extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model(array('Bill_approver_model', 'Location_model'));
    }

    private function logged_in()
    {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'bill_approver');
        $this->data['main'] = 'bill_approver/bill_approver_list';
        $this->data['list'] = $this->Bill_approver_model->get_user_list();
        $this->data['firmlist'] = $this->Bill_approver_model->get_company();
        $this->data['locationlist'] = $this->customlib->getWorkLocationList();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        // $this->form_validation->set_rules('location[]', 'location_id', 'trim|required');
        $this->form_validation->set_rules('firm[]', 'Company', 'trim');
        $this->form_validation->set_rules('department[]', 'Department', 'trim');
        if ($this->form_validation->run() == false) {
            $this->data['list'] = $this->Bill_approver_model->get_user_list();
            $this->data['locationlist'] = $this->customlib->getWorkLocationList();
            $this->data['firmlist'] = $this->Bill_approver_model->get_company();
            $this->data['main'] = 'bill_approver/bill_approver_list';
            $this->load->view('layout/template', $this->data);
        } else {

            $data['created_by'] = $this->session->userdata('user_id');
            $data['first_name'] = $this->input->post('first_name');
            $data['last_name'] = $this->input->post('last_name');
            $data['username'] = $this->input->post('username');
            $data['password'] = md5($this->input->post('password'));
            $data['role'] = 'bill_approver';
            $data['location_id'] = is_array($this->input->post('location')) ? implode(',', $this->input->post('location')) : $this->input->post('location');
            $data['firm_id'] = is_array($this->input->post('firm')) ? implode(',', $this->input->post('firm')) : $this->input->post('firm');
            $data['department_id'] = is_array($this->input->post('department')) ? implode(',', $this->input->post('department')) : $this->input->post('department');
            $data['group_id'] = 0;
            $result = $this->Bill_approver_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Bill Approver Created Successfully.</p>');
                redirect('bill_approver');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('bill_approver');
            }
        }
    }

    function delete($id)
    {
        $this->Bill_approver_model->delete($id);
        redirect('bill_approver');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'bill_approver');
        $this->session->set_userdata('sub_menu', 'bill_approver');
        $this->data['list'] = $this->Bill_approver_model->get_user_list();
        $this->data['locationlist'] = $this->customlib->getWorkLocationList();
        $this->data['departmentlist'] = $this->customlib->getDepartmentList();
        $this->data['companylist'] = $this->customlib->getCompanyList();
        $this->data['user'] = $this->Bill_approver_model->get_user_list($id);
        $this->data['id'] = $id;
        $this->data['main'] = 'bill_approver/bill_approver_edit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['user_id'] = $id;
        $data['first_name'] = $this->input->post('first_name');
        $data['last_name'] = $this->input->post('last_name');
        $data['username'] = $this->input->post('username');


        $location = $this->input->post('location');
        $data['location_id'] = is_array($location) ? implode(',', $location) : $location;
        $data['firm_id'] = is_array($this->input->post('firm')) ? implode(',', $this->input->post('firm')) : $this->input->post('firm');
        $data['department_id'] = is_array($this->input->post('department')) ? implode(',', $this->input->post('department')) : $this->input->post('department');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $result = $this->Bill_approver_model->update($data);

        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Bill Approver Updated Successfully.</p>');
            redirect('bill_approver');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('bill_approver');
        }
    }


    public function pending_bill_approve()
    {

        $bill_list = $this->db->where('bill_approval_status', 'N')
            ->join('master_work_location', 'master_work_location.location_id = y{$this->year_id}_scan_file.location_id', 'left')
            ->where('bill_approver_id', $this->session->userdata('user_id'))
            ->get("y{$this->year_id}_scan_file")
            ->result_array();
        $this->data['bill_list'] = $bill_list;
        $this->data['main'] = 'bill_approver/pending_bill_list';
        $this->load->view('layout/template', $this->data);
    }

    public function my_approved_bill()
    {
        $bill_list = $this->db->where('bill_approval_status', 'Y')->where('bill_approver_id', $this->session->userdata('user_id'))
            ->join('master_work_location', 'master_work_location.location_id = y{$this->year_id}_scan_file.location_id', 'left')
            ->get("y{$this->year_id}_scan_file")
            ->result_array();
        $this->data['bill_list'] = $bill_list;
        $this->data['main'] = 'bill_approver/approved_bill_list';
        $this->load->view('layout/template', $this->data);
    }

    public function rejected_bill_by_me()
    {
        $bill_list = $this->db->where('bill_approval_status', 'R')
            ->where('bill_approver_id', $this->session->userdata('user_id'))
            ->join('master_work_location', 'master_work_location.location_id = y{$this->year_id}_scan_file.location_id', 'left')
            ->get("y{$this->year_id}_scan_file")
            ->result_array();
        $this->data['bill_list'] = $bill_list;
        $this->data['main'] = 'bill_approver/rejected_bill_list';
        $this->load->view('layout/template', $this->data);
    }

    public function reject_bill($scan_id)
    {
        $user_id = $this->session->userdata('user_id');
        $Reject_Remark = $this->input->post('Remark');
        $this->db->where('scan_id', $scan_id);
        if ($_SESSION['role'] === 'scan_admin') {
            $data = array('is_temp_scan_rejected' => 'Y', 'temp_scan_rejected_by' => $user_id, 'temp_scan_reject_remark' => $Reject_Remark, 'temp_scan_reject_date' => date('Y-m-d'));
        } else {
            $data = array('bill_approval_status' => 'R', 'bill_approver_id' => $user_id, 'bill_approver_remark' => $Reject_Remark, 'bill_approved_date' => date('Y-m-d'));
        }
        $result = $this->db->update("y{$this->year_id}_scan_file", $data);
        echo $this->db->last_query();
        exit;
        if ($result) {
            echo json_encode(array('status' => '200', 'message' => 'Bill Rejected Successfully.'));
        } else {
            echo json_encode(array('status' => '400', 'message' => 'Something went wrong. Please try again.'));
        }
    }

    public function approve_bill($scan_id)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('scan_id', $scan_id);
        $result = $this->db->update("y{$this->year_id}_scan_file", array('bill_approval_status' => 'Y', 'bill_approver_id' => $user_id, 'bill_approved_date' => date('Y-m-d')));
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Bill Approved Successfully.</p>');
            redirect('pending_bill_approve');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('pending_bill_approve');
        }
    }
    public function get_departments()
    {
        $company_ids = $this->input->post('company_ids');
        $departments = $this->Bill_approver_model->get_departments_by_company_ids($company_ids);
        echo json_encode($departments);
    }

}
