<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title"><?= !empty($hotel['hotel_id']) ? 'Edit Hotel' : 'Add Hotel' ?></h3>
					</div>
					<form id="hotel_form"
						action="<?= base_url('save_hotel' . (!empty($hotel['hotel_id']) ? '/' . $hotel['hotel_id'] : '')) ?>"
						name="hotel_form" method="post" accept-charset="utf-8">
						<div class="box-body">
							<?php if ($this->session->flashdata('message')) { ?>
								<?php echo $this->session->flashdata('message') ?>
							<?php } ?>
							<div class="form-group">
								<label for="hotel_name">Hotel Name <span class="text-danger">*</span></label>
								<input autofocus id="hotel_name" required name="hotel_name" type="text"
									class="form-control"
									value="<?= set_value('hotel_name', !empty($hotel['hotel_name']) ? htmlspecialchars($hotel['hotel_name']) : '') ?>" />
								<span class="text-danger"><?php echo form_error('hotel_name'); ?></span>
							</div>

							<div class="row">
								<div class="form-group col-md-6">
									<label for="country_id">Country <span class="text-danger">*</span></label>
									<select name="country_id" required id="country_id" class="form-control">
										<option value="">-- Select Country --</option>
									</select>
									<span class="text-danger"><?php echo form_error('country_id'); ?></span>
								</div>
								<div class="form-group col-md-6">
									<label for="state_id">State <span class="text-danger">*</span></label>
									<select name="state_id" required id="state_id" class="form-control">
										<option value="">-- Select State --</option>
									</select>
									<span class="text-danger"><?php echo form_error('state_id'); ?></span>
								</div>
							</div>
							<div class="form-group">
								<label for="city_name">City <span class="text-danger">*</span></label>
								<input id="city_name" name="city_name" required type="text" class="form-control"
									value="<?= set_value('city_name', !empty($hotel['city_name']) ? htmlspecialchars($hotel['city_name']) : '') ?>" />
								<span class="text-danger"><?php echo form_error('city_name'); ?></span>
							</div>
							<div class="form-group">
								<label for="address">Address</label>
								<input id="address" name="address" type="text" class="form-control"
									value="<?= set_value('address', !empty($hotel['address']) ? htmlspecialchars($hotel['address']) : '') ?>" />
								<span class="text-danger"><?php echo form_error('address'); ?></span>
							</div>
							<div class="form-group">
								<label for="status">Status <span class="text-danger">*</span></label>
								<select name="status" id="status" class="form-control">
									<option value="A" <?= set_select('status', 'A', !empty($hotel['status']) && $hotel['status'] == 'A') ?>>Active</option>
									<option value="D" <?= set_select('status', 'D', !empty($hotel['status']) && $hotel['status'] == 'D') ?>>Deactive</option>
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
						<h3 class="box-title titlefix">Hotel List</h3>
					</div>
					<div class="box-body  ">
						<div class="table-responsive mailbox-messages">
							<div class="download_label">Hotel List</div>
							<table id="hotelTable" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Hotel Name</th>
										<th>Address</th>
										<th>City</th>
										<th style="text-align:center">Status</th>
										<th style="text-align:center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (empty($hotel_list)) {
										?>
										<?php
									} else {

										$count = 1;
										foreach ($hotel_list as $row) {
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

												<td style="text-align:center" class="mailbox-name">
													<?php echo ($row['status'] == 'A') ? 'Active' : 'Deactive' ?>
												</td>
												<td style="text-align:center;">
													<a href="javascript:void(0)" class="btn btn-xs btn-default view-hotel"
														data-id="<?= $row['hotel_id'] ?>">
														<i class="fa fa-eye" aria-hidden="true"></i>
													</a>
													<a href="<?= base_url(); ?>hotel/<?php echo $row['hotel_id'] ?>"
														class="btn btn-default btn-xs">
														<i class="fa fa-pencil"></i>
													</a>
													<a href="<?= base_url(); ?>delete_hotel/<?php echo $row['hotel_id'] ?>"
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
<div class="modal fade" id="hotelModal" tabindex="-1" role="dialog" aria-labelledby="hotelModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="hotelModalLabel">Hotel Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered">
					<tr>
						<td style="width: 25%; text-align: left;">Hotel Name</td>
						<td style="width:75%" id="modal_hotel_name"></td>
					</tr>
					<tr>
						<td style="width: 25%; text-align: left;">Address</td>
						<td style="width:75%" id="modal_address"></td>
					</tr>
					<tr>
						<td style="width: 25%; text-align: left;">Country</td>
						<td style="width:75%" id="modal_country_name"></td>
					</tr>
					<tr>
						<td style="width: 25%; text-align: left;">State</td>
						<td style="width:75%" id="modal_state_name"></td>
					</tr>
					<tr>
						<td style="width: 25%; text-align: left;">City</td>
						<td style="width:75%" id="modal_city_name"></td>
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
		var country_id = "<?= !empty($hotel['state_id']) ? addslashes($this->BaseModel->getData('master_state', ['state_id' => $hotel['state_id']])->row('country_id')) : '' ?>";
		var state_id = "<?= !empty($hotel['state_id']) ? addslashes($hotel['state_id']) : '' ?>";

		$("#hotelTable").DataTable({
			paging: true,
			searching: true,
			ordering: true,
			dom: 'Bfrtip',
			pageLength: 10,
			buttons: [
				{
					extend: 'csv',
					text: '<i class="fa fa-file-text-o"></i> Export',
					title: 'Hotel_List_' + new Date().toISOString().slice(0, 10),
					className: 'btn btn-primary btn-sm',
					exportOptions: {
						columns: ':not(:last-child)'
					}
				}
			]
		});

		getCountry(country_id, state_id);

		$('#country_id').on('change', function () {
			let countryId = $(this).val();
			if (countryId) {
				getState(countryId, '');
			} else {
				$('#state_id').html('<option value="">-- Select State --</option>');
			}
		});

		$(document).on('click', '.view-hotel', function () {
			var hotelId = $(this).data('id');
			$.ajax({
				url: '<?= base_url("get_hotel_details/") ?>' + hotelId,
				type: 'GET',
				dataType: 'json',
				success: function (response) {
					if (response.error) {
						alert(response.error);
						return;
					}
					$('#modal_hotel_name').text(response.hotel_name || 'N/A');
					$('#modal_address').text(response.address || 'N/A');
					$('#modal_country_name').text(response.country_name || 'N/A');
					$('#modal_state_name').text(response.state_name || 'N/A');
					$('#modal_city_name').text(response.city_name || 'N/A');
					$('#modal_status_label').text(response.status_label || 'N/A');
					$('#hotelModal').modal('show');
				},
				error: function () {
					alert('Error loading hotel details.');
				}
			});
		});
	});

	function getCountry(selectedCountryId, selectedStateId) {
		$.ajax({
			url: '<?= base_url("get-country") ?>',
			type: 'GET',
			dataType: 'json',
			success: function (response) {
				let options = '<option value="">-- Select Country --</option>';
				$.each(response, function (index, country) {
					let selected = (country.api_id == selectedCountryId) ? 'selected' : '';
					options += `<option value="${country.api_id}" ${selected}>${country.country_name} (${country.country_code})</option>`;
				});
				$('#country_id').html(options);
				if (selectedCountryId && selectedStateId) {
					getState(selectedCountryId, selectedStateId);
				}
			},
			error: function () {
				$('#country_id').html('<option value="">Error loading countries</option>');
			}
		});
	}

	function getState(countryId, selectedStateId) {
		$.ajax({
			url: '<?= base_url("get-state") ?>',
			type: 'POST',
			data: {
				country_id: countryId,
				<?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>'
			},
			dataType: 'json',
			success: function (response) {
				let options = '<option value="">-- Select State --</option>';
				$.each(response, function (index, state) {
					let selected = (state.api_id == selectedStateId) ? 'selected' : '';
					options += `<option value="${state.api_id}" ${selected}>${state.state_name} (${state.short_code})</option>`;
				});
				$('#state_id').html(options);
			},
			error: function () {
				$('#state_id').html('<option value="">Error loading states</option>');
			}
		});
	}
</script>