<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">

		<div class="row">
			<div class="col-md-4">
				<!-- Horizontal Form -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Scan File</h3>
					</div>
					<form id="form1" action="<?= base_url(); ?>Scan/upload_main" id="scan_main" name="scan_main" method="post" accept-charset="utf-8" enctype="multipart/form-data">
						<div class="box-body">
							<?php if ($this->session->flashdata('message')) { ?>
								<?php echo $this->session->flashdata('message') ?>
							<?php } ?>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="location">Location <i style="color: red;">*</i></label>
										<select name="location" id="location" class="form-control" required>
											<option value="">Select Location</option>
											<?php foreach ($locationlist as $key => $value) { ?>
												<option value="<?= $value['location_id'] ?>">
													<?= $value['location_name'] ?>
												</option>
											<?php } ?>
											<span class="text-danger"><?php echo form_error('location'); ?></span>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="bill_approver">Bill Approver <i style="color: red;">*</i></label>
										<select name="bill_approver" id="bill_approver" class="form-control" required>
											<option value="">Select Approver</option>
											<?php foreach ($bill_approver_list as $key => $value) { ?>
												<option value="<?= $value['user_id'] ?>">
													<?= $value['first_name'].' '.$value['last_name'] ?>
												</option>
											<?php } ?>
											<span class="text-danger"><?php echo form_error('bill_approver'); ?></span>
										</select>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for="doc_type">Document Name <i style="color: red;">*</i></label>
								<input type="text" name="document_name" id="document_name" class="form-control" required>
								<span class="text-danger"><?php echo form_error('document_name'); ?></span>
							</div>

							<div class="form-group">
								<input class="filestyle form-control" type='file' name='main_file' id="main_file" accept="image/*,application/pdf">
							</div>
						</div><!-- /.box-body -->
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
					</div><!-- /.box-header -->
					<div class="box-body">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Latest Scan File</div>
							<table class="table table-striped table-bordered table-hover" id="mytable">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Location</th>
										<th>Document Name</th>
										<th>File</th>
										<th>ScanDT</th>
										<th>Final Submit</th>
										<th>Bill Approved</th>
										<th>Bill Approver</th>
										<th>Bill Approver Remark</th>
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
													<?php echo $row['location_name']; ?>
												</td>
												<td class="mailbox-name">
													<?php echo $row['document_name']; ?>
												</td>
												<td class="mailbox-name">
													<a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path']  ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
												</td>
												<td class="mailbox-name">
													<?= date('d-m-Y', strtotime($row['scan_date'])); ?>
												</td>
												<td class="mailbox-name">
													<?php echo ($row['is_final_submitted'] == 'Y') ? 'Yes' : 'No' ?>
												</td>
												<td class="mailbox-name">
													<?php echo $row['bill_approval_status']; ?>
												</td>
												<td class="mailbox-name">
													<?php echo $this->customlib->get_Name($row['bill_approver_id']); ?>
												</td>
												<td class="mailbox-name">
													<?php echo $row['bill_approver_remark']; ?>
												</td>
												<td class="mailbox-date pull-right no-print">
													<?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
														<a data-toggle="collapse" href="#detail<?= $row['scan_id'] ?>" data-parent="#mytable" style="cursor: pointer;" class="btn btn-default btn-xs"> <i class="fa fa-eye"></i></a>
													<?php } ?>
													<a href="<?php echo base_url(); ?>Scan/upload_supporting/<?php echo $row['scan_id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit">
														<i class="fa fa-pencil"></i>
														<a href="javascript:void(0);" data-scan_id="<?= $row['scan_id']; ?>" class="btn btn-default btn-xs" id="delete_all">
															<i class="fa fa-remove"></i>
														</a>
												</td>
											</tr>

											<tr id="detail<?= $row['scan_id'] ?>" class="collapse accordion-collapse" style="background-color: #FEF9E7;">
												<td colspan="6" class="">
													<table class="table table-bordered mytable1" id="subtable<?= $row['scan_id'] ?>" style="background-color:#FEF9E7;margin-bottom:0px;">

														<tbody>
															<?php
															$sql = "SELECT * FROM support_file WHERE Scan_Id = '" . $row['scan_id'] . "'";
															$query = $this->db->query($sql);
															$result = $query->result_array();
															foreach ($result as $rec) {
															?>
																<tr>
																	<td class="mailbox-name">
																		<a href="javascript:void(0);" target="popup" onclick="window.open('<?= $rec['file_path']  ?>','popup','width=600,height=600');"> <?php echo $rec['file_name'] ?></a>

																	</td>

																</tr>
															<?php } ?>
														</tbody>
													</table>
												</td>

											</tr>
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
			</div> <!-- right column -->
		</div> <!-- /.row -->
	</section>
</div>

<script type="text/javascript">
	$('#scan_form').on('submit', function(e) {
		e.preventDefault();
		var form = this;
		$.ajax({
			url: $(form).attr('action'),
			method: $(form).attr('method'),
			data: new FormData(form),
			processData: false,
			dataType: 'json',
			contentType: false,
			success: function(data) {
				alert(data.message);
			}
		});
	});

	$(document).ready(function() {
		$("#location").select2();
		$(document).on('click', '#delete_all', function() {
			var scan_id = $(this).data('scan_id');
			var url = '<?= base_url() ?>Scan/delete_all';
			if (confirm('Are you sure to delete all ?')) {
				$.ajax({
					url: url,
					type: 'POST',
					data: {
						'scan_id': scan_id
					},
					dataType: 'json',
					success: function(data) {
						if (data.status == 200) {
							window.location.href = '<?= base_url() ?>Scan';
						}
					}
				});
			}
		});



	});
</script>
