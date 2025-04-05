<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Rejection_reason_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function create($data)
	{
		$result = $this->db->insert('master_rj_reason', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public function get_reason_list($id = null)
	{
		if ($id != null) {
			$this->db->where('id', $id)->order_by('reason', 'asc');
			$result = $this->db->get('master_rj_reason')->row_array();
			return $result;
		} else {
			$this->db->select('*');
			$this->db->from('master_rj_reason');
			$this->db->where('master_rj_reason.is_deleted', 'N');
			$this->db->order_by('reason', 'asc');
			$result = $this->db->get()->result_array();
			return $result;
		}
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		$result = $this->db->update('master_rj_reason', array('is_deleted' => 'Y', 'updated_by' => $this->session->userdata('user_id'), 'updated_at' => date('Y-m-d H:i:s')));
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public function update($data)
	{

		$this->db->where('id', $data['id']);
		$result = $this->db->update('master_rj_reason', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
}
