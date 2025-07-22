<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DMSPunchingController extends CI_Controller
{
    protected $year_id;
    function __construct()
    {
        parent::__construct();
        $this->load->model("Punch_model");
        $this->year_id = $this->session->userdata('year_id') ?? ($this->db->select('id')->from('financial_years')->where('is_current', 1)->get()->row()->id ?? null);
    }
    public function punch_file()
    {
        if (!getRoutePermission("punch_file")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $this->data["main"] = "punch/punchfile";
        $this->data["scanfile_list"] = $this->Punch_model->get_file_for_punch();
        $this->load->view("layout/template", $this->data);
    }
    public function punch_entry($scan_id = null, $doc_type_id = null)
    {
        $punch_table = "y" . $this->year_id . "_punchdata_" . $doc_type_id;
        $data = $this->get_common_punch_data($scan_id, $doc_type_id, $punch_table);
        $data["user_permission"] = $this->Punch_model->checkUserPermission($this->session->userdata("user_id"));
        $mainRecord = $this->get_additional_information($scan_id);
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
    private function get_common_punch_data($scan_id, $doc_type_id, $punch_table)
    {
        $document_name = $this->customlib->getDocumentName($scan_id);
        $doc_type_name = $this->customlib->getDocType($doc_type_id);
        $docTypeViews = [1 => ["view" => "two_four_wheeler"], 6 => ["view" => "cash_deposit_withdrawals"], 7 => ["view" => "cash_voucher"], 13 => ["view" => "electricity_bill"], 17 => ["view" => "hired_vehicle"], 22 => ["view" => "insurance_policy"], 23 => ["view" => "invoice"], 28 => ["view" => "lodging"], 29 => ["view" => "meals"], 42 => ["view" => "telephone_bill"], 43 => ["view" => "vehicle_fule"], 44 => ["view" => "vehicle_maintenance"], 46 => ["view" => "gst_challan"], 50 => ["view" => "machine_operation"], 52 => ["view" => "rail"], 54 => ["view" => "sale_bill"],];
        foreach ($docTypeViews as $id => &$config) {
            $config['data_method'] = "get_punch_data_$id";
        }
        unset($config);
        return ["scan_id" => $scan_id, "doc_type_id" => $doc_type_id, $punch_table, "document_name" => $document_name, "doc_type_name" => $doc_type_name, "doc_config" => $docTypeViews[$doc_type_id] ?? null];
    }
    public function get_additional_information($scan_id)
    {
        $main_tbl = "y{$this->year_id}_tbl_additional_information";
        $item_tbl = "y{$this->year_id}_tbl_additional_information_items";
        $this->db->select("ai.*, be.business_entity_name, td.section");
        $this->db->from($main_tbl . " ai");
        $this->db->join("master_business_entity be", "be.business_entity_id = ai.business_entity_id", "left");
        $this->db->join("master_tds_sections td", "td.id = ai.tds_section_id", "left");
        $this->db->where("ai.scan_id", $scan_id);
        $mainRecord = $this->db->get()->row_array();
        $this->db->select("aii.*, cc.name as cost_center_name, d.department_name, bu.business_unit_name, r.region_name, s.state_name, " . "l.location_name, c.category_name, cr.crop_name, a.activity_name, " . "da.account_name as debit_account, ca.account_name as credit_account, pm.payment_term_name as payment_term, " . "f.function_name, v.vertical_name, sd.sub_department_name, z.zone_name");
        $this->db->from($item_tbl . " aii");
        $this->db->join("master_cost_center cc", "cc.id = aii.cost_center_id", "left");
        $this->db->join("core_department d", "d.api_id = aii.department_id", "left");
        $this->db->join("core_business_unit bu", "bu.api_id = aii.business_unit_id", "left");
        $this->db->join("core_region r", "r.api_id = aii.region_id", "left");
        $this->db->join("core_state s", "s.api_id = aii.state_id", "left");
        $this->db->join("master_work_location l", "l.location_id = aii.location_id", "left");
        $this->db->join("master_category c", "c.category_id = aii.category_id", "left");
        $this->db->join("core_crop cr", "cr.api_id = aii.crop_id", "left");
        $this->db->join("core_activity a", "a.api_id = aii.activity_id", "left");
        $this->db->join("master_account_ledger da", "da.id = aii.debit_account_id", "left");
        $this->db->join("master_account_ledger ca", "ca.id = aii.credit_account_id", "left");
        $this->db->join("payment_term_master pm", "pm.id = aii.payment_term_id", "left");
        $this->db->join("core_org_function f", "f.api_id = aii.function_id", "left");
        $this->db->join("core_vertical v", "v.api_id = aii.vertical_id", "left");
        $this->db->join("core_sub_department sd", "sd.api_id = aii.sub_department_id", "left");
        $this->db->join("core_zone z", "z.api_id = aii.zone_id", "left");
        $this->db->where("aii.scan_id", $scan_id);
        $items = $this->db->get()->result_array();
        $mainRecord["items"] = $items;
        return $mainRecord;
    }
    private function get_document_number($punch_table, $doc_type_id)
    {
        return "CASH/" . date("y-m") . "/" . str_pad($this->db->where("doctype_id", $doc_type_id)->where("MONTH(created_at)", date("m"))->where("YEAR(created_at)", date("Y"))->count_all_results($punch_table) + 1, 4, "0", STR_PAD_LEFT);
    }
    private function get_tdsjv_no($punch_table, $doc_type_id)
    {
        $last_created_at = $this->db->select("created_at")->where("doctype_id", $doc_type_id)->where("MONTH(created_at)", date("m"))->where("YEAR(created_at)", date("Y"))->order_by("created_at", "DESC")->limit(1)->get($punch_table)->row()->created_at ?? date("Y-m");
        return "TDSCASH/" . date("Y-m", strtotime($last_created_at)) . "/" . str_pad($this->db->where("doctype_id", $doc_type_id)->where("MONTH(created_at)", date("m"))->where("YEAR(created_at)", date("Y"))->count_all_results($punch_table) + 1, 4, "0", STR_PAD_LEFT);
    }
    private function get_punch_data_23($scan_id, $doc_type_id, $punch_table)
    {
        return ["rec" => $this->customlib->getScanData($scan_id), "punch_detail" => $this->db->get_where($punch_table, ["scan_id" => $scan_id])->row(), "firm" => $this->db->get_where("master_firm", ["status" => "A"])->result_array(), "company_list" => $this->customlib->getCompanyList(), "file_list" => $this->customlib->getFileList(), "ledger_list" => $this->customlib->getLedgerList(), "category_list" => $this->customlib->getCategoryList(), "item_list" => $this->customlib->getItemList(), "locationlist" => $this->customlib->getWorkLocationList(), "temp_punch_detail" => $this->BaseModel->getData("ext_tempdata_{$doc_type_id}", ["scan_id" => $scan_id])->row(), "document_number" => $this->get_document_number($punch_table, $doc_type_id), "tdsJvNo" => $this->get_tdsjv_no($punch_table, $doc_type_id),];
    }
    public function get_approvers($items, $bill_type = null)
    {
        $grouped = [];
        $totalAmount = 0;
        foreach ($items as $item) {
            $groupKeyArray = array_diff_key($item, ['amount' => '']);
            $groupKey = md5(json_encode($groupKeyArray));
            if (!isset($grouped[$groupKey])) {
                $grouped[$groupKey] = $groupKeyArray;
                $grouped[$groupKey]['amount'] = $item['amount'];
            } else {
                $grouped[$groupKey]['amount'] += $item['amount'];
            }
            $totalAmount += $item['amount'];
        }
        $final = [];
        foreach ($grouped as $row) {
            foreach ($row as $key => $value) {
                if ($key !== 'amount') {
                    $final[$key][$value] = $value;
                }
            }
        }
        foreach ($final as $key => &$value) {
            $value = implode(',', array_values($value));
        }
        $final['amount'] = $totalAmount;
        $finalMapped = [
            'function' => $final['function_id'] ?? '',
            'vertical' => $final['vertical_id'] ?? '',
            'department' => $final['department_id'] ?? '',
            'sub_department' => $final['sub_department_id'] ?? '',
            'ledger' => $final['ledger_id'] ?? '',
            'subledger' => $final['subledger_id'] ?? '',
            'crop' => $final['crop_id'] ?? '',
            'activity' => $final['activity_id'] ?? '',
            'location' => $final['location_id'] ?? '',
            'zone' => $final['production_zone_id'] ?? ($final['sales_zone_id'] ?? ''),
            'sales_region' => $final['sales_region_id'] ?? '',
            'business_unit' => $final['business_unit_id'] ?? '',
            'amount' => $final['amount'] ?? 0,
        ];
        $this->db->select('id, l1_approver, l2_approver, l3_approver');
        $this->db->from('tbl_approval_matrix');
        $this->db->where('status', '1');
        if (!empty($bill_type)) {
            $this->db->where('bill_type', $bill_type);
        }
        if (!empty($finalMapped['amount'])) {
            $this->db->where('(amount_min IS NULL OR amount_min = 0 OR amount_min <= ' . $finalMapped['amount'] . ')', NULL, FALSE);
            $this->db->where('(amount_max IS NULL OR amount_max = 0 OR amount_max >= ' . $finalMapped['amount'] . ')', NULL, FALSE);
        }
        $fieldGroups = [
            'organizational' => [
                'priority' => 4,
                'fields' => [
                    'function' => 4,
                    'vertical' => 3,
                    'department' => 2,
                    'sub_department' => 1
                ]
            ],
            'financial' => [
                'priority' => 3,
                'fields' => [
                    'ledger' => 2,
                    'subledger' => 1
                ]
            ],
            'operational' => [
                'priority' => 2,
                'fields' => [
                    'crop' => 2,
                    'activity' => 1
                ]
            ],
            'geographical' => [
                'priority' => 1,
                'fields' => [
                    'business_unit' => 4,
                    'zone' => 3,
                    'sales_region' => 2,
                    'location' => 1,
                ]
            ]
        ];
        $conditions = [];
        $matchConditions = [];
        $maxGroupPriority = max(array_column($fieldGroups, 'priority'));
        foreach ($fieldGroups as $groupName => $group) {
            $groupPriority = $group['priority'];
            foreach ($group['fields'] as $key => $fieldPriority) {
                $value = $finalMapped[$key];
                $weight = ($maxGroupPriority - $groupPriority + 1) * (max(array_values($group['fields'])) - $fieldPriority + 1);
                $conditions[] = "FIND_IN_SET('{$value}', `$key`) != 0";
                $matchConditions[] = "CASE WHEN (`$key` = '' AND '{$value}' = '') OR FIND_IN_SET('{$value}', `$key`) != 0 THEN {$weight} ELSE 0 END";
            }
        }
        $scoreExpression = implode(' + ', $matchConditions) . ' AS match_score';

        if (!empty($conditions)) {
            $this->db->where('(' . implode(' OR ', $conditions) . ')', NULL, FALSE);
        } else {
            $this->db->where('1=1', NULL, FALSE);
        }

        $this->db->select($scoreExpression, FALSE);
        $this->db->order_by('match_score', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();
        $result = $query->row_array();
        return [
            'finalMapped' => $finalMapped,
            'approvers' => $result ? $result : [
                'l1_approver' => 153,
                'l2_approver' => null,
                'l3_approver' => null
            ]
        ];
    }
    public function save_punch_details()
    {
        if (!isset($this->year_id)) {
            show_error("Year ID is not defined", 500);
        }
        $DocTypeId = (int) $this->input->post("DocTypeId");
        $functionName = "process_punch_data_{$DocTypeId}";
        $punch_table = "y{$this->year_id}_punchdata_{$DocTypeId}";
        $punch_table_detail = "y{$this->year_id}_punchdata_{$DocTypeId}_details";
        if (method_exists($this, $functionName)) {
            $result = $this->$functionName($this->input->post());
        } else {
            show_error("Handler not defined for DocTypeId {$DocTypeId}", 500);
        }
        $data = $result;
        $main = $data["main"];
        $items = $data["items"];
        $scan_id = $main["scan_id"];
        $this->db->trans_start();
        $this->db->trans_strict(true);
        $is_update = $this->db->where("scan_id", $scan_id)->count_all_results($punch_table) > 0;
        if ($is_update) {
            $this->db->where("scan_id", $scan_id)->update($punch_table, $main);
            $FileID = $this->db->select("id")->where("scan_id", $scan_id)->get($punch_table)->row("id");
            $this->db->where("FileID", $FileID)->update("sub_punchfile", ["Amount" => "-" . (float) ($this->input->post("Grand_Total") ?? 0), "Comment" => $this->db->escape_str($this->input->post("Remark") ?? "")]);
        } else {
            $this->db->insert($punch_table, $main);
            $insert_id = $this->db->insert_id();
            $this->db->insert("sub_punchfile", ["FileID" => $insert_id, "Amount" => "-" . (float) ($this->input->post("Grand_Total") ?? 0), "Comment" => $this->db->escape_str($this->input->post("Remark") ?? "")]);
        }
        if (!empty($items)) {
            $this->db->where("scan_id", $scan_id)->delete($punch_table_detail);
            $this->db->insert_batch($punch_table_detail, $items);
        }
        $this->db->where("scan_id", $scan_id)->delete("y{$this->year_id}_tbl_additional_information_details");
        $additional_items = [];
        $item_count = count($this->input->post("ledger_id") ?? []);
        for ($i = 0; $i < $item_count; $i++) {
            $ledger_id = $this->input->post("ledger_id")[$i] ?? null;
            $add_amount = $this->input->post("add_amount")[$i] ?? null;
            if ($add_amount !== null && $ledger_id !== null && $add_amount > 0 && $ledger_id > 0) {
                $additional_items[] = [
                    'scan_id' => $scan_id,
                    'ledger_id' => $ledger_id,
                    'subledger_id' => $this->input->post("subledger_id")[$i] ?? null,
                    'vertical_id' => $this->input->post("vertical_id")[$i] ?? null,
                    'department_id' => $this->input->post("department_id")[$i] ?? null,
                    'sub_department_id' => $this->input->post("sub_department_id")[$i] ?? null,
                    'activity_id' => $this->input->post("activity_id")[$i] ?? null,
                    'crop_id' => $this->input->post("crop_id")[$i] ?? null,
                    'business_unit_id' => $this->input->post("business_unit_id")[$i] ?? null,
                    'territory_id' => $this->input->post("territory_id")[$i] ?? null,
                    'sales_region_id' => $this->input->post("sales_region_id")[$i] ?? null,
                    'sales_zone_id' => $this->input->post("sales_zone_id")[$i] ?? null,
                    'production_zone_id' => $this->input->post("production_zone_id")[$i] ?? null,
                    'season_id' => $this->input->post("season_id")[$i] ?? null,
                    'acrage_id' => $this->input->post("acrage_id")[$i] ?? null,
                    'location_id' => $this->input->post("location_id")[$i] ?? null,
                    'amount' => $add_amount,
                ];
            }
        }

        if (!empty($additional_items)) {
            $this->db->insert_batch("y{$this->year_id}_tbl_additional_information_details", $additional_items);
        }
        if ($is_update) {
            $this->db->where("scan_id", $scan_id)->update("y{$this->year_id}_scan_file", ["is_rejected" => "N", "finance_punch_action_status" => "N", "reject_date" => null, "has_edit_permission" => "N"]);
        }
        $this->db->trans_complete();
        if ($this->input->post("submit")) {
            $approvers = $this->get_approvers($additional_items, $DocTypeId);
            $this->customlib->update_file_path($scan_id, $approvers['approvers']);
        }
        if ($this->db->trans_status() === false) {
            $this->session->set_flashdata("message", '<div class="alert alert-danger text-left">Something went wrong</div>');
        } else {
            $msg = $this->input->post("submit") ? "Invoice added successfully" : "Data saved in Draft";
            $this->session->set_flashdata("message", "<div class='alert alert-success text-left'>{$msg}</div>");
        }
        redirect($this->input->post("submit") ? "punch" : $_SERVER["HTTP_REFERER"]);
    }
    private function process_punch_data_1($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $EmployeeID = (int) ($post["Employee"] ?? 0);
        $Emp_Code = $this->db->escape_str($post["Emp_Code"] ?? "");
        $DocTypeId = 1;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "bill_date" => $this->db->escape_str($post["Bill_Date"] ?? ""), "employee_name" => $EmployeeID, "emp_code" => $Emp_Code, "vehicle_no" => $this->db->escape_str($post["Vehicle_No"] ?? ""), "vehicle_type" => $this->db->escape_str($post["Vehicle_Type"] ?? ""), "rs_km" => (float) ($post["Rate"] ?? 0), "total_run_km" => (float) ($post["Total_KM"] ?? 0), "location" => $this->db->escape_str($post["Location"] ?? ""), "round_off_type" => $this->db->escape_str($post["plus_minus"] ?? ""), "total" => (float) ($post["Total_Amount"] ?? 0), "grand_total" => (float) ($post["Grand_Total"] ?? 0), "total_discount" => (float) ($post["Total_Discount"] ?? 0), "remark_comment" => $this->db->escape_str($post["Remark"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $items = [];
        $count = count($post["Dist_Opening"] ?? []);
        for ($i = 0; $i < $count; $i++) {
            $items[] = ["scan_id" => $scan_id, "opening_km" => (float) ($post["Dist_Opening"][$i] ?? 0), "closing_km" => (float) ($post["Dist_Closing"][$i] ?? 0), "total_km" => (float) ($post["Km"][$i] ?? 0), "amount" => (float) ($post["Amount"][$i] ?? 0),];
        }
        $scan_data = ['bill_number' => $this->db->escape_str($post["Vehicle_No"] ?? ""), 'bill_date' => $this->db->escape_str($post["Bill_Date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $items];
    }
    private function process_punch_data_6($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 6;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "type" => $this->db->escape_str($post["Type"] ?? ""), "date" => $this->db->escape_str($post["Date"] ?? ""), "invoice_number" => $this->db->escape_str($post["invoice_number"] ?? ""), "bank_name" => $this->db->escape_str($post["Bank_Name"] ?? ""), "branch" => $this->db->escape_str($post["Branch"] ?? ""), "account_no" => $this->db->escape_str($post["Account_No"] ?? ""), "beneficiary_name" => $this->db->escape_str($post["Beneficiary_Name"] ?? ""), "amount" => $this->db->escape_str($post["Amount"] ?? ""), "remark_comment" => $this->db->escape_str($post["Remark"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $items = [];
        $scan_data = ['bill_number' => "", 'bill_date' => $this->db->escape_str($post["invoice_number"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $items];
    }
    private function process_punch_data_7($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 7;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "company_name" => $this->db->escape_str($post["CompanyID"] ?? ""), "voucher_no" => $this->db->escape_str($post["Voucher_No"] ?? ""), "voucher_date" => $this->db->escape_str($post["Voucher_Date"] ?? ""), "location" => $this->db->escape_str($post["Location"] ?? ""), "payee" => $this->db->escape_str($post["Payee"] ?? ""), "payer" => $this->db->escape_str($post["Payer"] ?? ""), "amount" => (float) ($post["Amount"] ?? 0), "particular" => $this->db->escape_str($post["Particular"] ?? ""), "remark_comment" => $this->db->escape_str($post["Remark"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $items = [];
        $scan_data = ['bill_number' => $this->db->escape_str($post["Voucher_No"] ?? ""), 'bill_date' => $this->db->escape_str($post["Voucher_Date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $items];
    }
    private function process_punch_data_13($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 13;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "location" => $this->db->escape_str($post["Location"] ?? ""), "payment_date" => $this->db->escape_str($post["PaymentDate"] ?? ""), "biller_name" => $this->db->escape_str($post["Biller_Name"] ?? ""), "business_partner_no" => $this->db->escape_str($post["BP_No"] ?? ""), "bill_period" => $this->db->escape_str($post["Period"] ?? ""), "meter_number" => $this->db->escape_str($post["Meter_No"] ?? ""), "bill_date" => $this->db->escape_str($post["Bill_Date"] ?? ""), "bill_no" => $this->db->escape_str($post["Bill_No"] ?? ""), "previous_meter_reading" => $this->db->escape_str($post["Previous_Reading"] ?? ""), "current_meter_reading" => $this->db->escape_str($post["Current_Reading"] ?? ""), "unit_consumed" => $this->db->escape_str($post["Unit_Consumed"] ?? ""), "last_date_of_payment" => $this->db->escape_str($post["Last_Date"] ?? ""), "payment_mode" => $this->db->escape_str($post["Payment_Mode"] ?? ""), "bill_amount" => $this->db->escape_str($post["Bill_Amount"] ?? ""), "payment_amount" => $this->db->escape_str($post["Payment_Amount"] ?? ""), "remark_comment" => $this->db->escape_str($post["Remark"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $scan_data = ['bill_number' => $this->db->escape_str($post["Bill_No"] ?? ""), 'bill_date' => $this->db->escape_str($post["Bill_Date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        $items = [];
        return ["main" => $mainData, "items" => $items];
    }
    private function process_punch_data_17($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 17;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "agency_name" => $this->db->escape_str($post["agency_name"] ?? ""), "agency_address" => $this->db->escape_str($post["agency_address"] ?? ""), "billing_name" => $this->db->escape_str($post["billing_name"] ?? ""), "billing_address" => $this->db->escape_str($post["billing_address"] ?? ""), "employee_name" => $this->db->escape_str($post["employee_name"] ?? ""), "emp_code" => $this->db->escape_str($post["emp_code"] ?? ""), "vehicle_no" => $this->db->escape_str($post["vehicle_no"] ?? ""), "location" => $this->db->escape_str($post["location"] ?? ""), "invoice_no" => $this->db->escape_str($post["invoice_no"] ?? ""), "invoice_date" => $this->db->escape_str($post["invoice_date"] ?? ""), "per_km_rate" => (float) ($post["per_km_rate"] ?? 0.0), "booking_date" => $this->db->escape_str($post["booking_date"] ?? ""), "end_date" => $this->db->escape_str($post["end_date"] ?? ""), "start_reading" => (int) ($post["start_reading"] ?? 0), "closing_reading" => (int) ($post["closing_reading"] ?? 0), "total_km" => (int) ($post["total_km"] ?? 0), "other_charges" => (float) ($post["other_charges"] ?? 0.0), "total_amount" => (float) ($post["total_amount"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $scan_data = ['bill_number' => $this->db->escape_str($post["invoice_no"] ?? ""), 'bill_date' => $this->db->escape_str($post["invoice_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => []];
    }
    private function process_punch_data_20($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 20;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "section" => $this->db->escape_str($post["section"] ?? ""), "company" => $this->db->escape_str($post["company"] ?? ""), "nature_of_payment" => $this->db->escape_str($post["nature_of_payment"] ?? ""), "assessment_year" => $this->db->escape_str($post["assessment_year"] ?? ""), "bank_name" => $this->db->escape_str($post["bank_name"] ?? ""), "bsr_code" => $this->db->escape_str($post["bsr_code"] ?? ""), "challan_no" => $this->db->escape_str($post["challan_no"] ?? ""), "challan_date" => $this->db->escape_str($post["challan_date"] ?? ""), "bank_reference_no" => $this->db->escape_str($post["bank_reference_no"] ?? ""), "amount" => (float) ($post["amount"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $scan_data = ['bill_number' => $this->db->escape_str($post["challan_no"] ?? ""), 'bill_date' => $this->db->escape_str($post["challan_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => []];
    }
    private function process_punch_data_22($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 22;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "insurance_type" => $this->db->escape_str($post["insurance_type"] ?? ""), "insurance_company" => $this->db->escape_str($post["insurance_company"] ?? ""), "policy_number" => $this->db->escape_str($post["policy_number"] ?? ""), "policy_date" => $this->db->escape_str($post["policy_date"] ?? ""), "from_date" => $this->db->escape_str($post["from_date"] ?? ""), "to_date" => $this->db->escape_str($post["to_date"] ?? ""), "vehicle_no" => $this->db->escape_str($post["vehicle_no"] ?? ""), "location" => $this->db->escape_str($post["location"] ?? ""), "premium_amount" => (float) ($post["premium_amount"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $scan_data = ['bill_number' => $this->db->escape_str($post["policy_number"] ?? ""), 'bill_date' => $this->db->escape_str($post["policy_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => []];
    }
    private function process_punch_data_46($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 46;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "cpin" => $this->db->escape_str($post["cpin"] ?? ""), "deposit_date" => $this->db->escape_str($post["deposit_date"] ?? ""), "cin" => $this->db->escape_str($post["cin"] ?? ""), "bank_name" => $this->db->escape_str($post["bank_name"] ?? ""), "brn" => $this->db->escape_str($post["brn"] ?? ""), "gstin" => $this->db->escape_str($post["gstin"] ?? ""), "email_id" => $this->db->escape_str($post["email_id"] ?? ""), "mobile_no" => $this->db->escape_str($post["mobile_no"] ?? ""), "company_name" => $this->db->escape_str($post["company_name"] ?? ""), "address" => $this->db->escape_str($post["address"] ?? ""), "total_challan_amount" => (float) ($post["total_challan_amount"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_at" => date("Y-m-d H:i:s"),];
        $items = [];
        if (!empty($post["Particular"]) && is_array($post["Particular"])) {
            foreach ($post["Particular"] as $key => $Particular) {
                if (!empty($Particular)) {
                    $items[] = ["scan_id" => $scan_id, "particular" => $this->db->escape_str($Particular), "tax" => (float) ($post["Tax"][$key] ?? 0.0), "interest" => (float) ($post["Interest"][$key] ?? 0.0), "penalty" => (float) ($post["Penalty"][$key] ?? 0.0), "fees" => (float) ($post["Fees"][$key] ?? 0.0), "other" => (float) ($post["Other"][$key] ?? 0.0), "total" => (float) ($post["Total"][$key] ?? 0.0),];
                }
            }
        }
        $scan_data = ['bill_number' => $this->db->escape_str($post["cpin"] ?? ""), 'bill_date' => $this->db->escape_str($post["deposit_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $items];
    }
    private function process_punch_data_27($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 27;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "mode" => $this->db->escape_str($post["mode"] ?? ""), "invoice_number" => $this->db->escape_str($post["invoice_number"] ?? ""), "invoice_date" => $this->db->escape_str($post["invoice_date"] ?? ""), "location" => $this->db->escape_str($post["location"] ?? ""), "employee_name" => $this->db->escape_str($post["employee_name"] ?? ""), "emp_code" => $this->db->escape_str($post["emp_code"] ?? ""), "vehicle_no" => $this->db->escape_str($post["vehicle_no"] ?? ""), "month" => $this->db->escape_str($post["month"] ?? ""), "calculation_base" => $this->db->escape_str($post["calculation_base"] ?? ""), "per_km_rate" => (float) ($post["per_km_rate"] ?? 0.0), "total_km" => (float) ($post["total_km"] ?? 0.0), "total" => (float) ($post["total_amount"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $items = [];
        if (!empty($post["date"]) && is_array($post["date"])) {
            foreach ($post["date"] as $key => $date) {
                if (!empty($date)) {
                    $items[] = ["scan_id" => $scan_id, "travel_date" => $this->db->escape_str($date), "opening_reading" => (float) ($post["dist_opening"][$key] ?? 0.0), "closing_reading" => (float) ($post["dist_closing"][$key] ?? 0.0), "total_km" => (float) ($post["km"][$key] ?? 0.0), "amount" => (float) ($post["amount"][$key] ?? 0.0),];
                }
            }
        }
        $scan_data = ['bill_number' => $this->db->escape_str($post["invoice_number"] ?? ""), 'bill_date' => $this->db->escape_str($post["invoice_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $items];
    }
    private function process_punch_data_28($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 28;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "location" => $this->db->escape_str($post["location"] ?? ""), "bill_no" => $this->db->escape_str($post["bill_no"] ?? ""), "bill_date" => $this->db->escape_str($post["bill_date"] ?? ""), "billing_name" => $this->db->escape_str($post["billing_name"] ?? ""), "billing_address" => $this->db->escape_str($post["billing_address"] ?? ""), "hotel_name" => $this->db->escape_str($post["hotel_name"] ?? ""), "hotel_address" => $this->db->escape_str($post["hotel_address"] ?? ""), "billing_instruction" => $this->db->escape_str($post["billing_instruction"] ?? ""), "booking_id" => $this->db->escape_str($post["booking_id"] ?? ""), "check_in" => $this->db->escape_str($post["check_in"] ?? ""), "check_out" => $this->db->escape_str($post["check_out"] ?? ""), "duration_of_stay" => (int) ($post["duration_of_stay"] ?? 0), "number_of_rooms" => (int) ($post["number_of_rooms"] ?? 0), "room_type" => $this->db->escape_str($post["room_type"] ?? ""), "meal_plan" => $this->db->escape_str($post["meal_plan"] ?? ""), "rate" => (float) ($post["rate"] ?? 0.0), "amount" => (float) ($post["amount"] ?? 0.0), "other_charges" => (float) ($post["other_charges"] ?? 0.0), "discount" => (float) ($post["discount"] ?? 0.0), "gst" => (float) ($post["gst"] ?? 0.0), "grand_total" => (float) ($post["grand_total"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $items = [];
        if (!empty($post["employee"]) && is_array($post["employee"])) {
            foreach ($post["employee"] as $key => $employee) {
                if (!empty($employee)) {
                    $items[] = ["scan_id" => $scan_id, "emp_name" => $this->db->escape_str($employee), "emp_code" => $this->db->escape_str($post["emp_code"][$key] ?? "")];
                }
            }
        }
        $scan_data = ['bill_number' => $this->db->escape_str($post["bill_no"] ?? ""), 'bill_date' => $this->db->escape_str($post["bill_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $items];
    }
    private function process_punch_data_47($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 47;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "voucher_no" => $this->db->escape_str($post["voucher_no"] ?? ""), "payment_date" => $this->db->escape_str($post["payment_date"] ?? ""), "payee" => $this->db->escape_str($post["payee"] ?? ""), "location" => $this->db->escape_str($post["location"] ?? ""), "particular" => $this->db->escape_str($post["particular"] ?? ""), "total_amount" => (float) ($post["total_amount"] ?? 0.0), "from_date" => $this->db->escape_str($post["from_date"] ?? ""), "to_date" => $this->db->escape_str($post["to_date"] ?? ""), "sub_total" => (float) ($post["sub_total"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $items = [];
        if (!empty($post["head"]) && is_array($post["head"])) {
            foreach ($post["head"] as $key => $head) {
                if (!empty($head)) {
                    $items[] = ["scan_id" => $scan_id, "head" => $this->db->escape_str($head), "amount" => (float) ($post["amount"][$key] ?? 0.0)];
                }
            }
        }
        $scan_data = ['bill_number' => $this->db->escape_str($post["voucher_no"] ?? ""), 'bill_date' => $this->db->escape_str($post["payment_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $items];
    }
    private function process_punch_data_48($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 48;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "location" => $this->db->escape_str($post["Location"] ?? ""), "company_name" => $this->db->escape_str($post["CompanyID"] ?? ""), "voucher_no" => $this->db->escape_str($post["Receipt_No"] ?? ""), "date" => $this->db->escape_str($post["Receipt_Date"] ?? ""), "receiver_name" => $this->db->escape_str($post["Receiver"] ?? ""), "received_from" => $this->db->escape_str($post["ReceivedFrom"] ?? ""), "amount" => (float) ($post["Amount"] ?? 0), "particular" => $this->db->escape_str($post["Particular"] ?? ""), "remark_comment" => $this->db->escape_str($post["Remark"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $items = [];
        $scan_data = ['bill_number' => $this->db->escape_str($post["Receipt_No"] ?? ""), 'bill_date' => $this->db->escape_str($post["Receipt_Date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $items];
    }
    private function process_punch_data_51($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 51;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "mode" => $this->db->escape_str($post["mode"] ?? ""), "agent_name" => $this->db->escape_str($post["agent_name"] ?? ""), "pnr_number" => $this->db->escape_str($post["pnr_number"] ?? ""), "date_of_booking" => $this->db->escape_str($post["date_of_booking"] ?? ""), "journey_date" => $this->db->escape_str($post["journey_date"] ?? ""), "air_line" => $this->db->escape_str($post["air_line"] ?? ""), "ticket_number" => $this->db->escape_str($post["ticket_number"] ?? ""), "journey_from" => $this->db->escape_str($post["journey_from"] ?? ""), "journey_upto" => $this->db->escape_str($post["journey_upto"] ?? ""), "travel_class" => $this->db->escape_str($post["travel_class"] ?? ""), "location" => $this->db->escape_str($post["location_id"] ?? ""), "passenger_details" => $this->db->escape_str($post["passenger_details"] ?? ""), "base_fare" => (float) ($post["base_fare"] ?? 0.0), "gst" => (float) ($post["gst"] ?? 0.0), "fees_surcharge" => (float) ($post["fees_surcharge"] ?? 0.0), "cute_charge" => $this->db->escape_str($post["cute_charge"] ?? ""), "extra_luggage" => $this->db->escape_str($post["extra_luggage"] ?? ""), "other" => $this->db->escape_str($post["other"] ?? ""), "total_fare" => (float) ($post["total_fare"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $items = [];
        if (!empty($post["Employee"]) && is_array($post["Employee"])) {
            foreach ($post["Employee"] as $key => $employee_id) {
                if (!empty($employee_id)) {
                    $items[] = ["scan_id" => $scan_id, "emp_name" => (int) $employee_id, "emp_code" => $this->db->escape_str($post["EmpCode"][$key] ?? "")];
                }
            }
        }
        $scan_data = ['bill_number' => $this->db->escape_str($post["pnr_number"] ?? ""), 'bill_date' => $this->db->escape_str($post["date_of_booking"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $items];
    }
    private function process_punch_data_23($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $From = (int) ($post["From"] ?? 0);
        $To = (int) ($post["To"] ?? 0);
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $this->db->escape_str($this->customlib->getDocType(23)), "doctype_id" => 23, "invoice_date" => $this->db->escape_str($post["Bill_Date"] ?? ""), "invoice_no" => $this->db->escape_str($post["Bill_No"] ?? ""), "mode_of_payment" => $this->db->escape_str($post["Payment_Mode"] ?? ""), "buyer" => $From, "vendor" => $To, "buyer_address" => $this->db->escape_str($post["Buyer_Address"] ?? ""), "vendor_address" => $this->db->escape_str($post["Vendor_Address"] ?? ""), "buyers_order_no" => $this->db->escape_str($post["Buyer_Order"] ?? ""), "buyers_order_date" => $this->db->escape_str($post["Buyer_Order_Date"] ?? ""), "dispatch_through" => $this->db->escape_str($post["Dispatch_Trough"] ?? ""), "delivery_note_date" => $this->db->escape_str($post["Delivery_Note_Date"] ?? ""), "voucher_type_category" => $this->db->escape_str($post["Category"] ?? ""), "lr_number" => $this->db->escape_str($post["LR_Number"] ?? ""), "lr_date" => $this->db->escape_str($post["LR_Date"] ?? ""), "cartoon_number" => $this->db->escape_str($post["Cartoon_Number"] ?? ""), "sub_total" => (float) ($post["Sub_Total"] ?? 0), "total" => (float) ($post["Total"] ?? 0), "grand_total" => (float) ($post["Grand_Total"] ?? 0), "total_discount" => (float) ($post["Total_Discount"] ?? 0), "tcs_percent" => (float) ($post["TCS"] ?? 0), "remark_comment" => $this->db->escape_str($post["Remark"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $invoiceDetails = [];
        $count = count($post["Particular"] ?? []);
        for ($i = 0; $i < $count; $i++) {
            $unit = isset($post["Unit"][$i]) && $post["Unit"][$i] !== "" ? $this->db->escape_str($post["Unit"][$i]) : "PCS";
            $invoiceDetails[] = ["scan_id" => $scan_id, "particular" => $this->db->escape_str($post["Particular"][$i] ?? ""), "hsn" => $this->db->escape_str($post["HSN"][$i] ?? ""), "qty" => (float) ($post["Qty"][$i] ?? 0), "unit" => $unit, "mrp" => (float) ($post["MRP"][$i] ?? 0), "discount_in_mrp" => (float) ($post["Discount"][$i] ?? 0), "price" => (float) ($post["Price"][$i] ?? 0), "amount" => (float) ($post["Amount"][$i] ?? 0), "gst" => (float) ($post["GST"][$i] ?? 0), "sgst" => (float) ($post["SGST"][$i] ?? 0), "igst" => (float) ($post["IGST"][$i] ?? 0), "cess" => (float) ($post["Cess"][$i] ?? 0), "total_amount" => (float) ($post["TAmount"][$i] ?? 0),];
        }
        $scan_data = ['bill_number' => $this->db->escape_str($post["Bill_No"] ?? ""), 'bill_date' => $this->db->escape_str($post["Bill_Date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $invoiceDetails];
    }
    private function process_punch_data_29($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 29;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "hotel_name" => $this->db->escape_str($post["hotel_name"] ?? ""), "bill_no" => $this->db->escape_str($post["bill_no"] ?? ""), "bill_date" => $this->db->escape_str($post["bill_date"] ?? ""), "hotel_address" => $this->db->escape_str($post["hotel_address"] ?? ""), "employee_name" => $this->db->escape_str($post["employee_name"] ?? ""), "emp_code" => $this->db->escape_str($post["emp_code"] ?? ""), "amount" => (float) ($post["amount"] ?? 0.0), "location" => $this->db->escape_str($post["location"] ?? ""), "detail" => $this->db->escape_str($post["detail"] ?? ""), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $scan_data = ['bill_number' => $this->db->escape_str($post["bill_no"] ?? ""), 'bill_date' => $this->db->escape_str($post["bill_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => []];
    }
    private function process_punch_data_31($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 31;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "company" => $this->db->escape_str($post["company"] ?? ""), "voucher_no" => $this->db->escape_str($post["voucher_no"] ?? ""), "voucher_date" => $this->db->escape_str($post["voucher_date"] ?? ""), "location" => $this->db->escape_str($post["location"] ?? ""), "vendor" => $this->db->escape_str($post["vendor"] ?? ""), "amount" => (float) ($post["amount"] ?? 0.0), "particular" => $this->db->escape_str($post["particular"] ?? ""), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $scan_data = ['bill_number' => $this->db->escape_str($post["voucher_no"] ?? ""), 'bill_date' => $this->db->escape_str($post["voucher_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => []];
    }
    private function process_punch_data_50($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 50;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "company_name" => $this->db->escape_str($post["company_name"] ?? ""), "company_address" => $this->db->escape_str($post["company_address"] ?? ""), "vendor_name" => $this->db->escape_str($post["vendor_name"] ?? ""), "vendor_address" => $this->db->escape_str($post["vendor_address"] ?? ""), "vehicle_no" => $this->db->escape_str($post["vehicle_no"] ?? ""), "vehicle_type" => $this->db->escape_str($post["vehicle_type"] ?? ""), "location" => $this->db->escape_str($post["location"] ?? ""), "invoice_date" => $this->db->escape_str($post["invoice_date"] ?? ""), "particular" => $this->db->escape_str($post["particular"] ?? ""), "hour" => (float) ($post["hour"] ?? 0.0), "trips" => $this->db->escape_str($post["trips"] ?? ""), "rate_per_trip" => (float) ($post["rate_per_trip"] ?? 0.0), "total_amount" => (float) ($post["total_amount"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $scan_data = ['bill_number' => $this->db->escape_str($post["vehicle_no"] ?? ""), 'bill_date' => $this->db->escape_str($post["invoice_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => []];
    }
    private function process_punch_data_42($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 42;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "bill_invoice_date" => $this->db->escape_str($post["bill_invoice_date"] ?? ""), "invoice_bill_no" => $this->db->escape_str($post["invoice_bill_no"] ?? ""), "biller_name" => $this->db->escape_str($post["biller_name"] ?? ""), "telephone_no" => $this->db->escape_str($post["telephone_no"] ?? ""), "invoice_period" => $this->db->escape_str($post["invoice_period"] ?? ""), "invoice_taxable_value" => (float) ($post["invoice_taxable_value"] ?? 0.0), "cgst" => (float) ($post["cgst"] ?? 0.0), "sgst" => (float) ($post["sgst"] ?? 0.0), "igst" => (float) ($post["igst"] ?? 0.0), "total_amount_due" => (float) ($post["total_amount_due"] ?? 0.0), "total_amount_outstanding" => (float) ($post["total_amount_outstanding"] ?? 0.0), "last_payment_date" => $this->db->escape_str($post["last_payment_date"] ?? ""), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $scan_data = ['bill_number' => $this->db->escape_str($post["invoice_bill_no"] ?? ""), 'bill_date' => $this->db->escape_str($post["bill_invoice_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => []];
    }
    private function process_punch_data_43($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 43;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "vendor_name" => $this->db->escape_str($post["vendor_name"] ?? ""), "billing_to" => $this->db->escape_str($post["billing_to"] ?? ""), "dealer_code" => $this->db->escape_str($post["dealer_code"] ?? ""), "invoice_no" => $this->db->escape_str($post["invoice_no"] ?? ""), "invoice_date" => $this->db->escape_str($post["invoice_date"] ?? ""), "due_date" => $this->db->escape_str($post["due_date"] ?? ""), "location" => $this->db->escape_str($post["location"] ?? ""), "vehicle_no" => $this->db->escape_str($post["vehicle_no"] ?? ""), "description" => $this->db->escape_str($post["description"] ?? ""), "liters" => (float) ($post["liters"] ?? 0.0), "per_liter_rate" => (float) ($post["per_liter_rate"] ?? 0.0), "amount" => (float) ($post["amount"] ?? 0.0), "round_off_value" => (float) ($post["round_off_value"] ?? 0.0), "round_off_type" => $this->db->escape_str($post["round_off_type"] ?? "Plus"), "grand_total" => (float) ($post["grand_total"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $scan_data = ['bill_number' => $this->db->escape_str($post["invoice_no"] ?? ""), 'bill_date' => $this->db->escape_str($post["invoice_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => []];
    }
    private function process_punch_data_44($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $From = (int) ($post["Vendor_Name"] ?? 0);
        $To = (int) ($post["Billing_To"] ?? 0);
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $this->db->escape_str($this->customlib->getDocType(23)), "doctype_id" => 44, "invoice_date" => $this->db->escape_str($post["Bill_Date"] ?? ""), "invoice_no" => $this->db->escape_str($post["InvoiceNo"] ?? ""), "vehicle_no" => $this->db->escape_str($post["VehicleRegNo"] ?? ""), "vendor_name" => $From, "billing_to" => $To, "sub_total" => (float) ($post["Sub_Total"] ?? 0), "total" => (float) ($post["Total"] ?? 0), "grand_total" => (float) ($post["Grand_Total"] ?? 0), "total_discount" => (float) ($post["Total_Discount"] ?? 0), "remark_comment" => $this->db->escape_str($post["Remark"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $invoiceDetails = [];
        $count = count($post["Particular"] ?? []);
        for ($i = 0; $i < $count; $i++) {
            $unit = isset($post["Unit"][$i]) && $post["Unit"][$i] !== "" ? $this->db->escape_str($post["Unit"][$i]) : "PCS";
            $invoiceDetails[] = ["scan_id" => $scan_id, "particular" => $this->db->escape_str($post["Particular"][$i] ?? ""), "hsn" => $this->db->escape_str($post["HSN"][$i] ?? ""), "qty" => (float) ($post["Qty"][$i] ?? 0), "unit" => $unit, "mrp" => (float) ($post["MRP"][$i] ?? 0), "discount_in_mrp" => (float) ($post["Discount"][$i] ?? 0), "price" => (float) ($post["Price"][$i] ?? 0), "amount" => (float) ($post["Amount"][$i] ?? 0), "gst" => (float) ($post["GST"][$i] ?? 0), "sgst" => (float) ($post["SGST"][$i] ?? 0), "igst" => (float) ($post["IGST"][$i] ?? 0), "cess" => (float) ($post["Cess"][$i] ?? 0), "total_amount" => (float) ($post["TAmount"][$i] ?? 0),];
        }
        $scan_data = ['bill_number' => $this->db->escape_str($post["InvoiceNo"] ?? ""), 'bill_date' => $this->db->escape_str($post["Bill_Date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $invoiceDetails];
    }
    private function process_punch_data_52($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 52;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "mode" => $this->db->escape_str($post["mode"] ?? ""), "train_number" => $this->db->escape_str($post["train_number"] ?? ""), "agent_name" => $this->db->escape_str($post["agent_name"] ?? ""), "pnr_number" => $this->db->escape_str($post["pnr_number"] ?? ""), "date_of_booking" => $this->db->escape_str($post["date_of_booking"] ?? ""), "journey_date" => $this->db->escape_str($post["journey_date"] ?? ""), "booking_id" => $this->db->escape_str($post["booking_id"] ?? ""), "transaction_id" => $this->db->escape_str($post["transaction_id"] ?? ""), "journey_from" => $this->db->escape_str($post["journey_from"] ?? ""), "journey_upto" => $this->db->escape_str($post["journey_upto"] ?? ""), "travel_class" => $this->db->escape_str($post["travel_class"] ?? ""), "quota" => $this->db->escape_str($post["quota"] ?? ""), "location" => $this->db->escape_str($post["location"] ?? ""), "passenger_details" => $this->db->escape_str($post["passenger_details"] ?? ""), "base_fare" => $this->db->escape_str($post["base_fare"] ?? ""), "gst" => $this->db->escape_str($post["gst"] ?? ""), "fees_surcharge" => $this->db->escape_str($post["fees_surcharge"] ?? ""), "other_charges" => $this->db->escape_str($post["other_charges"] ?? ""), "total_fare" => (float) ($post["total_fare"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $items = [];
        if (!empty($post["employee"]) && is_array($post["employee"])) {
            foreach ($post["employee"] as $key => $employee) {
                if (!empty($employee)) {
                    $items[] = ["scan_id" => $scan_id, "employee_name" => $this->db->escape_str($employee), "emp_code" => $this->db->escape_str($post["emp_code"][$key] ?? "")];
                }
            }
        }
        $scan_data = ['bill_number' => $this->db->escape_str($post["pnr_number"] ?? ""), 'bill_date' => $this->db->escape_str($post["date_of_booking"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $items];
    }
    private function process_punch_data_55($post)
    {
        $scan_id = (int) ($post["scan_id"] ?? 0);
        $DocTypeId = 55;
        $DocType = $this->db->escape_str($this->customlib->getDocType($DocTypeId));
        $mainData = ["scan_id" => $scan_id, "group_id" => $this->session->userdata("group_id"), "doctype" => $DocType, "doctype_id" => $DocTypeId, "agent_name" => $this->db->escape_str($post["agent_name"] ?? ""), "booking_date" => $this->db->escape_str($post["booking_date"] ?? ""), "cancelled_date" => $this->db->escape_str($post["cancelled_date"] ?? ""), "sub_total" => (float) ($post["sub_total"] ?? 0.0), "cancellation_charge" => (float) ($post["cancellation_charge"] ?? 0.0), "other_charges" => (float) ($post["other_charges"] ?? 0.0), "grand_total" => (float) ($post["grand_total"] ?? 0.0), "remark_comment" => $this->db->escape_str($post["remark_comment"] ?? ""), "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "updated_by" => $this->session->userdata("user_id"),];
        $items = [];
        if (!empty($post["employee"]) && is_array($post["employee"])) {
            foreach ($post["employee"] as $key => $employee) {
                if (!empty($employee)) {
                    $items[] = ["scan_id" => $scan_id, "employee_name" => $this->db->escape_str($employee), "pnr_number" => $this->db->escape_str($post["pnr_number"][$key] ?? ""), "amount" => (float) ($post["amount"][$key] ?? 0.0)];
                }
            }
        }
        $scan_data = ['bill_number' => "", 'bill_date' => $this->db->escape_str($post["booking_date"] ?? "")];
        $this->update_scan_basic_detail($scan_id, $scan_data);
        return ["main" => $mainData, "items" => $items];
    }
    public function update_scan_basic_detail($scan_id, $data)
    {
        return $this->db->where('scan_id', $scan_id)->update('y' . $this->year_id . '_scan_file', $data);
    }
    public function punched_files($show_all = null)
    {
        if (!getRoutePermission("punched_files")) {
            show_error('You do not have permission to access this page.', 403);
        }
        $user_id = $this->session->userdata("user_id");
        $from_date = null;
        $to_date = null;
        $from_date = $this->input->get("from_date");
        $to_date = $this->input->get("to_date");
        $punch_file_list = $this->Punch_model->get_punched_files($user_id, $from_date, $to_date, $show_all ? true : false);
        $this->data["my_punched_files"] = $punch_file_list;
        $this->data["main"] = "punch/my_punched_file";
        $this->load->view("layout/template", $this->data);
    }
}
