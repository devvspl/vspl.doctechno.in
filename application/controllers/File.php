<?php
defined('BASEPATH') or exit('No direct script access allowed');

class File extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('File_model');
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
        $this->session->set_userdata('sub_menu', 'file');
        $this->data['main'] = 'file/filelist';
        $this->data['filelist'] = $this->File_model->get_file_list();
        $this->data['companylist'] = $this->Department_model->get_companylist();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('file_name', 'File Name', 'trim|required');
        $this->form_validation->set_rules('file_code', 'File Code', 'trim|required');
        $this->form_validation->set_rules('company_id', 'Company', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'file/filelist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['file_name'] = $this->input->post('file_name');
            $data['file_code'] = $this->input->post('file_code');
            $data['company_id'] = $this->input->post('company_id');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');

            $result = $this->File_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">File Created Successfully.</p>');
                redirect('file');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('file');
            }
        }
    }

    function delete($id)
    {
        $this->File_model->delete($id);
        redirect('file');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'file');
        $this->data['filelist'] = $this->File_model->get_file_list();
        $this->data['file'] = $this->File_model->get_file_list($id);
        $this->data['companylist'] = $this->Department_model->get_companylist();
        $this->data['file_id'] = $id;
        $this->data['main'] = 'file/fileedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['file_id'] = $id;
        $data['file_name'] = $this->input->post('file_name');
        $data['file_code'] = $this->input->post('file_code');
        $data['company_id'] = $this->input->post('company_id');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->File_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">File Updated Successfully.</p>');
            redirect('file');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('file');
        }
    }
}
