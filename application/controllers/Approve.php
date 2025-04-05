<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Approve extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model("Approve_model");
        $this->load->model('Group_model');
    }
    private function logged_in() {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    public function index() {
        $this->session->set_userdata('top_menu', 'approve_master');
        $this->session->set_userdata('sub_menu', 'approve');
        if ($_SESSION['role'] == 'super_approver') {
            $this->data['main'] = 'approve/super_approver_list';
            $this->load->view('layout/template', $this->data);
        } else {
            $this->data['pending_for_approval'] = $this->Approve_model->pending_for_approval();
            $this->data['main'] = 'approve/approvefile';
            $this->load->view('layout/template', $this->data);
        }
    }
    public function my_approved_file() {
        $this->session->set_userdata('top_menu', 'approve_master');
        $this->session->set_userdata('sub_menu', 'my_approved_file');
        $this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
        $this->form_validation->set_rules('to_date', 'To Date', 'trim|required');
        $search = $this->input->get('search');
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get('to_date');
        $group = $this->input->get('Group');
        $group = $group !== null ? $group : '';
		$Doctype = $this->input->get('Doctype');
        $Doctype = $Doctype !== null ? $Doctype : '';
        $search = $search !== null ? $search : '';
        $from_date = $from_date !== null ? $from_date : '';
        $to_date = $to_date !== null ? $to_date : '';
        $page = ($this->input->get('page')) ? $this->input->get('page') : 0;
        $this->data['grouplist'] = $this->Group_model->get_group_list();
        $this->data['doctypelist'] = $this->db->where(['status'=>'A'])->get('master_doctype')->result_array();
        $this->load->library('pagination');
        $config['base_url'] = site_url('Approve/my_approved_file?search=' . urlencode($search) . '&from_date=' . urlencode($from_date) . '&to_date=' . urlencode($to_date) . '&Group=' . urlencode($group).  '&Doctype=' . urlencode($Doctype));
        $config['total_rows'] = $this->Approve_model->count_filtered_records($search, $from_date, $to_date, $group, $Doctype);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $this->pagination->initialize($config);
        $approve_file_list = $this->Approve_model->get_my_approved_file($config['per_page'], $page, $group, $Doctype, $search, $from_date, $to_date);
        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['approve_file_list'] = $approve_file_list;
        $this->data['main'] = 'approve/my_approved_file';
        $this->load->view('layout/template', $this->data);
    }
    function approve_record($Scan_Id) {
        $role = $this->session->userdata('role');
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Scan_Id', $Scan_Id);
        $data = array('File_Approved' => 'Y', 'Approve_Date' => date('Y-m-d H:i:s'));
        if ($role == 'admin') {
            $data['Admin_Approve'] = 'Y';
            $data['Is_Rejected'] = 'N';
        } else {
            $data['Approve_By'] = $user_id;
        }
        $result = $this->db->update('scan_file', $data);
        if ($result) {
            $this->customlib->send_for_accounting($Scan_Id);
            $this->session->set_flashdata('message', '<p class="text-success text-center">File Approved Successfully.</p>');
            redirect($role == 'admin' ? 'admin_rejected_list' : 'approve');
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect($role == 'admin' ? 'admin_rejected_list' : 'approve');
        }
    }
    function reject_record($Scan_Id) {
        $user_id = $this->session->userdata('user_id');
        $Reject_Remark = $this->input->post('Remark');
        $this->db->where('Scan_Id', $Scan_Id);
        $result = $this->db->update('scan_file', array('Is_Rejected' => 'Y', 'Approve_By' => $user_id, 'Reject_Remark' => $Reject_Remark, 'Reject_Date' => date('Y-m-d')));
        if ($result) {
            echo json_encode(array('status' => '200', 'message' => 'File Rejected Successfully.'));
        } else {
            echo json_encode(array('status' => '400', 'message' => 'Something went wrong. Please try again.'));
        }
    }
    function approve_record_by_super_approver($Scan_Id) {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Scan_Id', $Scan_Id);
        $data = array('File_Approved' => 'Y', 'Approve_By' => $user_id, 'Approve_Date' => date('Y-m-d H:i:s'));
        $result = $this->db->update('scan_file', $data);
        if ($result) {
            $this->customlib->send_for_accounting($Scan_Id);
            echo json_encode(array('status' => '200', 'message' => 'File Approved Successfully.'));
        } else {
            echo json_encode(array('status' => '400', 'message' => 'Something went wrong. Please try again.'));
        }
    }
    function rejected_by_me() {
        $this->session->set_userdata('top_menu', 'approve_master');
        $this->session->set_userdata('sub_menu', 'rejected_by_me');
        $this->data['rejected_by_me'] = $this->Approve_model->rejected_by_me();
        $this->data['main'] = 'approve/rejected_by_me';
        $this->load->view('layout/template', $this->data);
    }
    function delete_record($Scan_Id) {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Scan_Id', $Scan_Id);
        $data = array('Is_Deleted' => 'Y', 'Delete_Date' => date('Y-m-d H:i:s'), 'Deleted_By' => $user_id);
        $result = $this->db->update('scan_file', $data);
        $this->db->where('Scan_Id', $Scan_Id)->delete('punchfile');
        $this->db->where('Scan_Id', $Scan_Id)->delete('punchfile2');
        if ($result) {
            $this->session->set_flashdata('message', '<p class="text-success text-center">File Deleted Successfully.</p>');
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    function get_pending_list_approve() {
        $group_id = $this->input->post('group_id');
        $doctype = $this->input->post('doctype');
        $group_name = $this->customlib->get_GroupName($group_id);
    
        $this->db->select('scan_file.*, CONCAT(users.first_name, " ", users.last_name) as full_name', false);
        $this->db->from('scan_file');
        $this->db->join('users', 'scan_file.punch_by = users.user_id', 'left');
        $this->db->where('File_Punched', 'Y');
        $this->db->where('File_Approved', 'N');
        $this->db->where('Is_Rejected', 'N');
        $this->db->where('Is_Deleted', 'N');
        $this->db->where('scan_file.Group_Id', $group_id);
    $case_condition = "(CASE 
                        WHEN DocType_Id = 57 THEN at_finance = 'Y'
                        WHEN DocType_Id = 58 THEN at_finance = 'Y'
                        ELSE 1=1
                    END)";
        $this->db->where($case_condition, null, false);
    
        if ($doctype != '' && $doctype != null) {
            $this->db->where('DocType_Id', $doctype);
        }
    
        $this->db->order_by('Scan_Id', 'desc');
        $query = $this->db->get()->result_array();
    
        echo json_encode(array('data' => $query, 'group_name' => $group_name));
    }
    
    
    function reject_list_company($id) {
        $group_id = $id;
        $query = $this->db->select('*')->from('scan_file')->where(array('Group_Id' => $group_id, 'Is_Rejected' => 'Y', 'Edit_Permission' => 'N'))->where('Is_Deleted', 'N')->order_by('Scan_Id', 'desc')->get()->result_array();
        $this->data['rejected_list'] = $query;
        $this->data['main'] = 'records/super_rejected_list';
        $this->load->view('layout/template', $this->data);
    }
}
