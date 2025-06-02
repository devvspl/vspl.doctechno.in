<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Punch_model extends MY_Model
{
    protected $year_id;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->year_id = $this->session->userdata('year_id');
    }
    public function get_file_for_punch()
    {
        $group_id = $this->session->userdata('group_id');
        $conditions = [
            'extract_status' => 'Y',
            'is_file_punched' => 'N',
            'bill_approval_status' => 'Y',
            'is_temp_scan_rejected' => 'N',
            'is_deleted' => 'N'
        ];

        $this->db->select('*')
            ->from("y{$this->year_id}_scan_file")
            ->where_in('group_id', [$group_id])
            ->where($conditions)
            ->order_by('scan_id', 'desc');

        $query = $this->db->get();
        return $query->result_array();
    }

    function vspl_get_file_for_punch()
    {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*')->from("y{$this->year_id}_scan_file")->where('is_file_punched', 'Y')->where('is_scan_resend', 'N')->where_in('group_id', $group_id)->where('is_final_submitted', 'Y')->where('finance_punch_action_status', 'N')->where('is_rejected', 'N')->where('finance_punch_action_status', 'N')->where("((location_id IS NOT NULL AND bill_approval_status = 'Y')  OR location_id IS NULL)")->order_by('scan_id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_my_permissioned_doctype_list()
    {
        $user_id = $this->session->userdata('user_id');
        if ($_SESSION['role'] == 'super_admin' || $_SESSION['role'] == 'admin') {
            $this->db->from('master_doctype');
            $this->db->where('Status', 'A');
            $query = $this->db->get();
            return $query->result_array();
        } else {
            $query = $this->db->select('d.*')->from('`permission` p')->join('master_doctype d', 'd.alias = p.permission_name')->join('user_permission up', 'up.permission_id = p.permission_id')->where(['p.category' => 'Doc Type', 'up.user_id' => $user_id])->order_by('d.file_type', 'asc')->get();
            return $query->result_array();
        }
    }
    function get_my_punched_file()
    {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from("y{$this->year_id}_scan_file")->where(['is_file_punched' => 'Y', 'punched_by' => $user_id, 'punched_date' => date('Y-m-d')])->order_by('scan_id', 'desc')->get();
        return $query->result_array();
    }
    function search_punched_file($from_date, $to_date)
    {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from("y{$this->year_id}_scan_file")->where(['is_file_punched' => 'Y', 'punched_by' => $user_id, 'punched_date >=' => $from_date, 'punched_date <=' => $to_date])->order_by('scan_id', 'desc')->get();
        return $query->result_array();
    }
    function get_my_punched_file_all()
    {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from("y{$this->year_id}_scan_file")->where(['is_file_punched' => 'Y', 'punched_by' => $user_id])->order_by('scan_id', 'desc')->get();
        return $query->result_array();
    }
    function get_my_saved_file()
    {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from("y{$this->year_id}_scan_file")->where(['is_file_punched' => 'N', 'Partial_Punch' => 'Y', 'punched_by' => $user_id])->order_by('scan_id', 'desc')->get();
        return $query->result_array();
    }
    function get_rejected_punch_list()
    {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from("y{$this->year_id}_scan_file")->where(['punched_by' => $user_id, 'is_rejected' => 'Y'])->order_by('scan_id', 'desc')->get();
        return $query->result_array();
    }
    function get_finance_rejected_punch_list()
    {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from("y{$this->year_id}_scan_file")->where(['punched_by' => $user_id, 'finance_punch_action_status' => 'Y'])->order_by('scan_id', 'desc')->get();
        return $query->result_array();
    }
    function get_finance_rejected_punch_list_1()
    {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from("y{$this->year_id}_scan_file")->where(['finance_punched_by' => $user_id, 'finance_punch_action_status' => 'R'])->order_by('scan_id', 'desc')->get();
        return $query->result_array();
    }
    function get_total_punched_by_me()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('punched_by', $user_id);
        $this->db->where('is_file_punched', 'Y');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function get_total_approved()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('punched_by', $user_id);
        $this->db->where('is_file_approved', 'Y');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function pending_for_punch()
    {
        $group_id = $this->session->userdata('group_id');
        $this->db->select('*')->from("y{$this->year_id}_scan_file")->where_in('group_id', [$group_id])->where('extract_status', 'Y')->where('is_file_punched', 'N')->where('is_temp_scan_rejected', 'N')->where('is_deleted', 'N')->order_by('scan_id', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }
    function rejected_punch()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('punched_by', $user_id);
        $this->db->where('is_rejected', 'Y');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function finance_rejected_punch()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('punched_by', $user_id);
        $this->db->where('finance_punch_action_status', 'Y');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function pending_for_approval_punch_by_me()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('punched_by', $user_id);
        $this->db->where('is_file_approved', 'N');
        $this->db->where('is_rejected', 'N');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function get_approved_file()
    {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from("y{$this->year_id}_scan_file")->where(['is_file_punched' => 'Y', 'punched_by' => $user_id, 'is_file_approved' => 'Y', 'is_entry_confirmed' => 'N'])->order_by('scan_id', 'desc')->get();
        return $query->result_array();
    }
    function get_files_for_tally_confirmation()
    {
        $user_id = $this->session->userdata('user_id');
        $query = $this->db->select('*')->from("y{$this->year_id}_scan_file")->where(['is_file_punched' => 'Y', 'punched_by' => $user_id, 'is_file_approved' => 'N', 'is_entry_confirmed' => 'N'])->order_by('scan_id', 'desc')->get();
        return $query->result_array();
    }
    function vspl_get_total_punched_by_me()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('finance_punched_by', $user_id);
        $this->db->where('is_file_punched', 'Y');
        $this->db->where('finance_punch_action_status', 'Y');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function vspl_get_total_approved()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('finance_punched_by', $user_id);
        $this->db->where('is_file_approved', 'Y');
        $this->db->where('finance_punch_action_status', 'Y');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function vspl_pending_for_punch()
    {
        $group_id = $this->session->userdata('group_id');
        $this->db->where('is_final_submitted', 'Y');
        $this->db->where('is_file_punched', 'Y');
        $this->db->where('is_rejected', 'N');
        $this->db->where('is_scan_resend', 'N');
        $this->db->where('is_deleted', 'N');
        $this->db->where("((location_id IS NOT NULL AND bill_approval_status = 'Y')  OR location_id IS NULL)");
        $this->db->where('group_id', $group_id);
        $this->db->where('finance_punch_action_status', 'N');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function vspl_rejected_punch()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('finance_punched_by', $user_id);
        $this->db->where('is_rejected', 'Y');
        $this->db->where('finance_punch_action_status', 'Y');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function vspl_finance_rejected_punch()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('finance_punched_by', $user_id);
        $this->db->where('finance_punch_action_status', 'Y');
        $this->db->where('finance_punch_action_status', 'R');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    function vspl_pending_for_approval_punch_by_me()
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('finance_punched_by', $user_id);
        $this->db->where('is_file_approved', 'N');
        $this->db->where('is_rejected', 'N');
        $this->db->where('finance_punch_action_status', 'Y');
        $query = $this->db->get("y{$this->year_id}_scan_file");
        return $query->num_rows();
    }
    public function get_records($limit, $offset, $doctype = null, $search = null, $from_date = null, $to_date = null)
    {
        $this->db->select(['punchfile.document_number AS DocNo', 'punchfile.finance_punch_date AS Date', "'' AS EmptyField", 'punchfile.account AS CashBankAC', 'master_business_entity.business_entity_code AS BusinessEntity', 'punchfile.narration AS sNarration', 'punchfile.favouring AS Favouring', 'punchfile.TDS_JV_no AS TDSJVNo', 'master_cost_center.cost_center_name AS CostCenter', 'master_work_location.location_name AS Location', 'master_crop.crop_code AS Crop', 'core_activity.activity_code AS Activity', 'master_state.state_code AS State', 'master_crop_category.crop_category_code AS Category', 'master_region.region_code AS Region', 'master_department.department_code AS Department', 'cash_voucher_items.payment_term AS PMTCategory', 'master_business_unit.business_unit_code AS BusinessUnit', 'master_account_ledger.account_code AS Account', 'cash_voucher_items.Total_Amount AS TotalAmount', 'cash_voucher_items.ReferenceNo AS Reference', 'cash_voucher_items.Remark AS sRemarks', 'punchfile.TDS_section AS TDS',]);
        $this->db->from('cash_voucher_items');
        $this->db->join("y{$this->year_id}_scan_file", 'y{$this->year_id}_scan_file.scan_id = cash_voucher_items.scan_id', 'left');
        $this->db->join('punchfile', 'y{$this->year_id}_scan_file.scan_id = punchfile.scan_id', 'left');
        $this->db->join('master_business_entity', 'punchfile.business_entity_id = master_business_entity.business_entity_id', 'left');
        $this->db->join('master_cost_center', 'cash_voucher_items.cost_center_id = master_cost_center.cost_center_id', 'left');
        $this->db->join('master_business_unit', 'cash_voucher_items.business_unit_id = master_business_unit.business_unit_id', 'left');
        $this->db->join('master_state', 'cash_voucher_items.state_id = master_state.state_id', 'left');
        $this->db->join('master_crop_category', 'cash_voucher_items.category_id = master_crop_category.crop_category_id', 'left');
        $this->db->join('master_crop', 'cash_voucher_items.crop_id = master_crop.crop_id', 'left');
        $this->db->join('core_activity', 'cash_voucher_items.activity_id = core_activity.activity_id', 'left');
        $this->db->join('master_account_ledger', 'cash_voucher_items.debit_ac_id = master_account_ledger.id', 'left');
        $this->db->join('master_work_location', 'cash_voucher_items.location_id = master_work_location.location_id', 'left');
        $this->db->join('master_region', 'cash_voucher_items.region_id = master_region.region_id', 'left');
        $this->db->join('master_department', 'cash_voucher_items.DepartmentID = master_department.department_id', 'left');
        $this->db->where(['y{$this->year_id}_scan_file.group_id' => 16, 'y{$this->year_id}_scan_file.finance_punch' => 'Y', 'y{$this->year_id}_scan_file.is_file_approved' => 'Y', 'y{$this->year_id}_scan_file.finance_punch_status' => 'N', 'punchfile.finance_punch_date IS NOT NULL' => null, 'punchfile.finance_punched_by IS NOT NULL' => null,]);
        if (!empty($doctype)) {
            $this->db->where('y{$this->year_id}_scan_file.doc_type_id', $doctype);
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
    public function get_total_rows($doctype = null, $search = null, $from_date = null, $to_date = null)
    {
        $this->db->from('cash_voucher_items');
        $this->db->join("y{$this->year_id}_scan_file", 'y{$this->year_id}_scan_file.scan_id = cash_voucher_items.scan_id', 'left');
        $this->db->join('punchfile', 'y{$this->year_id}_scan_file.scan_id = punchfile.scan_id', 'left');
        $this->db->where(['y{$this->year_id}_scan_file.group_id' => 16, 'y{$this->year_id}_scan_file.finance_punch' => 'Y', 'y{$this->year_id}_scan_file.finance_punch_status' => 'N', 'punchfile.finance_punch_date IS NOT NULL' => null, 'punchfile.finance_punched_by IS NOT NULL' => null,]);
        if (!empty($doctype)) {
            $this->db->where('y{$this->year_id}_scan_file.doc_type_id', $doctype);
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
    public function get_export_data($doctype, $from_date, $to_date)
    {
        $this->db->select(['punchfile.document_number AS DocNo', 'punchfile.finance_punch_date AS Date', "'' AS EmptyField", 'punchfile.account AS CashBankAC', 'master_business_entity.business_entity_code AS BusinessEntity', 'punchfile.narration AS sNarration', 'punchfile.favouring AS Favouring', 'punchfile.TDS_JV_no AS TDSJVNo', 'master_cost_center.cost_center_name AS CostCenter', 'master_work_location.location_name AS Location', 'master_crop.crop_code AS Crop', 'core_activity.activity_code AS Activity', 'master_state.state_code AS State', 'master_crop_category.crop_category_code AS Category', 'master_region.region_code AS Region', 'master_department.department_code AS Department', 'cash_voucher_items.payment_term AS PMTCategory', 'master_business_unit.business_unit_code AS BusinessUnit', 'master_account_ledger.account_code AS Account', 'cash_voucher_items.Total_Amount AS TotalAmount', 'cash_voucher_items.ReferenceNo AS Reference', 'cash_voucher_items.Remark AS sRemarks', 'punchfile.TDS_section AS TDS',]);
        $this->db->from('cash_voucher_items');
        $this->db->join("y{$this->year_id}_scan_file", 'y{$this->year_id}_scan_file.scan_id = cash_voucher_items.scan_id', 'left');
        $this->db->join('punchfile', 'y{$this->year_id}_scan_file.scan_id = punchfile.scan_id', 'left');
        $this->db->join('master_business_entity', 'punchfile.business_entity_id = master_business_entity.business_entity_id', 'left');
        $this->db->join('master_cost_center', 'cash_voucher_items.cost_center_id = master_cost_center.cost_center_id', 'left');
        $this->db->join('master_business_unit', 'cash_voucher_items.business_unit_id = master_business_unit.business_unit_id', 'left');
        $this->db->join('master_state', 'cash_voucher_items.state_id = master_state.state_id', 'left');
        $this->db->join('master_crop_category', 'cash_voucher_items.category_id = master_crop_category.crop_category_id', 'left');
        $this->db->join('master_crop', 'cash_voucher_items.crop_id = master_crop.crop_id', 'left');
        $this->db->join('core_activity', 'cash_voucher_items.activity_id = core_activity.activity_id', 'left');
        $this->db->join('master_account_ledger', 'cash_voucher_items.debit_ac_id = master_account_ledger.id', 'left');
        $this->db->join('master_work_location', 'cash_voucher_items.location_id = master_work_location.location_id', 'left');
        $this->db->join('master_region', 'cash_voucher_items.region_id = master_region.region_id', 'left');
        $this->db->join('master_department', 'cash_voucher_items.DepartmentID = master_department.department_id', 'left');
        $this->db->where(['y{$this->year_id}_scan_file.group_id' => 16, 'y{$this->year_id}_scan_file.finance_punch' => 'Y', 'y{$this->year_id}_scan_file.is_file_approved' => 'Y', 'y{$this->year_id}_scan_file.finance_punch_status' => 'N', 'punchfile.finance_punch_date IS NOT NULL' => null, 'punchfile.finance_punched_by IS NOT NULL' => null,]);
        if (!empty($doctype)) {
            $this->db->where('y{$this->year_id}_scan_file.doc_type_id', $doctype);
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
    public function get_punched_files($user_id, $from_date = null, $to_date = null, $show_all = false)
    {
        $this->db->select('*')->from("y{$this->year_id}_scan_file")->where('is_file_punched', 'Y')->where('punched_by', $user_id);
        if (!$show_all) {
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('DATE(punched_date) >=', $from_date)->where('DATE(punched_date) <=', $to_date);
            } else {
                $this->db->where('DATE(punched_date)', date('Y-m-d'));
            }
        }
        $this->db->order_by('scan_id', 'desc');
        return $this->db->get()->result_array();
    }
    public function get_finance_punched_files($user_id, $from_date = null, $to_date = null, $show_all = false)
    {
        $this->db->select('*')->from("y{$this->year_id}_scan_file")->where('finance_punch_action_status', 'Y')->where('finance_punched_by', $user_id);
        if (!$show_all) {
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where('DATE(finance_punch_date) >=', $from_date)->where('DATE(finance_punch_date) <=', $to_date);
            } else {
                $this->db->where('DATE(finance_punch_date)', date('Y-m-d'));
            }
        }
        $this->db->order_by('scan_id', 'desc');
        return $this->db->get()->result_array();
    }
    public function get_finance_bill_approval_files($finance_punch_status)
    {
        $this->db->select('*')->from("y{$this->year_id}_scan_file")->where('finance_punch_action_status', 'Y')->where('finance_punch_action_status', $finance_punch_status);
        $this->db->order_by('scan_id', 'desc');
        return $this->db->get()->result_array();
    }
    public function checkUserPermission($user_id)
    {
        $query = $this->db->select('permission_id')->from('user_permission')->where('user_id', $user_id)->where('permission_id', 4)->get();
        if ($query->num_rows() > 0) {
            return 'Y';
        } else {
            return 'N';
        }
    }
    public function getPunchDetail($scan_id, $DocTypeId)
    {
        $this->load->database();
        $scan_id = $this->db->escape_str($scan_id);
        $DocTypeId = $this->db->escape_str($DocTypeId);
        $punchdata_table = "y{$this->year_id}_punchdata_{$DocTypeId}";
        $punchdata_details_table = "y{$this->year_id}_punchdata_{$DocTypeId}_details";
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        // Fetch punchdata with buyer and vendor details
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*, bf.firm_name AS buyer_name, bf.address AS buyer_address, vf.firm_name AS vendor_name, vf.address AS vendor_address')
                ->from($punchdata_table . ' p')
                ->join('master_firm bf', 'bf.firm_id = p.buyer', 'left')
                ->join('master_firm vf', 'vf.firm_id = p.vendor', 'left')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->row_array() ?: [];
        }

        // Fetch punchdata_details with unit name
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*, mu.unit_name')
                ->from($punchdata_details_table . ' pd')
                ->join('master_unit mu', 'mu.unit_id = pd.unit', 'left')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->result_array() ?: [];
        }

        return $result;
    }
}
