<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Punch extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Punch_model');
        $this->load->model('BaseModel');
    }
    private function logged_in() {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    public function index() {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', 'punch');
        $this->data['main'] = 'punch/punchfile';
        $this->data['scanfile_list'] = $this->Punch_model->get_file_for_punch();
        $this->data['my_doctype_list'] = $this->Punch_model->get_my_permissioned_doctype_list();
        $this->load->view('layout/template', $this->data);
    }
    public function get_additional_information_by_scan_id($scan_id) {
        $this->db->select('ai.*, be.business_entity_name, td.section');
        $this->db->from('tbl_additional_information ai');
        $this->db->join('master_business_entity be', 'be.business_entity_id = ai.business_entity_id', 'left');
        $this->db->join('master_tds_sections td', 'td.id = ai.tds_section_id', 'left');
        $this->db->where('ai.scan_id', $scan_id);
        $mainRecord = $this->db->get()->row_array();;
        if (empty($mainRecord)) {
            return null;
        }
        $this->db->select('aii.*, cc.name, d.department_name, bu.business_unit_name, r.region_name, s.state_name, l.city_village_name as location_name, c.category_name, cr.crop_name, a.activity_name, da.account_name as debit_account, pm.payment_term_name as payment_term');
        $this->db->from('tbl_additional_information_items aii');
        $this->db->join('master_cost_center cc', 'cc.id = aii.cost_center_id', 'left');
        $this->db->join('core_department d', 'd.api_id = aii.department_id', 'left');
        $this->db->join('core_business_unit bu', 'bu.api_id = aii.business_unit_id', 'left');
        $this->db->join('core_region r', 'r.api_id = aii.region_id', 'left');
        $this->db->join('core_state s', 's.api_id = aii.state_id', 'left');
        $this->db->join('core_city_village l', 'l.api_id = aii.location_id', 'left');
        $this->db->join('master_category c', 'c.category_id = aii.category_id', 'left');
        $this->db->join('core_crop cr', 'cr.api_id = aii.crop_id', 'left');
        $this->db->join('core_activity a', 'a.api_id = aii.activity_id', 'left');
        $this->db->join('master_account_ledger da', 'da.id = aii.debit_account_id', 'left');
        $this->db->join('payment_term_master pm', 'pm.id = aii.payment_term_id', 'left');
        $this->db->where('aii.scan_id', $scan_id);
        $items = $this->db->get()->result_array();
        $mainRecord['items'] = $items;
        return $mainRecord;
    }
    public function file_entry($scan_id = null, $doc_type_id = null) {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', 'punch');
        $data = $this->getCommonPunchData($scan_id, $doc_type_id);
        $data['user_permission'] = $this->Punch_model->checkUserPermission($this->session->userdata('user_id'));
        $mainRecord = $this->get_additional_information_by_scan_id($scan_id);
        if ($mainRecord) {
            $data['main_record'] = $mainRecord;
        } else {
            $data['main_record'] = null;
        }
        $data['main'] = 'punch/_punch';
        if (!empty($data['doc_config']) && !empty($data['doc_config']['data_method'])) {
            $method = $data['doc_config']['data_method'];
            if (method_exists($this, $method)) {
                $docData = $this->$method($scan_id, $doc_type_id);
                $data = array_merge($data, $docData);
            }
        }
        $this->load->view('layout/template', $data);
    }
    private function getCommonPunchData($scan_id, $doc_type_id) {
        $document_name = $this->customlib->getDocumentName($scan_id);
        $doc_type_name = $this->customlib->getDocType($doc_type_id);
        $docTypeViews = [
            23 => ['view' => 'invoice', 'data_method' => 'getInvoiceData'], 
            1 => ['view' => 'two_four_wheeler', 'data_method' => 'getTwoFourWheelerData'], 
            4 => ['view' => 'bank_statement', 'data_method' => 'getBankStatementData'], 
            5 => ['view' => 'boarding_pass', 'data_method' => 'getBoardingPassData'], 
            6 => ['view' => 'cash_deposit_withdrawals', 'data_method' => 'getCashDepositWithdrawalsData'], 
            8 => ['view' => 'certificate', 'data_method' => 'getCertificateData'], 
            10 => ['view' => 'company_record', 'data_method' => 'getCompanyRecordData'], 
            11 => ['view' => 'confirmation_account', 'data_method' => 'getConfirmationAccountData'], 
            18 => ['view' => 'id_address_proof', 'data_method' => 'getIdAddressProofData'], 
            19 => ['view' => 'import_export_paper', 'data_method' => 'getImportExportPaperData'], 
            30 => ['view' => 'mediclaim_history', 'data_method' => 'getMediclaimHistoryData'], 
            31 => ['view' => 'miscellaneous', 'data_method' => 'getMiscellaneousData'], 
            32 => ['view' => 'pf_esic', 'data_method' => 'getPfEsicData'], 
            35 => ['view' => 'property_record', 'data_method' => 'getPropertyRecordData'], 
            36 => ['view' => 'reting_credential', 'data_method' => 'getRetingCredentialData'], 
            37 => ['view' => 'registration_certificate', 'data_method' => 'getRegistrationCertificateData'], 
            41 => ['view' => 'tax_credit_document', 'data_method' => 'getTaxCreditDocumentData'], 
            45 => ['view' => 'vehicle_registration_paper', 'data_method' => 'getVehicleRegistrationPaperData'], 
            44 => ['view' => 'vehicle_maintenance', 'data_method' => 'getVehicleMaintenanceData'], 
            43 => ['view' => 'vehicle_fule', 'data_method' => 'getVehicleFuelData'], 
            42 => ['view' => 'telephone_bill', 'data_method' => 'getTelephoneBillData'], 
            40 => ['view' => 'subsidy', 'data_method' => 'getSubsidyData'], 
            39 => ['view' => 'rtgs_neft', 'data_method' => 'getRtgsNeftData'], 
            38 => ['view' => 'rst_ofd', 'data_method' => 'getRstOfdData'], 
            34 => ['view' => 'postage_courier', 'data_method' => 'getPostageCourierData'], 
            33 => ['view' => 'phone_fax', 'data_method' => 'getPhoneFaxData'], 
            29 => ['view' => 'meals', 'data_method' => 'getMealsData'], 
            28 => ['view' => 'lodging', 'data_method' => 'getLodgingData'], 
            27 => ['view' => 'local_conveyance', 'data_method' => 'getLocalConveyanceData'], 
            26 => ['view' => 'lease_rent', 'data_method' => 'getLeaseRentData'], 
            25 => ['view' => 'jeep_campaign', 'data_method' => 'getJeepCampaignData'], 
            24 => ['view' => 'it_return', 'data_method' => 'getItReturnData'], 
            22 => ['view' => 'insurance_policy', 'data_method' => 'getInsurancePolicyData'], 
            21 => ['view' => 'insurance_document', 'data_method' => 'getInsuranceDocumentData'], 
            20 => ['view' => 'income_taxt_tds', 'data_method' => 'getIncomeTaxTdsData'], 
            17 => ['view' => 'hired_vehicle', 'data_method' => 'getHiredVehicleData'], 
            16 => ['view' => 'challan', 'data_method' => 'getChallanData'], 
            15 => ['view' => 'fixed_deposit_receipt', 'data_method' => 'getFixedDepositReceiptData'], 
            14 => ['view' => 'fd_fv', 'data_method' => 'getFdFvData'], 
            13 => ['view' => 'electricity_bill', 'data_method' => 'getElectricityBillData'], 
            12 => ['view' => 'dealer_meeting', 'data_method' => 'getDealerMeetingData'], 
            9 => ['view' => 'cheque', 'data_method' => 'getChequeData'], 
            7 => ['view' => 'cash_voucher', 'data_method' => 'getCashVoucherData'], 
            46 => ['view' => 'gst_challan', 'data_method' => 'getGstChallanData'], 
            47 => ['view' => 'labour_payment', 'data_method' => 'getLabourPaymentData'], 
            48 => ['view' => 'cash_receipt', 'data_method' => 'getCashReceiptData'], 
            49 => ['view' => 'fixed_asset', 'data_method' => 'getFixedAssetData'], 
            50 => ['view' => 'machine_operation', 'data_method' => 'getMachineOperationData'], 
            51 => ['view' => 'air', 'data_method' => 'getAirData'], 
            52 => ['view' => 'rail', 'data_method' => 'getRailData'], 
            53 => ['view' => 'bus', 'data_method' => 'getBusData'], 
            54 => ['view' => 'sale_bill', 'data_method' => 'getSaleBillData'], 
            55 => ['view' => 'ticket_cancellation', 'data_method' => 'getTicketCancellationData'], 
            56 => ['view' => 'credit_note', 'data_method' => 'getCreditNoteData'], 
        ];
        return ['scan_id' => $scan_id, 'doc_type_id' => $doc_type_id, 'document_name' => $document_name, 'doc_type_name' => $doc_type_name, 'doc_config' => $docTypeViews[$doc_type_id]??null];
    }
    private function fetchData($tableName, $db) {
        return $db->where('status', 'A')->where('is_deleted', 'N')->get($tableName)->result_array();
    }
    private function getInvoiceData($scan_id, $doc_type_id) {
        return ['rec' => $this->customlib->getScanData($scan_id), 'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 'firm' => $this->db->get_where('master_firm', ['status' => 'A'])->result_array(), 'company_list' => $this->customlib->getCompanyList(), 'file_list' => $this->customlib->getFileList(), 'ledger_list' => $this->customlib->getLedgerList(), 'category_list' => $this->customlib->getCategoryList(), 'item_list' => $this->customlib->getItemList(), 'locationlist' => $this->customlib->getWorkLocationList(), 'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), ];
    }
    private function getIncomeTaxTdsData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'firm' => $this->db->get_where('master_firm', ['status' => 'A'])->result_array(), 
            'company_list' => $this->customlib->getCompanyList(), 
            'fin_year' => $this->customlib->getFinancial_year(),
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), ];
    }
    private function getCashDepositWithdrawalsData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'firm' => $this->db->get_where('master_firm', ['status' => 'A'])->result_array(), 
            'company_list' => $this->customlib->getCompanyList(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), ];
    }
    private function getCashReceiptData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'company_list' => $this->customlib->getCompanyList(), 
            'locationlist' => $this->customlib->getWorkLocationList(),
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), ];
    }

    private function getCashVoucherData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'company_list' => $this->customlib->getCompanyList(), 
            'locationlist' => $this->customlib->getWorkLocationList(),
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), ];
    }

    private function getTwoFourWheelerData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'worklocation_list' => $this->customlib->getWorkLocationList(),
            'employee_list' => $this->customlib->getEmployeeList(),
         ];
    }

    private function getAirData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'locationlist' => $this->customlib->getWorkLocationList(),
            'employee_list' => $this->customlib->getEmployeeList(),
         ];
    }

    private function getCreditNoteData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'firm' => $this->db->get_where('master_firm', ['status' => 'A'])->result_array(),
            'company_list' => $this->customlib->getCompanyList(),
            'department_list' => $this->customlib->getDepartmentList(),
            'file_list' => $this->customlib->getFileList(),
            'worklocation_list' => $this->customlib->getWorkLocationList(),
            'ledger_list' => $this->customlib->getLedgerList(),
            'category_list' => $this->customlib->getCategoryList(),
            'item_list' => $this->customlib->getItemList(),
            'locationlist' => $this->customlib->getWorkLocationList(),
         ];
    }


    private function getElectricityBillData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'company_list' => $this->customlib->getCompanyList(),
            'locationlist' => $this->customlib->getWorkLocationList(),
         ];
    }

    private function getGstChallanData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'company_list' => $this->customlib->getCompanyList(),
         ];
    }


    private function getHiredVehicleData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'company_list' => $this->customlib->getCompanyList(),
            'vendor_list' => $this->customlib->getVendorList(),
            'locationlist' => $this->customlib->getWorkLocationList(),
            'employee_list' => $this->customlib->getEmployeeList(),
         ];
    }
    private function getInsurancePolicyData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'company_list' => $this->customlib->getCompanyList(),
         ];
    }


    private function getLabourPaymentData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
          
         ];
    }
    private function getLocalConveyanceData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'locationlist' => $this->customlib->getWorkLocationList(),
            'employee_list' => $this->customlib->getEmployeeList(),
            'months' => array('1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December')
         ];
    }
    
    private function getLodgingData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'company_list' => $this->customlib->getCompanyList(),
            'vendor_list' => $this->customlib->getVendorList(),
            'locationlist' => $this->customlib->getWorkLocationList(),
            'hotel_list' => $this->db->select('hotel_id,hotel_name,address')->get_where('master_hotel', ['status' => 'A', 'is_deleted' => 'N'])->result_array(),
            'employee_list' => $this->customlib->getEmployeeList(),
         ];
    }

    private function getMachineOperationData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'company_list' => $this->customlib->getCompanyList(),
            'vendor_list' => $this->customlib->getVendorList(),
            'worklocation_list' => $this->customlib->getWorkLocationList(),
            'fin_year' => $this->customlib->getFinancial_year()
         ];
    }

    private function getMealsData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'company_list' => $this->customlib->getCompanyList(),
            'locationlist' => $this->customlib->getWorkLocationList(),
            'employee_list' => $this->customlib->getEmployeeList(),
         ];
    }

    private function getMiscellaneousData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile2', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'company_list' => $this->customlib->getCompanyList(),
            'vendor_list' => $this->db->get_where('master_firm', ['status' => 'A'])->result_array(),
            'worklocation_list' => $this->customlib->getWorkLocationList(),

         ];
    }

    private function getRailData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile2', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'employee_list' => $this->customlib->getEmployeeList(),
            'locationlist' => $this->customlib->getWorkLocationList(),

         ];
    }

    private function getSaleBillData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'firm' => $this->db->get_where('master_firm', ['status' => 'A'])->result_array(),
            'company_list' => $this->customlib->getCompanyList(),
            'department_list' => $this->customlib->getDepartmentList(),
            'file_list' => $this->customlib->getFileList(),
            'worklocation_list' => $this->customlib->getWorkLocationList(),
            'ledger_list' => $this->customlib->getLedgerList(),
            'category_list' => $this->customlib->getCategoryList(),
            'item_list' => $this->customlib->getItemList(),
            'locationlist' => $this->customlib->getWorkLocationList(),
         ];
    }

    private function getTelephoneBillData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT)
         ];
    }

    private function getTicketCancellationData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'company_list' => $this->customlib->getCompanyList(),
            'hotel_list' => $this->db->select('hotel_id,hotel_name,address')->get_where('master_hotel', ['status' => 'A', 'is_deleted' => 'N'])->result_array(),
            'employee_list' => $this->customlib->getEmployeeList(),
         ];
    }

    private function getVehicleFuelData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'company_list' => $this->customlib->getCompanyList(),
            'vendor_list' => $this->db->get_where('master_firm', ['status' => 'A'])->result_array(),
            'worklocation_list' => $this->customlib->getWorkLocationList(),

         ];
    }

    private function getVehicleMaintenanceData($scan_id, $doc_type_id) {
        return [
            'rec' => $this->customlib->getScanData($scan_id), 
            'punch_detail' => $this->db->get_where('punchfile', ['scan_id' => $scan_id])->row(), 
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$doc_type_id}", ['scan_id' => $scan_id])->row(), 
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date??date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'company_list' => $this->customlib->getCompanyList(),
            'vendor_list' => $this->db->get_where('master_firm', ['status' => 'A'])->result_array(),
            'worklocation_list' => $this->customlib->getWorkLocationList(),

         ];
    }
    
    public function my_punched_file($show_all = null) {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', 'my_punched_file');
        $user_id = $this->session->userdata('user_id');
        $from_date = null;
        $to_date = null;
        if ($show_all == null) {
            $this->form_validation->set_rules('from_date', 'Punch From Date', 'trim|required');
            $this->form_validation->set_rules('to_date', 'Punch To Date', 'trim|required');
            if ($this->form_validation->run() == true) {
                $from_date = $this->input->post('from_date');
                $to_date = $this->input->post('to_date');
            }
        }
        $punch_file_list = $this->Punch_model->get_punched_files($user_id, $from_date, $to_date, $show_all ? true : false);
        $this->data['my_punched_files'] = $punch_file_list;
        $this->data['main'] = 'punch/my_punched_file';
        $this->load->view('layout/template', $this->data);
    }
    public function my_finance_punched_file($show_all = null) {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', 'my_punched_file');
        $user_id = $this->session->userdata('user_id');
        $from_date = null;
        $to_date = null;
        if ($show_all == null) {
            $this->form_validation->set_rules('from_date', 'Punch From Date', 'trim|required');
            $this->form_validation->set_rules('to_date', 'Punch To Date', 'trim|required');
            if ($this->form_validation->run() == true) {
                $from_date = $this->input->post('from_date');
                $to_date = $this->input->post('to_date');
            }
        }
        $punch_file_list = $this->Punch_model->get_finance_punched_files($user_id, $from_date, $to_date, $show_all ? true : false);
        $this->data['my_finance_punched_file'] = $punch_file_list;
        $this->data['main'] = 'punch/my_finance_punched_file';
        $this->load->view('layout/template', $this->data);
    }
    public function my_finance_bill_approval_file($finance_punch_status) {
        $punch_file_list = $this->Punch_model->get_finance_bill_approval_files($finance_punch_status);
        $this->data['my_finance_punched_file'] = $punch_file_list;
        $this->data['main'] = 'punch/my_finance_bill_approval_file';
        $this->load->view('layout/template', $this->data);
    }
    public function rejected_punch() {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', '');
        $this->data['rejected_punch_list'] = $this->Punch_model->get_rejected_punch_list();
        $this->data['main'] = 'punch/rejected_punch';
        $this->load->view('layout/template', $this->data);
    }
    public function finance_rejected_punch() {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', '');
        $this->data['finance_rejected_punch_list'] = $this->Punch_model->get_finance_rejected_punch_list();
        $this->data['main'] = 'punch/finance_rejected_punch';
        $this->load->view('layout/template', $this->data);
    }
    public function finance_rejected_punch_1() {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', '');
        $this->data['finance_rejected_punch_list'] = $this->Punch_model->get_finance_rejected_punch_list_1();
        $this->data['main'] = 'punch/finance_rejected_punch_1';
        $this->load->view('layout/template', $this->data);
    }
    public function resend_scan($scan_id) {
        $user_id = $this->session->userdata('user_id');
        $Reject_Remark = $this->input->post('Remark');
        $this->db->where('scan_id', $scan_id);
        $result = $this->db->update("y{$this->year_id}_scan_file", ['is_scan_resend' => 'Y', 'scan_resend_by' => $user_id, 'scan_resend_remark' => $Reject_Remark, 'scan_resend_date' => date('Y-m-d') ]);
        if ($result) {
            echo json_encode(['status' => '200', 'message' => 'File Resend Successfully.']);
        } else {
            echo json_encode(['status' => '400', 'message' => 'Something went wrong. Please try again.']);
        }
    }
    public function finance_resend_scan($scan_id) {
        $user_id = $this->session->userdata('user_id');
        $Reject_Remark = $this->input->post('Remark');
        $this->db->where('scan_id', $scan_id);
        $result = $this->db->update("y{$this->year_id}_scan_file", ['finance_punch_action_status' => 'Y', 'Finance_Resend_By' => $user_id, 'Finance_Resend_Remark' => $Reject_Remark, 'finance_punch_action_status' => 'P', 'Finance_Resend_Date' => date('Y-m-d') ]);
        if ($result) {
            echo json_encode(['status' => '200', 'message' => 'File Resend Successfully.']);
        } else {
            echo json_encode(['status' => '400', 'message' => 'Something went wrong. Please try again.']);
        }
    }
    public function changeDocType() {
        $scan_id = $this->input->post('scan_id');
        $doc_type_id = $this->input->post('doc_type_id');
        $Doc_Type = $this->customlib->getDocType($doc_type_id);
        $this->db->where('scan_id', $scan_id);
        $query = $this->db->update("y{$this->year_id}_scan_file", ['doc_type_id' => $doc_type_id, 'doc_type' => $Doc_Type]);
        if ($query) {
            echo json_encode(['status' => 200]);
        } else {
            echo json_encode(['status' => 400]);
        }
    }
    public function getSupportFile() {
        $scan_id = $this->input->post('scan_id');
        $this->db->select('*');
        $this->db->from('support_file');
        $this->db->where('scan_id', $scan_id);
        $query = $this->db->get();
        $result = $query->result();
        echo json_encode(["data" => $result, 'status' => 200]);
    }
    public function getFileList() {
        $Company = $this->input->post('Company');
        $this->db->select('file_name');
        $this->db->from('master_file');
        $this->db->where('company_id', $Company);
        $query = $this->db->get();
        $result = $query->result();
        if (!empty($result)) {
            echo json_encode(["data" => $result, 'status' => 200]);
        } else {
            echo json_encode(["data" => [], 'status' => 400]);
        }
    }
    public function getDepartmentList() {
        $Company = $this->input->post('Company');
        $this->db->select('department_id,department_name');
        $this->db->from('master_department');
        $this->db->where('company_id', $Company);
        $query = $this->db->get();
        $result = $query->result();
        if (!empty($result)) {
            echo json_encode(["data" => $result, 'status' => 200]);
        } else {
            echo json_encode(["data" => [], 'status' => 400]);
        }
    }
    public function edit_doc_name() {
        $scan_id = $this->input->post('scan_id');
        $DocName = $this->input->post('DocName');
        $this->db->where('scan_id', $scan_id);
        $query = $this->db->update("y{$this->year_id}_scan_file", ['document_name' => $DocName]);
        if ($query) {
            echo json_encode(['status' => '200', 'message' => 'File Name Update Successfully.']);
        } else {
            echo json_encode(['status' => '400', 'message' => 'Something went wrong. Please try again.']);
        }
    }
    public function fetchRegions() {
        $state_id = $this->input->post('state_id');
        $regions = $this->db->where('status', 'A')->where('is_deleted', 'N')->get('master_region')->result_array();
        echo json_encode($regions);
    }
    public function fetchCrops() {
        $category_id = $this->input->post('category_id');
        $category_id_array = [1, 2, 3, 4, 5, 6];
        if (in_array($category_id, $category_id_array)) {
            $crops = $this->db->where('status', 'A')->where('is_deleted', 'N')->get('master_crop')->result_array();
        } else {
            $crops = $this->db->where('status', 'A')->where('is_deleted', 'N')->where('crop_category_id', $category_id)->get('master_crop')->result_array();
        }
        echo json_encode($crops);
    }
    public function fetchAccount() {
        $category_id = $this->input->post('account_id');
        $query = $this->db->where('account_group', $category_id)->get('master_account');
        $accounts = $query->result_array();
        echo json_encode($accounts);
    }
    public function approve_file($scan_id) {
        if ($scan_id) {
            $data = array('finance_punch_action_status' => 'Y', 'finance_punch_action_by' => $this->session->userdata('user_id'), 'finance_punch_action_date' => date('Y-m-d H:i:s'));
            $this->db->where('scan_id', $scan_id);
            $this->db->update("y{$this->year_id}_scan_file", $data);
            $this->session->set_flashdata('success', 'File has been approved successfully.');
            redirect('finance/bill-approval/N');
        } else {
            $this->session->set_flashdata('error', 'Invalid file.');
            redirect('finance/bill-approval/N');
        }
    }
    public function reject_file($scan_id) {
        if ($scan_id) {
            $punch_reject_remark = $this->input->post('punch_reject_remark');
            $data = array('finance_punch_action_status' => 'R', 'finance_punch_action_by' => $this->session->userdata('user_id'), 'finance_punch_action_date' => date('Y-m-d H:i:s'), 'punch_reject_remark' => $punch_reject_remark);
            $this->db->where('scan_id', $scan_id);
            $this->db->update("y{$this->year_id}_scan_file", $data);
            $this->session->set_flashdata('success', 'File has been rejected with your remark.');
            redirect('finance/bill-approval/N');
        } else {
            $this->session->set_flashdata('error', 'Invalid file.');
            redirect('finance/bill-approval/N');
        }
    }
}
