<?php
defined("BASEPATH") or exit("No direct script access allowed");
class ExtractorController extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->logged_in();
        $this->load->database();
        $this->load->model("Scan_model");
    }
    private function logged_in() {
        if (!$this->session->userdata("authenticated")) {
            redirect("/");
        }
    }
    public function feilds_mapping() {
        $api_list = $this->db->get("ext_mater_api_control")->result_array();
        foreach ($api_list as & $api) {
            $doctype_id = $api['doctype_id'];
            $tableName = "ext_tempdata_" . $doctype_id . "_details";
            $query = $this->db->query("SHOW TABLES LIKE '{$tableName}'");
            $api['has_items'] = ($query->num_rows() > 0) ? true : false;
        }
        $this->data["api_list"] = $api_list;
        $this->data["main"] = "extract/feilds_mapping";
        $this->load->view("layout/template", $this->data);
    }
    public function classification() {
        $this->session->set_userdata("top_menu", "classification");
        $this->session->set_userdata("sub_menu", "classification");
        $this->load->database();
        $group_id = $this->input->get("group_id");
        $location_id = $this->input->get("location_id");
        $this->db->select("group_id, group_name");
        $this->db->where("is_deleted", "N");
        $this->data["groups"] = $this->db->where('group_id', 16)->get("master_group")->result();
        $this->db->select("location_id, location_name");
        $this->db->where("status", "A");
        $this->data["locations"] = $this->db->get("master_work_location")->result();
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
        if (!empty($group_id)) {
            $this->db->where("s.Group_Id", $group_id);
        }
        if (!empty($location_id)) {
            $this->db->where("s.Location", $location_id);
        }
        $this->db->where("s.Group_Id", '16');
        $query = $this->db->get();
        $this->data["documents"] = $query->result();
        $this->data["main"] = "extract/classification";
        $this->load->view("layout/template", $this->data);
    }
    public function processed() {
        $this->session->set_userdata("top_menu", "processed");
        $this->session->set_userdata("sub_menu", "processed");
        $this->load->database();
        $group_id = $this->input->get("group_id");
        $location_id = $this->input->get("location_id");
        $this->db->select("group_id, group_name");
        $this->db->where("is_deleted", "N");
        $this->data["groups"] = $this->db->get("master_group")->result();
        $this->db->select("location_id, location_name");
        $this->db->where("status", "A");
        $this->data["locations"] = $this->db->get("master_work_location")->result();
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
        $query = $this->db->get();
        $this->data["documents"] = $query->result();
        $this->data["main"] = "extract/processed";
        $this->load->view("layout/template", $this->data);
    }
    public function changeRequestList() {
        $this->session->set_userdata("top_menu", "change-request");
        $this->session->set_userdata("sub_menu", "change-request");
        $this->load->database();
        $group_id = $this->input->get("group_id");
        $location_id = $this->input->get("location_id");
        $this->db->select("group_id, group_name");
        $this->db->where("is_deleted", "N");
        $this->data["groups"] = $this->db->get("master_group")->result();
        $this->db->select("location_id, location_name");
        $this->db->where("status", "A");
        $this->data["locations"] = $this->db->get("master_work_location")->result();
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
        $query = $this->db->get();
        $this->data["documents"] = $query->result();
        $this->data["main"] = "extract/change-request";
        $this->load->view("layout/template", $this->data);
    }
    public function changeRequest() {
        $scan_id = $this->input->post('scan_id');
        if (empty($scan_id)) {
            echo json_encode(["status" => "error", "message" => "Invalid Scan ID"]);
            return;
        }
        $this->db->where('Scan_Id', $scan_id);
        $update = $this->db->update('scan_file', ['is_extract' => 'C']);
        if ($update) {
            echo json_encode(["status" => "success", "message" => "Approved Change Request"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update record"]);
        }
    }
    public function approveChangeRequest() {
        $scan_id = $this->input->post('scan_id');
        if (empty($scan_id)) {
            echo json_encode(["status" => "error", "message" => "Invalid Scan ID"]);
            return;
        }
        $this->db->where('Scan_Id', $scan_id);
        $update = $this->db->update('scan_file', ['is_extract' => 'N']);
        if ($update) {
            echo json_encode(["status" => "success", "message" => "Change Request Submitted"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update record"]);
        }
    }
    public function getDetails() {
        $scanId = $this->input->post("scan_id");
        $data["document"] = $this->db->select("s.Scan_Id, s.DocType_Id, g.group_name, l.location_name, s.Document_Name, s.File_Location, IF(s.Temp_Scan = 'Y', s.Temp_Scan_Date, s.Scan_Date) AS Scan_Date, IF(s.Temp_Scan = 'Y', CONCAT(sb.first_name, ' ', sb.last_name), CONCAT(sbb.first_name, ' ', sbb.last_name)) AS Scan_By, CONCAT(ba.first_name, ' ', ba.last_name) AS Bill_Approver, s.Bill_Approver_Date")->from("scan_file s")->join("master_group g", "g.group_id = s.Group_Id", "left")->join("master_work_location l", "l.location_id = s.Location", "left")->join("users ba", "ba.user_id = s.Bill_Approver", "left")->join("users sb", "sb.user_id = s.Temp_Scan_By", "left")->join("users sbb", "sbb.user_id = s.Scan_By", "left")->where("s.Scan_Id", $scanId)->get()->row();
        $data["docTypes"] = $this->db->where("status", "A")->where_in("type_id", [1, 6, 7, 13, 17, 20, 22, 23, 27, 28, 29, 31, 42, 43, 44, 46, 47, 48, 50, 51, 52, 54, 55, 56])->get("master_doctype")->result();
        $this->load->view("extract/details_ajax", $data);
    }
    public function extractDetails() {
        $scanId = $this->input->post("scan_id");
        $typeId = $this->input->post("type_id");
        if (!$scanId || !$typeId) {
            echo json_encode(["status" => "error", "message" => "Invalid request parameters.", ]);
            return;
        }
        $endpoint = $this->getApiEndpoint($typeId);
        if (!$endpoint) {
            echo json_encode(["status" => "error", "message" => "API endpoint not found.", ]);
            return;
        }
        $fileUrl = $this->getFileLocation($scanId);
        if (!$fileUrl) {
            echo json_encode(["status" => "error", "message" => "File not found.", ]);
            return;
        }
        $apiResponse = $this->callExternalApi($endpoint, $fileUrl);
        if ($apiResponse["statusCode"] !== 200 || empty($apiResponse["data"])) {
            echo json_encode(["status" => "error", "message" => "API call failed.", ]);
            return;
        }
        $saveStatus = $this->storeExtractedData($typeId, $scanId, $apiResponse["data"]);
        if ($saveStatus) {
            echo json_encode(["status" => "success", "message" => "Data stored successfully.", ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to store data.", ]);
        }
    }
    private function isValidDate($date) {
        if (empty($date)) {
            return false; 
        }
    
        $timestamp = strtotime($date);
        return $timestamp !== false && date('Y-m-d', $timestamp) === date('Y-m-d', $timestamp);
    }

    private function storeExtractedData($typeId, $scanId, $data) {
        $tableName = "ext_tempdata_" . $typeId;
        if (!$this->db->table_exists($tableName)) {
            return false;
        }
        $nestedKeys = ["Details", "Items", "Employees", "Distance Details", "Head Details", "Perticulars"];
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
                        $deleteTempData = $this->db->where("Scan_Id", $scanId)->delete($detailsTable);
                        $detailsColumns = $this->db->list_fields($detailsTable);
                        if($deleteTempData){
                             foreach ($data[$section] as $item) {
                            $detailsData = ["scan_id" => $scanId];
                            foreach ($item as $key => $value) {
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
        $punchData = ["Scan_Id" => $scanId, "DocType" => $docType, "DocTypeId" => $docTypeId, "Group_Id" => $this->session->userdata("group_id"), "Created_By" => $this->session->userdata("user_id"), "Created_Date" => date("Y-m-d H:i:s"), ];
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
        $this->db->insert("sub_punchfile", ["FileID" => $fileID, "Amount" => "-" . ($punchData["Total_Amount"]??0), "Comment" => $punchData["Remark"]??"", ]);
        $detailsTable = "ext_tempdata_{$docTypeId}_details";
        if ($this->db->table_exists($detailsTable)) {
            $detailsData = $this->db->where("Scan_Id", $scanId)->get($detailsTable)->result_array();
            $details_mappings = $this->db->select("temp_column, input_type, select_table, relation_column, relation_value, punch_column, punch_table, add_condition")
                ->where("doctype_id", $docTypeId)
                ->where("has_Items_feild", "Y")
                ->where_not_in("punch_table", ["punchfile", "punchfile2"])
                ->get("ext_field_mappings")
                ->result_array();
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
    // private function getClosestValueMatch($table, $searchColumn, $searchValue, $returnColumn, $addCondition) {
    //     $ci = & get_instance();
    //     $ci->load->database();
    //     if (empty($table) || empty($searchColumn) || empty($returnColumn)) {
    //         return null;  
    //     }
    //     $searchValue = trim((string)$searchValue);
    //     $ci->db->select("$searchColumn, $returnColumn");
    //     $ci->db->from($table);
    //     if (!empty($addCondition)) {
    //         $ci->db->where($addCondition);
    //     }
    //     $list = $ci->db->get()->result();
    //     if (empty($list)) {
    //         return null;
    //     }
    //     $bestMatch = null;
    //     $bestRelationValue = null;
    //     $highestSimilarity = 0;
    //     $smallestDistance = PHP_INT_MAX;
    //     foreach ($list as $item) {
    //         $itemValue = isset($item->$searchColumn) ? trim((string)$item->$searchColumn) : "";
    //         $relationValue = isset($item->$returnColumn) ? trim((string)$item->$returnColumn) : null;
    //         if (empty($itemValue) || empty($relationValue)) {
    //             continue;
    //         }
    //         if ($itemValue === $searchValue) {
    //             return $relationValue;
    //         }
    //         if (strpos($itemValue, $searchValue) !== false || strpos($searchValue, $itemValue) !== false) {
    //             return $relationValue;
    //         }
    //         if (is_numeric($searchValue) && is_numeric($itemValue)) {
    //             $distance = abs($searchValue - $itemValue);
    //         } else {
    //             similar_text($searchValue, $itemValue, $percent);
    //             $distance = levenshtein($searchValue, $itemValue);
    //         }
    //         if ($percent > $highestSimilarity || ($percent == $highestSimilarity && $distance < $smallestDistance)) {
    //             $highestSimilarity = $percent;
    //             $smallestDistance = $distance;
    //             $bestRelationValue = $relationValue;
    //         }
    //     }
    //     return $bestRelationValue??$searchValue;
    // }
    private function getClosestValueMatch($table, $searchColumn, $searchValue, $returnColumn, $addCondition) {
        $ci = & get_instance();
        $ci->load->database();
    
        if (empty($table) || empty($searchColumn) || empty($returnColumn)) {
            return null;  
        }
    
        $searchValue = trim((string)$searchValue);
    
        $ci->db->select($returnColumn);
        $ci->db->from($table);
        $ci->db->where($searchColumn, $searchValue);
    
        if (!empty($addCondition)) {
            $ci->db->where($addCondition);
        }
    
        $result = $ci->db->get()->row();
        // echo $this->db->last_query();
        // exit;
    
        return $result ? $result->$returnColumn : null;
    }
    private function getApiEndpoint($typeId) {
        $this->db->select("endpoint");
        $this->db->from("ext_mater_api_control");
        $this->db->where(["doctype_id" => $typeId, "status" => 1]);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row()->endpoint : null;
    }
    private function getFileLocation($scanId) {
        $this->db->select("File_Location");
        $this->db->from("scan_file");
        $this->db->where("Scan_Id", $scanId);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row()->File_Location : null;
    }
    private function callExternalApi($endpoint, $fileUrl) {
        $postData = json_encode(["fileUrl" => $fileUrl]);
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Accept: application/json"]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode === 200 ? json_decode($response, true) : ["status" => "error", "message" => "API call failed."];
    }
    public function getFieldDetails($has_items_feild) {
        $doctype_id = $this->input->post("doctype_id");
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
                "input_type" => $mapping["input_type"]??"", 
                "select_table" => $mapping["select_table"]??"", 
                "relation_column" => $mapping["relation_column"]??"", 
                "relation_value" => $mapping["relation_value"]??"", 
                "punch_table" => $mapping["punch_table"]??"", 
                "punch_column" => $mapping["punch_column"]??"", 
                "add_condition" => $mapping["add_condition"]??"", 
                ];
        }
        echo json_encode($columns);
    }
    public function getPunchTableColumns() {
        $table = $this->input->post("table");
        $query = $this->db->query("SHOW COLUMNS FROM `$table`");
        $columns = [];
        foreach ($query->result() as $row) {
            $columns[] = $row->Field;
        }
        echo json_encode($columns);
    }
    public function getAllTables() {
        $query = $this->db->query("SHOW TABLES");
        $tables = [];
        foreach ($query->result_array() as $row) {
            $tables[] = reset($row);
        }
        echo json_encode($tables);
    }
    public function getTableColumns() {
        $table = $this->input->post("table");
        $query = $this->db->query("SHOW COLUMNS FROM `$table`");
        $columns = [];
        foreach ($query->result() as $row) {
            $columns[] = $row->Field;
        }
        echo json_encode($columns);
    }
    public function saveFieldMappings() {
    $doctype_id = $this->input->post("doctype_id");
    $has_items_feild = $this->input->post("has_items_feild");
    $fieldMappings = $this->input->post("fieldMappings");
    
    $defaultMappings = [
        [
            "has_items_feild" => $has_items_feild,
            "temp_column" => "scan_id",
            "input_type" => "input",
            "select_table" => null,
            "relation_column" => null,
            "relation_value" => null,
            "punch_table" => "punchfile",
            "punch_column" => "Scan_Id",
        ],
        [
            "has_items_feild" => $has_items_feild,
            "temp_column" => "group_id",
            "input_type" => "input",
            "select_table" => null,
            "relation_column" => null,
            "relation_value" => null,
            "punch_table" => "punchfile",
            "punch_column" => "Group_Id",
        ],
    ];
    
    $allMappings = array_merge($defaultMappings, $fieldMappings);
    $errors = [];
    
    $this->db->where(["doctype_id" => $doctype_id, "has_items_feild" => $has_items_feild])->delete("ext_field_mappings");
    
    foreach ($allMappings as $field) {
        if (empty($field["punch_table"]) || empty($field["punch_column"])) {
            continue;
        }
        
        $data = [
            "doctype_id" => $doctype_id,
            "has_items_feild" => $has_items_feild,
            "temp_column" => $field["temp_column"],
            "input_type" => $field["input_type"],
            "select_table" => !empty($field["select_table"]) ? $field["select_table"] : null,
            "relation_column" => !empty($field["relation_column"]) ? $field["relation_column"] : null,
            "relation_value" => !empty($field["relation_value"]) ? $field["relation_value"] : null,
            "punch_table" => $field["punch_table"],
            "punch_column" => $field["punch_column"],
            "add_condition" => isset($field["add_condition"]) ? $field["add_condition"] : null,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
        ];
        
        if (!$this->db->insert("ext_field_mappings", $data)) {
            $errors[] = "Failed to insert mapping for column: " . $field["temp_column"];
        }
    }
    
    if (empty($errors)) {
        echo json_encode(["status" => "success", "message" => "Field mappings saved successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Some field mappings failed to save.", "errors" => $errors]);
    }
}
}
