
<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">All Trashed Bills</h3>
						<?php if ($this->session->flashdata('message')) { ?>
							<?php echo $this->session->flashdata('message') ?>
						<?php } ?>
					</div>
					<div class="box-body">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">All Trashed Bills</div>
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
                                    <th>Deleted By</th>
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
										<?php 
											
											if (!empty($row['Bill_Approver_Date'])) {
												echo date('d-m-Y', strtotime($row['Bill_Approver_Date']));
											} else {
												echo ''; 
											}
										?>
									</td>

									<td class="mailbox-name">
										<?php echo $row['Bill_Approver_Remark']; ?>
									</td>
                                    <td class="mailbox-name">
										<?php echo $row['deleted_by_name']; ?>
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

