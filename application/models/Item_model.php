<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Item_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function create($data)
	{
		$result = $this->db->insert('master_item', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public function get_item_list($id = null)
	{
		if ($id != null) {
			$this->db->where('item_id', $id)->order_by('item_name', 'asc');
			$result = $this->db->get('master_item')->row_array();
			return $result;
		} else {
			$this->db->select('*');
			$this->db->from('master_item');
			$this->db->where('is_deleted', 'N');
			$this->db->order_by('item_name', 'asc');
			$result = $this->db->get()->result_array();
			return $result;
		}
	}

	public function delete($id)
	{
		$this->db->where('item_id', $id);
		$result = $this->db->update('master_item', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public function update($data)
	{

		$this->db->where('item_id', $data['item_id']);
		$result = $this->db->update('master_item', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
}
