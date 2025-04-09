<?php
defined('BASEPATH') or exit('No direct script access allowed');

class State extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('State_model');
        $this->load->model('Country_model');
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
        $this->session->set_userdata('sub_menu', 'state');
        $this->data['main'] = 'state/statelist';
        $this->data['statelist'] = $this->State_model->get_state_list();
        $this->data['countrylist'] = $this->Country_model->get_country_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('state_name', 'State Name', 'trim|required');
        $this->form_validation->set_rules('state_code', 'State Code', 'trim|required');
        $this->form_validation->set_rules('state_numeric_code', 'Stat Code', 'trim|required');
        $this->form_validation->set_rules('country_id', 'Country', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'state/statelist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['state_name'] = $this->input->post('state_name');
            $data['state_code'] = $this->input->post('state_code');
            $data['country_id'] = $this->input->post('country_id');
            $data['state_numeric_code'] = $this->input->post('state_numeric_code');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');

            $result = $this->State_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">State Created Successfully.</p>');
                redirect('state');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('state');
            }
        }
    }

    function delete($id)
    {
        $this->State_model->delete($id);
        redirect('state');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'state');
        $this->data['statelist'] = $this->State_model->get_state_list();
        $this->data['countrylist'] = $this->Country_model->get_country_list();
        $this->data['state'] = $this->State_model->get_state_list($id);
        $this->data['state_id'] = $id;
        $this->data['main'] = 'state/stateedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['state_id'] = $id;
        $data['country_id'] = $this->input->post('country_id');
        $data['state_name'] = $this->input->post('state_name');
        $data['state_code'] = $this->input->post('state_code');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->State_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">State Updated Successfully.</p>');
            redirect('state');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('state');
        }
    }

    function get_state_by_country_id()
    {
        $country_id = $this->input->post('country_id');
        $state_list = $this->State_model->get_state_by_country_id($country_id);
        echo json_encode($state_list);
    }
}
