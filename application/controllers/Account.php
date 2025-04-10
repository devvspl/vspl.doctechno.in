<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Account_model');
    }

    private function logged_in()
    {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }

    public function index()
    {
        $this->load->library('pagination');

        $group = $this->input->get('group');
        $search = $this->input->get('search');

        $base_url = base_url('account/index');

        if ($group) {
            $base_url .= '?group=' . urlencode($group);
        }

        if ($search) {
            $base_url .= ($group ? '&' : '?') . 'search=' . urlencode($search);
        }

        $config['base_url'] = $base_url;
        $config['total_rows'] = $this->Account_model->count_filtered_accounts($group, $search);
        $config['per_page'] = 100;

        $this->pagination->initialize($config);

        $page = $this->uri->segment(3) ? $this->uri->segment(3) : 0;

        $this->data['accountlist'] = $this->Account_model->get_account_list($config['per_page'], $page, $group, $search);
        $this->data['getGroupedData'] = $this->Account_model->getGroupedData();
        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['selected_group'] = $group;
        $this->data['search'] = $search;

        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'account');

        $this->data['main'] = 'account/accountlist';
        $this->load->view('layout/template', $this->data);
    }

    public function create()
    {
        $this->form_validation->set_rules('account_name', 'Account Name', 'trim|required');
        $this->form_validation->set_rules('account_code', 'Account Code', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'account/accountlist';
            $this->load->view('layout/template', $this->data);
        } else {
            $data['account_name'] = $this->input->post('account_name');
            $data['account_code'] = $this->input->post('account_code');
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

    function delete($id)
    {
        $this->Account_model->delete($id);
        redirect('account');
    }

    function edit($id)
    {
        $this->session->set_userdata('top_menu', 'master');
        $this->session->set_userdata('sub_menu', 'account');
        // $this->data['accountlist'] = $this->Account_model->get_account_list();
        $this->data['account'] = $this->db->where(['account_id'=>$id])->get('master_account')->result_array();
        $this->data['account_id'] = $id;
        $this->data['main'] = 'account/accountedit';
        $this->data['getGroupedData'] = $this->Account_model->getGroupedData();
        $this->load->view('layout/template', $this->data);
    }

    function update($id)
    {
        $data['account_id'] = $id;
        $data['account_name'] = $this->input->post('account_name');
        $data['account_code'] = $this->input->post('account_code');
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
