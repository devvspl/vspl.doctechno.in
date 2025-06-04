<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends CI_Controller
{
    protected $year_id;
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model(array('Auth_model', 'Punch_model', 'Approve_model', 'Scan_model', 'Group_model', 'User_model'));
        $this->year_id = $this->session->userdata('year_id') ?? ($this->db->select('id')->from('financial_years')->where('is_current', 1)->get()->row()->id ?? null);

    }
    private function logged_in()
    {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    // public function index() {
    //     $this->session->set_userdata('top_menu', 'dashboard');
    //     $this->session->set_userdata('sub_menu', '');
    //     $this->data['total_punched_by_me'] = $this->Punch_model->get_total_punched_by_me();
    //     $this->data['total_approved'] = $this->Punch_model->get_total_approved();
    //     $this->data['pending_for_punch'] = $this->Punch_model->pending_for_punch();
    //     $this->data['rejected_punch'] = $this->Punch_model->rejected_punch();
    //     $this->data['pending_for_approval_punch_by_me'] = $this->Punch_model->pending_for_approval_punch_by_me();
    //     $this->data['total_approved_by_me'] = $this->Approve_model->get_total_approved_by_me();
    //     $this->data['rejected_by_me'] = $this->Approve_model->get_rejected_by_me();
    //     $this->data['pending_for_approval_count'] = $this->Approve_model->get_pending_for_approval_count();
    //     $this->data['total_rejected'] = $this->Approve_model->get_total_rejected();
    //     $this->data['scan_rejected'] = $this->Scan_model->get_scan_rejected_count();
    //     $this->data['temp_scan_rejected'] = $this->Scan_model->get_temp_scan_rejected_count();
    //     $this->data['scan_pending_name'] = [];
    //     $this->data['bill_rejected_count'] = $this->Scan_model->get_bill_rejected_count();
    //     $this->data['main'] = 'dashboard';
    //     $this->data['grouplist'] = $this->Group_model->get_group_list();
    //     $this->data['vspl_total_punched_by_me'] = $this->Punch_model->vspl_get_total_punched_by_me();
    //     $this->data['vspl_total_approved'] = $this->Punch_model->vspl_get_total_approved();
    //     $this->data['vspl_pending_for_punch'] = $this->Punch_model->vspl_pending_for_punch();
    //     $this->data['vspl_rejected_punch'] = $this->Punch_model->vspl_rejected_punch();
    //     $this->data['vspl_finance_rejected_punch'] = $this->Punch_model->vspl_finance_rejected_punch();
    //     $this->data['vspl_pending_for_approval_punch_by_me'] = $this->Punch_model->vspl_pending_for_approval_punch_by_me();
    // 	$this->data['year_id'] = $this->year_id;
    //     $this->load->view('layout/template', $this->data);
    // }
    public function index()
    {
        $this->session->set_userdata('top_menu', 'dashboard');
        $this->session->set_userdata('sub_menu', '');
        $role = $_SESSION['role'] ?? 'user';
        $user_id = $_SESSION['user_id'];
        $group_id = $_SESSION['group_id'];
        $year_id = $this->year_id;
        $this->data['year_id'] = $year_id;
        switch ($role) {
            case 'super_admin':
                $this->data['main'] = 'dashboard';
                break;
            case 'admin':
                $this->data['main'] = 'dashboard';
                break;
            case 'user':
                if ($this->customlib->has_permission('Temporary Scan')) {
                    $counts = $this->Scan_model->get_user_dashboard_counts($user_id, $group_id, $year_id);
                    $this->data = array_merge($this->data, $counts);
                    $this->data['main'] = 'dashboard/user';
                } else {
                    $counts = $this->Scan_model->get_punch_counts($year_id, $user_id);
                    $this->data = array_merge($this->data, $counts);
                    $this->data['main'] = 'dashboard/punch_user';
                }
                break;
            case 'super_approver':
                $this->data['main'] = 'dashboard/super_approver';
                break;
            case 'bill_approver':
                $this->data['main'] = 'dashboard/bill_approver';
                $this->data['dept_summary'] = $this->Scan_model->getBillApprovalSummaryByDepartment($user_id);
                break;
            case 'vspl_bill_approver':
                $this->data['main'] = 'dashboard/vspl_bill_approver';
                break;
            case 'scan_admin':
                $this->data['main'] = 'dashboard/scan_admin';
                $counts = $this->Scan_model->get_scan_admin_dashboard_counts($user_id, $group_id, $year_id);
                $this->data = array_merge($this->data, $counts);

                break;
            default:
                $this->data['main'] = 'dashboard/user';
        }
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
            $condition .= " AND group_id = '$group'";
        }
        if ($user != '' || $user != null) {
            $condition .= " AND (Punch_By = '$user')";
        }
        $complete_scan = $this->db->query("SELECT * FROM `y{$this->year_id}_scan_file` 
		WHERE is_final_submitted ='Y' 
			  AND is_deleted='N' 
			  AND ( date(`scan_date`) BETWEEN '$from_date' AND '$to_date') $condition");
        $complete_scan_count = $complete_scan->num_rows();
        $complete_punch = $this->db->query("SELECT * FROM `y{$this->year_id}_scan_file` WHERE is_file_punched ='Y' AND is_deleted='N' AND  date(`punched_date`) >= '$from_date' AND date(`punched_date`) <= '$to_date' $condition");
        $complete_punch_count = $complete_punch->num_rows();
        $complete_approve = $this->db->query("SELECT * FROM `y{$this->year_id}_scan_file` WHERE is_file_approved ='Y' AND is_deleted='N' AND date(`approved_date`) >= '$from_date' AND date(`approved_date`) <= '$to_date' $condition");
        $complete_approve_count = $complete_approve->num_rows();
        $pending_punch = $this->db->query("SELECT * FROM `y{$this->year_id}_scan_file` WHERE is_final_submitted ='Y' AND is_file_punched ='N' AND is_deleted='N' AND date(`scan_date`) >= '$from_date' AND date(`scan_date`) <= '$to_date' $condition");
        $pending_punch_count = $pending_punch->num_rows();
        $pending_approve = $this->db->query("SELECT * FROM `y{$this->year_id}_scan_file` WHERE is_file_punched ='Y' AND is_file_approved ='N' AND is_deleted='N' AND date(`punched_date`) >= '$from_date' AND date(`punched_date`) <= '$to_date' $condition");
        $pending_approve_count = $pending_approve->num_rows();
        $complete_data = '[' . $complete_scan_count . ',' . $complete_punch_count . ',' . $complete_approve_count . ']';
        $pending_data = '[' . $pending_punch_count . ',' . $pending_approve_count . ']';
        echo json_encode(array('complete_data' => $complete_data, 'pending_data' => $pending_data));
    }
    function get_overall_report_bill_approver()
    {
        $From_Date = $this->input->post('From_Date');
        $To_Date = $this->input->post('To_Date');
        $table = 'y' . $this->year_id . '_scan_file'; // Correctly build table name

        $this->db->select('CONCAT(users.first_name, " ", users.last_name) AS bill_approver', false);
        $this->db->select('SUM(IF(' . $table . '.bill_approval_status = "Y", 1, 0)) AS approved', false);
        $this->db->select('SUM(IF(' . $table . '.bill_approval_status = "R", 1, 0)) AS rejected', false);
        $this->db->select('SUM(IF(' . $table . '.bill_approval_status = "N", 1, 0)) AS pending', false);
        $this->db->from($table);
        $this->db->join('users', 'users.user_id = ' . $table . '.bill_approver_id', 'left');
        $this->db->where($table . '.location_id IS NOT NULL');

        // Optional: filter by date range if provided
        if (!empty($From_Date) && !empty($To_Date)) {
            $this->db->where('DATE(' . $table . '.scan_date) >=', $From_Date);
            $this->db->where('DATE(' . $table . '.scan_date) <=', $To_Date);
        }

        $this->db->group_by($table . '.bill_approver_id');
        $query = $this->db->get();
        $data = $query->result();
        echo json_encode(['data' => $data]);
    }

    function get_overall_report()
    {
        $From_Date = $this->input->post('From_Date');
        $To_Date = $this->input->post('To_Date');
        $table = 'y' . $this->year_id . '_scan_file'; // Construct table name

        $this->db->select('master_group.group_name');

        if ($From_Date != '' && $To_Date != '') {
            $this->db->select('SUM(IF(is_final_submitted = "Y" AND ' . $table . '.is_deleted = "N" AND (DATE(scan_date) >= "' . $From_Date . '" AND DATE(scan_date) <= "' . $To_Date . '"), 1, 0)) AS Scan', false);
            $this->db->select('SUM(IF(' . $table . '.is_deleted = "N" AND (DATE(punched_date) >= "' . $From_Date . '" AND DATE(punched_date) <= "' . $To_Date . '"), 1, 0)) AS Punch', false);
            $this->db->select('SUM(IF(' . $table . '.is_deleted = "N" AND (DATE(approved_date) >= "' . $From_Date . '" AND DATE(approved_date) <= "' . $To_Date . '"), 1, 0)) AS Approve', false);
            $this->db->select('SUM(IF(is_file_punched = "N" AND ' . $table . '.is_deleted = "N" AND is_final_submitted = "Y" AND (DATE(scan_date) >= "' . $From_Date . '" AND DATE(scan_date) <= "' . $To_Date . '"), 1, 0)) AS Pending_Punch', false);
            $this->db->select('SUM(IF(is_file_punched = "Y" AND ' . $table . '.is_deleted = "N" AND is_file_approved = "N" AND (DATE(punched_date) >= "' . $From_Date . '" AND DATE(punched_date) <= "' . $To_Date . '"), 1, 0)) AS Pending_Approve', false);
            $this->db->select('SUM(IF(is_file_punched = "Y" AND ' . $table . '.is_deleted = "N" AND is_file_approved = "N" AND Is_Rejected = "Y" AND (DATE(punched_date) >= "' . $From_Date . '" AND DATE(punched_date) <= "' . $To_Date . '"), 1, 0)) AS Reject', false);
        } else {
            $this->db->select('SUM(IF(is_final_submitted = "Y" AND ' . $table . '.is_deleted = "N", 1, 0)) AS Scan', false);
            $this->db->select('SUM(IF(' . $table . '.is_deleted = "N" AND is_file_punched="Y", 1, 0)) AS Punch', false);
            $this->db->select('SUM(IF(' . $table . '.is_deleted = "N" AND is_file_approved="Y", 1, 0)) AS Approve', false);
            $this->db->select('SUM(IF(is_file_punched = "N" AND ' . $table . '.is_deleted = "N" AND is_final_submitted = "Y", 1, 0)) AS Pending_Punch', false);
            $this->db->select('SUM(IF(is_file_punched = "Y" AND ' . $table . '.is_deleted = "N" AND is_file_approved = "N" AND Is_Rejected = "N", 1, 0)) AS Pending_Approve', false);
            $this->db->select('SUM(IF(is_scan_resend = "Y" AND ' . $table . '.is_deleted = "N", 1, 0)) AS Scan_Reject', false);
            $this->db->select('SUM(IF(is_file_punched = "Y" AND ' . $table . '.is_deleted = "N" AND is_file_approved = "N" AND Is_Rejected = "Y", 1, 0)) AS Punch_Reject', false);
        }

        $this->db->from($table);
        $this->db->join('master_group', 'master_group.group_id = ' . $table . '.group_id');
        $this->db->where($table . '.is_deleted', 'N');
        $this->db->group_by($table . '.group_id, master_group.group_name');

        $query = $this->db->get();
        $data = $query->result_array();
        echo json_encode(['data' => $data]);
    }

    function get_report_for_super_approver()
    {
        $this->db->select('master_group.group_name,master_group.group_id');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('master_group', 'master_group.group_id = y{$this->year_id}_scan_file.group_id');
        $this->db->select('SUM(IF(is_file_punched = "Y"  AND is_file_approved = "Y" AND Is_Rejected = "N" , 1, 0)) AS Approve', false);
        $this->db->select('SUM(IF(is_file_punched = "Y" AND is_file_approved = "N" AND Is_Rejected = "Y" AND y{$this->year_id}_scan_file.Edit_Permission ="N"  , 1, 0)) AS Reject', false);
        $this->db->select('SUM(IF(is_file_punched = "Y" AND is_file_approved = "N" AND Is_Rejected = "N",  1, 0)) AS Pending_Approve', false);
        $this->db->where('y{$this->year_id}_scan_file.is_deleted', 'N');
        $this->db->group_by('y{$this->year_id}_scan_file.group_id, master_group.group_name');
        $query = $this->db->get();
        $data = $query->result_array();
        $formattedData = [];
        foreach ($data as $row) {
            $formattedData[] = ['group_name' => '<a href="reject_list_company/' . $row['group_id'] . '" target="_blank">' . $row['group_name'] . '</a>', 'Approve' => $row['Approve'], 'Reject' => $row['Reject'], 'Pending_Approve' => $row['Pending_Approve'],];
        }
        echo json_encode(['data' => $formattedData]);
    }
    function get_report_for_super_scanner()
    {
        $this->db->select('master_group.group_name,master_group.group_id');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->join('master_group', 'master_group.group_id = y{$this->year_id}_scan_file.group_id');
        $this->db->select('SUM(IF(y{$this->year_id}_scan_file.is_final_submitted = "Y" , 1, 0)) AS Scan', false);
        $this->db->select('SUM(IF(is_scan_resend = "Y" , 1, 0)) AS Reject', false);
        $this->db->select('SUM(IF(is_temp_scan = "Y" AND Scan_Complete = "N" AND temp_scan_reject = "N",  1, 0)) AS Pending', false);
        $this->db->select('SUM(IF(is_temp_scan = "Y" AND Scan_Complete = "Y" AND temp_scan_reject = "N" AND document_verified="N",  1, 0)) AS Pending_Verification', false);
        $this->db->where('y{$this->year_id}_scan_file.is_deleted', 'N');
        $this->db->group_by('y{$this->year_id}_scan_file.group_id, master_group.group_name');
        $query = $this->db->get();
        $data = $query->result_array();
        $formattedData = [];
        foreach ($data as $row) {
            $formattedData[] = ['group_name' => '<a href="super_scan/' . $row['group_id'] . '">' . $row['group_name'] . '</a>', 'Scan' => $row['Scan'], 'Reject' => $row['Reject'], 'Pending' => $row['Pending'], 'Pending_Verification' => $row['Pending_Verification'],];
        }
        echo json_encode(['data' => $formattedData]);
    }
}
