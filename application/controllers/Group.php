<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Group extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Group_model');
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
        $this->session->set_userdata('sub_menu', 'group');
        $this->data['main'] = 'group/grouplist';
        $this->data['grouplist'] = $this->Group_model->get_group_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('group_name', 'Group Name', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'group/grouplist';
            $this->data['grouplist'] = $this->Group_model->get_group_list();
            $this->load->view('layout/template', $this->data);
        } else {
            $data['group_name'] = $this->input->post('group_name');
            $result = $this->Group_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Group Created Successfully.</p>');
                redirect('group');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('group');
            }
        }
    }

    function delete($id)
    {
        $this->Group_model->delete($id);
        redirect('group');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'group');
        $this->data['grouplist'] = $this->Group_model->get_group_list();
        $this->data['group'] = $this->Group_model->get_group_list($id);
        $this->data['group_id'] = $id;
        $this->data['main'] = 'group/groupedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['group_id'] = $id;
        $data['group_name'] = $this->input->post('group_name');
        $result = $this->Group_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Group Updated Successfully.</p>');
            redirect('group');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('group');
        }
    }

   

}
