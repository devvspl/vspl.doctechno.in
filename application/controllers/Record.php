<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Record extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->logged_in();
		$this->load->database();
		$this->load->model('Record_model');
	}
	private function logged_in() {
		if (!$this->session->userdata('authenticated')) {
			redirect('/');
		}
	}
	public function index($Scan_Id, $DocTypeId) {
		$this->session->set_userdata('top_menu', 'punch_master');
		$this->session->set_userdata('sub_menu', 'punch');
		$doc_type_list = array(4, 5, 8, 10, 11, 18, 19, 30, 31, 32, 35, 36, 37, 41, 45);
		if (in_array($DocTypeId, $doc_type_list)) {
			$this->data['file_detail'] = $this->Record_model->getRecordFile($Scan_Id);
		} else {
			$this->data['file_detail'] = $this->Record_model->getRecordFile_Accounting($Scan_Id);
		}
		$this->data['main'] = 'records/_record';
		$this->load->view('layout/template', $this->data);
	}
	public function admin_rejected_list() {
		$this->data['rejected_list'] = $this->Record_model->getRejectedList();
		$this->data['main'] = 'records/admin_rejected_list';
		$this->load->view('layout/template', $this->data);
	}
	function give_edit_permission($Scan_Id) {
		$this->db->where('Scan_Id', $Scan_Id);
		$result = $this->db->update('scan_file', array('Edit_Permission' => 'Y'));
		if ($result) {
			echo json_encode(array('status' => '200'));
		} else {
			echo json_encode(array('status' => '400'));
		}
	}
	function report() {
		$this->session->set_userdata('top_menu', 'report');
		$user_list = $this->Record_model->get_user();
		$this->data['user_list'] = $user_list;
		$Scan_By = $this->input->post('Scan_By');
		$Punch_By = $this->input->post('Punch_By');
		$Approve_By = $this->input->post('Approve_By');
		if ($Scan_By != '' || $Punch_By != '' || $Approve_By != '') {
			$record_list = $this->Record_model->get_filter_record($Scan_By, $Punch_By, $Approve_By);
		} else {
			$record_list = $this->Record_model->get_record_list();
		}
		$this->data['record_list'] = $record_list;
		$this->data['main'] = 'records/report';
		$this->load->view('layout/template', $this->data);
	}
	function all_record() {
	}
	function reject_approved_file($Scan_Id) {
		$this->db->where('Scan_Id', $Scan_Id);
		$result = $this->db->update('scan_file', array('File_Approved' => 'N', 'Approve_Date' => NULL, 'Approve_By' => NULL));
		if ($result) {
			echo json_encode(array('status' => '200'));
		} else {
			echo json_encode(array('status' => '400'));
		}
	}
	function super_admin_reject_list() {
		$this->data['rejected_list'] = $this->Record_model->getRejectedList_SU();
		$this->data['main'] = 'records/reject_list';
		$this->load->view('layout/template', $this->data);
	}
	function bill_approval_report() {
		$this->session->set_userdata('top_menu', 'bill_approval_report');
		$this->data['location_list'] = $this->customlib->getWorkLocationList();
		$this->data['bill_approver_list'] = $this->customlib->getBillApproverList();
		$location = $this->input->post('location');
		$bill_approver = $this->input->post('bill_approver');
		$status = $this->input->post('status');
		$fromDate = $this->input->post('from_date');
		$toDate = $this->input->post('to_date');
		$last_day = $this->input->post('last_day');
		$this->db->select('*');
		$this->db->from('scan_file');
		$this->db->where('Location is not null', NULL, FALSE);
		$this->db->where('Is_Deleted', 'N');
		if (!empty($location)) {
			$this->db->where('Location', $location);
		}
		if (!empty($fromDate)) {
			$this->db->where("(Temp_Scan_Date >= '$fromDate' OR Scan_Date >= '$fromDate')", NULL, FALSE);
		}
		if (!empty($toDate)) {
			$this->db->where("(Temp_Scan_Date <= '$toDate' OR Scan_Date <= '$toDate')", NULL, FALSE);
		}
		if (!empty($status)) {
			$this->db->where('Bill_Approved', $status);
		}
		if (!empty($last_day) && is_numeric($last_day)) {
			$end_date = date('Y-m-d', strtotime("-$last_day days"));
			$this->db->where("(Temp_Scan_Date <= '$end_date' OR Scan_Date <= '$end_date')", NULL, FALSE);
			$this->db->where('Bill_Approved', 'N');
		}
		if (!empty($bill_approver)) {
			$this->db->where('Bill_Approver', $bill_approver);
		}

		$this->data['record_list'] = $this->db->get()->result_array();
		$this->data['main'] = 'records/bill_approval_report';
		$this->load->view('layout/template', $this->data);
	}
	function bill_approval_report_new() {
		$this->session->set_userdata('top_menu', 'bill_approval_report_new');
		$this->data['location_list'] = $this->customlib->getWorkLocationList();
		$this->data['bill_approver_list'] = $this->customlib->getBillApproverList();
		$this->data['main'] = 'records/bill_approval_report_new';
		$this->load->view('layout/template', $this->data);
	}
	function get_filtered_bill_approval_data() {
		if ($this->input->is_ajax_request()) {
			$draw = intval($this->input->get('draw'));
			$start = intval($this->input->get('start'));
			$length = intval($this->input->get('length'));
			$search = $this->input->get('search') ['value'];
			$location = $this->input->get('location');
			$bill_approver = $this->input->get('bill_approver');
			$status = $this->input->get('status');
			$fromDate = $this->input->get('from_date');
			$toDate = $this->input->get('to_date');
			$last_day = $this->input->get('last_day');
			$this->db->select(['tcb.first_name as scan_by_name', 'ba.first_name as bill_approver_name', 'master_work_location.location_name', 'Location', 'Document_Name', 'File', 'File_Location', 'Temp_Scan', 'Temp_Scan_By', 'Temp_Scan_Date', 'Scan_By', 'Scan_Date', 'Bill_Approved', 'Bill_Approver', 'Bill_Approver_Date', 'Bill_Approver_Remark']);
			$this->db->from('scan_file');
			$this->db->join('master_work_location', 'scan_file.Location = master_work_location.location_id');
			$this->db->join('users tcb', 'scan_file.Temp_Scan_By = tcb.user_id');
			$this->db->join('users ba', 'scan_file.Bill_Approver = ba.user_id');
			$this->db->where('Location is not null', NULL, FALSE);
			$this->db->where('scan_file.Is_Deleted', 'N');
			if (!empty($location)) {
				$this->db->where('Location', $location);
			}
			if (!empty($fromDate)) {
				$this->db->where('date(Temp_Scan_Date) >=', $fromDate);
			}
			if (!empty($toDate)) {
				$this->db->where('date(Temp_Scan_Date) <=', $toDate);
			}
			if (!empty($status)) {
				$this->db->where('Bill_Approved', $status);
			}
			if (!empty($last_day) && is_numeric($last_day)) {
				$date = date('Y-m-d', strtotime("-$last_day days"));
				$this->db->where('date(Temp_Scan_Date) >=', $date);
				$this->db->where('Bill_Approved', 'N');
			}
			if (!empty($bill_approver)) {
				$this->db->where('Bill_Approver', $bill_approver);
			}
			if (!empty($search)) {
				$this->db->group_start();
				$this->db->like('Location', $search);
				$this->db->or_like('Document_Name', $search);
				$this->db->or_like('Scan_By', $search);
				$this->db->or_like('Bill_Approver', $search);
				$this->db->group_end();
			}
			$totalRecords = $this->db->count_all_results('', false);
			$this->db->limit($length, $start);
			$result = $this->db->get()->result_array();
			$response = ['draw' => $draw, 'recordsTotal' => $totalRecords, 'recordsFiltered' => $totalRecords, 'data' => $result];
			echo json_encode($response);
			return;
		}
	}
	function ledger_wise_report() {
		$this->session->set_userdata('top_menu', 'ledger_wise_report');
		$this->data['main'] = 'records/ledger_wise_report';
		$this->load->view('layout/template', $this->data);
	}
}
