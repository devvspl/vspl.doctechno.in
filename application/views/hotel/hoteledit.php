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
			<div class="col-md-3">
				<!-- Horizontal Form -->
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Update Hotel</h3>
					</div><!-- /.box-header -->
					<!-- form start -->
					<form id="form1" action="<?= base_url(); ?>master/HotelController/update/<?= $hotel_id ?>" id="hotel_form"
						  name="hotel_form" method="post" accept-charset="utf-8">
						<div class="box-body">
							<?php if ($this->session->flashdata('message')) { ?>
								<?php echo $this->session->flashdata('message') ?>
							<?php } ?>

							<div class="form-group">
								<label for="exampleInputEmail1">Hotel Name</label>
								<input autofocus="" id="hotel_name" name="hotel_name" placeholder="" type="text"
									   class="form-control"
									   value="<?php echo set_value('hotel_name', $hotel['hotel_name']); ?>"/>
								<span class="text-danger"><?php echo form_error('hotel_name'); ?></span>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Address</label>
								<input autofocus="" id="address" name="address" placeholder="" type="text"
									   class="form-control"
									   value="<?php echo set_value('address', $hotel['address']); ?>"/>
								<span class="text-danger"><?php echo form_error('address'); ?></span>
							</div>

							<div class="form-group">
								<label for="exampleInputEmail1">City Name</label>
								<input autofocus="" id="city_name" name="city_name" placeholder="" type="text"
									   class="form-control"
									   value="<?php echo set_value('city_name', $hotel['city_name']); ?>"/>
								<span class="text-danger"><?php echo form_error('city_name'); ?></span>
							</div>

							<div class="form-group">
								<label for="exampleInputEmail1">State :</label> <span class="text-danger">*</span>
								<select class="form-control form-select" id="state_id" name="state_id">
									<option value="">Select</option>
									<?php
									foreach ($state_list as $key => $value) {
										if ($value['state_id'] == $hotel['state_id']) {
											echo "<option value='" . $value['state_id'] . "' selected>" . $value['state_name'] . "</option>";
										} else {
											echo "<option value='" . $value['state_id'] . "'>" . $value['state_name'] . "</option>";
										}
									}
									?>
								</select>
								<span class="text-danger"><?php echo form_error('state_id'); ?></span>
							</div>


							<div class="form-group">
								<label for="exampleInputEmail1">Status</label>
								<select name="status" id="status" class="form-control">
									<option value="">Select</option>
									<option value="A" <?php if (set_value('status', $hotel['status']) == 'A') {
										echo "selected";
									} ?>>Active
									</option>
									<option value="D" <?php if (set_value('status', $hotel['status']) == 'D') {
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
						<h3 class="box-title titlefix">Hotel List</h3>
						<div class="box-tools pull-right">
							<a href="<?= base_url(); ?>hotel" class="btn btn-primary btn-sm"><i
										class="fa fa-long-arrow-left"></i> Back</a>
						</div>
					</div><!-- /.box-header -->
					<div class="box-body  ">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Hotel List</div>
							<table class="table table-striped table-bordered table-hover example">
								<thead>
								<tr>
									<th>Hotel Name</th>
									<th>Address</th>
									<th>City Name</th>
									<th>State</th>
									<th>Status</th>
									<th class="text-right no-print">Delete</th>
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
												<a href="<?= base_url(); ?>master/HotelController/delete/<?php echo $row['hotel_id'] ?>"
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
