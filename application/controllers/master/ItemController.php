<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ItemController extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->logged_in();
		$this->load->database();
		$this->load->model(array('Item_model'));
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
		$this->session->set_userdata('sub_menu', 'item');
		$this->data['itemlist'] = $this->Item_model->get_item_list();
		$this->data['main'] = 'item/itemlist';
		$this->load->view('layout/template', $this->data);
	}

	public function create()
	{

		$this->form_validation->set_rules('item_name', 'Item Name', 'trim|required|is_unique[master_item.item_name]');
		$this->form_validation->set_rules('item_code', 'Item Code', 'trim|required|is_unique[master_item.item_code]');
		if ($this->form_validation->run() == false) {
			$this->data['main'] = 'item/itemlist';
			$this->load->view('layout/template', $this->data);
		} else {
			$data['item_name'] = $this->input->post('item_name');
			$data['item_code'] = $this->input->post('item_code');
			$data['focus_data'] = 'N';
			$data['status'] = $this->input->post('status');
			$data['created_by'] = $this->session->userdata('user_id');

			$result = $this->Item_model->create($data);
			if ($result) {
				$this->session->set_flashdata('message', '<p class="text-success text-center">Item Created Successfully.</p>');
				redirect('item');
			} else {
				$this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
				redirect('item');
			}
		}
	}

	function delete($id)
	{
		$this->Item_model->delete($id);
		redirect('item');
	}

	function edit($id)
	{
		$this->session->set_userdata('top_menu', 'master');
		$this->session->set_userdata('sub_menu', 'item');
		$this->data['itemlist'] = $this->Item_model->get_item_list();
		$this->data['item'] = $this->Item_model->get_item_list($id);
		$this->data['item_id'] = $id;
		$this->data['main'] = 'item/itemedit';
		$this->load->view('layout/template', $this->data);
	}

	function update($id)
	{
		$data['item_id'] = $id;
		$data['item_name'] = $this->input->post('item_name');
		$data['item_code'] = $this->input->post('item_code');
		$data['status'] = $this->input->post('status');
		$data['updated_by'] = $this->session->userdata('user_id');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$result = $this->Item_model->update($data);
		if ($result) {
			$this->session->set_flashdata('message', '<p class="text-success text-center">Item Updated Successfully.</p>');
			redirect('item');
		} else {
			$this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
			redirect('item');
		}
	}

	public function get_item_list()
	{
		$result = $this->db->query("SELECT item_name FROM `master_item` WHERE is_deleted='N' AND status='A'")->result_array();
		echo json_encode(array('item_list' => $result, 'status' => 200));
	}

}
