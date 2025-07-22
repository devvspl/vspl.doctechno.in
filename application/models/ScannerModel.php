<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ScannerModel extends MY_Model
{
    protected $year_id;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->year_id = $this->session->userdata('year_id') ?? ($this->db->select('id')->from('financial_years')->where('is_current', 1)->get()->row()->id ?? null);
    }
    public function getScannedFile($status, $document_name, $from_date, $to_date, $page = null, $per_page = null)
    {
        if ($page !== null && $per_page !== null && $per_page > 0) {
            $offset = ($page - 1) * $per_page;
            $this->db->limit($per_page, $offset);
        }
        $user_id = $this->session->userdata('user_id');
        $this->db->select('*');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('temp_scan_by', $user_id);
        $this->db->where('temp_scan_date !=', '0000-00-00');
        if ($status === 'submitted') {
            $this->db->where(['is_final_submitted' => 'Y', 'is_deleted' => 'N']);
        } elseif ($status === 'pending') {
            $this->db->where(['is_final_submitted' => 'N', 'is_deleted' => 'N']);
        } elseif ($status === 'rejected') {
            $this->db->where(['is_temp_scan_rejected' => 'Y', 'is_deleted' => 'N']);
        } elseif ($status === 'deleted') {
            $this->db->where('is_deleted', 'Y');
        }
        if ($document_name) {
            $this->db->group_start()
                ->like('document_name', $document_name)
                ->or_like('file_name', $document_name)
                ->group_end();
        }

        if ($from_date) {
            $this->db->where('DATE(temp_scan_date) >=', date('Y-m-d', strtotime($from_date)));
        }
        if ($to_date) {
            $this->db->where('DATE(temp_scan_date) <=', date('Y-m-d', strtotime($to_date)));
        }
        $this->db->order_by('scan_id', 'desc');
        $this->db->limit($per_page, ($page - 1) * $per_page);
        return $this->db->get()->result_array();
    }
    function getScannedFileCount($status = null, $document_name = null, $from_date = null, $to_date = null)
    {

        $user_id = $this->session->userdata('user_id');
        $this->db->from("y{$this->year_id}_scan_file");
        $this->db->where('temp_scan_by', $user_id);
        $this->db->where('temp_scan_date !=', '0000-00-00');
        if ($status === 'submitted') {
            $this->db->where(['is_final_submitted' => 'Y', 'is_deleted' => 'N']);
        } elseif ($status === 'pending') {
            $this->db->where(['is_final_submitted' => 'N', 'is_deleted' => 'N']);
        } elseif ($status === 'rejected') {
            $this->db->where(['is_temp_scan_rejected' => 'Y', 'is_deleted' => 'N']);
        } elseif ($status === 'deleted') {
            $this->db->where('is_deleted', 'Y');
        }
        if ($document_name) {
            $this->db->group_start()
                ->like('document_name', $document_name)
                ->or_like('file_name', $document_name)
                ->group_end();
        }
        if ($from_date) {
            $this->db->where('DATE(temp_scan_date) >=', date('Y-m-d', strtotime($from_date)));
        }
        if ($to_date) {
            $this->db->where('DATE(temp_scan_date) <=', date('Y-m-d', strtotime($to_date)));
        }
        return $this->db->count_all_results();
    }


}