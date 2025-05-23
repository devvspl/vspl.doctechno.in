<?php
defined('BASEPATH') or exit('No direct script access allowed');
class VSPL_Punch extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Punch_model');
        $this->load->model('Group_model');
        $this->load->model('Record_model');
    }
    private function logged_in() {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    public function file_entry() {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', 'punch');
        $this->data['main'] = 'punch/_vspl_punch';
        $this->load->view('layout/template', $this->data);
    }
    public function focus_exports() {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', 'punch');
    
        $doctype = $this->input->get('doctype') ?? '';
        $search = $this->input->get('search') ?? '';
        $from_date = $this->input->get('from_date') ?? '';
        $to_date = $this->input->get('to_date') ?? '';
    
        $this->data['doctype'] = $this->db->where(['status' => 'A'])->get('master_doctype')->result_array();
    
        
        $this->load->library('pagination');
        $config['base_url'] = site_url('VSPL_Punch/focus_exports?doctype=' . urlencode($doctype) . '&search=' . urlencode($search) . '&from_date=' . urlencode($from_date) . '&to_date=' . urlencode($to_date));
        $config['total_rows'] = $this->Punch_model->get_total_rows($doctype, $search, $from_date, $to_date);
        $config['per_page'] = 10;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
    
        $this->pagination->initialize($config);
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 0;
        $offset = $page * $config['per_page'];
    
        $record_list = $this->Punch_model->get_records($config['per_page'], $offset, $doctype, $search, $from_date, $to_date);

        $this->data['start_count'] = $offset + 1;
        $this->data['record_list'] = $record_list;
        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['main'] = 'vspl/focus_exports';
        $this->load->view('layout/template', $this->data);
    }
    public function export_cash_payment() {
        $this->load->helper('file');
        $filename = 'cash_payment_export_' . date('Ymd') . '.csv';
        $doctype = $this->input->get('doctype') ?? '';
        $from_date = $this->input->get('from_date') ?? '';
        $to_date = $this->input->get('to_date') ?? '';
    
        $data = $this->Punch_model->get_export_data($doctype, $from_date, $to_date);
    
        $output = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=\"$filename\"");
    
        
        $columns = ['DocNo', 'Date', 'Time', 'CashBankAC', 'Business Entity', 'Narration', 'Favouring', 'TDS JV No', 'Cost Center', 'location_id', 'Crop', 'Activity', 'State', 'Category', 'Region', 'Department', 'PMT Category', 'Business Unit', 'Account', 'Amount', 'Reference', 'Remarks', 'TDS'];
        fputcsv($output, $columns);
    
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
    
        fclose($output);
        exit();
    }
}
