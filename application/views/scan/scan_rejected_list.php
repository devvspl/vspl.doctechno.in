<?php 

	$group_id =$this->uri->segment(2);
	
?>
<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Resend Scan Files</h3>
						<?php if ($this->session->flashdata('message')) { ?>
							<?php echo $this->session->flashdata('message') ?>
						<?php } ?>
					</div>
					<div class="box-body">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Resend Scan Files</div>
							<table class="table table-striped table-bordered table-hover example">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Document Name</th>
										<th>File</th>
										<th>Resend By</th>
										<th>Resend Date</th>
										<th class="no-print">Support</th>
										<th>Resend Remark</th>
										<th>Rename Document</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (empty($scan_rejected_list)) {
									?>
										<?php
									} else {
										$count = 1;
										foreach ($scan_rejected_list as $row) {
										?>
											<tr>
												<td><?php echo $count++; ?></td>
												<td class="mailbox-name">
													<?php echo $row['Document_Name']; ?>
												</td>

												<td class="mailbox-name">
													<a href="javascript:void(0);" target="popup" onclick="window.open('<?= $row['File_Location']  ?>','popup','width=600,height=600');"> <?php echo $row['File'] ?></a>
												</td>
												<td class="mailbox-name">
													<?php echo $this->customlib->get_Name($row['Scan_Resend_By']); ?>
												</td>

												<td class="mailbox-name">
													<?php echo date('d-m-Y', strtotime($row['Scan_Resend_Date'])) ?>
												</td>

												<td class="mailbox-name text-center no-print">
													<?php if ($this->customlib->haveSupportFile($row['Scan_Id']) == 1) { ?>
														<a href="javascript:void(0);" class="btn btn-link btn-xs" onclick="getSupportFile(<?= $row['Scan_Id'] ?>)"><i class="fa fa-eye"></i></a>
													<?php } ?>
												</td>
												<!--<td>-->
												<!--    <a href="<?php echo base_url(); ?>file_detail/<?= $row['Scan_Id'] ?>/<?= $row['DocType_Id'] ?>" class="btn btn-info btn-xs" target="_blank"><i class="fa fa-eye"></i></a>-->
												<!--</td>-->
												<td class="mailbox-name">
													<?php echo $row['Scan_Resend_Remark']; ?>
												</td>
												<td class="text-center">
													<a href="javascript:void(0);" onclick="openFile('<?= base_url(); ?>naming_file/<?= $row['Scan_Id'] ?>')" class="btn btn-success btn-xs" data-toggle="tooltip" title="Scan File"><i class="fa fa-pencil"></i> Rename</a>

													<script>
														function openFile(url) {
															window.open(url, '_blank');
														}
													</script>
												</td>
												<td>
													<?php
													if ($_SESSION['role'] == 'super_scan') {
													?>
														<a href="<?php echo base_url(); ?>Scan/edit_scan/<?php echo $row['Scan_Id']; ?>/<?= $group_id ?>" class="btn btn-danger btn-xs"><i class="fa fa-pencil"></i>Edit</a>
													<?php } else { ?>
														<a href="<?php echo base_url(); ?>Scan/edit_scan/<?php echo $row['Scan_Id']; ?>" class="btn btn-danger btn-xs"><i class="fa fa-pencil"></i>Edit</a>
													<?php } ?>
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
</script>
