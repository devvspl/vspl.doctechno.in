<?php

   $overall_scan_count = $this->db->where(array('is_final_submitted' => 'Y', 'is_deleted' => 'N'))->from("y{$year_id}_scan_file")->count_all_results();
   
   
   
   
   $overall_scan_reject_count = $this->db->where(array('is_scan_resend' => 'Y', 'is_deleted' => 'N'))->from("y{$year_id}_scan_file")->count_all_results();
   
   
   
   
   $overall_pending_naming_count = $this->db->where(array('is_temp_scan' => 'Y', 'is_scan_complete' => 'N', 'is_temp_scan_rejected' => 'N'))->from("y{$year_id}_scan_file")->count_all_results();
   
   
   
   
   $overall_pending_verification_count = $this->db->where(array('is_temp_scan' => 'Y', 'is_scan_complete' => 'Y', 'is_temp_scan_rejected' => 'N', 'is_document_verified' => 'N', "y{$year_id}_scan_file.is_deleted" => 'N'))->from("y{$year_id}_scan_file")->count_all_results();
   
   
   
   
   
   
   
   
   
   
   
   
   $overall_punch_count = $this->db->where(array('is_file_punched' => 'Y', 'is_deleted' => 'N'))->from("y{$year_id}_scan_file")->count_all_results();
   
   $overall_approve_count = $this->db->where(array('is_file_approved' => 'Y', 'is_deleted' => 'N'))->from("y{$year_id}_scan_file")->count_all_results();
   
   $overall_punch_pending_count = $this->db->where(array('is_final_submitted' => 'Y', 'is_file_punched' => 'N'))->from("y{$year_id}_scan_file")->count_all_results();
   
   $overall_approve_pending_count = $this->db->where(array('is_file_punched' => 'Y', 'is_file_approved' => 'N', 'is_deleted' => 'N', 'is_rejected' => 'N'))->from("y{$year_id}_scan_file")->count_all_results();
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   $current_date_scan_count = $this->db->where(array('is_final_submitted' => 'Y', 'is_deleted' => 'N', 'scan_date' => date('Y-m-d')))->from("y{$year_id}_scan_file")->count_all_results();
   
   $current_date_punch_count = $this->db->where(array('is_file_punched' => 'Y', 'is_deleted' => 'N', 'scan_date' => date('Y-m-d')))->from("y{$year_id}_scan_file")->count_all_results();
   
   $current_date_approve_count = $this->db->where(array('is_file_approved' => 'Y', 'is_deleted' => 'N', 'approved_date' => date('Y-m-d')))->from("y{$year_id}_scan_file")->count_all_results();
   
   $current_date_punch_pending_count = $this->db->where(array('is_final_submitted' => 'Y', 'is_deleted' => 'N', 'is_file_punched' => 'N', 'scan_date' => date('Y-m-d')))->from("y{$year_id}_scan_file")->count_all_results();
   
   $curr_date_approve_pending_count = $this->db->where(array('is_file_punched' => 'Y', 'is_file_approved' => 'N', 'scan_date' => date('Y-m-d')))->from("y{$year_id}_scan_file")->count_all_results();
   
   
   
   $data_curr_date_complete = '[{ data: [' . $current_date_scan_count . ',' . $current_date_punch_count . ',' . $current_date_approve_count . '] }]';
   $data_curr_date_pending = '[{ data: [' . $current_date_punch_pending_count . ',' . $curr_date_approve_pending_count . '] }]';
   
   
   $total_bill_approved_by_me = '';
   $rejected_bill_by_me = '';
   $bill_pending = '';
   
   
   
   
   	
   	
   	
   	$bill_pending_query = $this->db->where('bill_approval_status', 'N')
   		->where_in('bill_approver_id', $this->session->userdata('user_id'))
   		->get("y{$year_id}_scan_file")
   		->result_array();
   	$bill_pending = count($bill_pending_query);
   	
   
   	$rejected_bill_by_me_query = $this->db->where('bill_approval_status', 'R')->where('bill_approver_id',$this->session->userdata('user_id'))
   	->get("y{$year_id}_scan_file")
   	->result_array();
   	$rejected_bill_by_me = count($rejected_bill_by_me_query);
   
   	$total_bill_approved_by_me_query = $this->db->where('bill_approval_status', 'Y')->where('bill_approver_id',$this->session->userdata('user_id'))
   	->get("y{$year_id}_scan_file")
   	->result_array();
   	$total_bill_approved_by_me = count($total_bill_approved_by_me_query);
   
   ?>
