<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Auth extends REST_Controller
{

	function __construct()
	{

		parent::__construct();
		$this->load->database();
		$this->load->model('Auth_model');
		$this->jwt = new JWT();
	}

	function index_get()
	{
		$this->db->select('*');
		$result = $this->db->get_where('users', array('status' => 'A'))->result_array();
		$this->response($result, 200);
	}


	function index_post()
	{
		$header = ($this->input->request_headers());
		if (isset($header['token']) &&  $header['token'] != null) {
			$udata = $this->my_lib->is_valid($header['token']);
			if (!is_null($udata)) {
				$this->db->select('*');
				$result = $this->db->get_where('users', array('status' => 'A'))->result_array();
				$this->response($result, 200);
			} else {
				$this->response(array('msg' => 'Invalid token.', 'status' => '401'), 401);
			}
		} else { //token not set
			$this->response(array('msg' => 'Token not set.', 'status' => '400'), 400);
		}
	}

	function login_post()
	{

		$data['identity'] = trim($this->post('identity'));
		$data['password'] = md5(trim($this->post('password')));

		$result = $this->Auth_model->login($data);
		$permission = $this->Auth_model->get_user_permission($result[0]['user_id']);
		
		if (!is_null($result)) {
			$response = array(
				'user_id' => $result[0]['user_id'],
				'role' => $result[0]['role']
			);
			$token = $this->jwt->encode($response, $this->config->item('jwtsecrateKey'), 'HS256');
			$this->response(
				array(
					'data' => array(
						'user_id' => $result[0]['user_id'],
						'name' => $result[0]['first_name'] . ' ' . $result[0]['last_name'],
						'role'  => $result[0]['role'],
						'token' => $token,
						'permission' => $permission
					),

					'msg' => 'login successfully.',
					'status' => 200
				),
				200
			);
		} else {
			$this->response(array('msg' => 'Login failed.', 'status' => '401'), 401);
		}
	}
}
