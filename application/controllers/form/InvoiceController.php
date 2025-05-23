<?php
defined('BASEPATH') or exit('No direct script access allowed');
class InvoiceController extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Scan_model');
    }
    public function create() {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $post = $this->input->post();
        $Scan_Id = $post['scan_id'];
        $DocTypeId = $post['DocTypeId'];
        $From = $post['From'];
        $To = $post['To'];
        $data = ['scan_id' => $Scan_Id, 'group_id' => $this->session->userdata('group_id'), 'DocType' => $this->customlib->getDocType($DocTypeId), 'DocTypeId' => $DocTypeId, 'BillDate' => $post['Bill_Date'], 'File_No' => $post['Bill_No'], 'NatureOfPayment' => $post['Payment_Mode'], 'ReferenceNo' => $post['Supplier_Ref'], 'From_ID' => $From, 'FromName' => $this->customlib->getCompanyNameById($From), 'To_ID' => $To, 'ToName' => $this->customlib->getCompanyNameById($To), 'Loc_Add' => $post['Buyer_Address'], 'AgencyAddress' => $post['Vendor_Address'], 'ServiceNo' => $post['Buyer_Order'], 'BookingDate' => $post['Buyer_Order_Date'], 'Particular' => $post['Dispatch_Trough'], 'DueDate' => $post['Delivery_Note_Date'], 'Category' => $post['Category'], 'FDRNo' => $post['LR_Number'], 'File_Date' => $post['LR_Date'], 'RegNo' => $post['Cartoon_Number'], 'SubTotal' => $post['Sub_Total'], 'Total_Amount' => $post['Total'], 'Grand_Total' => $post['Grand_Total'], 'Total_Discount' => $post['Total_Discount'], 'TCS' => $post['TCS'],'Remark' => $post['Remark'], 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'), ];
        $is_update = $this->customlib->check_punchfile($Scan_Id);
        if ($is_update) {
            $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->select('FileID')->where('scan_id', $Scan_Id)->get('punchfile')->row('FileID');
            $this->db->where('FileID', $FileID)->update('sub_punchfile', ['Amount' => '-' . $post['Grand_Total'], 'Comment' => $post['Remark'], ]);
            $this->db->where('scan_id', $Scan_Id)->delete('invoice_detail');
        } else {
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', ['FileID' => $insert_id, 'Amount' => '-' . $post['Grand_Total'], 'Comment' => $post['Remark'], ]);
        }
        $invoice_details = [];
        $count = count($post['Particular']);
        for ($i = 0;$i < $count;$i++) {
            $unit = isset($post['Unit'][$i]) && $post['Unit'][$i] !== '' ? $post['Unit'][$i] : 'PCS';
            $invoice_details[] = ['scan_id' => $Scan_Id, 'Particular' => $post['Particular'][$i], 'HSN' => $post['HSN'][$i], 'Qty' => $post['Qty'][$i], 'Unit' => $unit, 'MRP' => $post['MRP'][$i], 'Discount' => $post['Discount'][$i], 'Price' => $post['Price'][$i], 'Amount' => $post['Amount'][$i], 'GST' => $post['GST'][$i], 'SGST' => $post['SGST'][$i], 'IGST' => $post['IGST'][$i], 'Cess' => $post['Cess'][$i], 'Total_Amount' => $post['TAmount'][$i], ];
        }
        if (!empty($invoice_details)) {
            $this->db->insert_batch('invoice_detail', $invoice_details);
        }
        if ($is_update) {
            $this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", ['is_rejected' => 'N', 'finance_punch_action_status' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N', ]);
        }
        $this->db->trans_complete();
        if ($post['submit']) {
            $this->customlib->update_file_path($Scan_Id);
        }
        if ($this->db->trans_status() === false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger text-left">Something went wrong</div>');
        } else {
            if ($post['submit']) {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Invoice added successfully</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Data saved in Draft</div>');
            }
        }
        redirect($post['submit'] ? 'punch' : $_SERVER['HTTP_REFERER']);
    }
    public function getInvoiceItem() {
        $Scan_Id = $this->input->post('scan_id');
        $result = $this->db->select('*')->from('invoice_detail')->where('scan_id', $Scan_Id)->get()->result_array();
        if (!empty($result)) {
            echo json_encode(array('status' => 200, 'data' => $result));
        } else {
            echo json_encode(array('status' => 400, 'msg' => 'No Record Found'));
        }
    }
    public function add_item() {
        $item_name = $this->input->post('item_name');
        $item_code = $this->input->post('item_code');
        $item_data = array('item_name' => $item_name, 'item_code' => $item_code, 'focus_data' => 'N', 'status' => 'A', 'is_deleted' => 'N', 'created_by' => $this->session->userdata('user_id'), 'created_at' => date('Y-m-d H:i:s'),);
        $this->db->insert('master_item', $item_data);
        if ($this->db->affected_rows() > 0) {
            echo json_encode(array('status' => 200, 'msg' => 'Item added successfully'));
        } else {
            echo json_encode(array('status' => 400, 'msg' => 'Something went wrong'));
        }
    }
    public function sale_bill_save() {
        $Scan_Id = $this->input->post('scan_id');
        $DocTypeId = $this->input->post('DocTypeId');
        $DocType = $this->customlib->getDocType($DocTypeId);
        $Bill_Date = $this->input->post('Bill_Date');
        $Bill_No = $this->input->post('Bill_No');
        $Payment_Mode = $this->input->post('Payment_Mode');
        $Supplier_Ref = $this->input->post('Supplier_Ref');
        $From = $this->input->post('From');
        $FromName = $this->customlib->getCompanyNameById($From);
        $To = $this->input->post('To');
        $ToNmae = $this->customlib->getCompanyNameById($To);
        $Buyer_Address = $this->input->post('Buyer_Address');
        $Vendor_Address = $this->input->post('Vendor_Address');
        $Buyer_Order = $this->input->post('Buyer_Order');
        $Buyer_Order_Date = $this->input->post('Buyer_Order_Date');
        $Dispatch_Trough = $this->input->post('Dispatch_Trough');
        $Delivery_Note_Date = $this->input->post('Delivery_Note_Date');
        $DepartmentId = $this->input->post('Department');
        $Department = $this->customlib->getDepatmentNameById($DepartmentId);
        $Category = $this->input->post('Category');
        $Ledger = $this->input->post('Ledger');
        $File = $this->input->post('file_name');
        $LR_Number = $this->input->post('LR_Number');
        $LR_Date = $this->input->post('LR_Date');
        $Cartoon_Number = $this->input->post('Cartoon_Number');
        $Consignee_Name = $this->input->post('Consignee_Name');
        $Sub_Total = $this->input->post('Sub_Total');
        $Total = $this->input->post('Total');
        $Total_Discount = $this->input->post('Total_Discount');
        $Grand_Total = $this->input->post('Grand_Total');
        $GST = $this->input->post('GST');
        $SGST = $this->input->post('SGST');
        $IGST = $this->input->post('IGST');
        $Cess = $this->input->post('Cess');
        $TCS = $this->input->post('TCS');
        $Particular = $this->input->post('Particular');
        $HSN = $this->input->post('HSN');
        $Qty = $this->input->post('Qty');
        $Unit = $this->input->post('Unit');
        $MRP = $this->input->post('MRP');
        $Discount = $this->input->post('Discount');
        $Price = $this->input->post('Price');
        $Amount = $this->input->post('Amount');
        $TAmount = $this->input->post('TAmount');
        $Remark = $this->input->post('Remark');
        $data = array('scan_id' => $Scan_Id, 'group_id' => $this->session->userdata('group_id'), 'DocType' => $DocType, 'DocTypeId' => $DocTypeId, 'BillDate' => $Bill_Date, 'File_No' => $Bill_No, 'From_ID' => $From, 'FromName' => $FromName, 'To_ID' => $To, 'ToName' => $ToNmae, 'Loc_Add' => $Buyer_Address, 'AgencyAddress' => $Vendor_Address, 'ServiceNo' => $Buyer_Order, 'BookingDate' => $Buyer_Order_Date, 'Particular' => $Dispatch_Trough, 'DueDate' => $Delivery_Note_Date, 'Department' => $Department, 'DepartmentID' => $DepartmentId, 'Ledger' => $Ledger, 'FileName' => $File, 'FDRNo' => $LR_Number, 'File_Date' => $LR_Date, 'RegNo' => $Cartoon_Number, 'AgentName' => $Consignee_Name, 'SubTotal' => $Sub_Total, 'Total_Amount' => $Total, 'Grand_Total' => $Grand_Total, 'Total_Discount' => $Total_Discount, 'TCS' => $TCS, 'Remark' => $Remark, 'Created_By' => $this->session->userdata('user_id'), 'Created_Date' => date('Y-m-d H:i:s'),);
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if ($this->customlib->check_punchfile($Scan_Id) == true) {
            $this->db->where('scan_id', $Scan_Id)->update('punchfile', $data);
            $FileID = $this->db->where('scan_id', $Scan_Id)->get('punchfile')->row()->FileID;
            $this->db->where('FileID', $FileID)->update('sub_punchfile', array('Amount' => '-' . $Grand_Total, 'Comment' => $Remark));
            $this->db->where('scan_id', $Scan_Id)->delete('invoice_detail');
            $array = array();
            for ($i = 0;$i < count($Particular);$i++) {
                $array[$i] = array('scan_id' => $Scan_Id, 'Particular' => $Particular[$i], 'HSN' => $HSN[$i], 'Qty' => $Qty[$i], 'Unit' => $Unit[$i], 'MRP' => $MRP[$i], 'Discount' => $Discount[$i], 'Price' => $Price[$i], 'Amount' => $Amount[$i], 'GST' => $GST[$i], 'SGST' => $SGST[$i], 'IGST' => $IGST[$i], 'Cess' => $Cess[$i], 'Total_Amount' => $TAmount[$i],);
            }
            $this->db->insert_batch('invoice_detail', $array);
            $this->db->where('scan_id', $Scan_Id)->update("y{$this->year_id}_scan_file", array('is_rejected' => 'N', 'reject_date' => NULL, 'has_edit_permission' => 'N'));
        } else {
            $this->db->insert('punchfile', $data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('sub_punchfile', array('FileID' => $insert_id, 'Amount' => '-' . $Grand_Total, 'Comment' => $Remark));
            $array = array();
            for ($i = 0;$i < count($Particular);$i++) {
                $array[$i] = array('scan_id' => $Scan_Id, 'scan_id' => $Scan_Id, 'Particular' => $Particular[$i], 'HSN' => $HSN[$i], 'Qty' => $Qty[$i], 'Unit' => $Unit[$i], 'MRP' => $MRP[$i], 'Discount' => $Discount[$i], 'Price' => $Price[$i], 'Amount' => $Amount[$i], 'GST' => $GST[$i], 'SGST' => $SGST[$i], 'IGST' => $IGST[$i], 'Cess' => $Cess[$i], 'Total_Amount' => $TAmount[$i],);
            }
            $this->db->insert_batch('invoice_detail', $array);
        }
        $this->customlib->update_file_path($Scan_Id);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Something Went Wrong</div>');
            redirect('punch');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success text-left">Sale Bill added successfully</div>');
            redirect('punch');
        }
    }
}
