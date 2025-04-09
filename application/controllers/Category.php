<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Category_model');
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
        $this->session->set_userdata('sub_menu', 'category');
        $this->data['main'] = 'category/categorylist';
        $this->data['categorylist'] = $this->Category_model->get_category_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('category_name', 'Category Name', 'trim|required');
        $this->form_validation->set_rules('category_code', 'Category Code', 'trim|required');
    
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'category/categorylist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['category_name'] = $this->input->post('category_name');
            $data['category_code'] = $this->input->post('category_code');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            
            $result = $this->Category_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Category Created Successfully.</p>');
                redirect('category');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('category');
            }
        }
    }

    function delete($id)
    {
        $this->Category_model->delete($id);
        redirect('category');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'category');
        $this->data['categorylist'] = $this->Category_model->get_category_list();
        $this->data['category'] = $this->Category_model->get_category_list($id);
        $this->data['category_id'] = $id;
        $this->data['main'] = 'category/categoryedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['category_id'] = $id;
        $data['category_name'] = $this->input->post('category_name');
        $data['category_code'] = $this->input->post('category_code');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Category_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Category Updated Successfully.</p>');
            redirect('category');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('category');
        }
    }

   

}
