<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EmployeeController extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->logged_in();
		$this->load->database();
		$this->load->model(array('Employee_model'));
		$this->load->library('form_validation');
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
		$this->session->set_userdata('sub_menu', 'employee');
		$this->data['employeelist'] = $this->Employee_model->get_employee_list();
		$this->data['main'] = 'employee/employeelist';
		$this->load->view('layout/template', $this->data);
	}

	public function create()
	{

		$this->form_validation->set_rules('emp_name', 'Unit Name', 'trim|required');
		if ($this->form_validation->run() == false) {
			$this->data['main'] = 'employee/employeelist';
			$this->load->view('layout/template', $this->data);
		} else {
			$data['emp_name'] = $this->input->post('emp_name');
			$data['emp_code'] = $this->input->post('emp_code');
			$data['emp_vspl'] = 'N';
			$data['status'] = $this->input->post('status');
			$data['created_by'] = $this->session->userdata('user_id');

			$result = $this->Employee_model->create($data);
			if ($result) {
				$this->session->set_flashdata('message', '<p class="text-success text-center">Employee Created Successfully.</p>');
				redirect('employee');
			} else {
				$this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
				redirect('employee');
			}
		}
	}

	function delete($id)
	{
		$this->Employee_model->delete($id);
		$this->session->set_flashdata('message', '<p class="text-success text-center">Employee Deleted Successfully.</p>');
		redirect('employee');
	}

	function edit($id)
	{
		$this->session->set_userdata('top_menu', 'master');
		$this->session->set_userdata('sub_menu', 'employee');
		$this->data['employeelist'] = $this->Employee_model->get_employee_list();
		$this->data['employee'] = $this->Employee_model->get_employee_list($id);
		$this->data['id'] = $id;
		$this->data['main'] = 'employee/employeeedit';
		$this->load->view('layout/template', $this->data);
	}

	function update($id)
	{
		$data['id'] = $id;
		$data['emp_name'] = $this->input->post('emp_name');
		$data['emp_code'] = $this->input->post('emp_code');
		$data['status'] = $this->input->post('status');

		$result = $this->Employee_model->update($data);
		if ($result) {
			$this->session->set_flashdata('message', '<p class="text-success text-center">Employee Updated Successfully.</p>');
			redirect('employee');
		} else {
			$this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
			redirect('employee');
		}
	}


	//sync employee from api
	public function sync_employee()
	{
		$this->db->trans_begin();
		$json = file_get_contents('https://vnrseeds.co.in/RcdDetails.php?action=Details&val=Employee');
		$data = json_decode($json, true);

		foreach ($data['employee_list'] as $key => $value) {

			$query = $this->db->get_where('master_employee', array('company_id' => $value['CompanyId'], 'emp_code' => $value['EmpCode'], 'emp_vspl' => 'Y'));
			if ($value['CompanyId'] != 4) {
				if ($query->num_rows() > 0) {
					$this->db->where('company_id', $value['CompanyId']);
					$this->db->where('emp_code', $value['EmpCode']);
					$this->db->where('emp_vspl', 'Y');
					$this->db->update('master_employee', array('emp_name' => $value['Fname'] . ' ' . $value['Sname'] . ' ' . $value['Lname'], 'status' => $value['EmpStatus']));
				} else {
					$company_code = null;
					if ($value['CompanyId'] == 1) {
						$company_code = 'VSPL';
					} else if ($value['CompanyId'] == 2) {
						$company_code = 'VFarm';
					} else if ($value['CompanyId'] == 3) {
						$company_code = 'VNPL';
					}
					$user_id = $this->session->userdata('user_id');
					$this->db->insert('master_employee', array('emp_vspl' => 'Y', 'emp_code' => $value['EmpCode'], 'emp_name' => $value['Fname'] . ' ' . $value['Sname'] . ' ' . $value['Lname'], 'company_code' => $company_code, 'company_id' => $value['CompanyId'], 'Status' => $value['EmpStatus'], 'created_by' => $user_id));
				}
			}
		}
		if ($this->db->trans_status() == FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array('status' => 400));
		} else {
			$this->db->trans_commit();
			echo json_encode(array('status' => 200));
		}
	}

	public function employee_import()
	{
		if ($this->input->post('importSubmit')) {
			$insertCount = $rowCount = $notAddCount = 0;
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				$this->load->library('CSVReader');
				$csvData = $this->csvreader->parse_file($_FILES['file']['tmp_name']);
				if (!empty($csvData)) {
					foreach ($csvData as $row) {
						$rowCount++;
						$empData = array(
							'emp_code' => $row['emp_code'],
							'emp_name' => $row['emp_name'],
							'company_code' => $row['company_code'],
							'status' => 'A',
						);
						$insert = $this->Employee_model->get_employee_by_name_and_code($empData['emp_name'], $empData['emp_code']);
						if (!$insert) {
							$this->Employee_model->import_employee($empData);
							$insertCount++;
						}
					}
					$notAddCount = $rowCount - $insertCount;
					$successMsg = '<p class="text-center">Employee imported successfully. Total Rows (' . $rowCount . ') | Inserted (' . $insertCount . ')  | <span class="text-danger">Not Inserted (' . $notAddCount . ')</span></p>';
					$this->session->set_flashdata('message', $successMsg);
					redirect('employee');
				}
			} else {
				$this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
				redirect('employee');
			}
		}
	}
}
