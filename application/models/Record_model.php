<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Record_model extends MY_Model {
    function getRecordFile($Scan_Id) {
        $result = $this->db->select('punchfile2.*, scan_file.File_Punched, scan_file.Is_Rejected,scan_file.File_Approved')->from('punchfile2')->join('scan_file', 'scan_file.Scan_Id=punchfile2.Scan_Id')->where('punchfile2.Scan_Id', $Scan_Id)->get()->row();
        return $result;
    }
    function getRecordFile_Accounting($Scan_Id) {
        $result = $this->db->select('punchfile.*,scan_file.File_Punched,scan_file.Is_Rejected,scan_file.File_Approved')->from('punchfile')->join('scan_file', 'scan_file.Scan_Id=punchfile.Scan_Id')->where('punchfile.Scan_Id', $Scan_Id)->get()->row();
        return $result;
    }
    function vspl_getRecordFile_Accounting($Scan_Id) {
        $result = $this->db->select('punchfile.*,scan_file.File_Punched,scan_file.Is_Rejected,scan_file.File_Approved')->from('punchfile')->join('scan_file', 'scan_file.Scan_Id=punchfile.Scan_Id')->where('punchfile.Scan_Id', $Scan_Id)->get()->row();
        return $result;
    }
    function getRejectedList() {
        $group_id = $this->session->userdata('group_id');
        $query = $this->db->select('*')->from('scan_file')->where(array('Group_Id' => $group_id, 'Is_Rejected' => 'Y', 'Edit_Permission' => 'N'))->where('Is_Deleted', 'N')->order_by('Scan_Id', 'desc')->get();
        return $query->result_array();
    }
    function getRejectedList_SU() {
        $query = $this->db->select('*')->from('scan_file')->where(array('Is_Deleted' => 'N', 'Is_Rejected' => 'Y', 'Edit_Permission' => 'N'))->order_by('Scan_Id', 'desc')->get();
        return $query->result_array();
    }
    function get_user() {
        $group_id = $this->session->userdata('group_id');
        $query = $this->db->select('user_id,first_name,last_name')->from('users')->where(array('group_id' => $group_id))->order_by('user_id', 'desc')->get();
        return $query->result_array();
    }
    function get_record_list() {
        $group_id = $this->session->userdata('group_id');
        $query = $this->db->select('*')->from('scan_file')->where(array('Group_Id' => $group_id))->order_by('Scan_Id', 'desc')->get();
        return $query->result_array();
    }
    function get_filter_record($Scan_By, $Punch_By, $Approve_By) {
        $Scan_By = $Scan_By ? $Scan_By : '';
        $Punch_By = $Punch_By ? $Punch_By : '';
        $Approve_By = $Approve_By ? $Approve_By : '';
        $group_id = $this->session->userdata('group_id');
        if ($Scan_By != '') {
            $this->db->where('Scan_By', $Scan_By);
        }
        if ($Punch_By != '') {
            $this->db->where('Punch_By', $Punch_By);
        }
        if ($Approve_By != '') {
            $this->db->where('Approve_By', $Approve_By);
        }
        $query = $this->db->select('*')->from('scan_file')->where(array('Group_Id' => $group_id))->order_by('Scan_Id', 'desc')->get();
        // echo $this->db->last_query();die;
        return $query->result_array();
    }
    //=================Super Admin All Record================
    function get_all_record_list() {
        $query = $this->db->select('scan_file.Document_Name, scan_file.Doc_Type,scan_file.DocType_Id, scan_file.File_Location, scan_file.File, scan_file.Temp_Scan_Date, scan_file.Temp_Scan_By, scan_file.Scan_By, scan_file.Scan_Date, scan_file.Bill_Approver_Date, scan_file.File_Punched, scan_file.Punch_By, scan_file.Punch_Date, scan_file.File_Approved, scan_file.Approve_By, scan_file.Approve_Date, scan_file.Scan_Id, punchfile.ServiceNo, punchfile.BookingDate')->from('scan_file')->join('punchfile', 'scan_file.Scan_Id = punchfile.Scan_Id', 'left')->where('Is_Deleted', 'N')->order_by('scan_file.Scan_Id', 'desc')->get();
        return $query->result_array();
    }
	public function count_filtered_records($group = '', $search = '', $from_date = '', $to_date = '') {
        $this->db->select('scan_file.Document_Name, scan_file.Doc_Type,scan_file.DocType_Id, scan_file.File_Location, scan_file.File, scan_file.Temp_Scan_Date, scan_file.Temp_Scan_By, scan_file.Scan_By, scan_file.Scan_Date, scan_file.Bill_Approver_Date, scan_file.File_Punched, scan_file.Punch_By, scan_file.Punch_Date, scan_file.File_Approved, scan_file.Approve_By, scan_file.Approve_Date, scan_file.Scan_Id, punchfile.ServiceNo, punchfile.BookingDate');
        $this->db->from('scan_file');
        if (!empty($group)) {
            $this->db->where('scan_file.Group_Id', $group);
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('scan_file.Document_Name', $search);
            $this->db->or_like('scan_file.Doc_Type', $search);
            $this->db->or_like('scan_file.File_Location', $search);
            $this->db->or_like('scan_file.File', $search);
            $this->db->or_like('scan_file.Temp_Scan_Date', $search);
            $this->db->or_like('scan_file.Temp_Scan_By', $search);
            $this->db->or_like('scan_file.Scan_By', $search);
            $this->db->or_like('scan_file.Scan_Date', $search);
            $this->db->or_like('scan_file.Bill_Approver_Date', $search);
            $this->db->or_like('scan_file.File_Punched', $search);
            $this->db->or_like('scan_file.Punch_By', $search);
            $this->db->or_like('scan_file.Punch_Date', $search);
            $this->db->or_like('scan_file.File_Approved', $search);
            $this->db->or_like('scan_file.Approve_By', $search);
            $this->db->or_like('scan_file.Approve_Date', $search);
            $this->db->or_like('scan_file.Scan_Id', $search);
            $this->db->group_end();
        }
		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('scan_file.Temp_Scan_Date >=', $from_date);
			$this->db->where('scan_file.Temp_Scan_Date <=', $to_date);
			$this->db->where('scan_file.Scan_Date >=', $from_date);
			$this->db->where('scan_file.Scan_Date <=', $to_date);
		}
        $this->db->where('Is_Deleted', 'N');
        return $this->db->count_all_results();
    }
    // public function get_filtered_records($limit, $start, $group = '', $search = '') {
    //     $this->db->select('scan_file.Document_Name, scan_file.Doc_Type, scan_file.DocType_Id, scan_file.File_Location, scan_file.File, scan_file.Temp_Scan_Date, scan_file.Temp_Scan_By, scan_file.Scan_By, scan_file.Scan_Date, scan_file.Bill_Approver_Date, scan_file.File_Punched, scan_file.Punch_By, scan_file.Punch_Date, scan_file.File_Approved, scan_file.Approve_By, scan_file.Approve_Date, scan_file.Scan_Id, punchfile.ServiceNo, punchfile.BookingDate');
    //     $this->db->from('scan_file');
    //     $this->db->join('punchfile', 'scan_file.Scan_Id = punchfile.Scan_Id', 'left');
    //     if (!empty($group)) {
    //         $this->db->where('scan_file.Group_Id', $group);
    //     }
    //     if (!empty($search)) {
    //         $this->db->group_start();
    //         $this->db->like('scan_file.Document_Name', $search);
    //         $this->db->or_like('scan_file.Doc_Type', $search);
    //         $this->db->or_like('scan_file.File_Location', $search);
    //         $this->db->or_like('scan_file.File', $search);
    //         $this->db->or_like('scan_file.Temp_Scan_Date', $search);
    //         $this->db->or_like('scan_file.Temp_Scan_By', $search);
    //         $this->db->or_like('scan_file.Scan_By', $search);
    //         $this->db->or_like('scan_file.Scan_Date', $search);
    //         $this->db->or_like('scan_file.Bill_Approver_Date', $search);
    //         $this->db->or_like('scan_file.File_Punched', $search);
    //         $this->db->or_like('scan_file.Punch_By', $search);
    //         $this->db->or_like('scan_file.Punch_Date', $search);
    //         $this->db->or_like('scan_file.File_Approved', $search);
    //         $this->db->or_like('scan_file.Approve_By', $search);
    //         $this->db->or_like('scan_file.Approve_Date', $search);
    //         $this->db->or_like('scan_file.Scan_Id', $search);
    //         $this->db->or_like('punchfile.ServiceNo', $search);
    //         $this->db->or_like('punchfile.BookingDate', $search);
    //         $this->db->group_end();
    //     }
    //     $this->db->where('scan_file.Is_Deleted', 'N');
    //     $this->db->limit($limit, $start);
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }
	public function get_filtered_records($limit, $start, $group = '', $search = '', $from_date = '', $to_date = '') {
		$this->db->select('scan_file.Document_Name, scan_file.Doc_Type, scan_file.DocType_Id, scan_file.File_Location, scan_file.File, scan_file.Temp_Scan_Date, scan_file.Temp_Scan_By, scan_file.Scan_By, scan_file.Scan_Date, scan_file.Bill_Approver_Date, scan_file.File_Punched, scan_file.Punch_By, scan_file.Punch_Date, scan_file.File_Approved, scan_file.Approve_By, scan_file.Approve_Date, scan_file.Scan_Id, punchfile.ServiceNo, punchfile.BookingDate');
		$this->db->from('scan_file');
		$this->db->join('punchfile', 'scan_file.Scan_Id = punchfile.Scan_Id', 'left');
		
		if (!empty($group)) {
			$this->db->where('scan_file.Group_Id', $group);
		}
		
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('scan_file.Document_Name', $search);
			$this->db->or_like('scan_file.Doc_Type', $search);
			$this->db->or_like('scan_file.File_Location', $search);
			$this->db->or_like('scan_file.File', $search);
			$this->db->or_like('scan_file.Temp_Scan_Date', $search);
			$this->db->or_like('scan_file.Temp_Scan_By', $search);
			$this->db->or_like('scan_file.Scan_By', $search);
			$this->db->or_like('scan_file.Scan_Date', $search);
			$this->db->or_like('scan_file.Bill_Approver_Date', $search);
			$this->db->or_like('scan_file.File_Punched', $search);
			$this->db->or_like('scan_file.Punch_By', $search);
			$this->db->or_like('scan_file.Punch_Date', $search);
			$this->db->or_like('scan_file.File_Approved', $search);
			$this->db->or_like('scan_file.Approve_By', $search);
			$this->db->or_like('scan_file.Approve_Date', $search);
			$this->db->or_like('scan_file.Scan_Id', $search);
			$this->db->or_like('punchfile.ServiceNo', $search);
			$this->db->or_like('punchfile.BookingDate', $search);
			$this->db->group_end();
		}
	
		// Date range filtering
		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('scan_file.Temp_Scan_Date >=', $from_date);
			$this->db->where('scan_file.Temp_Scan_Date <=', $to_date);
			$this->db->where('scan_file.Scan_Date >=', $from_date);
			$this->db->where('scan_file.Scan_Date <=', $to_date);
		}
	
		$this->db->where('scan_file.Is_Deleted', 'N');
		$this->db->limit($limit, $start);
		
		$query = $this->db->get();
		return $query->result_array();
	}
	
}
