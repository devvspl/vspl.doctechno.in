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
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Update Employee</h3>
					</div><!-- /.box-header -->
					<!-- form start -->
					<form id="form1" action="<?= base_url(); ?>master/EmployeeController/update/<?= $id ?>" id="emp_form" name="emp_form"
						  method="post" accept-charset="utf-8">
						<div class="box-body">
							<?php if ($this->session->flashdata('message')) { ?>
								<?php echo $this->session->flashdata('message') ?>
							<?php } ?>

							<div class="form-group">
								<label for="exampleInputEmail1">Employee Name</label>
								<input autofocus="" id="emp_name" name="emp_name" placeholder="" type="text"
									   class="form-control"
									   value="<?php echo set_value('emp_name', $employee['emp_name']); ?>"/>
								<span class="text-danger"><?php echo form_error('emp_name'); ?></span>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Employee Name</label>
								<input autofocus="" id="emp_code" name="emp_code" placeholder="" type="text"
									   class="form-control"
									   value="<?php echo set_value('emp_code', $employee['emp_code']); ?>"/>
								<span class="text-danger"><?php echo form_error('emp_code'); ?></span>
							</div>


							<div class="form-group">
								<label for="exampleInputEmail1">Status</label>
								<select name="status" id="status" class="form-control">
									<option value="">Select</option>
									<option value="A" <?php if (set_value('status', $employee['status']) == 'A') {
										echo "selected";
									} ?>>Active
									</option>
									<option value="D" <?php if (set_value('status', $employee['status']) == 'D') {
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
				<div class="box box-primary" id="exphead">
					<div class="box-header ptbnull">
						<h3 class="box-title titlefix">Employee List</h3>
						<div class="box-tools pull-right">
							<a href="<?= base_url(); ?>employee" class="btn btn-primary btn-sm"><i
										class="fa fa-long-arrow-left"></i> Back</a>
						</div>
					</div><!-- /.box-header -->
					<div class="box-body  ">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Employee List</div>
							<table class="table table-striped table-bordered table-hover example">
								<thead>
								<tr>
									<th>Employee Name</th>
									<th>Emp Code</th>
									<th>Status</th>
									<th class="text-right no-print">Delete</th>
								</tr>
								</thead>
								<tbody>
								<?php if (empty($employeelist)) {
									?>

									<?php
								} else {
									$count = 1;
									foreach ($employeelist as $row) {
										?>
										<tr>
											<td class="mailbox-name">
												<?php echo $row['emp_name']; ?>
											</td>
											<td class="mailbox-name">
												<?php echo $row['emp_code']; ?>
											</td>

											<td class="mailbox-name">
												<?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
											</td>
											<?php if ($row['emp_vspl'] == 'N') { ?>
												<td class="mailbox-date pull-right no-print">
													<a href="<?= base_url(); ?>master/EmployeeController/delete/<?php echo $row['id'] ?>"
													   class="btn btn-default btn-xs"
													   onclick="return confirm('Are you sure to delete');">
														<i class="fa fa-remove"></i>
													</a>

												</td>
											<?php } else { ?>
												<td></td>
											<?php } ?>
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
