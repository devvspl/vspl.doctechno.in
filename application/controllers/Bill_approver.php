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
        // $this->form_validation->set_rules('location[]', 'Location', 'trim|required');
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
        // $user_location = $this->db->select('location_id')
        //     ->get_where('users', array('user_id' => $this->session->userdata('user_id')))
        //     ->row()
        //     ->location_id;
        // $user_location_array = explode(',', $user_location);

        $bill_list = $this->db->where('Bill_Approved', 'N')
            // ->where_in('Location', $user_location_array)
           ->join('master_work_location', 'master_work_location.location_id = scan_file.Location', 'left')
			->where('Bill_Approver',$this->session->userdata('user_id'))
            ->get('scan_file')
            ->result_array();

        $this->data['bill_list'] = $bill_list;
        $this->data['main'] = 'bill_approver/pending_bill_list';
        $this->load->view('layout/template', $this->data);
    }

    public function my_approved_bill()
    {
        $bill_list = $this->db->where('Bill_Approved', 'Y')->where('Bill_Approver', $this->session->userdata('user_id'))
            ->join('master_work_location', 'master_work_location.location_id = scan_file.Location', 'left')
            ->get('scan_file')
            ->result_array();
        $this->data['bill_list'] = $bill_list;
        $this->data['main'] = 'bill_approver/approved_bill_list';
        $this->load->view('layout/template', $this->data);
    }

    public function rejected_bill_by_me()
    {
        $bill_list = $this->db->where('Bill_Approved', 'R')
            ->where('Bill_Approver', $this->session->userdata('user_id'))
            ->join('master_work_location', 'master_work_location.location_id = scan_file.Location', 'left')
            ->get('scan_file')
            ->result_array();
        $this->data['bill_list'] = $bill_list;
        $this->data['main'] = 'bill_approver/rejected_bill_list';
        $this->load->view('layout/template', $this->data);
    }

    public function reject_bill($Scan_Id)
    {
        $user_id = $this->session->userdata('user_id');
        $Reject_Remark = $this->input->post('Remark');
        $this->db->where('Scan_Id', $Scan_Id);
        $result = $this->db->update('scan_file', array('Bill_Approved' => 'R', 'Bill_Approver' => $user_id, 'Bill_Approver_Remark' => $Reject_Remark, 'Bill_Approver_Date' => date('Y-m-d')));
        if ($result) {
            echo json_encode(array('status' => '200', 'message' => 'Bill Rejected Successfully.'));
        } else {
            echo json_encode(array('status' => '400', 'message' => 'Something went wrong. Please try again.'));
        }
    }

    public function approve_bill($Scan_Id)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Scan_Id', $Scan_Id);
        $result = $this->db->update('scan_file', array('Bill_Approved' => 'Y', 'is_extract'=> 'N', 'Bill_Approver' => $user_id, 'Bill_Approver_Date' => date('Y-m-d')));
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Bill Approved Successfully.</p>');
            redirect('pending_bill_approve');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('pending_bill_approve');
        }
    }
    public function get_departments() {
        $company_ids = $this->input->post('company_ids');
        $departments = $this->Bill_approver_model->get_departments_by_company_ids($company_ids);
        echo json_encode($departments);
    }
    
}
