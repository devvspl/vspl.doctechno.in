<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'BaseController/login';
$route['login'] = 'BaseController/login';

$route['main_dashboard'] = 'MainDashboard/index';
/***********************************************************************************************************/
/********************************************  Common Functions **********************************************/
/***********************************************************************************************************/
$route['temp_files'] = 'CommonController/temp_files';
$route['temp_files/download/(:any)'] = 'CommonController/temp_file_download/$1';
$route['temp_files/view/(:any)'] = 'CommonController/temp_file_view/$1';
$route['temp_files/delete/(:any)'] = 'CommonController/temp_file_delete/$1';
$route['change_password'] = 'CommonController/change_password';
/***********************************************************************************************************/
/********************************************  Super Admin *************************************************/
/***********************************************************************************************************/
$route['employee'] = 'AdminController/employee';
$route['sync_employee'] = 'AdminController/sync_employee';
$route['approval_matrix'] = 'AdminController/approval_matrix';
$route['add_approval_matrix'] = 'AdminController/add_approval_matrix';
$route['edit_approval_matrix/(:any)'] = 'AdminController/edit_approval_matrix/$1';
$route['save_approval_matrix'] = 'AdminController/save_approval_matrix';
$route['tag_control'] = 'AdminController/tag_control';
$route['tag_control_update'] = 'AdminController/tag_control_update';
$route['set_permission/(:any)'] = 'AdminController/set_permission/$1';
$route['permissions_data/(:any)'] = 'AdminController/permissions_data/$1';
$route['save_permissions'] = 'AdminController/save_permissions';
$route['ledger'] = 'AdminController/ledger';
$route['sub_ledger'] = 'AdminController/sub_ledger';
$route['update_sub_ledger'] = 'AdminController/update_sub_ledger';
$route['business_entity'] = 'AdminController/business_entity';
$route['business_entity/(:any)'] = 'AdminController/business_entity/$1';
$route['save_business_entity'] = 'AdminController/save_business_entity';
$route['save_business_entity/(:any)'] = 'AdminController/save_business_entity/$1';
$route['delete_business_entity/(:any)'] = 'AdminController/delete_business_entity/$1';
$route['roles'] = 'AdminController/roles';
$route['user'] = 'AdminController/user';
$route['user/(:any)'] = 'AdminController/user/$1';
$route['save_user'] = 'AdminController/save_user';
$route['save_user/(:any)'] = 'AdminController/save_user/$1';
$route['delete_user/(:any)'] = 'AdminController/delete_user/$1';
$route['get_user_details/(:any)'] = 'AdminController/get_user_details/$1';
$route['core_apis'] = 'CoreController/core_apis';
$route['fetch_apis'] = 'CoreController/fetch_apis';
$route['sync_apis'] = 'CoreController/sync_apis';
$route['sync_single_api'] = 'CoreController/sync_single_api';
$route['get_api_data'] = 'CoreController/get_api_data';
$route['update_api_data'] = 'CoreController/update_api_data';
$route['empty_table'] = 'CoreController/empty_table';
$route['drop_table'] = 'CoreController/drop_table';
$route['menu_mapping'] = 'AdminController/menu_mapping';
$route['activity_dep_mapping'] = 'AdminController/activity_dep_mapping';
$route['location'] = 'AdminController/location';
$route['location/(:any)'] = 'AdminController/location/$1';
$route['save_location'] = 'AdminController/save_location';
$route['save_location/(:any)'] = 'AdminController/save_location/$1';
$route['delete_location/(:any)'] = 'AdminController/delete_location/$1';
$route['vendors'] = 'AdminController/vendors';
$route['vendor/(:any)'] = 'AdminController/vendors/$1';
$route['save_vendor'] = 'AdminController/save_vendor';
$route['save_vendor/(:any)'] = 'AdminController/save_vendor/$1';
$route['delete_vendor/(:any)'] = 'AdminController/delete_vendor/$1';
$route['get_vendor_details/(:any)'] = 'AdminController/get_vendor_details/$1';
$route['hotel'] = 'AdminController/hotel';
$route['hotel/(:any)'] = 'AdminController/hotel/$1';
$route['save_hotel'] = 'AdminController/save_hotel';
$route['save_hotel/(:any)'] = 'AdminController/save_hotel/$1';
$route['delete_hotel/(:any)'] = 'AdminController/delete_hotel/$1';
$route['get_hotel_details/(:any)'] = 'AdminController/get_hotel_details/$1';
$route['item'] = 'AdminController/item';
$route['item/(:any)'] = 'AdminController/item/$1';
$route['save_item'] = 'AdminController/save_item';
$route['save_item/(:any)'] = 'AdminController/save_item/$1';
$route['delete_item/(:any)'] = 'AdminController/delete_item/$1';
$route['get_item_details/(:any)'] = 'AdminController/get_item_details/$1';
$route['unit'] = 'AdminController/unit';
$route['unit/(:any)'] = 'AdminController/unit/$1';
$route['save_unit'] = 'AdminController/save_unit';
$route['save_unit/(:any)'] = 'AdminController/save_unit/$1';
$route['delete_unit/(:any)'] = 'AdminController/delete_unit/$1';
$route['get_unit_details/(:any)'] = 'AdminController/get_unit_details/$1';
/***********************************************************************************************************/
/**********************************************    Scanner  ************************************************/
/***********************************************************************************************************/
$route['scanner'] = 'ScannerController/scanner';
$route['scanner/export/(:any)'] = 'ScannerController/export/$1';
$route['upload_main'] = 'ScannerController/upload_main';
$route['upload_supporting_file/(:any)'] = 'ScannerController/upload_supporting_file/$1';
$route['upload_supporting'] = 'ScannerController/upload_supporting';
$route['scan_final_submit'] = 'ScannerController/scan_final_submit';
$route['temp_supporting/(:any)'] = 'ScannerController/temp_supporting/$1';
$route['delete_supporting_file'] = 'ScannerController/delete_supporting_file';
/***********************************************************************************************************/
/********************************************  Doc Classifier **********************************************/
/***********************************************************************************************************/
$route['classification'] = 'DocClassifierController/classification';
$route['processed'] = 'DocClassifierController/processed';
$route['classifications_rejected'] = 'DocClassifierController/classifications_rejected';
$route['rejected_scans'] = 'DocClassifierController/rejected_scans';
$route['document_received'] = 'DocClassifierController/document_received';
$route['scan_document_details'] = 'DocClassifierController/scan_document_details';
$route['update_received_status'] = 'DocClassifierController/update_received_status';



