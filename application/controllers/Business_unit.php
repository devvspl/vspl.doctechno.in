<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Business_unit extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Business_unit_model');
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
        $this->session->set_userdata('sub_menu', 'business_unit');
        $this->data['main'] = 'business_unit/business_unitlist';
        $this->data['business_unitlist'] = $this->Business_unit_model->get_business_unit_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('business_unit_name', 'Business Unit Name', 'trim|required');
        $this->form_validation->set_rules('business_unit_code', 'Business Unit Code', 'trim|required');
        $this->form_validation->set_rules('business_unit_group', 'Business Unit Group', 'trim');
    
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'business_unit/business_unitlist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['business_unit_name'] = $this->input->post('business_unit_name');
            $data['business_unit_code'] = $this->input->post('business_unit_code');
            $data['business_unit_group'] = $this->input->post('business_unit_group');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            
            $result = $this->Business_unit_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Business Unit Created Successfully.</p>');
                redirect('business_unit');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('business_unit');
            }
        }
    }

    function delete($id)
    {
        $this->Business_unit_model->delete($id);
        redirect('business_unit');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'business_unit');
        $this->data['business_unitlist'] = $this->Business_unit_model->get_business_unit_list();
        $this->data['business_unit'] = $this->Business_unit_model->get_business_unit_list($id);
        $this->data['business_unit_id'] = $id;
        $this->data['main'] = 'business_unit/business_unitedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['business_unit_id'] = $id;
        $data['business_unit_name'] = $this->input->post('business_unit_name');
        $data['business_unit_code'] = $this->input->post('business_unit_code');
        $data['business_unit_group'] = $this->input->post('business_unit_group');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Business_unit_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Business Unit Updated Successfully.</p>');
            redirect('business_unit');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('business_unit');
        }
    }

   

}


