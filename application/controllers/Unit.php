<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Unit extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model(array('Unit_model'));
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
        $this->session->set_userdata('sub_menu', 'unit');
        $this->data['unitlist'] = $this->Unit_model->get_unit_list();
        $this->data['main'] = 'unit/unitlist';
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('unit_name', 'Unit Name', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'unit/unitlist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['unit_name'] = $this->input->post('unit_name');
            $data['unit_code'] = $this->input->post('unit_code');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');

            $result = $this->Unit_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Unit Created Successfully.</p>');
                redirect('unit');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('unit');
            }
        }
    }

    function delete($id)
    {
        $this->Unit_model->delete($id);
        redirect('unit');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'unit');
        $this->data['unitlist'] = $this->Unit_model->get_unit_list();
        $this->data['unit'] = $this->Unit_model->get_unit_list($id);
        $this->data['unit_id'] = $id;
        $this->data['main'] = 'unit/unitedit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['unit_id'] = $id;
        $data['unit_name'] = $this->input->post('unit_name');
        $data['unit_code'] = $this->input->post('unit_code');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Unit_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Unit Updated Successfully.</p>');
            redirect('unit');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('unit');
        }
    }


    public function get_unit_list()
    {
        $result = $this->db->query("SELECT unit_id,unit_name FROM `master_unit` WHERE is_deleted='N' AND status='A'")->result_array();
        echo json_encode(array('unit_list' => $result, 'status' => 200));
    }
}