/***********************************************************************************************************/
/********************************************  DMS Punching ************************************************/
/***********************************************************************************************************/
$route['punch_file'] = 'DMSPunchingController/punch_file';
$route['punch_entry/(:any)/(:any)'] = 'DMSPunchingController/punch_entry/$1/$2';
$route['save_punch_details'] = 'DMSPunchingController/save_punch_details';
$route['punched_files'] = 'DMSPunchingController/punched_files/1';
/***********************************************************************************************************/
/********************************************  Punch Approver ************************************************/
/***********************************************************************************************************/
































// >>>>>>>>>>>>>>>>>>>>>>>>> Dashboard Section <<<<<<<<<<<<<<<<<<<<<<<<< //
// $route['dashboard'] = 'Dashboard';
$route['logout'] = 'Dashboard/logout';
$route['changepass'] = 'Dashboard/changepass';
$route['get_overall_report'] = 'Dashboard/get_overall_report';
$route['get_overall_report_bill_approver'] = 'Dashboard/get_overall_report_bill_approver';
$route['get_report_for_super_approver'] = 'Dashboard/get_report_for_super_approver';
$route['get_report_for_super_scanner'] = 'Dashboard/get_report_for_super_scanner';
// >>>>>>>>>>>>>>>>>>>>>>>>> Master Section <<<<<<<<<<<<<<<<<<<<<<<<<<< //
// $route['location'] = 'Location';





$route['user/delete/(:any)'] = 'master/UserController';
$route['bill_approver'] = 'master/BillApproverController';
// $route['ledger'] = 'master/LedgerController';
$route['firm'] = 'master/FirmController';
$route['new-vendor-request'] = 'master/FirmController/saveVendor';
$route['vendor-request'] = 'master/FirmController/vendorRequest';
$route['approve-vendor'] = 'master/FirmController/approveVendor';
$route['file'] = 'master/FileController';
$route['group'] = 'master/GroupController';



// $route['account'] = 'master/AccountController';
$route['master/AccountController/index/(:num)'] = 'master/AccountController/index/$1';
$route['master/AccountController/index'] = 'master/AccountController/index';
$route['activity'] = 'master/ActivityController';
$route['business_unit'] = 'master/BusinessUnitController';

$route['rejection_reason'] = 'master/RejectionReasonController';
$route['pending_bill_approve'] = 'master/BillApproverController/pending_bill_approve';
$route['reject_bill_approve'] = 'master/BillApproverController/reject_bill_approve';
$route['approved_bill_approve'] = 'master/BillApproverController/approved_bill_approve';
$route['bill_detail/(:any)'] = 'master/BillApproverController/bill_detail/$1';
$route['my_approved_bill'] = 'master/BillApproverController/my_approved_bill';
$route['rejected_bill_by_me'] = 'master/BillApproverController/rejected_bill_by_me';
$route['reject_bill/(:any)'] = 'master/BillApproverController/reject_bill/$1';
$route['approve_bill/(:any)'] = 'master/BillApproverController/approve_bill/$1';
$route['employee_import'] = 'master/EmployeeController/employee_import';
$route['firm_import'] = 'master/FirmController/firm_import';

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
// >>>>>>>>>>>>>>>>>>>>>>>>> Data Extraction Section <<<<<<<<<<<<<<<<<<<<<<<<< //


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
$route['get-territory'] = 'accounts/AdditionalController/get_territory';
$route['get-states'] = 'accounts/AdditionalController/get_states';
$route['get-locations'] = 'accounts/AdditionalController/get_locations';
$route['get-ledger'] = 'accounts/AdditionalController/get_ledger';
$route['get-subledger'] = 'accounts/AdditionalController/get_subledger';
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
$route['verification'] = 'Super_scan/verification';
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
$route['reject_list_company/(:any)'] = 'Approve/reject_list_company/$1';
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

//Routes for core apis

$route['focus-export'] = 'api/Agrisoft_ctrl/set_data';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
//==========================================
$route['ledger_wise_report'] = 'Record/ledger_wise_report';
