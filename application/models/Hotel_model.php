<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hotel_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function create($data)
	{
		$result = $this->db->insert('master_hotel', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public function get_hotel_list($id = null)
	{
		if ($id != null) {
			$this->db->where('hotel_id', $id)->order_by('hotel_name', 'asc');
			$result = $this->db->get('master_hotel')->row_array();
			return $result;
		} else {
			$this->db->select('master_hotel.*, master_state.state_name');
			$this->db->from('master_hotel');
			$this->db->join('master_state', 'master_state.state_id = master_hotel.state_id');
			$this->db->where('master_hotel.is_deleted', 'N');
			$this->db->order_by('hotel_name', 'asc');
			$result = $this->db->get()->result_array();
			return $result;
		}
	}

	public function delete($id)
	{
		$this->db->where('hotel_id', $id);
		$result = $this->db->update('master_hotel', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public function update($data)
	{

		$this->db->where('hotel_id', $data['hotel_id']);
		$result = $this->db->update('master_hotel', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
}
