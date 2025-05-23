<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css" />
<style>
    #ui-datepicker-div {
        z-index: 999 !important;
    }
</style>
<?php
$user_id = $this->session->userdata('user_id');
$this->db->select('*');
$this->db->from("y{$this->year_id}_scan_file");
$this->db->join('master_work_location', 'master_work_location.location_id = y{$this->year_id}_scan_file.location_id', 'left');
$this->db->where('scanned_by', $user_id);
$this->db->where('is_file_punched', 'N');
$this->db->where('is_scan_resend', 'N');
$this->db->where('Group_id', $group_id);
$this->db->order_by('scan_id', 'desc');
$my_lastest_scan = $this->db->get()->result_array();



$this->db->select('*');
$this->db->from("y{$this->year_id}_scan_file");
$this->db->where('group_id', $group_id);
$this->db->where('is_scan_resend', 'Y');
$query = $this->db->get();
$scan_rejected =  $query->num_rows();


$this->db->select('*');
$this->db->from("y{$this->year_id}_scan_file");
$this->db->where('group_id', $group_id);
$this->db->where('is_temp_scan', 'Y');
$this->db->where('is_scan_complete', 'N');
$this->db->where('is_temp_scan_rejected', 'N');
$result = $this->db->get();
$scan_pending_name = $result->num_rows();

$this->db->select('*');
$this->db->from("y{$this->year_id}_scan_file");
$this->db->where('group_id', $group_id);
$this->db->where('is_temp_scan', 'Y');
$this->db->where('is_scan_complete', 'Y');
$this->db->where('is_temp_scan_rejected', 'N');
$this->db->where('is_document_verified','N');
$this->db->where('is_deleted','N');
$result = $this->db->get();
$scan_pending_verification = $result->num_rows();
?>