<div class="content-wrapper">
   <section class="content">
      <?php if ($this->customlib->has_permission('Punch')) { ?>
      <div class="row">
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>my_punched_file/1">
                  <span class="info-box-icon bg-yellow"><i class="fa fa-list-alt"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Total Punched</span>
                     <span class="info-box-number"><?= $total_punched_by_me ?></span>
                  </div>
               </a>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
               <a href="javascript:void(0);">
                  <span class="info-box-icon bg-red"><i class="fa fa-exclamation-triangle"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Pending For Approval</span>
                     <span class="info-box-number"><?= $pending_for_approval_punch_by_me ?></span>
                  </div>
               </a>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
               <a href="javascript:void(0);">
                  <span class="info-box-icon bg-green"><i class="fa fa-check-circle-o"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Punch Approved</span>
                     <span class="info-box-number"><?= $total_approved ?></span>
                  </div>
               </a>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>rejected_punch">
                  <span class="info-box-icon bg-red"><i class="fa fa-exclamation-triangle"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Punch Rejected</span>
                     <span class="info-box-number"><?= $rejected_punch ?></span>
                  </div>
               </a>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>punch">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-exclamation-circle"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Pending for Punch</span>
                     <span class="info-box-number"><?= $pending_for_punch ?></span>
                  </div>
               </a>
            </div>
         </div>
      </div>
      <?php } ?>
      <?php if ($this->customlib->has_permission('Approve') || $_SESSION['role'] == 'super_approver') { ?>
      <div class="row">
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>my_approved_file/1">
                  <span class="info-box-icon bg-green"><i class="fa fa-list-alt"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Total Approved by Me</span>
                     <span class="info-box-number"><?= $total_approved_by_me ?></span>
                  </div>
               </a>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>rejected_by_me">
                  <span class="info-box-icon bg-red"><i class="fa fa-exclamation-triangle"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Total Rejected by Me</span>
                     <span class="info-box-number"><?= $rejected_by_me ?></span>
                  </div>
               </a>
            </div>
         </div>
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>approve">
                  <span class="info-box-icon bg-aqua"><i class="fa fa-exclamation-triangle"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Pending for Approvel</span>
                     <span class="info-box-number"><?= $overall_approve_pending_count; ?></span>
                  </div>
               </a>
            </div>
         </div>
      </div>
      <?php if ($_SESSION['role'] == 'super_approver') { ?>
      <div class="row" id="company_wise_report">
         <div class="col-lg-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title">Company Wise Report</h3>
               </div>
               <div class="box-body">

                  <div class="download_label">Company Wise Report</div>
                  <table class="table table-striped table-bordered table-hover" id="approveDetails">
                     <thead>
                        <tr>
                           <th>Company</th>
                           <th class="text-center">Approved</th>
                           <th class="text-center">Rejected</th>
                           <th class="text-center">Pending</th>
                        </tr>
                     </thead>
                     <tbody>
                     </tbody>
                     <tfoot>
                        <tr>
                           <th>Sum</th>
                           <th class="text-center"></th>
                           <th class="text-center"></th>
                           <th class="text-center"></th>
                        </tr>
                     </tfoot>
                  </table>
               </div>
            </div>
         </div>
      </div>
      <?php } ?>
      <?php } ?>
      <?php if ($_SESSION['role'] == 'admin') { ?>
      <div class="row">
         <div class="col-md-3 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>admin_rejected_list">
                  <span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Rejected List</span>
                     <span class="info-box-number"><?= $total_rejected ?></span>
                  </div>
               </a>
            </div>
         </div>
      </div>
      <?php } ?>
      <?php if ($this->customlib->has_permission('Scan')) { ?>
      <div class="row">
         <div class="col-md-4 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>scan_rejected_list">
                  <span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Total Scan File Rejected</span>
                     <span class="info-box-number"><?= $scan_rejected ?></span>
                  </div>
               </a>
            </div>
         </div>
         <div class="col-md-4 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>temp_scan_list_for_naming">
                  <span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Scan File Pending For Document Naming</span>
                     <span class="info-box-number"><?= $scan_pending_name ?></span>
                  </div>
               </a>
            </div>
         </div>
         <div class="col-md-4 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>bill_rejected">
                  <span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Bill Rejected</span>
                     <span class="info-box-number"><?= $bill_rejected_count ?></span>
                  </div>
               </a>
            </div>
         </div>
      </div>
      <?php } ?>
      <?php if ($this->customlib->has_permission('Temporary Scan')) { ?>
      <div class="row">
         <div class="col-md-4 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>temp_scan_rejected_list">
                  <span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Total Scan File Rejected</span>
                     <span class="info-box-number"><?= $temp_scan_rejected ?></span>
                  </div>
               </a>
            </div>
         </div>
         <div class="col-md-4 col-sm-6">
            <div class="info-box">
               <a href="<?= base_url(); ?>bill_rejected">
                  <span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
                  <div class="info-box-content">
                     <span class="info-box-text">Bill Rejected</span>
                     <span class="info-box-number"><?= $bill_rejected_count ?></span>
                  </div>
               </a>
            </div>
         </div>
      </div>
</div>
<?php } ?>
<?php if ($_SESSION['role'] == 'super_admin') { ?>
<div class="row">
<div class="col-lg-12">
<div class="box box-primary">
<div class="box-header with-border" style="padding: 4px;">
<h3 class="box-title">Overall Report</h3>
</div>
<div class="box-body">
<div class="row">
<div class="col-md-2 text-center">
<div class="card">
<div class="card-body">
<span class="info-box-text">Scan</span>
<span class="info-box-number"><?= $overall_scan_count; ?></span>
</div>
</div>
</div>
<div class="col-md-2 text-center">
<div class="card">
<div class="card-body">
<span class="info-box-text">Punch</span>
<span class="info-box-number"><?= $overall_punch_count; ?></span>
</div>
</div>
</div>
<div class="col-md-2 text-center">
<div class="card">
<div class="card-body">
<span class="info-box-text">Approve</span>
<span class="info-box-number"><?= $overall_approve_count; ?></span>
</div>
</div>
</div>
<div class="col-md-2 text-center pull-right">
<div class="card">
<div class="card-body">
<span class="info-box-text">Pending for Approve</span>
<span class="info-box-number"><?= $overall_approve_pending_count; ?></span>
</div>
</div>
</div>
<div class="col-md-2 text-center pull-right">
<div class="card">
<div class="card-body">
<span class="info-box-text">Pending for Punch</span>
<span class="info-box-number"><?= $overall_punch_pending_count; ?></span>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12">
<div class="box box-primary">
<div class="box-body">
<form role="form" action="" method="post" class="class_search_form">
<div class="row">
<div class="col-sm-2">
<div class="form-group">
<label>From Date</label>
<input type="date" name="from_date" id="from_date" class="form-control" value="<?php if (set_value('from_date') != '') {echo set_value('from_date');} else {echo date('Y-m-d');}  ?>">
<span class="text-danger" id="error_from_date"></span>
</div>
</div>
<div class="col-sm-2">
<div class="form-group">
<label>To Date</label>
<input type="date" name="to_date" id="to_date" class="form-control" value="<?php if (set_value('to_date') != '') {echo set_value('to_date');} else {echo date('Y-m-d');}?>">
<span class="text-danger" id="error_to_date"></span>
</div>
</div>
<div class="col-sm-2">
<div class="form-group">
<label>Company</label>
<select name="group" id="group" class="form-control">
<option value="">Select</option>
<?php foreach ($grouplist as $key => $value) { ?>
<option value="<?= $value['group_id']; ?>" <?php if (set_value('group') == $value['group_id']) {
   echo "selected";
   } ?>><?= $value['group_name'] ?></option>
<?php } ?>
</select>
<span class="text-danger" id="error_group"></span>
</div>
</div>
<div class="col-sm-2">
<div class="form-group">
<label>User</label>
<select name="user" id="user" class="form-control">
<option value="">Select</option>
</select>
<span class="text-danger" id="error_user"></span>
</div>
</div>
<div class="col-sm-2" style="margin-top: 22px;">
<div class="form-group">
<button type="reset" class="btn btn-danger btn-sm pull-right" style="margin-left: 5px;"><i class="fa fa-undo"></i> Reset</button>
<button type="button" id="search" class="btn btn-success btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> Search</button>
</div>
</div>
</div>
</form>
</div>
</div>
<!--./box box-primary -->
</div>
</div>
<div class="row">
<div class="col-md-6">
<div class="box box-primary border0">
<div class="box-body">
<div id="complete_chart" width="400" height="250"></div>
</div>
</div>
</div>
<div class="col-md-6">
<div class="box box-primary border0">
<div class="box-body">
<div id="pending_chart" width="400" height="250"></div>
</div>
</div>
</div>
</div>
<div class="row" id="company_wise_report">
<div class="col-lg-12">
<div class="box box-primary">
<div class="box-header with-border">
<h3 class="box-title">Company Wise Report (Overall)</h3>
</div>
<div class="box-body">
<div class="row">
<div class="col-sm-2">
<div class="form-group">
<label>From Date</label>
<input type="date" name="from_date_overall" id="from_date_overall" class="form-control" value="<?php if (set_value('from_date_overall') != '') {
   echo set_value('from_date_overall');
   }   ?>">
