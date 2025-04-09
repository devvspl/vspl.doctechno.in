<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Activity extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('activity_model');
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
        $this->session->set_userdata('sub_menu', 'activity');
        $this->data['main'] = 'activity/activitylist';
        $this->data['activitylist'] = $this->activity_model->get_activity_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('activity_name', 'Activity Name', 'trim|required');
        $this->form_validation->set_rules('activity_code', 'Activity Code', 'trim|required');
        $this->form_validation->set_rules('activity_group', 'Activity Group', 'trim');
    
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'activity/activitylist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['activity_name'] = $this->input->post('activity_name');
            $data['activity_code'] = $this->input->post('activity_code');
            $data['activity_group'] = $this->input->post('activity_group');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            
            $result = $this->activity_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Activity Created Successfully.</p>');
                redirect('activity');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('activity');
            }
        }
    }

    function delete($id)
    {
        $this->activity_model->delete($id);
        redirect('activity');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'activity');
        $this->data['activitylist'] = $this->activity_model->get_activity_list();
        $this->data['activity'] = $this->activity_model->get_activity_list($id);
        $this->data['activity_id'] = $id;
        $this->data['main'] = 'activity/activityedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['activity_id'] = $id;
        $data['activity_name'] = $this->input->post('activity_name');
        $data['activity_code'] = $this->input->post('activity_code');
        $data['activity_group'] = $this->input->post('activity_group');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->activity_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Activity Updated Successfully.</p>');
            redirect('activity');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('activity');
        }
    }

   

}