<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title text-danger"><?= $group_name ?></h3>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="info-box">
								<a href="<?= base_url(); ?>super_scan_rejected_list/<?= $group_id ?>">
									<span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
									<div class="info-box-content">
										<span class="info-box-text">Total Scan File Rejected</span>
										<span class="info-box-number"><?= $scan_rejected ?></span>
									</div>
								</a>
							</div>
						</div>
						<div class="col-md-4">
							<div class="info-box">
								<a href="<?= base_url(); ?>super_scan_naming_list/<?= $group_id ?>">
									<span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
									<div class="info-box-content">
										<span class="info-box-text">Pending For Document Naming</span>
										<span class="info-box-number"><?= $scan_pending_name ?></span>
									</div>
								</a>
							</div>
						</div>

						<div class="col-md-4">
							<div class="info-box">
								<a href="<?= base_url(); ?>super_scan_verification_list/<?= $group_id ?>">
									<span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>
									<div class="info-box-content">
										<span class="info-box-text">Pending For Verification</span>
										<span class="info-box-number"><?= $scan_pending_verification ?></span>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				
			<!-- Horizontal Form -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Scan File</h3>
					</div>
					
					

					<form id="form1" action="<?= base_url(); ?>Super_scan/upload_main" name="scan_main" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <input type="hidden" name="group_id" id="group_id" value="<?= $group_id; ?>">   
					<div class="box-body">
                            <?php if ($this->session->flashdata('message')) { ?>
                            <?= $this->session->flashdata('message') ?>
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="doctype">Document Type<i style="color: red;">*</i></label>
                                        <select name="doctype_id" id="doctype" class="form-control" required>
                                            <option value="">Select Document Type</option>
                                            <?php foreach ($doctypeList as $value) { ?>
                                            <option value="<?= $value['type_id'] ?>"><?= $value['file_type'] ?></option>
                                            <?php } ?>
                                            <span class="text-danger"><?php echo form_error('doctype'); ?></span>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location">Location<i style="color: red;">*</i></label>
                                        <select name="location" id="location" class="form-control" required>
                                            <option value="">Select Location</option>
                                            <?php foreach ($locationlist as $value) { ?>
                                            <option value="<?= $value['location_id'] ?>"><?= $value['location_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('location'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department">Department</label>
                                        <select name="department_id" id="department" class="form-control" >
                                            <option value="">Select Department</option>
                                            <?php foreach ($departmentList as $value) { ?>
                                            <option value="<?= $value['department_id'] ?>"><?= $value['department_name'] ?></option>
                                            <?php } ?>
                                            <span class="text-danger"><?php echo form_error('department'); ?></span>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firm">Vendor Name/Exp Head</label>
                                        <select name="firm_id" id="firm" class="form-control" >
                                            <option value="">Select Vendor Name / Exp Head</option>
                                            <?php foreach ($firmList as $value) { ?>
                                            <option value="<?= $value['firm_id'] ?>" data-name="<?= $value['firm_name'] ?>"><?= $value['firm_name'] ?></option>
                                            <?php } ?>
                                            <span class="text-danger"><?php echo form_error('firm'); ?></span>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bill_approver">Bill Approver<i style="color: red;">*</i></label>
                                        <select name="bill_approver" id="bill_approver" class="form-control" required>
                                            <option value="">Select Approver</option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('bill_approver'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bill_voucher_date">Bill Date/Voucher Date</label>
                                        <input type="text" placeholder="MM/DD/YYYY" name="bill_voucher_date" id="bill_voucher_date" class="form-control"  />
                                        <span class="text-danger"><?php echo form_error('bill_voucher_date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bill_no_voucher_no">Bill No/Voucher No</label>
                                        <input type="text" placeholder="Enter Bill No or Voucher No" name="bill_no_voucher_no" id="bill_no_voucher_no" class="form-control"  />
                                        <span class="text-danger"><?php echo form_error('bill_no_voucher_no'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="document_name">Document Name<i style="color: red;">*</i></label>
                                <input type="text" name="document_name" id="document_name" class="form-control" required />
                                <span class="text-danger"><?php echo form_error('document_name'); ?></span>
                            </div>
                            <div class="form-group">
                                <input class="filestyle form-control" type="file" name="main_file" id="main_file" accept="image/*,application/pdf" />
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" id="upload_main" class="btn btn-info pull-right">Save</button>
                        </div>
                    </form>
				</div>
			</div>

			<div class="col-md-8">
				
			<!-- general form elements -->
				<div class="box box-primary" id="exphead">
					<div class="box-header ptbnull">
						<h3 class="box-title titlefix">Latest Scan File <small style="color:red;">(Pending for Punching)</small></h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Latest Scan File</div>
							<table class="table table-striped table-bordered table-hover" id="latestScanFileTable">
								<thead>
								<tr>
									<th>S.No</th>
									<th>Document Name</th>
									<th>File</th>
									<th>Temp ScanDT</th>
									<th>ScanDT</th>
									<th>Location</th>
									<th>Bill Approver</th>
									<th>Final Submit</th>
									<th class="text-right no-print">Action</th>
								</tr>
								</thead>
								<tbody>
								<?php if (empty($my_lastest_scan)) {
									?>

									<?php
								} else {
									$count = 1;
									foreach ($my_lastest_scan as $row) {
										?>
										<tr>
											<td><?php echo $count++; ?></td>
											<td class="mailbox-name">
												<?php echo $row['document_name']; ?>
											</td>
											<td class="mailbox-name">
												<a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path']  ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
											</td>
											<td class="mailbox-name">
												<?= !empty($row['temp_scan_date']) ? date('d-m-Y', strtotime($row['temp_scan_date'])) : ''; ?>

											</td>
											<td class="mailbox-name">
												<?= date('d-m-Y', strtotime($row['scan_date'])); ?>
											</td>
											<td class="mailbox-name">
												<?php echo $row['location_name']; ?>
											</td>
											<td class="mailbox-name">
												<?php echo $this->customlib->get_Name($row['bill_approver_id']); ?>
											</td>
											<td class="mailbox-name">
												<?php echo ($row['is_final_submitted'] == 'Y') ? 'Yes' : 'No' ?>
											</td>
											<td class="mailbox-date pull-right no-print">
												<?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
													<a data-toggle="collapse" href="#detail<?= $row['scan_id'] ?>" data-parent="#mytable" style="cursor: pointer;" class="btn btn-default btn-xs"> <i class="fa fa-eye"></i></a>
												<?php } ?>
												<a href="<?php echo base_url(); ?>Scan/temp_upload_supporting/<?php echo $row['scan_id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                       </a>
												<a href="javascript:void(0);" data-scan_id="<?= $row['scan_id']; ?>" class="btn btn-default btn-xs" id="delete_all">
													<i class="fa fa-remove"></i>
												</a>
											</td>
										</tr>


										<?php
									}
									$count++;
								}
								?>

								</tbody>
							</table>
							<!-- /.table -->
						</div>
						<!-- /.mail-box-messages -->
					</div>
					<!-- /.box-body -->
				</div>
			</div> 
			<!-- right column -->
		</div> 
		<!-- /.row -->
	</section>
</div>
<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
<script>
    var groupId = "<?php echo $group_id ?>";

    var defaultApproverId = 115;
    var approversByLocation = <?= json_encode($approversByLocation ?? []) ?>;
    var approversByDepartment = <?= json_encode($approversByDepartment ?? []) ?>;
    function appendDefaultApprover($approverSelect, defaultApproverId) {
        var defaultApproverExists = false;


        $approverSelect.find('option').each(function() {
            if ($(this).val() == defaultApproverId) {
                defaultApproverExists = true;
                return false;
            }
        });


        if (!defaultApproverExists) {
            $approverSelect.append('<option value="' + defaultApproverId + '" selected>Auto Approver</option>');
        }
    }

    function populateApproversByLocation(locationId, defaultApproverId) {
        var $approverSelect = $('#bill_approver');
        $approverSelect.empty().append('<option value="">Select Approver</option>');

        if (approversByLocation[locationId]) {
            $.each(approversByLocation[locationId], function(index, approver) {
                $approverSelect.append('<option value="' + approver.user_id + '">' + approver.first_name + ' ' + approver.last_name + '</option>');
            });
        }


        appendDefaultApprover($approverSelect, defaultApproverId);
    }

    function populateApproversByDepartment(departmentId, defaultApproverId) {
        var $approverSelect = $('#bill_approver');
        $approverSelect.empty().append('<option value="">Select Approver</option>');

        if (approversByDepartment[departmentId]) {
            $.each(approversByDepartment[departmentId], function(index, approver) {
                $approverSelect.append('<option value="' + approver.user_id + '">' + approver.first_name + ' ' + approver.last_name + '</option>');
            });
        }


        appendDefaultApprover($approverSelect, defaultApproverId);
    }

	function generateDocumentName(format) {
   // Get the date value and format it
   var dateVal = $('#bill_voucher_date').val();
   var formattedDate = '';
   if (dateVal) {
      var dateParts = dateVal.split('/');
      if (dateParts.length === 3) {
         formattedDate = dateParts[1] + dateParts[0] + dateParts[2].substr(-2);
      }
   }

   // Trim the docType value to remove leading and trailing spaces
   var docType = $('#doctype option:selected').text().trim();

   // Initialize vendorName based on the docType
   var vendorName = '';
   if (docType === 'Labour Payment') {
      vendorName = (docType + ' ' + ($('#location option:selected').text() || '')).toUpperCase();
   } else {
      vendorName = (($('#firm option:selected').data('name') || docType) || '').toUpperCase();
   }

   // Get the bill number or voucher number
   var billNoVoucherNo = $('#bill_no_voucher_no').val() || '';

   // Generate the document name based on the selected format
   var documentName = '';
   if (format === 'underscore') {
      documentName = formattedDate + '_' + vendorName + '_' + billNoVoucherNo;
   } else if (format === 'slash') {
      documentName = formattedDate + '/' + vendorName + '/' + billNoVoucherNo;
   }

   // Set the generated document name in the input field
   $('#document_name').val(documentName);
}


    $(document).ready(function () {

       $("#location, #doctype, #department, #firm, #bill_approver").select2();
       $("#bill_voucher_date").datepicker();


      

	   $('#bill_voucher_date, #bill_no_voucher_no, #location, #doctype, #firm').on('change keyup', function () {
      generateDocumentName('underscore');
   });



       $('#scan_form').on('submit', function (e) {
          e.preventDefault();
          var form = this;
          $.ajax({
             url: $(form).attr('action'),
             method: $(form).attr('method'),
             data: new FormData(form),
             processData: false,
             dataType: 'json',
             contentType: false,
             success: function (data) {
                alert(data.message);
             }
          });
       });

	

       $(document).on('click', '#delete_all', function () {
          var scan_id = $(this).data('scan_id');
          var url = '<?= base_url() ?>Scan/delete_all';
          if (confirm('Are you sure to delete all ?')) {
             $.ajax({
                url: url,
                type: 'POST',
                data: { 'scan_id': scan_id },
                dataType: 'json',
                success: function (data) {
                   if (data.status == 200) {
                      window.location.href = '<?= base_url() ?>Scan';
                   }
                }
             });
          }
       });

       var initialLocationId = $('#location').val();
       var initialDepartmentId = $('#department').val();

        if (groupId == 16) {
            if (initialDepartmentId) {
                populateApproversByDepartment(initialDepartmentId, defaultApproverId);
            }

            $('#department').on('change', function() {
                var departmentId = $(this).val();
                if (departmentId) {
                    populateApproversByDepartment(departmentId, defaultApproverId);
                }
            });
        } else {
            if (initialLocationId) {
                populateApproversByLocation(initialLocationId, defaultApproverId);
            }

            $('#location').on('change', function() {
                var locationId = $(this).val();
                if (locationId) {
                    populateApproversByLocation(locationId, defaultApproverId);
                }
            });
        }
    });
</script>
