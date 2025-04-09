<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hotel extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->logged_in();
		$this->load->database();
		$this->load->model(array('Hotel_model', 'State_model'));
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
		$this->session->set_userdata('sub_menu', 'hotel');
		$this->data['hotellist'] = $this->Hotel_model->get_hotel_list();
		$this->data['state_list'] = $this->State_model->get_state_list();
		$this->data['main'] = 'hotel/hotellist';
		$this->load->view('layout/template', $this->data);
	}

	public function create()
	{

		$this->form_validation->set_rules('hotel_name', 'Hotel Name', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('city_name', 'City Name', 'trim|required');
		$this->form_validation->set_rules('state_id', 'State', 'trim|required');
		if ($this->form_validation->run() == false) {
			$this->data['main'] = 'hotel/hotellist';
	//state list
			$this->data['state_list'] = $this->State_model->get_state_list();
			$this->load->view('layout/template', $this->data);
		} else {
			$data['hotel_name'] = $this->input->post('hotel_name');
			$data['address'] = $this->input->post('address');
			$data['city_name'] = $this->input->post('city_name');
			$data['state_id'] = $this->input->post('state_id');
			$data['status'] = $this->input->post('status');
			$data['created_by'] = $this->session->userdata('user_id');

			$result = $this->Hotel_model->create($data);
			if ($result) {
				$this->session->set_flashdata('message', '<p class="text-success text-center">Hotel Created Successfully.</p>');
				redirect('hotel');
			} else {
				$this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
				redirect('hotel');
			}
		}
	}

	function delete($id)
	{
		$this->Hotel_model->delete($id);
		redirect('hotel');
	}

	function edit($id)
	{
		$this->session->set_userdata('top_menu', 'master');
		$this->session->set_userdata('sub_menu', 'hotel');
		$this->data['hotellist'] = $this->Hotel_model->get_hotel_list();
		$this->data['state_list'] = $this->State_model->get_state_list();
		$this->data['hotel'] = $this->Hotel_model->get_hotel_list($id);
		$this->data['hotel_id'] = $id;
		$this->data['main'] = 'hotel/hoteledit';
		$this->load->view('layout/template', $this->data);
	}

	function update($id)
	{
		$data['hotel_id'] = $id;
		$data['hotel_name'] = $this->input->post('hotel_name');
		$data['address'] = $this->input->post('address');
		$data['city_name'] = $this->input->post('city_name');
		$data['state_id'] = $this->input->post('state_id');
		$data['status'] = $this->input->post('status');
		$data['updated_by'] = $this->session->userdata('user_id');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$result = $this->Hotel_model->update($data);
		if ($result) {
			$this->session->set_flashdata('message', '<p class="text-success text-center">Hotel Updated Successfully.</p>');
			redirect('hotel');
		} else {
			$this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
			redirect('hotel');
		}
	}


}