<span class="text-danger" id="error_from_date_overall"></span>
</div>
</div>
<div class="col-sm-2">
<div class="form-group">
<label>To Date</label>
<input type="date" name="to_date_overall" id="to_date_overall" class="form-control" value="<?php if (set_value('to_date_overall') != '') {
   echo set_value('to_date_overall');
   }
   ?>">
<span class="text-danger" id="error_to_date_overall"></span>
</div>
</div>
<div class="col-sm-2" style="margin-top: 22px;">
<div class="form-group">
<button type="reset" class="btn btn-danger btn-sm pull-right" style="margin-left: 5px;" id="reset_btn"><i class="fa fa-undo"></i> Reset</button>
<button type="button" id="search_overall" class="btn btn-success btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> Search</button>
</div>
</div>
</div>
<div class="download_label">Company Wise Report</div>
<table class="table table-striped table-bordered table-hover" id="myTable">
<thead>
<tr>
<th>Company</th>
<th class="text-center">Scan</th>
<th class="text-center">Punch</th>
<th class="text-center">Approve</th>
<th class="text-center">Pending for Punch</th>
<th class="text-center">Pending for Approve</th>
<th class="text-center">Scan Rejected</th>
<th class="text-center">Punch Rejected</th>
</tr>
</thead>
<tbody></tbody>
<tfoot>
<tr>
<th>Sum</th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
</tr>
</tfoot>
</table>
</div>
</div>
</div>
</div>
<div class="row" id="bill_approver_report">
<div class="col-lg-12">
<div class="box box-primary">
<div class="box-header with-border">
<h3 class="box-title">Bill Approver Report (Overall)</h3>
</div>
<div class="box-body">
<!-- 	<div class="row">
   <div class="col-sm-2">
   	<div class="form-group">
   		<label>From Date</label>
   		<input type="date" name="from_date_overall_bill_approver" id="from_date_overall_bill_approver" class="form-control" value="<?php if (set_value('from_date_overall_bill_approver') != '') {
      echo set_value('from_date_overall_bill_approver');
      }   ?>">
   		<span class="text-danger" id="error_from_date_overall_bill_approver"></span>
   	</div>
   </div>
   <div class="col-sm-2">
   	<div class="form-group">
   		<label>To Date</label>
   		<input type="date" name="to_date_overall_bill_approver" id="to_date_overall_bill_approver" class="form-control" value="<?php if (set_value('to_date_overall_bill_approver') != '') {
      echo set_value('to_date_overall_bill_approver');
      }
      ?>">
   		<span class="text-danger" id="error_to_date_overall_bill_approver"></span>
   	</div>
   </div>
   
   <div class="col-sm-2" style="margin-top: 22px;">
   	<div class="form-group">
   		<button type="reset" class="btn btn-danger btn-sm pull-right" style="margin-left: 5px;" id="reset_btn"><i class="fa fa-undo"></i> Reset</button>
   		<button type="button" id="search_overall_bill_approver" class="btn btn-success btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> Search</button>
   	</div>
   </div>
   
   </div> -->
