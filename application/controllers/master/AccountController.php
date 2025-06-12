<?php
defined('BASEPATH') or exit('No direct script access allowed');
class AccountController extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Account_model');
    }
    private function logged_in() {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    public function index() {
        $this->load->library('pagination');
        $search = $this->input->get('search') ??'';
        $group = $this->input->get('group') ??'';
        $total_rows = $this->Account_model->get_account_count($search, $group);
        $config = array();
        $config['base_url'] = base_url('master/AccountController/index');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 50;
        $config['uri_segment'] = 4;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['next_link'] = '»';
        $config['prev_link'] = '«';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $this->data['accountlist'] = $this->Account_model->get_account_list($config['per_page'], $page, $search, $group);
        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['getGroupedData'] = $this->Account_model->getGroupedData();
        $this->data['search'] = $search;
        $this->data['selected_group'] = $group;
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'account');
        $this->data['main'] = 'account/accountlist';
        $this->load->view('layout/template', $this->data);
    }
    public function create() {
        $this->form_validation->set_rules('account_name', 'account name', 'trim|required');
        $this->form_validation->set_rules('focus_code', 'account code', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'account/accountlist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['account_name'] = $this->input->post('account_name');
            $data['focus_code'] = $this->input->post('focus_code');
            $data['account_group'] = $this->input->post('account_group') ? $this->input->post('account_group') : $data['account_name'];
            $data['status'] = $this->input->post('status');
            $data['created_by'] = $this->session->userdata('user_id');
            $result = $this->Account_model->create($data);
            if ($result) {
                $this->session->set_flashdata('message', '<p class="text-success text-center">Account Created Successfully.</p>');
                redirect('account');
            } else {
                $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
                redirect('account');
            }
        }
    }
    function delete($id) {
        $this->Account_model->delete($id);
        redirect('account');
    }
    function edit($id) {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'account');
        $this->data['account'] = $this->db->where(['account_id' => $id])->get('master_account')->result_array();
        $this->data['account_id'] = $id;
        $this->data['main'] = 'account/accountedit';
        $this->data['getGroupedData'] = $this->Account_model->getGroupedData();
        $this->load->view('layout/template', $this->data);
    }
    function update($id) {
        $data['account_id'] = $id;
        $data['account_name'] = $this->input->post('account_name');
        $data['focus_code'] = $this->input->post('focus_code');
        $data['status'] = $this->input->post('status');
        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $result = $this->Account_model->update($data);
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">Account Updated Successfully.</p>');
            redirect('account');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect('account');
        }
    }
}
