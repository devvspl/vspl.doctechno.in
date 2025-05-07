<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Extract_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function getGroups() {
        $this->db->select("group_id, group_name");
        $this->db->where("is_deleted", "N");
        return $this->db->where('group_id', 16)->get("master_group")->result();
    }
    public function getLocations() {
        $this->db->select("location_id, location_name");
        $this->db->where("status", "A");
        return $this->db->get("master_work_location")->result();
    }
    public function getClassificationList($group_id = null, $location_id = null) {
        $this->db->select('scan_id');
        $this->db->where('status', 'pending');
        $queuedScans = $this->db->get('tbl_queues')->result_array();
        $queuedScanIds = array_column($queuedScans, 'scan_id');
        $this->db->select("s.Scan_Id, g.group_name, l.location_name, s.Document_Name, s.File_Location, IF(s.Temp_Scan = 'Y', s.Temp_Scan_Date, s.Scan_Date) AS Scan_Date, IF(s.Temp_Scan = 'Y', CONCAT(sb.first_name, ' ', sb.last_name), CONCAT(sbb.first_name, ' ', sbb.last_name)) AS Scan_By, CONCAT(ba.first_name, ' ', ba.last_name) AS Bill_Approver, s.Bill_Approver_Date");
        $this->db->from("scan_file s");
        $this->db->join("master_group g", "g.group_id = s.Group_Id", "left");
        $this->db->join("master_work_location l", "l.location_id = s.Location", "left");
        $this->db->join("users ba", "ba.user_id = s.Bill_Approver", "left");
        $this->db->join("users sb", "sb.user_id = s.Temp_Scan_By", "left");
        $this->db->join("users sbb", "sbb.user_id = s.Scan_By", "left");
        $this->db->where("s.Document_Name !=", "");
        $this->db->where("s.is_extract", "N");
        $this->db->where("s.Bill_Approved", "Y");
        if (!empty($queuedScanIds)) {
            $this->db->where_not_in("s.Scan_Id", $queuedScanIds);
        }
        if (!empty($group_id)) {
            $this->db->where("s.Group_Id", $group_id);
        }
        if (!empty($location_id)) {
            $this->db->where("s.Location", $location_id);
        }
        $this->db->where("s.Group_Id", '16');
        return $this->db->get()->result();
    }
    public function getProcessedList($group_id = null, $location_id = null) {
        $this->db->select("s.Scan_Id, g.group_name, md.file_type, s.is_extract, l.location_name, s.Document_Name, s.File_Location, IF(s.Temp_Scan = 'Y', s.Temp_Scan_Date, s.Scan_Date) AS Scan_Date, IF(s.Temp_Scan = 'Y', CONCAT(sb.first_name, ' ', sb.last_name), CONCAT(sbb.first_name, ' ', sbb.last_name)) AS Scan_By, CONCAT(ba.first_name, ' ', ba.last_name) AS Bill_Approver, s.Bill_Approver_Date");
        $this->db->from("scan_file s");
        $this->db->join("master_group g", "g.group_id = s.Group_Id", "left");
        $this->db->join("master_doctype md", "md.type_id  = s.DocType_Id", "left");
        $this->db->join("master_work_location l", "l.location_id = s.Location", "left");
        $this->db->join("users ba", "ba.user_id = s.Bill_Approver", "left");
        $this->db->join("users sb", "sb.user_id = s.Temp_Scan_By", "left");
        $this->db->join("users sbb", "sbb.user_id = s.Scan_By", "left");
        $this->db->where("s.Document_Name !=", "");
        $this->db->where_in("s.is_extract", ["Y", "C"]);
        $this->db->where("s.Bill_Approved", "Y");
        if (!empty($group_id)) {
            $this->db->where("s.Group_Id", $group_id);
        }
        if (!empty($location_id)) {
            $this->db->where("s.Location", $location_id);
        }
        $this->db->where("s.Group_Id", '16');
        return $this->db->get()->result();
    }
    public function getChangeRequestList($group_id = null, $location_id = null) {
        $this->db->select("s.Scan_Id, g.group_name, s.is_extract, md.file_type, l.location_name, s.Document_Name, s.File_Location, IF(s.Temp_Scan = 'Y', s.Temp_Scan_Date, s.Scan_Date) AS Scan_Date, IF(s.Temp_Scan = 'Y', CONCAT(sb.first_name, ' ', sb.last_name), CONCAT(sbb.first_name, ' ', sbb.last_name)) AS Scan_By, CONCAT(ba.first_name, ' ', ba.last_name) AS Bill_Approver, s.Bill_Approver_Date");
        $this->db->from("scan_file s");
        $this->db->join("master_group g", "g.group_id = s.Group_Id", "left");
        $this->db->join("master_doctype md", "md.type_id  = s.DocType_Id", "left");
        $this->db->join("master_work_location l", "l.location_id = s.Location", "left");
        $this->db->join("users ba", "ba.user_id = s.Bill_Approver", "left");
        $this->db->join("users sb", "sb.user_id = s.Temp_Scan_By", "left");
        $this->db->join("users sbb", "sbb.user_id = s.Scan_By", "left");
        $this->db->where("s.Document_Name !=", "");
        $this->db->where_in("s.is_extract", ["C"]);
        $this->db->where("s.Bill_Approved", "Y");
        if (!empty($group_id)) {
            $this->db->where("s.Group_Id", $group_id);
        }
        if (!empty($location_id)) {
            $this->db->where("s.Location", $location_id);
        }
        return $this->db->get()->result();
    }
    public function getDocumentDetails($scanId) {
        $this->db->select("s.Scan_Id, s.DocType_Id, g.group_name, l.location_name, s.Document_Name, s.File_Location, IF(s.Temp_Scan = 'Y', s.Temp_Scan_Date, s.Scan_Date) AS Scan_Date, IF(s.Temp_Scan = 'Y', CONCAT(sb.first_name, ' ', sb.last_name), CONCAT(sbb.first_name, ' ', sbb.last_name)) AS Scan_By, CONCAT(ba.first_name, ' ', ba.last_name) AS Bill_Approver, s.Bill_Approver_Date");
        $this->db->from("scan_file s");
        $this->db->join("master_group g", "g.group_id = s.Group_Id", "left");
        $this->db->join("master_work_location l", "l.location_id = s.Location", "left");
        $this->db->join("users ba", "ba.user_id = s.Bill_Approver", "left");
        $this->db->join("users sb", "sb.user_id = s.Temp_Scan_By", "left");
        $this->db->join("users sbb", "sbb.user_id = s.Scan_By", "left");
        $this->db->where("s.Scan_Id", $scanId);
        return $this->db->get()->row();
    }
    public function getDocTypes() {
        return $this->db->where("status", "A")->where_in("type_id", [1, 6, 7, 13, 17, 20, 22, 23, 27, 28, 29, 31, 42, 43, 44, 46, 47, 48, 50, 51, 52, 54, 55, 56, 59])->order_by("file_type", "ASC")->get("master_doctype")->result();
    }
    public function addToQueue($scanId, $typeId) {
        $existing = $this->db->where(['scan_id' => $scanId, 'status' => 'pending'])->get('tbl_queues')->row();
        if ($existing) {
            return false;
        }
        $data = ['scan_id' => $scanId, 'type_id' => $typeId, 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s'), 'created_by' => $this->session->userdata('user_id') ];
        return $this->db->insert('tbl_queues', $data);
    }
    public function getQueueList() {
        $this->db->select('q.*, s.Document_Name, md.file_type');
        $this->db->from('tbl_queues q');
        $this->db->join('scan_file s', 's.Scan_Id = q.scan_id');
        $this->db->join('master_doctype md', 'md.type_id = q.type_id');
        $this->db->where('q.status', 'pending');
        $this->db->order_by('q.created_at', 'ASC');
        return $this->db->get()->result();
    }
    public function removeFromQueue($queueId) {
        $this->db->where('id', $queueId);
        return $this->db->delete('tbl_queues');
    }
    public function updateQueueStatus($queueId, $status, $result = null) {
        $data = ['status' => $status, 'completed_at' => date('Y-m-d H:i:s') ];
        if ($result !== null) {
            $data['result'] = $result;
        }
        $this->db->where('id', $queueId);
        return $this->db->update('tbl_queues', $data);
    }
    public function getNextQueueItem() {
        $this->db->where('status', 'pending');
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get('tbl_queues')->row();
    }
    public function getAllPendingQueueItems() {
        $this->db->where('status', 'pending');
        $this->db->order_by('created_at', 'ASC');
        return $this->db->get('tbl_queues')->result();
    }
    public function getApiEndpoint($typeId) {
        $this->db->select("endpoint");
        $this->db->from("ext_mater_api_control");
        $this->db->where(["doctype_id" => $typeId, "status" => 1]);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row()->endpoint : null;
    }
    public function getFileLocation($scanId) {
        $this->db->select("File_Location");
        $this->db->from("scan_file");
        $this->db->where("Scan_Id", $scanId);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row()->File_Location : null;
    }
    public function storeExtractedData($typeId, $scanId, $data) {
        $tableName = "ext_tempdata_" . $typeId;
        if (!$this->db->table_exists($tableName)) {
            return false;
        }
        $nestedKeys = ["Details", "Items", "Employees", "Distance Details", "Head Details", "Perticulars"];
        if (isset($data['Round Off']) && is_array($data['Round Off'])) {
            if (isset($data['Round Off']['Value'])) {
                $data['Round Off Value'] = $data['Round Off']['Value'];
            }
            if (isset($data['Round Off']['Type'])) {
                $data['Round Off Type'] = $data['Round Off']['Type'];
            }
            unset($data['Round Off']);
        }
        $this->db->select("Group_Id, Location");
        $this->db->where("Scan_Id", $scanId);
        $query = $this->db->get("scan_file");
        if ($query->num_rows() == 0) {
            return false;
        }
        $scanData = $query->row_array();
        $groupId = $scanData["Group_Id"];
        $location = $scanData["Location"];
        $this->db->where("Scan_Id", $scanId);
        if ($this->db->get($tableName)->num_rows() > 0) {
            $this->db->where("Scan_Id", $scanId);
            $this->db->delete($tableName);
        }
        $flatData = $this->flattenArray($data, $nestedKeys);
        $tableColumns = $this->db->list_fields($tableName);
        $insertData = ["scan_id" => $scanId, "group_id" => $groupId, "location_id" => $location];
        foreach ($flatData as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            $column = strtolower(str_replace([" ", "/", "-"], " ", $key));
            $matchedColumn = $this->getBestMatch($column, $tableColumns);
            if ($matchedColumn) {
                if (is_string($value) && $this->isValidDate($value)) {
                    $value = date('Y-m-d', strtotime($value));
                } else {
                    if (!is_null($value) && is_string($value)) {
                        $value = preg_replace('/[₹,]/', '', $value);
                    }
                    if (is_numeric($value)) {
                        $value = (float)$value;
                    }
                }
                $insertData[$matchedColumn] = $value;
            }
        }
        if ($this->db->insert($tableName, $insertData)) {
            $docType = $this->customlib->getDocType($typeId);
            $this->db->where("Scan_Id", $scanId);
            $this->db->update("scan_file", ["is_extract" => "Y", "Doc_Type" => $docType, "DocType_Id" => $typeId]);
            foreach ($nestedKeys as $section) {
                if (isset($data[$section]) && is_array($data[$section])) {
                    $detailsTable = "ext_tempdata_{$typeId}_details";
                    if ($this->db->table_exists($detailsTable)) {
                        $this->db->where("Scan_Id", $scanId)->delete($detailsTable);
                        $detailsColumns = $this->db->list_fields($detailsTable);
                        foreach ($data[$section] as $item) {
                            $detailsData = ["scan_id" => $scanId];
                            foreach ($item as $key => $value) {
                                if (is_array($value)) {
                                    continue;
                                }
                                $column = strtolower(str_replace([" ", "/", "-"], "", $key));
                                $matchedColumn = $this->getBestMatch($column, $detailsColumns);
                                if ($matchedColumn) {
                                    $detailsData[$matchedColumn] = $value;
                                }
                            }
                            $this->db->insert($detailsTable, $detailsData);
                        }
                    }
                }
            }
            return $this->moveDataToPunchfile($scanId, $typeId);
        }
        return false;
    }
    private function getBestMatch($inputColumn, $columns) {
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
    private function flattenArray($data, $excludeKeys = []) {
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
    private function isValidDate($date) {
        if (empty($date)) {
            return false;
        }
        $timestamp = strtotime($date);
        return $timestamp !== false && date('Y-m-d', $timestamp) === date('Y-m-d', $timestamp);
    }
    private function moveDataToPunchfile($scanId, $docTypeId) {
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
        $tempData = $this->db->where("Scan_Id", $scanId)->get($tableName)->row_array();
        if (!$tempData) {
            return ["status" => "error", "message" => "No data found in temp table for Scan_Id: $scanId"];
        }
        $punchData = ["Scan_Id" => $scanId, "DocType" => $docType, "DocTypeId" => $docTypeId, "Group_Id" => $this->session->userdata("group_id"), "Created_By" => $this->session->userdata("user_id"), "Created_Date" => date("Y-m-d H:i:s") ];
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
        $existingFile = $this->db->where("Scan_Id", $scanId)->get($punch_table)->row();
        if ($existingFile) {
            $this->db->where("Scan_Id", $scanId)->update($punch_table, $punchData);
            $fileID = $existingFile->FileID;
        } else {
            $this->db->insert($punch_table, $punchData);
            $fileID = $this->db->insert_id();
        }
        $this->db->insert("sub_punchfile", ["FileID" => $fileID, "Amount" => "-" . ($punchData["Total_Amount"]??0), "Comment" => $punchData["Remark"]??""]);
        $detailsTable = "ext_tempdata_{$docTypeId}_details";
        if ($this->db->table_exists($detailsTable)) {
            $detailsData = $this->db->where("Scan_Id", $scanId)->get($detailsTable)->result_array();
            $details_mappings = $this->db->select("temp_column, input_type, select_table, relation_column, relation_value, punch_column, punch_table, add_condition")->where("doctype_id", $docTypeId)->where("has_Items_feild", "Y")->where_not_in("punch_table", ["punchfile", "punchfile2"])->get("ext_field_mappings")->result_array();
            $punchTables = array_unique(array_column($details_mappings, "punch_table"));
            foreach ($punchTables as $punchTable) {
                $this->db->where("Scan_Id", $scanId)->delete($punchTable);
            }
            $detailFieldMap = array_column($details_mappings, null, "temp_column");
            if (!empty($detailsData)) {
                foreach ($detailsData as $detail) {
                    $punchDetailData = ["Scan_Id" => $scanId];
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
    private function getClosestValueMatch($table, $searchColumn, $searchValue, $returnColumn, $addCondition) {
        if (empty($table) || empty($searchColumn) || empty($returnColumn)) {
            return null;
        }
        $searchValue = trim((string)$searchValue);
        $this->db->select($returnColumn);
        $this->db->from($table);
        $this->db->where($searchColumn, $searchValue);
        if (!empty($addCondition)) {
            $this->db->where($addCondition);
        }
        $result = $this->db->get()->row();
        return $result ? $result->$returnColumn : null;
    }
    public function callExternalApi($endpoint, $fileUrl) {
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
}
