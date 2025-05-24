<?php
$this->db->select('master_group.group_name, SUM(IF(y{$this->year_id}_scan_file.is_file_punched = "Y" AND y{$this->year_id}_scan_file.is_file_approved = "N" AND y{$this->year_id}_scan_file.Is_Rejected = "N", 1, 0)) as total_count,y{$this->year_id}_scan_file.group_id');
$this->db->from("y{$this->year_id}_scan_file");
$this->db->join('master_group', 'master_group.group_id = y{$this->year_id}_scan_file.group_id');
$this->db->where('y{$this->year_id}_scan_file.is_deleted', 'N');
$this->db->group_by('master_group.group_id');
$this->db->order_by('total_count', 'desc');

$group_list = $this->db->get()->result_array();






$rj_list = $this->customlib->getRejectReason();
$doctype_list = $this->db->select('type_id,file_type')->from('master_doctype')->where('status', 'A')->get()->result_array();
?>
<style>
	.info-box-icon1 {
		display: block;
		float: left;
		height: 41px;
		width: 50px;
		text-align: center;
		font-size: 26px;
		line-height: 43px;
		background: rgba(0, 0, 0, 0.2);
		border-radius: 3px;
		-webkit-box-shadow: 0px 9px 19px -9px rgba(229, 58, 54, 0.65);
		-moz-box-shadow: 0px 9px 19px -9px rgba(229, 58, 54, 0.65);
		box-shadow: 0px 8px 10px -9px rgba(53, 51, 51, 0.67);
		border: 2px solid #fff inset;
	}
