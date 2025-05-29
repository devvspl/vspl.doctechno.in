<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Extract_model extends CI_Model
{
    protected $year_id;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->year_id = $this->session->userdata('year_id');
    }
    public function getApiList()
    {
        $api_list = $this->db->get("ext_master_api_control")->result_array();
        foreach ($api_list as &$api) {
            $doctype_id = $api['doctype_id'];
            $tableName = "ext_tempdata_" . $doctype_id . "_details";
            $query = $this->db->query("SHOW TABLES LIKE '{$tableName}'");
            $api['has_items'] = ($query->num_rows() > 0) ? true : false;
        }
        return $api_list;
    }
    public function getFieldDetails($doctype_id, $has_items_feild)
    {
        $tableName = "";
        if ($has_items_feild == "N") {
            $tableName = "ext_tempdata_" . $doctype_id;
        } else {
            $tableName = "ext_tempdata_" . $doctype_id . "_details";
        }
        $query = $this->db->query("SHOW COLUMNS FROM `$tableName`");
        $excludedColumns = ["id", "scan_id", "group_id", "location_id", "created_at"];
        $columns = [];
        foreach ($query->result() as $row) {
            if (in_array($row->Field, $excludedColumns)) {
                continue;
            }
            $mapping = $this->db->get_where("ext_field_mappings", ["doctype_id" => $doctype_id, "temp_column" => $row->Field, 'has_items_feild' => $has_items_feild])->row_array();
            $columns[] = [
                "temp_column" => $row->Field,
                "input_type" => $mapping["input_type"] ?? "",
                "select_table" => $mapping["select_table"] ?? "",
                "relation_column" => $mapping["relation_column"] ?? "",
                "relation_value" => $mapping["relation_value"] ?? "",
                "punch_table" => $mapping["punch_table"] ?? "",
                "punch_column" => $mapping["punch_column"] ?? "",
                "add_condition" => $mapping["add_condition"] ?? "",
            ];
        }
        echo json_encode($columns);
        exit;
    }
    public function getAllTablesList($punchOnly)
    {
        $tablePrefix = "y{$this->year_id}_punchdata_";

        $query = $this->db->query("SHOW TABLES");

        $tables = [];
        foreach ($query->result_array() as $row) {
            $tableName = reset($row);

            if ($punchOnly === 'Y') {

                if (strpos($tableName, $tablePrefix) === 0) {
                    $tables[] = $tableName;
                }
            } else {

                if (strpos($tableName, $tablePrefix) !== 0) {
                    $tables[] = $tableName;
                }
            }
        }

        return $tables;
    }

    public function saveFieldMappingsValue($doctype_id, $has_items_feild, $fieldMappings, $table)
    {
        $defaultMappings = [
            [
                "has_items_feild" => $has_items_feild,
                "temp_column" => "scan_id",
                "input_type" => "input",
                "select_table" => null,
                "relation_column" => null,
                "relation_value" => null,
                "punch_table" => $table,
                "punch_column" => "scan_id",
            ],
            [
                "has_items_feild" => $has_items_feild,
                "temp_column" => "group_id",
                "input_type" => "input",
                "select_table" => null,
                "relation_column" => null,
                "relation_value" => null,
                "punch_table" => $table,
                "punch_column" => "group_id",
            ],
        ];

        $allMappings = array_merge($defaultMappings, $fieldMappings ?? []);
        $errors = [];

        $this->db->where([
            "doctype_id" => $doctype_id,
            "has_items_feild" => $has_items_feild
        ])->delete("ext_field_mappings");

        foreach ($allMappings as $field) {
            if (empty($field["punch_table"]) || empty($field["punch_column"])) {
                continue;
            }

            $data = [
                "doctype_id" => $doctype_id,
                "has_items_feild" => $has_items_feild,
                "temp_column" => $field["temp_column"],
                "input_type" => $field["input_type"],
                "select_table" => $field["select_table"] ?? null,
                "relation_column" => $field["relation_column"] ?? null,
                "relation_value" => $field["relation_value"] ?? null,
                "punch_table" => $field["punch_table"],
                "punch_column" => $field["punch_column"],
                "add_condition" => $field["add_condition"] ?? null,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),
            ];

            if (!$this->db->insert("ext_field_mappings", $data)) {
                $errors[] = "Failed to insert mapping for column: " . $field["temp_column"];
            }
        }

        if (empty($errors)) {
            return [
                "status" => "success",
                "message" => "Field mappings saved successfully."
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Some field mappings failed to save.",
                "errors" => $errors
            ];
        }
    }


    public function getTableColumnsList($table)
    {
        $query = $this->db->query("SHOW COLUMNS FROM `$table`");
        $columns = [];
        foreach ($query->result() as $row) {
            $columns[] = $row->Field;
        }
        return $columns;
    }

    public function getGroups()
    {
        $this->db->select("group_id, group_name");
        $this->db->where("is_deleted", "N");
        return $this->db->where('group_id', 16)->get("master_group")->result();
    }
    public function getLocations()
    {
        $this->db->select("location_id, location_name");
        $this->db->where("status", "A");
        return $this->db->get("master_work_location")->result();
    }
    public function getClassificationList($group_id = null, $location_id = null)
    {
        $this->db->select('scan_id');
        $this->db->where('status', 'pending');
        $queuedScans = $this->db->get('tbl_queues')->result_array();
        $queuedScanIds = array_column($queuedScans, 'scan_id');
        $this->db->select("s.scan_id, g.group_name, l.location_name, s.document_name , s.file_path, IF(s.is_temp_scan = 'Y', s.temp_scan_date, s.scan_date) AS scan_date, IF(s.is_temp_scan = 'Y', CONCAT(sb.first_name, ' ', sb.last_name), CONCAT(sbb.first_name, ' ', sbb.last_name)) AS scanned_by, CONCAT(ba.first_name, ' ', ba.last_name) AS bill_approver_id, s.bill_approved_date");
        $this->db->from("y{$this->year_id}_scan_file s");
        $this->db->join("master_group g", "g.group_id = s.group_id", "left");
        $this->db->join("master_work_location l", "l.location_id = s.location_id", "left");
        $this->db->join("users ba", "ba.user_id = s.bill_approver_id", "left");
        $this->db->join("users sb", "sb.user_id = s.Temp_Scan_By", "left");
        $this->db->join("users sbb", "sbb.user_id = s.scanned_by", "left");
        $this->db->where("s.document_name  !=", "");
        $this->db->where("s.extract_status", "N");
        $this->db->where("s.bill_approval_status", "Y");
        if (!empty($queuedScanIds)) {
            $this->db->where_not_in("s.scan_id", $queuedScanIds);
        }
        if (!empty($group_id)) {
            $this->db->where("s.group_id", $group_id);
        }
        if (!empty($location_id)) {
            $this->db->where("s.location_id", $location_id);
        }
        $this->db->where("s.group_id", '16');
        return $this->db->get()->result();
    }
    public function getProcessedList($group_id = null, $location_id = null)
    {
        $this->db->select("s.scan_id, g.group_name, md.file_type, s.extract_status, l.location_name, s.document_name , s.file_path, IF(s.is_temp_scan = 'Y', s.temp_scan_date, s.scan_date) AS scan_date, IF(s.is_temp_scan = 'Y', CONCAT(sb.first_name, ' ', sb.last_name), CONCAT(sbb.first_name, ' ', sbb.last_name)) AS scanned_by, CONCAT(ba.first_name, ' ', ba.last_name) AS bill_approver_id, s.bill_approved_date");
        $this->db->from("y{$this->year_id}_scan_file s");
        $this->db->join("master_group g", "g.group_id = s.group_id", "left");
        $this->db->join("master_doctype md", "md.type_id  = s.doc_type_id", "left");
        $this->db->join("master_work_location l", "l.location_id = s.location_id", "left");
        $this->db->join("users ba", "ba.user_id = s.bill_approver_id", "left");
        $this->db->join("users sb", "sb.user_id = s.Temp_Scan_By", "left");
        $this->db->join("users sbb", "sbb.user_id = s.scanned_by", "left");
        $this->db->where("s.document_name  !=", "");
        $this->db->where_in("s.extract_status", ["Y", "C"]);
        $this->db->where("s.bill_approval_status", "Y");
        if (!empty($group_id)) {
            $this->db->where("s.group_id", $group_id);
        }
        if (!empty($location_id)) {
            $this->db->where("s.location_id", $location_id);
        }
        $this->db->where("s.group_id", '16');
        return $this->db->get()->result();
    }
    public function getChangeRequestList($group_id = null, $location_id = null)
    {
        $this->db->select("s.scan_id, g.group_name, s.extract_status, md.file_type, l.location_name, s.document_name , s.file_path, IF(s.is_temp_scan = 'Y', s.temp_scan_date, s.scan_date) AS scan_date, IF(s.is_temp_scan = 'Y', CONCAT(sb.first_name, ' ', sb.last_name), CONCAT(sbb.first_name, ' ', sbb.last_name)) AS scanned_by, CONCAT(ba.first_name, ' ', ba.last_name) AS bill_approver_id, s.bill_approved_date");
        $this->db->from("y{$this->year_id}_scan_file s");
        $this->db->join("master_group g", "g.group_id = s.group_id", "left");
        $this->db->join("master_doctype md", "md.type_id  = s.doc_type_id", "left");
        $this->db->join("master_work_location l", "l.location_id = s.location_id", "left");
        $this->db->join("users ba", "ba.user_id = s.bill_approver_id", "left");
        $this->db->join("users sb", "sb.user_id = s.Temp_Scan_By", "left");
        $this->db->join("users sbb", "sbb.user_id = s.scanned_by", "left");
        $this->db->where("s.document_name  !=", "");
        $this->db->where_in("s.extract_status", ["C"]);
        $this->db->where("s.bill_approval_status", "Y");
        if (!empty($group_id)) {
            $this->db->where("s.group_id", $group_id);
        }
        if (!empty($location_id)) {
            $this->db->where("s.location_id", $location_id);
        }
        return $this->db->get()->result();
    }
    public function getDocumentDetails($scanId)
    {
        $this->db->select("s.scan_id, s.doc_type_id, g.group_name, l.location_name, s.document_name , s.file_path, IF(s.is_temp_scan = 'Y', s.temp_scan_date, s.scan_date) AS scan_date, IF(s.is_temp_scan = 'Y', CONCAT(sb.first_name, ' ', sb.last_name), CONCAT(sbb.first_name, ' ', sbb.last_name)) AS scanned_by, CONCAT(ba.first_name, ' ', ba.last_name) AS bill_approver_id, s.bill_approved_date");
        $this->db->from("y{$this->year_id}_scan_file s");
        $this->db->join("master_group g", "g.group_id = s.group_id", "left");
        $this->db->join("master_work_location l", "l.location_id = s.location_id", "left");
        $this->db->join("users ba", "ba.user_id = s.bill_approver_id", "left");
        $this->db->join("users sb", "sb.user_id = s.Temp_Scan_By", "left");
        $this->db->join("users sbb", "sbb.user_id = s.scanned_by", "left");
        $this->db->where("s.scan_id", $scanId);
        return $this->db->get()->row();
    }
    public function getDocTypes()
    {
        return $this->db->where("status", "A")->where_in("type_id", [1, 6, 7, 13, 17, 20, 22, 23, 27, 28, 29, 31, 42, 43, 44, 46, 47, 48, 50, 51, 52, 54, 55, 56, 59])->order_by("file_type", "ASC")->get("master_doctype")->result();
    }
    public function addToQueue($scanId, $typeId)
    {
        $existing = $this->db->where(['scan_id' => $scanId, 'status' => 'pending'])->get('tbl_queues')->row();
        if ($existing) {
            return false;
        }
        $data = ['scan_id' => $scanId, 'type_id' => $typeId, 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s'), 'created_by' => $this->session->userdata('user_id')];
        return $this->db->insert('tbl_queues', $data);
    }
    public function getQueueList()
    {
        $this->db->select('q.*, s.document_name , md.file_type');
        $this->db->from('tbl_queues q');
        $this->db->join("y{$this->year_id}_scan_file s", "s.scan_id = q.scan_id");
        $this->db->join('master_doctype md', 'md.type_id = q.type_id');
        $this->db->where_in('q.status', ['pending', 'failed']);
        $this->db->order_by('q.created_at', 'ASC');
        return $this->db->get()->result();
    }
    public function removeFromQueue($queueId)
    {
        $this->db->where('id', $queueId);
        return $this->db->delete('tbl_queues');
    }
    public function updateQueueStatus($queueId, $status, $result = null)
    {
        $data = ['status' => $status, 'completed_at' => date('Y-m-d H:i:s')];
        if ($result !== null) {
            $data['result'] = $result;
        }
        $this->db->where('id', $queueId);
        return $this->db->update('tbl_queues', $data);
    }
    public function getNextQueueItem()
    {
        $this->db->where('status', 'pending');
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get('tbl_queues')->row();
    }
    public function getAllPendingQueueItems()
    {
        $this->db->where('status', 'pending');
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get('tbl_queues')->result();
    }
    public function getApiEndpoint($typeId)
    {
        $this->db->select("endpoint");
        $this->db->from("ext_master_api_control");
        $this->db->where(["doctype_id" => $typeId, "status" => 1]);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row()->endpoint : null;
    }
    public function getFileLocation($scanId)
    {

        $year_id = $this->year_id ?? null;

        if (!$year_id) {
            $query = $this->db->select("id")
                ->from("financial_years")
                ->where("is_current", 1)
                ->get();

            if ($query->num_rows() > 0) {
                $year_id = $query->row()->id;
            } else {
                return null;
            }
        }


        $tableName = "y{$year_id}_scan_file";

        $this->db->select("file_path");
        $this->db->from($tableName);
        $this->db->where("scan_id", $scanId);
        $query = $this->db->get();

        return $query->num_rows() > 0 ? $query->row()->file_path : null;
    }

    public function storeExtractedData($typeId, $scanId, $data)
    {
        $tableName = "ext_tempdata_" . $typeId;
        if (!$this->db->table_exists($tableName)) {
            return false;
        }
        if (isset($data['Round Off']) && is_array($data['Round Off'])) {
            if (isset($data['Round Off']['Value'])) {
                $data['Round Off Value'] = $data['Round Off']['Value'];
            }
            if (isset($data['Round Off']['Type'])) {
                $data['Round Off Type'] = $data['Round Off']['Type'];
            }
            unset($data['Round Off']);
        }
        $this->db->select("group_id, location_id");
        $this->db->where("scan_id", $scanId);
        $query = $this->db->get("y{$this->year_id}_scan_file");
        if ($query->num_rows() == 0) {
            return false;
        }
        $scanData = $query->row_array();
        $groupId = $scanData["group_id"];
        $location = $scanData["location_id"];
        $this->db->where("scan_id", $scanId);
        if ($this->db->get($tableName)->num_rows() > 0) {
            $this->db->where("scan_id", $scanId)->delete($tableName);
        }
        $flatData = $this->flattenArray($data);
        $tableColumns = $this->db->list_fields($tableName);
        $insertData = ["scan_id" => $scanId, "group_id" => $groupId, "location_id" => $location];
        foreach ($flatData as $key => $value) {
            if (is_array($value))
                continue;
            $column = strtolower(str_replace([" ", "/", "-"], "_", $key));
            $matchedColumn = $this->getBestMatch($column, $tableColumns);
            if ($matchedColumn) {
                if (is_string($value) && $this->isValidDate($value) && stripos($key, 'date') !== false) {
                    $value = date('Y-m-d', strtotime($value));
                } else {
                    if (!is_null($value) && is_string($value)) {
                        $value = preg_replace('/[₹,]/', '', $value);
                    }
                    if (is_numeric($value)) {
                        $value = (float) $value;
                    }
                }
                $insertData[$matchedColumn] = $value;
            } else {
            }
        }
        if ($this->db->insert($tableName, $insertData)) {
            foreach ($flatData as $sectionName => $sectionItems) {
                if (!is_array($sectionItems) || !isset($sectionItems[0]) || !is_array($sectionItems[0])) {
                    continue;
                }
                $detailsTable = "ext_tempdata_{$typeId}_details";
                if (!$this->db->table_exists($detailsTable)) {
                    continue;
                }
                $this->db->where("scan_id", $scanId)->delete($detailsTable);
                $detailsColumns = $this->db->list_fields($detailsTable);
                $mainItems = [];

                $taxData = ['gst' => null, 'sgst' => null, 'igst' => null, 'cess' => null, 'tax_amount' => 0];
                foreach ($sectionItems as $item) {
                    if (!is_array($item)) {
                        continue;
                    }
                    $particular = isset($item['Particular']) ? $item['Particular'] : '';
                    if (stripos($particular, 'CGST') !== false || stripos($particular, 'SGST') !== false || stripos($particular, 'IGST') !== false) {
                        if (stripos($particular, 'CGST') !== false && isset($item['GST %'])) {
                            $taxData['gst'] = $item['GST %'];
                            $taxData['tax_amount'] += isset($item['Amount']) ? (float) $item['Amount'] : 0;
                        } elseif (stripos($particular, 'SGST') !== false && isset($item['GST %'])) {
                            $taxData['sgst'] = $item['GST %'];
                            $taxData['tax_amount'] += isset($item['Amount']) ? (float) $item['Amount'] : 0;
                        } elseif (stripos($particular, 'IGST') !== false && isset($item['GST %'])) {
                            $taxData['igst'] = $item['GST %'];
                            $taxData['tax_amount'] += isset($item['Amount']) ? (float) $item['Amount'] : 0;
                        }
                        if (isset($item['Cess %'])) {
                            $taxData['cess'] = $item['Cess %'];
                        }
                    } else {
                        $mainItems[] = $item;
                    }
                }


                foreach ($mainItems as $item) {
                    $detailsData = ["scan_id" => $scanId];
                    foreach ($item as $key => $value) {
                        if (is_array($value)) {
                            continue;
                        }
                        $column = strtolower(str_replace([" ", "/", "-", "%"], "_", $key));
                        $matchedColumn = $this->getBestMatch($column, $detailsColumns);
                        if ($matchedColumn) {
                            if (in_array($matchedColumn, ['qty', 'mrp', 'discount_in_mrp', 'price', 'amount', 'gst', 'sgst', 'igst', 'cess', 'total_amount']) && !empty($value)) {
                                $value = (float) preg_replace('/[₹,]/', '', $value);
                            }
                            $detailsData[$matchedColumn] = $value;
                        }
                    }


                    if ($taxData['gst'] !== null) {
                        $detailsData['gst'] = (float) $taxData['gst'];
                    }
                    if ($taxData['sgst'] !== null) {
                        $detailsData['sgst'] = (float) $taxData['sgst'];
                    }
                    if ($taxData['igst'] !== null) {
                        $detailsData['igst'] = (float) $taxData['igst'];
                    }
                    if ($taxData['cess'] !== null) {
                        $detailsData['cess'] = (float) $taxData['cess'];
                    }
                    if (isset($detailsData['amount']) && $taxData['tax_amount'] > 0) {
                        $detailsData['total_amount'] = (float) $detailsData['amount'] + $taxData['tax_amount'];
                    } elseif (isset($detailsData['amount'])) {
                        $detailsData['total_amount'] = (float) $detailsData['amount'];
                    }


                    foreach (['particular', 'hsn', 'qty', 'unit', 'price', 'amount', 'mrp', 'discount_in_mrp'] as $requiredField) {
                        if (!isset($detailsData[$requiredField])) {
                            $detailsData[$requiredField] = in_array($requiredField, ['qty', 'price', 'amount', 'mrp', 'discount_in_mrp']) ? 0.00 : '';
                        }
                    }


                    if (!empty($detailsData)) {
                        if (!$this->db->insert($detailsTable, $detailsData)) {
                            $error = $this->db->error();
                            log_message('error', 'Insert failed: ' . json_encode($error));
                        }
                    }
                }
            }
            $docType = $this->customlib->getDocType($typeId);
            $this->db->where("scan_id", $scanId);
            $this->db->update("y{$this->year_id}_scan_file", ["extract_status" => "Y", "Doc_Type" => $docType, "doc_type_id" => $typeId]);
            return $this->moveDataToPunchfile($scanId, $typeId);
        }
        return false;
    }
    private function getBestMatch($inputColumn, $columns)
    {
        $bestMatch = null;
        $bestScore = 0;
        $minDistance = PHP_INT_MAX;
        foreach ($columns as $column) {
            $normalizedInput = strtolower(preg_replace('/[^a-z0-9]/', '', $inputColumn));
            $normalizedColumn = strtolower(preg_replace('/[^a-z0-9]/', '', $column));
            similar_text($normalizedInput, $normalizedColumn, $similarity);
            $levenshteinDistance = levenshtein($normalizedInput, $normalizedColumn);
            if ($similarity > $bestScore || ($similarity == $bestScore && $levenshteinDistance < $minDistance)) {
                $bestScore = $similarity;
                $minDistance = $levenshteinDistance;
                $bestMatch = $column;
            }
        }
        return ($bestScore >= 70 || $minDistance <= 3) ? $bestMatch : null;
    }
    private function flattenArray($data, $excludeKeys = [])
    {
        $flatData = [];
        foreach ($data as $key => $value) {
            if (is_array($value) && !in_array($key, $excludeKeys)) {
                foreach ($value as $subKey => $subValue) {
                    $flatData[$subKey] = $subValue;
                }
            } else {
                $flatData[$key] = $value;
            }
        }
        return $flatData;
    }
    private function isValidDate($date)
    {
        if (empty($date)) {
            return false;
        }
        $timestamp = strtotime($date);
        return $timestamp !== false && date('Y-m-d', $timestamp) === date('Y-m-d', $timestamp);
    }
    public function moveDataToPunchfile($scanId, $docTypeId)
    {
        $docType = $this->customlib->getDocType($docTypeId);
        $tableName = "ext_tempdata_" . $docTypeId;
        if (!$this->db->table_exists($tableName)) {
            return ["status" => "error", "message" => "Temp table ($tableName) does not exist!"];
        }
        $mappings = $this->db->select("temp_column, input_type, select_table, relation_column, relation_value, punch_column, punch_table, add_condition")->where(["doctype_id" => $docTypeId, "has_Items_feild" => "N"])->get("ext_field_mappings")->result_array();
        if (empty($mappings)) {
            return ["status" => "error", "message" => "No field mappings found!"];
        }
        $punch_table = $mappings[0]["punch_table"];
        $fieldMap = array_column($mappings, null, "temp_column");
        $tempData = $this->db->where("scan_id", $scanId)->get($tableName)->row_array();
        if (!$tempData) {
            return ["status" => "error", "message" => "No data found in temp table for scan_id: $scanId"];
        }
        $punchData = ["scan_id" => $scanId, "DocType" => $docType, "DocTypeId" => $docTypeId, "group_id" => $this->session->userdata("group_id"), "Created_By" => $this->session->userdata("user_id"), "Created_Date" => date("Y-m-d H:i:s")];
        foreach ($tempData as $key => $value) {
            if (isset($fieldMap[$key])) {
                $map = $fieldMap[$key];
                if ($map["input_type"] === "select") {
                    $relatedValue = $this->getClosestValueMatch($map["select_table"], $map["relation_column"], $value, $map["relation_value"], $map["add_condition"]);
                    if ($relatedValue !== null) {
                        $punchData[$map["punch_column"]] = $relatedValue;
                    }
                } else {
                    $punchData[$map["punch_column"]] = $value;
                }
            }
        }
        $existingFile = $this->db->where("scan_id", $scanId)->get($punch_table)->row();
        if ($existingFile) {
            $this->db->where("scan_id", $scanId)->update($punch_table, $punchData);
            $fileID = $existingFile->FileID;
        } else {
            $this->db->insert($punch_table, $punchData);
            $fileID = $this->db->insert_id();
        }
        $this->db->insert("sub_punchfile", ["FileID" => $fileID, "Amount" => "-" . ($punchData["Total_Amount"] ?? 0), "Comment" => $punchData["Remark"] ?? ""]);
        $detailsTable = "ext_tempdata_{$docTypeId}_details";
        if ($this->db->table_exists($detailsTable)) {
            $detailsData = $this->db->where("scan_id", $scanId)->get($detailsTable)->result_array();
            $details_mappings = $this->db->select("temp_column, input_type, select_table, relation_column, relation_value, punch_column, punch_table, add_condition")->where("doctype_id", $docTypeId)->where("has_Items_feild", "Y")->where_not_in("punch_table", ["punchfile", "punchfile2"])->get("ext_field_mappings")->result_array();
            $punchTables = array_unique(array_column($details_mappings, "punch_table"));
            foreach ($punchTables as $punchTable) {
                $this->db->where("scan_id", $scanId)->delete($punchTable);
            }
            $detailFieldMap = array_column($details_mappings, null, "temp_column");
            if (!empty($detailsData)) {
                foreach ($detailsData as $detail) {
                    $punchDetailData = ["scan_id" => $scanId];
                    foreach ($detail as $key => $value) {
                        if (isset($detailFieldMap[$key])) {
                            $map = $detailFieldMap[$key];
                            if ($map["input_type"] === "select") {
                                $relatedValue = $this->getClosestValueMatch($map["select_table"], $map["relation_column"], $value, $map["relation_value"], $map["add_condition"]);
                                if ($relatedValue !== null) {
                                    $punchDetailData[$map["punch_column"]] = $relatedValue;
                                }
                            } else {
                                $punchDetailData[$map["punch_column"]] = $value;
                            }
                        }
                    }
                    $this->db->insert($map["punch_table"], $punchDetailData);
                }
            }
        }
        return ["status" => "success", "message" => "Data moved successfully."];
    }
    private function getClosestValueMatch($table, $searchColumn, $searchValue, $returnColumn, $addCondition)
    {
        if (empty($table) || empty($searchColumn) || empty($returnColumn) || empty($searchValue)) {
            return null;
        }
        $searchValue = trim((string) $searchValue);
        if (strlen($searchValue) === 0) {
            return null;
        }
        $searchValueCleaned = preg_replace('/\s*\([^)]+\)/', '', $searchValue);
        $searchParts = array_map('trim', preg_split('/and|&/', $searchValueCleaned, -1, PREG_SPLIT_NO_EMPTY));
        if (empty($searchParts)) {
            return null;
        }
        $this->db->select("$searchColumn, $returnColumn");
        $this->db->from($table);
        if (!empty($addCondition)) {
            $this->db->where($addCondition);
        }
        $query = $this->db->get();
        $results = $query->result();
        if (empty($results)) {
            if ($table === 'master_item') {
                return $this->insertNewItem($searchValueCleaned);
            }
            return null;
        }
        $matches = [];
        $similarityThreshold = 50;
        $highestPercentOverall = 0;
        foreach ($results as $row) {
            $dbValue = trim((string) $row->$searchColumn);
            if (empty($dbValue)) {
                continue;
            }
            $highestPercent = 0;
            foreach ($searchParts as $part) {
                if (empty($part))
                    continue;
                $percent = 0;
                similar_text(strtoupper($part), strtoupper($dbValue), $percent);
                $percent = round($percent, 2);
                if ($percent < $similarityThreshold) {
                    $percent = 0;
                }
                $highestPercent = max($highestPercent, $percent);
            }
            if ($highestPercent > 0) {
                $matches[] = ['percent' => $highestPercent, 'return_value' => $row->$returnColumn];
            }
            $highestPercentOverall = max($highestPercentOverall, $highestPercent);
        }
        if ($table === 'master_item' && ($highestPercentOverall < $similarityThreshold || empty($matches))) {
            return $this->insertNewItem($searchValueCleaned);
        }
        if (empty($matches)) {
            return null;
        }
        usort($matches, function ($a, $b) {
            return $b['percent'] <=> $a['percent'];
        });
        return $matches[0]['return_value'];
    }
    public function callExternalApi($endpoint, $fileUrl)
    {
        $postData = json_encode(["fileUrl" => $fileUrl]);
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Accept: application/json"]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ["statusCode" => $httpCode, "data" => $httpCode === 200 ? json_decode($response, true) : null];
    }
    private function insertNewItem($itemName)
    {
        $itemName = trim($itemName);
        if (empty($itemName)) {
            return null;
        }
        $data = ['item_name' => $itemName, 'item_code' => 0];
        $this->db->insert('master_item', $data);
        $lastInsertId = $this->db->insert_id();
        if (!$lastInsertId) {
            return null;
        }
        $itemCode = sprintf('ITEM-%03d', $lastInsertId);
        $this->db->where('item_id', $lastInsertId);
        $this->db->update('master_item', ['item_code' => $itemCode]);
        return $itemCode;
    }
    public function get_filtered_list($table, $search_value, $search_column, $return_column, $select_column, $add_condition = '')
    {
        $search_value = trim((string) $search_value);
        $search_parts = [];
        if (!empty($search_value)) {
            $search_value_cleaned = preg_replace('/\s*\([^)]+\)/', '', $search_value);
            $search_parts = array_map('trim', preg_split('/and|&/', $search_value_cleaned, -1, PREG_SPLIT_NO_EMPTY));
        }
        $this->db->select($select_column);
        $this->db->from($table);
        if (!empty($add_condition)) {
            $this->db->where($add_condition);
        }
        $query = $this->db->get();
        $results = $query->result_array();
        if (empty($results)) {
            return [];
        }
        $similarity_threshold = 50;
        foreach ($results as &$row) {
            $highest_similarity_percent = 0;
            $db_value = trim((string) $row[$search_column]);
            if (!empty($search_parts) && !empty($db_value)) {
                foreach ($search_parts as $part) {
                    if (empty($part))
                        continue;
                    $similarity_percent = 0;
                    similar_text(strtoupper($part), strtoupper($db_value), $similarity_percent);
                    $similarity_percent = round($similarity_percent, 2);
                    if ($similarity_percent < $similarity_threshold) {
                        $similarity_percent = 0;
                    }
                    $highest_similarity_percent = max($highest_similarity_percent, $similarity_percent);
                }
            }
            $row['similarity'] = $highest_similarity_percent;
        }
        unset($row);
        usort($results, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });
        return $results;
    }
}
