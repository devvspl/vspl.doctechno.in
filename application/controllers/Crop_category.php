<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crop_category extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Crop_category_model');
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
        $this->session->set_userdata('sub_menu', 'crop_category');
        $this->data['main'] = 'crop_category/crop_categorylist';
        $this->data['crop_categorylist'] = $this->Crop_category_model->get_crop_category_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('crop_category_name', 'Crop Category Name', 'trim|required');
        $this->form_validation->set_rules('crop_category_code', 'Crop Category Code', 'trim|required');
    
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'crop_category/crop_categorylist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['crop_category_name'] = $this->input->post('crop_category_name');
            $data['crop_category_code'] = $this->input->post('crop_category_code');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            
            $result = $this->Crop_category_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Crop Category Created Successfully.</p>');
                redirect('crop_category');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('crop_category');
            }
        }
    }

    function delete($id)
    {
        $this->Crop_category_model->delete($id);
        redirect('crop_category');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'crop_category');
        $this->data['crop_categorylist'] = $this->Crop_category_model->get_crop_category_list();
        $this->data['crop_category'] = $this->Crop_category_model->get_crop_category_list($id);
        $this->data['crop_category_id'] = $id;
        $this->data['main'] = 'crop_category/crop_categoryedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['crop_category_id'] = $id;
        $data['crop_category_name'] = $this->input->post('crop_category_name');
        $data['crop_category_code'] = $this->input->post('crop_category_code');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Crop_category_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Crop Category Updated Successfully.</p>');
            redirect('crop_category');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('crop_category');
        }
    }

   

}


