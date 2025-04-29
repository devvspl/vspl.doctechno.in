<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->logged_in();
		$this->load->database();
		$this->load->model(array('Auth_model', 'Punch_model', 'Approve_model', 'Scan_model', 'Group_model', 'User_model'));
	}

	private function logged_in()
	{
		if (!$this->session->userdata('authenticated')) {
			redirect('/');
		}
	}

	public function index()
	{
		$this->session->set_userdata('top_menu', 'dashboard');
		$this->session->set_userdata('sub_menu', '');
		$this->data['total_punched_by_me'] = $this->Punch_model->get_total_punched_by_me();
		$this->data['total_approved'] = $this->Punch_model->get_total_approved();
		$this->data['pending_for_punch'] = $this->Punch_model->pending_for_punch();
		$this->data['rejected_punch'] = $this->Punch_model->rejected_punch();
		$this->data['pending_for_approval_punch_by_me'] = $this->Punch_model->pending_for_approval_punch_by_me();

		$this->data['total_approved_by_me'] = $this->Approve_model->get_total_approved_by_me();
 		$this->data['rejected_by_me'] = $this->Approve_model->get_rejected_by_me();
	    $this->data['pending_for_approval_count'] = $this->Approve_model->get_pending_for_approval_count();
 		$this->data['total_rejected'] = $this->Approve_model->get_total_rejected();
 		$this->data['scan_rejected'] = $this->Scan_model->get_scan_rejected_count();
 		$this->data['temp_scan_rejected'] = $this->Scan_model->get_temp_scan_rejected_count();
 		$this->data['scan_pending_name'] = $this->Scan_model->get_temp_scan_file_count();
		$this->data['bill_rejected_count'] = $this->Scan_model->get_bill_rejected_count();
 		$this->data['main'] = 'dashboard';
 		$this->data['grouplist'] = $this->Group_model->get_group_list();
		// VSPL Punch
		$this->data['vspl_total_punched_by_me'] = $this->Punch_model->vspl_get_total_punched_by_me();
		$this->data['vspl_total_approved'] = $this->Punch_model->vspl_get_total_approved();
		$this->data['vspl_pending_for_punch'] = $this->Punch_model->vspl_pending_for_punch();
		$this->data['vspl_rejected_punch'] = $this->Punch_model->vspl_rejected_punch();
		$this->data['vspl_finance_rejected_punch'] = $this->Punch_model->vspl_finance_rejected_punch();
		$this->data['vspl_pending_for_approval_punch_by_me'] = $this->Punch_model->vspl_pending_for_approval_punch_by_me(); 
		
		$this->load->view('layout/template', $this->data);
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
	}

	public function changepass()
	{
		$this->form_validation->set_rules('current_pass', 'Current password', 'trim|required');
		$this->form_validation->set_rules('new_pass', 'New password', 'trim|required|matches[confirm_pass]');
		$this->form_validation->set_rules('confirm_pass', 'Confirm password', 'trim|required');
		if ($this->form_validation->run() == false) {
			$this->data['main'] = 'changepass';
			$this->load->view('layout/template', $this->data);
		} else {
			$data['user_id'] = $this->session->userdata('user_id');
			$data['current_pass'] = md5($this->input->post('current_pass'));
			$data['new_pass'] = md5($this->input->post('new_pass'));
			$data['user_id'] = $this->session->userdata('user_id');
			$result = $this->Auth_model->changepass($data);
			if ($result) {
				$this->session->set_flashdata('message', '<p class="text-success text-center">Password changed successfully.</p>');
				redirect('dashboard/changepass');
			} else {
				$this->session->set_flashdata('message', '<p class="text-danger text-center">Invalid current password. Please try again.</p>');
				redirect('dashboard/changepass');
			}
		}
	}

	function getUserByGroup()
	{
		$group_id = $this->input->post('group');
		$data = $this->User_model->getUserByGroup($group_id);
		echo json_encode($data);
	}

	function get_report_data()
	{
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		$group = $this->input->post('group');
		$user = $this->input->post('user');
		$condition = '';
		
		if ($group != '' || $group != null) {
			$condition .= " AND Group_Id = '$group'";
		}
		if ($user != '' || $user != null) {
			$condition .= " AND (Punch_By = '$user')";
		}
	
		$complete_scan = $this->db->query("SELECT * FROM `scan_file` 
		WHERE Final_Submit ='Y' 
			  AND Is_Deleted='N' 
			  AND ( date(`Scan_Date`) BETWEEN '$from_date' AND '$to_date') $condition");

		$complete_scan_count = $complete_scan->num_rows();

	
		$complete_punch = $this->db->query("SELECT * FROM `scan_file` WHERE File_Punched ='Y' AND Is_Deleted='N' AND  date(`Punch_Date`) >= '$from_date' AND date(`Punch_Date`) <= '$to_date' $condition");
		$complete_punch_count = $complete_punch->num_rows();
	
		$complete_approve = $this->db->query("SELECT * FROM `scan_file` WHERE File_Approved ='Y' AND Is_Deleted='N' AND date(`Approve_Date`) >= '$from_date' AND date(`Approve_Date`) <= '$to_date' $condition");
		$complete_approve_count = $complete_approve->num_rows();
	
		$pending_punch = $this->db->query("SELECT * FROM `scan_file` WHERE Final_Submit ='Y' AND File_Punched ='N' AND Is_Deleted='N' AND date(`Scan_Date`) >= '$from_date' AND date(`Scan_Date`) <= '$to_date' $condition");
		$pending_punch_count = $pending_punch->num_rows();
	
		$pending_approve = $this->db->query("SELECT * FROM `scan_file` WHERE File_Punched ='Y' AND File_Approved ='N' AND Is_Deleted='N' AND date(`Punch_Date`) >= '$from_date' AND date(`Punch_Date`) <= '$to_date' $condition");
		$pending_approve_count = $pending_approve->num_rows();
	
		$complete_data  = '[' . $complete_scan_count . ',' . $complete_punch_count . ',' . $complete_approve_count . ']';
		$pending_data = '[' . $pending_punch_count . ',' . $pending_approve_count . ']';
		echo json_encode(array('complete_data' => $complete_data, 'pending_data' => $pending_data));
	}
	

	function get_overall_report_bill_approver()
	{
		$From_Date = $this->input->post('From_Date');
		$To_Date = $this->input->post('To_Date');
		if ($From_Date != '' && $To_Date != '') {
			$this->db->select('CONCAT(users.first_name, " ", users.last_name) AS bill_approver,
                   SUM(IF(scan_file.Bill_Approved = "Y", 1, 0)) AS approved,
                   SUM(IF(scan_file.Bill_Approved = "R", 1, 0)) AS rejected,
                   SUM(IF(scan_file.Bill_Approved = "N", 1, 0)) AS pending');
			$this->db->from('scan_file');
			$this->db->join('users', 'users.user_id = scan_file.Bill_Approver', 'left');
			$this->db->where('scan_file.Location IS NOT NULL');
			$this->db->group_by('scan_file.Bill_Approver');
		} else {
			$this->db->select('CONCAT(users.first_name, " ", users.last_name) AS bill_approver,
                   SUM(IF(scan_file.Bill_Approved = "Y", 1, 0)) AS approved,
                   SUM(IF(scan_file.Bill_Approved = "R", 1, 0)) AS rejected,
                   SUM(IF(scan_file.Bill_Approved = "N", 1, 0)) AS pending');
			$this->db->from('scan_file');
			$this->db->join('users', 'users.user_id = scan_file.Bill_Approver', 'left');
			$this->db->where('scan_file.Location IS NOT NULL');
			$this->db->group_by('scan_file.Bill_Approver');
		}

		$query = $this->db->get();
		$data = $query->result();
		echo json_encode(['data' => $data]);
	}
	
	function get_overall_report(){
		$From_Date = $this->input->post('From_Date');
		$To_Date = $this->input->post('To_Date');
		
		
		$this->db->select('master_group.group_name');
		if($From_Date != '' && $To_Date != ''){
			$this->db->select('SUM(IF(Final_Submit = "Y" AND scan_file.Is_Deleted = "N" AND (date(`Scan_Date`) >= "'.$From_Date.'" AND date(`Scan_Date`) <= "'.$To_Date.'"), 1, 0)) AS Scan', false);
			$this->db->select('SUM(IF( scan_file.Is_Deleted = "N" AND (date(`Punch_Date`) >= "'.$From_Date.'" AND date(`Punch_Date`) <= "'.$To_Date.'"), 1, 0)) AS Punch', false);
			$this->db->select('SUM(IF( scan_file.Is_Deleted = "N" AND (date(`Approve_Date`) >= "'.$From_Date.'" AND date(`Approve_Date`) <= "'.$To_Date.'"), 1, 0)) AS Approve', false);
			$this->db->select('SUM(IF(File_Punched = "N" AND scan_file.Is_Deleted = "N" AND Final_Submit = "Y" AND (date(`Scan_Date`) >= "'.$From_Date.'" AND date(`Scan_Date`) <= "'.$To_Date.'"), 1, 0)) AS Pending_Punch', false);
			$this->db->select('SUM(IF(File_Punched = "Y" AND scan_file.Is_Deleted = "N" AND File_Approved = "N" AND (date(`Punch_Date`) >= "'.$From_Date.'" AND date(`Punch_Date`) <= "'.$To_Date.'"), 1, 0)) AS Pending_Approve', false);
			$this->db->select('SUM(IF(File_Punched = "Y" AND scan_file.Is_Deleted = "N" AND File_Approved = "N" AND Is_Rejected = "Y" AND (date(`Punch_Date`) >= "'.$From_Date.'" AND date(`Punch_Date`) <= "'.$To_Date.'") , 1, 0)) AS Reject', false);
		}else{
			$this->db->select('SUM(IF(Final_Submit = "Y" AND scan_file.Is_Deleted = "N", 1, 0)) AS Scan', false);
		$this->db->select('SUM(IF(scan_file.Is_Deleted = "N" AND File_Punched="Y", 1, 0)) AS Punch', false);
		$this->db->select('SUM(IF(scan_file.Is_Deleted = "N" AND File_Approved="Y", 1, 0)) AS Approve', false);
		$this->db->select('SUM(IF(File_Punched = "N" AND scan_file.Is_Deleted = "N" AND Final_Submit = "Y", 1, 0)) AS Pending_Punch', false);
		$this->db->select('SUM(IF(File_Punched = "Y" AND scan_file.Is_Deleted = "N" AND File_Approved = "N" AND Is_Rejected = "N", 1, 0)) AS Pending_Approve', false);
		$this->db->select('SUM(IF(Scan_Resend = "Y" AND scan_file.Is_Deleted = "N", 1, 0)) AS Scan_Reject', false);
		$this->db->select('SUM(IF(File_Punched = "Y" AND scan_file.Is_Deleted = "N" AND File_Approved = "N" AND Is_Rejected = "Y" , 1, 0)) AS Punch_Reject', false);
		}
		
		$this->db->from('scan_file');
		$this->db->join('master_group', 'master_group.group_id = scan_file.Group_Id');
		
		$this->db->where('scan_file.Is_Deleted', 'N');
		
		$this->db->group_by('scan_file.Group_Id, master_group.group_name');
	
		$query = $this->db->get();
		$data = $query->result_array();
		
		echo json_encode(['data' => $data]);
	}
	
	function get_report_for_super_approver(){
		$this->db->select('master_group.group_name,master_group.group_id');
		$this->db->from('scan_file');
		$this->db->join('master_group', 'master_group.group_id = scan_file.Group_Id');
		$this->db->select('SUM(IF(File_Punched = "Y"  AND File_Approved = "Y" AND Is_Rejected = "N" , 1, 0)) AS Approve', false);
		$this->db->select('SUM(IF(File_Punched = "Y" AND File_Approved = "N" AND Is_Rejected = "Y" AND scan_file.Edit_Permission ="N"  , 1, 0)) AS Reject', false);
		$this->db->select('SUM(IF(File_Punched = "Y" AND File_Approved = "N" AND Is_Rejected = "N",  1, 0)) AS Pending_Approve', false);
		$this->db->where('scan_file.Is_Deleted', 'N');
		$this->db->group_by('scan_file.Group_Id, master_group.group_name');
	
		$query = $this->db->get();
		$data = $query->result_array();
	
		$formattedData = [];
		foreach ($data as $row) {
			$formattedData[]= [
				'group_name' => '<a href="reject_list_company/' . $row['group_id'] . '" target="_blank">' . $row['group_name'] . '</a>',
				'Approve'=>$row['Approve'],
				'Reject'=>$row['Reject'],
				'Pending_Approve'=>$row['Pending_Approve'],
				
			];
		}

		echo json_encode(['data' => $formattedData]);
	}
	
	
	function get_report_for_super_scanner()
	{
		$this->db->select('master_group.group_name,master_group.group_id');
		$this->db->from('scan_file');
		$this->db->join('master_group', 'master_group.group_id = scan_file.Group_Id');
		$this->db->select('SUM(IF(scan_file.Final_Submit = "Y" , 1, 0)) AS Scan', false);
		$this->db->select('SUM(IF(Scan_Resend = "Y" , 1, 0)) AS Reject', false);
		$this->db->select('SUM(IF(Temp_Scan = "Y" AND Scan_Complete = "N" AND temp_scan_reject = "N",  1, 0)) AS Pending', false);
		$this->db->select('SUM(IF(Temp_Scan = "Y" AND Scan_Complete = "Y" AND temp_scan_reject = "N" AND document_verified="N",  1, 0)) AS Pending_Verification', false);
		$this->db->where('scan_file.Is_Deleted', 'N');
		$this->db->group_by('scan_file.Group_Id, master_group.group_name');
	
		$query = $this->db->get();
		$data = $query->result_array();

		$formattedData = [];

		foreach ($data as $row) {
    $formattedData[] = [
        'group_name' => '<a href="super_scan/' . $row['group_id'] . '">' . $row['group_name'] . '</a>',
        'Scan' => $row['Scan'],
        'Reject' => $row['Reject'],
        'Pending' => $row['Pending'],
        'Pending_Verification'=>$row['Pending_Verification'],
    ];
}

		echo json_encode(['data' => $formattedData]);

	}
}
