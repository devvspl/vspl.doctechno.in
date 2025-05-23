<?php
$rj_list = $this->customlib->getRejectReason();
?>
<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Approve Files</h3>
						<?php if ($this->session->flashdata('message')) { ?>
							<?php echo $this->session->flashdata('message') ?>
						<?php } ?>
					</div>
					<div class="box-body">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Approve Files</div>
							<table class="table table-striped table-bordered table-hover example">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Document Name</th>
										<th>Document Type</th>
										<th>File</th>
										<th>Punch By</th>
										<th>Punch Date</th>
										<th class="no-print">Support</th>
										<th class="no-print">view</th>
										<th>Tally Entry</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (empty($pending_for_approval)) {
									?>
										<?php
									} else {
										$count = 1;
										foreach ($pending_for_approval

											as $row) {
										?>
											<tr>
												<td><?php echo $count++; ?></td>
												<td class="mailbox-name">
													<?php echo $row['document_name']; ?>
												</td>
												<td class="mailbox-name">
													<?php echo $row['doc_type']; ?>
												</td>
												<td class="mailbox-name">
													<a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
												</td>
												<td class="mailbox-name">
													<?php echo $this->customlib->get_Name($row['punched_by']); ?>
												</td>

												<td class="mailbox-name">
													<?php echo date('d-m-Y', strtotime($row['punched_date'])) ?>
												</td>

												<td class="mailbox-name text-center no-print">
													<?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
														<a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
													<?php } ?>
												</td>
												<td>
													<a href="<?php echo base_url(); ?>file_detail/<?= $row['scan_id'] ?>/<?= $row['doc_type_id'] ?>" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>
												</td>
												<td class="text-center">
													<?php
													if ($row['is_entry_confirmed'] == 'Y') {
														echo 'Yes';
													} else {
														echo 'No';
													}
													?>
												</td>
												<td>
													<?php
													if ($row['is_entry_confirmed'] == 'Y') { ?>
														<a href="<?php echo base_url(); ?>approve_record/<?= $row['scan_id'] ?>" class="btn btn-success btn-xs" onclick="return confirm('Are you sure you want to approve this file?');">Approve</a>
													<?php } ?>
													<a href="javascript:void(0);" class="btn btn-danger btn-xs" data-id="<?= $row['scan_id'] ?>" id="reject_record">Reject</a>
												</td>
										<?php
										}
										$count++;
									}
										?>

								</tbody>
							</table><!-- /.table -->
						</div><!-- /.mail-box-messages -->
					</div><!-- /.box-body -->
				</div>
			</div>
		</div>
	</section>
</div>
<div id="SupportFileView" class="modal fade" role="dialog" aria-hidden="true" style="display: none;">
	<div class="modal-dialog modalwrapwidth">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
			<div class="scroll-area">
				<div class="modal-body paddbtop">
					<div id="detail">

					</div>
				</div>
			</div>
		</div>
	</div>
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
	function getSupportFile(scan_id) {
		$.ajax({
			url: '<?php echo base_url(); ?>Punch/getSupportFile',
			type: 'POST',
			data: {
				scan_id: scan_id
			},
			dataType: 'json',
			success: function(response) {

				if (response.status == 200) {

					var x = '';
					$.each(response.data, function(index, value) {

						x += '<object data="' + value.file_path + '" type="application/pdf" width="100%" height="500px"></object>';

					});
					$('#detail').html(x);
					$('#SupportFileView').modal('show');
				}


			}
		});
	}


	$(document).on("click", "#reject_record", function() {
		var scan_id = $(this).data('id');
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
	});

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
					location.reload();
				}
			}

		});
	});
</script>
