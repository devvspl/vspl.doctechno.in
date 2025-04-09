<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Country extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
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
        $this->session->set_userdata('sub_menu', 'country');
        $this->data['main'] = 'country/countrylist';
        $this->data['countrylist'] = $this->Country_model->get_country_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('country_name', 'Country Name', 'trim|required');
        $this->form_validation->set_rules('country_code', 'Country Code', 'trim|required');
    
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'country/countrylist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['country_name'] = $this->input->post('country_name');
            $data['country_code'] = $this->input->post('country_code');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            
            $result = $this->Country_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Country Created Successfully.</p>');
                redirect('country');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('country');
            }
        }
    }

    function delete($id)
    {
        $this->Country_model->delete($id);
        redirect('country');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'country');
        $this->data['countrylist'] = $this->Country_model->get_country_list();
        $this->data['country'] = $this->Country_model->get_country_list($id);
        $this->data['country_id'] = $id;
        $this->data['main'] = 'country/countryedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['country_id'] = $id;
        $data['country_name'] = $this->input->post('country_name');
        $data['country_code'] = $this->input->post('country_code');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Country_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Country Updated Successfully.</p>');
            redirect('country');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('country');
        }
    }

   

}
