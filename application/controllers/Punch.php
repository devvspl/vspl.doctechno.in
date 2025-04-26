<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Punch extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Punch_model');
        $this->load->model('BaseModel');
    }
    private function logged_in()
    {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    public function index()
    {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', 'punch');
        $this->data['main'] = 'punch/punchfile';
        $this->data['scanfile_list'] = $this->Punch_model->get_file_for_punch();
        $this->data['my_doctype_list'] = $this->Punch_model->get_my_permissioned_doctype_list();
        $this->load->view('layout/template', $this->data);
    }
    public function file_entry($Scan_Id = null, $DocType_Id = null)
    {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', 'punch');
        $data = $this->getCommonPunchData($Scan_Id, $DocType_Id);
        $data['main'] = 'punch/_punch';
        if (!empty($data['doc_config']) && !empty($data['doc_config']['data_method'])) {
            $method = $data['doc_config']['data_method'];
            if (method_exists($this, $method)) {
                $docData = $this->$method($Scan_Id, $DocType_Id);
                $data = array_merge($data, $docData);
            }
        }
        $this->load->view('layout/template', $data);
    }
    private function getCommonPunchData($Scan_Id, $DocType_Id)
    {
        $document_name = $this->customlib->getDocumentName($Scan_Id);
        $doc_type_name = $this->customlib->getDocType($DocType_Id);
        $docTypeViews = [
            23 => ['view' => 'invoice', 'data_method' => 'getInvoiceData'],
            1 => ['view' => 'two_four_wheeler', 'data_method' => 'getTwoFourWheelerData'],
            4 => ['view' => 'bank_statement', 'data_method' => 'getBankStatementData'],
            5 => ['view' => 'boarding_pass', 'data_method' => 'getBoardingPassData'],
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
        return ['Scan_Id' => $Scan_Id, 'DocType_Id' => $DocType_Id, 'document_name' => $document_name, 'doc_type_name' => $doc_type_name, 'doc_config' => $docTypeViews[$DocType_Id] ?? null];
    }

    private function fetchData($tableName, $db) {
        return $db->where('status', 'A')
           ->where('is_deleted', 'N')
           ->get($tableName)
           ->result_array();
        }


    private function getInvoiceData($Scan_Id, $DocType_Id)
    {
        return [
           
            'rec' => $this->customlib->getScanData($Scan_Id),
            'punch_detail' => $this->db->get_where('punchfile', ['Scan_Id' => $Scan_Id])->row(),
            'firm' => $this->db->get_where('master_firm', ['status' => 'A'])->result_array(),
            'company_list' => $this->customlib->getCompanyList(),
            'file_list' => $this->customlib->getFileList(),
            'ledger_list' => $this->customlib->getLedgerList(),
            'category_list' => $this->customlib->getCategoryList(),
            'item_list' => $this->customlib->getItemList(),
            'locationlist' => $this->customlib->getWorkLocationList(),
            'temp_punch_detail' => $this->db->get_where("ext_tempdata_{$DocType_Id}", ['scan_id' => $Scan_Id])->row(),
            'document_number' => 'CASH/' . date('y-m') . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT),
            'business_entity' => $this->db->where(['status'=>'A', 'is_deleted'=> 'N'])->get('master_business_entity')->result_array(),
            'headquarter' => $this->db->get('master_headquarter')->result_array(),
            'voucher_items' => $this->db->where(['Scan_Id'=>$Scan_Id])->get('cash_voucher_items'),
            'core_department' => $this->BaseModel->getData('core_department', ['is_active'=>1])->result_array(),
            'core_business_unit' => $this->BaseModel->getData('core_business_unit', ['is_active'=>1])->result_array(),
            'core_region' => $this->BaseModel->getData('core_region', ['is_active'=>1])->result_array(),
            'core_state' => $this->BaseModel->getData('core_state', ['is_active'=>1])->result_array(),
            'core_category' => $this->BaseModel->getData('core_category', ['is_active'=>1])->result_array(),
            'master_payment_method' => $this->BaseModel->getData('master_payment_method')->result_array(),

            
            'states' => $this->fetchData('master_state', $this->db),
            'locations' => $this->fetchData('master_work_location', $this->db),
            'categories' => $this->fetchData('master_crop_category', $this->db),
            'activities' => $this->fetchData('master_activity', $this->db),
            'crop_list' => $this->fetchData('master_crop', $this->db),
            'cost_centers' => $this->fetchData('master_cost_center', $this->db),
            'region_list' => $this->fetchData('master_region', $this->db), 
            'tdsJvNo' => 'TDSCASH/' . date('Y-m', strtotime($this->db->select('Created_Date')->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->order_by('Created_Date', 'DESC')->limit(1)->get('punchfile')->row()->Created_Date ?? date('Y-m'))) . '/' . str_pad($this->db->where('DocTypeId', 7)->where('MONTH(Created_Date)', date('m'))->where('YEAR(Created_Date)', date('Y'))->count_all_results('punchfile') + 1, 4, '0', STR_PAD_LEFT)
   
        ];
    }
    private function getTwoFourWheelerData($Scan_Id, $DocType_Id)
    {
        return ['vehicle_info' => $this->db->get_where('vehicle_table', ['scan_id' => $Scan_Id])->row(), 'vehicle_type_list' => $this->customlib->getVehicleTypes()];
    }
    public function my_punched_file($show_all = null)
    {
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

    public function rejected_punch()
    {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', '');
        $this->data['rejected_punch_list'] = $this->Punch_model->get_rejected_punch_list();
        $this->data['main'] = 'punch/rejected_punch';
        $this->load->view('layout/template', $this->data);
    }
    public function finance_rejected_punch()
    {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', '');
        $this->data['finance_rejected_punch_list'] = $this->Punch_model->get_finance_rejected_punch_list();
        $this->data['main'] = 'punch/finance_rejected_punch';
        $this->load->view('layout/template', $this->data);
    }
    public function resend_scan($Scan_Id)
    {
        $user_id = $this->session->userdata('user_id');
        $Reject_Remark = $this->input->post('Remark');
        $this->db->where('Scan_Id', $Scan_Id);
        $result = $this->db->update('scan_file', ['Scan_Resend' => 'Y', 'Scan_Resend_By' => $user_id, 'Scan_Resend_Remark' => $Reject_Remark, 'Scan_Resend_Date' => date('Y-m-d')]);
        if ($result) {
            echo json_encode(['status' => '200', 'message' => 'File Resend Successfully.']);
        } else {
            echo json_encode(['status' => '400', 'message' => 'Something went wrong. Please try again.']);
        }
    }
    public function finance_resend_scan($Scan_Id)
    {
        $user_id = $this->session->userdata('user_id');
        $Reject_Remark = $this->input->post('Remark');
        $this->db->where('Scan_Id', $Scan_Id);
        $result = $this->db->update('scan_file', ['Is_Finance_Rejected' => 'Y', 'Finance_Resend_By' => $user_id, 'Finance_Resend_Remark' => $Reject_Remark, 'at_finance' => 'P', 'Finance_Resend_Date' => date('Y-m-d')]);
        if ($result) {
            echo json_encode(['status' => '200', 'message' => 'File Resend Successfully.']);
        } else {
            echo json_encode(['status' => '400', 'message' => 'Something went wrong. Please try again.']);
        }
    }
    public function changeDocType()
    {
        $Scan_Id = $this->input->post('Scan_Id');
        $DocType_Id = $this->input->post('DocType_Id');
        $Doc_Type = $this->customlib->getDocType($DocType_Id);
        $this->db->where('Scan_Id', $Scan_Id);
        $query = $this->db->update('scan_file', ['DocType_Id' => $DocType_Id, 'Doc_Type' => $Doc_Type]);
        if ($query) {
            echo json_encode(['status' => 200]);
        } else {
            echo json_encode(['status' => 400]);
        }
    }
    public function getSupportFile()
    {
        $Scan_Id = $this->input->post('Scan_Id');
        $this->db->select('*');
        $this->db->from('support_file');
        $this->db->where('Scan_Id', $Scan_Id);
        $query = $this->db->get();
        $result = $query->result();
        echo json_encode(["data" => $result, 'status' => 200]);
    }
    public function getFileList()
    {
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
    public function getDepartmentList()
    {
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
    public function edit_doc_name()
    {
        $Scan_Id = $this->input->post('Scan_Id');
        $DocName = $this->input->post('DocName');
        $this->db->where('Scan_Id', $Scan_Id);
        $query = $this->db->update('scan_file', ['Document_Name' => $DocName]);
        if ($query) {
            echo json_encode(['status' => '200', 'message' => 'File Name Update Successfully.']);
        } else {
            echo json_encode(['status' => '400', 'message' => 'Something went wrong. Please try again.']);
        }
    }
    public function fetchRegions()
    {
        $state_id = $this->input->post('state_id');
        $regions = $this->db
            ->where('status', 'A')
            ->where('is_deleted', 'N')
            ->get('master_region')
            ->result_array();
        echo json_encode($regions);
    }
    public function fetchCrops()
    {
        $category_id = $this->input->post('category_id');
        $category_id_array = [1, 2, 3, 4, 5, 6];
        if (in_array($category_id, $category_id_array)) {
            $crops = $this->db
                ->where('status', 'A')
                ->where('is_deleted', 'N')
                ->get('master_crop')
                ->result_array();
        } else {
            $crops = $this->db
                ->where('status', 'A')
                ->where('is_deleted', 'N')
                ->where('crop_category_id', $category_id)
                ->get('master_crop')
                ->result_array();
        }
        echo json_encode($crops);
    }
    public function fetchAccount()
    {
        $category_id = $this->input->post('account_id');
        $query = $this->db->where('account_group', $category_id)->get('master_account');
        $accounts = $query->result_array();
        echo json_encode($accounts);
    }
}
