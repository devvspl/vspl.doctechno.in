<?php
class ScanFileController extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
    }
    public function index() {
        $this->load->view('scan_file_list');
    }
    public function getScanFileList() {
        $fileName = $this->input->get('file');
        $this->db->select('Scan_Id');
        $this->db->from('scan_file');
        $this->db->like('File', $fileName);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $scanIds = array_column($query->result_array(), 'Scan_Id');
        } else {
            $scanIds = [];
        }
        $tables = ['cash_voucher_items', 'gst_challan_detail', 'invoice_detail', 'labour_payment_detail', 'lodging_employee', 'punchfile', 'punchfile2', 'support_file', 'ticket_cancellation', 'vehicle_traveling', 'scan_file'];
        $results = [];
        foreach ($tables as $table) {
            if (!empty($scanIds)) {
                $this->db->select('*');
                $this->db->from($table);
                $this->db->where_in('Scan_Id', $scanIds);
                $query = $this->db->get();
                if ($query->num_rows() > 0) {
                    $results[$table] = $query->result_array();
                }
            }
        }
        $data['results'] = $results;
        $data['fileName'] = $fileName;
        $data['success_message'] = $this->session->flashdata('success_message');
        $this->load->view('scan_file_list', $data);
    }
    public function deleteTableAllRow($table, $scanId) {
        $this->db->where('Scan_Id', $scanId);
        $this->db->delete($table);
        $this->session->set_flashdata('success_message', "Row with Scan_Id: $scanId has been deleted from the $table table.");
        $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('ScanFileController/getScanFileList');
        redirect($redirectUrl);
    }
}
