<style type="text/css">
	<blade media|%20print%20%7B>.no-print,
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
						<h3 class="box-title">Add Employee</h3>
					</div>
					<form id="form1" action="<?= base_url(); ?>master/EmployeeController/create" id="emp_form" name="emp_form"
						method="post" accept-charset="utf-8">
						<div class="box-body">
							<?php if ($this->session->flashdata('message')) { ?>
							<?php echo $this->session->flashdata('message') ?>
							<?php } ?>

							<div class="form-group">
								<label for="exampleInputEmail1">Employee Name</label>
								<input autofocus="" id="emp_name" name="emp_name" placeholder="" type="text"
									class="form-control" value="<?php echo set_value('emp_name'); ?>" />
								<span class="text-danger"><?php echo form_error('emp_name'); ?></span>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Employee Code</label>
								<input type="text" id="emp_code" name="emp_code" class="form-control">
								<span class="text-danger"><?php echo form_error('emp_code'); ?></span>
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

			<div class="col-md-9">

				<div class="box box-primary" id="exphead">
					<div class="box-header ptbnull">
						<h3 class="box-title titlefix">Employee List</h3>
						<div class="box-tools pull-right">
							<div class="btn-group pull-right">
								<button type="button" class="btn btn-default btn-sm" style="margin-right: 15px;"
									data-toggle="modal" data-target="#importModal"><i class="fa fa-file-excel-o"></i>
									Import
								</button>

								<button type="button" class="btn btn-default btn-sm" id="sync"><i
										class="fa fa-list"></i> Sync
								</button>

							</div>
						</div>
						<div class="box-body">
							<div class="table-responsive mailbox-messages">
								<div class="download_label">Employee List</div>
								<table class="table table-striped table-bordered table-hover example">
									<thead>
										<tr>
											<th>Employee</th>
											<th>Emp Code</th>
											<th>Company Code</th>
											<th>Status</th>
											<th class="text-right no-print">Action</th>
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
												<?php echo $row['company_code']; ?>
											</td>
											<td class="mailbox-name">
												<?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
											</td>
											<?php if($row['emp_vspl']=='N'){ ?>
											<td class="mailbox-date pull-right no-print">

												<a href="<?= base_url(); ?>employee/edit/<?php echo $row['id'] ?>"
													class="btn btn-default btn-xs">
													<i class="fa fa-pencil"></i>
												</a>
												<a href="<?= base_url(); ?>employee/delete/<?php echo $row['id'] ?>"
													class="btn btn-default btn-xs"
													onclick="return confirm('Are you sure to delete');">
													<i class="fa fa-remove"></i>
												</a>

											</td>
											<?php } else{?>
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
<div class="modal fade" id="importModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title title text-center fees_title"><a href="<?= base_url() ?>/assets/import_sample/employee.csv" download="">
									<button class="btn btn-warning btn-sm"><i class="fa fa-download"></i> Sample
										Import File</button>
								</a></h4>
			</div>
			<form action="<?= base_url() ?>employee_import" id="importform" name="importform" method="post"
				enctype="multipart/form-data">
				<div class="modal-body pb0">
					<div class="form-horizontal balanceformpopup">
						<div class="box-body">
						
							<div class="row">
								<div class="col-md-12">
									<label for="file">Select CSV File</label><small class="req"> *</small>
									<input class="filestyle form-control" type='file' name='file' id="file"
										accept=".csv" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancle</button>
					<input type="submit" class="btn btn-info pull-right" name="importSubmit" value="Import Employee">
				</div>
			</form>
		</div>
	</div>
</div>
<input type="hidden" id="base_url" value="<?= base_url(); ?>">
<script>
	$(document).ready(function () {
		$(document).on("click", "#sync", function () {
			if (confirm("Are you sure to sync!")) {
				var baseUrl = $("#base_url").val();
				$.ajax({
					type: "GET",
					url: baseUrl + "Employee/sync_employee",
					dataType: "json",
					beforeSend: function () {
						// setting a timeout
						$("#sync").html('<i class="fa fa-spinner fa-spin"></i> Syncing');
					},
					success: function (response) {
						if (response.status == 200) {

							alert("Employee Sync Successfully");
							location.reload();

						} else {

							alert("something went wrong...!!");
						}
					},
				});
			}
		});
	});

</script>
