<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_ctrl extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('Auth_model');
        
		if ($this->session->userdata('user_id') != null) {

			redirect('dashboard');
		}
	}
	
	function login()
	{
      
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
            $this->load->view('login');
        } else {
            $this->form_validation->set_rules('identity', 'Identity', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');
            if ($this->form_validation->run()) {

                $data['identity'] = $this->input->post('identity');
                $data['password'] = md5($this->input->post('password'));

                $result = $this->Auth_model->login($data);
                if ($result) {
                    $this->session->set_userdata(array(
                        'user_id' => $result[0]['user_id'],
                        'name' => $result[0]['first_name'] . ' ' . $result[0]['last_name'],
                        'role' => $result[0]['role'],
                        'username' => $result[0]['username'],
                        'group_id' => $result[0]['group_id'],
                        'authenticated' => true
                    ));
                    redirect('dashboard');
                } else {
                    $this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid Credentials. Please try again.</p>');
                    $this->load->view('login');
                }
            } else {
                $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
                $this->load->view('login');
            }
        }
	}
}
