<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Temp_doctype extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Temp_doctype_model');
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
        $this->session->set_userdata('sub_menu', 'Temp_doctype');
        $this->data['main'] = 'Temp_doctype/Temp_doctypelist';
        $this->data['Temp_doctypelist'] = $this->Temp_doctype_model->get_Temp_doctype_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('file_type', 'Temp Doctype Name', 'trim|required');
    
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'Temp_doctype/Temp_doctypelist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['file_type'] = $this->input->post('file_type');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            
            $result = $this->Temp_doctype_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Temp_doctype Created Successfully.</p>');
                redirect('Temp_doctype');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('Temp_doctype');
            }
        }
    }

    function delete($id)
    {
        $this->Temp_doctype_model->delete($id);
        redirect('Temp_doctype');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'Temp_doctype');
        $this->data['Temp_doctypelist'] = $this->Temp_doctype_model->get_Temp_doctype_list();
        $this->data['Temp_doctype'] = $this->Temp_doctype_model->get_Temp_doctype_list($id);
        $this->data['type_id'] = $id;
        $this->data['main'] = 'Temp_doctype/Temp_doctypeedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['type_id'] = $id;
        $data['file_type'] = $this->input->post('file_type');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Temp_doctype_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Temp_doctype Updated Successfully.</p>');
            redirect('Temp_doctype');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('Temp_doctype');
        }
    }

   

}
