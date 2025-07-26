<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"><?= !empty($item['item_id']) ? 'Edit Item' : 'Add Item' ?></h3>
					</div>
					<form id="item_form"
						action="<?= base_url('save_item' . (!empty($item['item_id']) ? '/' . $item['item_id'] : '')) ?>"
						name="item_form" method="post" accept-charset="utf-8">
						<div class="box-body">
							<?php if ($this->session->flashdata('message')) { ?>
								<?php echo $this->session->flashdata('message') ?>
							<?php } ?>
							<div class="form-group">
								<label for="item_name">Item Name <span class="text-danger">*</span></label>
								<input autofocus id="item_name" required name="item_name" type="text"
									class="form-control"
									value="<?= set_value('item_name', !empty($item['item_name']) ? htmlspecialchars($item['item_name']) : '') ?>" />
								<span class="text-danger"><?php echo form_error('item_name'); ?></span>
							</div>
							<div class="form-group">
								<label for="item_code">Item Code <span class="text-danger">*</span></label>
								<input id="item_code" required name="item_code" type="text" class="form-control"
									value="<?= set_value('item_code', !empty($item['item_code']) ? htmlspecialchars($item['item_code']) : '') ?>" />
								<span class="text-danger"><?php echo form_error('item_code'); ?></span>
							</div>
							<div class="form-group">
								<label for="status">Status <span class="text-danger">*</span></label>
								<select name="status" required id="status" class="form-control">
									<option value="A" <?= set_select('status', 'A', !empty($item['status']) && $item['status'] == 'A') ?>>Active</option>
									<option value="D" <?= set_select('status', 'D', !empty($item['status']) && $item['status'] == 'D') ?>>Deactive</option>
								</select>
								<span class="text-danger"><?php echo form_error('status'); ?></span>
							</div>
						</div>
						<div class="box-footer">
							<button type="submit" class="btn btn-info pull-right">Save</button>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-9">
				<div class="box" id="exphead">
					<div class="box-header ptbnull">
						<h3 class="box-title titlefix">Item List</h3>
					</div>
					<div class="box-body">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Item List</div>
							<table id="itemTable" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>ID</th>
										<th>Item Name</th>
										<th>Item Code</th>
										<th style="text-align:center;">Status</th>
										<th style="text-align:center;">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (empty($item_list)) {
										?>
										<?php
									} else {

										$count = 1;
										foreach ($item_list as $row) {
											?>
											<tr>
												<td class="mailbox-name">
													<?php echo $row['item_id']; ?>
												</td>
												<td class="mailbox-name">
													<?php echo $row['item_name']; ?>
												</td>
												<td class="mailbox-name">
													<?php echo $row['item_code']; ?>
												</td>
												<td style="text-align:center;" class="mailbox-name">
													<?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
												</td>
												<td style="text-align:center;">
													<!-- <a href="javascript:void(0)" class="btn btn-xs btn-default view-item"
														data-id="<?= $row['item_id'] ?>">
														<i class="fa fa-eye" aria-hidden="true"></i>
													</a> -->
													<a href="<?= base_url(); ?>item/<?php echo $row['item_id'] ?>"
														class="btn btn-default btn-xs">
														<i class="fa fa-pencil"></i>
													</a>
													<a href="<?= base_url(); ?>delete_item/<?php echo $row['item_id'] ?>"
														class="btn btn-default btn-xs"
														onclick="return confirm('Are you sure to delete?');">
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
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="itemModalLabel">Item Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered">
					<tr>
						<td style="width: 25%; text-align: left;">Item Name</td>
						<td style="width:75%" id="modal_item_name"></td>
					</tr>
					<tr>
						<td style="width: 25%; text-align: left;">Item Code</td>
						<td style="width:75%" id="modal_item_code"></td>
					</tr>
					<tr>
						<td style="width: 25%; text-align: left;">Status</td>
						<td style="width:75%" id="modal_status_label"></td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$("#itemTable").DataTable({
			paging: true,
			searching: true,
			ordering: true,
			dom: 'Bfrtip',
			pageLength: 10,
			buttons: [
				{
					extend: 'csv',
					text: '<i class="fa fa-file-text-o"></i> Export',
					title: 'Item_List_' + new Date().toISOString().slice(0, 10),
					className: 'btn btn-primary btn-sm',
					exportOptions: {
						columns: ':not(:last-child)'
					}
				}
			]
		});

		$(document).on('click', '.view-item', function () {
			var itemId = $(this).data('id');
			$.ajax({
				url: '<?= base_url("get_item_details/") ?>' + itemId,
				type: 'GET',
				dataType: 'json',
				success: function (response) {
					if (response.error) {
						alert(response.error);
						return;
					}
					$('#modal_item_name').text(response.item_name || 'N/A');
					$('#modal_item_code').text(response.item_code || 'N/A');
					$('#modal_focus_data_label').text(response.focus_data_label || 'N/A');
					$('#modal_status_label').text(response.status_label || 'N/A');
					$('#modal_import_flag_label').text(response.import_flag_label || 'N/A');
					$('#itemModal').modal('show');
				},
				error: function () {
					alert('Error loading item details.');
				}
			});
		});
	});
</script>