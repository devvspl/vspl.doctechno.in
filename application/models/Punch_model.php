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
        $conditions = ['extract_status' => 'Y', 'is_file_punched' => 'N', 'bill_approval_status' => 'Y', 'is_temp_scan_rejected' => 'N', 'is_deleted' => 'N'];
        $this->db->select('*')->from("y{$this->year_id}_scan_file")->where_in('group_id', [$group_id])->where($conditions)->order_by('scan_id', 'desc');
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
        $result = ['punchdata' => [], 'punchdata_details' => []];
        $docType = [23 => ["data_method" => "getInvoiceData"], 1 => ["data_method" => "getTwoFourWheelerData",], 4 => ["data_method" => "getBankStatementData",], 5 => ["data_method" => "getBoardingPassData",], 6 => ["data_method" => "getCashDepositWithdrawalsData",], 8 => ["data_method" => "getCertificateData",], 10 => ["data_method" => "getCompanyRecordData",], 11 => ["data_method" => "getConfirmationAccountData",], 18 => ["data_method" => "getIdAddressProofData",], 19 => ["data_method" => "getImportExportPaperData",], 30 => ["data_method" => "getMediclaimHistoryData",], 31 => ["data_method" => "getMiscellaneousData",], 32 => ["data_method" => "getPfEsicData"], 35 => ["data_method" => "getPropertyRecordData",], 36 => ["data_method" => "getRetingCredentialData",], 37 => ["data_method" => "getRegistrationCertificateData",], 41 => ["data_method" => "getTaxCreditDocumentData",], 45 => ["data_method" => "getVehicleRegistrationPaperData",], 44 => ["data_method" => "getVehicleMaintenanceData",], 43 => ["data_method" => "getVehicleFuelData",], 42 => ["data_method" => "getTelephoneBillData",], 40 => ["data_method" => "getSubsidyData"], 39 => ["data_method" => "getRtgsNeftData"], 38 => ["data_method" => "getRstOfdData"], 34 => ["data_method" => "getPostageCourierData",], 33 => ["data_method" => "getPhoneFaxData"], 29 => ["data_method" => "getMealsData"], 28 => ["data_method" => "getLodgingData"], 27 => ["data_method" => "getLocalConveyanceData",], 26 => ["data_method" => "getLeaseRentData"], 25 => ["data_method" => "getJeepCampaignData",], 24 => ["data_method" => "getItReturnData"], 22 => ["data_method" => "getInsurancePolicyData",], 21 => ["data_method" => "getInsuranceDocumentData",], 20 => ["data_method" => "getIncomeTaxTdsData",], 17 => ["data_method" => "getHiredVehicleData",], 16 => ["data_method" => "getChallanData"], 15 => ["data_method" => "getFixedDepositReceiptData",], 14 => ["data_method" => "getFdFvData"], 13 => ["data_method" => "getElectricityBillData",], 12 => ["data_method" => "getDealerMeetingData",], 9 => ["data_method" => "getChequeData"], 7 => ["data_method" => "getCashVoucherData",], 46 => ["data_method" => "getGstChallanData",], 47 => ["data_method" => "getLabourPaymentData",], 48 => ["data_method" => "getCashReceiptData",], 49 => ["data_method" => "getFixedAssetData",], 50 => ["data_method" => "getMachineOperationData",], 51 => ["data_method" => "getAirData"], 52 => ["data_method" => "getRailData"], 53 => ["data_method" => "getBusData"], 54 => ["data_method" => "getSaleBillData"], 55 => ["data_method" => "getTicketCancellationData",], 56 => ["data_method" => "getCreditNoteData",],];
        if (isset($docType[$DocTypeId]) && method_exists($this, $docType[$DocTypeId]['data_method'])) {
            $result = $this->{$docType[$DocTypeId]['data_method']}($scan_id, $punchdata_table, $punchdata_details_table);
        } else {
            if ($this->db->table_exists($punchdata_table)) {
                $this->db->select('p.*')->from($punchdata_table . ' p')->where('p.scan_id', $scan_id);
                $query = $this->db->get();
                $result['punchdata'] = $query->row_array() ?: [];
            }
            if ($this->db->table_exists($punchdata_details_table)) {
                $this->db->select('pd.*')->from($punchdata_details_table . ' pd')->where('pd.scan_id', $scan_id);
                $query = $this->db->get();
                $result['punchdata_details'] = $query->result_array() ?: [];
            }
        }
        return $result;
    }
    private function getInvoiceData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*, b.firm_name, b.address, v.firm_name, v.address')
                ->from($punchdata_table . ' p')
                ->join('master_firm b', 'p.buyer = b.firm_id', 'left')
                ->join('master_firm v', 'p.vendor = v.firm_id', 'left')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->row_array() ?: [];
        }

        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*, u.unit_name')
                ->from($punchdata_details_table . ' pd')
                ->join('master_unit u', 'pd.unit = u.unit_id', 'left')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->result_array() ?: [];
        }

        return $result;
    }
    private function getTwoFourWheelerData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        // Fetch punchdata
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*, e.emp_name')
                ->from($punchdata_table . ' p')
                ->join('master_employee e', 'p.employee_name = e.id', 'left')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->num_rows() > 0 ? $query->row_array() : [];
        }

        // Fetch punchdata_details
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*')
                ->from($punchdata_details_table . ' pd')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->num_rows() > 0 ? $query->result_array() : [];
        }

        return $result;
    }
    private function getVehicleFuelData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        // Fetch punchdata with joins to master_firm for vendor_name and billing_to
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*, v.firm_name AS vendor_name_text, b.firm_name AS billing_to_text')
                ->from($punchdata_table . ' p')
                ->join('master_firm v', 'p.vendor_name = v.firm_id AND v.firm_type = "Vendor" AND v.is_deleted = "N"', 'left')
                ->join('master_firm b', 'p.billing_to = b.firm_id AND b.firm_type = "Company" AND b.is_deleted = "N"', 'left')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->row_array() ?: [];
        }

        // Fetch punchdata_details
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*')
                ->from($punchdata_details_table . ' pd')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->result_array() ?: [];
        }

        return $result;
    }
    private function getTelephoneBillData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        // Fetch punchdata
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*')
                ->from($punchdata_table . ' p')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->row_array() ?: [];
        }

        // Fetch punchdata_details
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*')
                ->from($punchdata_details_table . ' pd')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->result_array() ?: [];
        }

        return $result;
    }
    private function getCashDepositWithdrawalsData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        // Fetch punchdata
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*')
                ->from($punchdata_table . ' p')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->row_array() ?: [];
        }

        // Fetch punchdata_details
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*')
                ->from($punchdata_details_table . ' pd')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->result_array() ?: [];
        }

        return $result;
    }
    private function getCashVoucherData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        // Fetch punchdata with join to master_firm for company_name
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*, b.firm_name AS company_name_text')
                ->from($punchdata_table . ' p')
                ->join('master_firm b', 'p.company_name = b.firm_id AND b.firm_type = "Company" AND b.is_deleted = "N"', 'left')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->row_array() ?: [];
        }

        // Fetch punchdata_details
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*')
                ->from($punchdata_details_table . ' pd')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->result_array() ?: [];
        }

        return $result;
    }


    private function getElectricityBillData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        // Fetch punchdata with join to master_firm for company_name
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*')
                ->from($punchdata_table . ' p')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->row_array() ?: [];
        }

        // Fetch punchdata_details
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*')
                ->from($punchdata_details_table . ' pd')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->result_array() ?: [];
        }

        return $result;
    }

    private function getHiredVehicleData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        // Fetch punchdata with joins to master_firm for agency_name and billing_name
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*, v.firm_name AS agency_name_text, b.firm_name AS billing_name_text')
                ->from($punchdata_table . ' p')
                ->join('master_firm v', 'p.agency_name = v.firm_id AND v.firm_type = "Vendor" AND v.is_deleted = "N"', 'left')
                ->join('master_firm b', 'p.billing_name = b.firm_id AND b.firm_type = "Company" AND b.is_deleted = "N"', 'left')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->row_array() ?: [];
        }

        // Fetch punchdata_details
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*')
                ->from($punchdata_details_table . ' pd')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->result_array() ?: [];
        }

        return $result;
    }

    private function getIncomeTaxTdsData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        // Fetch punchdata with join to master_firm for company
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*, b.firm_name AS company_text, y.label AS assessment_year_text')
                ->from($punchdata_table . ' p')
                ->join('master_firm b', 'p.company = b.firm_id AND b.firm_type = "Company" AND b.is_deleted = "N"', 'left')
                ->join('financial_years y', 'p.assessment_year = y.id', 'left')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->row_array() ?: [];
        }

        // Fetch punchdata_details
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*')
                ->from($punchdata_details_table . ' pd')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->result_array() ?: [];
        }

        return $result;
    }
    private function getInsurancePolicyData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        // Fetch punchdata
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*')
                ->from($punchdata_table . ' p')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->row_array() ?: [];
        }

        // Fetch punchdata_details
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*')
                ->from($punchdata_details_table . ' pd')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->result_array() ?: [];
        }

        return $result;
    }

    private function getLocalConveyanceData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => []
        ];

        // Fetch punchdata
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.*, p.location As location_text, e.emp_name')
                ->from($punchdata_table . ' p')
                ->join('master_employee e', 'p.employee_name = e.id', 'left')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->num_rows() > 0 ? $query->row_array() : [];
        }

        // Fetch punchdata_details
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*')
                ->from($punchdata_details_table . ' pd')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->num_rows() > 0 ? $query->result_array() : [];
        }

        return $result;
    }


    private function getLodgingData($scan_id, $punchdata_table, $punchdata_details_table)
    {
        $result = [
            'punchdata' => [],
            'punchdata_details' => [],
            'emp_detail' => []
        ];

        // Fetch punchdata
        if ($this->db->table_exists($punchdata_table)) {
            $this->db->select('p.bill_no, p.bill_date, p.billing_name, p.billing_address, p.hotel_name, p.hotel_address, p.billing_instruction, p.booking_id, p.check_in, p.check_out, p.duration_of_stay, p.number_of_rooms, p.room_type, p.meal_plan, p.rate, p.amount, p.other_charges, p.discount, p.gst, p.grand_total, p.location, p.remark_comment')
                ->from($punchdata_table . ' p')
                ->join('master_firm b', 'p.billing_name = b.firm_id AND b.firm_type = "Company" AND b.is_deleted = "N"', 'left')
                ->where('p.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata'] = $query->num_rows() > 0 ? $query->row_array() : [];
        }

        // Fetch punchdata_details (if applicable)
        if ($this->db->table_exists($punchdata_details_table)) {
            $this->db->select('pd.*')
                ->from($punchdata_details_table . ' pd')
                ->where('pd.scan_id', $scan_id);
            $query = $this->db->get();
            $result['punchdata_details'] = $query->num_rows() > 0 ? $query->result_array() : [];
        }

        // Fetch employee details
        if ($this->db->table_exists('lodging_employee')) {
            $this->db->select('emp_name, emp_code')
                ->from('lodging_employee')
                ->where('scan_id', $scan_id);
            $query = $this->db->get();
            $result['emp_detail'] = $query->num_rows() > 0 ? $query->result() : [];
        }

        return $result;
    }



}
