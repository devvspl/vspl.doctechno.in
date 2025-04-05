<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Employee_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function create($data)
	{
		$result = $this->db->insert('master_employee', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public function get_employee_list($id = null)
	{
		if ($id != null) {
			$this->db->where('id', $id)->order_by('emp_name', 'asc');
			$result = $this->db->get('master_employee')->row_array();
			return $result;
		} else {
			$this->db->select('*');
			$this->db->from('master_employee');
			$this->db->where('is_deleted', 'N');
			$this->db->order_by('emp_name', 'asc');
			$result = $this->db->get()->result_array();
			return $result;
		}
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		$result = $this->db->update('master_employee', array('is_deleted' => 'Y'));
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public function update($data)
	{

		$this->db->where('id', $data['id']);
		$result = $this->db->update('master_employee', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	public function import_employee($data)
	{
		if (!empty($data)) {

			// Insert member data
			$insert = $this->db->insert('master_employee', $data);

			// Return the status
			return $insert ? $this->db->insert_id() : false;
		}
		return false;
	}


	public function get_employee_by_name_and_code($empName, $empCode)
	{
		$this->db->where('emp_name', $empName);
		$this->db->where('emp_code', $empCode);
		$query = $this->db->get('master_employee');

		return $query->row(); // Return the employee record or null if not found
	}
}
