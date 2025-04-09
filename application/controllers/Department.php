<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Department extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Department_model');
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
        $this->session->set_userdata('sub_menu', 'department');
        $this->data['main'] = 'department/departmentlist';
        $this->data['departmentlist'] = $this->Department_model->get_department_list();
        $this->data['companylist'] = $this->Department_model->get_companylist();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('department_name', 'Department Name', 'trim|required');
        $this->form_validation->set_rules('department_code', 'Department Code', 'trim|required');
        $this->form_validation->set_rules('company_id', 'Company', 'trim|required');
    
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'department/departmentlist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['department_name'] = $this->input->post('department_name');
            $data['department_code'] = $this->input->post('department_code');
            $data['company_id'] = $this->input->post('company_id');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            
            $result = $this->Department_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Department Created Successfully.</p>');
                redirect('department');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('department');
            }
        }
    }

    function delete($id)
    {
        $this->Department_model->delete($id);
        redirect('department');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'department');
        $this->data['departmentlist'] = $this->Department_model->get_department_list();
        $this->data['department'] = $this->Department_model->get_department_list($id);
        $this->data['companylist'] = $this->Department_model->get_companylist();
        $this->data['department_id'] = $id;
        $this->data['main'] = 'department/departmentedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['department_id'] = $id;
        $data['department_name'] = $this->input->post('department_name');
        $data['department_code'] = $this->input->post('department_code');
        $data['company_id'] = $this->input->post('company_id');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Department_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Department Updated Successfully.</p>');
            redirect('department');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('department');
        }
    }

   

}
