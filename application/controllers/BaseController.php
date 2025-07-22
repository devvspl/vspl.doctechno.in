<?php
defined('BASEPATH') or exit('No direct script access allowed');
class BaseController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        if ($this->session->userdata('user_id') != null) {
            redirect('dashboard');
        }
    }
    public function login()
    {
        if ($this->input->server('REQUEST_METHOD') === 'GET') {
            $data['financial_years'] = $this->BaseModel->getData('financial_years')->result_array();
            $this->load->view('login', $data);
        } else {
            $this->form_validation->set_rules('identity', 'Identity', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');
            $this->form_validation->set_rules('financial_year', 'financial year', 'required|trim');
            $log_data = ['user_id' => 0, 'ip_address' => $this->input->ip_address(), 'is_success' => 0, 'user_agent' => $this->input->user_agent()];
            if ($this->form_validation->run()) {
                $identity = $this->input->post('identity');
                $password = md5($this->input->post('password'));
                $data['financial_year'] = $this->input->post('financial_year');
                $result = $this->BaseModel->getData('users', ['username' => $identity, 'password' => $password, 'status' => 'A'])->result_array();
                if ($result) {
                    $log_data['user_id'] = $result[0]['user_id'];
                    $log_data['is_success'] = 1;
                    $this->BaseModel->insertData('tbl_login_logs', $log_data);
                    $session = ['user_id' => $result[0]['user_id'], 'name' => $result[0]['first_name'] . ' ' . $result[0]['last_name'], 'role' => $result[0]['role'], 'role_id' => $result[0]['role_id'], 'username' => $result[0]['username'], 'group_id' => $result[0]['group_id'], 'authenticated' => true, 'year_id' => $this->input->post('financial_year')];
                    $this->session->set_userdata($session);
                    redirect('dashboard');
                } else {
                    $this->BaseModel->insertData('tbl_login_logs', $log_data);
                    $this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid Credentials. Please try again.</p>');
                    $data['financial_years'] = $this->BaseModel->getData('financial_years')->result_array();
                    $this->load->view('login', $data);
                }
            } else {
                $this->BaseModel->insertData('tbl_login_logs', $log_data);
                $data['financial_years'] = $this->BaseModel->getData('financial_years')->result_array();
                $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
                $this->load->view('login', $data);
            }
        }
    }
}
