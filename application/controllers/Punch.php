<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Punch extends CI_Controller
{
    protected $year_id;
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model("Punch_model");
        $this->load->model("BaseModel");
        $this->year_id = $this->session->userdata('year_id') ?? ($this->db->select('id')->from('financial_years')->where('is_current', 1)->get()->row()->id ?? null);
    }
    private function logged_in()
    {
        if (!$this->session->userdata("authenticated")) {
            redirect("/");
        }
    }
    public function index()
    {
        $this->session->set_userdata("top_menu", "punch_master");
        $this->session->set_userdata("sub_menu", "punch");
        $this->data["main"] = "punch/punchfile";
        $this->data["scanfile_list"] = $this->Punch_model->get_file_for_punch();
        $this->data["my_doctype_list"] = $this->Punch_model->get_my_permissioned_doctype_list();
        $this->load->view("layout/template", $this->data);
    }
    public function get_additional_information_by_scan_id($scan_id)
    {
        $this->db->select("ai.*, be.business_entity_name, td.section");
        $this->db->from("tbl_additional_information ai");
        $this->db->join("master_business_entity be", "be.business_entity_id = ai.business_entity_id", "left");
        $this->db->join("master_tds_sections td", "td.id = ai.tds_section_id", "left");
        $this->db->where("ai.scan_id", $scan_id);
        $mainRecord = $this->db->get()->row_array();
        if (empty($mainRecord)) {
            return null;
        }
        $this->db->select("aii.*, cc.name, d.department_name, bu.business_unit_name, r.region_name, s.state_name, l.city_village_name as location_name, c.category_name, cr.crop_name, a.activity_name, da.account_name as debit_account, pm.payment_term_name as payment_term");
        $this->db->from("tbl_additional_information_items aii");
        $this->db->join("master_cost_center cc", "cc.id = aii.cost_center_id", "left");
        $this->db->join("core_department d", "d.api_id = aii.department_id", "left");
        $this->db->join("core_business_unit bu", "bu.api_id = aii.business_unit_id", "left");
        $this->db->join("core_region r", "r.api_id = aii.region_id", "left");
        $this->db->join("core_state s", "s.api_id = aii.state_id", "left");
        $this->db->join("core_city_village l", "l.api_id = aii.location_id", "left");
        $this->db->join("master_category c", "c.category_id = aii.category_id", "left");
        $this->db->join("core_crop cr", "cr.api_id = aii.crop_id", "left");
        $this->db->join("core_activity a", "a.api_id = aii.activity_id", "left");
        $this->db->join("master_account_ledger da", "da.id = aii.debit_account_id", "left");
        $this->db->join("payment_term_master pm", "pm.id = aii.payment_term_id", "left");
        $this->db->where("aii.scan_id", $scan_id);
        $items = $this->db->get()->result_array();
        $mainRecord["items"] = $items;
        return $mainRecord;
    }
    public function file_entry($scan_id = null, $doc_type_id = null)
    {
        $this->session->set_userdata("top_menu", "punch_master");
        $this->session->set_userdata("sub_menu", "punch");
        $punch_table = "y" . $this->year_id . "_punchdata_" . $doc_type_id;
        $data = $this->getCommonPunchData($scan_id, $doc_type_id, $punch_table);
        $data["user_permission"] = $this->Punch_model->checkUserPermission($this->session->userdata("user_id"));
        $mainRecord = $this->get_additional_information_by_scan_id($scan_id);
        if ($mainRecord) {
            $data["main_record"] = $mainRecord;
        } else {
            $data["main_record"] = null;
        }
        $data["main"] = "punch/_punch";
        if (!empty($data["doc_config"]) && !empty($data["doc_config"]["data_method"])) {
            $method = $data["doc_config"]["data_method"];
            if (method_exists($this, $method)) {
                $docData = $this->$method($scan_id, $doc_type_id, $punch_table);
                $data = array_merge($data, $docData);
            }
        }
        $this->load->view("layout/template", $data);
    }
    private function getCommonPunchData($scan_id, $doc_type_id, $punch_table)
    {
        $document_name = $this->customlib->getDocumentName($scan_id);
        $doc_type_name = $this->customlib->getDocType($doc_type_id);
        $docTypeViews = [23 => ["view" => "invoice", "data_method" => "getInvoiceData"], 1 => ["view" => "two_four_wheeler", "data_method" => "getTwoFourWheelerData",], 4 => ["view" => "bank_statement", "data_method" => "getBankStatementData",], 5 => ["view" => "boarding_pass", "data_method" => "getBoardingPassData",], 6 => ["view" => "cash_deposit_withdrawals", "data_method" => "getCashDepositWithdrawalsData",], 8 => ["view" => "certificate", "data_method" => "getCertificateData",], 10 => ["view" => "company_record", "data_method" => "getCompanyRecordData",], 11 => ["view" => "confirmation_account", "data_method" => "getConfirmationAccountData",], 18 => ["view" => "id_address_proof", "data_method" => "getIdAddressProofData",], 19 => ["view" => "import_export_paper", "data_method" => "getImportExportPaperData",], 30 => ["view" => "mediclaim_history", "data_method" => "getMediclaimHistoryData",], 31 => ["view" => "miscellaneous", "data_method" => "getMiscellaneousData",], 32 => ["view" => "pf_esic", "data_method" => "getPfEsicData"], 35 => ["view" => "property_record", "data_method" => "getPropertyRecordData",], 36 => ["view" => "reting_credential", "data_method" => "getRetingCredentialData",], 37 => ["view" => "registration_certificate", "data_method" => "getRegistrationCertificateData",], 41 => ["view" => "tax_credit_document", "data_method" => "getTaxCreditDocumentData",], 45 => ["view" => "vehicle_registration_paper", "data_method" => "getVehicleRegistrationPaperData",], 44 => ["view" => "vehicle_maintenance", "data_method" => "getVehicleMaintenanceData",], 43 => ["view" => "vehicle_fule", "data_method" => "getVehicleFuelData",], 42 => ["view" => "telephone_bill", "data_method" => "getTelephoneBillData",], 40 => ["view" => "subsidy", "data_method" => "getSubsidyData"], 39 => ["view" => "rtgs_neft", "data_method" => "getRtgsNeftData"], 38 => ["view" => "rst_ofd", "data_method" => "getRstOfdData"], 34 => ["view" => "postage_courier", "data_method" => "getPostageCourierData",], 33 => ["view" => "phone_fax", "data_method" => "getPhoneFaxData"], 29 => ["view" => "meals", "data_method" => "getMealsData"], 28 => ["view" => "lodging", "data_method" => "getLodgingData"], 27 => ["view" => "local_conveyance", "data_method" => "getLocalConveyanceData",], 26 => ["view" => "lease_rent", "data_method" => "getLeaseRentData"], 25 => ["view" => "jeep_campaign", "data_method" => "getJeepCampaignData",], 24 => ["view" => "it_return", "data_method" => "getItReturnData"], 22 => ["view" => "insurance_policy", "data_method" => "getInsurancePolicyData",], 21 => ["view" => "insurance_document", "data_method" => "getInsuranceDocumentData",], 20 => ["view" => "income_taxt_tds", "data_method" => "getIncomeTaxTdsData",], 17 => ["view" => "hired_vehicle", "data_method" => "getHiredVehicleData",], 16 => ["view" => "challan", "data_method" => "getChallanData"], 15 => ["view" => "fixed_deposit_receipt", "data_method" => "getFixedDepositReceiptData",], 14 => ["view" => "fd_fv", "data_method" => "getFdFvData"], 13 => ["view" => "electricity_bill", "data_method" => "getElectricityBillData",], 12 => ["view" => "dealer_meeting", "data_method" => "getDealerMeetingData",], 9 => ["view" => "cheque", "data_method" => "getChequeData"], 7 => ["view" => "cash_voucher", "data_method" => "getCashVoucherData",], 46 => ["view" => "gst_challan", "data_method" => "getGstChallanData",], 47 => ["view" => "labour_payment", "data_method" => "getLabourPaymentData",], 48 => ["view" => "cash_receipt", "data_method" => "getCashReceiptData",], 49 => ["view" => "fixed_asset", "data_method" => "getFixedAssetData",], 50 => ["view" => "machine_operation", "data_method" => "getMachineOperationData",], 51 => ["view" => "air", "data_method" => "getAirData"], 52 => ["view" => "rail", "data_method" => "getRailData"], 53 => ["view" => "bus", "data_method" => "getBusData"], 54 => ["view" => "sale_bill", "data_method" => "getSaleBillData"], 55 => ["view" => "ticket_cancellation", "data_method" => "getTicketCancellationData",], 56 => ["view" => "credit_note", "data_method" => "getCreditNoteData",],];
        return ["scan_id" => $scan_id, "doc_type_id" => $doc_type_id, $punch_table, "document_name" => $document_name, "doc_type_name" => $doc_type_name, "doc_config" => $docTypeViews[$doc_type_id] ?? null,];
    }
    private function fetchData($tableName, $db)
    {
        return $db->where("status", "A")->where("is_deleted", "N")->get($tableName)->result_array();
    }
    private function getInvoiceData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "firm" => $this->db->get_where("master_firm", ["status" => "A"])->result_array(), "company_list" => $this->customlib->getCompanyList(), "file_list" => $this->customlib->getFileList(), "ledger_list" => $this->customlib->getLedgerList(), "category_list" => $this->customlib->getCategoryList(), "item_list" => $this->customlib->getItemList(), "locationlist" => $this->customlib->getWorkLocationList(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("doctype_id", 23)->where("MONTH(created_at)", date("m"))->where("YEAR(created_at)", date("Y"))->count_all_results($punch_table) + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("created_at")->where("doctype_id", 23)->where("MONTH(created_at)", date("m"))->where("YEAR(created_at)", date("Y"))->order_by("created_at", "DESC")->limit(1)->get($punch_table)->row()->created_at ?? date("Y-m"))) . "/" . str_pad($this->db->where("doctype_id", 23)->where("MONTH(created_at)", date("m"))->where("YEAR(created_at)", date("Y"))->count_all_results($punch_table) + 1, 4, "0", STR_PAD_LEFT),];
    }
    private function getIncomeTaxTdsData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "firm" => $this->db->get_where("master_firm", ["status" => "A"])->result_array(), "company_list" => $this->customlib->getCompanyList(), "fin_year" => $this->customlib->getFinancial_year(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT),];
    }
    private function getCashDepositWithdrawalsData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "firm" => $this->db->get_where("master_firm", ["status" => "A"])->result_array(), "company_list" => $this->customlib->getCompanyList(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT),];
    }
    private function getCashReceiptData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "company_list" => $this->customlib->getCompanyList(), "locationlist" => $this->customlib->getWorkLocationList(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT),];
    }
    private function getCashVoucherData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "company_list" => $this->customlib->getCompanyList(), "locationlist" => $this->customlib->getWorkLocationList(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT),];
    }
    private function getTwoFourWheelerData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "worklocation_list" => $this->customlib->getWorkLocationList(), "employee_list" => $this->customlib->getEmployeeList(),];
    }
    private function getAirData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "locationlist" => $this->customlib->getWorkLocationList(), "employee_list" => $this->customlib->getEmployeeList(),];
    }
    private function getCreditNoteData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "firm" => $this->db->get_where("master_firm", ["status" => "A"])->result_array(), "company_list" => $this->customlib->getCompanyList(), "department_list" => $this->customlib->getDepartmentList(), "file_list" => $this->customlib->getFileList(), "worklocation_list" => $this->customlib->getWorkLocationList(), "ledger_list" => $this->customlib->getLedgerList(), "category_list" => $this->customlib->getCategoryList(), "item_list" => $this->customlib->getItemList(), "locationlist" => $this->customlib->getWorkLocationList(),];
    }
    private function getElectricityBillData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "company_list" => $this->customlib->getCompanyList(), "locationlist" => $this->customlib->getWorkLocationList(),];
    }
    private function getGstChallanData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "company_list" => $this->customlib->getCompanyList(),];
    }
    private function getHiredVehicleData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "company_list" => $this->customlib->getCompanyList(), "vendor_list" => $this->customlib->getVendorList(), "locationlist" => $this->customlib->getWorkLocationList(), "employee_list" => $this->customlib->getEmployeeList(),];
    }
    private function getInsurancePolicyData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "company_list" => $this->customlib->getCompanyList(),];
    }
    private function getLabourPaymentData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT),];
    }
    private function getLocalConveyanceData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "locationlist" => $this->customlib->getWorkLocationList(), "employee_list" => $this->customlib->getEmployeeList(), "months" => ["1" => "January", "2" => "February", "3" => "March", "4" => "April", "5" => "May", "6" => "June", "7" => "July", "8" => "August", "9" => "September", "10" => "October", "11" => "November", "12" => "December",],];
    }
    private function getLodgingData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "company_list" => $this->customlib->getCompanyList(), "vendor_list" => $this->customlib->getVendorList(), "locationlist" => $this->customlib->getWorkLocationList(), "hotel_list" => $this->db->select("hotel_id,hotel_name,address")->get_where("master_hotel", ["status" => "A", "is_deleted" => "N",])->result_array(), "employee_list" => $this->customlib->getEmployeeList(),];
    }
    private function getMachineOperationData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "company_list" => $this->customlib->getCompanyList(), "vendor_list" => $this->customlib->getVendorList(), "worklocation_list" => $this->customlib->getWorkLocationList(), "fin_year" => $this->customlib->getFinancial_year(),];
    }
    private function getMealsData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "company_list" => $this->customlib->getCompanyList(), "locationlist" => $this->customlib->getWorkLocationList(), "employee_list" => $this->customlib->getEmployeeList(),];
    }
    private function getMiscellaneousData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "company_list" => $this->customlib->getCompanyList(), "vendor_list" => $this->db->get_where("master_firm", ["status" => "A"])->result_array(), "worklocation_list" => $this->customlib->getWorkLocationList(),];
    }
    private function getRailData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "employee_list" => $this->customlib->getEmployeeList(), "locationlist" => $this->customlib->getWorkLocationList(),];
    }
    private function getSaleBillData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "firm" => $this->db->get_where("master_firm", ["status" => "A"])->result_array(), "company_list" => $this->customlib->getCompanyList(), "department_list" => $this->customlib->getDepartmentList(), "file_list" => $this->customlib->getFileList(), "worklocation_list" => $this->customlib->getWorkLocationList(), "ledger_list" => $this->customlib->getLedgerList(), "category_list" => $this->customlib->getCategoryList(), "item_list" => $this->customlib->getItemList(), "locationlist" => $this->customlib->getWorkLocationList(),];
    }
    private function getTelephoneBillData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT),];
    }
    private function getTicketCancellationData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "company_list" => $this->customlib->getCompanyList(), "hotel_list" => $this->db->select("hotel_id,hotel_name,address")->get_where("master_hotel", ["status" => "A", "is_deleted" => "N",])->result_array(), "employee_list" => $this->customlib->getEmployeeList(),];
    }
    private function getVehicleFuelData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "company_list" => $this->customlib->getCompanyList(), "vendor_list" => $this->db->get_where("master_firm", ["status" => "A"])->result_array(), "worklocation_list" => $this->customlib->getWorkLocationList(),];
    }
    private function getVehicleMaintenanceData($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "temp_punch_detail" => $this->db->get_where("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id,])->row(), "document_number" => "CASH/" . date("y-m") . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "tdsJvNo" => "TDSCASH/" . date("Y-m", strtotime($this->db->select("Created_Date")->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->order_by("Created_Date", "DESC")->limit(1)->get("punchfile")->row()->Created_Date ?? date("Y-m"))) . "/" . str_pad($this->db->where("DocTypeId", 7)->where("MONTH(Created_Date)", date("m"))->where("YEAR(Created_Date)", date("Y"))->count_all_results("punchfile") + 1, 4, "0", STR_PAD_LEFT), "company_list" => $this->customlib->getCompanyList(), "vendor_list" => $this->db->get_where("master_firm", ["status" => "A"])->result_array(), "worklocation_list" => $this->customlib->getWorkLocationList(),];
    }
    public function my_punched_file($show_all = null)
    {
        $this->session->set_userdata("top_menu", "punch_master");
        $this->session->set_userdata("sub_menu", "my_punched_file");
        $user_id = $this->session->userdata("user_id");
        $from_date = null;
        $to_date = null;
        if ($show_all == null) {
            $this->form_validation->set_rules("from_date", "Punch From Date", "trim|required");
            $this->form_validation->set_rules("to_date", "Punch To Date", "trim|required");
            if ($this->form_validation->run() == true) {
                $from_date = $this->input->post("from_date");
                $to_date = $this->input->post("to_date");
            }
        }
        $punch_file_list = $this->Punch_model->get_punched_files($user_id, $from_date, $to_date, $show_all ? true : false);
        $this->data["my_punched_files"] = $punch_file_list;
        $this->data["main"] = "punch/my_punched_file";
        $this->load->view("layout/template", $this->data);
    }
    public function my_finance_punched_file($show_all = null)
    {
        $this->session->set_userdata("top_menu", "punch_master");
        $this->session->set_userdata("sub_menu", "my_punched_file");
        $user_id = $this->session->userdata("user_id");
        $from_date = null;
        $to_date = null;
        if ($show_all == null) {
            $this->form_validation->set_rules("from_date", "Punch From Date", "trim|required");
            $this->form_validation->set_rules("to_date", "Punch To Date", "trim|required");
            if ($this->form_validation->run() == true) {
                $from_date = $this->input->post("from_date");
                $to_date = $this->input->post("to_date");
            }
        }
        $punch_file_list = $this->Punch_model->get_finance_punched_files($user_id, $from_date, $to_date, $show_all ? true : false);
        $this->data["my_finance_punched_file"] = $punch_file_list;
        $this->data["main"] = "punch/my_finance_punched_file";
        $this->load->view("layout/template", $this->data);
    }
    public function my_finance_bill_approval_file($finance_punch_status)
    {
        $punch_file_list = $this->Punch_model->get_finance_bill_approval_files($finance_punch_status);
        $this->data["my_finance_punched_file"] = $punch_file_list;
        $this->data["main"] = "punch/my_finance_bill_approval_file";
        $this->load->view("layout/template", $this->data);
    }
    public function rejected_punch()
    {
        $this->session->set_userdata("top_menu", "punch_master");
        $this->session->set_userdata("sub_menu", "");
        $this->data["rejected_punch_list"] = $this->Punch_model->get_rejected_punch_list();
        $this->data["main"] = "punch/rejected_punch";
        $this->load->view("layout/template", $this->data);
    }
    public function finance_rejected_punch()
    {
        $this->session->set_userdata("top_menu", "punch_master");
        $this->session->set_userdata("sub_menu", "");
        $this->data["finance_rejected_punch_list"] = $this->Punch_model->get_finance_rejected_punch_list();
        $this->data["main"] = "punch/finance_rejected_punch";
        $this->load->view("layout/template", $this->data);
    }
    public function finance_rejected_punch_1()
    {
        $this->session->set_userdata("top_menu", "punch_master");
        $this->session->set_userdata("sub_menu", "");
        $this->data["finance_rejected_punch_list"] = $this->Punch_model->get_finance_rejected_punch_list_1();
        $this->data["main"] = "punch/finance_rejected_punch_1";
        $this->load->view("layout/template", $this->data);
    }
    public function resend_scan($scan_id)
    {
        $user_id = $this->session->userdata("user_id");
        $Reject_Remark = $this->input->post("Remark");
        $this->db->where("scan_id", $scan_id);
        $result = $this->db->update("y{$this->year_id}_scan_file", ["is_scan_resend" => "Y", "scan_resend_by" => $user_id, "scan_resend_remark" => $Reject_Remark, "scan_resend_date" => date("Y-m-d"),]);
        if ($result) {
            echo json_encode(["status" => "200", "message" => "File Resend Successfully.",]);
        } else {
            echo json_encode(["status" => "400", "message" => "Something went wrong. Please try again.",]);
        }
    }
    public function finance_resend_scan($scan_id)
    {
        $user_id = $this->session->userdata("user_id");
        $Reject_Remark = $this->input->post("Remark");
        $this->db->where("scan_id", $scan_id);
        $result = $this->db->update("y{$this->year_id}_scan_file", ["finance_punch_action_status" => "Y", "Finance_Resend_By" => $user_id, "Finance_Resend_Remark" => $Reject_Remark, "finance_punch_action_status" => "P", "Finance_Resend_Date" => date("Y-m-d"),]);
        if ($result) {
            echo json_encode(["status" => "200", "message" => "File Resend Successfully.",]);
        } else {
            echo json_encode(["status" => "400", "message" => "Something went wrong. Please try again.",]);
        }
    }
    public function changeDocType()
    {
        $scan_id = $this->input->post("scan_id");
        $doc_type_id = $this->input->post("doc_type_id");
        $Doc_Type = $this->customlib->getDocType($doc_type_id);
        $this->db->where("scan_id", $scan_id);
        $query = $this->db->update("y{$this->year_id}_scan_file", ["doc_type_id" => $doc_type_id, "doc_type" => $Doc_Type,]);
        if ($query) {
            echo json_encode(["status" => 200]);
        } else {
            echo json_encode(["status" => 400]);
        }
    }
    public function getSupportFile()
    {
        $scan_id = $this->input->post("scan_id");
        $this->db->select("*");
        $this->db->from("support_file");
        $this->db->where("scan_id", $scan_id);
        $query = $this->db->get();
        $result = $query->result();
        echo json_encode(["data" => $result, "status" => 200]);
    }
    public function getFileList()
    {
        $Company = $this->input->post("Company");
        $this->db->select("file_name");
        $this->db->from("master_file");
        $this->db->where("company_id", $Company);
        $query = $this->db->get();
        $result = $query->result();
        if (!empty($result)) {
            echo json_encode(["data" => $result, "status" => 200]);
        } else {
            echo json_encode(["data" => [], "status" => 400]);
        }
    }
    public function getDepartmentList()
    {
        $Company = $this->input->post("Company");
        $this->db->select("department_id,department_name");
        $this->db->from("master_department");
        $this->db->where("company_id", $Company);
        $query = $this->db->get();
        $result = $query->result();
        if (!empty($result)) {
            echo json_encode(["data" => $result, "status" => 200]);
        } else {
            echo json_encode(["data" => [], "status" => 400]);
        }
    }
    public function edit_doc_name()
    {
        $scan_id = $this->input->post("scan_id");
        $DocName = $this->input->post("DocName");
        $this->db->where("scan_id", $scan_id);
        $query = $this->db->update("y{$this->year_id}_scan_file", ["document_name" => $DocName,]);
        if ($query) {
            echo json_encode(["status" => "200", "message" => "File Name Update Successfully.",]);
        } else {
            echo json_encode(["status" => "400", "message" => "Something went wrong. Please try again.",]);
        }
    }
    public function fetchRegions()
    {
        $state_id = $this->input->post("state_id");
        $regions = $this->db->where("status", "A")->where("is_deleted", "N")->get("master_region")->result_array();
        echo json_encode($regions);
    }
    public function fetchCrops()
    {
        $category_id = $this->input->post("category_id");
        $category_id_array = [1, 2, 3, 4, 5, 6];
        if (in_array($category_id, $category_id_array)) {
            $crops = $this->db->where("status", "A")->where("is_deleted", "N")->get("master_crop")->result_array();
        } else {
            $crops = $this->db->where("status", "A")->where("is_deleted", "N")->where("crop_category_id", $category_id)->get("master_crop")->result_array();
        }
        echo json_encode($crops);
    }
    public function fetchAccount()
    {
        $category_id = $this->input->post("account_id");
        $query = $this->db->where("account_group", $category_id)->get("master_account");
        $accounts = $query->result_array();
        echo json_encode($accounts);
    }
    public function approve_file($scan_id)
    {
        if ($scan_id) {
            $data = ["finance_punch_action_status" => "Y", "finance_punch_action_by" => $this->session->userdata("user_id"), "finance_punch_action_date" => date("Y-m-d H:i:s"),];
            $this->db->where("scan_id", $scan_id);
            $this->db->update("y{$this->year_id}_scan_file", $data);
            $this->session->set_flashdata("success", "File has been approved successfully.");
            redirect("finance/bill-approval/N");
        } else {
            $this->session->set_flashdata("error", "Invalid file.");
            redirect("finance/bill-approval/N");
        }
    }
    public function reject_file($scan_id)
    {
        if ($scan_id) {
            $punch_reject_remark = $this->input->post("punch_reject_remark");
            $data = ["finance_punch_action_status" => "R", "finance_punch_action_by" => $this->session->userdata("user_id"), "finance_punch_action_date" => date("Y-m-d H:i:s"), "punch_reject_remark" => $punch_reject_remark,];
            $this->db->where("scan_id", $scan_id);
            $this->db->update("y{$this->year_id}_scan_file", $data);
            $this->session->set_flashdata("success", "File has been rejected with your remark.");
            redirect("finance/bill-approval/N");
        } else {
            $this->session->set_flashdata("error", "Invalid file.");
            redirect("finance/bill-approval/N");
        }
    }
    public function savePunchToDatabase()
    {
        if (!isset($this->year_id)) {
            show_error('Year ID is not defined', 500);
        }
        $DocTypeId = (int) $this->input->post('DocTypeId');
        $functionName = "processPunchData_{$DocTypeId}";
        $punch_table = "y{$this->year_id}_punchdata_{$DocTypeId}";
        $punch_table_detail = "y{$this->year_id}_punchdata_{$DocTypeId}_details";
        if (method_exists($this, $functionName)) {
            $result = $this->$functionName($this->input->post());
        } else {
            show_error("Handler not defined for DocTypeId {$DocTypeId}", 500);
        }
        $data = $result;
        $main = $data['main'];
        $items = $data['items'];
        $scan_id = $main['scan_id'];
        $this->db->trans_start();
        $this->db->trans_strict(true);
        $is_update = $this->db->where('scan_id', $scan_id)->count_all_results($punch_table) > 0;
        if ($is_update) {
            $this->db->where('scan_id', $scan_id)->update($punch_table, $main);
            $FileID = $this->db->select('id')->where('scan_id', $scan_id)->get($punch_table)->row('id');
            $this->db->where('FileID', $FileID)->update('sub_punchfile', ['Amount' => '-' . (float) ($this->input->post('Grand_Total') ?? 0), 'Comment' => $this->db->escape_str($this->input->post('Remark') ?? ''),]);
        } else {
            $this->db->insert($punch_table, $main);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', ['FileID' => $insert_id, 'Amount' => '-' . (float) ($this->input->post('Grand_Total') ?? 0), 'Comment' => $this->db->escape_str($this->input->post('Remark') ?? ''),]);
        }
        if (!empty($items)) {
            $this->db->where('scan_id', $scan_id)->delete($punch_table_detail);
            $this->db->insert_batch($punch_table_detail, $items);
        }
        if ($is_update) {
            $this->db->where('scan_id', $scan_id)->update("y{$this->year_id}_scan_file", ['is_rejected' => 'N', 'finance_punch_action_status' => 'N', 'reject_date' => null, 'has_edit_permission' => 'N',]);
        }
        $this->db->trans_complete();
        if ($this->input->post('submit')) {
            $this->customlib->update_file_path($scan_id);
        }
        if ($this->db->trans_status() === false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something went wrong</div>');
        } else {
            $msg = $this->input->post('submit') ? 'Invoice added successfully' : 'Data saved in Draft';
            $this->session->set_flashdata('message', "<div class='alert alert-success text-left'>{$msg}</div>");
        }
        redirect($this->input->post('submit') ? 'punch' : $_SERVER['HTTP_REFERER']);
    }
    private function processPunchData_1($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $EmployeeID = (int) ($post['Employee'] ?? 0);
        $Emp_Code = $this->db->escape_str($post['Emp_Code'] ?? '');
        $DocTypeId = 1;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ['scan_id' => $scan_id, 'group_id' => $this->session->userdata('group_id'), 'doctype' => $DocType, 'doctype_id' => $DocTypeId, 'bill_date' => $this->db->escape_str($post['Bill_Date'] ?? ''), 'employee_name' => $EmployeeID, 'emp_code' => $Emp_Code, 'vehicle_no' => $this->db->escape_str($post['Vehicle_No'] ?? ''), 'vehicle_type' => $this->db->escape_str($post['Vehicle_Type'] ?? ''), 'rs_km' => (float) ($post['Rate'] ?? 0), 'total_run_km' => (float) ($post['Total_KM'] ?? 0), 'location' => $this->db->escape_str($post['Location'] ?? ''), 'round_off_type' => $this->db->escape_str($post['plus_minus'] ?? ''), 'total' => (float) ($post['Total_Amount'] ?? 0), 'grand_total' => (float) ($post['Grand_Total'] ?? 0), 'total_discount' => (float) ($post['Total_Discount'] ?? 0), 'remark_comment' => $this->db->escape_str($post['Remark'] ?? ''), 'created_by' => $this->session->userdata('user_id'), 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => $this->session->userdata('user_id'),];
        $items = [];
        $count = count($post['Dist_Opening'] ?? []);
        for ($i = 0; $i < $count; $i++) {
            $items[] = ['scan_id' => $scan_id, 'opening_km' => (float) ($post['Dist_Opening'][$i] ?? 0), 'closing_km' => (float) ($post['Dist_Closing'][$i] ?? 0), 'total_km' => (float) ($post['Km'][$i] ?? 0), 'amount' => (float) ($post['Amount'][$i] ?? 0),];
        }
        return ['main' => $mainData, 'items' => $items,];
    }
    private function processPunchData_6($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 6;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ['scan_id' => $scan_id, 'group_id' => $this->session->userdata('group_id'), 'doctype' => $DocType, 'doctype_id' => $DocTypeId, 'type' => $this->db->escape_str($post['Type'] ?? ''), 'date' => $this->db->escape_str($post['Date'] ?? ''), 'bank_name' => $this->db->escape_str($post['Bank_Name'] ?? ''), 'branch' => $this->db->escape_str($post['Branch'] ?? ''), 'account_no' => $this->db->escape_str($post['Account_No'] ?? ''), 'beneficiary_name' => $this->db->escape_str($post['Beneficiary_Name'] ?? ''), 'amount' => $this->db->escape_str($post['Amount'] ?? ''), 'remark_comment' => $this->db->escape_str($post['Remark'] ?? ''), 'created_by' => $this->session->userdata('user_id'), 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => $this->session->userdata('user_id'),];
        $items = [];
        return ['main' => $mainData, 'items' => $items];
    }

    private function processPunchData_7($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 7;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'company_name' => $this->db->escape_str($post['CompanyID'] ?? ''),
            'voucher_no' => $this->db->escape_str($post['Voucher_No'] ?? ''),
            'voucher_date' => $this->db->escape_str($post['Voucher_Date'] ?? ''),
            'location' => $this->db->escape_str($post['Location'] ?? ''),
            'payee' => $this->db->escape_str($post['Payee'] ?? ''),
            'payer' => $this->db->escape_str($post['Payer'] ?? ''),
            'amount' => (float) ($post['Amount'] ?? 0),
            'particular' => $this->db->escape_str($post['Particular'] ?? ''),
            'remark_comment' => $this->db->escape_str($post['Remark'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];
        $items = [];
        return ['main' => $mainData, 'items' => $items];
    }

    private function processPunchData_13($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 13;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location' => $this->db->escape_str($post['Location'] ?? ''),
            'payment_date' => $this->db->escape_str($post['PaymentDate'] ?? ''),
            'biller_name' => $this->db->escape_str($post['Biller_Name'] ?? ''),
            'business_partner_no' => $this->db->escape_str($post['BP_No'] ?? ''),
            'bill_period' => $this->db->escape_str($post['Period'] ?? ''),
            'meter_number' => $this->db->escape_str($post['Meter_No'] ?? ''),
            'bill_date' => $this->db->escape_str($post['Bill_Date'] ?? ''),
            'bill_no' => $this->db->escape_str($post['Bill_No'] ?? ''),
            'previous_meter_reading' => $this->db->escape_str($post['Previous_Reading'] ?? ''),
            'current_meter_reading' => $this->db->escape_str($post['Current_Reading'] ?? ''),
            'unit_consumed' => $this->db->escape_str($post['Unit_Consumed'] ?? ''),
            'last_date_of_payment' => $this->db->escape_str($post['Last_Date'] ?? ''),
            'payment_mode' => $this->db->escape_str($post['Payment_Mode'] ?? ''),
            'bill_amount' => $this->db->escape_str($post['Bill_Amount'] ?? ''),
            'payment_amount' => $this->db->escape_str($post['Payment_Amount'] ?? ''),
            'remark_comment' => $this->db->escape_str($post['Remark'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];
        $items = [];
        return ['main' => $mainData, 'items' => $items];
    }
    private function processPunchData_17($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 17;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'agency_name' => $this->db->escape_str($post['agency_name'] ?? ''),
            'agency_address' => $this->db->escape_str($post['agency_address'] ?? ''),
            'billing_name' => $this->db->escape_str($post['billing_name'] ?? ''),
            'billing_address' => $this->db->escape_str($post['billing_address'] ?? ''),
            'employee_name' => $this->db->escape_str($post['employee_name'] ?? ''),
            'emp_code' => $this->db->escape_str($post['emp_code'] ?? ''),
            'vehicle_no' => $this->db->escape_str($post['vehicle_no'] ?? ''),
            'location' => $this->db->escape_str($post['location'] ?? ''),
            'invoice_no' => $this->db->escape_str($post['invoice_no'] ?? ''),
            'invoice_date' => $this->db->escape_str($post['invoice_date'] ?? ''),
            'per_km_rate' => (float) ($post['per_km_rate'] ?? 0.00),
            'booking_date' => $this->db->escape_str($post['booking_date'] ?? ''),
            'end_date' => $this->db->escape_str($post['end_date'] ?? ''),
            'start_reading' => (int) ($post['start_reading'] ?? 0),
            'closing_reading' => (int) ($post['closing_reading'] ?? 0),
            'total_km' => (int) ($post['total_km'] ?? 0),
            'other_charges' => (float) ($post['other_charges'] ?? 0.00),
            'total_amount' => (float) ($post['total_amount'] ?? 0.00),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        return ['main' => $mainData, 'items' => []];
    }
    private function processPunchData_20($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 20;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'section' => $this->db->escape_str($post['section'] ?? ''),
            'company' => $this->db->escape_str($post['company'] ?? ''),
            'nature_of_payment' => $this->db->escape_str($post['nature_of_payment'] ?? ''),
            'assessment_year' => $this->db->escape_str($post['assessment_year'] ?? ''),
            'bank_name' => $this->db->escape_str($post['bank_name'] ?? ''),
            'bsr_code' => $this->db->escape_str($post['bsr_code'] ?? ''),
            'challan_no' => $this->db->escape_str($post['challan_no'] ?? ''),
            'challan_date' => $this->db->escape_str($post['challan_date'] ?? ''),
            'bank_reference_no' => $this->db->escape_str($post['bank_reference_no'] ?? ''),
            'amount' => (float) ($post['amount'] ?? 0.00),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        return ['main' => $mainData, 'items' => []];
    }
    private function processPunchData_22($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 22;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'insurance_type' => $this->db->escape_str($post['insurance_type'] ?? ''),
            'insurance_company' => $this->db->escape_str($post['insurance_company'] ?? ''),
            'policy_number' => $this->db->escape_str($post['policy_number'] ?? ''),
            'policy_date' => $this->db->escape_str($post['policy_date'] ?? ''),
            'from_date' => $this->db->escape_str($post['from_date'] ?? ''),
            'to_date' => $this->db->escape_str($post['to_date'] ?? ''),
            'vehicle_no' => $this->db->escape_str($post['vehicle_no'] ?? ''),
            'location' => $this->db->escape_str($post['location'] ?? ''),
            'premium_amount' => (float) ($post['premium_amount'] ?? 0.00),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        return ['main' => $mainData, 'items' => []];
    }
    private function processPunchData_46($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 46;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'cpin' => $this->db->escape_str($post['cpin'] ?? ''),
            'deposit_date' => $this->db->escape_str($post['deposit_date'] ?? ''),
            'cin' => $this->db->escape_str($post['cin'] ?? ''),
            'bank_name' => $this->db->escape_str($post['bank_name'] ?? ''),
            'brn' => $this->db->escape_str($post['brn'] ?? ''),
            'gstin' => $this->db->escape_str($post['gstin'] ?? ''),
            'email_id' => $this->db->escape_str($post['email_id'] ?? ''),
            'mobile_no' => $this->db->escape_str($post['mobile_no'] ?? ''),
            'company_name' => $this->db->escape_str($post['company_name'] ?? ''),
            'address' => $this->db->escape_str($post['address'] ?? ''),
            'cgst_tax' => (float) ($post['Tax'][0] ?? 0.00),
            'cgst_interest' => (float) ($post['Interest'][0] ?? 0.00),
            'cgst_penalty' => (float) ($post['Penalty'][0] ?? 0.00),
            'cgst_fees' => (float) ($post['Fees'][0] ?? 0.00),
            'cgst_other' => (float) ($post['Other'][0] ?? 0.00),
            'cgst_total' => (float) ($post['Total'][0] ?? 0.00),
            'sgst_tax' => (float) ($post['Tax'][1] ?? 0.00),
            'sgst_interest' => (float) ($post['Interest'][1] ?? 0.00),
            'sgst_penalty' => (float) ($post['Penalty'][1] ?? 0.00),
            'sgst_fees' => (float) ($post['Fees'][1] ?? 0.00),
            'sgst_other' => (float) ($post['Other'][1] ?? 0.00),
            'sgst_total' => (float) ($post['Total'][1] ?? 0.00),
            'igst_tax' => (float) ($post['Tax'][2] ?? 0.00),
            'igst_interest' => (float) ($post['Interest'][2] ?? 0.00),
            'igst_penalty' => (float) ($post['Penalty'][2] ?? 0.00),
            'igst_fees' => (float) ($post['Fees'][2] ?? 0.00),
            'igst_other' => (float) ($post['Other'][2] ?? 0.00),
            'igst_total' => (float) ($post['Total'][2] ?? 0.00),
            'cess_tax' => (float) ($post['Tax'][3] ?? 0.00),
            'cess_interest' => (float) ($post['Interest'][3] ?? 0.00),
            'cess_penalty' => (float) ($post['Penalty'][3] ?? 0.00),
            'cess_fees' => (float) ($post['Fees'][3] ?? 0.00),
            'cess_other' => (float) ($post['Other'][3] ?? 0.00),
            'cess_total' => (float) ($post['Total'][3] ?? 0.00),
            'total_challan_amount' => (float) ($post['total_challan_amount'] ?? 0.00),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return ['main' => $mainData, 'items' => []];
    }

    private function processPunchData_27($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 27;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'mode' => $this->db->escape_str($post['mode'] ?? ''),
            'location' => $this->db->escape_str($post['location'] ?? ''),
            'employee_name' => $this->db->escape_str($post['employee_name'] ?? ''),
            'emp_code' => $this->db->escape_str($post['emp_code'] ?? ''),
            'vehicle_no' => $this->db->escape_str($post['vehicle_no'] ?? ''),
            'month' => $this->db->escape_str($post['month'] ?? ''),
            'calculation_base' => $this->db->escape_str($post['calculation_base'] ?? ''),
            'per_km_rate' => (float) ($post['per_km_rate'] ?? 0.00),
            'total_km' => (float) ($post['total_km'] ?? 0.00),
            'total' => (float) ($post['total_amount'] ?? 0.00),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        $items = [];
        if (!empty($post['date']) && is_array($post['date'])) {
            foreach ($post['date'] as $key => $date) {
                if (!empty($date)) {
                    $items[] = [
                        'scan_id' => $scan_id,
                        'travel_date' => $this->db->escape_str($date),
                        'opening_reading' => (float) ($post['dist_opening'][$key] ?? 0.00),
                        'closing_reading' => (float) ($post['dist_closing'][$key] ?? 0.00),
                        'total_km' => (float) ($post['km'][$key] ?? 0.00),
                        'amount' => (float) ($post['amount'][$key] ?? 0.00),
                    ];
                }
            }
        }

        return ['main' => $mainData, 'items' => $items];
    }
    private function processPunchData_28($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 28;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'location' => $this->db->escape_str($post['location'] ?? ''),
            'bill_no' => $this->db->escape_str($post['bill_no'] ?? ''),
            'bill_date' => $this->db->escape_str($post['bill_date'] ?? ''),
            'billing_name' => $this->db->escape_str($post['billing_name'] ?? ''),
            'billing_address' => $this->db->escape_str($post['billing_address'] ?? ''),
            'hotel_name' => $this->db->escape_str($post['hotel_name'] ?? ''),
            'hotel_address' => $this->db->escape_str($post['hotel_address'] ?? ''),
            'billing_instruction' => $this->db->escape_str($post['billing_instruction'] ?? ''),
            'booking_id' => $this->db->escape_str($post['booking_id'] ?? ''),
            'check_in' => $this->db->escape_str($post['check_in'] ?? ''),
            'check_out' => $this->db->escape_str($post['check_out'] ?? ''),
            'duration_of_stay' => (int) ($post['duration_of_stay'] ?? 0),
            'number_of_rooms' => (int) ($post['number_of_rooms'] ?? 0),
            'room_type' => $this->db->escape_str($post['room_type'] ?? ''),
            'meal_plan' => $this->db->escape_str($post['meal_plan'] ?? ''),
            'rate' => (float) ($post['rate'] ?? 0.00),
            'amount' => (float) ($post['amount'] ?? 0.00),
            'other_charges' => (float) ($post['other_charges'] ?? 0.00),
            'discount' => (float) ($post['discount'] ?? 0.00),
            'gst' => (float) ($post['gst'] ?? 0.00),
            'grand_total' => (float) ($post['grand_total'] ?? 0.00),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        $items = [];
        if (!empty($post['employee']) && is_array($post['employee'])) {
            foreach ($post['employee'] as $key => $employee) {
                if (!empty($employee)) {
                    $items[] = [
                        'scan_id' => $scan_id,
                        'emp_name' => $this->db->escape_str($employee),
                        'emp_code' => $this->db->escape_str($post['emp_code'][$key] ?? ''),
                    ];
                }
            }
        }

        return ['main' => $mainData, 'items' => $items];
    }
    private function processPunchData_47($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 47;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'voucher_no' => $this->db->escape_str($post['voucher_no'] ?? ''),
            'payment_date' => $this->db->escape_str($post['payment_date'] ?? ''),
            'payee' => $this->db->escape_str($post['payee'] ?? ''),
            'location' => $this->db->escape_str($post['location'] ?? ''),
            'particular' => $this->db->escape_str($post['particular'] ?? ''),
            'total_amount' => (float) ($post['total_amount'] ?? 0.00),
            'from_date' => $this->db->escape_str($post['from_date'] ?? ''),
            'to_date' => $this->db->escape_str($post['to_date'] ?? ''),
            'sub_total' => (float) ($post['sub_total'] ?? 0.00),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        $items = [];
        if (!empty($post['head']) && is_array($post['head'])) {
            foreach ($post['head'] as $key => $head) {
                if (!empty($head)) {
                    $items[] = [
                        'scan_id' => $scan_id,
                        'head' => $this->db->escape_str($head),
                        'amount' => (float) ($post['amount'][$key] ?? 0.00),
                    ];
                }
            }
        }

        return ['main' => $mainData, 'items' => $items];
    }
    private function processPunchData_48($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 48;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location' => $this->db->escape_str($post['Location'] ?? ''),
            'company_name' => $this->db->escape_str($post['CompanyID'] ?? ''),
            'voucher_no' => $this->db->escape_str($post['Receipt_No'] ?? ''),
            'date' => $this->db->escape_str($post['Receipt_Date'] ?? ''),
            'receiver_name' => $this->db->escape_str($post['Receiver'] ?? ''),
            'received_from' => $this->db->escape_str($post['ReceivedFrom'] ?? ''),
            'amount' => (float) ($post['Amount'] ?? 0),
            'particular' => $this->db->escape_str($post['Particular'] ?? ''),
            'remark_comment' => $this->db->escape_str($post['Remark'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];
        $items = [];
        return ['main' => $mainData, 'items' => $items];
    }

    private function processPunchData_51($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 51;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'mode' => $this->db->escape_str($post['mode'] ?? ''),
            'agent_name' => $this->db->escape_str($post['agent_name'] ?? ''),
            'pnr_number' => $this->db->escape_str($post['pnr_number'] ?? ''),
            'date_of_booking' => $this->db->escape_str($post['date_of_booking'] ?? ''),
            'journey_date' => $this->db->escape_str($post['journey_date'] ?? ''),
            'air_line' => $this->db->escape_str($post['air_line'] ?? ''),
            'ticket_number' => $this->db->escape_str($post['ticket_number'] ?? ''),
            'journey_from' => $this->db->escape_str($post['journey_from'] ?? ''),
            'journey_upto' => $this->db->escape_str($post['journey_upto'] ?? ''),
            'travel_class' => $this->db->escape_str($post['travel_class'] ?? ''),
            'location' => $this->db->escape_str($post['location_id'] ?? ''),
            'passenger_details' => $this->db->escape_str($post['passenger_details'] ?? ''),
            'base_fare' => (float) ($post['base_fare'] ?? 0.00),
            'gst' => (float) ($post['gst'] ?? 0.00),
            'fees_surcharge' => (float) ($post['fees_surcharge'] ?? 0.00),
            'cute_charge' => $this->db->escape_str($post['cute_charge'] ?? ''),
            'extra_luggage' => $this->db->escape_str($post['extra_luggage'] ?? ''),
            'other' => $this->db->escape_str($post['other'] ?? ''),
            'total_fare' => (float) ($post['total_fare'] ?? 0.00),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        $items = [];
        if (!empty($post['Employee']) && is_array($post['Employee'])) {
            foreach ($post['Employee'] as $key => $employee_id) {
                if (!empty($employee_id)) {
                    $items[] = [
                        'scan_id' => $scan_id,
                        'emp_name' => (int) $employee_id,
                        'emp_code' => $this->db->escape_str($post['EmpCode'][$key] ?? ''),
                    ];
                }
            }
        }

        return ['main' => $mainData, 'items' => $items];
    }

    private function processPunchData_23($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $From = (int) ($post['From'] ?? 0);
        $To = (int) ($post['To'] ?? 0);
        $mainData = ['scan_id' => $scan_id, 'group_id' => $this->session->userdata('group_id'), 'doctype' => $this->db->escape_str($this->customlib->getDocType(23)), 'doctype_id' => 23, 'invoice_date' => $this->db->escape_str($post['Bill_Date'] ?? ''), 'invoice_no' => $this->db->escape_str($post['Bill_No'] ?? ''), 'mode_of_payment' => $this->db->escape_str($post['Payment_Mode'] ?? ''), 'buyer' => $From, 'vendor' => $To, 'buyer_address' => $this->db->escape_str($post['Buyer_Address'] ?? ''), 'vendor_address' => $this->db->escape_str($post['Vendor_Address'] ?? ''), 'buyers_order_no' => $this->db->escape_str($post['Buyer_Order'] ?? ''), 'buyers_order_date' => $this->db->escape_str($post['Buyer_Order_Date'] ?? ''), 'dispatch_through' => $this->db->escape_str($post['Dispatch_Trough'] ?? ''), 'delivery_note_date' => $this->db->escape_str($post['Delivery_Note_Date'] ?? ''), 'voucher_type_category' => $this->db->escape_str($post['Category'] ?? ''), 'lr_number' => $this->db->escape_str($post['LR_Number'] ?? ''), 'lr_date' => $this->db->escape_str($post['LR_Date'] ?? ''), 'cartoon_number' => $this->db->escape_str($post['Cartoon_Number'] ?? ''), 'sub_total' => (float) ($post['Sub_Total'] ?? 0), 'total' => (float) ($post['Total'] ?? 0), 'grand_total' => (float) ($post['Grand_Total'] ?? 0), 'total_discount' => (float) ($post['Total_Discount'] ?? 0), 'tcs_percent' => (float) ($post['TCS'] ?? 0), 'remark' => $this->db->escape_str($post['Remark'] ?? ''), 'created_by' => $this->session->userdata('user_id'), 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => $this->session->userdata('user_id'),];
        $invoiceDetails = [];
        $count = count($post['Particular'] ?? []);
        for ($i = 0; $i < $count; $i++) {
            $unit = isset($post['Unit'][$i]) && $post['Unit'][$i] !== '' ? $this->db->escape_str($post['Unit'][$i]) : 'PCS';
            $invoiceDetails[] = ['scan_id' => $scan_id, 'particular' => $this->db->escape_str($post['Particular'][$i] ?? ''), 'hsn' => $this->db->escape_str($post['HSN'][$i] ?? ''), 'qty' => (float) ($post['Qty'][$i] ?? 0), 'unit' => $unit, 'mrp' => (float) ($post['MRP'][$i] ?? 0), 'discount_in_mrp' => (float) ($post['Discount'][$i] ?? 0), 'price' => (float) ($post['Price'][$i] ?? 0), 'amount' => (float) ($post['Amount'][$i] ?? 0), 'gst' => (float) ($post['GST'][$i] ?? 0), 'sgst' => (float) ($post['SGST'][$i] ?? 0), 'igst' => (float) ($post['IGST'][$i] ?? 0), 'cess' => (float) ($post['Cess'][$i] ?? 0), 'total_amount' => (float) ($post['TAmount'][$i] ?? 0),];
        }
        return ['main' => $mainData, 'items' => $invoiceDetails];
    }
    private function processPunchData_29($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 29;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'hotel_name' => $this->db->escape_str($post['hotel_name'] ?? ''),
            'bill_no' => $this->db->escape_str($post['bill_no'] ?? ''),
            'bill_date' => $this->db->escape_str($post['bill_date'] ?? ''),
            'hotel_address' => $this->db->escape_str($post['hotel_address'] ?? ''),
            'employee_name' => $this->db->escape_str($post['employee_name'] ?? ''),
            'emp_code' => $this->db->escape_str($post['emp_code'] ?? ''),
            'amount' => (float) ($post['amount'] ?? 0.00),
            'location' => $this->db->escape_str($post['location'] ?? ''),
            'detail' => $this->db->escape_str($post['detail'] ?? ''),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        return ['main' => $mainData, 'items' => []];
    }
    private function processPunchData_31($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 31;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'company' => $this->db->escape_str($post['company'] ?? ''),
            'voucher_no' => $this->db->escape_str($post['voucher_no'] ?? ''),
            'voucher_date' => $this->db->escape_str($post['voucher_date'] ?? ''),
            'location' => $this->db->escape_str($post['location'] ?? ''),
            'vendor' => $this->db->escape_str($post['vendor'] ?? ''),
            'amount' => (float) ($post['amount'] ?? 0.00),
            'particular' => $this->db->escape_str($post['particular'] ?? ''),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        return ['main' => $mainData, 'items' => []];
    }
    private function processPunchData_50($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 50;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'company_name' => $this->db->escape_str($post['company_name'] ?? ''),
            'company_address' => $this->db->escape_str($post['company_address'] ?? ''),
            'vendor_name' => $this->db->escape_str($post['vendor_name'] ?? ''),
            'vendor_address' => $this->db->escape_str($post['vendor_address'] ?? ''),
            'vehicle_no' => $this->db->escape_str($post['vehicle_no'] ?? ''),
            'vehicle_type' => $this->db->escape_str($post['vehicle_type'] ?? ''),
            'location' => $this->db->escape_str($post['location'] ?? ''),
            'invoice_date' => $this->db->escape_str($post['invoice_date'] ?? ''),
            'particular' => $this->db->escape_str($post['particular'] ?? ''),
            'hour' => (float) ($post['hour'] ?? 0.00),
            'trips' => $this->db->escape_str($post['trips'] ?? ''),
            'rate_per_trip' => (float) ($post['rate_per_trip'] ?? 0.00),
            'total_amount' => (float) ($post['total_amount'] ?? 0.00),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        return ['main' => $mainData, 'items' => []];
    }
    private function processPunchData_42($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 42;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'bill_invoice_date' => $this->db->escape_str($post['bill_invoice_date'] ?? ''),
            'invoice_bill_no' => $this->db->escape_str($post['invoice_bill_no'] ?? ''),
            'biller_name' => $this->db->escape_str($post['biller_name'] ?? ''),
            'telephone_no' => $this->db->escape_str($post['telephone_no'] ?? ''),
            'invoice_period' => $this->db->escape_str($post['invoice_period'] ?? ''),
            'invoice_taxable_value' => (float) ($post['invoice_taxable_value'] ?? 0.00),
            'cgst' => (float) ($post['cgst'] ?? 0.00),
            'sgst' => (float) ($post['sgst'] ?? 0.00),
            'igst' => (float) ($post['igst'] ?? 0.00),
            'total_amount_due' => (float) ($post['total_amount_due'] ?? 0.00),
            'total_amount_outstanding' => (float) ($post['total_amount_outstanding'] ?? 0.00),
            'last_payment_date' => $this->db->escape_str($post['last_payment_date'] ?? ''),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        return ['main' => $mainData, 'items' => []];
    }


    private function processPunchData_52($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 52;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'mode' => $this->db->escape_str($post['mode'] ?? ''),
            'train_number' => $this->db->escape_str($post['train_number'] ?? ''),
            'agent_name' => $this->db->escape_str($post['agent_name'] ?? ''),
            'pnr_number' => $this->db->escape_str($post['pnr_number'] ?? ''),
            'date_of_booking' => $this->db->escape_str($post['date_of_booking'] ?? ''),
            'journey_date' => $this->db->escape_str($post['journey_date'] ?? ''),
            'booking_id' => $this->db->escape_str($post['booking_id'] ?? ''),
            'transaction_id' => $this->db->escape_str($post['transaction_id'] ?? ''),
            'journey_from' => $this->db->escape_str($post['journey_from'] ?? ''),
            'journey_upto' => $this->db->escape_str($post['journey_upto'] ?? ''),
            'travel_class' => $this->db->escape_str($post['travel_class'] ?? ''),
            'quota' => $this->db->escape_str($post['quota'] ?? ''),
            'location' => $this->db->escape_str($post['location'] ?? ''),
            'passenger_details' => $this->db->escape_str($post['passenger_details'] ?? ''),
            'base_fare' => $this->db->escape_str($post['base_fare'] ?? ''),
            'gst' => $this->db->escape_str($post['gst'] ?? ''),
            'fees_surcharge' => $this->db->escape_str($post['fees_surcharge'] ?? ''),
            'other_charges' => $this->db->escape_str($post['other_charges'] ?? ''),
            'total_fare' => (float) ($post['total_fare'] ?? 0.00),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        $items = [];
        if (!empty($post['employee']) && is_array($post['employee'])) {
            foreach ($post['employee'] as $key => $employee) {
                if (!empty($employee)) {
                    $items[] = [
                        'scan_id' => $scan_id,
                        'employee_name' => $this->db->escape_str($employee),
                        'emp_code' => $this->db->escape_str($post['emp_code'][$key] ?? ''),
                    ];
                }
            }
        }

        return ['main' => $mainData, 'items' => $items];
    }

    private function processPunchData_55($post)
    {
        $scan_id = (int) ($post['scan_id'] ?? 0);
        $DocTypeId = 55;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = [
            'scan_id' => $scan_id,
            'group_id' => $this->session->userdata('group_id'),
            'doctype' => $DocType,
            'doctype_id' => $DocTypeId,
            'location_id' => (int) ($post['location_id'] ?? 0),
            'agent_name' => $this->db->escape_str($post['agent_name'] ?? ''),
            'booking_date' => $this->db->escape_str($post['booking_date'] ?? ''),
            'cancelled_date' => $this->db->escape_str($post['cancelled_date'] ?? ''),
            'sub_total' => (float) ($post['sub_total'] ?? 0.00),
            'cancellation_charge' => (float) ($post['cancellation_charge'] ?? 0.00),
            'other_charges' => (float) ($post['other_charges'] ?? 0.00),
            'grand_total' => (float) ($post['grand_total'] ?? 0.00),
            'remark_comment' => $this->db->escape_str($post['remark_comment'] ?? ''),
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id'),
        ];

        $items = [];
        if (!empty($post['employee']) && is_array($post['employee'])) {
            foreach ($post['employee'] as $key => $employee) {
                if (!empty($employee)) {
                    $items[] = [
                        'scan_id' => $scan_id,
                        'employee_name' => $this->db->escape_str($employee),
                        'pnr_number' => $this->db->escape_str($post['pnr_number'][$key] ?? ''),
                        'amount' => (float) ($post['amount'][$key] ?? 0.00),
                    ];
                }
            }
        }

        return ['main' => $mainData, 'items' => $items];
    }
    private function fetchPunchDetails($scan_id, $type_id, $year_id)
    {
        if ($scan_id <= 0 || $type_id <= 0) {
            echo json_encode(['status' => 400, 'msg' => 'Invalid scan_id or type_id']);
            return;
        }
        if (empty($year_id)) {
            echo json_encode(['status' => 400, 'msg' => 'Year ID is not defined']);
            return;
        }
        $punch_table = "y{$year_id}_punchdata_{$type_id}_details";
        $result = $this->db->select('*')->from($punch_table)->where('scan_id', $scan_id)->get()->result_array();
        if (!empty($result)) {
            echo json_encode(['status' => 200, 'data' => $result]);
        } else {
            echo json_encode(['status' => 400, 'msg' => 'No Record Found']);
        }
    }
    public function getPunchItems()
    {
        $year_id = $this->year_id;
        $scan_id = (int) $this->input->post('scan_id');
        $type_id = (int) $this->input->post('type_id');
        $this->fetchPunchDetails($scan_id, $type_id, $year_id);
    }
    public function getTwoFourWheelerItems()
    {
        $year_id = $this->year_id;
        $scan_id = (int) $this->input->post('scan_id');
        $type_id = (int) $this->input->post('type_id');
        $this->fetchPunchDetails($scan_id, $type_id, $year_id);
    }
    public function getEmployeeItems()
    {
        $year_id = $this->year_id;
        $scan_id = (int) $this->input->post('scan_id');
        $type_id = (int) $this->input->post('type_id');
        $this->fetchPunchDetails($scan_id, $type_id, $year_id);
    }

    public function getLabourPaymentItems()
    {
        $year_id = $this->year_id;
        $scan_id = (int) $this->input->post('scan_id');
        $type_id = (int) $this->input->post('type_id');
        $this->fetchPunchDetails($scan_id, $type_id, $year_id);
    }


    public function getReadingItems()
    {
        $year_id = $this->year_id;
        $scan_id = (int) $this->input->post('scan_id');
        $type_id = (int) $this->input->post('type_id');
        $this->fetchPunchDetails($scan_id, $type_id, $year_id);
    }

    public function getTicketCancellationItems()
    {
        $year_id = $this->year_id;
        $scan_id = (int) $this->input->post('scan_id');
        $type_id = (int) $this->input->post('type_id');
        $this->fetchPunchDetails($scan_id, $type_id, $year_id);
    }
}
