<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'Auth_ctrl/login';
// >>>>>>>>>>>>>>>>>>>>>>>>> Dashboard Section <<<<<<<<<<<<<<<<<<<<<<<<< //
$route['dashboard'] = 'Dashboard';
$route['logout'] = 'Dashboard/logout';
$route['changepass'] = 'Dashboard/changepass';
$route['get_overall_report'] = 'Dashboard/get_overall_report';
$route['get_overall_report_bill_approver'] = 'Dashboard/get_overall_report_bill_approver';
$route['get_report_for_super_approver'] = 'Dashboard/get_report_for_super_approver';
$route['get_report_for_super_scanner'] = 'Dashboard/get_report_for_super_scanner';
// >>>>>>>>>>>>>>>>>>>>>>>>> Master Section <<<<<<<<<<<<<<<<<<<<<<<<<<< //
$route['user'] = 'master/UserController';
$route['user/delete/(:any)'] = 'master/UserController';
$route['bill_approver'] = 'master/BillApproverController';
$route['ledger'] = 'master/LedgerController';
$route['firm'] = 'master/FirmController';
$route['new-vendor-request'] = 'master/FirmController/saveVendor';
$route['vendor-request'] = 'master/FirmController/vendorRequest';
$route['approve-vendor'] = 'master/FirmController/approveVendor';
$route['file'] = 'master/FileController';
$route['group'] = 'master/GroupController';
$route['unit'] = 'master/UnitController';
$route['hotel'] = 'master/HotelController';
$route['item'] = 'master/ItemController';
$route['employee'] = 'master/EmployeeController';
$route['account'] = 'master/AccountController';
$route['activity'] = 'master/ActivityController';
$route['business_unit'] = 'master/BusinessUnitController';
$route['business_entity'] = 'master/BusinessEntityController';
$route['rejection_reason'] = 'master/RejectionReasonController';
$route['pending_bill_approve'] ='master/BillApproverController/pending_bill_approve';
$route['my_approved_bill'] ='master/BillApproverController/my_approved_bill';
$route['rejected_bill_by_me'] ='master/BillApproverController/rejected_bill_by_me';
$route['reject_bill/(:any)'] = 'master/BillApproverController/reject_bill/$1';   
$route['approve_bill/(:any)'] = 'master/BillApproverController/approve_bill/$1';
$route['employee_import'] = 'master/EmployeeController/employee_import';
$route['firm_import'] = 'master/FirmController/firm_import';
$route['core-apis'] = 'master/CoreController/core_apis';
$route['create_account'] = 'master/AccountController/create';
// >>>>>>>>>>>>>>>>>>>>>>>>> Data Extraction Section <<<<<<<<<<<<<<<<<<<<<<<<< //
$route['classification'] = 'extract/ExtractorController/classification';
$route['processed'] = 'extract/ExtractorController/processed';
$route['change-request'] = 'extract/ExtractorController/changeRequestList';
$route['extraction-queue'] = 'extract/ExtractorController/getQueueList';
$route['process-queue'] = 'extract/ExtractorController/processQueue';
$route['cron-process-queue'] = 'PublicController/processQueue';
// >>>>>>>>>>>>>>>>>>>>>>>>> Common Section <<<<<<<<<<<<<<<<<<<<<<<<< //
$route['get-country'] = 'master/CommonController/getCountry';
$route['get-state'] = 'master/CommonController/getState';
// >>>>>>>>>>>>>>>>>>>>>>>>> Reports Section <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<//
$route['all_report'] = 'All_report';
$route['search_global'] = 'Search/search_global';
$route['search_with_filter'] = 'Search/search_with_filter';
$route['search_with_filter_status'] = 'Search/search_with_filter_status';
$route['get_search_with_filter_data'] = 'Search/get_search_with_filter_data';
// >>>>>>>>>>>>>>>>>>>>>>>>> Punch Section <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<//
$route['punch/my-punched-file'] = 'punch/my_punched_file';      
$route['punch/my-punched-file/all'] = 'punch/my_punched_file/1';
// >>>>>>>>>>>>>>>>>>>>>>>>> Additional Information <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<//
$route['get-business-entities'] = 'accounts/AdditionalController/get_business_entities';
$route['get-tds-section'] = 'accounts/AdditionalController/get_tds_section';


















