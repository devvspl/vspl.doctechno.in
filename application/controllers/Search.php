<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Search extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model(array('Department_model', 'Firm_model', 'Location_model', 'Ledger_model', 'Punch_model', 'Search_model'));
        $this->load->helper('url', 'form');
        $this->load->library('pagination');
    }
    private function logged_in() {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    public function search_global() {
        $this->session->set_userdata('top_menu', 'search_master');
        $this->session->set_userdata('sub_menu', 'search_global');
        $this->form_validation->set_rules('searchbartext', 'Search', 'trim|required');
        $this->data['searchbartext'] = $search = $this->input->post('searchbartext');
        if ($this->form_validation->run() == false) {
            $this->data['main'] = 'records/global_search';
            $this->load->view('layout/template', $this->data);
        } else {
            $result = $this->Search_model->search_global($search);
            $this->data['result'] = $result;
            $this->data['main'] = 'records/global_search';
            $this->load->view('layout/template', $this->data);
        }
    }
    public function search_with_filter() {
        $this->session->set_userdata('top_menu', 'search_master');
        $this->session->set_userdata('sub_menu', 'search_with_filter');
        $this->data['companylist'] = $this->Department_model->get_companylist();
        $this->data['vendorlist'] = $this->Firm_model->vendor_list();
        $this->data['locationlist'] = $this->Location_model->get_location_list();
        $this->data['ledgerlist'] = $this->Ledger_model->get_ledger_list();
        $this->data['my_doctype_list'] = $this->Punch_model->get_my_permissioned_doctype_list();
        $this->data['company_wise'] = $company_wise = $this->input->post('company_wise');
        $this->data['vendor_wise'] = $vendor_wise = $this->input->post('vendor_wise');
        $this->data['from_date'] = $from_date = $this->input->post('from_date');
        $this->data['to_date'] = $to_date = $this->input->post('to_date');
        $this->data['work_location'] = $work_location = $this->input->post('work_location');
        $this->data['ledger_wise'] = $ledger_wise = $this->input->post('ledger_wise');
        $this->data['document_wise'] = $document_wise = $this->input->post('document_wise');
        $search = $this->input->post('search');
        if (isset($search)) {
            $result = $this->Search_model->get_search_with_filter_data($company_wise, $vendor_wise, $from_date, $to_date, $work_location, $ledger_wise, $document_wise);
            $this->data['result'] = $result;
        }
        $this->data['main'] = 'records/search_with_filter';
        $this->load->view('layout/template', $this->data);
    }
    public function search_with_filter_status() {
        $this->session->set_userdata('top_menu', 'search_master');
        $this->session->set_userdata('sub_menu', 'search_punch_records');
        $this->data['companylist'] = $this->Department_model->get_companylist();
        $this->data['vendorlist'] = $this->Firm_model->vendor_list();
        $this->data['locationlist'] = $this->Location_model->get_location_list();
        $this->data['ledgerlist'] = $this->Ledger_model->get_ledger_list();
        $this->data['my_doctype_list'] = $this->Punch_model->get_my_permissioned_doctype_list();
        $this->data['company_wise'] = $company_wise = $this->input->post('company_wise');
        $this->data['vendor_wise'] = $vendor_wise = $this->input->post('vendor_wise');
        $this->data['from_date'] = $from_date = $this->input->post('from_date');
        $this->data['to_date'] = $to_date = $this->input->post('to_date');
        $this->data['work_location'] = $work_location = $this->input->post('work_location');
        $this->data['ledger_wise'] = $ledger_wise = $this->input->post('ledger_wise');
        $this->data['document_wise'] = $document_wise = $this->input->post('document_wise');
        $search = $this->input->post('search');
        if (isset($search)) {
            $result = $this->Search_model->get_search_with_filter_status_data($company_wise, $vendor_wise, $from_date, $to_date, $work_location, $ledger_wise, $document_wise);
            $this->data['result'] = $result;
        }
        // echo "<pre>";
        // print_r($this->data['result']);
        // echo "</pre>";
        // exit;
        $this->data['main'] = 'records/search_with_filter_status';
        $this->load->view('layout/template', $this->data);
    }
    public function filecheck() {
        $this->db->select('File_Location,Scan_Id');
        $this->db->from('y{$this->year_id}_scan_file');
        $this->db->where_in('scan_id', array(10680, 10682, 10725, 10727, 10804, 10816, 10818, 11107, 11109, 1115));
        $result = $this->db->get()->result_array();
        foreach ($result as $row) {
            $href = $row['file_path'];
            echo "<a href='$href' target='_blank'>" . $row['scan_id'] . "</a><br>";
        }
    }
}