<table class="table table-striped table-bordered table-hover" id="bill_approver_table">
<thead>
<tr>
<th>Bill Approver</th>
<th class="text-center">Approved</th>
<th class="text-center">Rejected</th>
<th class="text-center">Pending</th>
</tr>
</thead>
<tbody></tbody>
<tfoot>
<tr>
<th>Sum</th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
</tr>
</tfoot>
</table>
</div>
</div>
</div>
</div>
<?php } ?>
<?php if ($_SESSION['role'] == 'super_scan') { ?>
<div class="row">
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="#">
<span class="info-box-icon bg-green"><i class="fa fa-list-alt"></i></span>
<div class="info-box-content">
<span class="info-box-text">Total Scanned</span>
<span class="info-box-number"><?= $overall_scan_count; ?></span>
</div>
</a>
</div>
</div>
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="#">
<span class="info-box-icon bg-red"><i class="fa fa-exclamation-triangle"></i></span>
<div class="info-box-content">
<span class="info-box-text">Total Rejected</span>
<span class="info-box-number"><?= $overall_scan_reject_count; ?></span>
</div>
</a>
</div>
</div>
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="javascript:void(0);">
<span class="info-box-icon bg-aqua"><i class="fa fa-exclamation-triangle"></i></span>
<div class="info-box-content">
<span class="info-box-text">Pending for Document Naming</span>
<span class="info-box-number"><?= $overall_pending_naming_count; ?></span>
</div>
</a>
</div>
</div>
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="javascript:void(0);">
<span class="info-box-icon bg-warning"><i class="fa fa-exclamation-triangle"></i></span>
<div class="info-box-content">
<span class="info-box-text">Pending for Document Verification</span>
<span class="info-box-number"><?= $overall_pending_verification_count; ?></span>
</div>
</a>
</div>
</div>
</div>
<div class="row">
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="<?= base_url(); ?>bill_rejected">
<span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
<div class="info-box-content">
<span class="info-box-text">Bill Rejected</span>
<span class="info-box-number"><?= $bill_rejected_count ?></span>
</div>
</a>
</div>
</div>
</div>
<div class="row" id="company_wise_report_scan">
<div class="col-lg-12">
<div class="box box-primary">
<div class="box-header with-border">
<h3 class="box-title">Company Wise Report</h3>
</div>
<div class="box-body">
<table class="table table-striped table-bordered table-hover" id="scanDetails">
<thead>
<tr>
<th>Company</th>
<th class="text-center">Scan</th>
<th class="text-center">Rejected</th>
<th class="text-center">Pending for naming</th>
<th class="text-center">Pending for verification</th>
</tr>
</thead>
<tbody>
</tbody>
<tfoot>
<tr>
<th>Sum</th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
<th class="text-center"></th>
</tr>
</tfoot>
</table>
</div>
</div>
</div>
</div>
<?php } ?>
<?php if ($_SESSION['role'] == 'bill_approver') { ?>
<div class="row">
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="<?= base_url(); ?>my_approved_bill">
<span class="info-box-icon bg-green"><i class="fa fa-list-alt"></i></span>
<div class="info-box-content">
<span class="info-box-text">Total Bill Approved by Me</span>
<span class="info-box-number"><?= $total_bill_approved_by_me ?></span>
</div>
</a>
</div>
</div>
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="<?= base_url(); ?>rejected_bill_by_me">
<span class="info-box-icon bg-red"><i class="fa fa-exclamation-triangle"></i></span>
<div class="info-box-content">
<span class="info-box-text">Total Bill Rejected by Me</span>
<span class="info-box-number"><?= $rejected_bill_by_me ?></span>
</div>
</a>
</div>
</div>
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="<?= base_url(); ?>pending_bill_approve">
<span class="info-box-icon bg-aqua"><i class="fa fa-exclamation-triangle"></i></span>
<div class="info-box-content">
<span class="info-box-text">Pending for Approvel</span>
<span class="info-box-number"><?= $bill_pending; ?></span>
</div>
</a>
</div>
</div>
</div>
<?php } ?>
<?php 
   if ($_SESSION['role'] == 'user') 
   {
   		if ($this->customlib->has_permission('Finance')) {
   			?>
<div class="row">
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="<?= base_url(); ?>my_punched_file/1">
<span class="info-box-icon bg-yellow"><i class="fa fa-list-alt"></i></span>
<div class="info-box-content">
<span class="info-box-text">Total Punched</span>
<span class="info-box-number"><?= $vspl_total_punched_by_me ?></span>
</div>
</a>
</div>
</div>
<!-- <div class="col-md-3 col-sm-6">
   <div class="info-box">
   	<a href="javascript:void(0);">
   		<span class="info-box-icon bg-red"><i class="fa fa-exclamation-triangle"></i></span>
   		<div class="info-box-content">
   			<span class="info-box-text">Pending For Approval</span>
   			<span class="info-box-number"><?= $vspl_pending_for_approval_punch_by_me ?></span>
   		</div>
   	</a>
   </div>
   </div> -->
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="javascript:void(0);">
<span class="info-box-icon bg-green"><i class="fa fa-check-circle-o"></i></span>
<div class="info-box-content">
<span class="info-box-text">Punch Approved</span>
<span class="info-box-number"><?= $vspl_total_approved ?></span>
</div>
</a>
</div>
</div>
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="<?= base_url(); ?>rejected_punch">
<span class="info-box-icon bg-red"><i class="fa fa-exclamation-triangle"></i></span>
<div class="info-box-content">
<span class="info-box-text">Punch Rejected</span>
<span class="info-box-number"><?= $vspl_rejected_punch ?></span>
</div>
</a>
</div>
</div>
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="<?= base_url(); ?>finance_rejected_punch_1">
<span class="info-box-icon bg-red"><i class="fa fa-exclamation-triangle"></i></span>
<div class="info-box-content">
<span class="info-box-text">Finance Punch Rejected</span>
<span class="info-box-number"><?= $vspl_finance_rejected_punch ?></span>
</div>
</a>
</div>
</div>
<div class="col-md-3 col-sm-6">
<div class="info-box">
<a href="<?= base_url(); ?>finance_punch">
<span class="info-box-icon bg-aqua"><i class="fa fa-exclamation-circle"></i></span>
<div class="info-box-content">
<span class="info-box-text">Pending for Punch</span>
<span class="info-box-number"><?= $vspl_pending_for_punch ?></span>
</div>
</a>
</div>
</div>
</div>
<?php 
   }
   }
   
   ?>
