<div class="content-wrapper" style="min-height: 946px;">
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary" id="exphead">
					<div class="box-header with-border">
						<h3 class="box-title titlefix">Employee List (with Company & Status Details)</h3>
						<div class="box-tools pull-right">
							<div class="btn-group pull-right">
								<button type="button" class="btn btn-primary btn-sm"
									id="sync"><i class="fa fa-refresh"></i> Sync Employee
								</button>
							</div>
						</div>
					</div>
					<div class="box-body">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Employee List (with Company & Status Details)</div>
							<table id="employeeTable" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th style="text-align: left;">S No.</th>
										<th style="text-align: left;">Employee</th>
										<th style="text-align: center;">Emp Code</th>
										<th style="text-align: center;">Company Code</th>
										<th style="text-align: center;">Status</th>
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
												<td class="mailbox-name" style="text-align: left;">
													<?php echo $count++; ?>
												<td class="mailbox-name" style="text-align: left;">
													<?php echo $row['emp_name']; ?>
												</td>
												<td class="mailbox-name" style="text-align: center;">
													<?php echo $row['emp_code']; ?>
												</td>
												<td class="mailbox-name" style="text-align: center;">
													<?php echo $row['company_code']; ?>
												</td>
												<td class="mailbox-name" style="text-align: center;">
													<?php if ($row['status'] == 'A'): ?>
														<span class="label label-success">Active</span>
													<?php else: ?>
														<span class="label label-danger">Inactive</span>
													<?php endif; ?>
												</td>
											</tr>
											<?php
										}
										$count++;
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="importModal" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title title text-center fees_title"><a
						href="<?= base_url() ?>/assets/import_sample/employee.csv" download="">
						<button class="btn btn-warning btn-sm"><i class="fa fa-download"></i> Sample
							Import File</button>
					</a>
				</h4>
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
		$("#employeeTable").DataTable({
			"order": [],
			"pageLength": 10,
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}],
			"language": {
				"emptyTable": "No data available in table"
			}
		});
		$(document).on("click", "#sync", function () {
			if (confirm("Are you sure to sync!")) {
				var baseUrl = $("#base_url").val();
				$.ajax({
					type: "GET",
					url: baseUrl + "master/EmployeeController/sync_employee",
					dataType: "json",
					beforeSend: function () {
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