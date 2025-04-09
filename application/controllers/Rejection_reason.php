<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rejection_reason extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->logged_in();
		$this->load->database();
		$this->load->model(array('Rejection_reason_model'));
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
		$this->session->set_userdata('sub_menu', 'rj_reason');
		$this->data['reason_list'] = $this->Rejection_reason_model->get_reason_list();
		$this->data['main'] = 'rejection/reasonlist';
		$this->load->view('layout/template', $this->data);
	}

	public function create()
	{

		$this->form_validation->set_rules('reason', 'Reason', 'trim|required');

		if ($this->form_validation->run() == false) {
			$this->data['main'] = 'rejection/reasonlist';
			$this->load->view('layout/template', $this->data);
		} else {
			$data['reason'] = $this->input->post('reason');
			$data['status'] = $this->input->post('status');
			$data['created_by'] = $this->session->userdata('user_id');

			$result = $this->Rejection_reason_model->create($data);
			if ($result) {
				$this->session->set_flashdata('message', '<p class="text-success text-center">Rejection Reason Created Successfully.</p>');
				redirect('rejection_reason');
			} else {
				$this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
				redirect('rejection_reason');
			}
		}
	}

	function delete($id)
	{
		$this->Rejection_reason_model->delete($id);
		redirect('rejection_reason');
	}

	function edit($id)
	{
		$this->session->set_userdata('top_menu', 'master');
		$this->session->set_userdata('sub_menu', 'rj_reason');
		$this->data['reason_list'] = $this->Rejection_reason_model->get_reason_list();
		$this->data['data'] = $this->Rejection_reason_model->get_reason_list($id);
		$this->data['id'] = $id;
		$this->data['main'] = 'rejection/reasonedit';
		$this->load->view('layout/template', $this->data);
	}

	function update($id)
	{
		$data['id'] = $id;
		$data['reason'] = $this->input->post('reason');

		$data['status'] = $this->input->post('status');
		$data['updated_by'] = $this->session->userdata('user_id');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$result = $this->Rejection_reason_model->update($data);
		if ($result) {
			$this->session->set_flashdata('message', '<p class="text-success text-center">Rejection Reason Updated Successfully.</p>');
			redirect('rejection_reason');
		} else {
			$this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
			redirect('rejection_reason');
		}
	}


	public function save_reason()
	{
		$reason = $this->input->post('reason');

		$data = array(
			'reason' => $reason,
			'status' => 'A',
			'is_deleted' => 'N',
			'created_by' => $this->session->userdata('user_id'),
		);
		$this->db->insert('master_rj_reason', $data);
		//if reason saved successfully then send response
		if ($this->db->affected_rows() > 0) {
			//send response
			echo json_encode(array('status' => 200, 'msg' => 'Rejection Reason added successfully'));
		} else {
			echo json_encode(array('status' => 400, 'msg' => 'Something went wrong'));
		}
	}
}