</section>
</div>
<script>
   var complete_chart_options = {
   	title: {
   		text: 'Scan Punch Approve (completed)',
   		align: 'center',
   		margin: 10,
   		offsetX: 0,
   		offsetY: 0,
   		floating: false,
   		style: {
   			fontSize: '14px',
   			fontWeight: 'bold',
   			fontFamily: 'Verdana, sans-serif',
   			color: '#263238'
   		},
   	},
   	/*  series: [{
   	     data: [1500, 675, 297]
   	 }], */
   	series: <?= $data_curr_date_complete ?>,
   	noData: {
   		text: 'Loading...'
   	},
   	chart: {
   		height: 250,
   		type: 'bar',
   	},
   	colors: complete_chart_colors,
   	plotOptions: {
   		bar: {
   			columnWidth: '45%',
   			distributed: true,
   		}
   	},
   	dataLabels: {
   		enabled: true
   	},
   	legend: {
   		show: true
   	},
   	xaxis: {
   		categories: [
   			'Scan',
   			'Punch',
   			'Approve'
   
   		],
   		labels: {
   			style: {
   				colors: complete_chart_colors,
   				fontSize: '12px'
   			}
   		}
   	},
   };
   var complete_chart_colors = ['#00a8ff', '#ff4081', '#ff4081'];
   var complete_chart = new ApexCharts(document.querySelector("#complete_chart"), complete_chart_options);
   complete_chart.render();
   
   
   var pending_chart_options = {
   	title: {
   		text: 'Punch Approve (pending)',
   		align: 'center',
   		margin: 10,
   		offsetX: 0,
   		offsetY: 0,
   		floating: false,
   		style: {
   			fontSize: '14px',
   			fontWeight: 'bold',
   			fontFamily: 'Verdana, sans-serif',
   			color: '#263238'
   		},
   	},
   	theme: {
   		monochrome: {
   			enabled: true,
   			color: '#EF5350',
   			shadeTo: 'light',
   			shadeIntensity: 0.65
   		}
   	},
   	series: <?= $data_curr_date_pending ?>,
   	chart: {
   		height: 250,
   		type: 'bar',
   	},
   	
   	plotOptions: {
   		bar: {
   			columnWidth: '45%',
   			distributed: true,
   		}
   	},
   	dataLabels: {
   		enabled: true
   	},
   	legend: {
   		show: true
   	},
   	xaxis: {
   		categories: [
   
   			'Punch',
   			'Approve'
   
   		],
   		labels: {
   			style: {
   				
   				fontSize: '12px'
   			}
   		}
   	},
   };
   
   var pending_chart = new ApexCharts(document.querySelector("#pending_chart"), pending_chart_options);
   pending_chart.render();
   $(document).on('change', '#group', function() {
   	var group = $(this).val();
   	$.ajax({
   		url: '<?= base_url() ?>Dashboard/getUserByGroup',
   		type: 'POST',
   		data: {
   			group: group
   		},
   		dataType: 'json',
   		success: function(res) {
   			$('#user').empty();
   			$('#user').append('<option value="">Select</option>');
   			$.each(res, function(i, item) {
   				$('#user').append($('<option>', {
   					value: item.user_id,
   					text: item.first_name + ' ' + item.last_name
   				}));
   			});
   		}
   	});
   });
   
   $(document).on('click', '#search', function() {
   	var from_date = $('#from_date').val();
   	var to_date = $('#to_date').val();
   	var group = $('#group').val();
   	var user = $('#user').val();
   	$.ajax({
   		url: '<?= base_url() ?>Dashboard/get_report_data',
   		type: 'POST',
   		data: {
   			from_date: from_date,
   			to_date: to_date,
   			group: group,
   			user: user
   		},
   		dataType: 'json',
   		success: function(res) {
   			complete_chart.updateSeries([{
   				data: JSON.parse(res.complete_data)
   			}]);
   
   			pending_chart.updateSeries([{
   				data: JSON.parse(res.pending_data)
   			}]);
   		}
   	});
   
   
   
   });
   
   $(document).on('click', "#reset_btn", function() {
   	window.location.reload();
   });
   
   $(document).ready(function() {
   	var table = $('#myTable').DataTable({
   		processing: true,
   		serverSide: true,
   		ordering: false,
   		searching: false,
   		paging: false,
   		info: false,
   		dom: 'Blfrtip',
   		buttons: [{
   			extend: 'excelHtml5',
   			text: '<i class="fa fa-file-excel-o"></i> Export',
   			titleAttr: 'Excel',
   			title: $('.download_label').html(),
   		}],
   		ajax: {
   			url: "get_overall_report",
   			headers: {
   				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   			},
   			data: function(d) {
   				d.From_Date = $('#from_date_overall').val();
   				d.To_Date = $('#to_date_overall').val();
   			},
   			type: 'POST',
   			dataType: "JSON",
   		},
   		columns: [{
   				data: 'group_name',
   				name: 'group_name'
   			},
   			{
   				data: 'Scan',
   				name: 'Scan',
   				className: 'text-center'
   			},
   			{
   				data: 'Punch',
   				name: 'Punch',
   				className: 'text-center'
   			},
   			{
   				data: 'Approve',
   				name: 'Approve',
   				className: 'text-center'
   			},
   			{
   				data: 'Pending_Punch',
   				name: 'Pending_Punch',
   				className: 'text-center'
   			},
   			{
   				data: 'Pending_Approve',
   				name: 'Pending_Approve',
   				className: 'text-center'
   			},
   			{
   				data: 'Scan_Reject',
   				name: 'Scan_Reject',
   				className: 'text-center'
   			},
   			{
   				data: 'Punch_Reject',
   				name: 'Punch_Reject',
   				className: 'text-center'
   			}
   		],
   		footerCallback: function(row, data, start, end, display) {
   			var api = this.api();
   
   			var getColumnTotal = function(columnIndex) {
   				return api.column(columnIndex, {
   					page: 'current'
   				}).data().reduce(function(a, b) {
   					return intVal(a) + intVal(b);
   				}, 0);
   			};
   
   			var intVal = function(i) {
   				return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
   			};
   
   			$(api.column(0).footer()).html('Total');
   			$(api.column(1).footer()).html(getColumnTotal(1));
   			$(api.column(2).footer()).html(getColumnTotal(2));
   			$(api.column(3).footer()).html(getColumnTotal(3));
   			$(api.column(4).footer()).html(getColumnTotal(4));
   			$(api.column(5).footer()).html(getColumnTotal(5));
   			$(api.column(6).footer()).html(getColumnTotal(6));
   			$(api.column(7).footer()).html(getColumnTotal(7));
   		}
   	});
   
   var bill_approver_table = $('#bill_approver_table').DataTable({
   		processing: true,
   		serverSide: true,
   		ordering: false,
   		searching: false,
   		paging: false,
   		info: false,
   		dom: 'Blfrtip',
   		buttons: [{
   			extend: 'excelHtml5',
   			text: '<i class="fa fa-file-excel-o"></i> Export',
   			titleAttr: 'Excel',
   			title: $('.download_label').html(),
   		}],
   		ajax: {
   			url: "get_overall_report_bill_approver",
   			headers: {
   				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   			},
   			data: function(d) {
   				d.From_Date = $('#from_date_overall_bill_approver').val();
   				d.To_Date = $('#to_date_overall_bill_approver').val();
   			},
   			type: 'POST',
   			dataType: "JSON",
   		},
   		columns: [{
   				data: 'bill_approver',
   				name: 'bill_approver'
   			},
   			{
   				data: 'approved',
   				name: 'approved',
   				className: 'text-center'
   			},
   			{
   				data: 'rejected',
   				name: 'rejected',
   				className: 'text-center'
   			},
   			{
   				data: 'pending',
   				name: 'pending',
   				className: 'text-center'
   			}
   			
   		],
   		footerCallback: function(row, data, start, end, display) {
   			var api = this.api();
   
   			var getColumnTotal = function(columnIndex) {
   				return api.column(columnIndex, {
   					page: 'current'
   				}).data().reduce(function(a, b) {
   					return intVal(a) + intVal(b);
   				}, 0);
   			};
   
   			var intVal = function(i) {
   				return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
   			};
   
   			$(api.column(0).footer()).html('Total');
   			$(api.column(1).footer()).html(getColumnTotal(1));
   			$(api.column(2).footer()).html(getColumnTotal(2));
   			$(api.column(3).footer()).html(getColumnTotal(3));
   
   		}
   	});
   	
   	var table1 = $('#approveDetails').DataTable({
   		processing: true,
   		serverSide: true,
   		ordering: false,
   		searching: false,
   		paging: false,
   		info: false,
   		dom: 'Blfrtip',
   		buttons: [{
   			extend: 'excelHtml5',
   			text: '<i class="fa fa-file-excel-o"></i> Export',
   			titleAttr: 'Excel',
   			title: $('.download_label').html(),
   		}, ],
   		ajax: {
   			url: "get_report_for_super_approver",
   			headers: {
   				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   			},
   			data: function(d) {
   				d.From_Date = $('#from_date_overall').val();
   				d.To_Date = $('#to_date_overall').val();
   
   			},
   			type: 'POST',
   			dataType: "JSON",
   		},
   		columns: [{
   				data: 'group_name',
   				name: 'group_name',
   
   			},
   
   			{
   				data: 'Approve',
   				name: 'Approve',
   				className: 'text-center'
   			},
   			{
   				data: 'Reject',
   				name: 'Reject',
   				className: 'text-center'
   			},
   			{
   				data: 'Pending_Approve',
   				name: 'Pending_Approve',
   				className: 'text-center'
   			},
   
   		],
   		footerCallback: function(row, data, start, end, display) {
   			var api = this.api();
   
   			var getColumnTotal = function(columnIndex) {
   				return api.column(columnIndex, {
   					page: 'current'
   				}).data().reduce(function(a, b) {
   					return intVal(a) + intVal(b);
   				}, 0);
   			};
   
   			var intVal = function(i) {
   				return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
   			};
   
   			$(api.column(0).footer()).html('Total');
   			$(api.column(1).footer()).html(getColumnTotal(1));
   			$(api.column(2).footer()).html(getColumnTotal(2));
   			$(api.column(3).footer()).html(getColumnTotal(3));
   
   		}
   	});
   
   	var scanDetails = $('#scanDetails').DataTable({
   		processing: true,
   		serverSide: true,
   		ordering: false,
   		searching: false,
   		paging: false,
   		info: false,
   		dom: 'Blfrtip',
   		buttons: [{
   			extend: 'excelHtml5',
   			text: '<i class="fa fa-file-excel-o"></i> Export',
   			titleAttr: 'Excel',
   			title: $('.download_label').html(),
   		}, ],
   		ajax: {
   			url: "get_report_for_super_scanner",
   			headers: {
   				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   			},
   			data: function(d) {
   				d.From_Date = $('#from_date_overall').val();
   				d.To_Date = $('#to_date_overall').val();
   
   			},
   			type: 'POST',
   			dataType: "JSON",
   		},
   		columns: [{
   				data: 'group_name',
   				name: 'group_name',
   
   			},
   
   			{
   				data: 'Scan',
   				name: 'Scan',
   				className: 'text-center'
   			},
   			{
   				data: 'Reject',
   				name: 'Reject',
   				className: 'text-center'
   			},
   			{
   				data: 'Pending',
   				name: 'Pending',
   				className: 'text-center'
   			},
   			{
   				data: 'Pending_Verification',
   				name: 'Pending_Verification',
   				className: 'text-center'
   			},
   
   		],
   		footerCallback: function(row, data, start, end, display) {
   			var api = this.api();
   
   			var getColumnTotal = function(columnIndex) {
   				return api.column(columnIndex, {
   					page: 'current'
   				}).data().reduce(function(a, b) {
   					return intVal(a) + intVal(b);
   				}, 0);
   			};
   
   			var intVal = function(i) {
   				return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
   			};
   
   			$(api.column(0).footer()).html('Total');
   			$(api.column(1).footer()).html(getColumnTotal(1));
   			$(api.column(2).footer()).html(getColumnTotal(2));
   			$(api.column(3).footer()).html(getColumnTotal(3));
   			$(api.column(4).footer()).html(getColumnTotal(4));
   
   		}
   	});
   
   	getCurrentDateData();
   });
   
   $(document).on("click", "#search_overall", function() {
   	$("#myTable").DataTable().draw(true);
   });
   
   function getCurrentDateData() {
   	var from_date = $('#from_date').val();
   	var to_date = $('#to_date').val();
   	var group = $('#group').val();
   	var user = $('#user').val();
   	$.ajax({
   		url: '<?= base_url() ?>Dashboard/get_report_data',
   		type: 'POST',
   		data: {
   			from_date: from_date,
   			to_date: to_date,
   			group: group,
   			user: user
   		},
   		dataType: 'json',
   		success: function(res) {
   			complete_chart.updateSeries([{
   				data: JSON.parse(res.complete_data)
   			}]);
   
   			pending_chart.updateSeries([{
   				data: JSON.parse(res.pending_data)
   			}]);
   		}
   	});
   }
</script>