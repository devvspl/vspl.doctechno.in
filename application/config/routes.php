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
$route['location'] = 'Location';
$route['user'] = 'master/UserController';
$route['roles'] = 'master/UserController/roles';
$route['menu-mapping'] = 'master/UserController/menuMapping';
$route['activity-dep-mapping'] = 'master/UserController/activityDepMapping';
$route['tag-control'] = 'master/UserController/tagControl';
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
$route['master/AccountController/index/(:num)'] = 'master/AccountController/index/$1';
$route['master/AccountController/index'] = 'master/AccountController/index';
$route['activity'] = 'master/ActivityController';
$route['business_unit'] = 'master/BusinessUnitController';
$route['business_entity'] = 'master/BusinessEntityController';
$route['rejection_reason'] = 'master/RejectionReasonController';
$route['pending_bill_approve'] ='master/BillApproverController/pending_bill_approve';
$route['reject_bill_approve'] ='master/BillApproverController/reject_bill_approve';
$route['approved_bill_approve'] ='master/BillApproverController/approved_bill_approve';
$route['bill_detail/(:any)'] = 'master/BillApproverController/bill_detail/$1';   
$route['my_approved_bill'] ='master/BillApproverController/my_approved_bill';
$route['rejected_bill_by_me'] ='master/BillApproverController/rejected_bill_by_me';
$route['reject_bill/(:any)'] = 'master/BillApproverController/reject_bill/$1';   
$route['approve_bill/(:any)'] = 'master/BillApproverController/approve_bill/$1';
$route['employee_import'] = 'master/EmployeeController/employee_import';
$route['firm_import'] = 'master/FirmController/firm_import';
$route['core-apis'] = 'master/CoreController/core_apis';
$route['create_account'] = 'master/AccountController/create';
// Admin Section
$route['approval-matrix'] = 'AdminController/approvalMatrix';
$route['approval-matrix/add'] = 'AdminController/addApprovalMatrix';
$route['approval-matrix/save'] = 'AdminController/saveApprovalMatrix';
$route['approval-matrix/delete/(:any)'] = 'AdminController/deleteApprovalMatrix/$1';
$route['approval-matrix/edit/(:any)'] = 'AdminController/editApprovalMatrix/$1';
$route['approval-matrix/get-approval-matrix'] = 'AdminController/getApprovalMatrix';
$route['approval-matrix/get-approval-matrix-by-id/(:any)'] = 'AdminController/getApprovalMatrixById/$1';
// >>>>>>>>>>>>>>>>>>>>>>>>> Document Classification Section <<<<<<<<<<<<<<<<<<<<<<<<< //
$route['doc-received'] = 'DocClassifierController/doc_verification';
// >>>>>>>>>>>>>>>>>>>>>>>>> Data Extraction Section <<<<<<<<<<<<<<<<<<<<<<<<< //
$route['classification'] = 'extract/ExtractorController/classification';
$route['processed'] = 'extract/ExtractorController/processed';
$route['classifications-rejected'] = 'extract/ExtractorController/classificationsRejected';
$route['scan-rejected-scan-admin'] = 'extract/ExtractorController/scanRejectedScanAdmin';
$route['change-request'] = 'extract/ExtractorController/changeRequestList';
$route['extraction-queue'] = 'extract/ExtractorController/getQueueList';
$route['process-queue'] = 'extract/ExtractorController/processQueue';
$route['cron-process-queue'] = 'PublicController/processQueue';
$route['import-data'] = 'PublicController/importData';
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
$route['finance/my-punched-file'] = 'punch/my_finance_punched_file';
$route['finance/my-punched-file/all'] = 'punch/my_finance_punched_file/1';
// >>>>>>>>>>>>>>>>>>>>>>>>> Additional Information <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<//
$route['get-business-entities'] = 'accounts/AdditionalController/get_business_entities';
$route['get-tds-section'] = 'accounts/AdditionalController/get_tds_section';
$route['get-cost-centers'] = 'accounts/AdditionalController/get_cost_centers';
$route['get-departments'] = 'accounts/AdditionalController/get_departments';
$route['get-business-units'] = 'accounts/AdditionalController/get_business_units';
$route['get-regions'] = 'accounts/AdditionalController/get_regions';
$route['get-states'] = 'accounts/AdditionalController/get_states';
$route['get-locations'] = 'accounts/AdditionalController/get_locations';
$route['get-categories'] = 'accounts/AdditionalController/get_categories';
$route['get-crops'] = 'accounts/AdditionalController/get_crops';
$route['get-activities'] = 'accounts/AdditionalController/get_activities';
$route['get-debit-accounts'] = 'accounts/AdditionalController/get_debit_accounts';
$route['get-credit-accounts'] = 'accounts/AdditionalController/get_credit_accounts';
$route['get-payment-term'] = 'accounts/AdditionalController/get_payment_term';
$route['get-function'] = 'accounts/AdditionalController/get_function';
$route['get-vertical'] = 'accounts/AdditionalController/get_vertical';
$route['get-zone'] = 'accounts/AdditionalController/get_zone';
$route['get-sub-department'] = 'accounts/AdditionalController/get_sub_department';
$route['store-additional-detail'] = 'accounts/AdditionalController/store_update';
$route['finance/bill-approval/(:any)'] = 'punch/my_finance_bill_approval_file/$1';
$route['approve_file/(:num)'] = 'punch/approve_file/$1';
$route['reject_file/(:num)'] = 'punch/reject_file/$1';
// ===========================Temp Scanner===========================
$route['temp-supporting/(:any)'] = 'Scan/temp_upload_supporting/$1';
$route['temp-final-submit'] = 'Scan/final_submit';
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
$route['finance_rejected_punch_1'] = 'Punch/finance_rejected_punch_1';
$route['resend_scan/(:any)'] = 'Punch/resend_scan/$1';
$route['finance_resend_scan/(:any)'] = 'Punch/finance_resend_scan/$1';
// VSPL Punch
$route['vspl_file_entry/(:any)/(:any)'] = 'Punch/file_entry/$1/$2'; 
$route['focus_exports'] = 'VSPL_Punch/focus_exports'; 
$route['export_csv'] = 'VSPL_Punch/export_csv'; 
$route['export_cash_payment'] = 'VSPL_Punch/export_cash_payment'; 
//--------------Finance_Punch File-------------------
$route['finance_punch'] = 'Finance_Punch';
$route['my_finance_punched_file'] = 'Finance_Punch/my_punched_file';
$route['my_finance_punched_file/(:any)'] = 'Finance_Punch/my_punched_file/$1';
//--------------View Record----------------------
$route['file_detail/(:any)/(:any)'] = 'Record/index/$1/$2';   //$1=scan_id,$2=doc_type_id
$route['view_record/(:any)/(:any)'] = 'MobileView/index/$1/$2';   //$1=scan_id,$2=doc_type_id
$route['vspl_file_detail/(:any)/(:any)'] = 'Record/vspl_index/$1/$2';
//--------------Approve File-------------------
$route['approve'] = 'Approve';
$route['my_approved_file'] = 'Approve/my_approved_file';
$route['my_approved_file/(:any)'] = 'Approve/my_approved_file/$i';
$route['approve_record/(:any)'] = 'Approve/approve_record/$1';   //$1=scan_id
$route['approve_record_by_super_approver/(:any)'] = 'Approve/approve_record_by_super_approver/$1';   //$1=scan_id
$route['reject_record/(:any)'] = 'Approve/reject_record/$1';   //$1=scan_id
$route['rejected_by_me'] = 'Approve/rejected_by_me';
$route['reject_list_company/(:any)']='Approve/reject_list_company/$1';
//----------------Admin ------------------
$route['admin_rejected_list'] = 'Record/admin_rejected_list';
$route['give_edit_permission/(:any)'] = 'Record/give_edit_permission/$1';   //$1=scan_id
$route['reject_approved_file/(:any)'] = 'Record/reject_approved_file/$1';   //$1=scan_id
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

$route['focus-export'] = 'api/Agrisoft_ctrl/set_data';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
//==========================================
$route['ledger_wise_report'] ='Record/ledger_wise_report';
