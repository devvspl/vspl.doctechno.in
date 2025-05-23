<?php
defined("BASEPATH") or exit("No direct script access allowed");
class JournalEntry_ctrl extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Scan_model");
    }
    public function create()
    {
        if ($this->customlib->has_permission('Finance') == 1) {
            if ($this->input->post("submit")) {
                $scan_id = $this->input->post("scan_id");
                $this->db->where(['scan_id' => $scan_id]);
                $query = $this->db->update("y{$this->year_id}_scan_file", ['finance_punch_action_status' => 'Y', 'finance_punched_date' => date('Y-m-d'), 'finance_punched_by' => $this->session->userdata("user_id")]);
                $this->customlib->update_file_path($scan_id);
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata("message", '<div class="alert alert-danger text-left">Transaction Failed</div>');
                } else {
                    $this->db->trans_commit();
                    $this->session->set_flashdata("message", '<div class="alert alert-success text-left">Invoice Saved Successfully</div>');
                }
                redirect("finance_punch");
            } else {
                $document_number = $this->input->post("document_number");
                $scan_id = $this->input->post("scan_id");
                $group_id = $this->session->userdata("group_id");
                $DocTypeId = $this->input->post("DocTypeId");
                $punch_date = date('Y-m-d');
                $business_entity_id = $this->input->post("business_entity_id");
                $narration = $this->input->post("narration");
                $finance_total_Amount = $this->input->post("finance_total_Amount");
                $DepartmentID = $this->input->post("DepartmentID[]") ?? [];
                $business_unit_id = $this->input->post("business_unit_id[]") ?? [];
                $state_id = $this->input->post("state_id[]") ?? [];
                $region_id = $this->input->post("region_id[]") ?? [];
                $location_id = $this->input->post("location_id[]") ?? [];
                $category_id = $this->input->post("category_id[]") ?? [];
                $crop_id = $this->input->post("crop_id[]") ?? [];
                $activity_id = $this->input->post("activity_id[]") ?? [];
                $subledger = $this->input->post("subledger[]") ?? [];
                $debit_ac = $this->input->post("debit_ac[]") ?? [];
                $credit_ac = $this->input->post("credit_ac[]") ?? [];
                $debit_ac_id = $this->input->post("debit_ac_id[]") ?? [];
                $credit_ac_id = $this->input->post("credit_ac_id[]") ?? [];
                $Total_Amount = $this->input->post("Item_amount[]") ?? [];
                $ReferenceNo = $this->input->post("Item_ReferenceNo[]") ?? [];
                $Remark = $this->input->post("Item_Remark[]") ?? [];
                $tdsApplicable = $this->input->post("tdsApplicable");
                $TDS_JV_no = $this->input->post("TDS_JV_no");
                $TDS_section = $this->input->post("TDS_section");
                $TDS_percentage = $this->input->post("TDS_percentage");
                $TDS_amount = $this->input->post("TDS_amount");
                $this->db->trans_start();
                $this->db->trans_strict(false);
                $data = ["finance_punch_date" => $punch_date, "business_entity_id" => $business_entity_id, "narration" => $narration, "document_number" => $document_number, "finance_total_Amount" => $finance_total_Amount, "tdsApplicable" => $tdsApplicable, "TDS_JV_no" => $TDS_JV_no, "TDS_section" => $TDS_section, "TDS_percentage" => $TDS_percentage, "TDS_amount" => $TDS_amount, "finance_punched_by" => $this->session->userdata("user_id"),];
                $this->db->where(['scan_id' => $scan_id]);
                $query = $this->db->update('punchfile', $data);
                if ($query) {
                    $this->db->where('scan_id', $scan_id);
                    $existing = $this->db->get('journal_entry_items')->result();
                    if (!empty($existing)) {
                        $this->db->where('scan_id', $scan_id);
                        $this->db->delete('journal_entry_items');
                    }
                    for ($i = 0; $i < count($DepartmentID); $i++) {
                        $json_data = ['scan_id' => $scan_id, 'DepartmentID' => isset($DepartmentID[$i]) ? $DepartmentID[$i] : null, 'business_unit_id' => isset($business_unit_id[$i]) ? $business_unit_id[$i] : null, 'state_id' => isset($state_id[$i]) ? $state_id[$i] : null, 'region_id' => isset($region_id[$i]) ? $region_id[$i] : null, 'location_id' => isset($location_id[$i]) ? $location_id[$i] : null, 'category_id' => isset($category_id[$i]) ? $category_id[$i] : null, 'crop_id' => isset($crop_id[$i]) ? $crop_id[$i] : null, 'activity_id' => isset($activity_id[$i]) ? $activity_id[$i] : null, 'subledger' => isset($subledger[$i]) ? $subledger[$i] : null, 'debit_ac' => isset($debit_ac[$i]) ? $debit_ac[$i] : null, 'credit_ac' => isset($credit_ac[$i]) ? $credit_ac[$i] : null, 'debit_ac_id' => isset($debit_ac_id[$i]) ? $debit_ac_id[$i] : null, 'credit_ac_id' => isset($credit_ac_id[$i]) ? $credit_ac_id[$i] : null, 'Total_Amount' => isset($Total_Amount[$i]) ? $Total_Amount[$i] : null, 'ReferenceNo' => isset($ReferenceNo[$i]) ? $ReferenceNo[$i] : null, 'Remark' => isset($Remark[$i]) ? $Remark[$i] : null, "Created_By" => $this->session->userdata("user_id"),];
                        $this->db->insert('journal_entry_items', $json_data);
                    }
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === false) {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata("message", '<div class="alert alert-success text-left">Invoice Detail Saved Successfully.</div>');
                        redirect('file_entry/' . $scan_id . '/' . $DocTypeId);
                    }
                } else {
                    $this->session->set_flashdata("message", '<div class="alert alert-danger text-left">Failed to update punchfile.</div>');
                }
                redirect('file_entry/' . $scan_id . '/' . $DocTypeId);
            }
        } else {
            set_time_limit(0);
            ini_set("max_execution_time", 0);
            $scan_id = $this->input->post("scan_id");
            $DocTypeId = $this->input->post("DocTypeId");
            $DocType = $this->customlib->getDocType($DocTypeId);
            $Bill_Date = $this->input->post("Bill_Date");
            $Bill_No = $this->input->post("Bill_No");
            $Payment_Mode = $this->input->post("Payment_Mode");
            $Supplier_Ref = $this->input->post("Supplier_Ref");
            $From = $this->input->post("From");
            $FromName = $this->customlib->getCompanyNameById($From);
            $To = $this->input->post("To");
            $ToName = $this->customlib->getCompanyNameById($To);
            $Buyer_Address = $this->input->post("Buyer_Address");
            $Vendor_Address = $this->input->post("Vendor_Address");
            $Buyer_Order = $this->input->post("Buyer_Order");
            $Buyer_Order_Date = $this->input->post("Buyer_Order_Date");
            $Dispatch_Trough = $this->input->post("Dispatch_Trough");
            $Delivery_Note_Date = $this->input->post("Delivery_Note_Date");
            $DepartmentId = $this->input->post("Department");
            $Department = $this->customlib->getDepatmentNameById($DepartmentId);
            $Category = $this->input->post("Category");
            $Ledger = $this->input->post("Ledger");
            $File = $this->input->post("File");
            $LR_Number = $this->input->post("LR_Number");
            $LR_Date = $this->input->post("LR_Date");
            $Cartoon_Number = $this->input->post("Cartoon_Number");
            $Location = $this->input->post("Location");
            $Sub_Total = $this->input->post("Sub_Total");
            $Total = $this->input->post("Total");
            $Total_Discount = $this->input->post("Total_Discount");
            $Grand_Total = $this->input->post("Grand_Total");
            $GST = $this->input->post("GST");
            $SGST = $this->input->post("SGST");
            $IGST = $this->input->post("IGST");
            $Cess = $this->input->post("Cess");
            $TCS = $this->input->post("TCS");
            $Particular = $this->input->post("Particular");
            $HSN = $this->input->post("HSN");
            $Qty = $this->input->post("Qty");
            $Unit = $this->input->post("Unit");
            $MRP = $this->input->post("MRP");
            $Discount = $this->input->post("Discount");
            $Price = $this->input->post("Price");
            $Amount = $this->input->post("Amount");
            $TAmount = $this->input->post("TAmount");
            $Remark = $this->input->post("Remark");
            $data = ["scan_id" => $scan_id, "Group_Id" => $this->session->userdata("group_id"), "DocType" => $DocType, "DocTypeId" => $DocTypeId, "BillDate" => $Bill_Date, "File_No" => $Bill_No, "NatureOfPayment" => $Payment_Mode, "ReferenceNo" => $Supplier_Ref, "From_ID" => $From, "FromName" => $FromName, "To_ID" => $To, "ToName" => $ToName, "Loc_Add" => $Buyer_Address, "AgencyAddress" => $Vendor_Address, "ServiceNo" => $Buyer_Order, "BookingDate" => $Buyer_Order_Date, "Particular" => $Dispatch_Trough, "DueDate" => $Delivery_Note_Date, "Department" => $Department, "DepartmentID" => $DepartmentId, "Category" => $Category, "Ledger" => $Ledger, "FileName" => $File, "FDRNo" => $LR_Number, "File_Date" => $LR_Date, "RegNo" => $Cartoon_Number, "SubTotal" => $Sub_Total, "Total_Amount" => $Total, "Grand_Total" => $Grand_Total, "Total_Discount" => $Total_Discount, "TCS" => $TCS, "Loc_Name" => $Location, "Remark" => $Remark, "Created_By" => $this->session->userdata("user_id"), "Created_Date" => date("Y-m-d H:i:s"),];
            $this->db->trans_start();
            $this->db->trans_strict(FALSE);
            if ($this->customlib->check_punchfile($scan_id) == true) {
                $this->db->where("scan_id", $scan_id)->update("punchfile", $data);
                $FileID = $this->db->where("scan_id", $scan_id)->get("punchfile")->row()->FileID;
                $this->db->where("FileID", $FileID)->update("sub_punchfile", ["Amount" => "-" . $Grand_Total, "Comment" => $Remark,]);
                $this->db->where("scan_id", $scan_id)->delete("invoice_detail");
                $array = [];
                for ($i = 0; $i < count($Particular); $i++) {
                    $array[] = ["scan_id" => $scan_id, "Particular" => $Particular[$i], "HSN" => $HSN[$i], "Qty" => $Qty[$i], "Unit" => $Unit[$i], "MRP" => $MRP[$i], "Discount" => $Discount[$i], "Price" => $Price[$i], "Amount" => $Amount[$i], "GST" => $GST[$i], "SGST" => $SGST[$i], "IGST" => $IGST[$i], "Cess" => $Cess[$i], "Total_Amount" => $TAmount[$i],];
                }
                $this->db->insert_batch("invoice_detail", $array);
                $this->db->where("scan_id", $scan_id)->update("y{$this->year_id}_scan_file", ["Is_Rejected" => "N", "Reject_Date" => null, "Edit_Permission" => "N",]);
            } else {
                $this->db->insert("punchfile", $data);
                $insert_id = $this->db->insert_id();
                $this->db->insert("sub_punchfile", ["FileID" => $insert_id, "Amount" => "-" . $Grand_Total, "Comment" => $Remark,]);
                $array = [];
                for ($i = 0; $i < count($Particular); $i++) {
                    $array[] = ["scan_id" => $scan_id, "Particular" => $Particular[$i], "HSN" => $HSN[$i], "Qty" => $Qty[$i], "Unit" => $Unit[$i], "MRP" => $MRP[$i], "Discount" => $Discount[$i], "Price" => $Price[$i], "Amount" => $Amount[$i], "GST" => $GST[$i], "SGST" => $SGST[$i], "IGST" => $IGST[$i], "Cess" => $Cess[$i], "Total_Amount" => $TAmount[$i],];
                }
                $this->db->insert_batch("invoice_detail", $array);
            }
            if ($this->input->post("submit_punch")) {
                $scan_id = (int)$this->input->post('scan_id');
                $query = $this->db->where("scan_id", $scan_id)->update("y{$this->year_id}_scan_file", ["finance_punch" => "N", 'punched_by' => $this->session->userdata("user_id"), 'punched_date' => date('Y-m-d H:i:s')]);
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata("message", '<div class="alert alert-danger text-left">Transaction Failed</div>');
                } else {
                    $this->db->trans_commit();
                    $this->session->set_flashdata("message", '<div class="alert alert-success text-left">Invoice Saved Successfully</div>');
                }
                redirect("punch");
            } elseif ($this->input->post("submit")) {
                $this->customlib->update_file_path($scan_id);
                $this->db->trans_complete();
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata("message", '<div class="alert alert-danger text-left">Transaction Failed</div>');
                } else {
                    $this->db->trans_commit();
                    $this->session->set_flashdata("message", '<div class="alert alert-success text-left">Invoice Saved Successfully</div>');
                }
                redirect("punch");
            } else {
                $this->db->trans_complete();
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Data Saved In Draft</div>');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }
    function getInvoiceItem()
    {
        $scan_id = $this->input->post("scan_id");
        $result = $this->db->select("*")->from("invoice_detail")->where("scan_id", $scan_id)->get()->result_array();
        if (!empty($result)) {
            echo json_encode(["status" => 200, "data" => $result]);
        } else {
            echo json_encode(["status" => 400, "msg" => "No Record Found"]);
        }
    }
    function add_item()
    {
        $item_name = $this->input->post("item_name");
        $item_code = $this->input->post("item_code");
        $item_data = ["item_name" => $item_name, "item_code" => $item_code, "focus_data" => "N", "status" => "A", "is_deleted" => "N", "created_by" => $this->session->userdata("user_id"), "created_at" => date("Y-m-d H:i:s"),];
        $this->db->insert("master_item", $item_data);
        if ($this->db->affected_rows() > 0) {
            echo json_encode(["status" => 200, "msg" => "Item added successfully",]);
        } else {
            echo json_encode(["status" => 400, "msg" => "Something went wrong",]);
        }
    }
    public function sale_bill_save()
    {
        $scan_id = $this->input->post("scan_id");
        $DocTypeId = $this->input->post("DocTypeId");
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Bill_Date = $this->input->post("Bill_Date");
        $Bill_No = $this->input->post("Bill_No");
        $Payment_Mode = $this->input->post("Payment_Mode");
        $Supplier_Ref = $this->input->post("Supplier_Ref");
        $From = $this->input->post("From");
        $FromName = $this->customlib->getCompanyNameById($From);
        $To = $this->input->post("To");
        $ToNmae = $this->customlib->getCompanyNameById($To);
        $Buyer_Address = $this->input->post("Buyer_Address");
        $Vendor_Address = $this->input->post("Vendor_Address");
        $Buyer_Order = $this->input->post("Buyer_Order");
        $Buyer_Order_Date = $this->input->post("Buyer_Order_Date");
        $Dispatch_Trough = $this->input->post("Dispatch_Trough");
        $Delivery_Note_Date = $this->input->post("Delivery_Note_Date");
        $DepartmentId = $this->input->post("Department");
        $Department = $this->customlib->getDepatmentNameById($DepartmentId);
        $Category = $this->input->post("Category");
        $Ledger = $this->input->post("Ledger");
        $File = $this->input->post("File");
        $LR_Number = $this->input->post("LR_Number");
        $LR_Date = $this->input->post("LR_Date");
        $Cartoon_Number = $this->input->post("Cartoon_Number");
        $Consignee_Name = $this->input->post("Consignee_Name");
        $Sub_Total = $this->input->post("Sub_Total");
        $Total = $this->input->post("Total");
        $Total_Discount = $this->input->post("Total_Discount");
        $Grand_Total = $this->input->post("Grand_Total");
        $GST = $this->input->post("GST");
        $SGST = $this->input->post("SGST");
        $IGST = $this->input->post("IGST");
        $Cess = $this->input->post("Cess");
        $TCS = $this->input->post("TCS");
        $Particular = $this->input->post("Particular");
        $HSN = $this->input->post("HSN");
        $Qty = $this->input->post("Qty");
        $Unit = $this->input->post("Unit");
        $MRP = $this->input->post("MRP");
        $Discount = $this->input->post("Discount");
        $Price = $this->input->post("Price");
        $Amount = $this->input->post("Amount");
        $TAmount = $this->input->post("TAmount");
        $Remark = $this->input->post("Remark");
        $data = ["scan_id" => $scan_id, "Group_Id" => $this->session->userdata("group_id"), "DocType" => $DocType, "DocTypeId" => $DocTypeId, "BillDate" => $Bill_Date, "File_No" => $Bill_No, "NatureOfPayment" => $Payment_Mode, "ReferenceNo" => $Supplier_Ref, "From_ID" => $From, "FromName" => $FromName, "To_ID" => $To, "ToName" => $ToNmae, "Loc_Add" => $Buyer_Address, "AgencyAddress" => $Vendor_Address, "ServiceNo" => $Buyer_Order, "BookingDate" => $Buyer_Order_Date, "Particular" => $Dispatch_Trough, "DueDate" => $Delivery_Note_Date, "Department" => $Department, "DepartmentID" => $DepartmentId, "Category" => $Category, "Ledger" => $Ledger, "FileName" => $File, "FDRNo" => $LR_Number, "File_Date" => $LR_Date, "RegNo" => $Cartoon_Number, "AgentName" => $Consignee_Name, "SubTotal" => $Sub_Total, "Total_Amount" => $Total, "Grand_Total" => $Grand_Total, "Total_Discount" => $Total_Discount, "TCS" => $TCS, "Remark" => $Remark, "Created_By" => $this->session->userdata("user_id"), "Created_Date" => date("Y-m-d H:i:s"),];
        $this->db->trans_start();
        $this->db->trans_strict(false);
        if ($this->customlib->check_punchfile($scan_id) == true) {
            $this->db->where("scan_id", $scan_id)->update("punchfile", $data);
            $FileID = $this->db->where("scan_id", $scan_id)->get("punchfile")->row()->FileID;
            $this->db->where("FileID", $FileID)->update("sub_punchfile", ["Amount" => "-" . $Grand_Total, "Comment" => $Remark,]);
            $this->db->where("scan_id", $scan_id)->delete("invoice_detail");
            $array = [];
            for ($i = 0; $i < count($Particular); $i++) {
                $array[$i] = ["scan_id" => $scan_id, "Particular" => $Particular[$i], "HSN" => $HSN[$i], "Qty" => $Qty[$i], "Unit" => $Unit[$i], "MRP" => $MRP[$i], "Discount" => $Discount[$i], "Price" => $Price[$i], "Amount" => $Amount[$i], "GST" => $GST[$i], "SGST" => $SGST[$i], "IGST" => $IGST[$i], "Cess" => $Cess[$i], "Total_Amount" => $TAmount[$i],];
            }
            $this->db->insert_batch("invoice_detail", $array);
            $this->db->where("scan_id", $scan_id)->update("y{$this->year_id}_scan_file", ["Is_Rejected" => "N", "Reject_Date" => null, "Edit_Permission" => "N",]);
        } else {
            $this->db->insert("punchfile", $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert("sub_punchfile", ["FileID" => $insert_id, "Amount" => "-" . $Grand_Total, "Comment" => $Remark,]);
            $array = [];
            for ($i = 0; $i < count($Particular); $i++) {
                $array[$i] = ["scan_id" => $scan_id, "scan_id" => $scan_id, "Particular" => $Particular[$i], "HSN" => $HSN[$i], "Qty" => $Qty[$i], "Unit" => $Unit[$i], "MRP" => $MRP[$i], "Discount" => $Discount[$i], "Price" => $Price[$i], "Amount" => $Amount[$i], "GST" => $GST[$i], "SGST" => $SGST[$i], "IGST" => $IGST[$i], "Cess" => $Cess[$i], "Total_Amount" => $TAmount[$i],];
            }
            $this->db->insert_batch("invoice_detail", $array);
        }
        $this->customlib->update_file_path($scan_id);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->session->set_flashdata("message", '<div class="alert alert-success text-left">Something Went Wrong</div>');
            redirect("punch");
        } else {
            $this->session->set_flashdata("message", '<div class="alert alert-success text-left">Sale Bill added successfully</div>');
            redirect("punch");
        }
    }
    public function getAccountOptions()
    {
        $type = $this->input->get('type');
        $query = $this->input->get('query');
        $ledgerTypeId = ($type === 'debit') ? 2 : 1;
        $this->db->like('account_name', $query);
        $this->db->where('ledger_type_id', $ledgerTypeId);
        $this->db->limit(5);
        $accounts = $this->db->get('master_account_ledger')->result_array();
        echo json_encode($accounts);
    }

    public function getAllAccountList()
    {
        $query = $this->input->get('query');
        $this->db->like('account_name', $query);
        $this->db->limit(8);
        $accounts = $this->db->get('master_account_ledger')->result_array();
        echo json_encode($accounts);
    }


    public function getSubLedgers()
    {
        $debitAcId = $this->input->post('debit_ac_id');
        
        if (!empty($debitAcId)) {
            $this->db->select('id, sub_ledger');
            $this->db->from('master_sub_ledger');
            $this->db->where('cr_ledger', $debitAcId);
            $query = $this->db->get();
            
            // Fetch the result as an array
            $subLedgers = $query->result_array();
            
            // Return the result in JSON format
            echo json_encode($subLedgers);
        } else {
            echo json_encode([]);
        }
    }
    

}
