<div class="content-wrapper" >
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Bill Approval Report:</h3>
					</div>
					<form role="form" action="<?= base_url() ?>bill_approval_report" method="post">
						<div class="box-body row">
							<div class="col-sm-2 col-md-2">
								<div class="form-group">
									<label>From Date :</label>
									<input type="date" autocomplete="off" name="from_date" id="from_date"
										   class="form-control" value="<?= set_value('from_date') ?>">
								</div>
							</div>
							<div class="col-sm-2 col-md-2">
								<div class="form-group">
									<label>To Date :</label>
									<input type="date" autocomplete="off" name="to_date" id="to_date"
										   class="form-control" value="<?= set_value('to_date') ?>">
								</div>
							</div>
							<div class="col-sm-2 col-md-2">
								<div class="form-group">
									<label for="location"> Location:</label>
									<select name="location" id="location" class="form-control form-control-sm">
										<option value="">Select</option>
										<?php foreach ($location_list as $key => $value) { ?>
											<option value="<?= $value['location_id']; ?>" <?php if (set_value('location') == $value['location_id']) {
												echo "selected";
											} ?>><?= $value['location_name'] ?></option>
										<?php } ?>
									</select>
								</div>
								<span class="text-danger"><?php echo form_error('Group'); ?></span>
							</div>
							<div class="col-sm-2 col-md-2">
								<div class="form-group">
									<label for="bill_approver">Bill Approver</label>
									<select name="bill_approver" id="bill_approver" class="form-control form-control-sm">
										<option value="">Select</option>
										<?php foreach ($bill_approver_list as $key => $value) { ?>
											<option value="<?= $value['user_id']; ?>" <?php if (set_value('bill_approver') == $value['user_id']) {
												echo "selected";
											} ?>><?= $value['first_name'].' '.$value['last_name'] ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-sm-2 col-md-2">
								<div class="form-group">
									<label for="status">Status:</label>
									<select name="status" id="status" class="form-control form-control-sm">
										<option value="">Select</option>
										<option value="Y" <?php if (set_value('status') == 'Y') {echo "selected";}?>>Approved</option>
										<option value="R" <?php if (set_value('status') == 'R') {echo "selected";}?>>Rejected</option>
										<option value="N" <?php if (set_value('status') == 'N') {echo "selected";}?>>Pending</option>
									</select>
								</div>
							</div>
							<div class="col-sm-2 col-md-2">
								<div class="form-group">
									<label for="last_day">Pending Since:</label>
									<select name="last_day" id="last_day" class="form-control form-control-sm">
										<option value="">Select</option>
										<option value="5" <?php if (set_value('last_day') == '5') {echo "selected";}?>>5 Days</option>
										<option value="7" <?php if (set_value('last_day') == '7') {echo "selected";}?>>7 Days</option>
										<option value="10" <?php if (set_value('last_day') == '10') {echo "selected";}?>>10 Days</option>
									</select>
								</div>
							</div>
							<div class="col-sm-12 col-md-12 float-end">
								<div class="form-group" style="margin-top: 10px;float: right;">
									<button type="submit" id="search" name="search"
											class="btn btn-primary btn-sm checkbox-toggle "><i class="fa fa-search"></i>
										Search
									</button>
									<button type="button" id="reset" name="reset" onclick="reloadPage();"
											class="btn btn-primary btn-sm checkbox-toggle "><i
											class="fa fa-refresh"></i> Reset
									</button>
								</div>
							</div>
						</div>
					</form>
					<div class="box-body">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Scan Punch Report</div>
							<table class="table table-striped table-bordered table-hover example">
								<thead>
								<tr>
									<th>S.No</th>
									<th>Location</th>
									<th>Document Name</th>
									<th>File</th>
									<th>Temp Scan Date</th>
									<th>Temp Scan By</th>
									<th>Scan By</th>
									<th>Scan Date</th>
									<th>Bill Approved</th>
									<th>Bill Approved By</th>
									<th>Bill Approval Date</th>
									<th>Remark</th>
								</tr>
								</thead>
								<tbody>
								<?php if (empty($record_list)) {
									?>
									<?php
								} else {
								$count = 1;
								foreach ($record_list

								as $row) {
								?>
								<tr>
									<td><?php echo $count++; ?></td>
									<td class="mailbox-name">
										<?php echo $this->customlib->get_Location_Name($row['location_id']); ?>
									</td>
									<td class="mailbox-name">
										<?php echo $row['document_name']; ?>
									</td>
									<td class="mailbox-name">
										<a href="javascript:void(0);" target="popup"
										   onclick="window.open('<?= $row['file_path'] ?>','popup','width=600,height=600');"> <?php echo $row['file_name'] ?></a>
									</td>
									<td class="mailbox-name">
										<?php
										if ($row['is_temp_scan'] == 'Y') {
											echo date('d-m-Y', strtotime($row['temp_scan_date']));
										} ?>
									</td>
									<td class="mailbox-name">
										<?php
										if ($row['is_temp_scan'] == 'Y') {
											echo $this->customlib->get_Name($row['temp_scan_by']);
										}
										?>
									</td>

									<td class="mailbox-name">
										<?php
										echo $this->customlib->get_Name($row['scanned_by']);

										?>
									</td>
									<td class="mailbox-name">
										<?php
										if($row['scan_date'] != null){
											echo date('d-m-Y', strtotime($row['scan_date']));
										}
										?>
									</td>
									<td class="text-center">
										<?php if($row['bill_approval_status'] == 'Y'){
											echo 'Yes';
										}elseif ($row['bill_approval_status'] =='R'){
											echo '<span class="badge bg-red">Rejected</span>';
										}else{
											echo 'No';
										}?>
									</td>
									<td class="mailbox-name">
										<?php
										if ($row['bill_approval_status'] == 'Y' || $row['bill_approval_status'] =='R') {
											echo $this->customlib->get_Name($row['bill_approver_id']);
										}else{
											echo '';
										}
										?>
									</td>
									<td class="mailbox-name">
										<?php
										if($row['bill_approval_status'] == 'Y'){
											echo date('d-m-Y', strtotime($row['bill_approved_date']));
										}else{
											echo '';
										}
										?>
									</td>
									<td>
										<?php echo $row['bill_approver_remark'] ?>
									</td>
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
	$("#location").select2();
	function getSupportFile(scan_id) {
		$.ajax({
			url: '<?php echo base_url(); ?>Punch/getSupportFile',
			type: 'POST',
			data: {
				scan_id: scan_id
			},
			dataType: 'json',
			success: function (response) {

				if (response.status == 200) {

					var x = '';
					$.each(response.data, function (index, value) {
						/*  x += '<div class="col-md-4">';
						 x += '<div class="form-group">';
						 x += '<a href="javascript:void(0);" target="popup" onclick="window.open(\'' + value.file_path + '\',\'popup\',\'width=600,height=600\');">' + value.File + '</a>';
						 x += '</div>';
						 x += '</div>'; */
						x += '<object data="' + value.file_path + '" type="application/pdf" width="100%" height="500px"></object>';

					});
					$('#detail').html(x);
					$('#SupportFileView').modal('show');
				}


			}
		});
	}

	function reloadPage() {
		window.location.href = "<?php echo base_url(); ?>bill_approval_report";
	}


</script>
