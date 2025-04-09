<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Region extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Region_model');
        $this->load->model('State_model');
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
        $this->session->set_userdata('sub_menu', 'region');
        $this->data['main'] = 'region/regionlist';
        $this->data['regionlist'] = $this->Region_model->get_region_list();
        $this->data['statelist'] = $this->State_model->get_state_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('region_name', 'Region Name', 'trim|required');
        $this->form_validation->set_rules('region_code', 'Region Code', 'trim|required');
        $this->form_validation->set_rules('state_id', 'State', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'region/regionlist';
            $this->data['statelist'] = $this->State_model->get_state_list();
            $this->load->view('layout/template', $this->data);
        } else {
            $data['region_name'] = $this->input->post('region_name');
            $data['region_code'] = $this->input->post('region_code');
            $data['state_id'] = $this->input->post('state_id');
            $data['region_numeric_code'] = $this->input->post('region_numeric_code');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');

            $result = $this->Region_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Region Created Successfully.</p>');
                redirect('region');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('region');
            }
        }
    }

    function delete($id)
    {
        $this->Region_model->delete($id);
        redirect('region');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'region');
        $this->data['regionlist'] = $this->Region_model->get_region_list();
        $this->data['statelist'] = $this->State_model->get_state_list();
        $this->data['region'] = $this->Region_model->get_region_list($id);
        $this->data['region_id'] = $id;
        $this->data['main'] = 'region/regionedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['region_id'] = $id;
        $data['state_id'] = $this->input->post('state_id');
        $data['region_name'] = $this->input->post('region_name');
        $data['region_code'] = $this->input->post('region_code');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Region_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Region Updated Successfully.</p>');
            redirect('region');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('region');
        }
    }

    function get_region_by_state_id()
    {
        $state_id = $this->input->post('state_id');
        $region_list = $this->Region_model->get_region_by_state_id($state_id);
        echo json_encode($region_list);
    }
}
