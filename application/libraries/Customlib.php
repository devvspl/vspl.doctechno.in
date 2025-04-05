<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Customlib
{
    public $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->jwt = new JWT();
        $this->CI->load->library('session');
        $this->CI->load->library('form_validation');
    }

    function is_valid($jwt)
    {
        $valid = $this->CI->jwt->decode($jwt, $this->CI->config->item('jwtsecrateKey'), 'HS256');
        if (!is_null($valid)) {
            return $valid;
        } else {
            return null;
        }
    }

    function haveSupportFile($Scan_Id)
    {
        $chk = $this->CI->db
            ->select('*')
            ->from('support_file')
            ->where('Scan_Id', $Scan_Id)
            ->get()
            ->num_rows();

        if ($chk > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getSupportFile($Scan_Id)
    {
        $result = $this->CI->db->select('*')->get_where('support_file', ['Scan_Id' => $Scan_Id]);
        return $result->result_array();
    }

    public function getUserData()
    {
        $result = $this->CI->db->get_where('users', ['user_id' => $this->CI->session->userdata('user_id')])->result_array();
        $id = $result[0]['user_id'];
        $name = $result[0]['first_name'] . ' ' . $result[0]['last_name'];
        $role = $result[0]['role'];
        $data = [
            'user_id' => $id,
            'name' => $name,
            'role' => $role,
        ];
        return $data;
    }

    public function user_permission()
    {
        $user_id = $this->CI->session->userdata('user_id');
        $result = $this->CI->db
            ->select('permission_name')
            ->join('permission', 'permission.permission_id=user_permission.permission_id')
            ->get_where('user_permission', ['user_id' => $user_id])
            ->result_array();
        $permission = [];
        foreach ($result as $row) {
            $permission[] = $row['permission_name'];
        }
        return $permission;
    }

    public function has_permission($permission)
    {
        $user_id = $this->CI->session->userdata('user_id');
        $result = $this->CI->db
            ->select('permission_name')
            ->join('permission', 'permission.permission_id=user_permission.permission_id')
            ->get_where('user_permission', ['user_id' => $user_id])
            ->result_array();
        $user_permission = [];
        foreach ($result as $row) {
            $user_permission[] = $row['permission_name'];
        }
        if (in_array($permission, $user_permission)) {
            return true;
        } else {
            return false;
        }
    }

    public function getDocumentName($Scan_Id)
    {
        $result = $this->CI->db->get_where('scan_file', ['Scan_Id' => $Scan_Id])->result_array();
        return $result[0]['Document_Name'];
    }

    public function get_Name($user_id)
    {
        $result = $this->CI->db->get_where('users', ['user_id' => $user_id])->result_array();
        if (count($result) > 0) {
            return $result[0]['first_name'] . ' ' . $result[0]['last_name'];
        } else {
            return '';
        }
    }

    public function getGroupID($user_id)
    {
        $result = $this->CI->db->get_where('users', ['user_id' => $user_id])->result_array();
        return $result[0]['group_id'];
    }

    public function getDocType($DocTypeId)
    {
        $result = $this->CI->db->get_where('master_doctype', ['type_id' => $DocTypeId])->result_array();
        return $result[0]['alias'];
    }

    public function get_user_list($group_id)
    {
        $result = $this->CI->db->select('user_id')->get_where('users', ['group_id' => $group_id]);
        return array_column($result->result_array(), 'user_id');
    }

    public function getScanData($Scan_Id)
    {
        $result = $this->CI->db
            ->select('*')
            ->from('scan_file')
            ->where('Scan_Id', $Scan_Id)
            ->get()
            ->row();
        return $result;
    }

    public function getFinancial_year()
    {
        $result = $this->CI->db
            ->select('*')
            ->from('financial_year')
            ->get()
            ->result_array();
        return $result;
    }

    public function getCompanyList()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('master_firm');
        $this->CI->db->where('is_deleted', 'N');
        $this->CI->db->where('firm_type', 'Company');
        $this->CI->db->order_by('firm_name', 'asc');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function getVendorList()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('master_firm');
        $this->CI->db->where('is_deleted', 'N');
        $this->CI->db->where('firm_type', 'Vendor');
        $this->CI->db->order_by('firm_name', 'asc');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function getDepartmentList()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('master_department');
        $this->CI->db->where('is_deleted', 'N');
        $this->CI->db->order_by('department_name', 'asc');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function getEmployeeList()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('master_employee');
        $this->CI->db->where('is_deleted', 'N');
        $this->CI->db->where('status', 'A');
        $this->CI->db->order_by('emp_name', 'asc');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function getFileList()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('master_file');
        $this->CI->db->where('is_deleted', 'N');
        $this->CI->db->order_by('file_name', 'asc');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function getReportType()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('master_report_type');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function getCompanyNameById($company_id)
    {
        $result = $this->CI->db
            ->select('firm_name')
            ->get_where('master_firm', ['firm_id' => $company_id])
            ->result_array();
        return $result[0]['firm_name'];
    }

    public function getEmployeeNameById($employee_id)
    {
        $result = $this->CI->db
            ->select('emp_name')
            ->get_where('master_employee', ['id' => $employee_id])
            ->result_array();
        return $result[0]['emp_name'];
    }

    public function getDepatmentNameById($department_id)
    {
        $result = $this->CI->db
            ->select('department_name')
            ->get_where('master_department', ['department_id' => $department_id])
            ->result_array();
        return $result[0]['department_name'];
    }

    public function update_file_path($Scan_Id)
    {
        $Get_Scan_Detail = $this->getScanData($Scan_Id);
        $DocType = $Get_Scan_Detail->Doc_Type;
        $ScanBY = $Get_Scan_Detail->Scan_By;
        $File = $Get_Scan_Detail->File;
        $Year = $Get_Scan_Detail->Year;
        $temp_file = './uploads/temp/' . $File;
        $upload_dir = 'uploads/' . $ScanBY . '/' . $Year . '/' . $DocType;
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        rename($temp_file, $upload_dir . '/' . $File);
        $file_location = base_url() . $upload_dir . '/' . $File;
        $file_location1 = $upload_dir . '/' . $File;
        $this->CI->db
            ->where('Scan_Id', $Scan_Id)
            ->update('scan_file', ['File_Location' => $file_location, 'File_Location1' => $file_location1, 'File_Punched' => 'Y', 'Punch_By' => $this->CI->session->userdata('user_id'), 'Punch_Date' => date('Y-m-d H:i:s')]);
        if ($this->haveSupportFile($Scan_Id) == true) {
            $support_file = $this->getSupportFile($Scan_Id);
            foreach ($support_file as $key => $value) {
                $SupportFile = $value['File'];
                $Support_Id = $value['Support_Id'];
                $Temp_SupportFile = './uploads/temp/' . $SupportFile;
                rename($Temp_SupportFile, $upload_dir . '/' . $SupportFile);
                $support_location = base_url() . $upload_dir . '/' . $SupportFile;
                $support_location1 = $upload_dir . '/' . $SupportFile;
                $this->CI->db->where('Support_Id', $Support_Id)->update('support_file', ['File_Location' => $support_location, 'File_Location1' => $support_location1]);
            }
        }
    }

    public function check_punchfile2($Scan_Id)
    {
        $chk = $this->CI->db
            ->select('*')
            ->from('punchfile2')
            ->where('Scan_Id', $Scan_Id)
            ->get()
            ->num_rows();

        if ($chk > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_punchfile($Scan_Id)
    {
        $chk = $this->CI->db
            ->select('*')
            ->from('punchfile')
            ->where('Scan_Id', $Scan_Id)
            ->get()
            ->num_rows();

        if ($chk > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getWorkLocationList()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('master_work_location');
        $this->CI->db->where('is_deleted', 'N');
        $this->CI->db->where('status', 'A');
        $this->CI->db->order_by('location_name', 'asc');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function getLedgerList()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('master_ledger');
        $this->CI->db->where('is_deleted', 'N');
        $this->CI->db->where('status', 'A');
        $this->CI->db->order_by('ledger_name', 'asc');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function getCategoryList()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('master_category');
        $this->CI->db->where('is_deleted', 'N');
        $this->CI->db->where('status', 'A');
        $this->CI->db->order_by('category_name', 'asc');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    function dateDiff($date1, $date2)
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);

        $datediff = $date1 - $date2;

        return round($datediff / (60 * 60 * 24));
    }

    function getMonthName($month)
    {
        $monthName = '';
        switch ($month) {
            case 1:
                $monthName = 'January';
                break;
            case 2:
                $monthName = 'February';
                break;
            case 3:
                $monthName = 'March';
                break;
            case 4:
                $monthName = 'April';
                break;
            case 5:
                $monthName = 'May';
                break;
            case 6:
                $monthName = 'June';
                break;
            case 7:
                $monthName = 'July';
                break;
            case 8:
                $monthName = 'August';
                break;
            case 9:
                $monthName = 'September';
                break;
            case 10:
                $monthName = 'October';
                break;
            case 11:
                $monthName = 'November';
                break;
            case 12:
                $monthName = 'December';
                break;
        }
        return $monthName;
    }

    function send_for_accounting($scanId)
    {
        $scanDetail = $this->getScanData($scanId);
        $data = [
            'Scan_Id' => $scanId,
            'Group_Id' => $scanDetail->Group_Id,
            'Doc_Type' => $scanDetail->Doc_Type,
            'DocType_Id' => $scanDetail->DocType_Id,
            'Document_Name' => $scanDetail->Document_Name,
            'File' => $scanDetail->File,
            'File_Ext' => $scanDetail->File_Ext,
            'File_Location' => $scanDetail->File_Location,
            'Punch_Date' => $scanDetail->Punch_Date,
        ];

        $secondaryDb = $this->CI->load->database('secondary', true);

        $secondaryDb->insert('scan_file', $data);
        
        if (!$secondaryDb) {
            log_message('error', 'Failed to load secondary database.');
            die("Failed to load secondary database.");
        }

        $accounting_type = [
            'invoice',
            'two_four_wheeler',
            'air_rail_bus',
            'bank_loan_paper',
            'cash_deposit_withdrawals',
            'vehicle_maintenance',
            'vehicle_fuel',
            'telephone_bill',
            'subsidy',
            'rtgs_neft',
            'rst_ofd',
            'postage_courier',
            'phone_fax',
            'meals',
            'lodging',
            'local_conveyance',
            'lease_rent',
            'jeep_campaign',
            'it_return',
            'insurance_policy',
            'insurance_document',
            'income_taxt_tds',
            'hired_vehicle',
            'challan',
            'fixed_deposit_receipt',
            'fd_fv',
            'electricity_bill',
            'dealer_meeting',
            'cheque',
            'cash_voucher',
            'gst_challan',
            'labour_payment',
            'cash_receipt',
            'fixed_asset',
            'machine_operation',
            'air',
            'rail',
            'bus',
            'ticket_cancellation',
        ];
        if (in_array($scanDetail->Doc_Type, $accounting_type)) {
            $punchfile = $this->CI->db
                ->select(
                    'Scan_Id, Group_Id, DocType, DocTypeId, Company, CompanyID, From_ID, FromName, To_ID, ToName, File_Date, File_Type, File_No, Related_Person, Related_Address, BillDate, BillMonth, BillYear, ReferenceNo, Loc_Name, Loc_Add, FromDateTime, ToDateTime, SubTotal, Total_Amount, Total_Discount, Grand_Total, NatureOfPayment, DateOf_SanctApp, GST_IGST_Amount, SGST_Amount, CGST_Amount, Cess, TCS, Department, DepartmentID, Ledger, Category, FileName, Section, TravelMode, TravelQuota, TravelClass, BookingDate, PassengerDetail, BookingStatus, TravelInsurance, TypeOfLoanDoc, BankName, BankIfscCode, BankAccountNo, BankAddress, DueDate, RenewalDate, Period, PaperSubmitted, Vehicle_Type, VehicleRs_PerKM, TripStarted, TripEnded, VehicleRegNo, OpeningKm, ClosingKm, TotalRunKM, ChequeNo, NoOfFarmers, Dealers_TradePartners, CropDetails, VerietyDetails, MealsAmount, HallTent_Amount, Gift_Amount, AVTent_Amount, HiredVehicle_Amount, Snacks_Amount, OthCharge_Amount, MeterNumber, PreviousReading, CurrentReading, UnitsConsumed, LastDateOfPayment, ServiceNo, FDRNo, Depositer, DepositAccNo, MaturityAmount, MaturityDate, RateOfInterest, JointHolderName, DepositedFrom, ChallanPurpose, BankBSRCode, PaymentHead, AgencyAddress, VehicleClass, RegNo, DriverCharges, BSRCode, MobileNo, BillingCycle, TariffPlan, PreviousBalance, LastPayement, NomineeDetails, SumAssured, PremiumDate, Coverage, AgentName, PropertyArea, OtherSpecif, Financial_Year, Remark, CPIN, CIN, GSTIN, Email, EmployeeID, EmployeeCode, Employee_Name, Cal_By, Month, MonthName, Hotel, Hotel_Name, Hotel_Address, Particular, Airline, Base_Fare, Surcharge, Cute_Charge, Extra_Luggage, CreditNo, CreditDate, Created_By, Created_Date, file_punch_date, business_entity_id, document_number, narration, tdsApplicable, TDS_JV_no, TDS_section, TDS_percentage, TDS_amount, Payment_Amount, account_group, account, favouring'
                )
                ->get_where('punchfile', ['Scan_Id' => $scanId])
                ->row();
            if ($punchfile != null) {
                log_message('debug', 'Punchfile found for Scan_Id: ' . $scanId);
                if ($secondaryDb->insert('punchfile', $punchfile)) {
                    log_message('debug', 'Punchfile inserted successfully into secondary DB.');
                } else {
                    log_message('error', 'Failed to insert punchfile into secondary DB.');
                    print_r($secondaryDb->error()); 
                }

                $secondaryDb->trans_complete();
                if ($secondaryDb->trans_status() === false) {
                    log_message('error', 'Transaction failed.');
                    die('Transaction failed.');
                } else {
                    log_message('debug', 'Transaction completed successfully.');
                }
            } else {
                log_message('debug', 'No punchfile found for Scan_Id: ' . $scanId);
                echo "No punchfile found.";
            }
        } else {
            $punchfile2 = $this->CI->db->get_where('punchfile2', ['Scan_Id' => $scanId])->row();
            if ($punchfile2 != null) {
                $secondaryDb->insert('punchfile2', $punchfile2);
            }
        }

        if ($scanDetail->Doc_Type == 'invoice' || $scanDetail->Doc_Type == 'fixed_asset' || $scanDetail->Doc_Type == 'vehicle_maintenance') {
            $invoiceDetail = $this->CI->db->select('Scan_Id, Particular, HSN, Qty, Unit, MRP, Discount, Price, Amount, GST, SGST, IGST, Cess, Total_Amount')->get_where('invoice_detail', ['Scan_Id' => $scanId])->result_array();
            foreach ($invoiceDetail as $value) {
                $secondaryDb->insert('invoice_detail', $value);
            }
        } elseif ($scanDetail->Doc_Type == 'two_four_wheeler' || $scanDetail->Doc_Type == 'local_conveyance') {
            $two_four_wheeler = $this->CI->db->select('Scan_Id, VehicleReg, JourneyStartDt, JourneyEndDt, DistTraOpen, DistTraClose, Totalkm, FilledTAmt')->get_where('vehicle_traveling', ['Scan_Id' => $scanId])->result_array();
            foreach ($two_four_wheeler as $value) {
                $secondaryDb->insert('vehicle_traveling', $value);
            }
        } elseif ($scanDetail->Doc_Type == 'lodging' || $scanDetail->Doc_Type == 'air' || $scanDetail->Doc_Type == 'rail') {
            $lodging_employee = $this->CI->db->select('Scan_Id, emp_id, emp_name, emp_code')->get_where('lodging_employee', ['Scan_Id' => $scanId])->result_array();
            foreach ($lodging_employee as $value) {
                $secondaryDb->insert('lodging_employee', $value);
            }
        } elseif ($scanDetail->Doc_Type == 'gst_challan') {
            $gst_chalan = $this->CI->db->select('Scan_Id, Particular, Tax, Interest, Penalty, Fees, Other, Total')->get_where('gst_challan_detail', ['Scan_Id' => $scanId])->result_array();
            foreach ($gst_chalan as $value) {
                $secondaryDb->insert('gst_challan_detail', $value);
            }
        } elseif ($scanDetail->Doc_Type == 'labour_payment') {
            $labour_payment_detail = $this->CI->db->select('Scan_Id, Head, Amount')->get_where('labour_payment_detail', ['Scan_Id' => $scanId])->result_array();
            foreach ($labour_payment_detail as $value) {
                $secondaryDb->insert('labour_payment_detail', $value);
            }
        } elseif ($scanDetail->Doc_Type == 'ticket_cancellation') {
            $ticket_cancellation = $this->CI->db->select('Scan_Id, Emp_Id, Emp_Name, Amount, PNR')->CI->db->get_where('ticket_cancellation', ['Scan_Id' => $scanId])->result_array();
            foreach ($ticket_cancellation as $value) {
                $secondaryDb->insert('ticket_cancellation', $value);
            }
        }
    }

    function set_missing_data()
    {
        $secondaryDb = $this->CI->load->database('secondary', true);
        // Use AND instead of OR in the condition to ensure the Scan_Id is not in both subqueries
        $scan_ids_result = $secondaryDb->query("SELECT Scan_Id FROM `scan_file` WHERE 
            (Scan_Id NOT IN (SELECT Scan_Id FROM punchfile) AND Scan_Id NOT IN (SELECT Scan_Id FROM punchfile2))");

        // Fetch the result as an array of objects
        $scan_ids = $scan_ids_result->result_array();
        $secondaryDb->trans_start();
        try {
            foreach ($scan_ids as $scanId) {
                $scanDetail = $this->getScanData($scanId['Scan_Id']);

                $accounting_type = [
                    'invoice',
                    'two_four_wheeler',
                    'air_rail_bus',
                    'bank_loan_paper',
                    'cash_deposit_withdrawals',
                    'vehicle_maintenance',
                    'vehicle_fule',
                    'telephone_bill',
                    'subsidy',
                    'rtgs_neft',
                    'rst_ofd',
                    'postage_courier',
                    'phone_fax',
                    'meals',
                    'lodging',
                    'local_conveyance',
                    'lease_rent',
                    'jeep_campaign',
                    'it_return',
                    'insurance_policy',
                    'insurance_document',
                    'income_taxt_tds',
                    'hired_vehicle',
                    'challan',
                    'fixed_deposit_receipt',
                    'fd_fv',
                    'electricity_bill',
                    'dealer_meeting',
                    'cheque',
                    'cash_voucher',
                    'gst_challan',
                    'labour_payment',
                    'cash_receipt',
                    'fixed_asset',
                    'machine_operation',
                    'air',
                    'rail',
                    'bus',
                ];

                if (in_array($scanDetail->Doc_Type, $accounting_type)) {
                    $punchfile = $this->CI->db->get_where('punchfile', ['Scan_Id' => $scanId['Scan_Id']])->row();
                    if ($punchfile != null) {
                        $secondaryDb->insert('punchfile', $punchfile);
                    }
                } else {
                    $punchfile2 = $this->CI->db->get_where('punchfile2', ['Scan_Id' => $scanId['Scan_Id']])->row();
                    if ($punchfile2 != null) {
                        $secondaryDb->insert('punchfile2', $punchfile2);
                    }
                }

                if ($scanDetail->Doc_Type == 'invoice' || $scanDetail->Doc_Type == 'fixed_asset' || $scanDetail->Doc_Type == 'vehicle_maintenance') {
                    $invoiceDetail = $this->CI->db->get_where('invoice_detail', ['Scan_Id' => $scanId['Scan_Id']])->result_array();
                    foreach ($invoiceDetail as $value) {
                        $secondaryDb->insert('invoice_detail', $value);
                    }
                } elseif ($scanDetail->Doc_Type == 'two_four_wheeler' || $scanDetail->Doc_Type == 'local_conveyance') {
                    $two_four_wheeler = $this->CI->db->get_where('vehicle_traveling', ['Scan_Id' => $scanId['Scan_Id']])->result_array();
                    foreach ($two_four_wheeler as $value) {
                        $secondaryDb->insert('vehicle_traveling', $value);
                    }
                } elseif ($scanDetail->Doc_Type == 'lodging' || $scanDetail->Doc_Type == 'air' || $scanDetail->Doc_Type == 'rail') {
                    $lodging_employee = $this->CI->db->get_where('lodging_employee', ['Scan_Id' => $scanId['Scan_Id']])->result_array();
                    foreach ($lodging_employee as $value) {
                        $secondaryDb->insert('lodging_employee', $value);
                    }
                } elseif ($scanDetail->Doc_Type == 'gst_challan') {
                    $gst_chalan = $this->CI->db->get_where('gst_challan_detail', ['Scan_Id' => $scanId['Scan_Id']])->result_array();
                    foreach ($gst_chalan as $value) {
                        $secondaryDb->insert('gst_challan_detail', $value);
                    }
                } elseif ($scanDetail->Doc_Type == 'labour_payment') {
                    $labour_payment_detail = $this->CI->db->get_where('labour_payment_detail', ['Scan_Id' => $scanId['Scan_Id']])->result_array();
                    foreach ($labour_payment_detail as $value) {
                        $secondaryDb->insert('labour_payment_detail', $value);
                    }
                } elseif ($scanDetail->Doc_Type == 'ticket_cancellation') {
                    $ticket_cancellation = $this->CI->db->get_where('ticket_cancellation', ['Scan_Id' => $scanId['Scan_Id']])->result_array();
                    foreach ($ticket_cancellation as $value) {
                        $secondaryDb->insert('ticket_cancellation', $value);
                    }
                }

                $secondaryDb->where('Scan_Id', $scanId['Scan_Id'])->update('scan_file', ['Missing_Data' => '0']);
            }
            $secondaryDb->trans_complete();
            print_r("Success");
        } catch (\Exception $e) {
            // An error occurred, rollback the transaction
            $secondaryDb->trans_rollback();

            print_r("Something went wrong");
        }
    }

    function getItemList()
    {
        $this->CI->db->select('item_name');
        $this->CI->db->from('master_item');
        $this->CI->db->where('is_deleted', 'N');
        $this->CI->db->where('status', 'A');
        $this->CI->db->order_by('item_name', 'asc');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function getHotelNameById($hotel)
    {
        $query = $this->CI->db
            ->select('hotel_name')
            ->from('master_hotel')
            ->where('hotel_id', $hotel)
            ->limit(1)
            ->get();
        $result = $query->row_array();

        return $result ? $result['hotel_name'] : '';
    }

    public function getRejectReason()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('master_rj_reason');
        $this->CI->db->where('is_deleted', 'N');
        $this->CI->db->where('status', 'A');
        $this->CI->db->order_by('reason', 'asc');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }

    public function get_GroupName($groupId)
    {
        $result = $this->CI->db->get_where('master_group', ['group_id' => $groupId])->result_array();
        if (count($result) > 0) {
            return $result[0]['group_name'];
        } else {
            return '';
        }
    }

    public function get_Location_Name($location_id)
    {
        $result = $this->CI->db->get_where('master_work_location', ['location_id' => $location_id])->result_array();
        if (count($result) > 0) {
            return $result[0]['location_name'];
        } else {
            return '';
        }
    }

    function getBillApproverList()
    {
        $this->CI->db->select('*');
        $this->CI->db->from('users');
        $this->CI->db->where('role', 'bill_approver');
        $this->CI->db->where('status', 'A');
        $this->CI->db->order_by('first_name', 'asc');
        $result = $this->CI->db->get()->result_array();
        return $result;
    }
}
