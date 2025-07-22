
<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Trashed Bills List</h3>
						<?php if ($this->session->flashdata('message')) { ?>
							<?php echo $this->session->flashdata('message') ?>
						<?php } ?>
					</div>
					<div class="box-body">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Trashed Bills List</div>
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

