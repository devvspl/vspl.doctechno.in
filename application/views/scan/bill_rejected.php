
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
										<?php echo $row['Document_Name']; ?>
									</td>
									<td class="mailbox-name">
										<?php echo $this->customlib->get_Location_Name($row['Location']); ?>
									</td>

									<td class="mailbox-name">
										<a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['File_Location']  ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
									</td>

									<td class="mailbox-name text-center no-print">
										<?php if ($this->customlib->haveSupportFile($row['Scan_Id']) == 1) { ?>
											<a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['Scan_Id'] ?>)"><i class="fa fa-eye"></i></a>
										<?php } ?>
									</td>
									<td>
										<?php echo date('d-m-Y', strtotime($row['Bill_Approver_Date'])) ?>
									</td>
									<td class="mailbox-name">
										<?php echo $row['Bill_Approver_Remark']; ?>
									</td>
									<td class="mailbox-name no-print">
										<select name="Scan_id" id="Scan_id_<?= $row['Scan_Id'] ?>" class="form-control-sm DocType_Id" disabled onchange="changeBillApprover(<?= $row['Scan_Id'] ?>,this.value)">
											<option value="0">Select</option>
											<?php
											foreach ($bill_approver_list as $value) {
												if ($value['user_id'] == $row['Bill_Approver']) {
													echo "<option value='" . $value['user_id'] . "' selected>" . $value['first_name'] .' '.$value['last_name']. "</option>";
												} else {
													echo "<option value='" . $value['user_id'] . "'>" . $value['first_name'] .' '.$value['last_name']. "</option>";
												}
											}
											?>
										</select>
										<i class="fa fa-pencil-square-o text-primary d-inline" aria-hidden="true" id="doctype_edit_<?= $row['Scan_Id'] ?>" onclick="editDocType(<?= $row['Scan_Id'] ?>,this)" style="font-size: 16px;cursor: pointer;"></i>
									</td>
									<td class="text-center">
										<a href="javascript:void(0);" onclick="openFile('<?= base_url(); ?>naming_file/<?= $row['Scan_Id'] ?>')" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Scan File"><i class="fa fa-pencil"></i> Rename</a>

										<script>
											function openFile(url) {
												window.open(url, '_blank');
											}
										</script>
									</td>
									<td>
										<button class="btn btn-xs btn-success" onclick="resend_rejected_bill(<?=$row['Scan_Id']?>)">Resend</button>
										<?php
										if ($row['Bill_Approved'] === 'R')
										{
											?>
											<button class="btn btn-xs btn-danger" onclick="trash_rejected_bill(<?=$row['Scan_Id']?>)">Trash</button>
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
	function getSupportFile(Scan_Id) {
		$.ajax({
			url: '<?php echo base_url(); ?>Punch/getSupportFile',
			type: 'POST',
			data: {
				Scan_Id: Scan_Id
			},
			dataType: 'json',
			success: function(response) {

				if (response.status == 200) {

					var x = '';
					$.each(response.data, function(index, value) {

						x += '<object data="' + value.File_Location + '" type="application/pdf" width="100%" height="500px"></object>';

					});
					$('#detail').html(x);
					$('#SupportFileView').modal('show');
				}


			}
		});
	}

	function editDocType(Scan_Id, th) {
		$("#Scan_id_" + Scan_Id).prop('disabled', false);
	}

	function changeBillApprover(Scan_Id, Bill_Approver) {
		$.ajax({
			url: '<?php echo base_url(); ?>Scan/changeBillApprover',
			type: 'POST',
			data: {
				Scan_Id: Scan_Id,
				Bill_Approver: Bill_Approver
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

	function resend_rejected_bill(Scan_Id){
		$.ajax({
			url: '<?php echo base_url(); ?>Scan/resend_scan_bill',
			type: 'POST',
			data: {
				Scan_Id: Scan_Id,
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

	function trash_rejected_bill(Scan_Id) {
		if (confirm('Are you sure you want to trash this bill?')) {
			$.ajax({
				url: '<?php echo base_url(); ?>Scan/trash_scan_bill',
				type: 'POST',
				data: {
					Scan_Id: Scan_Id,
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
