<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Record extends CI_Controller
{
	protected $year_id;
	function __construct()
	{
		parent::__construct();
		$this->logged_in();
		$this->load->database();
		$this->load->model('Record_model');
		$this->load->model('Punch_model');
		$this->load->model('AdditionalModel');
		    $this->year_id =  $this->session->userdata("year_id") ??
            ($this->db
                ->select("id")
                ->from("financial_years")
                ->where("is_current", 1)
                ->get()
                ->row()->id ??
                null);
	}
	private function logged_in()
	{
		if (!$this->session->userdata('authenticated')) {
			redirect('/');
		}
	}

	public function get_additional_information_by_scan_id($scan_id)
	{
		$main_tbl = "y{$this->year_id}_tbl_additional_information";
		$item_tbl = "y{$this->year_id}_tbl_additional_information_items";

		$this->db->select("ai.*, be.business_entity_name, td.section");
		$this->db->from($main_tbl . " ai");
		$this->db->join("master_business_entity be", "be.business_entity_id = ai.business_entity_id", "left");
		$this->db->join("master_tds_sections td", "td.id = ai.tds_section_id", "left");
		$this->db->where("ai.scan_id", $scan_id);
		$mainRecord = $this->db->get()->row_array();

		if (empty($mainRecord)) {
			return null;
		}

		$this->db->select(
			"aii.*, cc.name as cost_center_name, d.department_name, bu.business_unit_name, r.region_name, s.state_name, " .
			"l.location_name, c.category_name, cr.crop_name, a.activity_name, " .
			"da.account_name as debit_account, ca.account_name as credit_account, pm.payment_term_name as payment_term, " .
			"f.function_name, v.vertical_name, sd.sub_department_name, z.zone_name"
		);
		$this->db->from($item_tbl . " aii");
		$this->db->join("master_cost_center cc", "cc.id = aii.cost_center_id", "left");
		$this->db->join("core_department d", "d.api_id = aii.department_id", "left");
		$this->db->join("core_business_unit bu", "bu.api_id = aii.business_unit_id", "left");
		$this->db->join("core_region r", "r.api_id = aii.region_id", "left");
		$this->db->join("core_state s", "s.api_id = aii.state_id", "left");
		$this->db->join("master_work_location l", "l.location_id = aii.location_id", "left");
		$this->db->join("master_category c", "c.category_id = aii.category_id", "left");
		$this->db->join("core_crop cr", "cr.api_id = aii.crop_id", "left");
		$this->db->join("core_activity a", "a.api_id = aii.activity_id", "left");
		$this->db->join("master_account_ledger da", "da.id = aii.debit_account_id", "left");
		$this->db->join("master_account_ledger ca", "ca.id = aii.credit_account_id", "left");
		$this->db->join("payment_term_master pm", "pm.id = aii.payment_term_id", "left");
		$this->db->join("core_org_function f", "f.api_id = aii.function_id", "left");
		$this->db->join("core_vertical v", "v.api_id = aii.vertical_id", "left");
		$this->db->join("core_sub_department sd", "sd.api_id = aii.sub_department_id", "left");
		$this->db->join("core_zone z", "z.api_id = aii.zone_id", "left");
		$this->db->where("aii.scan_id", $scan_id);
		$items = $this->db->get()->result_array();

		$mainRecord["items"] = $items;
		return $mainRecord;
	}

	public function index($scan_id, $DocTypeId)
	{
		$this->session->set_userdata('top_menu', 'punch_master');
		$this->session->set_userdata('sub_menu', 'punch');
		$this->data['file_detail'] = $this->Punch_model->getPunchDetail($scan_id, $DocTypeId);
		// print_r($this->data['file_detail']);
		// exit;
		$this->data['main'] = 'records/_record';
		$this->load->view('layout/template', $this->data);
	}
	public function vspl_index($scan_id, $DocTypeId)
	{
		$this->session->set_userdata('top_menu', 'punch_master');
		$this->session->set_userdata('sub_menu', 'punch');
		$this->data['file_detail'] = $this->Punch_model->getPunchDetail($scan_id, $DocTypeId);
		$this->data['add_file_detail'] = $this->get_additional_information_by_scan_id($scan_id);
		$this->data['main'] = 'records/_vspl_record';
		$this->load->view('layout/template', $this->data);
	}
	public function admin_rejected_list()
	{
		$this->data['rejected_list'] = $this->Record_model->getRejectedList();
		$this->data['main'] = 'records/admin_rejected_list';
		$this->load->view('layout/template', $this->data);
	}
	function give_edit_permission($scan_id)
	{
		$this->db->where('scan_id', $scan_id);
		$result = $this->db->update("y{$this->year_id}_scan_file", array('has_edit_permission' => 'Y'));
		if ($result) {
			echo json_encode(array('status' => '200'));
		} else {
			echo json_encode(array('status' => '400'));
		}
	}
	function report()
	{
		$this->session->set_userdata('top_menu', 'report');
		$user_list = $this->Record_model->get_user();
		$this->data['user_list'] = $user_list;
		$scanned_by = $this->input->post('scanned_by');
		$Punch_By = $this->input->post('punched_by');
		$Approve_By = $this->input->post('approved_by');
		if ($scanned_by != '' || $Punch_By != '' || $Approve_By != '') {
			$record_list = $this->Record_model->get_filter_record($scanned_by, $Punch_By, $Approve_By);
		} else {
			$record_list = $this->Record_model->get_record_list();
		}
		$this->data['record_list'] = $record_list;
		$this->data['main'] = 'records/report';
		$this->load->view('layout/template', $this->data);
	}
	function all_record()
	{
	}
	function reject_approved_file($scan_id)
	{
		$this->db->where('scan_id', $scan_id);
		$result = $this->db->update("y{$this->year_id}_scan_file", array('is_file_approved' => 'N', 'approved_date' => NULL, 'approved_by' => NULL));
		if ($result) {
			echo json_encode(array('status' => '200'));
		} else {
			echo json_encode(array('status' => '400'));
		}
	}
	function super_admin_reject_list()
	{
		$this->data['rejected_list'] = $this->Record_model->getRejectedList_SU();
		$this->data['main'] = 'records/reject_list';
		$this->load->view('layout/template', $this->data);
	}
	function bill_approval_report()
	{
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
		$this->db->from("y{$this->year_id}_scan_file");
		$this->db->where('Location is not null', NULL, FALSE);
		$this->db->where('is_deleted', 'N');
		if (!empty($location)) {
			$this->db->where('location_id', $location);
		}
		if (!empty($fromDate)) {
			$this->db->where("(temp_scan_date >= '$fromDate' OR scan_date >= '$fromDate')", NULL, FALSE);
		}
		if (!empty($toDate)) {
			$this->db->where("(temp_scan_date <= '$toDate' OR scan_date <= '$toDate')", NULL, FALSE);
		}
		if (!empty($status)) {
			$this->db->where('bill_approval_status', $status);
		}
		if (!empty($last_day) && is_numeric($last_day)) {
			$end_date = date('Y-m-d', strtotime("-$last_day days"));
			$this->db->where("(temp_scan_date <= '$end_date' OR scan_date <= '$end_date')", NULL, FALSE);
			$this->db->where('bill_approval_status', 'N');
		}
		if (!empty($bill_approver)) {
			$this->db->where('bill_approver_id', $bill_approver);
		}

		$this->data['record_list'] = $this->db->get()->result_array();
		$this->data['main'] = 'records/bill_approval_report';
		$this->load->view('layout/template', $this->data);
	}
	function bill_approval_report_new()
	{
		$this->session->set_userdata('top_menu', 'bill_approval_report_new');
		$this->data['location_list'] = $this->customlib->getWorkLocationList();
		$this->data['bill_approver_list'] = $this->customlib->getBillApproverList();
		$this->data['main'] = 'records/bill_approval_report_new';
		$this->load->view('layout/template', $this->data);
	}
	function get_filtered_bill_approval_data()
	{
		if ($this->input->is_ajax_request()) {
			$draw = intval($this->input->get('draw'));
			$start = intval($this->input->get('start'));
			$length = intval($this->input->get('length'));
			$search = $this->input->get('search')['value'];
			$location = $this->input->get('location');
			$bill_approver = $this->input->get('bill_approver');
			$status = $this->input->get('status');
			$fromDate = $this->input->get('from_date');
			$toDate = $this->input->get('to_date');
			$last_day = $this->input->get('last_day');
			$this->db->select(['tcb.first_name as scan_by_name', 'ba.first_name as bill_approver_name', 'master_work_location.location_name', 'location_id', 'document_name', 'file_name', 'file_path', 'is_temp_scan', 'temp_scan_by', 'temp_scan_date', 'scanned_by', 'scan_date', 'bill_approval_status', 'bill_approver_id', 'bill_approved_date', 'bill_approver_remark']);
			$this->db->from("y{$this->year_id}_scan_file");
			$this->db->join('master_work_location', 'y{$this->year_id}_scan_file.location_id = master_work_location.location_id');
			$this->db->join('users tcb', 'y{$this->year_id}_scan_file.Temp_Scan_By = tcb.user_id');
			$this->db->join('users ba', 'y{$this->year_id}_scan_file.bill_approver_id = ba.user_id');
			$this->db->where('Location is not null', NULL, FALSE);
			$this->db->where('y{$this->year_id}_scan_file.is_deleted', 'N');
			if (!empty($location)) {
				$this->db->where('location_id', $location);
			}
			if (!empty($fromDate)) {
				$this->db->where('date(temp_scan_date) >=', $fromDate);
			}
			if (!empty($toDate)) {
				$this->db->where('date(temp_scan_date) <=', $toDate);
			}
			if (!empty($status)) {
				$this->db->where('bill_approval_status', $status);
			}
			if (!empty($last_day) && is_numeric($last_day)) {
				$date = date('Y-m-d', strtotime("-$last_day days"));
				$this->db->where('date(temp_scan_date) >=', $date);
				$this->db->where('bill_approval_status', 'N');
			}
			if (!empty($bill_approver)) {
				$this->db->where('bill_approver_id', $bill_approver);
			}
			if (!empty($search)) {
				$this->db->group_start();
				$this->db->like('location_id', $search);
				$this->db->or_like('document_name', $search);
				$this->db->or_like('scanned_by', $search);
				$this->db->or_like('bill_approver_id', $search);
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
	function ledger_wise_report()
	{
		$this->session->set_userdata('top_menu', 'ledger_wise_report');
		$this->data['main'] = 'records/ledger_wise_report';
		$this->load->view('layout/template', $this->data);
	}
}
