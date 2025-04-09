<style type="text/css">
	@media print {

		.no-print,
		.no-print * {
			display: none !important;
		}
	}
</style>
<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-4">
				<!-- Horizontal Form -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Add Hotel</h3>
					</div>
					<form id="form1" action="<?= base_url(); ?>Hotel/create" id="hotel_form" name="hotel_form"
						  method="post" accept-charset="utf-8">
						<div class="box-body">
							<?php if ($this->session->flashdata('message')) { ?>
								<?php echo $this->session->flashdata('message') ?>
							<?php } ?>

							<div class="form-group">
								<label for="exampleInputEmail1">Hotel Name :</label> <span class="text-danger">*</span>
								<input autofocus="" id="hotel_name" name="hotel_name" placeholder="" type="text"
									   class="form-control" value="<?php echo set_value('hotel_name'); ?>"/>
								<span class="text-danger"><?php echo form_error('hotel_name'); ?></span>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Address :</label> <span class="text-danger">*</span>
								<input autofocus="" id="address" name="address" placeholder="" type="text"
									   class="form-control" value="<?php echo set_value('address'); ?>"/>
								<span class="text-danger"><?php echo form_error('address'); ?></span>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">City :</label> <span class="text-danger">*</span>
								<input autofocus="" id="city_name" name="city_name" placeholder="" type="text"
									   class="form-control" value="<?php echo set_value('city_name'); ?>"/>
								<span class="text-danger"><?php echo form_error('city_name'); ?></span>
							</div>

							<div class="form-group">
								<label for="exampleInputEmail1">State :</label> <span class="text-danger">*</span>
								<select class="form-control form-select" id="state_id" name="state_id">
									<option value="">Select</option>
									<?php foreach ($state_list as $key => $value) { ?>
										<option value="<?php echo $value['state_id'] ?>"><?php echo $value['state_name'] ?></option>
									<?php } ?>
								</select>
								<span class="text-danger"><?php echo form_error('state_id'); ?></span>
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

				<div class="box box-primary" id="exphead">
					<div class="box-header ptbnull">
						<h3 class="box-title titlefix">Hotel List</h3>
					</div>
					<div class="box-body  ">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Hotel List</div>
							<table class="table table-striped table-bordered table-hover example">
								<thead>
								<tr>
									<th>Hotel Name</th>
									<th>Address</th>
									<th>City</th>
									<th>State</th>
									<th>Status</th>
									<th class="text-right no-print">Action</th>
								</tr>
								</thead>
								<tbody>
								<?php if (empty($hotellist)) {
									?>

									<?php
								} else {

									$count = 1;
									foreach ($hotellist as $row) {
										?>
										<tr>
											<td class="mailbox-name">
												<?php echo $row['hotel_name']; ?>
											</td>
											<td class="mailbox-name">
												<?php echo $row['address']; ?>
											</td>
											<td class="mailbox-name">
												<?php echo $row['city_name']; ?>
											</td>
											<td class="mailbox-name">
												<?php echo $row['state_name']; ?>
											</td>

											<td class="mailbox-name">
												<?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
											</td>
											<td class="mailbox-date pull-right no-print">

												<a href="<?= base_url(); ?>Hotel/edit/<?php echo $row['hotel_id'] ?>"
												   class="btn btn-default btn-xs">
													<i class="fa fa-pencil"></i>
												</a>
												<a href="<?= base_url(); ?>Hotel/delete/<?php echo $row['hotel_id'] ?>"
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
		$('#state_id').select2();

	});
</script>
