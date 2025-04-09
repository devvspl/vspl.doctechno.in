<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Business_entity extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Business_entity_model');
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
        $this->session->set_userdata('sub_menu', 'business_entity');
        $this->data['main'] = 'business_entity/business_entitylist';
        $this->data['business_entitylist'] = $this->Business_entity_model->get_business_entity_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('business_entity_name', 'Business entity Name', 'trim|required');
        $this->form_validation->set_rules('business_entity_code', 'Business entity Code', 'trim|required');
        $this->form_validation->set_rules('business_entity_group', 'Business entity group', 'trim|required');
    
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'business_entity/business_entitylist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['business_entity_name'] = $this->input->post('business_entity_name');
            $data['business_entity_code'] = $this->input->post('business_entity_code');
            $data['business_entity_group'] = $this->input->post('business_entity_group');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            
            $result = $this->Business_entity_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Business_entity Created Successfully.</p>');
                redirect('business_entity');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('business_entity');
            }
        }
    }

    function delete($id)
    {
        $this->Business_entity_model->delete($id);
        redirect('business_entity');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'business_entity');
        $this->data['business_entitylist'] = $this->Business_entity_model->get_business_entity_list();
        $this->data['business_entity'] = $this->Business_entity_model->get_business_entity_list($id);
        $this->data['business_entity_id'] = $id;
        $this->data['main'] = 'business_entity/business_entityedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['business_entity_id'] = $id;
        $data['business_entity_name'] = $this->input->post('business_entity_name');
        $data['business_entity_code'] = $this->input->post('business_entity_code');
        $data['business_entity_group'] = $this->input->post('business_entity_group');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Business_entity_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Business_entity Updated Successfully.</p>');
            redirect('business_entity');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('business_entity');
        }
    }

   

}