//--------------Scan File-------------------
$route['scan'] = 'Scan';
$route['myscannedfiles'] = 'Scan/myscannedfiles';
$route['scan_rejected_list'] = 'Scan/scan_rejected_list';
$route['temp_scan_rejected_list'] = 'Scan/temp_scan_rejected_list';
$route['temp_scan'] = 'Scan/temp_scan';
$route['my_temp_scan'] = 'Scan/my_temp_scan';
$route['temp_scan_list_for_naming'] = 'Scan/temp_scan_list_for_naming';
$route['naming_file/(:any)'] = 'Scan/naming_file/$1';
$route['update_document_name/(:any)'] = 'Scan/update_document_name/$1';
$route['reject_temp_scan/(:any)'] = 'Scan/reject_temp_scan/$1';
$route['edit_bill_approver'] = 'Scan/edit_bill_approver';
$route['bill_rejected'] = 'Scan/bill_rejected';
$route['bill_trashed'] = 'Scan/bill_trashed';
//==================Super Scan===============
$route['super_scan/(:any)'] = 'Super_scan/index/$1';
$route['super_scan_rejected_list/(:any)'] = 'Super_scan/super_scan_rejected_list/$1';
$route['super_scan_naming_list/(:any)'] = 'Super_scan/super_scan_naming_list/$1';
$route['super_scan_verification_list/(:any)'] = 'Super_scan/super_scan_verification_list/$1';
$route['verification']  = 'Super_scan/verification';
$route['all_trashed_bill'] = 'Scan/all_trashed_bill';
//--------------Punch File-------------------
$route['punch'] = 'Punch';
$route['my_punched_file'] = 'Punch/my_punched_file';
$route['my_punched_file/(:any)'] = 'Punch/my_punched_file/$1';
$route['my_saved_file'] = 'Punch/my_saved_file';
$route['file_entry/(:any)/(:any)'] = 'Punch/file_entry/$1/$2';  
$route['rejected_punch'] = 'Punch/rejected_punch';
$route['finance_rejected_punch'] = 'Punch/finance_rejected_punch';
$route['resend_scan/(:any)'] = 'Punch/resend_scan/$1';
$route['finance_resend_scan/(:any)'] = 'Punch/finance_resend_scan/$1';
// VSPL Punch
$route['vspl_file_entry/(:any)/(:any)'] = 'Punch/file_entry/$1/$2'; 
$route['focus_exports'] = 'VSPL_Punch/focus_exports'; 
$route['export_cash_payment'] = 'VSPL_Punch/export_cash_payment'; 
//--------------Finance_Punch File-------------------
$route['finance_punch'] = 'Finance_Punch';
$route['my_finance_punched_file'] = 'Finance_Punch/my_punched_file';
$route['my_finance_punched_file/(:any)'] = 'Finance_Punch/my_punched_file/$1';
//--------------View Record----------------------
$route['file_detail/(:any)/(:any)'] = 'Record/index/$1/$2';   //$1=Scan_Id,$2=DocType_Id
$route['view_record/(:any)/(:any)'] = 'MobileView/index/$1/$2';   //$1=Scan_Id,$2=DocType_Id
//--------------Approve File-------------------
$route['approve'] = 'Approve';
$route['my_approved_file'] = 'Approve/my_approved_file';
$route['my_approved_file/(:any)'] = 'Approve/my_approved_file/$i';
$route['approve_record/(:any)'] = 'Approve/approve_record/$1';   //$1=Scan_Id
$route['approve_record_by_super_approver/(:any)'] = 'Approve/approve_record_by_super_approver/$1';   //$1=Scan_Id
$route['reject_record/(:any)'] = 'Approve/reject_record/$1';   //$1=Scan_Id
$route['rejected_by_me'] = 'Approve/rejected_by_me';
$route['reject_list_company/(:any)']='Approve/reject_list_company/$1';
//----------------Admin ------------------
$route['admin_rejected_list'] = 'Record/admin_rejected_list';
$route['give_edit_permission/(:any)'] = 'Record/give_edit_permission/$1';   //$1=Scan_Id
$route['reject_approved_file/(:any)'] = 'Record/reject_approved_file/$1';   //$1=Scan_Id
$route['report'] = 'Record/report';
$route['bill_approval_report'] = 'Record/bill_approval_report';
//----------------data extraction ------------------




//===========================Entry Confirmation On Tally/ERP===========================
$route['entry_confirmation'] = 'Entry_confirmation';
$route['file_entry_confirm/(:any)'] = 'Entry_confirmation/file_entry_confirm/$1';
$route['api/sync'] = 'api/ApiSync_ctrl/sync';
$route['delete_record/(:any)'] = 'Approve/delete_record/$1';
$route['reject_list'] = 'Record/super_admin_reject_list';
$route['agrisoft_data_set'] = 'api/Agrisoft_ctrl/set_data';
$route['journal-entry/get-account-options'] = 'form/JournalEntry_ctrl/getAccountOptions';
// Routes for temp files
$route['temp-files'] = 'TempFilesController/temp_files';
//Routes for core apis




$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
//==========================================
$route['ledger_wise_report'] ='Record/ledger_wise_report';
