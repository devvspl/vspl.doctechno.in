
<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Rejected Bills List</h3>
						<?php if ($this->session->flashdata('message')) { ?>
							<?php echo $this->session->flashdata('message') ?>
						<?php } ?>
					</div>
					<div class="box-body">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Rejected Bills List</div>
							<table class="table table-striped table-bordered table-hover example">
								<thead>
								<tr>
									<th>S.No</th>
									<th>Document Name</th>
									<th>Location</th>
									<th>File</th>
									<th class="no-print">Support</th>
									<th>Rejection Date</th>
									<th>Rejection Remark</th>
									<th>Bill Approver</th>
									<th>Rename Document</th>
									<th>Action</th>

								</tr>
								</thead>
								<tbody>
								<?php if (empty($list)) {
									?>
									<?php
								} else {
								$count = 1;
								foreach ($list as $row) {
								?>
								<tr>
									<td><?php echo $count++; ?></td>
									<td class="mailbox-name">
										<?php echo $row['document_name']; ?>
									</td>
									<td class="mailbox-name">
										<?php echo $this->customlib->get_Location_Name($row['location_id']); ?>
									</td>

									<td class="mailbox-name">
										<a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path']  ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
									</td>

									<td class="mailbox-name text-center no-print">
										<?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
											<a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
										<?php } ?>
									</td>
									<td>
										<?php echo date('d-m-Y', strtotime($row['bill_approved_date'])) ?>
									</td>
									<td class="mailbox-name">
										<?php echo $row['bill_approver_remark']; ?>
									</td>
									<td class="mailbox-name no-print">
										<select name="Scan_id" id="Scan_id_<?= $row['scan_id'] ?>" class="form-control-sm DocType_Id" disabled onchange="changeBillApprover(<?= $row['scan_id'] ?>,this.value)">
											<option value="0">Select</option>
											<?php
											foreach ($bill_approver_list as $value) {
												if ($value['user_id'] == $row['bill_approver_id']) {
													echo "<option value='" . $value['user_id'] . "' selected>" . $value['first_name'] .' '.$value['last_name']. "</option>";
												} else {
													echo "<option value='" . $value['user_id'] . "'>" . $value['first_name'] .' '.$value['last_name']. "</option>";
												}
											}
											?>
										</select>
										<i class="fa fa-pencil-square-o text-primary d-inline" aria-hidden="true" id="doctype_edit_<?= $row['scan_id'] ?>" onclick="editDocType(<?= $row['scan_id'] ?>,this)" style="font-size: 16px;cursor: pointer;"></i>
									</td>
									<td class="text-center">
										<a href="javascript:void(0);" onclick="openFile('<?= base_url(); ?>naming_file/<?= $row['scan_id'] ?>')" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Scan File"><i class="fa fa-pencil"></i> Rename</a>

										<script>
											function openFile(url) {
												window.open(url, '_blank');
											}
										</script>
									</td>
									<td>
										<button class="btn btn-xs btn-success" onclick="resend_rejected_bill(<?=$row['scan_id']?>)">Resend</button>
										<?php
										if ($row['bill_approval_status'] === 'R')
										{
											?>
											<button class="btn btn-xs btn-danger" onclick="trash_rejected_bill(<?=$row['scan_id']?>)">Trash</button>
											<?php
										}
										?>
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

	function editDocType(scan_id, th) {
		$("#Scan_id_" + scan_id).prop('disabled', false);
	}

	function changeBillApprover(scan_id, bill_approver_id) {
		$.ajax({
			url: '<?php echo base_url(); ?>Scan/changeBillApprover',
			type: 'POST',
			data: {
				scan_id: scan_id,
				bill_approver_id: bill_approver_id
			},
			dataType: 'json',
			success: function(response) {
				if (response.status == 200) {
					window.location.reload();
				} else {
					alert('Something Went Wrong');
					window.location.reload();
				}
			}
		});
	}

	function resend_rejected_bill(scan_id){
		$.ajax({
			url: '<?php echo base_url(); ?>Scan/resend_scan_bill',
			type: 'POST',
			data: {
				scan_id: scan_id,
			},
			dataType: 'json',
			success: function(response) {
				if (response.status == 200) {
					window.location.reload();
				} else {
					alert('Something Went Wrong');
					window.location.reload();
				}
			}
		});
	}

	function trash_rejected_bill(scan_id) {
		if (confirm('Are you sure you want to trash this bill?')) {
			$.ajax({
				url: '<?php echo base_url(); ?>Scan/trash_scan_bill',
				type: 'POST',
				data: {
					scan_id: scan_id,
				},
				dataType: 'json',
				success: function(response) {
					if (response.status == 200) {
						alert('Bill has been successfully trashed.');
						window.location.reload();
					} else {
						alert('Something went wrong');
						window.location.reload();
					}
				},
				error: function() {
					alert('Request failed');
					window.location.reload();
				}
			});
		} else {
			// Action canceled by user
			console.log('Action canceled');
		}
	}


</script>
