<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cost_center extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Cost_center_model');
        $this->load->model('Region_model');
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
        $this->session->set_userdata('sub_menu', 'cost_center');
        $this->data['main'] = 'cost_center/cost_centerlist';
        $this->data['cost_centerlist'] = $this->Cost_center_model->get_cost_center_list();
        $this->data['regionlist'] = $this->Region_model->get_region_list();
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {

        $this->form_validation->set_rules('cost_center_name', 'Cost_center Name', 'trim|required');
        $this->form_validation->set_rules('cost_center_code', 'Cost_center Code', 'trim|required');
        $this->form_validation->set_rules('region_id', 'Region', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'cost_center/cost_centerlist';
            $this->data['regionlist'] = $this->Region_model->get_region_list();
            $this->load->view('layout/template', $this->data);
        } else {
            $data['cost_center_name'] = $this->input->post('cost_center_name');
            $data['cost_center_code'] = $this->input->post('cost_center_code');
            $data['region_id'] = $this->input->post('region_id');
            $data['cost_center_numeric_code'] = $this->input->post('cost_center_numeric_code');
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');

            $result = $this->Cost_center_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Cost_center Created Successfully.</p>');
                redirect('cost_center');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('cost_center');
            }
        }
    }

    function delete($id)
    {
        $this->Cost_center_model->delete($id);
        redirect('cost_center');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'cost_center');
        $this->data['cost_centerlist'] = $this->Cost_center_model->get_cost_center_list();
        $this->data['regionlist'] = $this->Region_model->get_region_list();
        $this->data['cost_center'] = $this->Cost_center_model->get_cost_center_list($id);
        $this->data['cost_center_id'] = $id;
        $this->data['main'] = 'cost_center/cost_centeredit';
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['cost_center_id'] = $id;
        $data['region_id'] = $this->input->post('region_id');
        $data['cost_center_name'] = $this->input->post('cost_center_name');
        $data['cost_center_code'] = $this->input->post('cost_center_code');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Cost_center_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Cost_center Updated Successfully.</p>');
            redirect('cost_center');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('cost_center');
        }
    }

    function get_cost_center_by_region_id()
    {
        $region_id = $this->input->post('region_id');
        $cost_center_list = $this->Cost_center_model->get_cost_center_by_region_id($region_id);
        echo json_encode($cost_center_list);
    }
}
