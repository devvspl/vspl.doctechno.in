<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Entry_confirmation extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->logged_in();
		$this->load->database();
		$this->load->model(array('Punch_model'));
	}

	private function logged_in()
	{
		if (!$this->session->userdata('authenticated')) {
			redirect('/');
		}
	}

	public function index()
	{
		$this->session->set_userdata('top_menu', 'entry_confirmation');
		$this->data['filelist'] = $this->Punch_model->get_files_for_tally_confirmation();
		$this->data['main'] = 'entry_confirmation/list';
		$this->load->view('layout/template', $this->data);
	}

	function file_entry_confirm($scanId)
	{
		$this->db->where('scan_id', $scanId)
			->update("y{$this->year_id}_scan_file", array('is_entry_confirmed' => 'Y', 'confirmed_date' => date('Y-m-d H:i:s')));

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('message', '<p class="text-success text-center">File Entry Confirmed Successfully.</p>');
		} else {
			$this->session->set_flashdata('message', '<p class="text-danger text-center">Something went wrong. Please try again.</p>');
		}

		redirect('entry_confirmation');
	}

}
