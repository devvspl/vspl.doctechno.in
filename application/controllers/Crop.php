<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crop extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Crop_model');
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
        $this->session->set_userdata('sub_menu', 'crop');
        $this->data['main'] = 'crop/croplist';
        $this->data['croplist'] = $this->Crop_model->get_crop_list();
        $this->data['cropCategorylist'] = $this->Crop_model->get_crop_category_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('crop_name', 'Crop Name', 'trim|required');
        $this->form_validation->set_rules('crop_code', 'Crop Code', 'trim|required');
        $this->form_validation->set_rules('crop_category_id', 'Crop Category', 'trim|required');
    
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'crop/croplist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['crop_name'] = $this->input->post('crop_name');
            $data['crop_code'] = $this->input->post('crop_code');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            $data['crop_category_id'] = $this->input->post('crop_category_id');
            $result = $this->Crop_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Crop Created Successfully.</p>');
                redirect('crop');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('crop');
            }
        }
    }

    function delete($id)
    {
        $this->Crop_model->delete($id);
        redirect('crop');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'crop');
        $this->data['croplist'] = $this->Crop_model->get_crop_list();
        $this->data['crop'] = $this->Crop_model->get_crop_list($id);
        $this->data['cropCategorylist'] = $this->Crop_model->get_crop_category_list();
        $this->data['crop_id'] = $id;
        $this->data['main'] = 'crop/cropedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['crop_id'] = $id;
        $data['crop_name'] = $this->input->post('crop_name');
        $data['crop_code'] = $this->input->post('crop_code');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['crop_category_id'] = $this->input->post('crop_category_id');
        $result = $this->Crop_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Crop Updated Successfully.</p>');
            redirect('crop');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('crop');
        }
    }

   

}




