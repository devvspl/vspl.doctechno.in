<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Punch_model extends MY_Model {
    function get_file_for_punch() {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*')
            ->from('scan_file')
            ->where('File_Punched', 'N')
            ->where('Scan_Resend', 'N')
            ->where_in('Group_Id', $group_id)
            ->where('Final_Submit', 'Y')
            ->where('at_finance', 'P')
            ->where('temp_scan_reject', 'N')
            ->where("Document_Name !=", '')
            ->where("((Location IS NOT NULL AND Bill_Approved = 'Y') OR Location IS NULL)")
            ->order_by('Scan_Id', 'desc');
    
        if ($group_id === '16') {
            $this->db->where('is_extract', 'Y');
        }
    
        $query = $this->db->get();
        return $query->result_array();
    }

    function vspl_get_file_for_punch() {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*')->from('scan_file')->where('File_Punched', 'Y')->where('Scan_Resend', 'N')->where_in('Group_Id', $group_id)->where('Final_Submit', 'Y')->where('at_finance', 'N')->where('Is_Rejected', 'N')->where('Is_Finance_Rejected', 'N')->where("((Location IS NOT NULL AND Bill_Approved = 'Y')  OR Location IS NULL)")->order_by('Scan_Id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_my_permissioned_doctype_list() {
        $user_id = $this->session->userdata('user_id');
        if ($_SESSION['role'] == 'super_admin' || $_SESSION['role'] == 'admin') {
            $this->db->select('*');
            $this->db->from('master_doctype');
            $this->db->where('Status', 'A');
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $query = $this->db->select('d.*')->from('`permission` p')->join('master_doctype d', 'd.alias = p.permission_name')->join('user_permission up', 'up.permission_id = p.permission_id')->where(array('p.category' => 'Doc Type', 'up.user_id' => $user_id))->order_by('d.file_type', 'asc')->get();
            return $query->result_array();
        }
    }
    function get_my_punched_file() {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from('scan_file')->where(array('File_Punched' => 'Y', 'Punch_By' => $user_id, 'Punch_Date' => date('Y-m-d')))->order_by('Scan_Id', 'desc')->get();
        return $query->result_array();
    }
    function search_punched_file($from_date, $to_date) {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from('scan_file')->where(array('File_Punched' => 'Y', 'Punch_By' => $user_id, 'Punch_Date >=' => $from_date, 'Punch_Date <=' => $to_date))->order_by('Scan_Id', 'desc')->get();
        return $query->result_array();
    }
    function get_my_punched_file_all() {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from('scan_file')->where(array('File_Punched' => 'Y', 'Punch_By' => $user_id))->order_by('Scan_Id', 'desc')->get();
        return $query->result_array();
    }
    function get_my_saved_file() {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from('scan_file')->where(array('File_Punched' => 'N', 'Partial_Punch' => 'Y', 'Punch_By' => $user_id))->order_by('Scan_Id', 'desc')->get();
        return $query->result_array();
    }
    function get_rejected_punch_list() {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from('scan_file')->where(array('Punch_By' => $user_id, 'Is_Rejected' => 'Y'))->order_by('Scan_Id', 'desc')->get();
        return $query->result_array();
    }
    function get_finance_rejected_punch_list() {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from('scan_file')->where(array('Punch_By' => $user_id, 'Is_Finance_Rejected' => 'Y'))->order_by('Scan_Id', 'desc')->get();
        return $query->result_array();
    }
    function get_total_punched_by_me() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Punch_By', $user_id);
        $this->db->where('File_Punched', 'Y');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    function get_total_approved() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Punch_By', $user_id);
        $this->db->where('File_Approved', 'Y');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    function pending_for_punch() {
          $group_id = $this->session->userdata('group_id');
        $this->db->select('*')
            ->from('scan_file')
            ->where('File_Punched', 'N')
            ->where('Scan_Resend', 'N')
            ->where_in('Group_Id', $group_id)
            ->where('Final_Submit', 'Y')
            ->where('at_finance', 'P')
            ->where('temp_scan_reject', 'N')
            ->where("Document_Name !=", '')
            ->where("((Location IS NOT NULL AND Bill_Approved = 'Y') OR Location IS NULL)")
            ->order_by('Scan_Id', 'desc');
    
        if ($group_id === '16') {
            $this->db->where('is_extract', 'Y');
        }
    
        $query = $this->db->get();
          return $query->num_rows();
        
        
    }
    function rejected_punch() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Punch_By', $user_id);
        $this->db->where('Is_Rejected', 'Y');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    function finance_rejected_punch() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Punch_By', $user_id);
        $this->db->where('Is_Finance_Rejected', 'Y');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    function pending_for_approval_punch_by_me() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Punch_By', $user_id);
        $this->db->where('File_Approved', 'N');
        $this->db->where('Is_Rejected', 'N');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    function get_approved_file() {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from('scan_file')->where(array('File_Punched' => 'Y', 'Punch_By' => $user_id, 'File_Approved' => 'Y', 'Entry_Confirm' => 'N'))->order_by('Scan_Id', 'desc')->get();
        return $query->result_array();
    }
    function get_files_for_tally_confirmation() {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from('scan_file')->where(array('File_Punched' => 'Y', 'Punch_By' => $user_id, 'File_Approved' => 'N', 'Entry_Confirm' => 'N'))->order_by('Scan_Id', 'desc')->get();
        return $query->result_array();
    }
    function vspl_get_total_punched_by_me() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Finance_Punch_By', $user_id);
        $this->db->where('File_Punched', 'Y');
        $this->db->where('at_finance', 'Y');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    function vspl_get_total_approved() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Finance_Punch_By', $user_id);
        $this->db->where('File_Approved', 'Y');
        $this->db->where('at_finance', 'Y');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    function vspl_pending_for_punch() {
        $group_id = $this->session->userdata('group_id');
        $this->db->where('Final_Submit', 'Y');
        $this->db->where('File_Punched', 'Y');
        $this->db->where('Is_Rejected', 'N');
        $this->db->where('Scan_Resend', 'N');
        $this->db->where('Is_Deleted', 'N');
        $this->db->where("((Location IS NOT NULL AND Bill_Approved = 'Y')  OR Location IS NULL)");
        $this->db->where('Group_Id', $group_id);
        $this->db->where('at_finance', 'N');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    function vspl_rejected_punch() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Finance_Punch_By', $user_id);
        $this->db->where('Is_Rejected', 'Y');
        $this->db->where('at_finance', 'Y');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    function vspl_pending_for_approval_punch_by_me() {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('Finance_Punch_By', $user_id);
        $this->db->where('File_Approved', 'N');
        $this->db->where('Is_Rejected', 'N');
        $this->db->where('at_finance', 'Y');
        $query = $this->db->get('scan_file');
        return $query->num_rows();
    }
    public function get_records($limit, $offset, $doctype = null, $search = null, $from_date = null, $to_date = null) {
        $this->db->select(['punchfile.document_number AS DocNo', 'punchfile.finance_punch_date AS Date', "'' AS EmptyField", 'punchfile.account AS CashBankAC', 'master_business_entity.business_entity_code AS BusinessEntity', 'punchfile.narration AS sNarration', 'punchfile.favouring AS Favouring', 'punchfile.TDS_JV_no AS TDSJVNo', 'master_cost_center.cost_center_name AS CostCenter', 'master_work_location.location_name AS Location', 'master_crop.crop_code AS Crop', 'master_activity.activity_code AS Activity', 'master_state.state_code AS State', 'master_crop_category.crop_category_code AS Category', 'master_region.region_code AS Region', 'master_department.department_code AS Department', 'cash_voucher_items.payment_method AS PMTCategory', 'master_business_unit.business_unit_code AS BusinessUnit', 'master_account_ledger.account_code AS Account', 'cash_voucher_items.Total_Amount AS TotalAmount', 'cash_voucher_items.ReferenceNo AS Reference', 'cash_voucher_items.Remark AS sRemarks', 'punchfile.TDS_section AS TDS']);
        $this->db->from('cash_voucher_items');
        $this->db->join('scan_file', 'scan_file.Scan_Id = cash_voucher_items.Scan_Id', 'left');
        $this->db->join('punchfile', 'scan_file.Scan_Id = punchfile.Scan_Id', 'left');
        $this->db->join('master_business_entity', 'punchfile.business_entity_id = master_business_entity.business_entity_id', 'left');
        $this->db->join('master_cost_center', 'cash_voucher_items.cost_center_id = master_cost_center.cost_center_id', 'left');
        $this->db->join('master_business_unit', 'cash_voucher_items.business_unit_id = master_business_unit.business_unit_id', 'left');
        $this->db->join('master_state', 'cash_voucher_items.state_id = master_state.state_id', 'left');
        $this->db->join('master_crop_category', 'cash_voucher_items.category_id = master_crop_category.crop_category_id', 'left');
        $this->db->join('master_crop', 'cash_voucher_items.crop_id = master_crop.crop_id', 'left');
        $this->db->join('master_activity', 'cash_voucher_items.activity_id = master_activity.activity_id', 'left');
        $this->db->join('master_account_ledger', 'cash_voucher_items.debit_ac_id = master_account_ledger.id', 'left');
        $this->db->join('master_work_location', 'cash_voucher_items.location_id = master_work_location.location_id', 'left');
        $this->db->join('master_region', 'cash_voucher_items.region_id = master_region.region_id', 'left');
        $this->db->join('master_department', 'cash_voucher_items.DepartmentID = master_department.department_id', 'left');
        $this->db->where(['scan_file.Group_Id' => 16,  'scan_file.at_finance' => 'Y','scan_file.File_Approved' => 'Y', 'scan_file.Is_Finance_Rejected' => 'N', 'punchfile.finance_punch_date IS NOT NULL' => null, 'punchfile.Finance_Punch_By IS NOT NULL' => null, ]);
        if (!empty($doctype)) {
            $this->db->where('scan_file.DocType_Id', $doctype);
        }
        if (!empty($search)) {
            $this->db->like('punchfile.document_number', $search);
        }
        if (!empty($from_date)) {
            $this->db->where('punchfile.finance_punch_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('punchfile.finance_punch_date <=', $to_date);
        }
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_total_rows($doctype = null, $search = null, $from_date = null, $to_date = null) {
        $this->db->from('cash_voucher_items');
        $this->db->join('scan_file', 'scan_file.Scan_Id = cash_voucher_items.Scan_Id', 'left');
        $this->db->join('punchfile', 'scan_file.Scan_Id = punchfile.Scan_Id', 'left');
        $this->db->where(['scan_file.Group_Id' => 16, 'scan_file.at_finance' => 'Y', 'scan_file.Is_Finance_Rejected' => 'N', 'punchfile.finance_punch_date IS NOT NULL' => null, 'punchfile.Finance_Punch_By IS NOT NULL' => null, ]);
        if (!empty($doctype)) {
            $this->db->where('scan_file.DocType_Id', $doctype);
        }
        if (!empty($search)) {
            $this->db->like('punchfile.document_number', $search);
        }
        if (!empty($from_date)) {
            $this->db->where('punchfile.finance_punch_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('punchfile.finance_punch_date <=', $to_date);
        }
        return $this->db->count_all_results();
    }
	public function get_export_data($doctype, $from_date, $to_date) {
		$this->db->select(['punchfile.document_number AS DocNo', 'punchfile.finance_punch_date AS Date', "'' AS EmptyField", 'punchfile.account AS CashBankAC', 'master_business_entity.business_entity_code AS BusinessEntity', 'punchfile.narration AS sNarration', 'punchfile.favouring AS Favouring', 'punchfile.TDS_JV_no AS TDSJVNo', 'master_cost_center.cost_center_name AS CostCenter', 'master_work_location.location_name AS Location', 'master_crop.crop_code AS Crop', 'master_activity.activity_code AS Activity', 'master_state.state_code AS State', 'master_crop_category.crop_category_code AS Category', 'master_region.region_code AS Region', 'master_department.department_code AS Department', 'cash_voucher_items.payment_method AS PMTCategory', 'master_business_unit.business_unit_code AS BusinessUnit', 'master_account_ledger.account_code AS Account', 'cash_voucher_items.Total_Amount AS TotalAmount', 'cash_voucher_items.ReferenceNo AS Reference', 'cash_voucher_items.Remark AS sRemarks', 'punchfile.TDS_section AS TDS']);
        $this->db->from('cash_voucher_items');
        $this->db->join('scan_file', 'scan_file.Scan_Id = cash_voucher_items.Scan_Id', 'left');
        $this->db->join('punchfile', 'scan_file.Scan_Id = punchfile.Scan_Id', 'left');
        $this->db->join('master_business_entity', 'punchfile.business_entity_id = master_business_entity.business_entity_id', 'left');
        $this->db->join('master_cost_center', 'cash_voucher_items.cost_center_id = master_cost_center.cost_center_id', 'left');
        $this->db->join('master_business_unit', 'cash_voucher_items.business_unit_id = master_business_unit.business_unit_id', 'left');
        $this->db->join('master_state', 'cash_voucher_items.state_id = master_state.state_id', 'left');
        $this->db->join('master_crop_category', 'cash_voucher_items.category_id = master_crop_category.crop_category_id', 'left');
        $this->db->join('master_crop', 'cash_voucher_items.crop_id = master_crop.crop_id', 'left');
        $this->db->join('master_activity', 'cash_voucher_items.activity_id = master_activity.activity_id', 'left');
        $this->db->join('master_account_ledger', 'cash_voucher_items.debit_ac_id = master_account_ledger.id', 'left');
        $this->db->join('master_work_location', 'cash_voucher_items.location_id = master_work_location.location_id', 'left');
        $this->db->join('master_region', 'cash_voucher_items.region_id = master_region.region_id', 'left');
        $this->db->join('master_department', 'cash_voucher_items.DepartmentID = master_department.department_id', 'left');
        $this->db->where(['scan_file.Group_Id' => 16, 'scan_file.at_finance' => 'Y','scan_file.File_Approved' => 'Y', 'scan_file.Is_Finance_Rejected' => 'N', 'punchfile.finance_punch_date IS NOT NULL' => null, 'punchfile.Finance_Punch_By IS NOT NULL' => null, ]);
        if (!empty($doctype)) {
            $this->db->where('scan_file.DocType_Id', $doctype);
        }
        if (!empty($from_date)) {
            $this->db->where('punchfile.finance_punch_date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('punchfile.finance_punch_date <=', $to_date);
        }
  
        $query = $this->db->get();
        return $query->result_array();
	}
}
