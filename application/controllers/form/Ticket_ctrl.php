<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ticket_ctrl extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	
	}

	public function save_ticket_cancellation()
	{

		$data = [
			'scan_id' => $this->input->post('scan_id'),
			'DocType' => $this->customlib->getDocType($this->input->post('DocTypeId')),
			'DocTypeId' => $this->input->post('DocTypeId'),
			'BillDate' => $this->input->post('BillDate'),
			'AgentName' => $this->input->post('AgentName'),
			'BookingDate' => $this->input->post('BookingDate'),
			'File_Date' => $this->input->post('File_Date'),
			'SubTotal' => $this->input->post('SubTotal'),
			'OthCharge_Amount' => $this->input->post('OthCharge_Amount'),
			'Total_Discount' => $this->input->post('Total_Discount'),
			'Grand_Total' => $this->input->post('Grand_Total'),
			'Remark' => $this->input->post('Remark'),
			'group_id' => $this->session->userdata('group_id'),
			'Created_By' => $this->session->userdata('user_id'),
			'Created_Date' => date('Y-m-d H:i:s'),
		];

		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		$isExistingRecord = $this->customlib->check_punchfile($data['scan_id']);
		if ($isExistingRecord) {
			//Update Existing Record
			$this->db->where('scan_id', $data['scan_id'])->update('punchfile', $data);
			$FileID = $this->db->where('scan_id', $data['scan_id'])->get('punchfile')->row()->FileID;

			$this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $data['Grand_Total'], 'Comment' => $data['Remark']));
			$this->db->where('scan_id', $data['scan_id'])->delete('ticket_cancellation');
			$array = array();
			for ($i = 0; $i < count($this->input->post('Employee')); $i++) {
				$array[$i] = array(
					'scan_id' => $data['scan_id'],
					'Emp_Id' => $this->input->post('Employee')[$i],
					'PNR' => $this->input->post('PNR')[$i],
					'Amount' => $this->input->post('Amount')[$i],
					'Emp_Name' => $this->customlib->getEmployeeNameById($this->input->post('Employee')[$i]),

				);
			}
			$this->db->insert_batch('ticket_cancellation', $array);
			$this->db->where('scan_id', $data['scan_id'])->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
		} else {
			//Insert New Record
			$this->db->insert('punchfile', $data);
			$insert_id = $this->db->insert_id();
			$this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $data['Grand_Total'], 'Comment' => $data['Remark']));
			$array = array();
			for ($i = 0; $i < count($this->input->post('Employee')); $i++) {
				$array[$i] = array(
					'scan_id' => $data['scan_id'],
					'Emp_Id' => $this->input->post('Employee')[$i],
					'PNR' => $this->input->post('PNR')[$i],
					'Amount' => $this->input->post('Amount')[$i],
					'emp_name' => $this->customlib->getEmployeeNameById($this->input->post('Employee')[$i]),

				);
			}
			$this->db->insert_batch('ticket_cancellation', $array);
		}
		$this->customlib->update_file_path($data['scan_id']);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$message = '<div class="alert alert-danger text-left">Something Went Wrong</div>';
		} else {
			$message = '<div class="alert alert-success text-left">Ticket Cancellation Record added successfully</div>';
		}
		$this->session->set_flashdata('message', $message);
		redirect('punch');
	}



	function get_ticket_cancel_employee_list(){
		$scan_id = $this->input->post('scan_id');
		$result = $this->db->select('*')->from('ticket_cancellation')->where('scan_id', $scan_id)->get()->result_array();
		if (!empty($result)) {
			echo json_encode(array('status' => 200, 'data' => $result));
		} else {
			echo json_encode(array('status' => 400, 'msg' => 'No Record Found'));
		}
	}
}
