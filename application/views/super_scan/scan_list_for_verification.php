<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Pending Scan Files For Document Naming</h3>
						<?php if ($this->session->flashdata('message')) { ?>
							<?php echo $this->session->flashdata('message') ?>
						<?php } ?>
					</div>
					<div class="box-body">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Resend Scan Files</div>
							<table class="table table-bordered table-hover example">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Document Name</th>
										<th>File</th>
										<th>Scan By</th>
										<th>Scan Date</th>
										<th class="no-print">Support</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (empty($scan_list_for_verification)) {
									?>
										<?php
									} else {
										$count = 1;
										foreach ($scan_list_for_verification as $row) {
										?>
											<tr>
												<td><?php echo $count++; ?></td>

												<td class="mailbox-name">
													<?php echo $row['document_name']; ?>
												</td>
												<td class="mailbox-name">

													<a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
												</td>
												<td class="mailbox-name">
													<?php echo $this->customlib->get_Name($row['temp_scan_by']); ?>
												</td>

												<td class="mailbox-name">
													<?php echo date('d-m-Y', strtotime($row['temp_scan_date'])) ?>
												</td>

												<td class="mailbox-name text-center no-print">
													<?php if ($this->customlib->haveSupportFile($row['scan_id']) == 1) { ?>
														<a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['scan_id'] ?>)"><i class="fa fa-eye"></i></a>
													<?php } ?>
												</td>

												<td>

													<button type="button" class="btn btn-sm btn-success" onclick="document_verify(<?= $row['scan_id'] ?>)"><i class="fa fa-check"></i> Verify</button>

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

						x += '<object data="' + value.File_Location +
							'" type="application/pdf" width="100%" height="500px"></object>';

					});
					$('#detail').html(x);
					$('#SupportFileView').modal('show');
				}


			}
		});
	}

	function document_verify(Scan_Id) {
		if (confirm("Are you sure to verified this document")) {
			$.ajax({
				url: '<?php echo base_url(); ?>Super_scan/verify_document',
				type: 'POST',
				data: {
					Scan_Id: Scan_Id
				},
				dataType: 'json',
				success: function(response) {
					if (response.status == 200) {
						alert("Document Verified Successfully...!!!");
						setTimeout(() => {
							window.location.reload();
						}, 700);
					} else {
						alert("Something went wrong, Please try again.");
					}
				}
			});
		} else {
			window.location.reload();
		}
	}
</script>