</style>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<?php
			foreach ($group_list as $key => $value) { ?>
				<div class="col-md-3 col-sm-6">
					<div class="info-box">
						<a href="javascript:void(0);" onclick="getPendingRecords(<?= $value['group_id'] ?>,null);">
							<span class="info-box-icon1 bg-green"><i class="fa fa-list-alt"></i></span>
							<div class="info-box-content">
								<div class="row">
									<div class="col-md-9"><span class="info-box-text"><?= $value['group_name'] ?></span></div>
									<div class="col-md-3" style="text-align: right;"><span class="info-box-number"><?= $value['total_count'] ?></span></div>
								</div>
							</div>
						</a>
					</div>
				</div>
			<?php } ?>
		</div>

		<div class="row" id="table_div" style="display: none;">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Pending Files for Approval (<b id="group_name"></b>)</h3>
						<?php if ($this->session->flashdata('message')) { ?>
							<?php echo $this->session->flashdata('message') ?>
						<?php } ?>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-6"></div>
							<div class="col-md-3">
								<label for="">Document Type:</label>
								<input type="hidden" name="group_id" id="group_id">
								<select name="document_type" id="document_type" class="form-control form-select form-control-lg">
									<option value="">Select</option>
									<?php

									foreach ($doctype_list as $value) { ?>
										<option value="<?= $value['type_id'] ?>"> <?= $value['file_type'] ?> </option>
									<?php  }
									?>
								</select>
							</div>
						</div>
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Approve Files</div>
							<table class="table table-striped table-bordered table-hover" id="myTable">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Document Name</th>
										<th>Document Type</th>
										<th>File</th>
										<th>Punch By</th>
										<th>Punch Date</th>

										<th class="no-print">view</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody id="record"></tbody>

							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
			<div class="scroll-area">
				<div class="modal-body ">
					<div class="form-group">
						<input type="hidden" name="scan_id" id="scan_id">
						<label for="Reject_Remark">Rejection Reason :</label> <span class="text-danger">*</span>
						<select name="Reject_Remark" id="Reject_Remark" class="form-control form-select select2">
							<option value="">Select</option>
							<?php foreach ($rj_list as $row) { ?>
								<option value="<?php echo $row['reason']; ?>"><?php echo $row['reason']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="box-footer">
					<button type="button" id="reject_btn" class="btn btn-success">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Add Reason Modal -->
<div id="myModal" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
			<div class="scroll-area">
				<div class="modal-body ">
					<div class="form-group">
						<label for="reason">Rejection Reason :</label> <span class="text-danger">*</span>
						<input type="text" name="reason" id="reason" class="form-control" required>
					</div>
				</div>
				<div class="box-footer">
					<button type="button" id="save_btn" class="btn btn-success pull-right">Save</button>
				</div>

			</div>
		</div>
	</div>
</div>
<script>
	$("#document_type").select2();

	function getPendingRecords(group_id, doctype = null) {
		var sNo = 1; // Initialize the serial number

		$.ajax({
			url: '<?= base_url() ?>Approve/get_pending_list_approve',
			type: 'POST',
			data: {
				group_id: group_id,
				doctype: doctype
			},
			dataType: 'json',
			success: function(res) {
				$("#group_name").html(res.group_name);
				// Get a reference to the DataTable
				var table = $('#myTable').DataTable();

				// Clear the existing rows
				table.clear().draw();

				// Add the new data to the DataTable
				$.each(res.data, function(key, value) {
					// Create the hyperlink HTML
					var fileLink = '<a href="javascript:void(0);" target="popup" onclick="window.open(\'' + value.file_path + '\', \'popup\', \'width=600,height=600\');">' + value.File + '</a>';
					// Create the hyperlink HTML for viewing file details
					var viewFileLink = '<a href="' + '<?= base_url() ?>file_detail/' + value.scan_id + '/' + value.doc_type_id + '" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>';
					// Create the "Approve" and "Reject" buttons
					var button = '<a href="javascript:void(0)" class="btn btn-success btn-xs" onclick="approveRecord(' + value.scan_id + ');">Approve</a> <a href="javascript:void(0);" class="btn btn-danger btn-xs" data-id="' + value.scan_id + '" onclick="rejectRecord(' + value.scan_id + ');">Reject</a>';
					table.row.add([
						sNo++,
						value.document_name ,
						value.Doc_Type,
						fileLink, // Add the hyperlink HTML to the table cell
						value.full_name,
						value.punched_date,
						viewFileLink, // Add your additional columns here
						button,
					]).draw(false);
				});
				$("#group_id").val(group_id);
				$("#table_div").css('display', 'block');

			},
			error: function(err) {
				// Handle any errors here
				console.error(err);
			}
		});
	}


	$(document).on("change", "#document_type", function() {
		var group_id = $("#group_id").val();
		var doctype = $(this).val();
		getPendingRecords(group_id, doctype);
	});

	function rejectRecord(scan_id) {


		$("#scan_id").val(scan_id);
		$("#rejectModal").modal("show");
		$("#Reject_Remark").select2({
			dropdownParent: $('#rejectModal'),
			width: '100%',
			allowClear: true,
			escapeMarkup: function(markup) {
				return markup;
			},
			placeholder: "Select Rejection Reason",
			language: {
				noResults: function() {
					return "<button class='btn btn-primary btn-block' data-target='#myModal' data-toggle='modal'>Add New Reason</button>";
				}
			}
		});
	}

	$(document).on('click', "#save_btn", function() {
		var reason = $("#reason").val();
		if (reason == '' || reason == null) {
			$("#reason").focus();
			$("#reason").css('border-color', 'red');
			return false;
		}
		$.ajax({
			type: 'POST',
			url: '<?= base_url() ?>master/RejectionReasonController/save_reason',
			data: {
				reason: reason,
			},
			async: false,
			dataType: 'json',
			beforeSend: function() {},
			success: function(response) {
				if (response.status == 200) {
					$("#Reject_Remark").append('<option value="' + reason + '">' + reason + '</option>');

					//modal close
					$("#myModal").modal('hide');
				} else {
					alert(response.msg);
				}
			},

		});
	});

	$(document).on('click', "#reject_btn", function() {
		var scan_id = $("#scan_id").val();
		var group_id = $("#group_id").val();
		var doctype = $("#document_type").val();

		var Reject_Remark = $("#Reject_Remark").val();
		if (Reject_Remark == '' || Reject_Remark == null) {
			$("#Reject_Remark").focus();
			$("#Reject_Remark").css('border-color', 'red');
			return false;
		}
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>reject_record/' + scan_id,
			data: {
				Remark: Reject_Remark,
			},
			async: false,
			dataType: 'json',
			success: function(response) {
				if (response.status == 200) {
					//modal close
					$("#rejectModal").modal('hide');
					alert('Record Rejected Successfully');
					getPendingRecords(group_id, doctype);
					$("#Reject_Remark").val("");
				}
			}

		});
	});

	function approveRecord(scan_id) {
		var group_id = $("#group_id").val();
		var doctype = $("#document_type").val();

		if (confirm("Are you sure to approve this file")) {
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url(); ?>approve_record_by_super_approver/' + scan_id,
				async: false,
				dataType: 'json',
				success: function(response) {
					if (response.status == 200) {
						alert(response.message);
						getPendingRecords(group_id, doctype);
					} else {
						alert(response.message);
					}
				}
			});
		}


	}
</script>
