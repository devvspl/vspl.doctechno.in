<?php
defined('BASEPATH') or exit('No direct script access allowed');
class All_report extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Record_model');
        $this->load->model('Group_model');
    }
    private function logged_in() {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    // public function index() {
    //     $this->session->set_userdata('top_menu', 'report');
    //     $group = $this->input->get('Group');
    //     $search = $this->input->get('search');
    //     $group = $group !== null ? $group : '';
    //     $search = $search !== null ? $search : '';
    //     $this->data['grouplist'] = $this->Group_model->get_group_list();
    //     $this->load->library('pagination');
    //     $config['base_url'] = site_url('All_report/index?Group=' . urlencode($group) . '&search=' . urlencode($search));
    //     $config['total_rows'] = $this->Record_model->count_filtered_records($group, $search);
    //     $config['per_page'] = 10;
    //     $config['page_query_string'] = TRUE;
    //     $config['query_string_segment'] = 'page';
    //     $this->pagination->initialize($config);
    //     $page = ($this->input->get('page')) ? $this->input->get('page') : 0;
    //     $record_list = $this->Record_model->get_filtered_records($config['per_page'], $page, $group, $search);
    //     $this->data['record_list'] = $record_list;
    //     $this->data['pagination'] = $this->pagination->create_links();
    //     $this->data['main'] = 'records/all_report';
    //     $this->load->view('layout/template', $this->data);
    // }
	public function index() {
		$this->session->set_userdata('top_menu', 'report');
		$group = $this->input->get('Group');
		$search = $this->input->get('search');
		$from_date = $this->input->get('from_date'); // Get start date
		$to_date = $this->input->get('to_date'); // Get end date
	
		// Default to empty strings if no values are provided
		$group = $group !== null ? $group : '';
		$search = $search !== null ? $search : '';
		$from_date = $from_date !== null ? $from_date : '';
		$to_date = $to_date !== null ? $to_date : '';
	
		$this->data['grouplist'] = $this->Group_model->get_group_list();
	
		// Initialize pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url('All_report/index?Group=' . urlencode($group) . '&search=' . urlencode($search) . '&from_date=' . urlencode($from_date) . '&to_date=' . urlencode($to_date));
		$config['total_rows'] = $this->Record_model->count_filtered_records($group, $search, $from_date, $to_date);
		$config['per_page'] = 10; // Define how many records per page
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$this->pagination->initialize($config);
	
		// Get the current page number or default to 0 for the first page
		$page = ($this->input->get('page')) ? $this->input->get('page') : 0;
	
		// Retrieve the records based on pagination
		$record_list = $this->Record_model->get_filtered_records($config['per_page'], $page, $group, $search, $from_date, $to_date);
	
		// Calculate the starting count based on the current page and number of records per page
		$start_count = ($page * $config['per_page']) + 1;
	
		// Pass start_count and record_list to the view
		$this->data['start_count'] = $start_count;
		$this->data['record_list'] = $record_list;
		$this->data['pagination'] = $this->pagination->create_links();
	
		$this->data['main'] = 'records/all_report';
		$this->load->view('layout/template', $this->data);
	}
	
	
}
