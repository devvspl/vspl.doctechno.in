<?php
defined('BASEPATH') or exit('No direct script access allowed');
class VSPL_Punch extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model('Punch_model');
        $this->load->model('Group_model');
        $this->load->helper('download');
        $this->load->model('Record_model');
    }
    private function logged_in()
    {
        if (!$this->session->userdata('authenticated')) {
            redirect('/');
        }
    }
    public function file_entry()
    {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', 'punch');
        $this->data['main'] = 'punch/_vspl_punch';
        $this->load->view('layout/template', $this->data);
    }
    public function focus_exports()
    {
        $this->session->set_userdata('top_menu', 'punch_master');
        $this->session->set_userdata('sub_menu', 'punch');

        $from_date = $this->input->get('from_date') ?? '';
        $to_date = $this->input->get('to_date') ?? '';

        // Initialize the WHERE clause
        $where = "sf.finance_punch_action_status = 'Y' AND sf.finance_punch_status = 'Y'";
        $params = [];

        // Add date filters if provided
        if (!empty($from_date)) {
            $where .= " AND sf.finance_punch_action_date >= ?";
            $params[] = $from_date;
        }
        if (!empty($to_date)) {
            $where .= " AND sf.finance_punch_action_date <= ?";
            $params[] = $to_date;
        }

        // Fetch data for table display
        $query = $this->db->query("
            SELECT
                ai.document_no AS DocNo,
                '' AS Date,
                '' AS Time,
                be.focus_code AS Business_Entity,
                ai.narration AS sNarration,
                ai.tds_jv_no AS TDSJVNo,
                aii.reverse_charge AS ReverseCharge_Yn_,
                sf.bill_number AS BillNo,
                sf.bill_date AS BillDate,
                cd.focus_code AS Department,
                mcc.focus_code AS Cost_Center,
                cbu.focus_code AS Business_Unit,
                ca.focus_code AS Activity,
                mwl.focus_code AS Location,
                cs.focus_code AS State,
                cc.focus_code AS Category,
                ccp.focus_code AS Crop,
                cr.focus_code AS Region,
                cof.focus_code AS Function,
                cv.focus_code AS FC_Vertical,
                csd.focus_code AS Sub_Department,
                cz.focus_code AS Zone,
                mald.focus_code AS DrAccount,
                malc.focus_code AS CrAccount,
                aii.amount AS Amount,
                aii.reference AS Reference,
                ai.tds_amount AS TDSBillAmount,
                aii.tds_amount AS TDS,
                ai.tds_percentage AS TDSPer
            FROM
                y1_tbl_additional_information_items AS aii
            LEFT JOIN y1_scan_file AS sf ON aii.scan_id = sf.scan_id
            LEFT JOIN y1_tbl_additional_information AS ai ON aii.scan_id = ai.scan_id
            LEFT JOIN master_business_entity AS be ON be.business_entity_id = ai.business_entity_id
            LEFT JOIN core_department AS cd ON cd.api_id = aii.department_id
            LEFT JOIN master_cost_center AS mcc ON mcc.id = aii.cost_center_id
            LEFT JOIN core_business_unit AS cbu ON cbu.api_id = aii.business_unit_id
            LEFT JOIN core_activity AS ca ON ca.api_id = aii.activity_id
            LEFT JOIN master_work_location AS mwl ON mwl.location_id = aii.location_id
            LEFT JOIN core_state AS cs ON cs.api_id = aii.state_id
            LEFT JOIN core_category AS cc ON cc.api_id = aii.category_id
            LEFT JOIN core_crop AS ccp ON ccp.api_id = aii.crop_id
            LEFT JOIN core_region AS cr ON cr.api_id = aii.region_id
            LEFT JOIN core_org_function AS cof ON cof.api_id = aii.function_id
            LEFT JOIN core_vertical AS cv ON cv.api_id = aii.vertical_id
            LEFT JOIN core_sub_department AS csd ON csd.api_id = aii.sub_department_id
            LEFT JOIN core_zone AS cz ON cz.api_id = aii.zone_id
            LEFT JOIN master_account_ledger AS mald ON mald.id = aii.debit_account_id
            LEFT JOIN master_account_ledger AS malc ON malc.id = aii.credit_account_id
            WHERE
                $where
        ", $params);

        $results = $query->result_array();

        // Prepare data for the view
        $this->data['from_date'] = $from_date;
        $this->data['to_date'] = $to_date;
        $this->data['results'] = $results;
        $this->data['main'] = 'vspl/focus_exports';
        $this->load->view('layout/template', $this->data);
    }

    public function export_csv()
    {
        $from_date = $this->input->get('from_date') ?? '';
        $to_date = $this->input->get('to_date') ?? '';

        // Initialize the WHERE clause
        $where = "sf.finance_punch_action_status = 'Y' AND sf.finance_punch_status = 'Y'";
        $params = [];

        // Add date filters if provided
        if (!empty($from_date)) {
            $where .= " AND sf.finance_punch_action_date >= ?";
            $params[] = $from_date;
        }
        if (!empty($to_date)) {
            $where .= " AND sf.finance_punch_action_date <= ?";
            $params[] = $to_date;
        }

        // Fetch data for CSV export
        $query = $this->db->query("
            SELECT
                ai.document_no AS DocNo,
                '' AS Date,
                '' AS Time,
                be.focus_code AS Business_Entity,
                ai.narration AS sNarration,
                ai.tds_jv_no AS TDSJVNo,
                aii.reverse_charge AS ReverseCharge_Yn_,
                sf.bill_number AS BillNo,
                sf.bill_date AS BillDate,
                cd.focus_code AS Department,
                mcc.focus_code AS Cost_Center,
                cbu.focus_code AS Business_Unit,
                ca.focus_code AS Activity,
                mwl.focus_code AS Location,
                cs.focus_code AS State,
                cc.focus_code AS Category,
                ccp.focus_code AS Crop,
                cr.focus_code AS Region,
                cof.focus_code AS Function,
                cv.focus_code AS FC_Vertical,
                csd.focus_code AS Sub_Department,
                cz.focus_code AS Zone,
                mald.focus_code AS DrAccount,
                malc.focus_code AS CrAccount,
                aii.amount AS Amount,
                aii.reference AS Reference,
                ai.tds_amount AS TDSBillAmount,
                aii.tds_amount AS TDS,
                ai.tds_percentage AS TDSPer
            FROM
                y1_tbl_additional_information_items AS aii
            LEFT JOIN y1_scan_file AS sf ON aii.scan_id = sf.scan_id
            LEFT JOIN y1_tbl_additional_information AS ai ON aii.scan_id = ai.scan_id
            LEFT JOIN master_business_entity AS be ON be.business_entity_id = ai.business_entity_id
            LEFT JOIN core_department AS cd ON cd.api_id = aii.department_id
            LEFT JOIN master_cost_center AS mcc ON mcc.id = aii.cost_center_id
            LEFT JOIN core_business_unit AS cbu ON cbu.api_id = aii.business_unit_id
            LEFT JOIN core_activity AS ca ON ca.api_id = aii.activity_id
            LEFT JOIN master_work_location AS mwl ON mwl.location_id = aii.location_id
            LEFT JOIN core_state AS cs ON cs.api_id = aii.state_id
            LEFT JOIN core_category AS cc ON cc.api_id = aii.category_id
            LEFT JOIN core_crop AS ccp ON ccp.api_id = aii.crop_id
            LEFT JOIN core_region AS cr ON cr.api_id = aii.region_id
            LEFT JOIN core_org_function AS cof ON cof.api_id = aii.function_id
            LEFT JOIN core_vertical AS cv ON cv.api_id = aii.vertical_id
            LEFT JOIN core_sub_department AS csd ON csd.api_id = aii.sub_department_id
            LEFT JOIN core_zone AS cz ON cz.api_id = aii.zone_id
            LEFT JOIN master_account_ledger AS mald ON mald.id = aii.debit_account_id
            LEFT JOIN master_account_ledger AS malc ON malc.id = aii.credit_account_id
            WHERE
                $where
        ", $params);

        $results = $query->result_array();

        // Define CSV column headers matching the Excel format
        $headers = [
            'DocNo',
            'Date',
            'Time',
            'Business Entity',
            'sNarration',
            'TDSJVNo',
            'ReverseCharge_Yn_',
            'BillNo',
            'BillDate',
            'Department',
            'Cost Center',
            'Business Unit',
            'Activity',
            'Location',
            'State',
            'Category',
            'Crop',
            'Region',
            'Function',
            'FC-Vertical',
            'Sub Department',
            'Zone',
            'DrAccount',
            'CrAccount',
            'Amount',
            'Reference',
            'TDSBillAmount',
            'TDS',
            'TDSPer'
        ];

        // Prepare CSV content
        $csv_data = [];
        $csv_data[] = implode(',', array_map(function ($header) {
            return '"' . str_replace('"', '""', $header) . '"';
        }, $headers));

        // Add data rows
        foreach ($results as $row) {
            $row_data = [];
            foreach ($headers as $header) {
                $value = isset($row[$header]) ? $row[$header] : '';
                $value = is_null($value) ? '' : $value;
                $row_data[] = '"' . str_replace('"', '""', $value) . '"';
            }
            $csv_data[] = implode(',', $row_data);
        }

        // Combine all CSV rows
        $csv_content = implode("\n", $csv_data);

        // Set file name and force download
        $file_name = 'Nova_Scan_Export_' . date('Ymd_His') . '.csv';
        force_download($file_name, $csv_content);
    }
    public function export_cash_payment()
    {
        $this->load->helper('file');
        $filename = 'cash_payment_export_' . date('Ymd') . '.csv';
        $doctype = $this->input->get('doctype') ?? '';
        $from_date = $this->input->get('from_date') ?? '';
        $to_date = $this->input->get('to_date') ?? '';

        $data = $this->Punch_model->get_export_data($doctype, $from_date, $to_date);

        $output = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=\"$filename\"");


        $columns = ['DocNo', 'Date', 'Time', 'CashBankAC', 'Business Entity', 'Narration', 'Favouring', 'TDS JV No', 'Cost Center', 'location_id', 'Crop', 'Activity', 'State', 'Category', 'Region', 'Department', 'PMT Category', 'Business Unit', 'Account', 'Amount', 'Reference', 'Remarks', 'TDS'];
        fputcsv($output, $columns);


        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }
}
