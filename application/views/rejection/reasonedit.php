<style type="text/css">
	@media print {

		.no-print,
		.no-print * {
			display: none !important;
		}
	}
</style>
<div class="content-wrapper" >
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<!-- Horizontal Form -->
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Update Reason</h3>
					</div><!-- /.box-header -->
					<!-- form start -->
					<form id="form1" action="<?= base_url(); ?>master/RejectionReasonController/update/<?= $id ?>" id="add_form"
						  name="add_form" method="post" accept-charset="utf-8">
						<div class="box-body">
							<?php if ($this->session->flashdata('message')) { ?>
								<?php echo $this->session->flashdata('message') ?>
							<?php } ?>

							<div class="form-group">
								<label for="exampleInputEmail1">Rejetion Reason</label>
								<input autofocus="" id="reason" name="reason" placeholder="" type="text"
									   class="form-control"
									   value="<?php echo set_value('reason', $data['reason']); ?>"/>
								<span class="text-danger"><?php echo form_error('reason'); ?></span>
							</div>



							<div class="form-group">
								<label for="exampleInputEmail1">Status</label>
								<select name="status" id="status" class="form-control">
									<option value="">Select</option>
									<option value="A" <?php if (set_value('status', $data['status']) == 'A') {
										echo "selected";
									} ?>>Active
									</option>
									<option value="D" <?php if (set_value('status', $data['status']) == 'D') {
										echo "selected";
									} ?>>Deactive
									</option>
								</select>
								<span class="text-danger"><?php echo form_error('status'); ?></span>
							</div>


						</div><!-- /.box-body -->
						<div class="box-footer">
							<button type="submit" class="btn btn-info pull-right">Save</button>
						</div>
					</form>
				</div>
			</div>
			<!--/.col (right) -->
			<!-- left column -->
			<div class="col-md-9">
				<!-- general form elements -->
				<div class="box" id="exphead">
					<div class="box-header ptbnull">
						<h3 class="box-title titlefix">Rejection Reason List</h3>
						<div class="box-tools pull-right">
							<a href="<?= base_url(); ?>hotel" class="btn btn-primary btn-sm"><i
									class="fa fa-long-arrow-left"></i> Back</a>
						</div>
					</div><!-- /.box-header -->
					<div class="box-body  ">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Rejection Reason List</div>
							<table class="table table-striped table-bordered table-hover example">
								<thead>
								<tr>
									<th>Rejection Reason</th>

									<th>Status</th>
									<th class="text-right no-print">Delete</th>
								</tr>
								</thead>
								<tbody>
								<?php if (empty($reason_list)) {
									?>

									<?php
								} else {
									$count = 1;
									foreach ($reason_list as $row) {
										?>
										<tr>
											<td class="mailbox-name">
												<?php echo $row['reason']; ?>
											</td>


											<td class="mailbox-name">
												<?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
											</td>
											<td class="mailbox-date pull-right no-print">
												<a href="<?= base_url(); ?>master/RejectionReasonController/delete/<?php echo $row['id'] ?>"
												   class="btn btn-default btn-xs"
												   onclick="return confirm('Are you sure to delete');">
													<i class="fa fa-remove"></i>
												</a>

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
	</section><!-- /.content -->
</div>
<script>
	$(document).ready(function () {
		$("#state_id").select2();
	});
</script>
