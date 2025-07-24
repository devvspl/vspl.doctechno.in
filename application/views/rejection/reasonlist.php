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
			<div class="col-md-4">
				<!-- Horizontal Form -->
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Add Rejection Reason</h3>
					</div>
					<form id="form1" action="<?= base_url(); ?>master/RejectionReasonController/create" id="add_form" name="add_form"
						  method="post" accept-charset="utf-8">
						<div class="box-body">
							<?php if ($this->session->flashdata('message')) { ?>
								<?php echo $this->session->flashdata('message') ?>
							<?php } ?>

							<div class="form-group">
								<label for="exampleInputEmail1">Rejection Reason :</label> <span class="text-danger">*</span>
								<input autofocus="" id="reason" name="reason" placeholder="" type="text"
									   class="form-control" value="<?php echo set_value('reason'); ?>"/>
								<span class="text-danger"><?php echo form_error('reason'); ?></span>
							</div>

							<div class="form-group">
								<label for="exampleInputEmail1">Status</label>
								<select name="status" id="status" class="form-control">
									<option value="A">Active</option>
									<option value="D">Deactive</option>
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

			<div class="col-md-8">

				<div class="box" id="exphead">
					<div class="box-header ptbnull">
						<h3 class="box-title titlefix">Rejection Reason List</h3>
					</div>
					<div class="box-body  ">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Hotel List</div>
							<table class="table table-striped table-bordered table-hover example">
								<thead>
								<tr>
									<th>Rejection Reason</th>
									<th>Status</th>
									<th class="text-right no-print">Action</th>
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

												<a href="<?= base_url(); ?>master/RejectionReasonController/edit/<?php echo $row['id'] ?>"
												   class="btn btn-default btn-xs">
													<i class="fa fa-pencil"></i>
												</a>
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
