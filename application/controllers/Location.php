<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Location extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Location_model');
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
        $this->session->set_userdata('sub_menu', 'location');
        $this->data['main'] = 'location/locationlist';
        $this->data['locationlist'] = $this->Location_model->get_location_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('location_name', 'Location Name', 'trim|required');
        $this->form_validation->set_rules('focus_code', 'Location Code', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'location/locationlist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['location_name'] = $this->input->post('location_name');
            $data['focus_code'] = $this->input->post('focus_code');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            
            $result = $this->Location_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Work Location Created Successfully.</p>');
                redirect('location');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('location');
            }
        }
    }

    function delete($id)
    {
        $this->Location_model->delete($id);
        redirect('location');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'location');
        $this->data['locationlist'] = $this->Location_model->get_location_list();
        $this->data['location'] = $this->Location_model->get_location_list($id);
        $this->data['location_id'] = $id;
        $this->data['main'] = 'location/locationedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['location_id'] = $id;
        $data['location_name'] = $this->input->post('location_name');
        $data['focus_code'] = $this->input->post('focus_code');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Location_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Work Location Updated Successfully.</p>');
            redirect('location');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('location');
        }
    }

   

}
